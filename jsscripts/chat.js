var chat = new function(){
    this.chatActive = false
    this.windowFocus = true;

    $(window).focus(function() {
        this.windowFocus = true
    }).blur(function() {
        this.windowFocus = false
    })

    this.fetchMessages = function(){
        $("#chatTitleSpinner").css("display", "inline-block")
        $.get("/phpscripts/fillin/getchatmsgs.php", function(html){
            if(html){
                $("#chatTextOutput").html(html)
                $("#chatTextOutput").scrollTop($("#chatTextOutput").innerHeight())
                $("#chatTitleSpinner").css("display", "none")
            }
        })
    }

    this.toggleChat = function(){
        if($("#chatTitleDiv").is("[data-activated]")){
            $("#chatTitleDiv").removeAttr("data-activated")
            $("#chatTitleDiv").css("bottom", "0px")
            $("#chatMainDiv").css("bottom", "-300px")
        }else{
            $("#chatTitleDiv").attr("data-activated", "")
            $("#chatTitleDiv").css("bottom", "300px")
            $("#chatMainDiv").css("bottom", "0px")
            $("#chatInput").focus()
            chat.fetchMessages()
        }
        this.chatActive = !this.chatActive
    }
}

$(function(){
    $("#chatTitleDiv").click(function(){
        chat.toggleChat()
    })

    setInterval(function(){
        if(chat.chatActive && chat.windowFocus){
            chat.fetchMessages()
        }
    }, 3 * 1000)
})
