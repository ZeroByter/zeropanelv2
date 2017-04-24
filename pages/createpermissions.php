<?php
	$maxString = "";
	if(!permissions::user_has_permission("ignoreaccesslevel")){
		$maxString = "max='$currAccount->accesslevel'";
	}
?>

<br><br><br><br><br><br>
<div class="custom-panel">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
            <div class="panel panel-info">
				<div class="panel-heading">Add new access level</div>
				<div class="panel-body">
					<center>
                        <form id="submitForm">
                            <div class="input-group form-group">
                                <span class="input-group-addon">Name</span>
                                <input type="text" class="form-control" id="name">
                            </div>
                            <div class="input-group form-group">
                                <span class="input-group-addon">Access Level</span>
                                <input type="number" class="form-control" id="accesslevel" <?php echo $maxString ?>>
                            </div>
                            <button class="btn btn-primary" style="float:right;">Add access level</button>
                        </form>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$("#submitForm").submit(function(){
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/addpermission.php", {name: $("#name").val(), accesslevel: $("#accesslevel").val()}, false, function(html){
            window.location = "/<?php echo $linksOffset ?>permissions/" + html.split("/")[1]
		})
		return false
	})
</script>
