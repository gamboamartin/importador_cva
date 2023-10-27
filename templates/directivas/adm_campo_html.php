<?php
namespace gamboamartin\importador\html;

use gamboamartin\administrador\models\adm_campo;
use gamboamartin\administrador\models\adm_tipo_dato;
use gamboamartin\errores\errores;
use gamboamartin\system\html_controler;
use PDO;


class adm_campo_html extends html_controler {

    public function select_adm_tipo_dato_id(int $cols, bool $con_registros, int $id_selected, PDO $link, bool $disabled = false): array|string
    {
        $modelo = new adm_campo(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, label: 'Campo', required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }


}
