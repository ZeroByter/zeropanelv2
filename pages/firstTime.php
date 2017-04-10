<?php
    if(!file_exists($_SERVER['DOCUMENT_ROOT'] . "/config.php")){
?>

<style>
    .page-header{
    	padding: 10px;
    	margin: 0px;
        margin-bottom: 30px;
    	border-bottom: 1px solid #eee;
        background: #337ab7;
        color: white;
    }
	#body_div{
		padding: 0px;
	}
</style>

<center>
    <h1 class="page-header">ZeroPanel Setup Page</h1>
    <h4>Use this setup page to perform a first-time setup for your new panel.</h4>
</center>

<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <form id="submitForm">
                <div class="input-group form-group">
                    <span class="input-group-addon">Community Name</span>
                    <input type="text" class="form-control" placeholder="Community Name" id="communityName" required>
                </div>
                <h4>User Setup</h4>
                <div class="input-group form-group">
                    <span class="input-group-addon">Username</span>
                    <input type="text" class="form-control" placeholder="Username" id="defaultUsername" required>
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">Password</span>
                    <input type="password" class="form-control" placeholder="Password" id="defaultPassword" required>
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">Steam ID</span>
                    <input type="number" class="form-control" placeholder="Player ID" id="defaultPlayerID">
                </div>
                <span id="playerIDSteam"></span>
                <h4>SQL Setup</h4>
                <div class="input-group form-group">
                    <span class="input-group-addon">MySQL Host IP</span>
                    <input type="text" class="form-control" placeholder="MySQL Host IP" id="sqlHost" required>
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">MySQL Username</span>
                    <input type="text" class="form-control" placeholder="MySQL Username" id="sqlUsername" required>
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">MySQL Password</span>
                    <input type="password" class="form-control" placeholder="MySQL Password" id="sqlPassword" required>
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">MySQL Database Name</span>
                    <input type="text" class="form-control" placeholder="MySQL Database Name" id="sqlDBName" required>
                </div>
                <h4>Server Setup</h4>
                <div class="input-group form-group">
                    <span class="input-group-addon">Server Name</span>
                    <input type="text" class="form-control" placeholder="Server Name" id="serverName" required>
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">Use RCON</span>
                    <select class="form-control" id="useRCON" required>
                        <option>No</option>
                        <option>Yes</option>
                    </select>
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">RCON Host IP</span>
                    <input type="text" class="form-control" placeholder="RCON Host IP" id="rconIP">
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">RCON Port</span>
                    <input type="text" class="form-control" placeholder="RCON Port" id="rconPort">
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">RCON Password</span>
                    <input type="password" class="form-control" placeholder="RCON Password" id="rconPassword">
                </div>
                <button type="submit" class="btn btn-primary">Setup Panel</button>
            </form>
        </div>
    </div>
</div>

<script>
    $("#submitForm").submit(function(){
        essentials.sendPost("/phpscripts/requests/dosetup.php", {
            communityName: $("#communityName").val(),
            defaultUsername: $("#defaultUsername").val(),
            defaultPassword: $("#defaultPassword").val(),
            defaultPlayerID: $("#defaultPlayerID").val(),

            sqlHost: $("#sqlHost").val(),
            sqlUsername: $("#sqlUsername").val(),
            sqlPassword: $("#sqlPassword").val(),
            sqlDBName: $("#sqlDBName").val(),

            serverName: $("#serverName").val(),
            useRCON: $("#useRCON").val(),
            rconIP: $("#rconIP").val(),
            rconPort: $("#rconPort").val(),
            rconPassword: $("#rconPassword").val(),
        }, false, function(){ location.reload() })
        return false
    })

    $("#defaultPlayerID").bind("change keyup", function(){
        var id = $(this).val()
    })
</script>

<?php
    }
?>
