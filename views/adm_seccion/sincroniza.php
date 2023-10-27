<?php /** @var gamboamartin\acl\controllers\controlador_adm_menu $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<main class="main section-color-primary">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="widget  widget-box box-container form-main widget-form-cart" id="form">
                    <?php include (new views())->ruta_templates."head/title.php"; ?>
                    <?php include (new views())->ruta_templates."head/subtitulo.php"; ?>
                    <?php include (new views())->ruta_templates."mensajes.php"; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<main class="main section-color-primary">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="widget widget-box box-container widget-mylistings table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <?php  echo $controlador->ths; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($controlador->registros as $row){ ?>
                            <tr>
                                <?php foreach ($controlador->keys_data as $key){ ?>
                                <td><?php  echo $row[$key] ?></td>
                                <?php } ?>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div> <!-- /. widget-table-->
            </div><!-- /.center-content -->
        </div>
    </div>
</main>

