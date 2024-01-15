<?php
use base\conexion;
use config\generales;
use gamboamartin\errores\errores;
use gamboamartin\importador_cva\models\cva_lista_precio;

require "init.php";
require 'vendor/autoload.php';
use Automattic\WooCommerce\Client;

// Conexión WooCommerce API destino
// ================================
$url_API_woo = 'https://tienda.ivitec.com.mx/';
$ck_API_woo = 'ck_a52d87c9bebb8d66b92a30740c30280b605a1b92';
$cs_API_woo = 'cs_735fae983ea458d35d65d41aa0e3beee82b8714d';

$woocommerce = new Client(
    $url_API_woo,
    $ck_API_woo,
    $cs_API_woo,
    ['version' => 'wc/v3']
);
// ================================


// Conexión API origen
// ===================
$url_API="http://localhost/inventory/";

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$url_API);

echo "➜ Obteniendo datos origen ... \n";
$items_origin = curl_exec($ch);
curl_close($ch);

if ( ! $items_origin ) {
    exit('❗Error en API origen');
}
// ===================


// Obtenemos datos de la API de origen
$items_origin = json_decode($items_origin, true);

// formamos el parámetro de lista de SKUs a actualizar
$param_sku ='';
foreach ($items_origin as $item){
    $param_sku .= $item['sku'] . ',';
}

echo "➜ Obteniendo los ids de los productos... \n";
// Obtenemos todos los productos de la lista de SKUs
$products = $woocommerce->get('products/?sku='. $param_sku);

// Construimos la data en base a los productos recuperados
$item_data = [];
foreach($products as $product){

    // Filtramos el array de origen por sku
    $sku = $product->sku;
    $search_item = array_filter($items_origin, function($item) use($sku) {
        return $item['sku'] == $sku;
    });
    $search_item = reset($search_item);

    // Formamos el array a actualizar
    $item_data[] = [
        'id' => $product->id,
        'regular_price' => $search_item['price'],
        'stock_quantity' => $search_item['qty'],
    ];

}

// Construimos información a actualizar en lotes
$data = [
    'update' => $item_data,
];

echo "➜ Actualización en lote ... \n";
// Actualización en lotes
$result = $woocommerce->post('products/batch', $data);

if (! $result) {
    echo("❗Error al actualizar productos \n");
} else {
    print("✔ Productos actualizados correctamente \n");
}