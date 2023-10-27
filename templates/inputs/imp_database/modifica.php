<?php /** @var gamboamartin\system\_ctl_parent $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->imp_server_id; ?>
<?php echo $controlador->inputs->user; ?>
<?php echo $controlador->inputs->password; ?>
<?php echo $controlador->inputs->tipo; ?>

<?php include (new views())->ruta_templates.'botons/submit/modifica_bd.php';?>



