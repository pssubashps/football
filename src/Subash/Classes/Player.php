<?php

namespace Subash\Classes;

use Subash\Classes\Database;

class Player extends Database {
	public function getPlayerIds() {
		$this->getConnection ();
		
		$sql = "SELECT * From player";
		$result = $this->dbLink->query ( $sql );
		$existingTeams = [ ];
		if ($result->num_rows > 0) {
			// output data of each row
			while ( $row = $result->fetch_assoc () ) {
				$existingTeams [$row ['id']] = strtolower ( $row ['name'] );
			}
		}
		$this->closeConnection ();
		return $existingTeams;
	}
	public function createPlayer($playerName) {
		$this->getConnection ();
		if (! ($stmt = $this->dbLink->prepare ( "INSERT INTO player(name) VALUES (?)" ))) {
			echo "Prepare failed palyer ";
			exit ();
		}
		if (! $stmt->bind_param ( "s", $playerName )) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			exit ();
		}
		if (! $stmt->execute ()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			exit ();
		}
		$insertId = $stmt->insert_id;
		$stmt->close ();
		$this->closeConnection ();
		return $insertId;
	}
	public function createPlayerScore($assocArray) {
		$this->getConnection ();
		if (! ($stmt = $this->dbLink->prepare ( "INSERT INTO player_score(team,player,player_type,player_position,player_score_year,player_round,player_score_val,player_price) VALUES (?,?,?,?,?,?,?,?)" ))) {
			printf ( "Error: %s.\n", $this->dbLink->error );
			echo "Prepare failed:player score";
			exit ();
		}
		$stmt->bind_param ( "iisssiii", $team, $player, $player_type, $player_position, $player_score_year, $player_round, $player_score_val, $player_price );
		foreach ( $assocArray as $assoc ) {
			$team = $assoc ['team'];
			$player = $assoc ['player'];
			$player_type = $assoc ['player_type'];
			
			$player_position = $assoc ['player_position'];
			$player_score_year = $assoc ['player_score_year'];
			
			$player_round = $assoc ['player_round'];
			$player_price = $assoc ['player_price'];
			$player_score_val = $assoc ['player_score_val'];
			if (! $stmt->execute ()) {
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				exit ();
			}
		}
		$stmt->close ();
		$this->closeConnection ();
	}
	public function getPlayers($search) {
		$this->getConnection ();
		$sql = "SELECT 
    p.id AS player_id, p.name AS player_name,t.name as team_name,avg(player_price) as avg_player_price
FROM
    player_score ps
        JOIN
    player p ON p.id = ps.player JOIN team t ON t.id = ps.team
WHERE
    (LOWER(p.`name`) LIKE '%" . $search . "%')
        OR (LOWER(t.`name`) LIKE '%" . $search . "%')
GROUP BY p.id
LIMIT 20";
		$result = $this->dbLink->query ( $sql );
		$existingTeams = [ ];
		if ($result->num_rows > 0) {
			// output data of each row
			while ( $row = $result->fetch_assoc () ) {
			    $row['avg_player_price'] =  money_format('%n', $row['avg_player_price']);
				$existingTeams [] = $row;
			}
		}
		$this->closeConnection ();
		return $existingTeams;
	}
	public function getPlayerDetails($id) {
		$this->getConnection ();
		$sql = "SELECT
    p.id AS player_id, p.name AS player_name,t.name as team_name,ps.*
FROM
    player_score ps
        JOIN
    player p ON p.id = ps.player LEFT JOIN team t ON t.id = ps.team
WHERE
    ps.player = $id order by id desc

LIMIT 50";
		// print $sql;exit;
		$result = $this->dbLink->query ( $sql );
		$existingTeams = [ ];
		if ($result->num_rows > 0) {
			// output data of each row
			while ( $row = $result->fetch_assoc () ) {
				$existingTeams [] = $row;
			}
		}
		$this->closeConnection ();
		return $existingTeams;
	}
	public function getPlayerSummary($id, $year = '2015') {
		$this->getConnection ();
		$sql = "SELECT 
    ps.player AS player_id,
	min(player_score_val) as min_player_score_val,
    max(player_score_val) as max_player_score_val,
    avg(player_price) as avg_player_price,
	avg(player_score_val) as avg_player_score_val
   
FROM
    player_score ps
     
WHERE
    ps.player = $id AND player_score_year = '" . $year . "'
ORDER BY id DESC";
		//print $sql;exit;
		$result = $this->dbLink->query ( $sql );
		$existingTeams = [ ];
		if ($result->num_rows > 0) {
			// output data of each row
			while ( $row = $result->fetch_assoc () ) {
				$existingTeams [] = $row;
			}
		}
	
		$this->closeConnection ();
		return $existingTeams;
	}
}