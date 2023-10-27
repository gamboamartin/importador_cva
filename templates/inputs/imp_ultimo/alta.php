<?php /** @var gamboamartin\acl\controllers\controlador_adm_seccion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->imp_destino_id; ?>
<?php echo $controlador->inputs->adm_accion_id; ?>
<?php echo $controlador->inputs->id_ultimo; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>
