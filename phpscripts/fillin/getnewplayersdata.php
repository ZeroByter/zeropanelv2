<?php
    include("scripts.php");

    $array = [];
    $timeJoinedAlias = essentials::getAlias("timeJoinedAlias");

    foreach(players::get_all_newplayersdata() as $value){
        $date = explode(" ", $value->$timeJoinedAlias)[0];
        if(isset($array[$date])){
            $array[$date] += 1;
        }else{
            $array[$date] = 1;
        }
    }

    echo JSON_encode($array);
?>
