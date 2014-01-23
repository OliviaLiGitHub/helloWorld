function loginToRegister(){	
	$.ajax({    
		   type: "POST", 		       
		   url: "/users/register",		       
		   success: function (data) {
			   $("#login").replaceWith(data);			   			   
		   } 		   
	});	
}

function logout(){
	window.location.href="/users/logout";
}

function updateStatusSubmit(){	
	var newStatus;
	try{
		newStatus=$("#statusText").val().trim();
	}catch(e){
	}
	if(newStatus.length==0){	
		alert("If you want to delete all your status, please use detete button!");
		return;
	}
	
	$.ajax({    
		   type: "POST", 		       
		   url: "/users/updateStatusSubmit",
		   data:{newStatus:newStatus},
		   success: function (data) {
			   var json = eval(data);			   
			   if(json[0]==0){
				   alert("Sorry! You are not allowed to update more than 500 characters each time.");
			   }else{ 
				   var replaceStatus="<td id='user_status_"+json[1]+"'>"+json[2]+"</td>";			   
				   $("#user_status_"+json[1]).replaceWith(replaceStatus);				   
				   
				   var postTime="<td id='user_postTime_"+json[1]+"'>"+json[3]+"</td>";
				   $("#user_postTime_"+json[1]).replaceWith(postTime);
				   alert("Update succeed!");
			   }
		   } 		   
	});	
}

function updateStatusContent(){
	var textBox="<div><br><textarea rows='3' cols='20' id='statusText'>"+
		"</textarea>"+
		"<br><input type='button' onclick='updateStatusSubmit()' value='Submit'></div>";
	$("#statusRegion").replaceWith(textBox);
}
function deleteStatus(){	
	if (!confirm("Are you sure to delete status?")) {
        window.event.returnValue = false;
	}
		$.ajax({    
			   type: "POST", 		       
			   url: "/users/deleteStatus",		       
			   success: function (data) {				   
				   var json = eval(data);				   
				   if(json[0]==1){
					   var replaceStatus="<td id='user_status_"+json[1]+"'></td>";			   
					   $("#user_status_"+json[1]).replaceWith(replaceStatus);
					   var replacePostTime="<td id='user_postTime_"+json[1]+"'></td>";			   
					   $("#user_postTime_"+json[1]).replaceWith(replacePostTime);
					   alert("Delete succeed!");
				   }else{
					   alert("Can not delete the status");
				   }
			   } 		   
			}); 
}




 
