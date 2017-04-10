<div class="modal fade" id="globalMessageModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">Say Global Server Message</h4>
            </div>
            <div class="modal-body">
                <input class="form-control" id="globalMessageInput">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="globalMessageBtn">Send Message</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="privateMessageModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">Say Private Message To Player</h4>
            </div>
            <div class="modal-body">
                <input class="form-control" id="privateMessageInput">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="privateMessageBtn">Send Message</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="kickModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">Say Private Message To Player</h4>
            </div>
            <div class="modal-body">
                <input class="form-control" id="kickInput" placeholder="Kick Message">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="kickBtn">Kick Player</button>
            </div>
        </div>
    </div>
</div>

<style>
    .playerRow{
        transition: all 200ms;
    }
    .playerRow:hover, .activeRow{
        cursor: pointer;
        background: #257cc5;
        color: white;
    }
    #refreshDiv{
        float: right;
        margin: 10px;
        font-size: 16px;
    }
    #warningLabel{
        display: -webkit-inline-box;
        overflow: visible;
    }
</style>

<br><br><br>
<center>
    <span class="label label-warning" id="warningLabel">
        <span style="font-size:26px;">Warning!</span><br><br>
        <span style="font-size:16px;">Do not idle in this page! You could cause lag on the server...</span>
    </span>
</center>
<br><br><br>
<div class="custom-panel">
    <a href="/<?php echo $linksOffset ?>rcon/<?php echo $server->id ?>" style="margin:10px;"><button class="btn btn-sm btn-primary">Go Back</button></a>
    <div id="refreshDiv">Refreshing list in <span id="refreshText"></span> seconds. <button class="btn btn-sm btn-primary" id="manualRefresh">Refresh Now</button></div>
    <center>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>IP</th>
                    <th>GUID</th>
                    <th>Ping</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
        <?php
            if(permissions::user_has_permission("sayrconglobal")){
                echo " <button class='btn btn-primary' data-toggle='modal' data-target='#globalMessageModal'>Global Message</button> ";
            }
            if(permissions::user_has_permission("sayrconprivate")){
                echo " <button class='btn btn-primary actionButton' data-toggle='modal' data-target='#privateMessageModal'>Private Message</button> ";
            }
            if(permissions::user_has_permission("kickrconplayers")){
                echo " <button class='btn btn-danger actionButton' data-toggle='modal' data-target='#kickModal'>Kick Player</button> ";
            }
        ?>
    </center>
</div>

<script>
    var selectedPlayer = undefined
    var refreshCooldown = 0
    var refreshTimer = 30

    function getPlayers(){
        console.log(refreshCooldown)
        if(refreshCooldown <= 0){
            selectedPlayer = undefined
            $(".actionButton").attr("disabled", "")
            $.get("/<?php echo $resourceLinksOffset ?>phpscripts/fillin/rcon/players.php", {id: "<?php echo $server->id ?>"}, function(html){
                $("#tableBody").html(html)
            })
        }
    }
    getPlayers()

    setInterval(function(){
        refreshTimer -= 1
        refreshCooldown -= 1
        if(refreshTimer <= 0){
            getPlayers()
            refreshTimer = 30
        }
        if(refreshCooldown <= 0){
            $("#manualRefresh").removeAttr("disabled")
        }
        $("#refreshText").html(refreshTimer)
    }, 1000)
    $("#manualRefresh").click(function(){
        getPlayers()
        refreshTimer = 30
        refreshCooldown = 10
        $("#manualRefresh").attr("disabled", "")
    })

    $("#globalMessageBtn").click(function(){
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/rcon/globalmessage.php", {serverid: "<?php echo $server->id ?>", message: $("#globalMessageInput").val()}, false, function(html){
            $("#globalMessageInput").val("")
            essentials.message("Global Message Sent! '"+html.split("/")[1]+"'", "success")
        })
    })

    $("#privateMessageBtn").click(function(){
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/rcon/privatemessage.php", {serverid: "<?php echo $server->id ?>", playerid: selectedPlayer, message: $("#privateMessageInput").val()}, false, function(html){
            $("#privateMessageInput").val("")
            essentials.message("Private Message Sent! '"+html.split("/")[1]+"'", "success")
        })
    })

    $("#kickBtn").click(function(){
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/rcon/kick.php", {serverid: "<?php echo $server->id ?>", playerid: selectedPlayer, message: $("#kickInput").val()}, false, function(html){
            $("#kickInput").val("")
            essentials.message("Player Kicked!", "success")
            getPlayers()
        })
    })
</script>
