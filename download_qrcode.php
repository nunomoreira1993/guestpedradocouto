<?php

setlocale(LC_TIME, 'pt_PT.utf-8');
if (session_id() == '') {
  session_start();
}
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);
date_default_timezone_set("Europe/Lisbon");

include_once($_SERVER["DOCUMENT_ROOT"] . '/plugins/phpqrcode/lib/full/qrlib.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/methods.obj.php');

if($_GET["cartao"]) {
  $dbmethods = new methods($_GET["hash"], $_GET["evento"], $_GET["rp"], $_GET["cartao"], $_GET["tipo_cartao"]);
  $cartao = $dbmethods->getCartaoByHash();

  if($cartao["data"]["qrcode"]) {
    ob_start();
    QRcode::png($cartao["data"]["qrcode"], null, QR_ECLEVEL_L, 35, 2);
    $qrcode_image = ob_get_clean();
	
    // Define o cabeçalho para indicar que o arquivo é um arquivo PNG
    header('Content-Description: File Transfer');
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="qrcode_' . str_replace("-", "_", $cartao["data"]["id"]) . '.png"');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Expires: 0');

    // Envia o conteúdo do arquivo PNG para o navegador
    echo $qrcode_image;
    exit;
  }
  else {
    echo "<h1>Não é possivel efetuar download.</h1>";
    echo "<h3>Cartão não encontrado.</h3>";
    die;
  }
}
else if (preg_match('/^[a-f0-9]{32}$/i', $_GET["hash"])) {
  $dbmethods = new methods($_GET["hash"], $_GET["evento"], $_GET["rp"]);

  $convite = $dbmethods->getConviteByHash();
  if ((int) $convite["data"]["qrcode"] > 0) {
    $evento = $dbmethods->getEventoByID($convite["data"]["id_evento"]);

    if (!empty($evento)) {
      if (date('H') < 14) {
        $data = date('Y-m-d', strtotime('-1 day'));
      } else {
        $data = date('Y-m-d');
      }
      if (!($evento["data"]["data"] < $data)) {
		ob_start();
        QRcode::png($convite["data"]["qrcode"], null, QR_ECLEVEL_L, 35, 2);
        $qrcode_image = ob_get_clean();
        // Define o cabeçalho para indicar que o arquivo é um arquivo PNG
        header('Content-Description: File Transfer');
        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="qrcode_' . str_replace("-", "_", $evento["data"]["data"]) . '.png"');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Expires: 0');

        // Envia o conteúdo do arquivo PNG para o navegador
        echo $qrcode_image;
        exit;
      } else {
        echo "<h1>Não é possivel efetuar download.</h1>";
        echo "<h3>Evento expirado.</h3>";
        die;
      }
    } else {
      echo "<h1>Não é possivel efetuar download.</h1>";
      echo "<h3>Evento não encontrado.</h3>";
      die;
    }
  } else {
    echo "<h1>Não é possivel efetuar download.</h1>";
    echo "<h3>Sem QR Code gerado.</h3>";
    die;
  }
} else {
  echo "<h1>Não é possivel efetuar download.</h1>";
  echo "<h3>Hash inválida.</h3>";
  die;
}
