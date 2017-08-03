<?php
    $filename = dirname(dirname(dirname(__FILE__))) . '/data/option.json';
    require_once dirname(dirname(dirname(__FILE__))) .'/includes/ins.php';
    $option = (array)json_decode(file_get_contents($filename));
    if(is_array($option) && $option['instagram_api_key'] != '' && $option['instagram_secret_key'] != ''): 
    $apiCallback = $option['callback_url'];
    $config = array(
      'apiKey'      => $option['instagram_api_key'],
      'apiSecret'   => $option['instagram_secret_key'],
      'apiCallback' => $option['callback_url']
    );    
    $in = new ins($config);
    $login_url = $in->login();   
?>
    <a href="javascript:void(0)" class="btn btn-default" onclick="joinInstagram()">Login</a>
        <script type="text/javascript">
            function joinInstagram(){
                    var w  = 600;
                    var h = 300;
                    var left = (window.screen.width / 2) - ((w / 2) + 10);
                    var top = (window.screen.height / 2) - ((h / 2) + 50);
                    var popup = window.open("<?php echo $login_url; ?>", "instagram", "status=no, height=" + h + ",width=" + w + ",resizable=yes, left=" + left + ", top=" + top + ",screenX=" + left + ",screenY=" + top + ", toolbar=no, menubar=no, scrollbars=no, location=no, directories=no");
                    popup.onload = function() {
                        var interval = setInterval(function() {
                            clearInterval(interval);
                            popup.close();
                            inAjax('<?php echo $apiCallback; ?>');
                        }, 100);
                    }
//                var timer = setInterval(function () {
//                    if (popup.closed) {
//                        clearInterval(timer);
//                        if (jQuery('#uploaded-instagram img').length == 0)
//                        {
//                            inAjax('<?php echo $apiCallback; ?>');
//                        }
//                    }
//                }, 1000);
            }
		function inAjax(link)
		{
                    jQuery.ajax({
                        url: link,
                        method: "GET",
                        dataType: "json",
                        beforeSend: function(){
                            //TODO
                        },
                        success: function( data ) {
                            console.log(data);
                        }
                    });
		}            
        </script>    
<?php else: ?>
    <p>Please fill Instagram API key!</p>
<?php endif; ?>