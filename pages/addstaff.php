<br><br><br><br><br><br>
<div class="custom-panel">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
            <div class="panel panel-info">
				<div class="panel-heading">Add new staff</div>
				<div class="panel-body">
					<center>
                        <form id="submitForm">
                            <div class="input-group form-group">
                                <span class="input-group-addon">Username</span>
                                <input type="text" class="form-control" id="username" required>
                            </div>
							<div class="input-group form-group">
								<span class="input-group-addon">Steam ID</span>
								<input type="number" class="form-control" id="steamid" required>
							</div>
							<div class="input-group form-group">
								<span class="input-group-addon">Password</span>
								<input type="password" class="form-control" id="password">
							</div>
                            <div class="input-group form-group">
                                <span class="input-group-addon">Access Level</span>
                                <select class="form-control" id="accesslevel">
                                    <?php
                                        foreach(permissions::get_all() as $value){
                                            echo "<option>$value->name ($value->accesslevel)</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <button class="btn btn-primary" style="float:right;">Add staff</button>
                        </form>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$("#submitForm").submit(function(){
        var accesslevel = $("#accesslevel").val().match(/(\(\d\)$)/g)[0].replace(/(\(|\))/g, "")
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/addstaff.php", {username: $("#username").val(), accesslevel: accesslevel, steamid: $("#steamid").val(), password: $("#password").val()}, false, function(html){
            window.location = "/<?php echo $linksOffset ?>staff/" + html.split("/")[1]
		})
		return false
	})
</script>
