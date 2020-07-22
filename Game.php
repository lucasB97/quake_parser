<?php

class Game{

	private $id;
	private $total_kills;
	private $players = array();
    private $kills = array();

    public function getId(){
    	return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTotalKills(){
    	return is_null($this->total_kills) ? 0 : $this->total_kills;
    }

    public function setTotalKills($total_kills) {
        $this->total_kills = $total_kills;
    }

    public function getPlayers() {
        return $this->players;
    }

    public function setPlayers($players) {
    	//vai exibir "[]" no JSON
        $this->players[] = $players;
    }

    public function getKills(){
    	return $this->kills;
    }

    public function setKills($kills){
    	$this->kills = $kills

    }

    public function adicionaKill() {
        $this->setTotalKills($this->getTotalKills() + 1);
    }

    public function JSON() {
        $data = array(
            'total_kills' => $this->getTotalKills(),
            'players' => $this->getPlayers(),
            'kills' => $this->getKills());
        $game = array('game_' . $this->getId() => $data);
        return json_encode($game, JSON_PRETTY_PRINT);
    }


}

?>