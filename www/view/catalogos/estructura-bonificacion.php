<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-cog nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card ">
            <div class="card-header">
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " href="estructura-descuentos" aria-selected="false">Descuentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="estructura-formas-pago" aria-selected="false">Formas de Pago</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="estructura-indicaciones" aria-selected="false">Indicaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="estructura-referencias" aria-selected="false">Referencias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="estructura-bonificacion" aria-selected="true"> Bonificación/Aumento</a>
                    </li>
                </ul>                                                                                                                                          

            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-12">
                        <form class="needs-validation-bonificacion" action="controller/catalogos/Estructura?opc=registro-bonificacion" method="post" novalidate="">
                            <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="estado">Departamento</label>
                                        <select class="form-control select2 text-uppercase" name="id_departamento" style="width: 100%;" required="">
                                            <option value="">Selecciona un Departamento</option>
                                            <?php
                                            foreach ($departamentos AS $row) {
                                                ?>
                                                <option value="<?= $row->id ?>"><?= $row->departamento ?></option>

                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Favor de seleccionar el campo Departamento
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="monedero">Bonificación a Monedero (%)</label>
                                        <input id="monedero" type="text" class="form-control form-control-border text-uppercase validate"  name="monedero" value="" placeholder="Bonificación a Monedero (%)" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Bonificación a Monedero (%)
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="aumento">Aumento a Estudios (%)</label>
                                        <input id="aumento" type="text" class="form-control form-control-border text-uppercase validate"  name="aumento" value="" placeholder="Aumento a Estudios (%)" required="" >
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Aumento a Estudios (%)
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="descuento">Descuento a Estudios (%)</label>
                                        <input id="descuento" type="text" class="form-control form-control-border text-uppercase validate"  name="descuento" value="" placeholder="Descuento a Estudios  (%)" required="" >
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Descuento a Estudios (%)
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 offset-md-4 pt-4 pb-2">
                                    <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                                </div>

                            </div>
                            <!-- /.row -->
                        </form>
                        <div class="row">

                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- Eliminar Doctor -->
<div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Está seguro que desea procesar los cambios?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Guardar" para procesar los cambios  <span id="nombre" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <button id="btn-send-bonificacion" class="btn btn-primary" >Guardar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .card-header {
        border-bottom: transparent
    }

</style>