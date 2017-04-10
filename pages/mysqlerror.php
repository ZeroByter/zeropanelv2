<style>
    #body_div{
		box-shadow: 0px 0px 200px rgba(255, 0, 0, 0.20) inset;
		text-align: center;
		padding-top: 80px;
	}
</style>

<h1>MySQL Connection error occured!</h1>
<h4>Connection error text: <?php get_mysql_conn() ?></h4>

<br>

<h4>
	Steps to fix this problem:<br><br>
	<b>1.</b> In the root folder of the panel (<?php echo __DIR__ ?>) there is a file named 'MySQL Error Fix Code - [code here]'.<br>
	<b>2.</b> Copy the code that appears after the dash and insert it into the form below.<br>
	<b>3.</b> Insert the new MySQL connection details to be used and submit!<br>
</h4>
<br>
<div style="width:40%;margin:10px auto;" id="submitForm">
	<h4>The form:</h4>
	<form>
		<div class="form-group input-group">
			<span class="input-group-addon">MySQL Fix Code</span>
			<input type="password" class="form-control" id="fixcode">
		</div>
		<div class="form-group input-group">
			<span class="input-group-addon">New MySQL Host IP</span>
			<input type="text" class="form-control" id="newip">
		</div>
		<div class="form-group input-group">
			<span class="input-group-addon">New MySQL Username</span>
			<input type="text" class="form-control" id="newusername">
		</div>
		<div class="form-group input-group">
			<span class="input-group-addon">New MySQL Password</span>
			<input type="password" class="form-control" id="newpassword">
		</div>
		<div class="form-group input-group">
			<span class="input-group-addon">New MySQL Database Name</span>
			<input type="text" class="form-control" id="newdbname">
		</div>
		<button type="submit" class="btn btn-primary" style="margin-right:0px;">Submit</button>
	</form>
</div>
<br><br><br>
<h4>Otherwise, if you don't mind loosing your configuration and options. You could simply delete 'config.php' (found in your root folder) alltogether to restart a fresh configuration.</h4>

<script>
	$("#submitForm").submit(function(){
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/fixconfig.php", {
			fixcode: $("#fixcode").val(),
			newip: $("#newip").val(),
			newusername: $("#newusername").val(),
			newpassword: $("#newpassword").val(),
			newdbname: $("#newdbname").val(),
		}, false, function(){
			location.reload()
		})

		return false
	})
</script>
