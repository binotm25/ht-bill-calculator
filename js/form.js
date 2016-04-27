var url = window.location.href;
$(document).ajaxStart(function () {
        $("#loading").show();
        
    }).ajaxStop(function () {
        $("#loading").hide();
    });

    $('.btn-danger').click(function(){
		$('.modal-backdrop').hide();
	});

var id = ""; var int = "";
$("#submit").click(function(){
	$("#submit-yes").show();
	var text = "Are You sure you wanna add this values?";
	$("#modal_alert_words").html(text);
    $("#modalBasic").modal('show');
});
$("#submit-yes").click(function(){
	$('.form-control').each(function(){
		id += $(this).attr('id')+"-"+$(this).val()+",";
	});
	id = id.substring(0, id.length - 1);
	
	$.ajax({
      	type: 'POST',
      	url: url,
      	data: {id:id, table:table, column:column},
      	success:function(data) {
      		if(data != "boom"){
      			var text = "You have successfully added reports into the database. If you have made any mistake go to the update page and edit the column you want.";
      			$("#submit-yes").hide();
      			$("#modalBasicLabel").html("Success");
				$("#modal_alert_words").html(text);
				$("#modalBasic").modal('show');
				$('.btn-danger').click(function(){
					location.reload(true);
				});
      		}else{
      			var text = "Reports cannot inserted right now please try again!";
      			$("#modalBasicLabel").html("Failed");
				$("#modal_alert_words").html(data);
				$("#submit-yes").hide();
				$("#modalBasic").modal('show');
				
      		}
        }
    });
});



$(".logout").click(function(){
	var email = '<?= $_SESSION["email"]; ?>';
	var username = '<?= $_SESSION["username"]; ?>';
	$.ajax( {
	    type:'POST',      
	    url:url,
	    data: {email:email,username:username},
	    success:function(data) {
	        if(data == "LogOut"){
	         	window.location.href = "../log/login";
	        }
	    }
	});
});
