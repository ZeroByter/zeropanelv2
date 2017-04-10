<?php

?>

<style>

</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<?php include("navbar.php") ?>
		<?php if(permissions::user_has_permission("editmoneybank") || permissions::user_has_permission("editmoneycash")){ ?>
	        <div class="row">
	            <div class="col-md-6 col-md-offset-3">
	                <center>
	                    <div class="input-group form-group">
	                        <span class="input-group-addon">$</span>
	                        <input type="number" class="form-control" id="editmoneyinput" value="0">
	                        <span class="input-group-btn">
								<?php if(permissions::user_has_permission("editmoneybank")){ ?>
		                            <button class="btn btn-success editmoneybtn" data-type="give" data-space="bank">Give bank money</button>
		                            <button class="btn btn-danger editmoneybtn" data-type="take" data-space="bank">Take bank money</button>
								<?php }if(permissions::user_has_permission("editmoneycash")){ ?>
		                            <button class="btn btn-success editmoneybtn" data-type="give" data-space="cash">Give cash money</button>
		                            <button class="btn btn-danger editmoneybtn" data-type="take" data-space="cash">Take cash money</button>
								<?php } ?>
	                        </span>
	                    </div>
	                </center>
	            </div>
	        </div>
		<?php } ?>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-success">
                    <div class="panel-heading">Cash</div>
                    <div class="panel-body">
                        <h4>$<?php echo number_format($player->cash) ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-success">
                    <div class="panel-heading">Bank</div>
                    <div class="panel-body">
                        <h4>$<?php echo number_format($player->bankacc) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	var playerID = <?php echo $player->uid ?>

    $(".editmoneybtn").click(function(){
		var type = $(this).data("type")
		var amount = $("#editmoneyinput").val()
		if(type == "give"){
			amount = Math.abs(amount)
		}else{
			amount = -Math.abs(amount)
		}

		if($(this).data("space") == "bank"){
			essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/changemoneybank.php", {uid: playerID, amount: amount}, false, function(){
				location.reload()
			})
		}else{
			essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/changemoneycash.php", {uid: playerID, amount: amount}, false, function(){
				location.reload()
			})
		}
	})
</script>
