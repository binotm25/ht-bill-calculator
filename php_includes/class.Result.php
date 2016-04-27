<?php
require_once 'vendor/autoload.php';
use Carbon\Carbon;
/**
* 
*/
class result
{
    public $db_conx, $days, $load, $category, $dtr_cap, $t_loss, $t_loss_cal, $max_demand, $ht_single, $fixed_ht;
    public $energy = [];
    
    public function __construct($db_conx)
    {   
        $this->db_conx = $db_conx;
        $sql = "SELECT * FROM energy";
        $query = $db_conx->query($sql);
        while($row = $query->fetch_assoc()){
            $this->energy[] = $row;
        }
    }

    public function calDays($month, $year){
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        return $totalDays;
    }

    /**
     * [Gets all the tables from the Database]
     * @param  [type] $db_conx [mysqli database connection]
     * @return [type] $sql     [the sql syntax that is going to be query]
     */
    // public function getTables($db_conx){
    //     $sql = "SELECT * INFORMATION_SCHEMA.tables WHERE TABLE_NAME LIKE 'sub_%'";
    //     $query = $db_conx->query($sql);
    //     return $query;
    // }
    
    /**
     * Get the names of the substation
     */

    public function getSub(){
        $sql = "SELECT * FROM substation ORDER BY name";
        $query = $this->db_conx->query($sql);
        return $query;
    }

    /**
     * [Gets all the Feeders from each table which will be given by the user]
     * @param  [type] $db_conx [mysqli database connection]
     * @return [type] $sub     [the name of the table for each substation]
     */

    public function getFeeders($db_conx, $sub){
        $table = preg_replace('#[^0-9]#i', '', $sub);
        $result = [];
        $sql = "SELECT * FROM feeders WHERE ss_id = '$sub' ORDER BY feeder";
        $query = $db_conx->query($sql);
        if($query){
            while($row = $query->fetch_assoc()){
                $result[] = [$row['id'] => $row['feeder']];
            }
            return json_encode($result);
        }else{
            return $db_conx->error;
        }
        
    }

    /**
     * Get the total no. of interuptions for each Feeder
     */

    public function getIntHour($db_conx, $feeder, $start, $end, $monthDiff){
        $feeder = preg_replace('#[^0-9]#', '', $feeder);
        $start = preg_replace('#[^0-9-]#', '', $start);
        $end = preg_replace('#[^0-9-]#', '', $end);
        $monthDiff = preg_replace('#[^0-9]#', '', $monthDiff);

        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);
        $firstEndDate = Carbon::create($endDate->year, 0, 1);

        if($monthDiff == 0){
            $table = 'report_'.$startDate->year;
            $sql = "SELECT * FROM $table WHERE id = '$feeder'";
            $query = $db_conx->query($sql);
            $result = $query->fetch_assoc();
            $result = $result[$startDate->month];
            return $result;
        }else{
            if($startDate->year != $endDate->year){
                $table = 'report_'.$startDate->year;
                $sql = "SELECT * FROM $table WHERE id = '$feeder'";
                $query = $db_conx->query($sql);
                $row = $query->fetch_assoc();
                for($i=$startDate->month; $i<13; $i++){
                    $result1[] = [$i => $row[$i]];
                }
                $table = 'report_'.$endDate->year;
                $sql = "SELECT * FROM $table WHERE id = '$feeder'";
                $query = $db_conx->query($sql);
                $row = $query->fetch_assoc();
                for($i=0; $i < $endDate->month; $i++){
                    $newDate = $firstEndDate->addMonths(1);
                    $result2[] = [$newDate->month => $row[$newDate->month]];
                }
                $result = array_merge($result1, $result2);
                return json_encode($result);
            }else{
                $result = [];
                $table = 'report_'.$startDate->year;
                $sql = "SELECT * FROM $table WHERE id = '$feeder'";
                $query = $db_conx->query($sql);
                $row = $query->fetch_assoc();
                $result1[] = [$startDate->month => $row[$startDate->month]];
                for($i=0; $i<$monthDiff; $i++){
                    $newMonth = $startDate->addMonths(1);
                    $result2[] = [$newMonth->month => $row[$newMonth->month]];
                }
                $result = array_merge($result1, $result2);
                $result = json_encode($result);
                return $result;
            }
        }
    }

    /**
     * Resetting the array key while converting the PHP array to JSON to be used by the jquery
     */

    public function resetArrKey($array){
        foreach ($array as $k => $val) {
            if (is_array($val)){
                $array[$k] = $this->resetArrKey($val); //recurse
            }
        }
        return array_values($array);
    }
    

    public function calculate($supply, $category, $load ,$h, $days, $energy, $startPeriod, $endPeriod){
        $startDay = substr($startPeriod, 0, 2); $startMonth = substr($startPeriod, 3, 2); $startYear = substr($startPeriod, 6, 4);
        $endDay = substr($endPeriod, 0, 2); $endMonth = substr($endPeriod, 3, 2); $endYear = substr($endPeriod, 6, 4);
        
        $startPeriod = Carbon::create($startYear, $startMonth);
        $endPeriod = Carbon::create($endYear, $endMonth);

        $monthsDiff = $endPeriod->diffInMonths($startPeriod);
        $endMonthDays = Carbon::create($endYear, $endMonth, 1);
        $endMonthDays = $endMonthDays->endOfMonth()->format('d');
        $startMonthDays = Carbon::create($startYear, $startMonth, 1);
        $startMonthDays = $startMonthDays->endOfMonth()->format('d');

        if($monthsDiff == 0){
            if($startDay == 1 && $endDay == $endMonthDays){
                $fixed = ($energy[$category]['fixed_ht']);
            }else{
                $fixed = ($energy[$category]['fixed_ht']/$endMonthDays)*$days;
            }
        }else{
            $fixed = ($energy[$category]['fixed_ht']/$startMonthDays)*($startMonthDays - $startDay + 1);
            $fixed += ($energy[$category]['fixed_ht']/$endMonthDays)*($endDay);
            
            if($startMonth == 12){
                $startMonth = 0;
            }

            for($i = $startMonth; $i < $monthsDiff; $i++){
                $fixed += ($energy[$category]['fixed_ht']);
            }
        }

        $unit = round(($load * $h * $energy[$category]['f'] * $energy[$category]['d']));
        $f_demand_single = ($fixed * $load);
        $f_demand = round($f_demand_single);
        $sub_total = $unit * $energy[$category]['ht_single'];
        $total = ($f_demand + $sub_total);

        return array($sub_total, $unit, $f_demand);
    }

    public function printResult($cat, $dtr, $hour, $t_loss, $days, $startPeriod, $endPeriod){
        $days       = preg_replace('#[^0-9"]#i', '', $days);
        $category   = preg_replace('#[^0-9"]#i', '', $cat);
        $dtr_cap    = preg_replace('#[^0-9"]#i', '', $dtr);
        $hour       = preg_replace('#[^0-9."]#i', '', $hour);
        $t_loss     = preg_replace('#[^0-9"]#i', '', $t_loss);
        $startPeriod= preg_replace('#[^0-9/]#', '', $startPeriod);
        $endPeriod  = preg_replace('#[^0-9/]#', '', $endPeriod);

        // if(empty($this->days) || empty($this->load) || empty($this->category) || empty($this->dtr_cap) || empty($this->hour) || empty($this->t_loss)){
        //     header('Location : ../index.php');
        //     exit();
        // }

        $load = round(($dtr_cap * .75),2);
        $max_demand = ($load);
        
        $hour = round($hour*0.9,2);
        $fixed_ht =  $this->energy[$category]['fixed_ht'];
        $ht_single = $this->energy[$category]['ht_single'];

        $startDay = substr($startPeriod, 0, 2); $startMonth = substr($startPeriod, 3, 2); $startYear = substr($startPeriod, 6, 4);
        $endDay = substr($endPeriod, 0, 2); $endMonth = substr($endPeriod, 3, 2); $endYear = substr($endPeriod, 6, 4);
        // return $startYear .' - '. $startMonth .' and '. $endYear .' - '. $endMonth;
        // exit();

        $cal = $this->calculate('ht', $category, $load ,$hour, $days, $this->energy, $startPeriod, $endPeriod);
        $startPeriod = Carbon::create($startYear, $startMonth);
        $endPeriod = Carbon::create($endYear, $endMonth);

        $monthsDiff = $endPeriod->diffInMonths($startPeriod);
        $endMonthDays = Carbon::create($endYear, $endMonth, 1);
        $endMonthDays = $endMonthDays->endOfMonth()->format('d');
        $startMonthDays = Carbon::create($startYear, $startMonth, 1);
        $startMonthDays = $startMonthDays->endOfMonth()->format('d');

        if($t_loss == 1){
            $t_loss_c = round((730 * 1.0 * $dtr_cap)/100);    
            if($monthsDiff == 0){
                if($startDay == 1 && $endDay == $endMonthDays){
                    $t_loss_cal = $t_loss_c;
                }else{
                    $t_loss_cal = ($t_loss_c/$endMonthDays)*$days;
                }
            }else{
                $t_loss_cal = ($t_loss_c/$startMonthDays)*($startMonthDays - $startDay + 1);
                $t_loss_cal += ($t_loss_c/$endMonthDays)*($endDay);
                
                if($startMonth == 12){
                    $startMonth = 0;
                }

                for($i = $startMonth; $i < $monthsDiff; $i++){
                    $t_loss_cal += ($t_loss_c);
                }
            }    
        } else{
            $t_loss_cal = 0;
        }
        
        $t_loss_cal = round($t_loss_cal);
        $res = '
        <div class="modal fade" id="modalLarge" tabindex="-1" role="dialog" aria-labelledby="modalLargeLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="modalLargeLabel">Your Total Bill for this period is <code>Rs.</code> '.number_format(round((($cal[1]+$t_loss_cal)*$ht_single)+$cal[2])).' /-</h4>
                    </div>
                    <div class="modal-body text-center">
                        <table align="center" style="margin:0px auto;text-align:left;">';
                            if($t_loss == 1){$res .= '<tr><td>Transformation Loss</td><td> : (730 X 1.0 X DTR Capacity in KVA)/100 X Per Month<td><tr>
                                <tr><td></td><td> : '. ($t_loss_cal) .' Units</td></tr><tr><td colspan="2"> (Which will be added to the Computed Energy Consumption) </td></tr>';}
                            $res .= '<tr>
                                <td>Computed Energy Consumption</td>
                                <td> : (L X H X F X D)  + Transformation loss</td>
                            </tr>
                            <tr>
                                <td>  </td><td>: ('.round($load,2).' X '. $hour .' X '.$this->energy[$category]["f"].' X '.$this->energy[$category]["d"]; if($t_loss_cal != 0){ $res .= " + ".($t_loss_cal);} $res .= ') Units
                                </td>
                            </tr>
                            <tr>
                                <td> </td><td>: '.number_format(round($cal[1])).' + '.$t_loss_cal.' = '. number_format(round($cal[1]) + $t_loss_cal).' Units</td>
                            </tr>
                            <tr>
                                <td>I. Energy Charges</td>
                                <td>: Total Unit Consumption X Tariff Rate of the Category</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>: ' . number_format(round($cal[1]) + $t_loss_cal).' X '.$ht_single.' = Rs. ' . number_format(round(($cal[1] + $t_loss_cal) * $ht_single, 0)) .' /-
                                </td>
                            </tr>
                            <tr>
                                <td>II. Demand Charge</td>
                                <td>: Billing Demand X Fixed Charge X (Per Month.)</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td> : ' .round($load, 2).' X '.$this->energy[$category]['fixed_ht'].' X Period of Bill Calculation = Rs. '.number_format(round($cal[2], 0)).' /-</td>
                            </tr>   
                                
                            </tr>
                            <tr>
                                <td>III. Total Bill</td>
                                <td>: Energy Charges + Demand Charge</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>: Rs. ('.number_format(round(($cal[1] + $t_loss_cal) * $ht_single, 0)).' + '.number_format(round($cal[2], 0)).') /-</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-toggle="modal" href="#modalbig">Calculation Details</button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        ';
        return $res;
    }
}
?>