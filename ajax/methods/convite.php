<?php
setlocale(LC_TIME, 'pt_PT.utf-8');
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
require_once($_SERVER['DOCUMENT_ROOT'] . '/rp/eventos/evento.obj.php');
$dbevento = new evento($db, $_SESSION['id_rp']);

if (preg_match('/^[a-f0-9]{32}$/i', $_GET["hash"]) || (preg_match('/^[a-f0-9]{32}$/i', $_GET["evento"]) && preg_match('/^[a-f0-9]{32}$/i', $_GET["rp"])) ) {

    $convite = $dbevento->devolveEventoByHash($_GET["hash"]);
   if($convite){
       echo json_encode(array("status"=> "success", "data" => $convite));
   }
   else {
       echo json_encode(array("status"=> "error","message"=> "Problem with URL"));
   }
}
else {
    echo json_encode(array("status"=> "error","message"=> "Problem with URL"));
}