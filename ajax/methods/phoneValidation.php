<?php
$_GET["hash"] = $_POST["hash"]?:"";
$_GET["evento"] = $_POST["evento"] ?: "";
$_GET["rp"] = $_POST["rp"] ?: "";

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/methods.obj.php');
$dbmethods = new methods($_GET["hash"], $_GET["evento"], $_GET["rp"]);

echo json_encode($dbmethods->phoneValidation($_POST));