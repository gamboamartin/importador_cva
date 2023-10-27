<?php /** @var gamboamartin\acl\controllers\controlador_adm_seccion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->imp_server_id; ?>
<?php echo $controlador->inputs->user; ?>
<?php echo $controlador->inputs->password; ?>
<?php echo $controlador->inputs->tipo; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>
