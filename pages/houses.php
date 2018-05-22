<?php
    $houses = houses::get_all($pageNum);
    $total_records = count($houses);

    if(permissions::user_has_permission("edithouses")){
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
		<h4 style="float:left;margin-left:20px;"><i class="fa fa-home" aria-hidden="true"></i> Houses</h4> <?php include("phpscripts/fillin/search.php") ?>
		<br><br>
		<hr class="hidden-xs">
		<table class="table">
			<thead>
				<tr>
					<th>Owner</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($houses as $value){ $houseowner = players::get_by_steamid($value->pid) ?>
					<tr class="itemRow" data-id="<?php echo $value->id ?>">
						<td><?php echo filterXSS($houseowner->name) ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php include("phpscripts/fillin/pagebrowser.php") ?>
	</div>
</div>

<?php
	if(permissions::user_has_permission("edithouses")){
?>
	<script>
		$(".itemRow").click(function(){
            window.location = "/<?php echo $linksOffset ?>houses/" + $(this).data("id")
		})
	</script>
<?php
	}
?>
