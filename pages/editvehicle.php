<?php

?>

<style>
    .shadowtext[data-side="civ"]{
        text-shadow: magenta 1px 1px 3px;
    }
    .shadowtext[data-side="cop"]{
        text-shadow: blue 1px 1px 3px;
    }
    .shadowtext[data-side="med"]{
        text-shadow: red 1px 1px 3px;
    }
    .submitBtn{
        margin-top: 10px;
        float: right;
    }
    #inventory{
        resize: vertical;
    }
    #storeveh, #deleteveh{
        float: right;
        margin: 2px;
    }
</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<div class="row">
			<div class="col-md-4">
				<div class="panel panel-info">
					<div class="panel-heading"><?php echo filterXSS("$owner->name's $vehicle->classname") ?></div>
					<div class="panel-body">
						<center>
                            <h4><i class="fa fa-user" aria-hidden="true"></i> Owner: <a href="/players/<?php echo filterXSS($owner->uid) ?>"><?php echo filterXSS($owner->name) ?></a></h4>
                            <h4><i class="fa fa-tag" aria-hidden="true"></i> Class: <?php echo filterXSS($vehicle->classname) ?></h4>
                            <h4><i class="fa fa-id-card" aria-hidden="true"></i> Plate: <?php echo filterXSS($vehicle->plate) ?></h4>
                            <h4><i class="fa fa-cog" aria-hidden="true"></i> Type: <?php echo $vehicle->type ?></h4>
                            <h4 class="shadowtext" data-side="<?php echo $vehicle->side ?>">Side: <?php echo $vehicle->side ?></h4>
                            <h4 style="margin-bottom:18px;">
                                <?php
                                    if($vehicle->alive){
                                        echo "<span class='label label-success'>Alive</span>";
                                    }else{
                                        echo "<span class='label label-danger'>Not alive</span>";
                                    }
                                ?>
                            </h4>
                            <h4>
                                <?php
                                    if($vehicle->active){
                                        echo "<span class='label label-success'>Active</span>";
                                    }else{
                                        echo "<span class='label label-danger'>Not active</span>";
                                    }
                                ?>
                            </h4>
						</center>
                        <br><br>
                        <?php
                            if(permissions::user_has_permission("editvehicles")){
                                echo '<button class="btn btn-primary" id="storeveh"><i class="fa fa-floppy-o" aria-hidden="true"></i> Store vehicle</button>';
                            }
                            if(permissions::user_has_permission("deletevehicle")){
                                echo '<button class="btn btn-danger" id="deleteveh"><i class="fa fa-times" aria-hidden="true"></i> Delete vehicle</button>';
                            }
                        ?>
					</div>
				</div>
			</div>
            <?php if(permissions::user_has_permission("editvehinventory")){ ?>
    			<div class="col-md-8">
    				<div class="panel panel-info">
    					<div class="panel-heading">Vehicle Inventory</div>
    					<div class="panel-body">
                            <?php
            					if($vehicle->inventory == '"[[],0]"' || $vehicle->inventory == '[[],0]'){
            						echo "<font color='grey'>Vehicle inventory is empty</font>";
            					}else{
                                    echo "
                                        <textarea class='form-control' rows='6' id='inventory'>$vehicle->inventory</textarea>
                                        <button class='btn btn-primary submitBtn'>Change Inventory</button>
                                    ";
            					}
            				?>
    					</div>
    				</div>
    			</div>
            <?php } ?>
		</div>
    </div>
</div>

<script>
    $(".submitBtn").click(function(){
        var inventory = $("#inventory").val()
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/changevehinventory.php", {id: <?php echo $vehicle->id ?>, inventory: inventory}, false, function(){
            essentials.message("Inventory updated", "success")
		})
    })

    $("#storeveh").click(function(){
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/storevehicle.php", {id: <?php echo $vehicle->id ?>}, false, function(){
            location.reload()
		})
    })
    $("#deleteveh").click(function(){
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/deletevehicle.php", {id: <?php echo $vehicle->id ?>}, false, function(){
            window.location = "/<?php echo $linksOffset ?>vehicles"
		})
    })
</script>
