<?php

	function str_insert($insertstring, $intostring, $offset) {
	   $part1 = substr($intostring, 0, $offset);
	   $part2 = substr($intostring, $offset);
	  
	   $part1 = $part1 . $insertstring;
	   $whole = $part1 . $part2;
	   return $whole;
	}
	
	function get_liveFeed($region, $game)
	{
		$gameStr;
		switch ($game) {
			case 'Starcraft':
				$gameStr = 'sc2';
				break;
			case 'Warcraft':
				$gameStr = 'wow';
				break;
			case 'Diablo':
				$gameStr = 'd3';
				break;
			default:
				$regionStr = 'sc2';
		}

		$regionStr;
		$serverStr;
		switch($region){
			case 'US':
				$regionStr = 'en';
				$serverStr = 'us';
				break;
			case 'EU-EN':
				$regionStr = 'en';
				$serverStr = 'eu';
				break;
			case 'FR':
				$regionStr = 'fr';
				$serverStr = 'eu';
				break;
			case 'DE':
				$regionStr = 'de';
				$serverStr = 'eu';
				break;
			case 'EU-ES':
				$regionStr = 'es';
				$serverStr = 'eu';
				break;
			case 'US-ES':
				$regionStr = 'es';
				$serverStr = 'us';
				break;
			default:
				$regionStr = 'en';
				$serverStr = 'us';
		}

		$url = "http://" . $serverStr . ".battle.net/" . $gameStr . "/" . $regionStr . "/forum/blizztracker/";

		return $url;

		try{
			$ch = curl_init();
	 		$timeout = 5;
	  		curl_setopt($ch,CURLOPT_URL,$url);
	  		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	  		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	  		$data = curl_exec($ch);
	  		curl_close($ch);

	  		return $data;
		} catch (Exception $e) {
			echo "Error Retrieving Blue posts. CURL error : " . $e->getMessage(); 
		}
	}


	function extractTitle($element)
	{
		$divs = $element->getElementsByTagName("div");

		foreach($divs as $div){
			$divClass = $div->getAttribute('class');

			if($divClass == 'desc'){

				$title = $div->getElementsByTagName("a")->item(1)->textContent;
				$titleLink = $div->getElementsByTagName("a")->item(1)->getAttribute('href');
				

				return '<a href="http://us.battle.net' . $titleLink . '" >' . $title . '</a>';
			}
		}
	}

	function extractBlueName($element)
	{
		
	}

	function extractPostDate($element)
	{

	}

	function extractForumOrigin($element, $gameStr, $regionStr)
	{
		$divs = $element->getElementsByTagName("div");

		foreach($divs as $div){
			$divClass = $div->getAttribute('class');

			if($divClass == 'desc'){

				$title = $div->getElementsByTagName("a")->item(0)->textContent;
				$originLink = $div->getElementsByTagName("a")->item(0)->getAttribute('href');

				return '<a href="http://us.battle.net/' . $gameStr . '/' . $regionStr . '/forum/' . str_replace('../','',$originLink) . '" >' . $title . '</a>';
			}
		}
	}

		$gameRegion = 'US';
		$gameToShow = 'Starcraft';
		$itemsToShow = 1;
		
		$dom = new DomDocument();
		$dom->load(get_liveFeed($gameRegion,$gameToShow));
		$finder = new DomXPath($dom);
		$classname = 'forum-topics';			
		$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
		$table = $nodes->item(0);

		$rows = $table->getElementsByTagName("tr");

		foreach ($rows as $row) 
	    {
	    	$cells = $row->getElementsByTagName("td");
	    	foreach ($cells as $cell){
	    		$tdClass = $cell->getAttribute('class');

	    		$title;
	    		$blueName;
	    		$postDate;
	    		$forumOrigin;
	    		
	    		switch($tdClass){
	    			case 'title-cell':
	    			$title = extractTitle($cell);
	    			$forumOrigin = extractForumOrigin($cell, 'sc2', 'us');
	    			break;

	    			case 'author-cell':
	    			$blueName = extractBlueName($cell);
	    			break;

	    			case 'last-post-cell':
	    			$postDate = extractPostDate($cell);
	    			break;

	    			default:
	    			break;
	    		}

	    		echo "\n";
	    		echo 'POST!' . "\n";
	    		echo 'TITLE : ' . $title . "\n";
	    		echo 'POST ORIGIN : ' . $forumOrigin . "\n";
	    		echo 'BLUENAME : ' . $blueName . "\n";
	    		echo 'POSTDATE : ' . $postDate . "\n";
	    	} 
	    }
		

?>
