<?php
setlocale(LC_TIME, 'pt_PT.utf-8');
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/config.php";
require_once($_SERVER['DOCUMENT_ROOT'] . '/rp/eventos/evento.obj.php');
$dbevento = new evento($db, $_SESSION['id_rp']);

if (preg_match('/^[a-f0-9]{32}$/i', $_GET["hash"]) || (preg_match('/^[a-f0-9]{32}$/i', $_GET["evento"]) && preg_match('/^[a-f0-9]{32}$/i', $_GET["rp"])) ) {

    $id_rp = (int) $_GET["id_rp"];
    if($id_rp) {
        $rp = $dbevento->devolveNomeRP($_GET["id_rp"]);
        if($rp){
            echo json_encode(array("status"=> "success", "data" => $rp));
        }
        else {
            echo json_encode(array("status"=> "error","message"=> "RP not found"));
        }
    }
    else {
        echo json_encode(array("status"=> "error", "message"=> "RP not valid"));
    }

}
else {
    echo json_encode(array("status"=> "error","message"=> "Problem with URL"));
}