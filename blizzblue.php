<?php
/*
Plugin Name: BlizzBlueWidget
Plugin URI: http://dev.wrclan.com/
Description: Adds a widget to your wordpress theme that gets the latest blue posts for Starcraft 2, Diablo 3 and WoW.
Author: Francis Carriere
Version: 2.1
Author URI: http://dev.wrclan.com/
Stable tag: 2.1
*/

/* Add our function to the widgets_init hook. */
add_action( 'widgets_init', 'blizzBlue_widgets' );

/* Function that registers our widget. 
If you wanted to create more than one widget, you’d use the register_widget() function for each widget inside of the blizzBlue_widgets() function.
*/
function blizzBlue_widgets() {
	register_widget( 'blizzBlue_Widget' );
}

class blizzBlue_Widget extends WP_Widget {

	function blizzBlue_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'blizzBlue_Widget', 'description' => 'Blizzard Blue Post Tracker' );

		/* Create the widget. */
		$this->WP_Widget( 'blizzBlue_Widget', 'BlizzBlue Widget', $widget_ops);
	}
	
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Blue Tracker', 'game_to_show' => 'All', 'items_to_show' => 5, 'showForumOrigin' => 'No','showDatePost' => 'No', 'gameRegion' => 'US', 'liveFeed' => 'No');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'liveFeed' ); ?>">Use Live Feed? (High traffic websites should use "No". List updated every 20 minutes to reduce traffic against Blizzard)</label>

			<select id="<?php echo $this->get_field_id( 'liveFeed' ); ?>" name="<?php echo $this->get_field_name( 'liveFeed'); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'Yes' == $instance['liveFeed']) echo 'selected="selected"'; ?>>Yes</option>
				<option <?php if ( 'No' == $instance['liveFeed'] ) echo 'selected="selected"'; ?>>No</option>
			</select>
				
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'items_to_show' ); ?>">Posts (Max 15):</label>
			<input id="<?php echo $this->get_field_id( 'items_to_show' ); ?>" name="<?php echo $this->get_field_name( 'items_to_show' ); ?>" value="<?php echo $instance['items_to_show']; ?>" style="width:100%;" />
			
		</p>		
		
		<p>
			<label for="<?php echo $this->get_field_id( 'showForumOrigin' ); ?>">Show Forum Origin</label>
			<select id="<?php echo $this->get_field_id( 'showForumOrigin' ); ?>" name="<?php echo $this->get_field_name( 'showForumOrigin' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'Yes' == $instance['showForumOrigin'] ) echo 'selected="selected"'; ?>>Yes</option>
				<option <?php if ( 'No' == $instance['showForumOrigin'] ) echo 'selected="selected"'; ?>>No</option>
				
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'showDatePost' ); ?>">Show Date of Post</label>
			<select id="<?php echo $this->get_field_id( 'showDatePost' ); ?>" name="<?php echo $this->get_field_name( 'showDatePost' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'Yes' == $instance['showDatePost'] ) echo 'selected="selected"'; ?>>Yes</option>
				<option <?php if ( 'No' == $instance['showDatePost'] ) echo 'selected="selected"'; ?>>No</option>
				
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'game_to_show' ); ?>">Game To Show :</label>

			<select id="<?php echo $this->get_field_id( 'game_to_show' ); ?>" name="<?php echo $this->get_field_name( 'game_to_show' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'Starcraft' == $instance['game_to_show'] ) echo 'selected="selected"'; ?>>Starcraft</option>
				<option <?php if ( 'Warcraft' == $instance['game_to_show'] ) echo 'selected="selected"'; ?>>Warcraft</option>
				<option <?php if ( 'Diablo' == $instance['game_to_show'] ) echo 'selected="selected"'; ?>>Diablo</option>
			</select>
				
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'gameRegion' ); ?>">Region</label>

			<select id="<?php echo $this->get_field_id( 'gameRegion' ); ?>" name="<?php echo $this->get_field_name( 'gameRegion' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'US' == $instance['gameRegion'] ) echo 'selected="selected"'; ?>>US</option>
				<option <?php if ( 'EU' == $instance['gameRegion'] ) echo 'selected="selected"'; ?>>EU</option>
			</select>
				
		</p>
		
		
		<?php
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['game_to_show'] = $new_instance['game_to_show'];
		$instance['items_to_show'] = $new_instance['items_to_show'];
		$instance['showForumOrigin'] = $new_instance['showForumOrigin'];
		$instance['showDatePost'] = $new_instance['showDatePost'];	
		$instance['gameRegion'] = $new_instance['gameRegion'];	
		$instance['liveFeed'] = $new_instance['liveFeed'];
		
		return $instance;
	}
	
	function str_insert($insertstring, $intostring, $offset) {
	   $part1 = substr($intostring, 0, $offset);
	   $part2 = substr($intostring, $offset);
	  
	   $part1 = $part1 . $insertstring;
	   $whole = $part1 . $part2;
	   return $whole;
	}

	function getfileurl($region, $game)
	{
		if($region === 'US'){		
			if($game  === 'Starcraft')
				return "http://www.wrclan.com/wp-content/plugins/BlizzBlueWidget/sc2Tracker.txt";
				else if ($game === 'Warcraft')
					return "http://www.wrclan.com/wp-content/plugins/BlizzBlueWidget/WoWTracker.txt";
					else if ($game === 'Diablo')
						return "http://www.wrclan.com/wp-content/plugins/BlizzBlueWidget/D3Tracker.txt";
					
		}else if ($region === 'EU'){
			if($game  === 'Starcraft')
				return "http://www.wrclan.com/wp-content/plugins/BlizzBlueWidget/EUsc2Tracker.txt";
				else if ($game === 'Warcraft')
					return "http://www.wrclan.com/wp-content/plugins/BlizzBlueWidget/EUWoWTracker.txt";
					else if ($game === 'Diablo')
						return "http://www.wrclan.com/wp-content/plugins/BlizzBlueWidget/EUD3Tracker.txt";
		}
		
		//If nothing set return sc2Tracker
		return "http://www.wrclan.com/wp-content/plugins/BlizzBlueWidget/sc2Tracker.txt";
	
	}
	function get_liveFeed($region, $game)
	{
		$url = "http://us.battle.net/sc2/en/forum/blizztracker/";
	
		if($region === 'US'){		
			if($game  === 'Starcraft')
				$url = "http://us.battle.net/sc2/en/forum/blizztracker/";
				else if ($game === 'Warcraft')
					$url = "http://us.battle.net/wow/en/forum/blizztracker/";
					else if ($game === 'Diablo')
						$url = "http://us.battle.net/d3/en/forum/blizztracker/";
					
		}else if ($region === 'EU'){
			if($game  === 'Starcraft')
				$url = "http://eu.battle.net/sc2/en/forum/blizztracker/";
				else if ($game === 'Warcraft')
					$url = "http://eu.battle.net/wow/en/forum/blizztracker/";
					else if ($game === 'Diablo')
						$url = "http://eu.battle.net/d3/en/forum/blizztracker/";
		}

		

  		$ch = curl_init();
 		$timeout = 5;
  		curl_setopt($ch,CURLOPT_URL,$url);
  		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  		$data = curl_exec($ch);
  		curl_close($ch);

  		return $data;
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		
		/*If you’re using something like a text input in your form and users shouldn’t add XHTML to it, then you should add the value to the strip_tags() function*/

		/* User-selected settings. */
		
		$title = apply_filters('widget_title', $instance['title'] );
		$gameToShow = $instance['game_to_show'];
		$itemsToShow = strip_tags($instance['items_to_show']);
		$showForumOrigin = $instance['showForumOrigin'];
		$showDatePost = $instance['showDatePost'];
		$gameRegion = $instance['gameRegion'];
		$liveFeed = $instance['liveFeed'];
		
		/* Before widget (defined by themes). */
		echo $before_widget;
		/* Title of widget (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		
		
		// WIDGET CODE GOES HERE
		if($liveFeed === 'No'){
			$fileURL = $this->getFileURL($gameRegion,$gameToShow);
			$myFile = file_get_contents($fileURL);
		}else{
			$myFile = $this->get_liveFeed($gameRegion,$gameToShow);
		}
		
		//Start from <tbody class="bluetracker-body">
		//End at </tbody>
		$myStart = strpos($myFile,"<tbody class=\"bluetracker-body\">");
		if($myStart === false){
			if($liveFeed === 'No'){
				$myFile = $this->get_liveFeed($gameRegion,$gameToShow);				
			}else{
				$fileURL = $this->getFileURL($gameRegion,$gameToShow);
				$myFile = file_get_contents($fileURL);				
			}
			
			$myStart = strpos($myFile,"<tbody class=\"bluetracker-body\">");
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
			if($gameRegion === 'US'){
				if($gameToShow  === 'Starcraft')
				$tempContent = str_replace ("href=\"../"," target=\"_blank\" title=\"Post Author: " . $blueName . "\" href=\"http://us.battle.net/sc2/en/forum/",$tempContent);
				else if ($gameToShow === 'Warcraft')
					$tempContent = str_replace ("href=\"../"," target=\"_blank\" title=\"Post Author: " . $blueName . "\" href=\"http://us.battle.net/wow/en/forum/",$tempContent);
					else if ($gameToShow === 'Diablo')
						$tempContent = str_replace ("href=\"../"," target=\"_blank\" title=\"Post Author: " . $blueName . "\" href=\"http://us.battle.net/d3/en/forum/",$tempContent);		
			}else if($gameRegion === 'EU'){
				if($gameToShow  === 'Starcraft')
					$tempContent = str_replace ("href=\"../"," target=\"_blank\" title=\"Post Author: " . $blueName . "\" href=\"http://eu.battle.net/sc2/en/forum/",$tempContent);
					else if ($gameToShow === 'Warcraft')
						$tempContent = str_replace ("href=\"../"," target=\"_blank\" title=\"Post Author: " . $blueName . "\" href=\"http://eu.battle.net/wow/en/forum/",$tempContent);
						else if ($gameToShow === 'Diablo')
							$tempContent = str_replace ("href=\"../"," target=\"_blank\" title=\"Post Author: " . $blueName . "\" href=\"http://eu.battle.net/d3/en/forum/",$tempContent);	
			}
			
			//Add Blizz logo in title			
			$tempContent = $this->str_insert("<img src=\"http://us.battle.net/sc2/static/images/layout/cms/icon_blizzard.gif\" title=\"Post Author: " . $blueName . "\"></img>", $tempContent, 18);

			
			echo $tempContent;
		}		 
		
		

		/* After widget (defined by themes). */
		echo $after_widget;
	}
	
	
}