<?php
    $vehicles = vehicles::get_all_real();
    $total_records = count($vehicles);

    if(permissions::user_has_permission("editvehicles")){
?>
    <style>
        .itemRow{
            transition: all 200ms;
        }
        .itemRow:hover{
            cursor: pointer;
            background: #257cc5;
            color: white;
        }
    </style>
<?php
	}
?>
<style>
    [data-correct="false"], [data-correct="false"]:hover{
        color: red;
    }
</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<h4 style="float:left;margin-left:20px;"><i class="fa fa-car" aria-hidden="true"></i> Vehicles</h4> <?php include("phpscripts/fillin/search.php") ?>
		<br><br>
		<hr class="hidden-xs">
		<table class="table">
			<thead>
				<tr>
					<th>Owner</th>
					<th>Class</th>
					<th>Side</th>
					<th>Type</th>
					<th>Plate</th>
					<th>Alive</th>
					<th>Active</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach(vehicles::get_all($pageNum) as $value){ $carowner = players::get_by_steamid($value->pid) ?>
					<tr class="itemRow" data-id="<?php echo $value->id ?>" data-correct='<?php echo (isset($carowner->uid)) ? "true" : "false" ?>'>
						<td><?php echo @filterXSS($carowner->name) ?></td>
						<td><?php echo filterXSS($value->classname) ?></td>
						<td><?php echo filterXSS($value->side) ?></td>
						<td><?php echo $value->type ?></td>
						<td><?php echo filterXSS($value->plate) ?></td>
						<td><?php echo $value->alive ?></td>
						<td><?php echo $value->active ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php include("phpscripts/fillin/pagebrowser.php") ?>
	</div>
</div>

<?php
	if(permissions::user_has_permission("editvehicles")){
?>
	<script>
		$(".itemRow").click(function(){
            if($(this).data("correct") == true){
                window.location = "/<?php echo $linksOffset ?>vehicles/" + $(this).data("id")
            }
		})
	</script>
<?php
	}
?>
