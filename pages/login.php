<style>
	a{
		text-decoration: underline;
	}
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
                    <div class="checkbox">
                        <label data-toggle="tooltip" title="This doesn't really work yet, but it will sometime in the future!" data-placement="right"><input type="checkbox" id="rememberme">Remember me</label>
                    </div>
					<div class="alert alert-info" style="display:none;" id="loginAlert">Having problems logging in since <a href="https://github.com/ZeroByter/zeropanelv2/wiki/v.2.0.7.7.3-non-compability-with-older-versions" target="_blank">v.2.0.7.7.1</a>? Passwords have changed since then! Click the version text to learn how to fix your panel! (That, or you may have forgotten your password)</div>
					<button type="submit" class="btn btn-primary" style="float:right;"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</button>
				</form>
			</div>
        </div>
    </div>
</div>

<span id="versionText">Version <?php echo getCurrentVersion() ?></span>

<script>
	$("[data-toggle='tooltip']").tooltip()

	var loginMsg = "";
    $("#submitForm").submit(function(){
        loginMsg = essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/login.php", {
            username: $("#username").val(),
            password: sha256($("#password").val()),
            rememberme: $("#rememberme").prop("checked"),
        }, false, function(){ location.reload() }, function(html){
			if(html == "Wrong password!"){
				$("#loginAlert").css("display", "block")
			}
		})

        return false
    })
</script>
