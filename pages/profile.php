<style>
    .profileRow{
        overflow: hidden;
        padding: 8px;
        border-top: #aaa 1px solid;
    }
    .profileRow:last-of-type{
        border-bottom: #aaa 1px solid;
    }
    .profileInfo{
        float: right;
    }
</style>

<br><br><br><br><br><br>
<h3>Profile</h3>
<div class="custom-panel">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
            <div class="panel panel-info">
                <div class="panel-heading"><h4><?php echo $currAccount->username ?></h4></div>
                <div class="panel-body">
                    <form id="submitForm">
                        <div>
                            <div class="profileRow">
                                Access Level
                                <div class="profileInfo"><?php echo "$currAccessLevel->name ($currAccessLevel->accesslevel)"; ?></div>
                            </div>
                            <div class="profileRow">
                                SteamID
                                <div class="profileInfo">
                                    <input type="number" class="form-control" value="<?php echo $currAccount->playerid ?>" id="playerid">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div>
                            <h4>Change Password</h4>
                            <div class="profileRow">
                                Current Password
                                <div class="profileInfo">
                                    <input type="password" class="form-control" id="currpass">
                                </div>
                            </div>
                            <div class="profileRow">
                                New Password
                                <div class="profileInfo">
                                    <input type="password" class="form-control" id="newpass">
                                </div>
                            </div>
                        </div>
                        <br>
                        <button class="btn btn-primary" style="float:right;">Edit Profile</button>
                    </form>
                </div>
            </div>
		</div>
	</div>
</div>

<script>
    $("#submitForm").submit(function(){
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/editprofile.php", {playerid: $("#playerid").val(), currpass: $("#currpass").val(), newpass: $("#newpass").val()}, false, function(){
            location.reload()
        })
        return false
    })
</script>
