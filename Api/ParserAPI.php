<?php
class ParserAPI{

	private $fileName;

	public function __construct(){

		$this->fileName = "../games.log";
		//"REQUEST_METHOD" Contém o método de request utilizando para acessar a página
		$method = $_SERVER['REQUEST_METHOD'];
        $param_id = str_replace("/quake_parser/Api/", "", $_SERVER['REQUEST_URI']);
        if ($method == 'GET'){
             $this->getGameId($param_id);  
        }

	}

	private function getGameId($id) {
        require_once '../Game.php';
        require_once '../Parser.php';

        $parser = new Parser($this->fileName);
        $games = $parser->getJsonGame();
        if (count($games) >= $id) {
            echo $games[$id - 1];
        }
    }


}



?>