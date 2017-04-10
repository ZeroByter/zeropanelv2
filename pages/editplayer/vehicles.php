<?php

?>

<style>
	.form-control{
		resize: vertical;
	}
	.submitBtn{
		margin: 10px 0px;
		float: right;
	}
	.itemRow{
		transition: all 200ms;
	}
	.itemRow:hover{
		cursor: pointer;
		background: #257cc5;
		color: white;
	}
</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<?php include("navbar.php") ?>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
				<table class="table">
					<thead>
						<tr>
							<th>Class</th>
							<th>Side</th>
							<th>Type</th>
							<th>Plate</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach(vehicles::get_by_owner($player->playerid, 10) as $value){ ?>
							<tr class="itemRow" data-id="<?php echo $value->id ?>">
								<td><?php echo filterXSS($value->classname) ?></td>
								<td><?php echo $value->side ?></td>
								<td><?php echo $value->type ?></td>
								<td><?php echo $value->plate ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<h4 style="float:right;"><a href="/<?php echo $linksOffset ?>vehicles?search=<?php echo $player->playerid ?>">See all...</a></h4>
            </div>
        </div>
    </div>
</div>

<script>
	var playerID = <?php echo $player->uid ?>

	$(".itemRow").click(function(){
		window.location = "/<?php echo $linksOffset ?>vehicles/" + $(this).data("id")
	})
</script>
