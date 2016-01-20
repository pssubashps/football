<?php
namespace Subash\Classes;

use Subash\Classes\Database;

class Player extends Database
{

    public function getPlayerIds()
    {
        $this->getConnection();
        
        $sql = "SELECT * From player";
        $result = $this->dbLink->query($sql);
        $existingTeams = [];
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $existingTeams[$row['id']] = strtolower($row['name']);
            }
        }
        $this->closeConnection();
        return $existingTeams;
    }

    public function createPlayer($playerName)
    {
        $this->getConnection();
        if (! ($stmt = $this->dbLink->prepare("INSERT INTO player(name) VALUES (?)"))) {
            echo "Prepare failed palyer ";
            exit();
        }
        if (! $stmt->bind_param("s", $playerName)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            exit();
        }
        if (! $stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            exit();
        }
        $insertId = $stmt->insert_id;
        $stmt->close();
        $this->closeConnection();
        return $insertId;
    }

    public function createPlayerScore($assocArray)
    {
       
        $this->getConnection();
        if (! ($stmt = $this->dbLink->prepare("INSERT INTO player_score(team,player,player_type,player_position,player_score_year,player_round,player_score_val,player_price) VALUES (?,?,?,?,?,?,?,?)"))) {
            printf("Error: %s.\n", $this->dbLink->error);
            echo "Prepare failed:player score";
            exit();
        }
        $stmt->bind_param("iisssiii", $team, $player, $player_type, $player_position, $player_score_year, $player_round, $player_score_val, $player_price);
        foreach ($assocArray as $assoc) {
            $team = $assoc['team'];
            $player = $assoc['player'];
            $player_type = $assoc['player_type'];
            
            $player_position = $assoc['player_position'];
            $player_score_year = $assoc['player_score_year'];
            
            $player_round = $assoc['player_round'];
            $player_price = $assoc['player_price'];
            $player_score_val = $assoc['player_score_val'];
            if (! $stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                exit();
            }
        }
        $stmt->close();
        $this->closeConnection();
    }
}