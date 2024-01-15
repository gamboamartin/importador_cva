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

    public function genera_arreglo_bruto($cliente_cva, $url_cva, string $clave = '%', string $codigo = '%',
                                         string $grupo = '%', string $marca = '%'){
        $archivo_xml = $this->obten_archivo_xml_cva(cliente:$cliente_cva, url: $url_cva,clave: $clave,codigo: $codigo,
            grupo: $grupo,marca: $marca);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al maquetar key_selects',data:  $archivo_xml);
        }

        $xmlArr = array();
        if(trim($archivo_xml) !== '') {
            $xml = simplexml_load_string($archivo_xml);
            $json = json_encode($xml);
            $xmlArr = json_decode($json, true);
        }

        return $xmlArr;
    }
    public function obten_archivo_xml_cva(string $cliente, string $url, string $clave = '%', string $codigo = '%',
                                          string $grupo = '%', string $marca = '%'){
        $fields = array('cliente' => $cliente, 'marca' => $marca, 'grupo' => $grupo, 'clave' => $clave,
            'codigo' => $codigo);
        $fields_string = http_build_query($fields);

        $xml = file_get_contents($url."?".$fields_string);
        if($xml === false){
            return $this->error->error(mensaje: 'Error al maquetar key_selects',data:  $fields_string);
        }

        return $xml;
    }

    public function obten_productos(bool $header, string $path_base, string $seccion){

        $generales = new generales();

        $xmlArr = $this->genera_arreglo_bruto(cliente_cva: $generales->cliente_cva, url_cva: $generales->url_cva);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error generar arreglo bruto',data:  $xmlArr);
        }

        if(count($xmlArr)>0) {
            $keys = array("clave", "codigo_fabricante", "descripcion", "solucion", "grupo", "marca", "garantia", "clase",
                "disponible", "precio", "moneda", "ficha_tecnica", "ficha_comercial", "imagen", "disponibleCD");

            $registros = array();
            if(isset($xmlArr['item'][0])) {
                foreach ($xmlArr['item'] as $reg) {
                    foreach ($keys as $key) {
                        if (is_array($reg[$key])) {
                            $reg[$key] = '';
                        }
                    }
                    $registros[] = $reg;
                }
            }else{
                $temp = array();
                foreach ($xmlArr['item'] as $campo => $valor) {
                    if (is_array($xmlArr['item'][$campo])) {
                        $valor = '';
                    }
                    $temp[$campo] = $valor;
                }
                $registros[]= $temp;
            }

            $nombre_hojas[] = 'Registros';
            $keys_hojas['Registros'] = new stdClass();
            $keys_hojas['Registros']->keys = $keys;
            $keys_hojas['Registros']->registros = $registros;

            $xls = (new exportador())->genera_xls(header: $header, name: $seccion, nombre_hojas: $nombre_hojas,
                keys_hojas: $keys_hojas, path_base: $path_base);
            if (errores::$error) {
                return $this->error->error(mensaje: 'Error al obtener xls', data: $xls);
            }
        }
        return false;
    }

}