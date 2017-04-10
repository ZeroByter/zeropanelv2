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
					<div class="panel-heading"><?php echo filterXSS("$owner->name's house") ?></div>
					<div class="panel-body">
						<center>
                            <h4><i class="fa fa-user" aria-hidden="true"></i> Owner: <a href="/players/<?php echo filterXSS($owner->uid) ?>"><?php echo filterXSS($owner->name) ?></a></h4>
                            <h4><i class="fa fa-tag" aria-hidden="true"></i> Position: <?php echo $house->pos ?></h4>
                            <h4><i class="fa fa-id-card" aria-hidden="true"></i> Owned: <?php echo ($house->owned) ? "Yes" : "No" ?></h4>
						</center>
                        <br><br>
                        <?php
                            if(permissions::user_has_permission("deletehouses")){
                                echo '<button class="btn btn-danger" id="deletehouse"><i class="fa fa-times" aria-hidden="true"></i> Delete house</button>';
                            }
                        ?>
					</div>
				</div>
			</div>
            <?php if(permissions::user_has_permission("edithousesinventory")){ ?>
    			<div class="col-md-8">
    				<div class="panel panel-info">
    					<div class="panel-heading">House Inventory</div>
    					<div class="panel-body">
                            <?php
            					if($house->inventory == '"[[],0]"' || $house->inventory == '[[],0]'){
            						echo "<font color='grey'>House inventory is empty</font>";
            					}else{
                                    echo "
                                        <textarea class='form-control' rows='6' id='inventory'>$house->inventory</textarea>
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
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/changehouseinventory.php", {id: <?php echo $house->id ?>, inventory: inventory}, false, function(){
            essentials.message("Inventory updated", "success")
		})
    })

    $("#deletehouse").click(function(){
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/deletehouse.php", {id: <?php echo $house->id ?>}, false, function(){
            window.location = "/<?php echo $linksOffset ?>houses"
		})
    })
</script>
