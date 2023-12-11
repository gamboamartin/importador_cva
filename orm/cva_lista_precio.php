<?php

namespace gamboamartin\importador_cva\models;

use base\orm\_modelo_parent;
use config\generales;
use gamboamartin\errores\errores;
use gamboamartin\plugins\exportador;
use PDO;
use stdClass;


class cva_lista_precio extends _modelo_parent{
    public function __construct(PDO $link)
    {
        $tabla = 'cva_lista_precio';
        $columnas = array($tabla=>false);

        $campos_obligatorios = array();

        $columnas_extra= array();
        $renombres= array();

        $atributos_criticos = array();

        parent::__construct(link: $link, tabla: $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, columnas_extra: $columnas_extra, renombres: $renombres,
            atributos_criticos: $atributos_criticos);

        $this->NAMESPACE = __NAMESPACE__;
        $this->etiqueta = 'Costo Ubicaciones';
    }

    public function obten_productos(bool $header, string $path_base, string $seccion){

        $generales = new generales();

        $archivo_xml = $this->obten_archivo_xml_cva(cliente: $generales->cliente_cva, url: $generales->url_cva,marca: "HP");
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al maquetar key_selects',data:  $archivo_xml);
        }

        $xml = simplexml_load_string($archivo_xml);
        $json  = json_encode($xml);
        $xmlArr = json_decode($json, true);

        $keys = array("clave","codigo_fabricante","descripcion","solucion","grupo","marca","garantia","clase",
            "disponible","precio","moneda","ficha_tecnica","ficha_comercial","imagen","disponibleCD");

        $registros = array();
        foreach ($xmlArr['item'] as $reg){
            foreach ($keys as $key){
                if(is_array($reg[$key])){
                    $reg[$key] = '';
                }
            }
            $registros[] = $reg;
        }

        $nombre_hojas[] = 'Registros';
        $keys_hojas['Registros'] = new stdClass();
        $keys_hojas['Registros']->keys = $keys;
        $keys_hojas['Registros']->registros = $registros;

        $xls = (new exportador())->genera_xls(header: $header,name:  $seccion,nombre_hojas:  $nombre_hojas,
            keys_hojas: $keys_hojas, path_base: $path_base);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener xls',data:  $xls);
        }

        return false;
    }

    public function obten_archivo_xml_cva(string $cliente, string $url, string $clave = '%', string $codigo = '%',
                                          string $grupo = '%', string $marca = '%'){
        $fields = array('cliente' => $cliente, 'marca' => $marca, 'grupo' => $grupo, 'clave', $clave,
            'codigo', $codigo);
        $fields_string = http_build_query($fields);

        $xml = file_get_contents($url."?".$fields_string);
        if($xml === false){
            return $this->error->error(mensaje: 'Error al maquetar key_selects',data:  $fields_string);
        }

        return $xml;
    }
}