<?php /** @var  \gamboamartin\importador_cva\controllers\controlador_cva_lista_precio $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->total_registros; ?>
<?php echo $controlador->inputs->registro_actual; ?>
<?php echo $controlador->inputs->codigo; ?>
<?php echo $controlador->inputs->clave; ?>
<?php echo $controlador->inputs->grupo; ?>
<?php echo $controlador->inputs->marca; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>