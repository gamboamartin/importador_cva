<?php /** @var  gamboamartin\facturacion\controllers\controlador_fc_docto_relacionado $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->inm_ubicacion_id; ?>
<?php echo $controlador->inputs->inm_concepto_id; ?>
<?php echo $controlador->inputs->referencia; ?>
<?php echo $controlador->inputs->fecha; ?>
<?php echo $controlador->inputs->monto; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>