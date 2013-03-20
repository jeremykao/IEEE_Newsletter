<?php
	require "database.php";
	
	$headlinesArr = grabHeadlines();
	foreach ($headlinesArr as $headline){
			$headlineVal = str_replace(" ", "-", $headline); 
			echo "<label><input type=\"checkbox\" name=\"postsToRemove\" value=\"$headlineVal\" />$headline</label>";
	}
	
?>
