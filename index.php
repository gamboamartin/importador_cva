<?php
require "init.php";
require 'vendor/autoload.php';

use base\controller\init;
use config\generales;
use gamboamartin\errores\errores;


$data_session_get = (new init())->asigna_session_get();
if(errores::$error){
    $error = (new errores())->error(mensaje: 'Error al inicializar datos',data:  $data_session_get, params: get_defined_vars());
    print_r($error);
    die('Error');
}



$data = (new init())->index(aplica_seguridad: (new generales())->aplica_seguridad);
if(errores::$error){
    $error = (new errores())->error(mensaje: 'Error al inicializar datos',data:  $data);
    print_r($error);
    die('Error');
}

if($data->acceso_denegado && isset($_GET['ws']) && $_GET['ws'] === '1'){

    $error = (new gamboamartin\errores\errores())->error(mensaje: 'Acceso denegado ',data: array());
    ob_clean();
    header('Content-Type: application/json');
    try {
        echo json_encode($error, JSON_THROW_ON_ERROR);
        exit;
    }
    catch (Throwable $e){
        print_r($e);
        exit;
    }

}

$controlador = $data->controlador;


$link = $data->link;
$conf_generales = $data->conf_generales;
if($conf_generales->muestra_index) {
    include "principal.php";
}
