<div class="modal fade" id="viewIPHistory">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title">View IP history</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
                <div class="panel panel-info">
					<div class="panel-heading"><a href="/<?php echo $linksOffset ?>staff"><button class="btn btn-sm btn-primary">Go Back</button></a> <?php echo "$staff->username"; if($staff->banned) echo " <font color='red'>*banned*</font>" ?></div>
					<div class="panel-body">
						<center>
                            <form id="submitForm">
                                <div class="input-group form-group">
                                    <span class="input-group-addon">Username</span>
                                    <input type="text" class="form-control" value="<?php echo $staff->username ?>" id="username">
                                </div>
                                <div class="input-group form-group">
                                    <span class="input-group-addon">Access Level</span>
                                    <select class="form-control" id="accesslevel">
                                        <?php
                                            foreach(permissions::get_all() as $value){
                                                if($staff->accesslevel == $value->id){
                                                    echo "<option selected>$value->name ($value->accesslevel)</option>";
                                                }else{
                                                    echo "<option>$value->name ($value->accesslevel)</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="input-group form-group">
                                    <span class="input-group-addon">Steam ID</span>
                                    <input type="number" class="form-control" value="<?php echo $staff->playerid ?>" id="steamid">
                                </div>
                                <button type="submit" class="btn btn-primary" style="float:right;"><i class="fa fa-edit" aria-hidden="true"></i> Edit staff</button>
                                <button type="button" class="btn btn-primary" id="iphistory" style="float:right;margin-right:4px;" data-toggle="modal" data-target="#viewIPHistory"><i class="fa fa-list" aria-hidden="true"></i> View IP History</button>
                                <?php
                                    if(permissions::user_has_permission("banstaff")){
                                        if($staff->banned){
                                            echo '<button type="button" class="btn btn-warning" style="float:right;margin-right:4px;" id="ban"><i class="fa fa-check" aria-hidden="true"></i> Unban staff</button>';
                                        }else{
                                            echo '<button type="button" class="btn btn-danger" style="float:right;margin-right:4px;" id="ban"><i class="fa fa-times" aria-hidden="true"></i> Ban staff</button>';
                                        }
                                    }
                                ?>
                            </form>
						</center>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$("#submitForm").submit(function(){
        var accesslevel = $("#accesslevel").val().match(/(\(\d\)$)/g)[0].replace(/(\(|\))/g, "")
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/editstaff.php", {id: <?php echo $staff->id ?>, username: $("#username").val(), accesslevel: accesslevel, steamid: $("#steamid").val()}, false, function(){
            essentials.message("Staff edited!", "success")
		})
		return false
	})

	$("#ban").click(function(){
		if(confirm("Are you sure?")){
			essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/banstaff.php", {id: <?php echo $staff->id ?>}, false, function(){
				location.reload()
			})
		}
	})

	function getIPHistoryFillin(id){
		$.get("/<?php echo $resourceLinksOffset ?>phpscripts/fillin/getIPHistory.php", {id: id}, function(html){
			$("#viewIPHistory > div > div > .modal-body").html(html)
		})
	}
	$("#iphistory").click(function(){
		getIPHistoryFillin(<?php echo $staff->id ?>)
	})
</script>
