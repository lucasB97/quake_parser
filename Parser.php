<?php

class Parser{

	private $filename;
	private $jsonGame = array();
    private $countGame = 0;
	private $game;
	private $kills;




	public function __construct($filename){
		$this->filename = $filename;
		$this->init();
	}

	private function init(){
		try{
			//Verifica se o arquivo existe
			if(!file_exists($this->filename)){
				throw new Exception("Arquivo não encontrado.");
			}

			//Abre o arquivo
			$arquivo = fopen($this->filename, 'r');
			//O segundo parâmetro especifica o tipo de acesso.
			//'r' abre o arquivo somente para leitura

			//Lê o arquivo
			$this->readArquivo($arquivo);

			//Fecha um ponteiro de arquivo aberto
			$this->fclose($arquivo);
		} catch(Exception $e){
			echo $e->getMessage();
		}
	}


	private function readArquivo($arquivo){
		//"feof" testa pelo fim-de-arquivo
		while (!feof($arquivo)) {
			//lê linha por linha
			$linha = $this->getLinha($arquivo);
			//Compara o comando do log
			switch ($linha['command']) {
				case 'InitGame':
					$this->initGame($linha);
                    break;
                case 'ClientUserinfoChanged':
                      $this->clientUserinfoChanged($linha);
                    break;
                case 'Kill':
                    $this->kill($linha);
                    break;
                //Essa marcação acontece após o final de todo game
                case '------------------------------------------------------------':
                case 'ShutdownGame':
                    $this->shutdownGame($linha);
                    break;
                default:
                    break;

			}
		}

	}


	private function getLinha($file) {
		//Lê uma linha do arquivo
		//'4096' remete ao tamanho da linha
		$linha = fgets($file, 4096);
		//verifica se a linha não está vazia
		if(!empty($linha)){
			$params = explode(":", trim($linha), 3);

            $time = explode(" ", $params[0], 2);
            $time = isset($time[1]) ? $time[1] : $time[0];
            $time = trim($time . ":" . $params[1]);
            $time_command = explode(" ", $time, 2);

            $result['params'] = isset($params[2]) ? $params[2] : '';
            $result['time'] = $time_command[0];
            $result['command'] = $time_command[1];
            return $result;
		}
		return false;
	}

	private function initGame($linha){
		$this->game = new Game();
		$this->kills = array();

        //adicione 1 para definir gameID
		$this->countGame = $countGame + 1;

		//Define a id do game
		$this->game->setId($this->countGame);
	}

	private function clientUserinfoChanged($linha){
		//exibe o jogador com formatação
		$player = explode('\t\\', $linha['params'], 2);
        $player = explode(' n\\', $player[0], 2);

        //Verifica se o jogador existe
        if (!in_array($player[1], $this->game->getPlayers())) {
            //define o nome do jogador
            $this->game->setPlayers($player[1]);
        }

	}

	private function kill($linha){

		//Adiciona 1 kill
		$this->game->adicionaKill();

		//Formata a kill exibida
		$value = explode(":", $linha['params'], 2);
        $value = explode("killed", $value[1]);
        $p_killer = trim($value[0]);
        $value = explode(" by ", trim($value[1]));
        $p_killed = trim($value[0]);
        $mod = trim($value[1]);

        //adiciona o player que foi morto e o player que matou
        $this->setKill($p_killer, $p_killed);
	}

	private  function shutdownGame($linha){
		if(isset($this->game)){
			//define as kills
			$this->game->setKills($this->kills);
			//coloca o json no array
			$this->jsonGame[] = $this->game->JSON();
			// "unset" destrói a variável especificada
			unset($this->game); 
		}

	}

	private function setKill($p_killer,$p_killed){
		//Se $p_killer for igual a "<world>", remove 1 kill do $p_killer
		if($p_killer == "<world>"){
			$this->kills[$p_killed] = (isset($this->kills[$p_killed]) ? $this->kills[$p_killed] : 0) - 1;
		}
		//Se o $p_killer for igual a $p_killed, é removio 1 kil do $p_killer
		else if ($p_killer == $p_killed){
			$this->kills[$p_killer] = (isset($this->kills[$p_killer]) ? $this->kills[$p_killer] : 0) - 1;

		}
		//Se o $p_killer matou o oponente, 1 kill é adicionada ao $p_killer
		else{
			$this->kills[$p_killer] = (isset($this->kills[$p_killer]) ? $this->kills[$p_killer] : 0) + 1;
		}


	}

	private function exibirJsonGame(){
		foreach ($this->jsonGame as $j) {
			echo '<pre>';
            echo  $j;
            echo '</pre>';
		}
	}

	public function getJsonGame() {
        return $this->jsonGame;
    }






}






















?>