<?php /** @var gamboamartin\acl\controllers\controlador_adm_seccion $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->inputs->descripcion; ?>
<?php echo $controlador->inputs->ip; ?>
<?php echo $controlador->inputs->proveedor; ?>
<?php echo $controlador->inputs->user; ?>
<?php echo $controlador->inputs->password; ?>
<?php echo $controlador->inputs->domain; ?>

<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>
