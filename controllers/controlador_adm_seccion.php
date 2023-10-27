<?php
namespace gamboamartin\importador\controllers;

use base\orm\estructuras;
use base\orm\modelo;
use base\orm\modelo_base;
use gamboamartin\errores\errores;
use gamboamartin\importador\html\adm_tipo_dato_html;
use gamboamartin\importador\html\imp_database_html;
use gamboamartin\importador\models\_conexion;
use gamboamartin\importador\models\adm_seccion;
use gamboamartin\importador\models\imp_database;
use gamboamartin\template_1\html;
use html\adm_seccion_html;
use PDO;
use stdClass;

class controlador_adm_seccion extends \gamboamartin\acl\controllers\controlador_adm_seccion {
    public array $not_actions = array();
    public string $link_imp_origen_alta_bd = '';
    public string $link_adm_campo_alta_bd = '';

    public string $ths = '';
    public array $keys_data = array();

    private adm_seccion_html $html_local;

    public function __construct(PDO $link, html $html = new html(), stdClass $paths_conf = new stdClass())

    {

        $datatables_custom_cols = array();
        $datatables_custom_cols['adm_seccion_n_origenes']['titulo'] = 'N Origenes';
        $datatables_custom_cols['adm_seccion_n_campos']['titulo'] = 'N Campos';
        $datatables_custom_cols_omite[] = 'adm_seccion_n_acciones';

        $this->html_local = (new adm_seccion_html(html: $html));

        parent::__construct(link: $link, html: $html, datatables_custom_cols: $datatables_custom_cols,
            datatables_custom_cols_omite: $datatables_custom_cols_omite, paths_conf: $paths_conf);


        $this->modelo = new adm_seccion(link: $link);

        $link_imp_origen_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'imp_origen');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_imp_origen_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_imp_origen_alta_bd = $link_imp_origen_alta_bd;

        $link_adm_campo_alta_bd = $this->obj_link->link_alta_bd(link: $link, seccion: 'adm_campo');
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al obtener link',data:  $link_adm_campo_alta_bd);
            print_r($error);
            exit;
        }
        $this->link_adm_campo_alta_bd = $link_adm_campo_alta_bd;

    }

    public function alta_full(bool $header, bool $ws = false){


        $r_alta_full = (new adm_seccion(link: $this->link))->alta_full(adm_seccion_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al insertar destinos',data:  $r_alta_full, header: $header,ws:  $ws);
        }

        var_dump($r_alta_full);
        exit;

    }

    final public function campos(bool $header = true, bool $ws = false): array|stdClass|string
    {
        $contenido_table = (new _base_importador())->campos(controler: $this, next_accion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }

        return $contenido_table;

    }

    protected function inputs_children(stdClass $registro): array|stdClass{

        $r_parent = parent::inputs_children(registro: $registro);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener inputs',data:  $r_parent);
        }


        $select_imp_database_id = (new imp_database_html(html: $this->html_base))->select_imp_database_id(
            cols:12,con_registros: true,id_selected:  -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_imp_database_id',data:  $select_imp_database_id);
        }

        $select_adm_seccion_id = (new adm_seccion_html(html: $this->html_base))->select_adm_seccion_id(
            cols:12,con_registros: true,id_selected:  $registro->adm_seccion_id,link:  $this->link, disabled: true);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_imp_database_id',data:  $select_adm_seccion_id);
        }

        $select_adm_tipo_dato_id = (new adm_tipo_dato_html(html: $this->html_base))->select_adm_tipo_dato_id(
            cols:12,con_registros: true,id_selected:  -1,link:  $this->link);

        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener select_adm_tipo_dato_id',data:  $select_adm_tipo_dato_id);
        }

        $adm_campo_descripcion = $this->html_local->input_descripcion(cols: 12, row_upd: new stdClass(),value_vacio: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener adm_campo_descripcion',data:  $adm_campo_descripcion);
        }

        $this->inputs->imp_database_id = $select_imp_database_id;
        $this->inputs->adm_seccion_id = $select_adm_seccion_id;
        $this->inputs->adm_tipo_dato_id = $select_adm_tipo_dato_id;
        $this->inputs->adm_campo_descripcion = $adm_campo_descripcion;

        return $this->inputs;
    }

    public function origenes(bool $header = true, bool $ws = false): array|stdClass|string
    {

        $contenido_table = (new _base_importador())->origenes(controler: $this,next_accion: __FUNCTION__);
        if(errores::$error){
            return $this->retorno_error(
                mensaje: 'Error al obtener tbody',data:  $contenido_table, header: $header,ws:  $ws);
        }

        return $contenido_table;

    }

    public function sincroniza(bool $header = true, bool $ws = false){
        $adm_seccion = $this->modelo->registro(registro_id: $this->registro_id);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener asm_seccion',data:  $adm_seccion, header: $header,ws:  $ws);
        }

        $name_modelo = $adm_seccion['adm_seccion_descripcion'];
        $namespace_model = $adm_seccion['adm_namespace_name'];
        $namespace_model = str_replace('/', '\\', $namespace_model);
        $namespace_model .= '\\models';

        $modelo_sink = $this->modelo->genera_modelo(modelo: $name_modelo,namespace_model: $namespace_model);

        if(!$modelo_sink->es_sincronizable){
            return $this->retorno_error(
                mensaje: 'Error al  el modelo no es sincronizable',data:  $modelo_sink, header: $header,ws:  $ws);
        }

        $imp_databases = (new imp_database(link: $this->link))->registros();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al obtener imp_databases',data:  $imp_databases, header: $header,ws:  $ws);
        }

        $campos_entidad = $modelo_sink->campos_view;

        $ths = '<th>DataBase</th>';
        $keys_data[] = 'imp_database_descripcion';
        foreach ($campos_entidad as $campo=>$value){
           $ths .="<th>$campo</th>";
           $keys_data[] = $modelo_sink->tabla."_".$campo;
        }
        $this->ths = $ths;
        $this->keys_data = $keys_data;


        foreach ($imp_databases as $imp_database){
            $link_destino = (new _conexion())->link_destino(imp_database_id: $imp_database['imp_database_id'],link:  $this->link);
            if(errores::$error){
                $error = $this->errores->error(mensaje: 'Error al conectar imp_database',data:  $link_destino);
                print_r($error);
                exit;
            }
            $name_db = $imp_database['imp_database_descripcion'];
            $estructura = (new estructuras(link: $link_destino));
            $existe_entidad = $estructura->existe_entidad(entidad: $name_modelo);
            if(errores::$error){
                $error =  $this->errores->error(mensaje: 'Error al obtener existe_entidad',data:  $existe_entidad);
                print_r($error);
                exit;
            }
            if(!$existe_entidad){
                continue;
            }

            $modelo_base = new modelo_base(link: $link_destino);

            $modelo_sink = $modelo_base->genera_modelo(modelo: $name_modelo,namespace_model: $namespace_model);
            $registros = $modelo_sink->registros();
            if(errores::$error){
                $error =  $this->errores->error(mensaje: 'Error al obtener registros',data:  array($registros,$name_db));
                print_r($error);
                exit;
            }
            foreach ($registros as $registro){
                $link_destino->beginTransaction();

                $regenera = $modelo_sink->regenera($registro[$modelo_sink->key_id]);
                if(errores::$error){
                    $link_destino->rollBack();
                    $error =  $this->errores->error(mensaje: 'Error al regenerar',data:  $regenera);
                    print_r($error);
                    exit;
                }
                $link_destino->commit();
                $registro['imp_database_descripcion'] = $name_db;
                $this->registros[] = $registro;
            }

        }


    }
}
