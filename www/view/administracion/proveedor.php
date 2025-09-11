<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-shipping-fast nav-icon"></i> <?= $page_title ?></h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $proveedor[0]->id == "" ? "Alta" : "Modificación" ?> de Proveedor</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation-proveedor" action="controller/administracion/InventarioC?opc=registro-proveedor" method="post" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input type="hidden" name="id_proveedor" value="<?= $proveedor[0]->id ?>">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control form-control-border text-uppercase" name="nombre"
                                       value="<?= $proveedor[0]->nombre ?>" placeholder="Nombre" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Nombre
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_estado">
                                    Estado
                                </label>
                                <select class="form-control select2" required id="id_estado" name="id_estado"
                                        style="width: 100%;">
                                    <option value="">Selecciona un Estado</option>
                                    <?php
                                    foreach ($estados AS $row) {
                                        if ($proveedor[0]->id_estado == $row->id)
                                            $municipios = $catalogos->getMunicipios($row->estado);
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $proveedor[0]->id_estado == $row->id ? "selected" : "" ?>><?= $row->estado ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo un Estado
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_municipio">
                                    Ciudad
                                </label>
                                <select class="form-control select2" required id="id_municipio" name="id_municipio"
                                        style="width: 100%;">
                                    <option value="">Selecciona un Estado</option>
                                    <?php
                                    foreach ($municipios AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $proveedor[0]->id_municipio == $row->id ? "selected" : "" ?>><?= $row->municipio ?></option>

                                        <?php
                                    }
                                    ?>

                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Ciudad
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contacto">Contacto</label>
                                <input type="text" class="form-control form-control-border text-uppercase" name="contacto"
                                       value="<?= $proveedor[0]->contacto ?>" placeholder="contacto" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Contacto
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control form-control-border text-uppercase" name="telefono"
                                       value="<?= $proveedor[0]->telefono ?>" placeholder="telefono" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Teléfono
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo">
                                    Tipo de proveedor
                                </label>
                                 <button id="btnNewTipo" type="button" class="btn btn-sm btn-primary rounded-circle m-1" data-toggle="tooltip" title="Nuevo">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <select class="form-control select2" required id="tipo" name="id_tipo_proveedor"
                                        style="width: 100%;">
                                    <option value="">Selecciona un tipo</option>
                                    <?php
                                    foreach ($tipo AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $proveedor[0]->id_tipo_proveedor == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>

                                        <?php
                                    }
                                    ?>

                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo tipo de proveedor
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 offset-md-5 pt-4 pb-2">
                            <button type="submit" class="btn btn-block bg-gradient-primary">
                                <i class="fa fa-save pr-2"></i> Guardar
                            </button>
                        </div>


                    </div>
                    <!-- /.row -->
                </form>


            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Eliminar proveedor -->
<div class="modal fade" id="newTipo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tipo de proveedor</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="form_tipo" class="needs-validation-tipo" action="#" method="POST" novalidate="">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control form-control-border text-uppercase" id="nombre" name="nombre"
                                           value="" placeholder="Nombre" required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Nombre
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="offset-md-4 col-md-4">
                            <button type="submit" class="btn btn-block bg-gradient-primary">
                                <i class="fa fa-save pr-2"></i> Guardar
                            </button>
                        </div>

                    </div>

                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>