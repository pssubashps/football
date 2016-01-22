<?php

namespace Subash\Classes;

use Subash\Classes\Database;

class Teams extends Database {
	public function getTeamIds() {
		$this->getConnection ();
		
		$sql = "SELECT * From team";
		$result = $this->dbLink->query ( $sql );
		$existingTeams = [ ];
		if ($result->num_rows > 0) {
			// output data of each row
			while ( $row = $result->fetch_assoc () ) {
				$existingTeams [$row ['id']] = strtolower ( $row ['name'] );
			}
		}
		return $existingTeams;
	}
	public function createTeam($teamName) {
		$this->getConnection ();
		if (! ($stmt = $this->dbLink->prepare ( "INSERT INTO team(name) VALUES (?)" ))) {
			echo "Prepare failed: Team";
			exit ();
		}
		if (! $stmt->bind_param ( "s", $teamName )) {
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
	public function createFixture($assocArray) {
		$this->getConnection ();
		if (! ($stmt = $this->dbLink->prepare ( "INSERT INTO 2016_afl_table(match_year,round_number,match_date,team1,team2,location,home_flag,team1_score,team2_score) VALUES (?,?,?,?,?,?,?,?,?)" ))) {
			printf ( "Error: %s.\n", $this->dbLink->error );
			echo "Prepare failed:player score";
			exit ();
		}
		$stmt->bind_param ( "iisiisiii", $match_year, $round_number, $match_date, $team1, $team2, $location, $home_flag, $team1_score, $team2_score );
		foreach ( $assocArray as $assoc ) {
			$match_year = $assoc ['match_year'];
			$round_number = $assoc ['round_number'];
			
			$match_date = $assoc ['match_date'];
			
			$team1 = $assoc ['team1'];
			
			$team2 = $assoc ['team2'];
			$location = $assoc ['location'];
			$home_flag = $assoc ['home_flag'];
			
			$team1_score = $assoc ['team1_score'];
			$team2_score = $assoc ['team2_score'];
			
			if (! $stmt->execute ()) {
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				exit ();
			}
		}
		$stmt->close ();
		$this->closeConnection ();
	}
	public function getUpcomingmatches($teamId) {
		$curDate = new \DateTime ( 'now' );
		$this->getConnection ();
		$sql = "SELECT 
    afl.*,t1.name as team1_name,t2.name as team2_name
FROM
    2016_afl_table AS afl JOIN team t1 ON t1.id = afl.team1 JOIN team t2 ON t2.id = afl.team2
WHERE
    afl.match_date > '" . $curDate->format ( 'Y-m-d H:i:s' ) . "'
        AND (afl.team1 = $teamId OR afl.team1 = $teamId) 
ORDER BY afl.match_date
LIMIT 3";
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
	public function getLastmatches($teamId) {
		$curDate = new \DateTime ( 'now' );
		$this->getConnection ();
		$sql = "SELECT
     afl.*,t1.name as team1_name,t2.name as team2_name
FROM
    2016_afl_table AS afl JOIN team t1 ON t1.id = afl.team1 JOIN team t2 ON t2.id = afl.team2
WHERE
    afl.match_date < '" . $curDate->format ( 'Y-m-d H:i:s' ) . "'
	    AND (afl.team1 = $teamId OR afl.team1 = $teamId) 
	    ORDER BY afl.match_date desc
	    LIMIT 3";
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