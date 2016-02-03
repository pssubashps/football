<?php
use Subash\Classes\Player;
require_once 'vendor/autoload.php';
setlocale(LC_MONETARY, 'en_US.UTF-8');
$player = new Player();
header("content-type:application/json");
echo json_encode($player->getPlayers($_GET['term']));exit();