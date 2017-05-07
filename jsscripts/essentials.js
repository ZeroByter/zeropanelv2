var essentials = new function(){
    this.sendPost = function(url, array, consoleLog = false, successFunction=function(html){}, errorFunction=function(html){}){
		$.post(url, array, function(html){
            if(consoleLog){
                console.log(html)
            }
            if(html.startsWith("success")){
                successFunction(html)
            }else{
                console.log("POST Request failed! URL: " + url)
                console.log(html)
                $.notify({
                    message: html,
                },{
                    type: "danger",
                    z_index: 10300001,
                })
				errorFunction(html)
            }
			
			return html
        })
    }

    this.message = function(message, type){
        $.notify({
            message: message,
        },{
            type: type,
            z_index: 10300001,
        })
    }

    this.steamIDToGUID = function(uid) {
		if (!uid) {
			return;
		}
		var steamId = bigInt(uid);
		var parts = [0x42,0x45,0,0,0,0,0,0,0,0];
		for (var i = 2; i < 10; i++) {
			var res = steamId.divmod(256);
			steamId = res.quotient;
			parts[i] = res.remainder.toJSNumber();
		}
		var wordArray = CryptoJS.lib.WordArray.create(new Uint8Array(parts));
		var hash = CryptoJS.MD5(wordArray);
		return hash.toString();
	}
}
