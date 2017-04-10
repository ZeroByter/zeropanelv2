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
</style>

<br><br><br><br><br><br>
<div class="custom-panel">
    <a href="/<?php echo $linksOffset ?>rcon/<?php echo $server->id ?>" style="margin:10px;"><button class="btn btn-sm btn-primary">Go Back</button></a>
    <center>
        <table class="table">
            <thead>
                <tr>
                    <th>GUID</th>
                    <th>Name</th>
                    <th>Steam ID</th>
                    <th>Reason</th>
                    <th>Notes</th>
                    <th>Time Banned</th>
                    <th>Expires In</th>
                    <th>Banned By</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
        <?php
            if(permissions::user_has_permission("removerconbans")){
                echo " <button class='btn btn-primary actionButton' id='removeBan' disabled>Remove Ban</button> ";
            }
        ?>
    </center>
</div>

<script>
    var selectedBan = undefined
    var selectedBanRConID = undefined

	$.get("/<?php echo $resourceLinksOffset ?>phpscripts/fillin/rcon/bans.php", {id: "<?php echo $server->id ?>"}, function(html){
		$("#tableBody").html(html)
	})

    $("#removeBan").click(function(){
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/rcon/removeBan.php", {serverid: "<?php echo $server->id ?>", banid: selectedBanRConID}, false, function(html){
            location.reload()
        })
    })
</script>
