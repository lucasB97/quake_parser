<?php
require_once 'Game.php';
require_once 'Parser.php';

$script = new Parser("games.log");
$script->getRelatorio();



?>