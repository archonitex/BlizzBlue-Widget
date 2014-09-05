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














		$gameRegion = 'US';
		$gameToShow = 'Starcraft';
		$itemsToShow = 1;
		
		$myFile = get_liveFeed($gameRegion,$gameToShow);
		
		//Start from <tbody class="bluetracker-body">
		//End at </tbody>
		$myStart = strpos($myFile,"<tbody>");
		if($myStart === false){
			$myFile = get_liveFeed($gameRegion,$gameToShow);		
			
			$myStart = strpos($myFile,"<tbody>");
			if($myStart === false){
				echo 'No Blue Posts Available';
				return;
			}
		}
		

		$myEnd = strpos($myFile,"</tbody>",$myStart);
		$myContent = substr($myFile,$myStart,$myEnd);	

		$lastEnd = 0;
		
		if($itemsToShow > 15)
			$itemsToShow = 15;
		if($itemsToShow < 0)
			$itemsToShow = 0;

		$gameStr;
		switch ($gameToShow) {
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
		switch($gameRegion){
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
			
		for ($i = 0; $i < $itemsToShow ; $i++) {
			$thisStrPosStart = strpos($myContent,"<tr id=\"postRow",$lastEnd);
			$thisStrPosEnd = strpos($myContent,"</tr>",$thisStrPosStart);
			
			//Find Post Title
			$desc = strpos($myContent,"<div class=\"desc\">",$thisStrPosStart);
			$descEnd = strpos($myContent,"</div>",$desc);
			$lastEnd = $descEnd;
			
			$tempContent = substr($myContent,$desc,$descEnd-$desc+6);	
			
			if($showDatePost === 'No'){
				//Remove Post Date (We start at 2nd </a>
				$trimfirst = strpos($tempContent,"</a>");
				$trimStart= strpos($tempContent,"</a>",$trimfirst + 4);
				$trimStart = $trimStart + 4;
				$trimEnd = strpos($tempContent,"</div>",$trimStart);
				$tempContent = substr_replace ( $tempContent, "" , $trimStart, $trimEnd-$trimStart);
			}
			if($showForumOrigin === 'No'){
				//Remove Forum Origin
				$trimStart = strpos($tempContent,"[<a");
				$trimEnd = strpos($tempContent,"]",$trimStart);
				$tempContent = substr_replace ( $tempContent, "" , $trimStart, $trimEnd-$trimStart +1);
			}
			
			//Get BluePoster Name
			//Find Post Title
			$blue = strpos($myContent,"<span class=\"type-blizzard\">",$thisStrPosStart);
			$blueEnd = strpos($myContent,"<img",$blue);
			$blueName = substr($myContent,$blue+28,$blueEnd-$blue-28);	
			$blueName  = trim($blueName);
			
			//Replace ../ by right forum link
			$tempContent = str_replace ("href=\"../"," target=\"_blank\" title=\"Post Author: " . $blueName . "\" href=\"http://" . $serverStr . ".battle.net/" . $gameStr . "/" . $regionStr ."/forum/",$tempContent);
			
			//Add Blizz logo in title			
			$tempContent = str_insert("<img src=\"http://us.battle.net/sc2/static/images/layout/cms/icon_blizzard.gif\" title=\"Post Author: " . $blueName . "\"></img>", $tempContent, 18);

			
			echo $tempContent;
		}	

?>
