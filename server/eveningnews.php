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

            $mode = "create";
	    if (\PHP_SAPI === 'cli') {
                if (isset($argv[1])) { $mode = $argv[1]; }
                echo "Cmdline is: "; var_dump($argv); echo '<br>';
                echo "Run via cmdline interface.<br>";
            } else {
                if (isset($_GET["mode"])) { $mode = $_GET["mode"]; }
                echo "Run via web interface.<br>";
	    }
	    echo "Mode set to: $mode." . '<br>';
            
            $timezone = date_default_timezone_get();
            echo "The current server timezone is: " . $timezone . '<br>';

            //Create and send pin
            $utc = new DateTimeZone('UTC');
            $amny = new DateTimeZone('America/New_York');       
            $newsTime = new DateTime('now', $amny);
            $newsTime->setTime(18, 30, 0);
            $newsTime->setTimeZone($utc);

            $id = "antonio-eveningnews-shared-" . $newsTime->format('Y-m-d');
            $subtitle = $newsTime->format('m/d') . " headlines";
            if ($mode == "create") { 
            	$body = "Posted at ~6:30pm ET & PT"; 
	    } else {
            	$body = "";
                $url = "https://news.google.com/?output=rss";
            	$xml = simplexml_load_file($url);
            	$search = array('&#39;', '&quot;', '&amp;');
            	$replace = array("'", '"', '&');

            	for ($i=1; $i<=5; $i++) {
                	$description = \strip_tags($xml->channel->item[$i]->description, '<br>');
                	$content = explode("<br>", $description);
                	$fixed = str_replace($search, $replace, $content[2]);
                	$body .= "$i: $fixed\n";
            	}        
	    }
            $pinlayout = new PinLayout(PinLayoutType::GENERIC_PIN, 'EveningNews', null, $subtitle, $body, PinIcon::NEWS_EVENT, null, null, null, '#00FFFF');   
            $pin = new Pin($id, $newsTime, $pinlayout);
  
            $reminderTime = new DateTime('now', $amny);
            $reminderTime->setTime(18, 25, 0);
            $reminderTime->setTimeZone($utc);
            $reminderlayout = new PinLayout(PinLayoutType::GENERIC_REMINDER, 'EveningNews reminder!!', null, null, null, PinIcon::NOTIFICATION_FLAG, null, null, null, '#00FFFF');
            $reminder = new PinReminder($reminderlayout, $reminderTime);
            $pin -> addReminder($reminder);

            if ($mode == "create") { 
                $newsTimeTomorrow = clone $newsTime; $newsTimeTomorrow->modify('+1 day');
                $newsTimeDayAfter = clone $newsTime; $newsTimeDayAfter->modify('+2 day');
                $idTomorrow = "antonio-eveningnews-shared-" . $newsTimeTomorrow->format('Y-m-d');
                $idDayAfter = "antonio-eveningnews-shared-" . $newsTimeDayAfter->format('Y-m-d');
                $subtitleTomorrow = $newsTimeTomorrow->format('m/d') . " headlines";
                $subtitleDayAfter = $newsTimeDayAfter->format('m/d') . " headlines";
                $pinlayoutTomorrow = new PinLayout(PinLayoutType::GENERIC_PIN, 'EveningNews', null, $subtitleTomorrow, $body, PinIcon::NEWS_EVENT, null, null, null, '#00FFFF');            
                $pinlayoutDayAfter = new PinLayout(PinLayoutType::GENERIC_PIN, 'EveningNews', null, $subtitleDayAfter, $body, PinIcon::NEWS_EVENT, null, null, null, '#00FFFF');            
                $pinTomorrow = new Pin($idTomorrow, $newsTimeTomorrow, $pinlayoutTomorrow);
                $pinDayAfter = new Pin($idDayAfter, $newsTimeDayAfter, $pinlayoutDayAfter);
            }
            
	    // $userToken = "SBJi6DLASS1gawIXru2tiBqAf8HohY5G";
            // $apiKey = "SBffgcwkhl939ur2fjynentgjexjne0t";
            $apiKey = "drekk95x2tufpn3rqluu3rxgmuo2t61k";
            $topics = array('all-users');
            
            echo "Pin id is: $id" . '<br>';          
            // $status = Timeline::pushPin($userToken, $pin);
            $status = Timeline::pushSharedPin($apiKey, $topics, $pin);
            echo "Status is: "; var_dump($status); echo '<br>';
            if ($mode == "create") { 
                echo "Created shared pin set for " . $newsTime->format('Y-m-d H:i:s') . " UTC<br>"; 
                Timeline::pushSharedPin($apiKey, $topics, $pinTomorrow);
                Timeline::pushSharedPin($apiKey, $topics, $pinDayAfter);
            } else { 
                echo "Updated shared pin.<br>";              
            }
            echo "Body set to: $body<br>";
        ?>
    </body>
</html>
