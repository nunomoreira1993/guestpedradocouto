<?php
class methods {
	private $hash;
	private $hash_evento;
	private $hash_rp;
	private $cartao;
	private $tipo_cartao;
	private $db;
	function __construct($hash = "", $evento = "", $rp = "", $cartao = "", $tipo_cartao = "")
	{		
		include $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
			
		$this->db = $db;
		$this->hash = $hash;
		$this->hash_evento = $evento;
		$this->hash_rp = $rp;
		$this->cartao = $cartao;
		$this->tipo_cartao = $tipo_cartao;
	}
	public function sendForm($fields)
	{
		setlocale(LC_TIME, 'pt_PT.utf-8');
		require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/evento.obj.php');
		$dbevento = new evento($this->db, $_SESSION['id_rp']);

		if (preg_match('/^[a-f0-9]{32}$/i', $this->hash) || (preg_match('/^[a-f0-9]{32}$/i', $this->hash_evento) && preg_match('/^[a-f0-9]{32}$/i', $this->hash_rp))) {
			if ($this->hash) {
				$convite = $dbevento->devolveEventoByHash($this->hash);
				if ((int) $convite["id_evento"] > 0) {
					$evento = $dbevento->devolveEvento($convite["id_evento"]);
				}
			} else if ($this->hash_rp && $this->hash_evento) {
				$evento = $dbevento->devolveEventoByMD5($this->hash_evento);
				$rp = $dbevento->devolveRPByMD5($this->hash_rp);
			}

			if (!empty($fields) && (int) $convite["qrcode"] == 0) {
				$camposComErro = [];

				// Validar o campo Nome
				$nome = $fields["nome"];
				if (empty($nome)) {
					$camposComErro[] = "nome";
				}

				// Validar o campo E-mail
				$email = $fields["email"];
				if (!empty($email)) {
					if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$camposComErro[] = "email";
					}
				}

				// Validar o campo Telémovel
				$telemovel = $fields["telemovel"];
				if (empty($telemovel) || !preg_match("/^\+?\d{9,15}$/", $telemovel)) {
					$camposComErro[] = "telemovel";
				}

				// Validar o campo Data de Nascimento
				$data_nascimento = $fields["data_nascimento"];
				if (empty($data_nascimento)) {
					$camposComErro[] = "data_nascimento";
				}
				// Validar o campo Data de Nascimento
				$termos_condicoes = $fields["termos_condicoes"];
				if (empty($termos_condicoes)) {
					$camposComErro[] = "termos_condicoes";
				}
				$marketing = $fields["marketing"];

				if (empty($camposComErro)) {
					if (empty($convite)) {
						$arrConvite["id_evento"] = $evento["id"];
						$arrConvite["id_rp"] = $rp["id"];
						$arrConvite["convite_tipo"] = 4;
						$arrConvite["convite_nome"] = $fields["nome"];
						$arrConvite["convite_email"] = $fields["email"];
						$arrConvite["convite_telemovel"] = $fields["telemovel"];
						$arrConvite["convite_data"] = date("Y-m-d H:i:s");
						$arrConvite["convite_status"] = "sucesso";
						$arrConvite["convite_status_date"] = date("Y-m-d H:i:s");
						$id_convite = $this->db->Insert('eventos_convites', $arrConvite);
						$this->hash = $hash = md5($arrConvite['id_evento'] . strtotime($arrConvite["convite_data"]) . $id_convite);
						$this->db->Update('eventos_convites', array("hash" => $hash), 'id=' . intval($id_convite));
						$convite = $dbevento->devolveEventoByHash($this->hash);
					}

					$arrUpdate["nome"] = $fields["nome"];
					$arrUpdate["data_nascimento"] = $fields["data_nascimento"];
					$arrUpdate["telemovel"] = $fields["telemovel"];
					$arrUpdate["email"] = $fields["email"];
					$arrUpdate["qrcode"] = strtotime("now") . $convite["id_rp"] . $convite["id_evento"] . $convite["id"];
					$arrUpdate["qrcode_data"] = 1;
					$arrUpdate["qrcode_ip"] = real_getip();
					$arrUpdate["qrcode_user_agent"] = $_SERVER["HTTP_USER_AGENT"];
					$arrUpdate["estado"] = 1;
					$arrUpdate["marketing"] = $marketing;
					$arrUpdate["termos_condicoes"] = $termos_condicoes;

					include_once($_SERVER["DOCUMENT_ROOT"] . '/lib/ticket_generator.obj.php');
					$bilheteGenerator = new BilheteGenerator($this->db, $arrUpdate, $evento, $convite);
					$bilheteGenerator->generateAndSendTicket();

					return array("status" => "success", "data" => array("hash" => $this->hash));
				} else {
					return array("status" => "error", "message" => $camposComErro);
				}
			}
		} else {
			return array("status" => "error", "message" => "Problem with URL");
		}
	}
	public function phoneValidation($fields)
	{
		$skip_session = true;
		
		require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/evento.obj.php');
		$dbevento = new evento($this->db, $_SESSION['id_rp']);

		if (preg_match('/^[a-f0-9]{32}$/i', $this->hash) || (preg_match('/^[a-f0-9]{32}$/i', $this->hash_evento) && preg_match('/^[a-f0-9]{32}$/i', $this->hash_rp))) {
			if ($this->hash) {
				$convite = $dbevento->devolveEventoByHash($this->hash);
				if ((int) $convite["id_evento"] > 0) {
					$evento = $dbevento->devolveEvento($convite["id_evento"]);
				}
			} else if ($this->hash_rp && $this->hash_evento) {
				$evento = $dbevento->devolveEventoByMD5($this->hash_evento);
			}
			if ($evento) {
				if ($fields["phone"]) {
					$hash = $dbevento->verificaConviteTelemovel($fields["phone"], $evento["id"]);
					if ($hash) {
						return array("status" => "error", "message" => "Já foi gerado um QR Code para este número.", "hash" => $hash);
					} else {
						$convite = $dbevento->getInfoConviteTelemovel($fields["phone"]);
						if ($convite["data_nascimento"]) {
							$data_nascimento = DateTime::createFromFormat('Y-m-d', $convite["data_nascimento"]);
							$convite["data_nascimento"] = $data_nascimento->format('d-m-Y');
						}
						return array("status" => "success", "convite" => $convite);
					}
				} else {
					return array("status" => "error",  "message" => "Phone is required");
				}
			} else {
				return array("status" => "error", "message" => "Event not found");
			}
		} else {
			return array("status" => "error", "message" => "Problem with URL");
		}
	}
	public function getCartaoByHash()
	{
		setlocale(LC_TIME, 'pt_PT.utf-8');
		include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
		require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/evento.obj.php');
		$dbevento = new evento($this->db, $_SESSION['id_rp']);
		if (preg_match('/^[a-f0-9]{32}$/i', $this->hash) || (preg_match('/^[a-f0-9]{32}$/i', $this->cartao))) {
			if ($this->tipo_cartao == 1) {
				$cartao = $dbevento->devolveCartaoSemConsumoByHash($this->cartao);
			} else if ($this->tipo_cartao == 2) {
				$cartao = $dbevento->devolveCartaoConsumoObrigatorioByHash($this->cartao);
			} else {
				return array("status" => "error", "message" => "Problem with URL");
			}
			if ($cartao) {
				$cartao["qrcode"] = "cartao_" . $this->tipo_cartao . "_" . $cartao["id"];
				return array("status" => "success", "data" => $cartao);
			} else {
				return array("status" => "error", "message" => "Problem with URL");
			}
		} else {
			return array("status" => "error", "message" => "Problem with URL");
		}
	}
	public function getConviteByHash()
	{
		setlocale(LC_TIME, 'pt_PT.utf-8');
		include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
		require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/evento.obj.php');
		$dbevento = new evento($this->db, $_SESSION['id_rp']);
		if (preg_match('/^[a-f0-9]{32}$/i', $this->hash) || (preg_match('/^[a-f0-9]{32}$/i', $this->hash_evento) && preg_match('/^[a-f0-9]{32}$/i', $this->hash_rp))) {
			$convite = $dbevento->devolveEventoByHash($this->hash);
			if ($convite) {
				return array("status" => "success", "data" => $convite);
			} else {
				return array("status" => "error", "message" => "Problem with URL");
			}
		} else {
			return array("status" => "error", "message" => "Problem with URL");
		}
	}
	public function getEventoByID($id_evento)
	{
		setlocale(LC_TIME, 'pt_PT.utf-8');
		include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
		require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/evento.obj.php');
		$dbevento = new evento($this->db, $_SESSION['id_rp']);
		if (preg_match('/^[a-f0-9]{32}$/i', $this->hash) || (preg_match('/^[a-f0-9]{32}$/i', $this->hash_evento) && preg_match('/^[a-f0-9]{32}$/i', $this->hash_rp))) {
			if ($id_evento) {
				$evento = $dbevento->devolveEvento($id_evento);
				if ($evento) {
					return array("status" => "success", "data" => $evento);
				} else {
					return array("status" => "error", "message" => "Evento not found");
				}
			} else {
				return array("status" => "error", "message" => "ID Evento not valid");
			}
		} else {
			return array("status" => "error", "message" => "Problem with URL");
		}
	}
	public function getEventoByMD5()
	{
		setlocale(LC_TIME, 'pt_PT.utf-8');
		include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
		require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/evento.obj.php');
		$dbevento = new evento($this->db, $_SESSION['id_rp']);
		if (preg_match('/^[a-f0-9]{32}$/i', $this->hash) || (preg_match('/^[a-f0-9]{32}$/i', $this->hash_evento) && preg_match('/^[a-f0-9]{32}$/i', $this->hash_rp))) {
			$evento = $dbevento->devolveEventoByMD5($this->hash_evento);
			if ($evento) {
				return array("status" => "success", "data" => $evento);
			} else {
				return array("status" => "error", "message" => "Evento not found");
			}
		} else {
			return array("status" => "error", "message" => "Problem with URL");
		}
	}
	public function getNomeRP($id_rp)
	{
		setlocale(LC_TIME, 'pt_PT.utf-8');
		include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
		require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/evento.obj.php');
		$dbevento = new evento($this->db, $id_rp);

		if (preg_match('/^[a-f0-9]{32}$/i', $this->hash) || (preg_match('/^[a-f0-9]{32}$/i', $this->hash_evento) && preg_match('/^[a-f0-9]{32}$/i', $this->hash_rp))) {
			if ($id_rp) {
				$rp = $dbevento->devolveNomeRP($id_rp);
				if ($rp) {
					return array("status" => "success", "data" => $rp);
				} else {
					return array("status" => "error", "message" => "RP not found");
				}
			} else {
				return array("status" => "error", "message" => "RP not valid");
			}
		} else {
			return array("status" => "error", "message" => "Problem with URL");
		}
	}

	public function getRPByMD5()
	{
		setlocale(LC_TIME, 'pt_PT.utf-8');
		include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
		require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/evento.obj.php');
		$dbevento = new evento($this->db, $_SESSION['id_rp']);

		if (preg_match('/^[a-f0-9]{32}$/i', $this->hash) || (preg_match('/^[a-f0-9]{32}$/i', $this->hash_evento) && preg_match('/^[a-f0-9]{32}$/i', $this->hash_rp))) {
			$rp = $dbevento->devolveRPByMD5($this->hash_rp);
			if ($rp) {
				return array("status" => "success", "data" => $rp);
			} else {
				return array("status" => "error", "message" => "RP not found");
			}
		} else {
			return array("status" => "error", "message" => "Problem with URL");
		}
	}

	function formatarDataPortugues($data)
	{
		// Array associativo para traduzir os nomes dos dias da semana
		$dias_semana = array(
			'Sunday' => 'Domingo',
			'Monday' => 'Segunda-feira',
			'Tuesday' => 'Terça-feira',
			'Wednesday' => 'Quarta-feira',
			'Thursday' => 'Quinta-feira',
			'Friday' => 'Sexta-feira',
			'Saturday' => 'Sábado'
		);

		// Array associativo para traduzir os nomes dos meses
		$meses = array(
			'January' => 'Janeiro',
			'February' => 'Fevereiro',
			'March' => 'Março',
			'April' => 'Abril',
			'May' => 'Maio',
			'June' => 'Junho',
			'July' => 'Julho',
			'August' => 'Agosto',
			'September' => 'Setembro',
			'October' => 'Outubro',
			'November' => 'Novembro',
			'December' => 'Dezembro'
		);

		// Converter a data para o formato desejado
		$dataFormatada = date('l, d \d\e F \d\e Y', strtotime($data));
		$dataFormatada = strtr($dataFormatada, $dias_semana);
		$dataFormatada = strtr($dataFormatada, $meses);

		return $dataFormatada;
	}
}
