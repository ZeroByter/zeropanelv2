<?php
    include("../fillin/scripts.php");

    if(isset($_POST["uid"]) && isset($_POST["amount"])){
        if(permissions::user_has_permission("editmoneybank")){
            $moneyBefore = players::get_by_id($_POST["uid"])->bankacc;
            players::changeMoneyBank($_POST["uid"], $_POST["amount"]);
            $moneyAfter = players::get_by_id($_POST["uid"])->bankacc;

			$player = players::get_by_id($_POST["uid"]);
			logs::add_log("money", "$1 added {$_POST["amount"]} to [player/$player->name($player->playerid)]. See money logs for more details", 10);
            moneylogs::create($moneyBefore, $_POST["amount"], $moneyAfter, $_POST["uid"], "Bank Account");
            echo "success";
        }else{
            echo "No permission!";
        }
    }else{
        echo "Missing inputs!";
    }
?>
