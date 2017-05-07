<?php
    include("../../fillin/scripts.php");

    if(permissions::user_has_permission("settingspage")){
        initliazeFirstTimeDatabase();
        echo "success";
    }else{
        echo "No permission!";
    }
?>
