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
                        <a class="nav-link " href="estructura-indicaciones" aria-selected="false">Indicaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="estructura-referencias" aria-selected="true">Referencias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="estructura-bonificacion" aria-selected="false"> Bonificación/Aumento</a>
                    </li>
                </ul>                                                                                                                                          

            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">
                        <form class="needs-validation" action="controller/catalogos/Estructura?opc=registro-referencia" method="post" novalidate="">
                            <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                            <input type="hidden"  name="id" value="<?= $referencia[0]->id ?>">
                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="no">No.</label>
                                        <div class="border-bottom w-100 pt-1 pb-2"><?= $referencia[0]->consecutivo != "" ? $referencia[0]->consecutivo : $consecutivo ?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="codigo">Código</label>
                                        <input type="text" class="form-control form-control-border text-uppercase"  name="codigo" value="<?= $referencia[0]->codigo ?>" placeholder="Código" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Código
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control form-control-border text-uppercase"  name="nombre" value="<?= $referencia[0]->nombre ?>" placeholder="Nombre" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Nombre
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direccion">Dirección</label>
                                        <input type="text" class="form-control form-control-border text-uppercase"  name="direccion" value="<?= $referencia[0]->direccion ?>" placeholder="Dirección" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Dirección
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="ciudad">Ciudad</label>
                                        <input type="text" class="form-control form-control-border text-uppercase"  name="ciudad" value="<?= $referencia[0]->ciudad ?>" placeholder="Ciudad" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Ciudad
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        <select class="form-control select2 text-uppercase" name="id_cat_estado" style="width: 100%;">
                                            <option value="">Selecciona un Estado</option>
                                            <?php
                                            foreach ($estados AS $row) {
                                                ?>
                                                <option value="<?= $row->id ?>" <?= $referencia[0]->id_cat_estados == $row->id ? "selected" : "" ?>><?= $row->estado ?></option>

                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cp">CP</label>
                                        <input type="text" class="form-control form-control-border text-uppercase"  name="cp" value="<?= $referencia[0]->cp ?>" placeholder="CP" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo CP
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono</label>
                                        <input type="text" class="form-control form-control-border text-uppercase"  name="telefono" value="<?= $referencia[0]->telefono ?>" placeholder="Teléfono" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Teléfono
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Correo electrónico</label>
                                        <input type="text" class="form-control form-control-border text-uppercase"  name="email" value="<?= $referencia[0]->email ?>" placeholder="Correo electrónico" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Correo electrónico
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 offset-md-4 pt-4 pb-2">
                                    <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                                </div>

                            </div>
                            <!-- /.row -->
                        </form>
                    </div>
                    <div class="col-md-6">
                        <table id="" class="table table-bordered table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>Consecutivo</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($datos AS $row) {
                                    ?>
                                    <tr>
                                        <td><?= $row->consecutivo ?></td>
                                        <td><?= $row->codigo ?></td>
                                        <td><?= $row->nombre ?></td>
                                        <td><?= $row->direccion ?></td>

                                        <td> 
                                            <div class="row">
                                                <form class="pr-2" action="estructura-referencias"  method="POST">
                                                    <input type="hidden" name="id" value="<?= $row->id ?>" class="d-none">
                                                    <button type="submit" class="btn btn-sm btn-warning rounded-circle m-1"  data-toggle="tooltip" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </form>

                                                <button class="btn btn-sm btn-danger rounded-circle m-1 delete-estructura" data-id="<?= $row->id ?>" data-nombre="<?= $row->nombre ?>" data-toggle="tooltip" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>

                                    <?php
                                    $i++;
                                }
                                ?>
                            </tbody>
                        </table>

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
<div class="modal fade" id="modConfirmarDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Está seguro que desea eliminar la Referencia?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar la referencia <span id="nombre" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form action="controller/catalogos/Estructura?opc=delete-referencia" method="POST" >
                    <input type="hidden" class="d-none" id="id_estructura" name="id" value="">
                    <button id="" class="btn btn-danger btn-cargando" >Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card-header {
        border-bottom: transparent
    }

</style>