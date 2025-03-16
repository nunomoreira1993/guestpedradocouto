<?php
class evento {
	private $db;
	private $id_rp;
	function __construct($db, $id_rp) {
		$this->db = $db;
		$this->id_rp = $id_rp;
	}
	
	function verificaConviteTelemovel($telemovel, $id_evento){
		$query ="SELECT hash FROM eventos_convites WHERE telemovel = '" . $telemovel . "' AND id_evento = '" . $id_evento . "' LIMIT 1";
		$res = $this->db->query($query);
		return $res[0]["hash"];
	}
	function getInfoConviteTelemovel($telemovel){
		$query ="SELECT nome, telemovel, email, data_nascimento, termos_condicoes, marketing FROM eventos_convites WHERE telemovel = '" . $telemovel . "' ORDER BY id DESC LIMIT 1";
		$res = $this->db->query($query);
		return $res[0];
	}
	function devolveNomeRP($id_rp) {
		require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/rp.obj.php');
		$dbrp = new rp($this->db, $this->id_rp);
		return $dbrp->devolveNomeRp($id_rp);
	}
	function devolveEvento($id){
		$query ="SELECT * FROM eventos WHERE eventos.id = '$id'";
		$res = $this->db->query($query);
		return $res[0];
	}

	function devolveEventoByMD5($id){
		$query = "SELECT * FROM eventos WHERE MD5(eventos.id) = '$id'";
		$res = $this->db->query($query);
		return $res[0];
	}
	function devolveRPByMD5($id){
		$query = "SELECT * FROM rps WHERE MD5(rps.id) = '$id'";
		$res = $this->db->query($query);
		return $res[0];
	}

	function devolveConvite($id){
		$query ="SELECT * FROM eventos_convites WHERE id = '$id' AND id_rp = '" . $this->id_rp . "'";
		$res = $this->db->query($query);
		return $res[0];
	}
	function listaConvites($id_evento = 0){
		$where = "";
		if($id_evento > 0){
			$where = " AND id_evento = '" . $id_evento . "'";
		}
		$query ="SELECT * FROM eventos_convites WHERE id_rp = '" . $this->id_rp . "' $where";
		$res = $this->db->query($query);
		return $res;
	}

	function listaEventos() {
		$sql = "SELECT * FROM eventos WHERE data >= '". date('Y-m-d', strtotime("-1 day")) ."'";
		$res = $this->db->query($sql);
		return $res;
	}
	function contaEventos(){
		$query ="SELECT count(*) as conta FROM eventos WHERE id_rp = " . $this->id_rp;
		$res = $this->db->query($query);
		return $res[0]['conta'];
	}
	function devolveEventoByHash($hash){
		$query ="SELECT * FROM eventos_convites WHERE hash = '" . $hash . "'";
		$res = $this->db->query($query);
		return $res[0];
	}
	function contaConvites($id){
		$query ="SELECT count(id) as conta FROM eventos_convites WHERE id_rp = " . $this->id_rp . " AND id_evento = " . $id;
		$res = $this->db->query($query);

		return $res[0]['conta'];
	}
	function contaBilhetes($id){
		$query ="SELECT count(id) as conta FROM eventos_convites WHERE id_rp = " . $this->id_rp  . " AND id_evento = " . $id . " AND qrcode != ''";
		$res = $this->db->query($query);
		return $res[0]['conta'];
	}
    function devolveCartaoSemConsumoByHash($hash) {
		$query ="SELECT * FROM rps_cartoes_sem_consumo WHERE md5(id) = '" . $hash . "' AND entrou = 0";
		$res = $this->db->query($query);
		return $res[0];
    }
    function devolveCartaoConsumoObrigatorioByHash($hash) {
		$query ="SELECT * FROM rps_cartoes_consumo_obrigatorio WHERE md5(id) = '" . $hash . "' AND entrou = 0";
		$res = $this->db->query($query);
		return $res[0];
    }
	function devolveMensagemDefault() {
		return "Olá {NOME}!\nAcede a este endereço para entrares na minha Guest no próximo fim de semana.\n{ENDERECO}
		\nObrigado e até já!\nPEDRA DO COUTO";
	}
}
