<?php
setlocale(LC_TIME, 'pt_PT.utf-8');
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
require_once($_SERVER['DOCUMENT_ROOT'] . '/rp/eventos/evento.obj.php');
$dbevento = new evento($db, $_SESSION['id_rp']);
if (preg_match('/^[a-f0-9]{32}$/i', $_GET["hash"]) || (preg_match('/^[a-f0-9]{32}$/i', $_GET["cartao"]))) {

    if($_GET["tipo_cartao"] == 1) {
        $cartao = $dbevento->devolveCartaoSemConsumoByHash($_GET["cartao"]);
    }
    else if($_GET["tipo_cartao"] == 2) {
        $cartao = $dbevento->devolveCartaoConsumoObrigatorioByHash($_GET["cartao"]);
    }
    else {
        echo json_encode(array("status"=> "error","message"=> "Problem with URL"));
    }
   if($cartao){
        $cartao["qrcode"] = "cartao_" . $_GET["tipo_cartao"] . "_" . $cartao["id"];
        echo json_encode(array("status"=> "success", "data" => $cartao));
   }
   else {
       echo json_encode(array("status"=> "error","message"=> "Problem with URL"));
   }
}
else {
    echo json_encode(array("status"=> "error","message"=> "Problem with URL"));
}