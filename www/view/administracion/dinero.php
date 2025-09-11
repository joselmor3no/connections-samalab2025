<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fa fa-money-check-alt nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
               
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Monedero</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/administracion/Monedero?opc=registro" method="post" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">

                    <div class="row">

                        <div class = "col-md-4">
                            <div class = "form-group">
                                <label for = "no_tarjeta">Número de Tarjeta</label>
                                <input id="no_tarjeta" type = "text" class = "form-control form-control-border" name = "no_tarjeta" value="" placeholder = "Número de Tarjeta" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Número de Tarjeta
                                </div>
                            </div>
                        </div>
                        <div class = "col-md-4">
                            <div class = "form-group">
                                <label for = "pass">Monto ($)</label>
                                <input type = "text" class = "form-control form-control-border" name = "monto" value="" placeholder = "Monto" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Monto
                                </div>
                            </div>
                        </div>

                        <div class="col-md-md-2 mt-4">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                        </div>

                        <hr>

                    </div><!-- .row -->

                </form>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
