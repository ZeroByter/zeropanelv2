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

    #versionText{
        position: absolute;
        bottom: 0px;
        margin-left: auto;
        margin-right: auto;
        left: 240;
        right: 0;
    }
</style>

<center>
    <h2>ZeroPanel is developed by ZeroByter, it is open source on <a href="https://github.com/ZeroByter/zeropanelv2" target="_blank">GitHub</a>.</h2>
    <h3>ZeroPanel is entirely based off <a href="https://github.com/Cyberbyte-Studios/CyberWorks" target="_blank">CyberWorks</a>' panel, ZeroPanel is simply redesigned and less buggy with modern Arma than <a href="https://github.com/Cyberbyte-Studios/CyberWorks" target="_blank">CyberWorks</a>.</h3>
    <div id="versionText">
        <?php
            $compareVersions = compareVersions();
            if($compareVersions == -1){
                echo "<h3 style='color:red;'>Current version is outdated! If you are the owner of this panel, you should <a href='https://github.com/ZeroByter/zeropanelv2' target='_blank'>update to the most up-to-date version</a> as soon as possible!</h3>";
            }
            if($compareVersions == 0){
                echo "<h3 style='color:green;'>Current version is up to date! Good job!</h3>";
            }
            if($compareVersions == 1){
                echo "<h3 class='weirdAnimation'><b>???</b><br>Somehow, your current panel version is <i>earlier</i> than the <i>supposedly</i> most early version!<br><span style='font-size: 8px;'>creepy stuffs</span></h3>";
            }
        ?>
        <h4>Current version: <?php echo getCurrentVersion() ?></h4>
        <a href="https://github.com/ZeroByter/zeropanelv2" target="_blank"><h4>GitHub version: <?php echo getGitHubVersion() ?></h4></a>
    </div>
</center>
