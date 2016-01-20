<?php
namespace Subash\Classes;

use Subash\Classes\Database;

class Teams extends Database
{

    public function getTeamIds()
    {
        $this->getConnection();
        
        $sql = "SELECT * From team";
        $result = $this->dbLink->query($sql);
        $existingTeams = [];
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $existingTeams[$row['id']] = strtolower($row['name']);
            }
        }
        return $existingTeams;
    }

    public function createTeam($teamName)
    {
        $this->getConnection();
        if (! ($stmt = $this->dbLink->prepare("INSERT INTO team(name) VALUES (?)"))) {
            echo "Prepare failed: Team";
            exit();
        }
        if (! $stmt->bind_param("s", $teamName)) {
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
}