<?php
namespace gamboamartin\importador_cva\html;
use gamboamartin\errores\errores;
use gamboamartin\importador_cva\models\cva_lista_precio;
use gamboamartin\system\html_controler;
use PDO;

class cva_lista_precio_html extends html_controler {

    public function select_inm_costo_id(int $cols, bool $con_registros, int $id_selected, PDO $link,
                                      bool $disabled = false, array $filtro = array()): array|string
    {
        $modelo = new cva_lista_precio(link: $link);

        $select = $this->select_catalogo(cols: $cols, con_registros: $con_registros, id_selected: $id_selected,
            modelo: $modelo, disabled: $disabled, filtro: $filtro, label: 'Costo', required: true);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }


}
