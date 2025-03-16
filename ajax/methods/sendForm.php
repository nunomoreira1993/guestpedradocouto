<?php
setlocale(LC_TIME, 'pt_PT.utf-8');
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
require_once($_SERVER['DOCUMENT_ROOT'] . '/rp/eventos/evento.obj.php');
$dbevento = new evento($db, $_SESSION['id_rp']);

$_GET["hash"] = $_POST["hash"]?:"";
$_GET["evento"] = $_POST["evento"] ?: "";
$_GET["rp"] = $_POST["rp"] ?: "";
if (preg_match('/^[a-f0-9]{32}$/i', $_GET["hash"]) || (preg_match('/^[a-f0-9]{32}$/i', $_GET["evento"]) && preg_match('/^[a-f0-9]{32}$/i', $_GET["rp"]))) {

    if($_GET["hash"]) {
        $convite = $dbevento->devolveEventoByHash($_GET["hash"]);
        if ((int) $convite["id_evento"] > 0) {
            $evento = $dbevento->devolveEvento($convite["id_evento"]);
        }
    }
    else if ($_GET["rp"] && $_GET["evento"]) {
        $evento = $dbevento->devolveEventoByMD5($_GET["evento"]);
        $rp = $dbevento->devolveRPByMD5($_GET["rp"]);
    }

    if (!empty($_POST) && (int) $convite["qrcode"] == 0) {
        $camposComErro = [];

        // Validar o campo Nome
        $nome = $fields["nome"] = $_POST['nome'];
        if (empty($nome)) {
            $camposComErro[] = "nome";
        }

        // Validar o campo E-mail
        $email = $fields["email"] = $_POST['email'];
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $camposComErro[] = "email";
            }
        }

        // Validar o campo Telémovel
        $telemovel = $fields["telemovel"] = $_POST['telemovel'];
        if (empty($telemovel) || !preg_match("/^\+?\d{9,15}$/", $telemovel)) {
            $camposComErro[] = "telemovel";
        }

        // Validar o campo Data de Nascimento
        $data_nascimento = $fields["data_nascimento"] = $_POST['data_nascimento'];
        if (empty($data_nascimento)) {
            $camposComErro[] = "data_nascimento";
        }
        // Validar o campo Data de Nascimento
        $termos_condicoes = $fields["termos_condicoes"] = $_POST['termos_condicoes'];
        if (empty($termos_condicoes)) {
            $camposComErro[] = "termos_condicoes";
        }
        $marketing = $fields["marketing"] = $_POST['marketing'];

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
                $id_convite = $db->Insert('eventos_convites', $arrConvite);
                $_GET["hash"] = $hash = md5($arrConvite['id_evento'] . strtotime($arrConvite["convite_data"]) . $id_convite);
                $db->Update('eventos_convites', array("hash" => $hash), 'id=' . intval($id_convite));
                $convite = $dbevento->devolveEventoByHash($_GET["hash"]);
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

            include_once($_SERVER["DOCUMENT_ROOT"] . '/administrador/entradas/ticket_generator.obj.php');
            $bilheteGenerator = new BilheteGenerator($db, $arrUpdate, $evento, $convite);
            $bilheteGenerator->generateAndSendTicket();

            echo json_encode(array("status" => "success", "data" => array("hash" => $_GET["hash"])));

        }
        else {
            echo json_encode(array("status" => "error", "message" => $camposComErro));
        }
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Problem with URL"));
}
