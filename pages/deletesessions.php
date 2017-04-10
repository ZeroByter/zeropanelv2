<?php
    $sessions = sessions::get_all_display($pageNum);
    $total_records = count($sessions);
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
    .itemRow[data-expired="1"]{
        color: red;
    }
</style>

<br><br><br><br><br><br>
<div>
	<div class="custom-panel">
		<h4 style="float:left;margin-left:20px;"><i class="fa fa-users" aria-hidden="true"></i> Staff</h4>
		<br><br>
		<hr class="hidden-xs">
		<table class="table">
			<thead>
				<tr>
					<th>IP</th>
					<th>Session ID</th>
					<th>Linked Account</th>
					<th>Date of creation</th>
					<th>Date of last usage</th>
					<th>Browser</th>
                    <?php
                        if(permissions::user_has_permission("deletesessions")){
                            echo "<th>Delete/Logout</th>";
                        }
                    ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($sessions as $value){ ?>
					<tr class="itemRow" data-id="<?php echo $value->id ?>" data-expired="<?php echo (false) ? "1" : "0" ?>">
						<td><?php echo $value->ip ?></td>
						<td><?php echo ($value->sessionid == $_SESSION["sessionid"]) ? $value->sessionid : "** Private **" ?></td>
						<td><?php echo filterXSS(accounts::get_by_id($value->accountid)->username) ?></td>
						<td><?php echo $value->created ?></td>
						<td><?php echo $value->lastused ?></td>
						<td><?php echo $value->browser ?></td>
                        <?php
                            if(permissions::user_has_permission("deletesessions")){
                                echo "<td><button class='btn btn-danger btn-xs deleteSession'>&times; Logout</button></td>";
                            }
                        ?>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php include("phpscripts/fillin/pagebrowser.php") ?>
	</div>
</div>

<script>
	$(".deleteSession").click(function(){
        var button = this
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/deletesession.php", {id: $(button).parent().parent().data("id")}, false, function(html){
            $(button).parent().parent().remove()
		})
	})
</script>
