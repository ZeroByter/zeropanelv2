<div id="chatTitleDiv">Staff Chat <i class="fa fa-spinner" id="chatTitleSpinner" style="display:none;"></i></div>
<div id="chatMainDiv">
    <div id="chatTextOutput"></div>
    <?php if(permissions::user_has_permission("typestaffchat")){ ?>
        <input class="form-control" id="chatInput" placeholder="Press enter to send"></input>
    <?php } ?>
</div>

<?php if(permissions::user_has_permission("typestaffchat")){ ?>
    <script>
        $("#chatInput").keypress(function(e){
            if(e.keyCode == 13){
                essentials.sendPost("/<?php echo $resourceLinksOffset ?>phpscripts/requests/postchat.php", {message: $(this).val()}, false, function(html){
                    $("#chatInput").val("")
                })
            }
        })
    </script>
<?php } ?>
