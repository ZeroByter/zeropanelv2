<?php
    include("../fillin/scripts.php");

    if(!empty($_GET["name"])){
		$_GET["name"] = preg_replace("/\s+/", " ", $_GET["name"]);
		$returnArray = players::get_all_playersbrowser($_GET["name"]);

		if($_GET["name"] == " "){
			$returnArray = [];
		}

		foreach($returnArray as $value){
			//get aliases in a nice format
			$aliasesArray = json_decode(str_replace("`", '"', preg_replace('(^"|"$)', "", $value->aliases)));
			if(count($aliasesArray) <= 1){
				$value->aliases = "";
			}else{
				$value->aliases = implode(", ", json_decode(str_replace("`", '"', preg_replace('(^"|"$)', "", $value->aliases))));
			}

            $value->name = filterXSS($value->name);

			//count all houses
			$value->houses = count(houses::get_by_owner($value->playerid));

			//get all vehicles that are on civ side and are alive
			$policeCarsArray = [];
			$civCarsArray = [];
			$EMSCarsArray = [];

			foreach(vehicles::get_by_owner($value->playerid) as $value1){
				if($value1->alive == 1){
					if($value1->side == "cop"){
						$policeCarsArray[] = $value1->classname;
					}
					if($value1->side == "civ"){
						$civCarsArray[] = $value1->classname;
					}
					if($value1->side == "ems"){
						$EMSCarsArray[] = $value1->classname;
					}
				}
			}

			$value->policecars = $policeCarsArray;
			$value->civcars = $civCarsArray;
			$value->emscars = $EMSCarsArray;
		}

        echo(json_encode($returnArray));
    }else{
        //echo "Missing inputs!";
    }
?>
