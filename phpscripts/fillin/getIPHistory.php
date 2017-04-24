<?php
	include("scripts.php");
	$user = accounts::get_by_id($_GET["id"]);
?>

<style>
	#mainIPHistoryDiv{
		width: 100%;
		padding: 8px;
		overflow: auto;
	}
	#ipHistorySideDiv{
		float: left;
		width: 160px;
		border-right: rgba(0,0,0,0.25) 1px solid;
	}
	#ipHistorySideDiv > div{
		padding: 6px;
		margin-right: 10px;
	}
	#ipHistorySideDiv > div:hover{
		background: rgba(0,0,0,0.25);
		cursor: pointer;
	}
	#ipHistoryAccountHistory{
		margin-left: 16px;
		float: left;
		max-width: 376px;
	}
	#ipHistoryMatchingAccounts{
		margin-left: 16px;
		float: left;
		max-width: 376px;
	}
	.same_ip_account_list_div{
		width: 200px;
		padding: 6px;
		text-align: center;
		cursor: pointer;
	}
	.same_ip_account_list_div:hover{
		background: rgba(0,0,0,0.24);
	}
	.ipHistoryMark:first-of-type{
		border-top: 1px #c0c0c0 solid;
	}
	.ipHistoryMark{
		border-bottom: 1px #c0c0c0 solid;
	}
</style>

<div id="mainIPHistoryDiv">
	<div id="ipHistorySideDiv">
		<div id="ipHistorySelect1">Account IP history</div>
		<div id="ipHistorySelect2">Other accounts with the same IP</div>
	</div>
	<div id="ipHistoryAccountHistory">
		<?php foreach(accounts::get_iplist($user->id) as $value){ ?>
			<div class='ipHistoryMark'>
				<h4><?php echo $value->ip ?></h4>
				<h5>Created at <?php echo timestamp_to_date($value->firstseen, true) ?> and last seen at <?php echo timestamp_to_date($value->lastseen,true) ?></h5>
				<?php
					if(isset($value->timesused)){
						echo "<h5>Used $value->timesused time(s)</h5>";
					}
				?>
			</div>
		<?php } ?>
	</div>
	<div id="ipHistoryMatchingAccounts" style="display:none;">
		<?php
			$all_same_ip_accounts = accounts::get_all_with_ip($user->id, $user->id);
			foreach($all_same_ip_accounts as $value){
	            echo "<div class='same_ip_account_list_div' data-id='$value->id'>".accounts::get_by_id($value->id)->username."</div>";
	        }
			if(count($all_same_ip_accounts) == 0){
	            echo "<center><h4>No other accounts with the same IP!</h4></center>";
	        }
		?>
	</div>
</div>

<script>
	$("#ipHistorySelect1").click(function(){
		$("#ipHistoryAccountHistory").css("display", "block")
		$("#ipHistoryMatchingAccounts").css("display", "none")
	})
	$("#ipHistorySelect2").click(function(){
		$("#ipHistoryMatchingAccounts").css("display", "block")
		$("#ipHistoryAccountHistory").css("display", "none")
	})
	$(".same_ip_account_list_div").click(function(){
		getIPHistoryFillin($(this).data("id"))
	})
</script>
