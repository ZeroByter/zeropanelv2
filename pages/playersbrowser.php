<style>
	body, #body_div{
		background: none transparent;
	}
	.panel{
		padding: 14px;
	}
	#searchResultsDiv{
		position: absolute;
		border-radius: 4px;
		border: 1px solid #ccc;
		padding: 7px 0px;
		text-align: left;
		background: #eaeaea;
	}
	#searchResultsDiv > div{
		font-size: 18px;
		padding: 4px 6px;
		cursor: pointer;
		transition: * 400ms;
	}
	#searchResultsDiv > div:hover{
		background: rgba(75, 136, 187, 0.5);
	}
	#info-panel{
		font-size: 16px;
	}
	#info-panel > div{
		margin-bottom: 10px;
	}
	table > thead > tr > th, table > tbody > tr > td{
		text-align: center;
	}

	@keyframes animcode{
		0%{
			margin-top: 0px;
			color: green;
		}
		50%{
			margin-top: 20px;
			color: black;
		}
		100%{
			margin-top: 0px;
			color: green;
		}
	}
	#searchHint{
		display: -webkit-inline-box;
		animation-name: animcode;
		animation-duration: 1.5s;
		animation-iteration-count: infinite;
		animation-timing-function: ease-in-out;
		font-size: 16px;
	}
</style>

<center>
	<div class="input-group form-group">
		<span class="input-group-addon">Player Name</span>
		<input type="text" class="form-control" autocomplete="off" placeholder="In-game name" id="name">
	</div>
	<div id="searchResultsDiv"></div>

	<div id="searchHint">Search by player name or SteamID64!</div>

	<div class="panel panel-info" id="info-panel" style="display:none;">
		<div><b><span class="fa fa-tag"></span> Name:</b> <span id="playerInfoName"></span> (<span id="playerInfoSteamID"></span>)</div>
		<div><b><span class="fa fa-tags"></span> Aliases:</b> <span id="playerInfoAliases"></span></div>
		<div><b><span class="fa fa-credit-card"></span> Bank money:</b> <span id="playerInfoBank"></span></div>
		<div><b><span class="fa fa-home"></span> # of houses:</b> <span id="playerInfoHouses"></span></div>
		<div>
			<b><span class="fa fa-car"></span> All vehicles:</b>
			<table class="table" style="width: 25%;">
				<thead>
					<tr>
						<th>Police Vehicles</th>
						<th>Civilian Vehicles</th>
						<th>EMS Vehicles</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td id="playerInfoCopCars"></td>
						<td id="playerInfoCivCars"></td>
						<td id="playerInfoEMSCars"></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div><b><span class="fa fa-calendar"></span> Time joined:</b> <span id="playerInfoJoined"></span></div>
		<div><b><span class="fa fa-calendar"></span> Last updated:</b> <span id="playerInfoUpdated"></span></div>
	</div>
</center>

<script>
	$("#searchResultsDiv").css("display", "none")
	var playersList = {}

	$("#name").keyup(function(){
		$("#searchHint").css("display", "none")
		$.get("/<?php echo $resourceLinksOffset ?>phpscripts/requests/playersbrowser.php", {name: $("#name").val()}, function(html){
			if($("#name").val() == "" || $("#name").val() == " " || JSON.parse(html).length < 1){
				$("#searchResultsDiv").css("display", "none")
			}else{
				$("#searchResultsDiv").css("display", "block")
				$("#searchResultsDiv").css("left", $("#name").position().left + 21)
				$("#searchResultsDiv").css("top", $("#name").position().top + 60)
				$("#searchResultsDiv").css("width", $("#name").width() + 24)

				$("#searchResultsDiv").html("")
				playersList = JSON.parse(html)
				for(var i = 0; i < JSON.parse(html).length; i++){
					var player = JSON.parse(html)[i]
					$("#searchResultsDiv").append("<div data-id='"+i+"'>"+player.name+"</div>")
				}

				$("#searchResultsDiv > div").click(function(){
					$("#searchResultsDiv").css("display", "none")
					$("#name").val(playersList[$(this).data("id")].name)
					$("#info-panel").css("display", "block")
					$("#playerInfoName").html(playersList[$(this).data("id")].name)
					$("#playerInfoSteamID").html(playersList[$(this).data("id")].<?php echo $playerIDAlias ?>)
					$("#playerInfoBank").html(playersList[$(this).data("id")].bankacc)
					$("#playerInfoHouses").html(playersList[$(this).data("id")].houses)
					$("#playerInfoCopCars").html(playersList[$(this).data("id")].policecars.join(", "))
					$("#playerInfoCivCars").html(playersList[$(this).data("id")].civcars.join(", "))
					$("#playerInfoEMSCars").html(playersList[$(this).data("id")].emscars.join(", "))
					if(playersList[$(this).data("id")].aliases == ""){
						$("#playerInfoAliases").html(playersList[$(this).data("id")].name)
					}else{
						$("#playerInfoAliases").html(playersList[$(this).data("id")].aliases)
					}
					$("#playerInfoJoined").html(playersList[$(this).data("id")].timejoined)
					$("#playerInfoUpdated").html(playersList[$(this).data("id")].timeupdated)
				})
			}
		})
	})
</script>
