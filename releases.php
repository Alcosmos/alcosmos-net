<?php
	/*\
	 * Lists the repositories from the database
	 * Structured and formatted for alcosmos.ddns.net?tab=releases
	 * 
	 * A part of alcosmos.ddns.net
	\*/
	
				$query = $pdo -> prepare("SELECT * FROM config WHERE id = ?;");
				$query -> execute(array("releases"));
				
				$row = $query -> fetch(PDO::FETCH_OBJ);
				
				if ($row != null) {
					echo $row->value;
				}
?>
