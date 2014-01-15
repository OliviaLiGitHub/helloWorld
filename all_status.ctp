<div class='displayRegion'>
	<p>Welcome back <?php echo $currentUser; ?>!<input type="button" onclick="logout()" value="Logout"></p>
	<h1>Users list</h1>
	<table border="1px" cellspacing="0px" style="border-collapse:collapse"  bordercolor="#ff1493">
	    <tr>
	    	<th>Username</th>
	    	<th>PostTime</th>	        
	        <th>Status</th>
	        
	    </tr>
	    <?php 
	    	foreach ($allUser as $user):
	    ?>
	    <tr>
	    	<td><?php echo $user['User']['username']; ?></td>
	    	<td id="user_postTime_<?php echo $user['User']['username']; ?>"><?php echo ($user['User']['postTime']=="0000-00-00 00:00:00")?'':$user['User']['postTime']; ?></td>	        
	        <td id="user_status_<?php echo $user['User']['username']; ?>"><?php echo $user['User']['status']; ?></td>	            
	    </tr>
	    <?php endforeach; ?>
	</table>
	<input type="button" onclick="updateStatusContent()" value="UpdateStatus">
	<input type="button" onclick="deleteStatus()" value="DeleteStatus">	
	<div id='statusRegion'></div>
</div>
   
<style>
	.displayRegion{margin:10px 50px;}
	th{text-align:center;width:50}
	td{text-align:center;width:50}	
</style>
