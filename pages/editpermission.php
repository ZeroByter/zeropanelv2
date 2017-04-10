<?php

?>
<style>
	.permissionBtn{
		margin: 2px;
	}
	.permissionMainDiv{
		margin-bottom: 12px;
		border-top: 1px solid #ccc;
		padding: 6px;
	}
	.permissionBtn[data-state="true"]{
		color: #fff;
		background-color: #5cb85c;
		border-color: #4cae4c;
	}
	.permissionBtn[data-state="false"]{
		color: #fff;
		background-color: #d9534f;
		border-color: #d43f3a;
	}
	#delete{
		float: right;
		margin: 10px;
	}
</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<h4 style="float:left;margin-left:20px;"><a href="/<?php echo $linksOffset ?>permissions"><button class="btn btn-sm btn-primary">Go Back</button></a> Edit Permission For Access-Level '<?php echo $permissionObj->name ?>'</h4>
		<?php
			if(permissions::user_has_permission("deletepermissions")){
				echo "<button class='btn btn-danger' id='delete'>Delete Accesslevel</button>";
			}
		?>
		<br><br><br><br>
        <center>
            <?php
                $permissions = permissions::getpermissions();
                foreach($permissions as $key=>$value){
                    echo "<div class='permissionMainDiv'>";
                    echo "<button class='btn btn-primary' data-toggle='collapse' data-target='#permissionCollapse$key'>{$value[0]}</button>";
                    echo "<div class='collapse' id='permissionCollapse$key'><br>";
                    foreach($value as $key2=>$permission){
                        if($key2 != 0){
							if(permissions::accesslevel_has_permission($permissionObj->id, $permission[0])){
								echo "<button class='btn permissionBtn' data-name='{$permission[0]}' data-state='true' data-desc='{$permission[1]}'>{$permission[1]}</button>";
							}else{
								echo "<button class='btn permissionBtn' data-name='{$permission[0]}' data-state='false' data-desc='{$permission[1]}'>{$permission[1]}</button>";
							}
                        }
                    }
                    echo "</div>";
                    echo "</div>";
                }
            ?>
        </center>
	</div>
</div>

<script>
    var permissionID = <?php echo $permissionObj->id ?>

    function updatePermissions(permissionUpdateName, type){
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/editpermissions.php", {id: permissionID, name: permissionUpdateName, type: type}, true, function(html){
            if(html == "success"){
				if($("[data-name='"+permissionUpdateName+"']").attr("data-state") == "true"){
					$("[data-name='"+permissionUpdateName+"']").attr("data-state", "false")
				}else{
					$("[data-name='"+permissionUpdateName+"']").attr("data-state", "true")
				}
            }
        })
	}

    $(".permissionBtn").click(function(){
		var state = $(this).attr("data-state")

		updatePermissions($(this).data("name"), state)
	})

	$("#delete").click(function(){
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/deletepermission.php", {id: permissionID}, false, function(html){
			window.location = "/<?php echo $linksOffset ?>permissions";
		})
	})
</script>
