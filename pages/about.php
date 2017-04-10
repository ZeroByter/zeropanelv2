<style>
    @keyframes weirdAnimation {
        0%{
            color: red;
        }

        25%{
            color: green;
        }

        50%{
            color: red;
        }

        75%{
            color: green;
        }

        100%{
            color: red;
        }
    }

    .weirdAnimation{
        animation-name: weirdAnimation;
        animation-duration: 1s;
        animation-iteration-count: infinite;
    }
</style>

<center>
    <h3>Current version: <?php echo getCurrentVersion() ?></h3>
    <a href="https://github.com/ZeroByter/zeropanelv2" target="_blank"><h3>GitHub version: <?php echo getGitHubVersion() ?></h3></a>
    <?php
        $compareVersions = compareVersions();
        if($compareVersions == -1){
            echo "<h2 style='color:red;'>Current version is outdated! If you are the owner of this panel, you should update as soon as possible!</h2>";
        }
        if($compareVersions == 0){
            echo "<h2 style='color:green;'>Current version is up to date! Good job!</h2>";
        }
        if($compareVersions == 1){
            echo "<h2 class='weirdAnimation'><b>???</b><br>Somehow, your current panel version is <i>earlier</i> than the <i>supposedly</i> most early version!<br><span style='font-size: 8px;'>creepy stuffs</span></h2>";
        }
    ?>
</center>
