<?php
    include("../fillin/scripts.php");
    $settings = get_config();

    logs::add_log("logout", "$1 logged out");

    sessions::delete_session($_SESSION["sessionid"]);

    unset($_SESSION["sessionid"]);
    session_destroy();

    if(isset($_GET["next"])){
?>
<script>
    window.location = "/<?php echo $settings["linksOffset"] . $_GET["next"] ?>"
</script>
<?php }else{ ?>
    <script>
        window.location = "/<?php echo $settings["linksOffset"] ?>";
    </script>
<?php } ?>
