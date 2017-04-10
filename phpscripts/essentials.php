<?php
    function encrypt_text($text, $salt){
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    function decrypt_text($text, $salt){
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    function get_config(){
        return include(__DIR__ . "/../config.php");
    }
    function write_config($config){
        file_put_contents(__DIR__ . "/../config.php", "<?php return " . var_export($config, true) . ";");
    }

    function getLicenseName($gameName){
        foreach(get_config()["licenseNames"] as $value){
            if($value[0] == $gameName){
                return $value[1];
            }
        }
        return $gameName;
    }

    function start_session(){
        $settings = array();
    	$cookie_id = "";
    	if(file_exists(__DIR__ . "/../config.php")){
    		$settings = get_config();
    		$cookie_id = $settings["cookie_id"];
    		session_name("zeroforumsv2_$cookie_id");
            //session_set_cookie_params(86400);
            session_start();
    	}
        return $cookie_id;
    }
	start_session();

    function stripArray($input, $type){
        switch($type){
            case 0:
                $array = explode("],[", $input);
                $array = str_replace('"[[', '', $array);
                $array = str_replace(']]"', '', $array);
                return str_replace('`', '', $array);
            case 1:
                $array = explode(",", $input);
                $array = str_replace('"[', '', $array);
                $array = str_replace(']"', '', $array);
                return str_replace('`', '', $array);
            case 2:
                $array = explode(",", $input);
                $array = str_replace('"[', '', $array);
                $array = str_replace(']"', '', $array);
                return str_replace('`', '', $array);
            case 3:
                $input = str_replace('[`', '', $input);
                $input = str_replace('`]', '', $input);
                return explode("`,`", $input);
                break;
            default:
                return [];
        }
    }

    function clean($input, $type){
        if($type == 'string'){
            return filter_var(htmlspecialchars(trim($input)), FILTER_SANITIZE_STRING);
        }elseif ($type == 'int'){
            $input = filter_var(htmlspecialchars(trim($input)), FILTER_SANITIZE_NUMBER_INT);
            if($input < 0){
                return 0;
            }
            return $input;
        }elseif ($type == 'url'){
            return filter_var(htmlspecialchars(trim($input)), FILTER_SANITIZE_URL);
        }elseif ($type == 'email'){
            return filter_var(htmlspecialchars(trim($input)), FILTER_SANITIZE_EMAIL);
        }elseif ($type == 'boolean'){
            return ($input === 'true');
        }elseif ($type == 'intbool' && ($input == 1 || $input == 0)){
                return $input;
        }
        return '';
    }

    function get_mysql_conn(){
        if(file_exists(__DIR__ . "/../config.php")){
            $settings = get_config();
            $conn = mysqli_connect(decrypt_text($settings["mysql"]["ip"], $settings["key"]), decrypt_text($settings["mysql"]["username"], $settings["key"]), decrypt_text($settings["mysql"]["password"], $settings["key"]), decrypt_text($settings["mysql"]["dbname"], $settings["key"]));
            return $conn;
        }else{
            return false;
        }
    }

    function get_human_time($time){
        $time = time() - $time;
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
    }

	function get_human_time_alt($time){
		$time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
    }

    function timestamp_to_date($timestamp, $withtime=false){
        if(gettype($timestamp) == "string"){
            $timestamp = strtotime($timestamp);
        }
        if($withtime){
            return getdate($timestamp)["mday"] . "/" . getdate($timestamp)["mon"] . "/" . getdate($timestamp)["year"] . " " . getdate($timestamp)["hours"] . ":" . getdate($timestamp)["minutes"] . ":" . getdate($timestamp)["seconds"];
        }else{
            return getdate($timestamp)["mday"] . "/" . getdate($timestamp)["mon"] . "/" . getdate($timestamp)["year"];
        }
    }

    function removeHTMLElement($identifier){
        echo "<script id='remove_script'>
            $('$identifier').remove()
            $('#remove_script').remove()
        </script>";
    }

    function redirectWindow($link){
        echo "<script id='remove_script'>
            window.location = '$link';
            $('#remove_script').remove()
        </script>";
    }

    function filterXSS($string){
        $string = htmlspecialchars($string);
        return $string;
    }

    function getCurrentVersion($absolute = false){
        $versionDir = $_SERVER['DOCUMENT_ROOT'] . "/version.txt";
        if(file_exists($versionDir)){
            $version = file_get_contents($versionDir);
            if($absolute){
                $version = preg_replace("/\./", "", $version);
            }
            return preg_replace("/\n/", "", $version);
        }else{
            return 0;
        }
    }

    function getGitHubVersion($absolute = false){
        $version = file_get_contents("https://raw.githubusercontent.com/ZeroByter/zeropanelv2/master/version.txt");
        if($absolute){
            $version = preg_replace("/\./", "", $version);
        }
        return preg_replace("/\n/", "", $version);
    }

    function compareVersions(){
		//-1 = current version is outdated
		//0 = current version is up to date
		//1 = should not happen, current version is earlier than github version
		return version_compare(getCurrentVersion(), getGitHubVersion());
    }
?>
