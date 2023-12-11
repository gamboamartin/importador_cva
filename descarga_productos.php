<?php
use base\conexion;
use config\generales;
use gamboamartin\errores\errores;
use gamboamartin\importador_cva\models\cva_lista_precio;

require "init.php";
require 'vendor/autoload.php';



$con = new conexion();
$link = conexion::$link;
$cva_lista_precio_modelo = (new cva_lista_precio(link: $link));
$generales = (new generales());
$_SESSION['usuario_id'] = 2;
$_SESSION['session_id'] = mt_rand(10000000,99999999);
$_GET['session_id'] = $_SESSION['session_id'];


$obtener_archivo = $cva_lista_precio_modelo->obten_productos(header: false,path_base: $generales->path_base,
    seccion: 'cva_lista_precio');
if(errores::$error){
    $error = (new errores())->error(mensaje: 'Error',data:  $obtener_archivo);
    print_r($error);
    exit;
}

exit;
