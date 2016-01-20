<?php

namespace Subash\Classes;

use Subash\Classes\Database;

class UploadFileLog extends Database {
	public function getQueuedItem($type) {
		$this->getConnection ();
		
		$sql = "SELECT * From upload_file_log where upload_status = 'Q' and upload_type = '" . $type . "'LIMIT 1";
		$result = $this->dbLink->query ( $sql );
		$existingTeams = [ ];
		if ($result->num_rows > 0) {
			// output data of each row
			while ( $row = $result->fetch_assoc () ) {
				$existingTeams ['id'] = $row ['id'];
				$existingTeams ['filename'] = $row ['upload_file'];
			}
		}
		return $existingTeams;
	}
	public function createUploadFileLog($fileName, $total = 0,$type = 'PLD') {
		$this->getConnection ();
		if (! ($stmt = $this->dbLink->prepare ( "INSERT INTO upload_file_log(upload_type,upload_file,upload_status,upload_total_rows	) VALUES (?,?,?,?)" ))) {
			echo "Prepare failed: File Log";
			exit ();
		}
		if (! $stmt->bind_param ( "sssi", $type, $fileName, $status, $total )) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			exit ();
		}
		$type = $type;
		$status = "Q";
		if (! $stmt->execute ()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			exit ();
		}
		$insertId = $stmt->insert_id;
		$stmt->close ();
		$this->closeConnection ();
		return $insertId;
	}
	public function startProcessing($id, $total, $status) {
		$this->getConnection ();
		$sql = "UPDATE upload_file_log SET upload_status='" . $status . "',upload_total_rows=$total WHERE id=$id";
		$this->dbLink->query ( $sql );
		$this->closeConnection ();
	}
}