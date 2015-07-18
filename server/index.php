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
            //Include the timeline API
            require_once '../TimelineAPI/Timeline.php';
            
            //Import the required classes
            use TimelineAPI\Pin;
            use TimelineAPI\PinLayout;
            use TimelineAPI\PinLayoutType;
            use TimelineAPI\PinIcon;
            use TimelineAPI\PinReminder;
            use TimelineAPI\Timeline;
            
            //Process RSS feed
            $url = "https://news.google.com/?output=rss";
            $xml = simplexml_load_file($url);
            for ($i=0; $i<5; $i++) {
                $description = strip_tags($xml->channel->item[$i]->description, '<br>');
                $content = explode("<br>", $description);
                echo "<b>$content[2]</b><br>$content[4]<br><br>"; 
            }        

            //Create and send pin
            $reminderlayout = new PinLayout(PinLayoutType::GENERIC_REMINDER, 'Sample reminder!', null, null, null, PinIcon::NOTIFICATION_FLAG);
            $pinlayout = new PinLayout(PinLayoutType::GENERIC_PIN, 'EveningNews', null, null, null, PinIcon::NEWS_EVENT);
            $reminder = new PinReminder($reminderlayout, (new DateTime('now')) -> add(new DateInterval('PT10M')));
            $pin = new Pin('antonio-eveningnews-1', (new DateTime('now')) -> add(new DateInterval('PT5M')), $pinlayout);
            $pin -> addReminder($reminder);
            
            $apiKey = "SBffgcwkhl939ur2fjynentgjexjne0t";
            $topics = ['all-users'];
            Timeline::pushSharedPin($apiKey, $topics, $pin);
            
        ?>
    </body>
</html>
