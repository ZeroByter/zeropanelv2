<?php
    $logs = moneylogs::get_all($pageNum);
    $total_records = count(moneylogs::get_all_real());
?>

<style>
    a{
        color: green;
        font-weight: bold;
    }
	.logContainer{
        color: green;
		margin: 0px auto;
	}
	.logContainer:not(:first-of-type) > .logHeader{
		border-top: none;
	}
	.logHeader{
		padding: 8px;
		border: rgb(47, 185, 43) 1px solid;
		border-bottom: none;
		background: rgba(0, 255, 67, 0.14);
		cursor: pointer;
	}
	.logDetails{
		border: rgb(47, 185, 43) 1px solid;
		border-top: none;
		overflow: hidden;
		transition: height 500ms;
		height: 0;
	}
	.logWrapper{
		padding: 12px 10px;
	}
</style>

<br><br><br><br><br><br>
<div class="custom-panel">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <center>
                <a href="/<?php echo $linksOffset ?>logs"><button class="btn btn-warning">Normal Logs</button></a>
                <a href="/<?php echo $linksOffset ?>moneylogs"><button class="btn btn-primary">Money Logs</button></a>
            </center>
        </div>
    </div>
	<br>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
            <?php include("phpscripts/fillin/search.php") ?><br><br><br>
            <div>
                <?php foreach($logs as $value){
                    $value->admins_name = filterXSS($value->admins_name);
                    $value->admins_playerid = filterXSS($value->admins_playerid);
                    $value->players_name = filterXSS($value->players_name);
                    $value->players_id = filterXSS($value->players_id);
                    ?>
            		<div class="logContainer">
            			<div class="logHeader" data-log="<?php echo $value->id ?>">
            				<span style="float: right"><?php echo timestamp_to_date($value->time, true) ?></span>
            				<?php echo "$value->admins_name ($value->admins_playerid) <i class='fa fa-arrow-right'></i> $value->players_name ($value->players_playerid)" ?>
            			</div>
            			<div class="logDetails" data-log="<?php echo $value->id ?>">
            				<div class="logWrapper" data-log="<?php echo $value->id ?>">
            					<b>Staff:</b> <?php echo "<a href='/staff/$value->admins_id'>$value->admins_name ($value->admins_playerid)</a>" ?><br>
            					<b>Money Before:</b> <?php echo $value->money_before ?><br>
            					<b>Money Given:</b> <?php echo $value->money_given ?><br>
            					<b>Money After:</b> <?php echo $value->money_now ?><br>
            					<b>Update Type:</b> <?php echo $value->update_type ?><br>
            					<b>Player:</b> <?php echo "<a href='/players/$value->players_id'>$value->players_name ($value->players_playerid)</a>" ?><br>
            				</div>
            			</div>
            		</div>
            	<?php } ?>
            </div>
		</div>
        <div class="col-md-6 col-md-offset-3">
            <?php include("phpscripts/fillin/pagebrowser.php") ?>
        </div>
	</div>
</div>

<script>
	$(".logHeader").click(function(){
		var logID = $(this).data("log")
		var logDetails = $(".logDetails[data-log='"+logID+"']")[0]
		if(logDetails.clientHeight){
			$(logDetails).attr("data-open", "0")
			logDetails.style.height = 0
		}else{
			$(logDetails).attr("data-open", "1")
			var logWrapper = $(".logWrapper[data-log='"+logID+"']")[0]
			logDetails.style.height = logWrapper.clientHeight + "px"
		}
	})
</script>
