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
while ( true ) {
	logme ( "polling" );
	$objFileLog = new UploadFileLog ();
	$isQueuedItem = $objFileLog->getQueuedItem ( 'FX' );
	if (count ( $isQueuedItem ) > 0) {
		$objTeam = new Teams ();
		$teams = $objTeam->getTeamIds ();
		print_r ( $teams );
		
		$filePath = $isQueuedItem ['filename'];
		$uploadId = $isQueuedItem ['id'];
		
		$fileObject = new CSVFile ( __DIR__ . "/" . $filePath, true, ',', '"', '\\', true );
		$insertArray = [ ];
		$indexCount = 1;
		$fileObject->seek ( PHP_INT_MAX );
		$totalCount = $fileObject->key ();
		$fileObject->seek ( 0 );
		
		$proccessed = 0;
		$objFileLog->startProcessing ( $uploadId, $totalCount, 'IP' );
		logme ( "Start Processing on " . date ( 'Y-M-D H:i:s' ) );
		foreach ( $fileObject as $row ) {
			
		
			$assoc = [ ];
			$assoc ['match_year'] = trim ( $row ['year'] );
			;
			$assoc ['round_number'] = trim ( $row ['round'] );
			try {
				$matchDate = new \DateTime($row['date']." ".$row['time']);
				$assoc ['match_date'] = $matchDate->format('Y-m-d H:i:s');
			} catch ( \Exception $e ) {
				$assoc ['match_date'] = null;
			}
			
			$team1 = trim ( $row ['team1'] );
			$team1Id = array_search ( strtolower ( $team1 ), $teams );
			
			/**
			 * Team Not Found..
			 * need to insert get the id back
			 */
			if ($team1Id === false) {
				$team1Id = $objTeam->createTeam ( $team1 );
				$teams [$team1Id] = strtolower ( $team1 );
			}
			
			$assoc ['team1'] = $team1Id;
			
			$team2 = trim ( $row ['team2'] );
			$team2Id = array_search ( strtolower ( $team2 ), $teams );
			
			/**
			 * Team Not Found..
			 * need to insert get the id back
			 */
			if ($team2Id === false) {
				$team2Id = $objTeam->createTeam ( $team2 );
				$teams [$team2Id] = strtolower ( $team2 );
			}
			$assoc ['team2'] = $team2Id;
			
			$assoc ['location'] = trim ( $row ['location'] );
			$assoc ['home_flag'] = ($row ['homeflag']  == 'Home') ? 1 : 0;
			$assoc ['team1_score'] = trim ( $row ['team1_score'] );
			$assoc ['team2_score'] = trim ( $row ['team2_score'] );
			$insertArray [] = $assoc;
			$indexCount ++;
			if ($indexCount % 50 == 0) {
				logme ( "updating fixture table" );
				$objTeam->createFixture ( $insertArray );
				$insertArray = [ ];
				$indexCount = 1;
				logme ( "$proccessed completed .  " . ($totalCount - $proccessed) . " is pending..." );
			}
			$proccessed ++;
		}
		if (count ( $insertArray ) > 0) {
			$objTeam->createFixture ( $insertArray );
		}
		$objFileLog->startProcessing ( $uploadId, $totalCount, 'C' );
		logme ( "Job Completed    $proccessed completed .  " . ($totalCount - $proccessed) . " is pending..." );
	} else {
		logme ( "I didnt find any thing to process" );
	}
	
	sleep ( 30 );
}
function logme($message) {
	echo "\n$message\n";
}
