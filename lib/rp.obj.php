<?php
class rp {
	function __construct($db, $id_rp) {
		$this->db = $db;
		$this->rp = $id_rp;
	}
	function alterouPassword(){
		$query ="SELECT alterou_password FROM rps WHERE rps.id = '".$this->rp."'";
		$res = $this->db->query($query);
		return $res[0]['alterou_password'];
	}
	function permissao()
	{
		$query = "SELECT rps_cargos.cartao_sem_consumo FROM rps INNER JOIN rps_cargos ON rps.id_cargo = rps_cargos.id WHERE rps.id = '" . $this->rp . "'";
		$res = $this->db->query($query);

		if ($res) {
			return $res[0]['cartao_sem_consumo'];
		}
	}
	function permissaoPrivados()
	{
		$where = " AND rps.id_cargo in (1,5)";
		$query = "SELECT rps.* FROM rps WHERE rps.id = '" . $this->rp . "' $where ORDER BY nome";
		$res = $this->db->query($query);

		if ($res) {
			return 1;
		}
	}
	function permissaoDisponibilidadeMesas()
	{
		$query = "SELECT rps.disponibilidade_mesas FROM rps WHERE rps.id = '" . $this->rp . "' ORDER BY nome";
		$res = $this->db->query($query);

		if ($res[0]['disponibilidade_mesas'] == 1) {
			return 1;
		}
	}

	function devolveCargo(){

		$query = "SELECT rps.id_cargo FROM rps WHERE rps.id = '" . $this->rp . "'";
		$res = $this->db->query($query);

		if($res){
 			return $res[0]['id_cargo'];
		}
	}
	function devolvePassword(){
		$query ="SELECT password FROM rps WHERE rps.id = '".$this->rp."'";
		$res = $this->db->query($query);
		return $res[0]['password'];
	}

	function listaRps(){
		$query ="SELECT * FROM rps ORDER BY rps.nome ASC" ;
		$res = $this->db->query($query);
		return $res;
	}


	function devolveNomeRp($id){
		$query ="SELECT * FROM rps WHERE id = ".$id." ORDER BY rps.nome ASC";
		$res = $this->db->query($query);
		return $res[0]['nome'];
	}

	function devolveInfo(){
		$sql = "SELECT rps.foto, rps.nome, rps.id, rps_cargos.nome as nome_cargo FROM rps LEFT JOIN rps_cargos ON rps.id_cargo = rps_cargos.id WHERE rps.id = ".$this->rp." ";
		$res = $this->db->query($sql);
		if($res){
			if($res[0]['foto'] && file_exists($_SERVER['DOCUMENT_ROOT']."/fotos/rps/".$res[0]['foto'])){
				$res[0]['foto'] = "/fotos/rps/".$res[0]['foto'];
			}
			else{
				unset($res[0]['foto']);
			}
			return $res[0];
		}
	}
	#cartões consumo obrigatorio
	function validaCartaoObrigatorio($data, $id = 0){
		if($id > 0){
			$where = " AND rps_cartoes_consumo_obrigatorio.id != ".$id." ";
		}
		$sql = "SELECT count(id) as conta FROM rps_cartoes_consumo_obrigatorio WHERE rps_cartoes_consumo_obrigatorio.id_rp = " . $this->rp . " $where AND rps_cartoes_consumo_obrigatorio.data_evento = '".$data."' ";
		$res = $this->db->query($sql);
		return $res[0]['conta'];
	}
	function devolveCartoesConsumoObrigatorio($entrou = false, $data_evento = false){

		$query = "";
		if ($entrou) {
			$query .= " AND rps_cartoes_consumo_obrigatorio.entrou = 1 ";
		}
		if($data_evento){
			$query .= " AND rps_cartoes_consumo_obrigatorio.data_evento = '".$data_evento."'";
		}

		$sql = "SELECT * FROM rps_cartoes_consumo_obrigatorio WHERE rps_cartoes_consumo_obrigatorio.id_rp = " . $this->rp." $query ORDER BY data_evento DESC";
		$res = $this->db->query($sql);
		if ($res) {
			return $res;
		}

	}
	function apagaCartaoConsumoObrigatorio($id){

		$sql = "DELETE FROM rps_cartoes_consumo_obrigatorio WHERE rps_cartoes_consumo_obrigatorio.id = " . $id . " AND rps_cartoes_consumo_obrigatorio.id_rp = " . $this->rp;
		$res = $this->db->query($sql);
        if ($res) {
            return $res;
        }
	}
	function devolveCartaoConsumoObrigatorio($id)
	{

		$sql = "SELECT * FROM rps_cartoes_consumo_obrigatorio WHERE rps_cartoes_consumo_obrigatorio.id = ".$id." AND rps_cartoes_consumo_obrigatorio.id_rp = " . $this->rp;
		$res = $this->db->query($sql);
		if($res){
			return $res[0];
		}

	}

	#cartões sem consumo obrigatorio
	function validaCartaoSemConsumo($data, $id = 0)
	{
		if ($id > 0) {
			$where = " AND rps_cartoes_sem_consumo.id != " . $id . " ";
		}
		$sql = "SELECT count(id) as conta FROM rps_cartoes_sem_consumo WHERE rps_cartoes_sem_consumo.id_rp = " . $this->rp . " $where AND rps_cartoes_sem_consumo.data_evento = '" . $data . "' ";
		$res = $this->db->query($sql);
		return $res[0]['conta'];
	}
	function devolveCartoesSemConsumo($entrou = false, $data_evento = false)
	{
		$query ="";
		if($entrou){
			$query .= " AND rps_cartoes_sem_consumo.entrou = 1 ";
		}
		if($data_evento){
			$query .= " AND rps_cartoes_sem_consumo.data_evento = '".$data_evento."'";
		}

		$sql = "SELECT * FROM rps_cartoes_sem_consumo WHERE rps_cartoes_sem_consumo.id_rp = " . $this->rp . " $query ORDER BY data_evento DESC";
		$res = $this->db->query($sql);
		if ($res) {
			return $res;
		}

	}
	function apagaCartaoSemConsumo($id)
	{

		$sql = "DELETE FROM rps_cartoes_sem_consumo WHERE rps_cartoes_sem_consumo.id = " . $id . " AND rps_cartoes_sem_consumo.id_rp = " . $this->rp;
		$res = $this->db->query($sql);
		if ($res) {
			return $res;
		}
	}
	function devolveCartaoSemConsumo($id)
	{

		$sql = "SELECT * FROM rps_cartoes_sem_consumo WHERE rps_cartoes_sem_consumo.id = " . $id . " AND rps_cartoes_sem_consumo.id_rp = " . $this->rp;
		$res = $this->db->query($sql);
		if ($res) {
			return $res[0];
		}

	}

	function listaEventosRP(){

		require_once($_SERVER['DOCUMENT_ROOT'] . '/administrador/pagamentos/pagamentos.obj.php');
		$dbpagamentos = new pagamentos($this->db);

		if(date('H') < 14){
			$data_actual = date('Y-m-d', strtotime('-1 day'));
		}
		else{
			$data_actual = date('Y-m-d');
		}
		$query = "SELECT data_evento FROM rps_entradas WHERE data_evento >= " . date("Y-m-d", strtotime("-1 year")) . "  GROUP BY data_evento DESC";
		$eventos = $this->db->query($query);

		if($eventos){
			foreach($eventos as $k=>$evento){
				$query = "SELECT sum(quantidade) as quantidade FROM rps_entradas  WHERE  id_rp = " . $this->rp . " AND data_evento = '".$evento['data_evento']."' GROUP BY data_evento DESC";
				$eventoRP = $this->db->query($query);
				if($evento['data_evento'] == $data_actual){
					$eventos_return[$k]['estado'] = "A decorrer";
				}
				else{
					$eventos_return[$k]['estado'] = "Passado";
				}
				$eventos_return[$k]['data_evento'] = date('d/m/Y', strtotime($evento['data_evento']));
				$eventos_return[$k]['quantidade'] = intval($eventoRP[0]['quantidade']);
				$eventos_return[$k]['cartoes_sem_consumo'] = intval(count($this->devolveCartoesSemConsumo(1, $evento['data_evento'])));
				$eventos_return[$k]['cartoes_consumo_obrigatorio'] = intval(count($this->devolveCartoesConsumoObrigatorio(1, $evento['data_evento'])));

				$eventos_return[$k]['comissao_entradas'] = $dbpagamentos->converteEntradasToEuro($eventos_return[$k]['quantidade'], $this->rp);
				$eventos_return[$k]['comissao_privados'] = $this->devolveComissaoPrivados($evento['data_evento']);

				$eventos_return[$k]['comissao_garrafas'] = $this->devolveComissaoGarrafas($evento['data_evento']);
			}
		}
		return $eventos_return;
	}
	function devolveComissaoPrivados($data_evento)
	{

        $query = "SELECT SUM(venda_privados.total) as total FROM venda_privados  WHERE venda_privados.id_rp = " .  $this->rp . " AND venda_privados.data_evento = '" . $data_evento . "' AND venda_privados.total > 50 GROUP BY venda_privados.data_evento";
        $resultado2  = $this->db->query($query);

        if ($resultado2) {
            return $resultado2[0]['total'] * 0.05;
        }
	}
	function devolveComissaoGarrafas($data_evento)
	{
		$query = "SELECT sum(venda_garrafas_bar_garrafas.quantidade) as quantidade FROM venda_garrafas_bar INNER JOIN venda_garrafas_bar_garrafas ON venda_garrafas_bar_garrafas.id_compra = venda_garrafas_bar.id  INNER JOIN garrafas ON venda_garrafas_bar_garrafas.id_garrafa = garrafas.id AND garrafas.comissao = 1 WHERE venda_garrafas_bar.id_rp = " . $this->rp . " AND venda_garrafas_bar.data_evento = '" . $data_evento . "' AND venda_garrafas_bar.total > 50 GROUP BY venda_garrafas_bar.data_evento DESC";
		$resultado  = $this->db->query($query);

		return $resultado[0]['quantidade'] * 5;
	}
	#convites
	function devolveConvites($data_evento = false)
	{

		$query = "";
		if ($data_evento) {
			$query .= " AND convites.data_evento = '" . $data_evento . "'";
		}

		$sql = "SELECT * FROM convites WHERE convites.id_rp = " . $this->rp . " $query ORDER BY data_evento DESC";
		$res = $this->db->query($sql);
		if ($res) {
			return $res;
		}
	}
	function apagaConvite($id)
	{

		$sql = "DELETE FROM convites WHERE convites.id = " . $id . " AND convites.id_rp = " . $this->rp;
		$res = $this->db->query($sql);
		if ($res) {
			return $res;
		}
	}
	function devolveConvite($id)
	{

		$sql = "SELECT * FROM convites WHERE convites.id = " . $id . " AND convites.id_rp = " . $this->rp;
		$res = $this->db->query($sql);
		if ($res) {
			return $res[0];
		}
	}
	function verificaMD5($md5){
		$sql = "SELECT count(*) as conta FROM convites WHERE convites.md5 = '" . $md5 . "'";
		$res = $this->db->query($sql);

		if ($res[0]['conta']) {
			return $res[0]['conta'];
		}
	}
	function devolvePagamentos(){

		$sql = "SELECT * FROM conta_corrente WHERE id_rp  = '" . $this->rp . "' ORDER BY data DESC";
		$res = $this->db->query($sql);
		foreach($res as $k=>$rs){
			$res[$k]['linhas'] = $this->devolveLinhasPagamento($rs['id']);
		}
		return $res;
	}
	function devolveLinhasPagamento($id_conta_corrente)
	{
		$query = "SELECT nome, descricao, valor FROM conta_corrente_linhas WHERE id_conta_corrente = '" . $id_conta_corrente . "'  ORDER BY id ASC";
		$res = $this->db->query($query);
		return $res;
	}
}
