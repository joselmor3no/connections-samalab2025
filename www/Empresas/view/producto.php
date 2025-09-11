<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-prescription-bottle-alt nav-icon"></i> <?= $page_title ?></h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $sucursal[0]->id == "" ? "Alta" : "Modificación" ?> de productos</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/Inventario?opc=registro-producto" method="post"
                      novalidate="">
                    <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $id_cliente ?>">
                    <input type="hidden" id="id_producto" name="id_producto" value="<?= $id_producto ?>">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control form-control-border text-uppercase" name="nombre"
                                       value="<?= $producto[0]->nombre ?>" placeholder="Nombre" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Nombre
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_presentacion_producto">
                                    Presentación
                                </label>
                                <button id="btn-new-presentacion" type="button" class="btn btn-sm btn-primary rounded-circle m-1" data-toggle="tooltip" title="Nuevo">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <select class="form-control select2" required id="id_presentacion_producto"
                                        name="id_presentacion_producto" style="width: 100%;">
                                    <option value="">Selecciona una Presentación</option>
                                    <?php
                                    foreach ($presentacion as $row) {
                                        ?>
                                        <option value="<?= $row->id ?>"
                                                <?= $producto[0]->id_presentacion_producto == $row->id ? "selected" : "" ?>>
                                            <?= $row->nombre ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Presentación
                                </div>
                            </div>
                        </div>


                        <div class="col-md-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cantidad">Catidad</label>
                                    <input type="text" class="form-control form-control-border text-uppercase"
                                           name="cantidad" value="<?= $producto[0]->cantidad ?>" placeholder="cantidad "
                                           required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo cantidad
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_unidad_producto">
                                    Unidad
                                </label>
                                <button id="btn-new-unidad" type="button" class="btn btn-sm btn-primary rounded-circle m-1" data-toggle="tooltip" title="Nuevo">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <select class="form-control select2" required id="id_unidad_producto"
                                        name="id_unidad_producto" style="width: 100%;">
                                    <option value="">Selecciona una Unidad</option>
                                    <?php
                                    foreach ($unidades as $row) {
                                        ?>
                                        <option value="<?= $row->id ?>"
                                                <?= $producto[0]->id_unidad_producto == $row->id ? "selected" : "" ?>>
                                            <?= $row->nombre ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Unidad
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="stock_min">Stock Mínimo</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="stock_min" value="<?= $producto[0]->stock_min ?>" placeholder="stock minimo"
                                       required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Stock Mínimo
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cant_pruebas">Catidad de Pruebas</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="cant_pruebas" value="<?= $producto[0]->cant_pruebas ?>"
                                       placeholder="cantidad de pruebas" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo cantidad de pruebas
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_departamento_producto">
                                    Departamento
                                </label>
                                <select class="form-control select2" required id="id_departamento_producto"
                                        name="id_departamento_producto" style="width: 100%;">
                                    <option value="">Selecciona un Departamento</option>
                                    <?php
                                    foreach ($departamentos as $row) {
                                        ?>
                                        <option value="<?= $row->id ?>"
                                                <?= $producto[0]->id_departamento_producto == $row->id ? "selected" : "" ?>>
                                            <?= $row->nombre ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Departamento
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 offset-md-5 pt-4 pb-2">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i
                                    class="fa fa-save pr-2"></i> Guardar</button>
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

<!-- Nuevo presentaion -->
<div class="modal fade" id="new-presentacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Presentación</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="form_presentacion" class="needs-validation-presentacion" action="#" method="POST" novalidate="">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombre">Presentación</label>
                                    <input type="text" class="form-control form-control-border text-uppercase" id="presentacion" name="presentacion"
                                           value="" placeholder="Nombre" required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Presentación
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

<!-- Nueva unidad -->
<div class="modal fade" id="new-unidad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Unidad</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="form_unidad" class="needs-validation-unidad" action="#" method="POST" novalidate="">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombre">Unidad</label>
                                    <input type="text" class="form-control form-control-border text-uppercase" id="unidad" name="unidad"
                                           value="" placeholder="Unidad" required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Unidad
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