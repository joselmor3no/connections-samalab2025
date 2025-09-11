<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-file-medical nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <button id="new-estudio" class="btn btn-block bg-gradient-success"><i
                            class="fas fa-file-medical pr-2"></i> Nuevo</button>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> Consulta rápida de Combos</h3>
            </div>
            <div class="card-body">
                <table id="" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Estudio</th>
                            <th>Productos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($datos as $row) {
                            ?>
                            <tr>
                                <td><?= $row["id"] ?></td>
                                <td><?= $row["estudio"] ?></td>
                                <td>
                                    <div class="row">
                                        <?php
                                        foreach ($row["productos"] as $fila) {
                                            // var_dump($fila);
                                            ?>
                                            <div class="col-md-12">
                                                <?= $fila->cantidad . " " . $fila->producto . " (" . $fila->cantidad_utilizar . " " . $fila->unidad . ")" ?>   
                                                <button class="btn btn-sm btn-danger rounded-circle m-1 delete-producto"
                                                        data-id="<?= $fila->id ?>" data-nombre="<?= " <span class='text-primary font-weight-bold'>" . $fila->producto . " (" . $fila->cantidad_utilizar . " " . $fila->unidad . ")</span> del estudio <span class='font-weight-bold'>" . $row["estudio"] . "</span>" ?>"
                                                        data-toggle="tooltip" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>


                                            <?php
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">

                                        <button class="btn btn-sm btn-primary rounded-circle m-1 new-producto"
                                                data-id="<?= $row["id"] ?>" data-nombre="PRODUCTOS DE <span class='text-primary'><?= $row["estudio"] ?></span>"
                                                data-toggle="tooltip" title="Producto">
                                            <i class="fas fa-plus"></i>
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
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- Eliminar productos -->
<div class="modal fade" id="modConfirmarDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Está seguro que desea eliminar el Producto?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar el producto 
                <span id="nombre" class=""></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form action="controller/Inventario?opc=delete-producto-combo" method="POST">
                    <input type="hidden" class="d-none" id="id_combo_producto" name="id" value="">
                    <button class="btn btn-danger btn-cargando">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-producto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="estudio" class="modal-title"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" action="controller/Inventario?opc=registro-combo-producto" method="POST" novalidate="">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cantidad">Cantidad</label>
                                <input id="edad_inicio" type = "text" class = "form-control form-control-border text-uppercase" name = "cantidad" value="" placeholder = "Cantidad" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Cantidad
                                </div>  
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="id_producto">Producto</label>
                                <select class="form-control select-add-producto" name="id_producto"
                                        required style="width: 100%;">
                                    <option value="">Seleccionar un Producto</option>
                                    <?php
                                    foreach ($list_productos as $row) {
                                        ?>
                                        <option value="<?= $row->id ?>"><?= $row->nombre . " (" . $row->cantidad_utilizar . " " . $row->unidad . ")" ?></option>

                                        <?php
                                    }
                                    ?>

                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Producto
                                </div>
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">
                    <input type="hidden" class="d-none" id="id_estudio" name="id_estudio" value="">

                    <div class="col-md-4 offset-md-4 pb-2">
                        <button type="submit" class="btn btn-block bg-gradient-primary"><i
                                class="fa fa-save pr-2"></i> Guardar</button>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="add-estudio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Estudio</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" action="controller/Inventario?opc=registro-combo" method="POST" novalidate="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="id_estudio">Estudio</label>
                                <select class="form-control add-estudio" name="id_estudio"
                                        required style="width: 100%;">
                                    <option value="">Seleccionar un Estudio</option>
                                    <?php
                                    foreach ($estudios as $row) {
                                        ?>
                                        <option value="<?= $row->id ?>"><?= $row->nombre_estudio ?></option>
                                        <?php
                                    }
                                    ?>

                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Estudio
                                </div>
                            </div>
                        </div>

                        <table id="table_registro" width="100%" class="table-bordered table-responsive" >
                            <thead align="center" class="bg-secondary">
                                <tr>
                                    <th width="20%">Cantidad</th>
                                    <th>Producto</th>
                                    <th width="20%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <?php
                                for ($i = 1; $i <= 3; $i++) {
                                    ?>
                                    <tr id='est_<?= $i ?>' class="text-center">

                                        <td><input id="cantidad" type = "text" class = "form-control form-control-border text-uppercase" name = "cantidad[]" value="" placeholder = "Cantidad" required=""></td>
                                        <td>                         
                                            <select class="form-control add-estudio" name="id_producto[]"
                                                    required style="width: 100%;">
                                                <option value="">Seleccionar un Producto</option>
                                                <?php
                                                foreach ($list_productos as $row) {
                                                    ?>
                                                    <option value="<?= $row->id ?>"><?= $row->nombre . " (" . $row->cantidad_utilizar . " " . $row->unidad . ")" ?></option>

                                                    <?php
                                                }
                                                ?>

                                            </select>
                                            <div class="invalid-feedback">
                                                Favor de capturar el campo Producto
                                            </div>

                                        </td>
                                        <td align='center'>                                          
                                            <button type='button' class='btn btn-sm btn-danger delete-producto-td rounded-circle mt-1 mb-1' data-id='<?= $i ?>'>
                                                <i class='fas fa-trash'></i>
                                            </button>
                                        </td>


                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>

                        </table>


                        <div class = "col-md-12 text-right">
                            <button type="button" class="btn btn-sm btn-success rounded-circle btn-add-paq"  data-toggle="tooltip" title="Agregar">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>


                        <!--div class="col-md-3">
                            <div class="form-group">
                                <label for="cantidad">Cantidad</label>
                                <input id="edad_inicio" type = "text" class = "form-control form-control-border text-uppercase" name = "cantidad" value="" placeholder = "Cantidad" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Cantidad
                                </div>  
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="id_producto">Producto</label>
                                <select class="form-control add-estudio" name="id_producto"
                                        required style="width: 100%;">
                                    <option value="">Seleccionar un Producto</option>
                        <?php
                        foreach ($list_productos as $row) {
                            ?>
                                                    <option value="<?= $row->id ?>"><?= $row->nombre . " (" . $row->cantidad_utilizar . " " . $row->unidad . ")" ?></option>

                            <?php
                        }
                        ?>

                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Producto
                                </div>
                            </div>
                        </div>

                        <div class = "col-md-12 text-right">
                            <button type="button" class="btn btn-sm btn-success rounded-circle btn-add-paq"  data-toggle="tooltip" title="Agregar">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div-->

                        <input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">

                        <div class="col-md-4 offset-md-4 pb-2">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i
                                    class="fa fa-save pr-2"></i> Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<script>
    var productos = <?= json_encode($list_productos) ?>;
</script>