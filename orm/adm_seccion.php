<?php
namespace gamboamartin\importador\models;
use base\orm\_modelo_parent;
use gamboamartin\errores\errores;
use PDO;

class adm_seccion extends \gamboamartin\administrador\models\adm_seccion {

    public function __construct(PDO $link, array $childrens = array(), array $columnas_extra = array())
    {
        $columnas_extra['adm_seccion_n_origenes'] = /** @lang sql */
            "(SELECT COUNT(*) FROM imp_origen WHERE imp_origen.adm_seccion_id = adm_seccion.id)";

        $columnas_extra['adm_seccion_n_campos'] = /** @lang sql */
            "(SELECT COUNT(*) FROM adm_campo WHERE adm_campo.adm_seccion_id = adm_seccion.id)";
        parent::__construct(link: $link,childrens:  $childrens,columnas_extra:  $columnas_extra);
    }

    final public function alta_full(int $adm_seccion_id){
        $r_altas_full = array();
        $imp_origenes = $this->origenes(adm_seccion_id: $adm_seccion_id);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener imp_origenes',data:  $imp_origenes);
        }
        foreach ($imp_origenes as $imp_origen){
            $r_alta_full = (new imp_origen(link: $this->link))->alta_full(imp_origen_id: $imp_origen['imp_origen_id']);
            if(errores::$error){
                return $this->error->error(mensaje: 'Error al insertar destinos',data:  $r_alta_full);
            }
            $r_altas_full[] = $r_alta_full;
        }
        return $r_altas_full;
    }

    private function origenes(int $adm_seccion_id){

        $filtro['adm_seccion.id'] = $adm_seccion_id;
        $r_imp_origenes = (new imp_origen(link: $this->link))->filtro_and(filtro: $filtro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener r_imp_origenes', data: $r_imp_origenes);
        }
        return $r_imp_origenes->registros;

    }


}