$(document).ready(function(){
	var url = window.location.href;

	$("a[href='http://www.shieldui.com']").hide();
	$("tspan").each(function(){
	    if ($(this).text() == "Demo Version" || $(this).text() == "Demo") {
	        $(this).hide();
	    }
	});

	$(".logic-noti").click(function(){
		var message_id = $(this).closest('span').data("type");
		var logic = $(this).data("noti");
		console.log(message_id+' - '+logic);
		$.ajax({
			type:'POST',
			url:url,
			data:{message_id:message_id, logic:logic},
			success:function(data){
				if(data == "success"){
					if(logic == 1){
						$(this).closest('.message-preview').children('.message').text('Request Accepted!');
					}else{
						$(this).closest('.message-preview').children('.message').text('Request Denied!');
					}
					console.log(data);
				}else{
					alert(data);
				}
			},
			error: function(xhr, error){
			    console.debug(xhr); console.debug(error);
			}
		});
		$(this).hide(100);
		$(this).siblings('.logic-noti').hide();
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
	        },
	        error: function(xhr, error){
			    console.debug(xhr); console.debug(error);
			}
       });
	});
});