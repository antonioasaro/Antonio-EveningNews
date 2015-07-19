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
            require_once './TimelineAPI/Timeline.php';
            use TimelineAPI\Pin;
            use TimelineAPI\PinLayout;
            use TimelineAPI\PinLayoutType;
            use TimelineAPI\PinIcon;
            use TimelineAPI\PinReminder;
            use TimelineAPI\Timeline;

            $update = "create";
            if (isset($_GET["update"])) $update = $_GET["update"];
            
            //Process RSS feed
            $url = "https://news.google.com/?output=rss";
            $xml = simplexml_load_file($url);
            $allTheNews = "";
            $search = array('&#39;', '&quot;');
            $replace = array("'", '"');

            for ($i=1; $i<=5; $i++) {
                $description = strip_tags($xml->channel->item[$i]->description, '<br>');
                $content = explode("<br>", $description);
                $fixed = str_replace($search, $replace, $content[2]);
                $allTheNews .= "$i: $fixed\n ";
                echo "<b>$content[2]</b><br>$content[4]<br><br>"; 
            }        

            //Create and send pin
            $timezone = date_default_timezone_get();
            echo "The current server timezone is: " . $timezone . '<br>';                   
            $utc = new DateTimeZone('UTC');
            $amny = new DateTimeZone('America/New_York');
            
            $newsTime = new DateTime('now', $amny);
            $newsTime->setTime(18, 30, 0);
            $newsTime->setTimeZone($utc);
            echo $newsTime->format('Y-m-d H:i:s') . '<br>';
            if ($update == "create") {
                $pinlayout = new PinLayout(PinLayoutType::GENERIC_PIN, 'EveningNews', null, 'Top headlines', "Will be posted at ~6:30pm ET", PinIcon::NEWS_EVENT);
            } else {
                $pinlayout = new PinLayout(PinLayoutType::GENERIC_PIN, 'EveningNews', null, 'Top headlines', $allTheNews, PinIcon::NEWS_EVENT);            
            }
            
            $pin = new Pin('antonio-eveningnews-1', $newsTime, $pinlayout);
  
            $reminderTime = new DateTime('now', $amny);
            $reminderTime->setTime(18, 25, 0);
            $reminderTime->setTimeZone($utc);
            $reminderlayout = new PinLayout(PinLayoutType::GENERIC_REMINDER, 'EveningNews reminder!!', null, 'stay tuned ...', null, PinIcon::NOTIFICATION_FLAG);
            $reminder = new PinReminder($reminderlayout, $reminderTime);
            $pin -> addReminder($reminder);
            
            $apiKey = "SBffgcwkhl939ur2fjynentgjexjne0t";
            $topics = array('all-users');
            Timeline::pushSharedPin($apiKey, $topics, $pin);
            echo "<br>Pushed shared pin.<br>";
        ?>
    </body>
</html>
