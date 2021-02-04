<?php
	/*\
	 * Reads the images in a given directory and inserts them as emotes into the database with the same name
	 * Intended for alcosmos.ddns.net?tool=emotes
	 * 
	 * A part of alcosmos.ddns.net
	\*/
	
	if (true) { // True to NOT execute
		return;
	}
	
	$route = '../public/emotes/img/2/3/';
	
	include('../../../config.php');
	
	echo 'Directory scanner and database inserter by Alcosmos<br>';
	echo 'Scanning "'.$route.'"...<br>';
	$files = scandir($route);
	echo count($files).' files found<br><br>';
	
	foreach($files as $thisName) {
		if ($thisName != '.' && $thisName != '..') {
			$thisFile = file_get_contents($route.$thisName);
			
			$thisNameExploded = array_values(explode('.', $thisName, 2))[0];
			
			$query = $pdo -> prepare("INSERT INTO emotes (name, emote) VALUES (?, ?);");
			$query -> bindParam(1, $thisNameExploded, PDO::PARAM_STR);
			$query -> bindParam(2, $thisFile, PDO::PARAM_STR);
			
			if ($query -> execute()) {
				echo 'Inserted "'.$thisName.'" as "'.$thisNameExploded.'"<br>';
			} else {
				echo '<b>ERROR</b> inserting '.$thisName.': ';
				print_r($query->errorInfo());
				echo '<br>';
			}
		}
	}
	
	echo '<br>';
	
	$currentDate = date('F j, Y H:i \(T O, e\)');
	
	$query = $pdo -> prepare("UPDATE config SET value = ? WHERE id = 'update_emotes';");
	$query -> bindParam(1, $currentDate, PDO::PARAM_STR);
	if ($query -> execute()) {
		echo 'Update date updated to "'.$currentDate.'"<br>';
	} else {
		echo '<b>ERROR</b> updating update date '.$thisName.': ';
		print_r($query->errorInfo());
		echo '<br>';
	}
	
	echo 'All done<br>';
?>
