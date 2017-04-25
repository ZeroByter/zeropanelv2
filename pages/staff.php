<?php
    $staff = accounts::get_all($pageNum);
    $total_records = count($staff);

	if(permissions::user_has_permission("editstaff")){
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
    .itemRow[data-banned="1"]{
        color: red;
    }
</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<h4 style="float:left;margin-left:20px;"><i class="fa fa-users" aria-hidden="true"></i> Staff</h4> <?php include("phpscripts/fillin/search.php") ?>
		<br><br>
		<hr class="hidden-xs">
		<table class="table">
			<thead>
				<tr>
					<th>Username</th>
					<th>Access Level</th>
					<th>Steam ID</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($staff as $value){ $accesslevel = permissions::get_by_id($value->accesslevel) ?>
					<tr class="itemRow" data-id="<?php echo $value->id ?>" data-banned="<?php echo ($value->banned) ? "1" : "0" ?>">
						<td><?php echo $value->username; if($value->banned) echo " *banned*" ?></td>
						<td><?php echo "$accesslevel->name ($accesslevel->accesslevel)" ?></td>
						<td><?php echo $value->playerid ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php include("phpscripts/fillin/pagebrowser.php") ?>
	</div>
</div>

<?php
	if(permissions::user_has_permission("editstaff")){
?>
	<script>
		$(".itemRow").click(function(){
			window.location = "/<?php echo $linksOffset ?>staff/" + $(this).data("id")
		})
	</script>
<?php
	}
?>
