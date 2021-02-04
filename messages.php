<?php
	/*\
	 * Scans the subdirectories in a given directory in search of Habbo handlers and responses written in Lingo and inserts them into the database
	 * Intended for alcosmos.ddns.net?tab=habbodev&page=messages
	 * 
	 * A part of alcosmos.ddns.net
	\*/
	
	if (true) { // True to NOT execute
		return;
	}
	
	$route = 'v17/src/';
	
	include('../../../config.php');
	
	echo 'Directory scanner and database inserter by Alcosmos<br>';
	echo 'Scanning "'.$route.'"...<br>';
	$archives = scandir($route);
	echo count($archives).' directories found<br>';
	
	foreach($archives as $subRoute) {
		if ($subRoute == '.' || $subRoute == '..') {
			continue;
		}
		
		$files = scandir($route.$subRoute);
		echo '<br>'.count($files).' files found in '.$subRoute.'<br>Searching for handlers and responses...<br><br>';
		
		foreach($files as $thisName) {
			if ($thisName == '.' || $thisName == '..') {
				continue;
			}
			
			$responses = file_get_contents($route.$subRoute.'/'.$thisName);
			
			// Handlers
			
			$handlers = $responses;
			
			while (true) {
				$pos = strpos($handlers, 'tMsgs.setaProp(');
				
				if ($pos === false) {
					break;
				}
				
				$handlers = substr($handlers, $pos + 15);
				$cont = explode(')', $handlers, 2)[0];
				
				$parts = explode(',', $cont, 2);
				
				$id = $parts[0];
				$name = substr($parts[1], 2);
				
				$query = $pdo -> prepare("INSERT INTO messages_requests (id, base64, name) VALUES (?, '', ?);");
				$query -> bindParam(1, $id, PDO::PARAM_INT);
				$query -> bindParam(2, $name, PDO::PARAM_STR);
				
				if ($query -> execute()) {
					echo 'Inserted "'.$name.'" with ID '.$id.'<br>';
				} else {
					echo '<b>ERROR</b> inserting '.$name.' with ID '.$id.': ';
					print_r($query->errorInfo());
					echo '<br>';
				}
			}
			
			// End of handlers
			
			// Responses
			
			while (true) {
				$pos = strpos($responses, 'tCmds.setaProp("');
				
				if ($pos === false) {
					break;
				}
				
				$responses = substr($responses, $pos + 16);
				$cont = explode(')', $responses, 2)[0];
				
				$parts = explode('"', $cont, 2);
				
				$id = substr($parts[1], 3);
				$name = $parts[0];
				echo $id.' '.$name.'<br>';
				$query = $pdo -> prepare("INSERT INTO messages_responses (id, base64, name, file) VALUES (?, '', ?, ?);");
				$query -> bindParam(1, $id, PDO::PARAM_INT);
				$query -> bindParam(2, $name, PDO::PARAM_STR);
				$query -> bindParam(3, $subRoute, PDO::PARAM_STR);
				
				if ($query -> execute()) {
					echo 'Inserted "'.$name.'" with ID '.$id.'<br>';
				} else {
					echo '<b>ERROR</b> inserting '.$name.' with ID '.$id.': ';
					print_r($query->errorInfo());
					echo '<br>';
				}
			}
			
			// End of responses
		}
	}
	
	echo '<br>All done<br>';
?>
