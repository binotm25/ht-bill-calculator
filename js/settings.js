var url = window.location.href;
$(document).ajaxStart(function () {
    $("#loading").show();
    
}).ajaxStop(function () {
    $("#loading").hide();
});

function addReq(var1, var2, type){
	$.ajax({
		type:'POST',      
        url:url,
        data: {var1:var1,var2:var2},
        success:function(data) {
            if(data == "added"){
            	$("#modalBasicLabel").removeClass('text-danger');
            	$("#modalBasicLabel").addClass('text-success').text('Added In the Database');
            	$("#modal_alert_words").text('The '+type+' has been successfully added in the Database! If you want to add another then continue. If not close the form!');
            	$("#modalBasic").modal('show');
            }else{
            	$("#modalBasicLabel").removeClass('text-success');
            	$("#modalBasicLabel").addClass('text-danger').text('Process failed!');
            	$("#modal_alert_words").text(data);
            	$("#modalBasic").modal('show');
            }
        }
	});
}

$(document).ready(function(){
	$("#gmForFeeder").change(function(){
		$("#sub-form").empty();
		var checkForSub = true;
		var gm = $(this).val();
		if(gm == ""){
			console.log('Ok');
		}else{
			$.ajax({
				type:'POST',
				url:url,
				data:{gm:gm, checkForSub:checkForSub},
				success:function(data){
					var response = $.parseJSON(data);
	                if(typeof response == 'object'){
	                    $.each(response, function(idx, obj){ 
	                        $.each(obj, function(key, value){
	                            $("#sub-form").append('<option value="'+key+'">'+value+'</option>');
	                        });
	                    });
	                    $(".sub-form").show(200);
	                }
				}
			});
		}
	});

	$('button').click(function(){
		var error = false;
		var id = $(this).attr('id');
		if(id == "addSub"){
			$("#modalLarge-addSub").modal('show');
		}else if(id == "addFeeder"){
			$("#modalLarge-addFeeder").modal('show');
		}else if(id == "removeSub"){
			$("#modalLarge-removeSub").modal('show');
		}else if(id == "removeFeeder"){
			$("#modalLarge-removeFeeder").modal('show');
		}else if(id == "addSubForm"){
			var sub_name = $("#sub-name").val();
			var gm = $("#gm").val();
			if(sub_name == "" || sub_name == null){
				$("#sub-name").closest('div').addClass('has-error');
				error = true;
			}
			if(gm == "" || gm == null){
				$("#gm").closest('div').addClass('has-error');
				error = true;
			}
			if(!error){
				$.ajax({
					type:'POST',      
			        url:url,
			        data: {sub_name:sub_name,gm:gm},
			        success:function(data) {
			            if(data == "added"){
			            	$("#modalBasicLabel").removeClass('text-danger');
			            	$("#modalBasicLabel").addClass('text-success').text('Added In the Database');
			            	$("#modal_alert_words").text('The Substation has been successfully added in the Database! If you want to add another then continue. If not close the form!');
			            	$("#modalBasic").modal('show');
			            }else{
			            	$("#modalBasicLabel").removeClass('text-success');
			            	$("#modalBasicLabel").addClass('text-danger').text('Process failed!');
			            	$("#modal_alert_words").text(data);
			            	$("#modalBasic").modal('show');
			            }
			        }
				});
			}
		}else if(id == "addNewFeeder"){
			var sub_id = $("#sub-form").val();
			var feeder_name = $("#feeder-name").val();
			var gmforfeeder = $("#gmForFeeder").val();
			console.log(sub_id, feeder_name, gmforfeeder);
			if(sub_id == "" || sub_id == null){
				$("#sub-form").closest('div').addClass('has-error');
				error = true;
			}
			if(feeder_name == "" || feeder_name == null){
				$("#feeder-name").closest('div').addClass('has-error');
				error = true;
			}
			if( gmforfeeder == "" || gmforfeeder == null){
				$('#gmForFeeder').parent().addClass('has-error');
				error = true;
			}
			if(!error){
				$.ajax({
					type:'POST',      
			        url:url,
			        data: {sub_id:sub_id,feeder_name:feeder_name},
			        success:function(data) {
			            if(data == "added"){
			            	$("#modalBasicLabel").removeClass('text-danger');
			            	$("#modalBasicLabel").addClass('text-success').text('Added In the Database');
			            	$("#modal_alert_words").text('The Feeder has been successfully added in the Database! If you want to add another then continue. If not close the form!');
			            	$("#modalBasic").modal('show');
			            }else{
			            	$("#modalBasicLabel").removeClass('text-success');
			            	$("#modalBasicLabel").addClass('text-danger').text('Process failed!');
			            	$("#modal_alert_words").text(data);
			            	$("#modalBasic").modal('show');
			            }
			        }
				});
			}
			
		}else if(id == "remove-substation"){
			var removeSubId = $("#remove-Sub").val();
			if(removeSub == "" || removeSub == null){
				$("#remove-Sub").closest("div").addClass('has-error');
			}else{
				var wathi_removeSubId = true;
				$.ajax({
					type:'POST',      
			        url:url,
			        data: {removeSubId:removeSubId,wathi_removeSubId:wathi_removeSubId},
			        success:function(data) {
			            if(data == "sent"){
			            	$("#modalBasicLabel").removeClass('text-danger');
			            	$("#modalBasicLabel").addClass('text-success').text('Request has been sent to the Concerned GM.');
			            	$("#modal_alert_words").text('Until the Concerned GM approves the removal of the Sub-Station it won\'t take affect!');
			            	$("#modalBasic").modal('show');
			            }else{
			            	$("#modalBasicLabel").removeClass('text-success');
			            	$("#modalBasicLabel").addClass('text-danger').text('Process failed!');
			            	$("#modal_alert_words").text(data);
			            	$("#modalBasic").modal('show');
			            }
			        }
				});
			}
		}else if(id == "remove-feeder"){
			removeFeederId = $("#remove-feed").val();
			if(removeFeederId == "" || removeFeederId == null){
				$("#remove-feeder").closest("div").addClass('has-error');
			}else{
				var wathi_removeFeedId = true;
				$.ajax({
					type:'POST',      
			        url:url,
			        data: {removeFeederId:removeFeederId,wathi_removeFeedId:wathi_removeFeedId},
			        success:function(data) {
			            if(data == "sent"){
			            	$("#modalBasicLabel").removeClass('text-danger');
			            	$("#modalBasicLabel").addClass('text-success').text('Request has been sent to the Concerned GM.');
			            	$("#modal_alert_words").text('Until the Concerned GM approves the removal of the Sub-Station it won\'t take affect!');
			            	$("#modalBasic").modal('show');
			            }else{
			            	$("#modalBasicLabel").removeClass('text-success');
			            	$("#modalBasicLabel").addClass('text-danger').text('Process failed!');
			            	$("#modal_alert_words").text(data);
			            	$("#modalBasic").modal('show');
			            }
			        }
				});
			}
		}

	});
	$(document).ajaxStart(function () {
	    $("#loading").show();
	}).ajaxStop(function () {
	    $("#loading").hide();
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

});

function ajaxReq(){

}