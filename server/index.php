<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            // put your code here
            require_once '../TimelineAPI/Timeline.php';
            
            $url = "https://news.google.com/?output=rss";
            $xml = simplexml_load_file($url);
            for ($i=0; $i<5; $i++) {
                $description = strip_tags($xml->channel->item[$i]->description, '<br>');
                $content = explode("<br>", $description);
                echo "<b>$content[2]</b><br>$content[4]<br><br>"; 
            }
        ?>
    </body>
</html>
