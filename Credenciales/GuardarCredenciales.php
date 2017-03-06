<?php
//681704659283-mia4va5lbtiik7d207vj30sc9ctomr4k.apps.googleusercontent.com - ID cliente
//R6D5R87bw4knaFZ1d-JUXrAT -Id-screto
//proyectblock-149611 - ID Proyecto

session_start();
require_once 'vendor/autoload.php';
$cliente = new Google_Client();
$cliente->setApplicationName('proyectblock-149611');
$cliente->setClientId('681704659283-mia4va5lbtiik7d207vj30sc9ctomr4k.apps.googleusercontent.com');
$cliente->setClientSecret('R6D5R87bw4knaFZ1d-JUXrAT');
$cliente->setRedirectUri('https://proyectoblock-alesander.c9users.io/notas/Credenciales/GuardarCredenciales.php');
$cliente->setScopes('https://www.googleapis.com/auth/gmail.compose');
$cliente->setAccessType('offline');
if (isset($_GET['code'])) {
   $cliente->authenticate($_GET['code']);
   $_SESSION['token'] = $cliente->getAccessToken();
   $archivo = "token.conf";
   $fh = fopen($archivo, 'w') or die("error");
   fwrite($fh, json_encode($cliente->getAccessToken()));
   fclose($fh);
}