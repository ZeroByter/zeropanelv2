<style>
    #body_div{
		//background: #337ab7;
		background: #316088;
		box-shadow: 0px 0px 200px #58b2ff inset;
		padding-top: 200px;
	}
    .well{
        overflow: hidden;
    }
    #rememberme{
        margin-top: 1.4px;
    }
    #versionText{
        position: absolute;
        right: 4px;
        bottom: 2px;
        color: white;
        font-size: 13px;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
			<div class="well well-sm">
				<form id="submitForm">
					<div class="input-group form-group">
						<span class="input-group-addon">Username</span>
						<input type="text" class="form-control" placeholder="Username" id="username" required>
					</div>
					<div class="input-group form-group">
						<span class="input-group-addon">Password</span>
						<input type="password" class="form-control" placeholder="Password" id="password" required>
					</div>
                    <!--div class="checkbox">
                        <label><input type="checkbox" id="rememberme">Remember me</label>
                    </div!-->
					<button type="submit" class="btn btn-primary" style="float:right;"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</button>
				</form>
			</div>
        </div>
    </div>
</div>

<span id="versionText">Version <?php echo getCurrentVersion() ?></span>

<script>
    $("#submitForm").submit(function(){
        essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/login.php", {
            username: $("#username").val(),
            password: $("#password").val(),
            rememberme: false,//$("#rememberme").prop("checked"),
        }, false, function(){ location.reload() })
        return false
    })
</script>
