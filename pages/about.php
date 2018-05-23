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

    #zerobyterProfilePic{
        width: 180px;
        border-width: 2px;
        border-style: solid;
        border-color: #00000026;
        border-radius: 180px;
        transition: border-color 500ms;
    }

    #zerobyterProfilePic:hover{
        border-color: blue;
    }
</style>

<center>
    <h2>ZeroPanel is developed by ZeroByter, it is open source on <a href="https://github.com/ZeroByter/zeropanelv2" target="_blank">GitHub</a>.</h2>
    <h3>ZeroPanel is entirely based off <a href="https://github.com/Cyberbyte-Studios/CyberWorks" target="_blank">CyberWorks</a>' panel, ZeroPanel is simply redesigned and less buggy with modern Arma than <a href="https://github.com/Cyberbyte-Studios/CyberWorks" target="_blank">CyberWorks</a>.</h3>
    <br><br><br><br><br>
    <h2 style="width: 60%;">Like using ZeroPanelV2? Please considering helping <a href="http://www.zerobyter.net" target="_blank">me</a> out by donating to my PayPal! My EMail is <a href="javascript:void(0)">zerobyter@zerobyter.net</a>. Donate how much you want! Any help is appreciated.</h2>
    <div id="zerobyterProfilePic" style="width:180px;">
        <a href="http://www.zerobyter.net" target="_blank">
            <img src="/css/most_handsome_man.png" style="width:180px;"></img>
        </a>
    </div>

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
