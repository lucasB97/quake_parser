<?php
require_once 'Game.php';
require_once 'Parser.php';

$parser = new Parser("games.log");
$parser->exibirJsonGame();
?>