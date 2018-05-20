<?php
    $allPlayers = players::get_all_real();
    $allPlayersCash = players::getAllByMoney();
    $allPlayersCashPoor = players::getAllByMoney(true);
    $allPlayersCopLvl = players::getAllByCopLvl();
    $allVehicles = vehicles::get_all_real();
?>

<style>
    .panel{
        box-shadow: 2px 3px 2px #aab2bd;
    }
</style>

<center>
    <h1><?php echo filterXSS($settings["communityName"]) ?></h1>
</center>

<br><br><br>

<div>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-users" aria-hidden="true"></i> Players</h3></div>
                <a href="/<?php echo $settings["linksOffset"] ?>players"><div class="panel-body">
                    <h4><?php echo count($allPlayers) ?></h4>
                </div></a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-user" aria-hidden="true"></i> Newest Player</h3></div>
                <a href="/<?php echo $settings["linksOffset"] ?>players/<?php echo $allPlayers[0]->playerid ?>"><div class="panel-body">
                    <?php
                        if(count($allPlayers) == 0){
                            echo "<h4>There are no records of any players!</h4>";
                        }else{
                            echo "<h4>" . filterXSS($allPlayers[0]->name) . "</h4>";
                        }
                    ?>
                </div></a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-danger">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-times" aria-hidden="true"></i> Banned Players</h3></div>
                <div class="panel-body">
                    <h4><?php echo count(bans::get_all_active()) ?></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-money" aria-hidden="true"></i> Total Money on Server</h3></div>
                <div class="panel-body">
                    <h4>$<?php echo number_format(players::getMoneySum()) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-money" aria-hidden="true"></i> Total Money Given by Staff</h3></div>
                <div class="panel-body">
                    <h4>$<?php echo number_format(moneylogs::getMoneyAdded()) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-danger">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-money" aria-hidden="true"></i> Total Money Taken by Staff</h3></div>
                <div class="panel-body">
                    <h4>$<?php echo number_format(moneylogs::getMoneyTaken()) ?></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-home" aria-hidden="true"></i> Total Houses</h3></div>
                <a href="/<?php echo $settings["linksOffset"] ?>houses"><div class="panel-body">
                    <h4><?php echo count(houses::get_all_real()) ?></h4>
                </div></a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-car" aria-hidden="true"></i> Total Vehicles</h3></div>
                <a href="/<?php echo $settings["linksOffset"] ?>vehicles"><div class="panel-body">
                    <h4><?php echo count($allVehicles) ?></h4>
                </div></a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-danger">
                <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-times" aria-hidden="true"></i> Total Destroyed Vehicles</h3></div>
                <div class="panel-body">
                    <h4><?php echo count(vehicles::getAllDestroyed()) ?></h4>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="newPlayerGraph"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <h4>Top 10 Wealthiest</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Bank</th>
                        <th>Cash</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($allPlayersCash as $value){ ?>
                        <tr>
                            <td><a href="/<?php echo $settings["linksOffset"] ?>players/<?php echo $value->playerid ?>"><?php echo filterXSS($value->name) ?></a></td>
                            <td><?php echo $value->bankacc ?></td>
                            <td><?php echo $value->cash ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h4>Top 10 Poorest</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Bank</th>
                        <th>Cash</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($allPlayersCashPoor as $value){ ?>
                        <tr>
                            <td><a href="/<?php echo $settings["linksOffset"] ?>players/<?php echo $value->playerid ?>"><?php echo filterXSS($value->name) ?></a></td>
                            <td><?php echo $value->bankacc ?></td>
                            <td><?php echo $value->cash ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h4>Top 10 Officers</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Access Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($allPlayersCopLvl as $value){ ?>
                        <tr>
                            <td><a href="/<?php echo $settings["linksOffset"] ?>players/<?php echo $value->playerid ?>"><?php echo filterXSS($value->name) ?></a></td>
                            <td><?php echo $value->coplevel ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var newPlayersData = []
    $.get("/phpscripts/fillin/getnewplayersdata.php", function(html){
        for(var key in JSON.parse(html)){
            var data = JSON.parse(html)[key]
            newPlayersData[newPlayersData.length] = {y: data, x: Date.parse(key), date: key}
        }
        generateNewPlayersGraph("Player Join History", newPlayersData[0].date + " to " + newPlayersData[newPlayersData.length-1].date, newPlayersData, "newPlayerGraph")
    })
    function generateNewPlayersGraph(title, text, dataArray, divID){
        Highcharts.chart(divID, {
            title: {
                text: title
            },
            subtitle: {
                text: text
            },
            yAxis: {
                title: {
                    text: 'Number of New Players'
                }
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: {
                    day: '%e/%b/%y'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            series: [{
                type: "column",
                name: 'New Players',
                data: dataArray,
                pointInterval: 24 * 3600 * 1000 // one day
            }]
        });
    }
</script>
