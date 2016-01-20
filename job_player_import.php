<?php
/**
 * SET FOREIGN_KEY_CHECKS=0;
TRUNCATE team;
TRUNCATE player;
 */
require_once 'vendor/autoload.php';
use Subash\Classes\CSVFile;
use Subash\Classes\Teams;
use Subash\Classes\Player;
use Subash\Classes\UploadFileLog;
while (true) {
    logme("polling");
    $objFileLog = new UploadFileLog();
    $isQueuedItem = $objFileLog->getQueuedItem('PLD');
    if (count($isQueuedItem) > 0) {
        $objTeam = new Teams();
        $teams = $objTeam->getTeamIds();
        print_r($teams);
        $objPlayer = new Player();
        $players = $objPlayer->getPlayerIds();
        $filePath = $isQueuedItem['filename'];
        $uploadId = $isQueuedItem['id'];
        
        $fileObject = new CSVFile(__DIR__ . "/" . $filePath, true, ',', '"', '\\', true);
        $insertArray = [];
        $indexCount = 1;
        $fileObject->seek(PHP_INT_MAX);
        $totalCount = $fileObject->key();
        $fileObject->seek(0);
        
        $proccessed = 0;
        $objFileLog->startProcessing($uploadId, $totalCount, 'IP');
logme("Start Processing on ".date('Y-M-D H:i:s'));
        foreach ($fileObject as $row) {
            $playerName = $row['player'];
            if (empty($playerName)) {
                die("Player Name is empty.script stopped");
            }
            $teamName = $row['team'];
            
            $teamId = array_search(strtolower($teamName), $teams);
            
            $playerId = array_search(strtolower($playerName), $players);
            /**
             * Team Not Found..
             * need to insert get the id back
             */
            if ($teamId === false) {
                $teamId = $objTeam->createTeam($teamName);
                $teams[$teamId] = strtolower($teamName);
            }
            if ($playerId === false) {
                $playerId = $objPlayer->createPlayer($playerName);
                $players[$playerId] = strtolower($playerName);
            }
            $assoc = [];
            $assoc['team'] = $teamId;
            $assoc['player'] = $playerId;
            $assoc['player_type'] = 'SC';
            
            $assoc['player_position'] = trim($row['position']);
            $assoc['player_score_year'] = trim($row['year']);
            
            $assoc['player_round'] = trim($row['round']);
            $assoc['player_price'] = trim($row['price']);
            $assoc['player_score_val'] = trim($row['score']);
            $insertArray[] = $assoc;
            $indexCount ++;
            if ($indexCount % 50 == 0) {
                logme("updating player score table");
                $objPlayer->createPlayerScore($insertArray);
                $insertArray = [];
                $indexCount = 1;
                logme("$proccessed completed .  ".($totalCount-$proccessed)." is pending...");
            }
            $proccessed++;
        }
        if (count($insertArray) > 0) {
            $objPlayer->createPlayerScore($insertArray);
        }
        $objFileLog->startProcessing($uploadId, $totalCount, 'C');
        logme("Job Completed    $proccessed completed .  ".($totalCount-$proccessed)." is pending...");
    } else 
    {
        logme("I didnt find any thing to process");
    }
    
    sleep(30);
}

function logme($message)
{
    echo "\n$message\n";
}
