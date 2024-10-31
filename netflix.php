<?php
	
	/*
		Netflix 
		Version: 1.0
		Author: Albert Banks 
		Author URL: http://www.albertbanks.com
		Plugin website: http://dev.wp-plugins.org/wiki/netflix 
		Plugin source: http://dev.wp-plugins.org/browser/netflix/trunk/
		Description: This plugin displays info from your Netflix account. Inspired by <a href="http://www.jimmyoliver.net">Jimmy Oliver</a> and his <a href="http://www.jimmyoliver.net/mynetflix-plugin/">MyNetflix Plugin</a>
		License: GPL
	*/ 
	
	require_once(ABSPATH.'wp-includes/rss-functions.php');
	
	/*  
		Set the netflixID variable to your id from your Netflix rss feed. Login to your Netflix account and go to your queue. At the bottom of the page is a RSS link. Copy the id variable in one of the links and paste as the netflixID variable in netflix.php. 
	*/
	$netflixID = "P1010844384151522763435961160037155";
	
	// netflix id is not valid
	if (strlen($netflixID) == 0) {
		echo "<h3>Netflix is not configured properly.  Please set the $id variable to your Netflix account RSS id</h3>";
		die();
	}
	
	// display netflix info
	function netflix($limit=0, $feed="queue", $type="title", $size="small") {
		global $netflixID;
	
		// diplay type
		switch($feed) {
			case 'recent':
				$url = "http://rss.netflix.com/TrackingRSS?id=".$netflixID;
				break;
			case 'recommendations':
				$url = "http://rss.netflix.com/RecommendationsRSS?id=".$netflixID;
				break;
			default: 
				$url = "http://rss.netflix.com/QueueRSS?id=".$netflixID;
		}
		
		// url setup properly
		if ($url) {
			$rss = fetch_rss($url);
			foreach ($rss->items as $item) {

				// no limit, exit
				if ($limit == 0) {
					break;
				}

				// vars
				$rawTitle = $item['title'];
				$titleStartPosition = strpos($rawTitle, " ") + 1;
				$title = substr($rawTitle, $titleStartPosition);
				$link = $item['link'];
				$movieIDStartPosition = strpos($link, "movieid=") + 8;
				$movieID = substr($link, $movieIDStartPosition, 8);
				
				// diplay type
				switch($type) {
					case 'title':
						$display = $title;
						break;
					case 'image':
						$display = "<img src=\"http://cdn.nflximg.com/us/boxshots/".$size."/$movieID.jpg\" />";
						break;
					default: 
						$display = $rawTitle;
				}
			
				// display link
				echo wptexturize("<li><a href=\"".$link."\">".$display."</a></li>");
				$limit--;
			}
		}
	}

?>
