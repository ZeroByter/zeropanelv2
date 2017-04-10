<?php

?>

<style>
	.permissionBtn{
		margin: 2px;
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
</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<?php include("navbar.php") ?>
        <div class="row">
            <div class="col-lg-12">
				<h4>Civilian Licenses</h4>
                <?php
					$civ_licenses = stripArray($player->civ_licenses, 0);
					if(count($civ_licenses) > 1){
	                    foreach($civ_licenses as $value){
							$name = substr($value, 0, strpos($value, ","));
							if(strpos($value, "1")){
								echo "<button data-name='$name' data-state='true' class='btn permissionBtn civLicense'>".getLicenseName($name)."</button>";
							}else{
								echo "<button data-name='$name' data-state='false' class='btn permissionBtn civLicense'>".getLicenseName($name)."</button>";
							}
						}
					}else{
						echo "<font color='grey'>This player has no civilian licenses</font>";
					}
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
				<h4>Medic Licenses</h4>
				<?php
					$med_licenses = stripArray($player->med_licenses, 0);
					if(count($med_licenses) > 1){
	                    foreach($med_licenses as $value){
							$name = substr($value, 0, strpos($value, ","));
							if(strpos($value, "1")){
								echo "<button data-name='$name' data-state='true' class='btn permissionBtn medLicense'>$name</button>";
							}else{
								echo "<button data-name='$name' data-state='false' class='btn permissionBtn medLicense'>$name</button>";
							}
						}
					}else{
						echo "<font color='grey'>This player has no medic licenses</font>";
					}
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
				<h4>Cop Licenses</h4>
				<?php
					$cop_licenses = stripArray($player->cop_licenses, 0);
					if(count($cop_licenses) > 1){
	                    foreach($cop_licenses as $value){
							$name = substr($value, 0, strpos($value, ","));
							if(strpos($value, "1")){
								echo "<button data-name='$name' data-state='true' class='btn permissionBtn copLicense'>".getLicenseName($name)."</button>";
							}else{
								echo "<button data-name='$name' data-state='false' class='btn permissionBtn copLicense'>".getLicenseName($name)."</button>";
							}
						}
					}else{
						echo "<font color='grey'>This player has no cop licenses</font>";
					}
                ?>
            </div>
        </div>
    </div>
</div>

<script>
	var playerID = <?php echo $player->uid ?>

    var civ_licenses = "<?php echo addslashes($player->civ_licenses) ?>"
    var med_licenses = "<?php echo addslashes($player->med_licenses) ?>"
    var cop_licenses = "<?php echo addslashes($player->cop_licenses) ?>"

	function updateLicenses(licenseName, type){
		$.post("/<?php echo $resourceLinksOffset ?>phpscripts/requests/updatelicenses.php", {uid: playerID, type: type, licenseName: licenseName}, function(html){
			console.log(html)
			if(html == "success"){
				if($("[data-name='"+licenseName+"']").attr("data-state") == "true"){
					$("[data-name='"+licenseName+"']").attr("data-state", "false")
				}else{
					$("[data-name='"+licenseName+"']").attr("data-state", "true")
				}
            }else{
                $.notify({
                    message: html,
                },{
                    type: "danger",
                })
            }
		})
	}

	$(".civLicense").click(function(){
		var state = $(this).attr("data-state")
		var name = $(this).data("name")
		if(state == "true"){
			civ_licenses = civ_licenses.replace(name + "`,1", name + "`,0")
		}else{
			civ_licenses = civ_licenses.replace(name + "`,0", name + "`,1")
		}
		updateLicenses($(this).data("name"), "civ_licenses")
	})
	$(".medLicense").click(function(){
		var state = $(this).attr("data-state")
		var name = $(this).data("name")
		if(state == "true"){
			med_licenses = med_licenses.replace(name + "`,1", name + "`,0")
		}else{
			med_licenses = med_licenses.replace(name + "`,0", name + "`,1")
		}
		updateLicenses($(this).data("name"), "med_licenses")
	})
	$(".copLicense").click(function(){
		var state = $(this).attr("data-state")
		var name = $(this).data("name")
		if(state == "true"){
			cop_licenses = cop_licenses.replace(name + "`,1", name + "`,0")
		}else{
			cop_licenses = cop_licenses.replace(name + "`,0", name + "`,1")
		}
		updateLicenses($(this).data("name"), "cop_licenses")
	})
</script>
