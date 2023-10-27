<?php /** @var gamboamartin\system\_ctl_parent $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>


<?php echo $controlador->inputs->adm_seccion_id; ?>
<?php echo $controlador->inputs->adm_tipo_dato_id; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->sub_consulta; ?>
<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>



