<?php
use Subash\Classes\Player;
require_once 'vendor/autoload.php';

$player = new Player();
header("content-type:application/json");
echo json_encode($player->getPlayers($_GET['term']));exit();