<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-window-restore nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> Consulta rápida de Sucursales</h3>
            </div>
            <div class="card-body">

                <form class="needs-validation" action="clonar-sucursal" method="post" novalidate="">
                    <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $id_cliente ?>">
                    <div class="row">

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="id_sucursal_origen">Sucursal origen</label>
                                <select class="form-control select2" id="id_sucursal_origen" name="id_sucursal_origen" style="width: 100%;" required="">
                                    <option value="">Selecciona una Empresa - Sucursal</option>
                                    <?php
                                    foreach ($sucursales AS $row) {
                                        if ($row->activo == 1) {
                                            ?>
                                            <option value="<?= $row->id ?>" <?= $row->id == $id_sucursal_origen ? "selected" : "" ?>>
                                                <?= $row->cliente . " - " . $row->nombre ?>
                                            </option>

                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Empresa - Sucursal
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="id_sucursal_destino">Sucursal destino</label>
                                <select class="form-control select2" id="id_sucursal_destino" name="id_sucursal_destino" style="width: 100%;" required="">
                                    <option value="">Selecciona una Empresa - Sucursal</option>
                                    <?php
                                    foreach ($sucursales AS $row) {
                                        if ($row->activo == 1) {
                                            ?>
                                            <option value="<?= $row->id ?>" <?= $row->id == $id_sucursal_destino ? "selected" : "" ?>>
                                                <?= $row->cliente . " - " . $row->nombre ?>
                                            </option>

                                            <?php
                                        }
                                    }
                                    ?>
                                </select>

                                <div class="invalid-feedback">
                                    Favor de capturar el campo Empresa - Sucursal
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="msg" value="ok">

                        <div class="col-md-2 mt-2">

                            <br>
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-cog pr-2"></i> Procesar</button>
                        </div>

                    </div>
                    <!-- /.row -->
                </form>

                <?php
                if (count($_POST) > 0) {

                    $empresas->clonarSucursal($id_sucursal_origen, $id_sucursal_destino);
                }
                ?>


            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

