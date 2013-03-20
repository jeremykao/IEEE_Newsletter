<?php   //database.php

/***************** Global Constants ***********************/

		$host = "localhost";
		$user = "jeremyka";
		$pass = "p@ssword123";
		$db = "ieee";		
		$table = "newsletter";
		$editionTable = "edition";

		$postLimit = 10;
		$postOffset = 0;
		$postObjArr = [];
		$showErrors = false;
		$connectMessage = "";

/***************** End Gloabl Constants *****************/

/**************************** Functions for Modifying Database *************************/

		function createTables(){
				global $table, $editionTable;
				$query = "CREATE TABLE $table (eventNum SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, headline VARCHAR(64), event TEXT) Engine=MyISAM;";
				$result = mysql_query($query);
				$query = "CREATE TABLE $editionTable (id TINYINT PRIMARY KEY, weekNum TINYINT UNSIGNED, quarterType VARCHAR(10)) Engine=MyISAM;";
				mysql_query($query);
				$query = "INSERT INTO $editionTable(id, weekNum, quarterType) VALUES(1, 1, 'Fall');";
				return mysql_query($query);
		}
		function updateEdition($weekNumber, $quarterType){
				global $editionTable;
				$weekNumber = (int) $weekNumber;
				$query = "UPDATE $editionTable SET weekNum=$weekNumber, quarterType='$quarterType' WHERE id=1;";
				return mysql_query($query);
		}
		function addToDatabase($headline, $description){
				global $table;
				//Checking for duplicates;
				$checkQuery = "SELECT * FROM $table WHERE headline='$headline';";
				$query = mysql_query($checkQuery);
				if ( mysql_num_rows($query) <= 0 ){
					$query = "INSERT INTO $table (headline, event) VALUES('$headline', '$description');";
					return mysql_query($query);
				}
				else{
					$query = "UPDATE $table SET event='$description' WHERE headline='$headline';";
					return mysql_query($query);
				}
		}	
		function removeFromDatabase($headline){
				global $table;
				$query = "DELETE FROM $table WHERE headline = '$headline';";
				return mysql_query($query);
		}
		function removeAllFromDatabase(){
				global $table;
				$query = "TRUNCATE $table;";
				return mysql_query($query);
		}

/*********************** End of Functions for Modifying Database ********************/

/*********************** Functions for Grabbing from Database ***********************/
		function grabContents(){
				global $table;
				$events = "";
				$query = "SELECT event FROM $table ORDER BY eventNum DESC;";
				$result = mysql_query($query);
				if ( !$result ){
						$numEntries = mysql_num_rows($result);
						if ($numEntries == 0)
								echo "No Events in the Database.";
						else
								echo "Unable to grab Posts.";
						return 0;
				}
				$numRows = mysql_num_rows( $result );
				for ( $i = 0; $i < $numRows; $i++ ){
						$row = mysql_fetch_row( $result );
						$events .= $row[0];
				}
				return $events;
		}
		function grabHeadlines(){
				global $table;
				$events = array();
				$query = "SELECT headline FROM $table ORDER BY eventNum DESC;";
				$result = mysql_query($query);
				if (!$result){
						$numEntries = mysql_num_rows($result);
						if ($numEntries == 0)
								echo "No Events in the Database.";
						else
								echo "Unable to grab Posts.";
						return 0;
				}
				$numRows = mysql_num_rows( $result );
				for ( $i = 0; $i < $numRows; $i++ ){
						$row = mysql_fetch_row( $result );
						array_push($events, $row[0]);
				}
				return $events;
		}
		function grabEdition(){
			$query = "SELECT weekNum, quarterType FROM edition WHERE id=1;";
			$result = mysql_query($query);

			return mysql_fetch_row($result);
		}
/******************** End of Functiosn for Grabbing from Database *******************/

$connection = mysql_connect($host, $user, $pass);
if ( $connection ){
    $selectedDB = mysql_select_db($db);
		if ( $selectedDB ){
				$connectMessage .= "Database Successfully Created and Connected.";
				$tableCreated = createTables();
				if ( $tableCreated )
						$connectMessage .= "Table Successfully Created.";
				else
						$connectMessage .= "Table Not Created.";
		}
		else
				$connectMessage .= "Database does not exist.";
}
else{
		$connectMessage .= "Cannot connect to MySQL.";
}


if ($showErrors == true){
		echo $connectMessage;
}
?>
