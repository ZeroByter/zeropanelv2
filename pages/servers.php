<?php
    $servers = servers::get_all($pageNum);
    $total_records = count($servers);

	if(permissions::user_has_permission("editservers")){
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

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<h4 style="float:left;margin-left:20px;">Servers</h4> <?php include("phpscripts/fillin/search.php") ?>
		<br><br>
		<hr class="hidden-xs">
		<table class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($servers as $value){ ?>
					<tr class="itemRow" data-id="<?php echo $value->id ?>">
						<td><?php echo $value->id ?></td>
						<td><?php echo $value->name ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php include("phpscripts/fillin/pagebrowser.php") ?>
	</div>
</div>

<?php
	if(permissions::user_has_permission("editservers")){
?>
	<script>
		$(".itemRow").click(function(){
			window.location = "/<?php echo $linksOffset ?>servers/" + $(this).data("id")
		})
	</script>
<?php
	}
?>
