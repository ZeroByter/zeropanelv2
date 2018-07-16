<?php
    $players = players::get_all_real();
    $total_records = count($players);
?>

<style>
    .playerRow{
        transition: all 200ms;
    }
    .playerRow:hover{
        cursor: pointer;
        background: #257cc5;
        color: white;
    }
</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<h4 style="float:left;margin-left:20px;"><i class="fa fa-users" aria-hidden="true"></i> Players</h4> <?php include("phpscripts/fillin/search.php") ?>
		<br><br>
		<hr class="hidden-xs">
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Steam ID</th>
					<th>Cash</th>
					<th>Bank</th>
					<th>Cop</th>
					<th>Medic</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach(players::get_all($pageNum) as $value){ ?>
					<tr class="playerRow" data-id="<?php echo $value->$playerIDAlias ?>">
						<td><?php echo filterXSS($value->name) ?></td>
						<td><?php echo $value->$playerIDAlias ?></td>
						<td><?php echo $value->cash ?></td>
						<td><?php echo $value->bankacc ?></td>
						<td><?php echo $value->coplevel ?></td>
						<td><?php echo $value->mediclevel ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php include("phpscripts/fillin/pagebrowser.php") ?>
	</div>
</div>

<script>
    $(".playerRow").click(function(){
        window.location = "/<?php echo $settings["linksOffset"] ?>players/" + $(this).data("id")
    })
</script>
