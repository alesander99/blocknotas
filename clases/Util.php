<?php

class Util {
    /*encripta la clave del usuario*/
    static function encriptar($cadena, $coste = 10) {
        $opciones = array(
            'cost' => $coste
        );
        return password_hash($cadena, PASSWORD_DEFAULT, $opciones);
    }
    /*verifica que la clave pasada sea la correcta*/
    static function verificarClave($claveSinEncriptar, $claveEncriptada){
        return password_verify($claveSinEncriptar, $claveEncriptada);
    }
    /*renderiza los ficheros pasados con su contenido*/
    static function renderFile($file, $data) {
        if (!file_exists($file)) {
            echo 'Error: ' . $file . '<br>';
            return '';
        }
        $contenido = file_get_contents($file);
        return self::renderText($contenido, $data);
    }
     /*renderiza los datos pasados con su contenido*/
    static function renderText($text, $data) {
        foreach ($data as $indice => $dato) {
            $text = str_replace('{' . $indice . '}', $dato, $text);
        }
        return $text;
    }
    /*envia un correo de confirmacion al destino que tu pongas con un mensage que pongas y un asunto que pongas*/
    static function enviarCorreo($destino,$asunto,$mensaje){
        require_once 'Credenciales/vendor/autoload.php';
        $cliente = new Google_Client();
        $cliente->setApplicationName(Constantes::APLICACIONAPIGMAIL);
        $cliente->setClientId('681704659283-mia4va5lbtiik7d207vj30sc9ctomr4k.apps.googleusercontent.com');
        $cliente->setClientSecret('R6D5R87bw4knaFZ1d-JUXrAT');
        $cliente->setAccessType('offline');
        $cliente->setAccessToken(file_get_contents('Credenciales/token.conf'));
        if ($cliente->getAccessToken()) {
            $service = new Google_Service_Gmail($cliente);
            try {
                $mail = new PHPMailer();
                $mail->CharSet = "UTF-8";
                $mail->From = Constantes::CORREO;
                $mail->FromName = Constantes::ALIAS;
                $mail->AddAddress($destino);
                $mail->AddReplyTo(Constantes::CORREO, Constantes::ALIAS);
                $mail->Subject = $asunto;
                $mail->Body = $mensaje;
                $mail->preSend();
                $mime = $mail->getSentMIMEMessage();
                $mime = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');
                $mensaje = new Google_Service_Gmail_Message();
                $mensaje->setRaw($mime);
                $service->users_messages->send('me', $mensaje);
                $mensaje = new Google_Service_Gmail_Message();
                $mensaje->setRaw($mime);
                $service->users_messages->send('me', $mensaje);
                return true;
            } catch (Exception $e) {
                var_dump($e);
                exit();
                return false;
            }
        } else {
            var_dump('token');
            exit();
            return false;
        }
    }
}