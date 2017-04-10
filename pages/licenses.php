<?php
	$licenses = $settings["licenseNames"];
?>

<style>
	td > button{
		width: 100%;
	}
</style>

<br><br><br><br><br><br>
<div class="custom-panel">
	<h4 style="float:left;margin-left:20px;">License Names</h4>
	<br><br>
	<hr class="hidden-xs">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="input-group">
				<span class="input-group-addon">Add License</span>
				<input type="text" class="form-control" placeholder="In-game name" id="newGameName">
				<input type="text" class="form-control" placeholder="Nice name" id="newNiceName">
				<span class="input-group-btn">
					<button class="btn btn-primary" style="height:120%;" id="newButton">Add License Definition</button>
				</span>
			</div>
		</div>
		<div class="col-md-6 col-md-offset-3">
			<br><br>
			<table class="table">
				<tbody>
					<?php foreach($licenses as $key=>$value){ ?>
						<tr data-id="<?php echo $key ?>">
							<td style="border:0;"><input type="text" class="form-control" value="<?php echo $value[0] ?>"></td>
							<td style="border:0;"><input type="text" class="form-control" value="<?php echo $value[1] ?>"></td>
							<td style="border:0;"><button class="btn btn-primary editLicense">Edit</button></td>
							<td style="border:0;"><button class="btn btn-danger deleteLicense">Delete</button></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	$("#newButton").click(function(){
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/addlicense.php", {gameName: $("#newGameName").val(), niceName: $("#newNiceName").val()}, false, function(){
			location.reload()
		})
	})

	$(".editLicense").click(function(){
		var tr = $(this).parent().parent()
		var gameName = $($(tr.children()[0]).children()[0]).val()
		var niceName = $($(tr.children()[1]).children()[0]).val()
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/editlicense.php", {id: tr.data("id"), gameName: gameName, niceName: niceName}, false, function(){
			location.reload()
		})
	})

	$(".deleteLicense").click(function(){
		var tr = $(this).parent().parent()
		essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/deletelicense.php", {id: tr.data("id")}, false, function(){
			location.reload()
		})
	})
</script>
