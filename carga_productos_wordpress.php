<?php
use base\conexion;
use config\generales;
use gamboamartin\errores\errores;
use gamboamartin\importador_cva\models\cva_lista_precio;

require "init.php";
require 'vendor/autoload.php';
use Automattic\WooCommerce\Client;

$url_API_woo = 'https://tienda.ivitec.com.mx/';
$ck_API_woo = 'ck_a52d87c9bebb8d66b92a30740c30280b605a1b92';
$cs_API_woo = 'cs_735fae983ea458d35d65d41aa0e3beee82b8714d';

$woocommerce = new Client(
    $url_API_woo,
    $ck_API_woo,
    $cs_API_woo,
    ['version' => 'wc/v3']
);

$con = new conexion();
$link = conexion::$link;
$cva_lista_precio_modelo = (new cva_lista_precio(link: $link));
$generales = (new generales());
$_SESSION['usuario_id'] = 2;
$_SESSION['session_id'] = mt_rand(10000000,99999999);
$_GET['session_id'] = $_SESSION['session_id'];

$xmlArr = $cva_lista_precio_modelo->genera_arreglo_bruto(cliente_cva: $generales->cliente_cva,
    url_cva: $generales->url_cva,marca: 'HP');
if(errores::$error){
    $error = (new errores())->error(mensaje: 'Error',data:  $xmlArr);
    print_r($error);
    exit;
}

$registros = $xmlArr['item'];

if(!isset($xmlArr['item'][0])) {
    $temp[] = $xmlArr['item'];
    $registros = $temp;
}

$param_sku ='';
foreach ($registros as $item){
    $param_sku .= $item['clave'] . ',';
}

$products = $woocommerce->get('products/?sku='. $param_sku);

$item_data = array();
$grupos = array();
foreach ($registros as $item) {
    $sku = $item['clave'];
    $search_item = array_filter((array)$products, function ($item) use ($sku) {
        return $item->sku == $sku;
    });

    $search_item = reset($search_item);

    if(empty($search_item)){
        if(count($item_data) === 100){
            $grupos[]= $item_data;
            $item_data = array();
        }
        $item_data[] = [
            'sku' => $item['clave'],
            'name' => $item['descripcion'],
            'type' => 'simple',
            'regular_price' => $item['precio'],
            'virtual' => true,
            'downloadable' => false,
            'downloads' => array(),
            'categories' => array(0=>$item['grupo']),
            'stock_quantity' => $item['disponible'],
            'imagen' => array('src'=>$item['imagen'])
        ];
    }
}
$grupos[]= $item_data;

foreach ($grupos as $grupo) {
    $data = [
        'create' => $grupo,
    ];
    echo "Actualización en lote ... \n";
    $result = $woocommerce->post('products/batch', $data);

    if (!$result) {
        echo("Error al actualizar productos \n");
    } else {
        print("Productos actualizados correctamente \n");
    }
}