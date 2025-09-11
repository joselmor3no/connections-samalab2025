<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fa fa-prescription-bottle-alt nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>

                <div class = "col-md-2 offset-md-2">
                    <a href="toma-inventario" class="btn btn-block bg-gradient-primary"><i class="fas fa-list-ol pr-2"></i>Toma de Inventario</a>
                </div>

                <div class = "col-md-2">
                    <button type="button" class="btn btn-block bg-gradient-primary load-productos"  title="Productos" data-toggle="modal" data-target="#modalProductos">
                        <i class="fa fa-prescription-bottle-alt "></i> Productos
                    </button>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <!--div class="card-header">
                <h3 class="card-title">Inventario</h3>
            </div-->
            <div class="card-body">
                <form class="needs-validation" action="controller/administracion/InventarioC?opc=registro-vale" method="post" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">

                    <div class="row">

                        <div class = "col-md-12">
                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="movimiento">Movimiento</label>
                                        <select class="custom-select" id="movimiento" name="movimiento" style="width: 100%;">
                                            <option value="Entrada" selected="">Entrada</option>
                                            <option value="Salida">Salida</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="no">No.</label>
                                        <div class="border-bottom w-100 pt-1 pb-2"><?= $vale[0]->consecutivo != "" ? $vale[0]->consecutivo : "1" ?></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="id_proveedor">Proveedores</label>
                                        <select id="id_proveedor" class="form-control" name="id_proveedor" required="" style="width: 100%; height: 100%">
                                            <option value="">Selecciona un Proveedor</option>
                                            <?php
                                            foreach ($proveedores AS $row) {
                                                ?>
                                                <option value="<?= $row->id ?>"><?= $row->nombre ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>

                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Proveedor
                                        </div>
                                    </div>
                                </div>
                                <div class = "col-md-1">
                                    <br>
                                    <a href="proveedores" class="btn btn-primary rounded-circle"  title="Registro Proveedor" >
                                        <i class="fas fa-shipping-fast"></i> 
                                    </a>
                                </div>

                                <div class = "col-md-2">
                                    <div class = "form-group">
                                        <label for = "factura">Factura</label>
                                        <input id="no_tarjeta" type = "text" class = "form-control form-control-border mt-1" name = "factura" value="" required="" placeholder = "Factura">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Factura
                                        </div>
                                    </div>
                                </div>

                                <div class = "col-md-1">
                                    <br>
                                    <button type="button" class="btn btn-primary rounded-circle load-inventario"  title="Buscar Vale" data-toggle="modal" data-target="#modalInventario">
                                        <i class="fa fa-search"></i> 
                                    </button>
                                </div>


                                <div class = "col-md-12">
                                    <div class = "form-group">
                                        <label for = "observaciones">Observaciones</label>
                                        <input id="no_tarjeta" type = "text" class = "form-control form-control-border" name = "observaciones" value="" placeholder = "Observaciones">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Observaciones
                                        </div>
                                    </div>
                                </div>

                            </div><!-- .row -->
                        </div><!-- .col-md-8 -->

                        <div class="col-md-12 m-1">
                            <table id="table_registro" width="100%" class="table-bordered table-responsive" >
                                <thead align="center" class="bg-teal color-palette" style="background: #138496;">
                                    <tr >

                                        <th width="8%">Código</th>
                                        <th width="20%">Descripción</th>
                                        <th width="15%">Marca</th>
                                        <th width="8%">Ingreso</th>
                                        <th>Unidad</th>
                                        <th width="8%">Precio</th>
                                        <th>Caducidad</th>
                                        <th width="4%">IVA</th>
                                        <th width="8%">% Descuento</th>
                                        <th>Subtotal</th>
                                        <th>Existencia</th>
                                        <th>Unidad</th>
                                        <th width="5%">Acciones</th>

                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    <?php
                                    for ($i = 1; $i <= 1; $i++) {
                                        ?>
                                        <tr id='est_<?= $i ?>' class="text-center">
                                            <td>
                                                <input type="text" class="form-control form-control-border text-uppercase codigo" data-id="<?= $i ?>"  name="codigo[]" value="" placeholder="Código" required="">
                                            </td>
                                            <td></td>
                                            <td>
                                                <input type="text" class="form-control form-control-border text-uppercase marca" data-id="<?= $i ?>"  name="marca[]" value="" placeholder="Marca" required="">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-border ingreso" data-id="<?= $i ?>"  name="ingreso[]" value="" placeholder="Ingreso" required="">
                                            </td>
                                            <td></td>
                                            <td>
                                                <input type="text" class="form-control form-control-border precio" data-id="<?= $i ?>"  name="precio[]" value="" placeholder="Precio" required="">
                                            </td>
                                            <td>
                                                <input type="date" class="form-control form-control-border caducidad" data-id="<?= $i ?>"  name="caducidad[]" value="" placeholder="Caducidad" required="">
                                            </td>
                                            <td>
                                                <input type="checkbox" class="iva" data-id="<?= $i ?>" value="">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-border descuento" data-id="<?= $i ?>"  name="descuento[]" value="" placeholder="% Descuento" >
                                            </td>
                                            <td class="subtotal"></td>
                                            <td class="existencia"></td>
                                            <td></td>
                                            <td align='center'>                                          
                                                <button type='button' class='btn btn-sm btn-danger delete-producto rounded-circle mt-1 mb-1' data-id='<?= $i ?>'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                            </td>


                                        </tr>
                                        <?php
                                    }
                                    ?>

                                </tbody>
                            </table>

                        </div>

                        <div class = "col-md-12 text-right">
                            <button type="button" class="btn btn-sm btn-success rounded-circle btn-add-paq"  data-toggle="tooltip" title="Agregar">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <div class = "col-md-4 offset-md-8" >
                            <div class = "form-group row mt-2">
                                <label class="col-md-4 col-form-label " for = "total">Importe</label>
                                <div class="col-md-8 "> <input id="total" type = "text" class = "form-control" name = "total" value="0.0" readonly=""></div>

                            </div>
                        </div>

                        <div class="col-md-4 offset-md-4 ">
                            <button id="save" class="btn btn-block bg-gradient-success"><i class="far fa-save  pr-2"></i> Guardar</button>
                        </div>

                    </div><!-- .row -->

                </form>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->

    <div class="modal fade" id="modalInventario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reportes de Inventario</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation-inventario" action="#" method="POST" novalidate="">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reporte">Reporte</label>
                                    <select id="reporte" class="form-control" name="reporte" style="width: 100%; height: 100%">
                                        <option value="entradas">Entradas</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_ini">De</label>
                                    <input id="nombre_componet" type = "date" class = "form-control form-control-border text-uppercase" name = "fecha_ini" value="<?= date("Y-m-d") ?>" placeholder = "De" required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo De
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_fin">A</label>
                                    <input id="nombre_componet" type = "date" class = "form-control form-control-border text-uppercase" name = "fecha_fin" value="<?= date("Y-m-d") ?>" placeholder = "A" required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo A
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-2 mt-4">
                                <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="table-responsive" style="height: 400px">
                            <table id="tableInventario" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Vale</th>
                                        <th>Proveedor</th>
                                        <th>Factura</th>
                                        <th>Total</th>
                                        <th>Observaciones</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="table-reportes"></tbody>
                            </table>
                        </div>
                    </div>


                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalProductos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reportes de Productos</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="table-responsive" style="height: 400px">
                            <table class="table table-bordered table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>No. Producto</th>
                                        <th>Descripción</th>
                                        <th>Existencia</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($productos AS $row) {
                                        ?>
                                        <tr>
                                            <td><?= $row->consecutivo ?></td>
                                            <td><?= $row->nombre ?></td>
                                            <td><?= $row->existencia ?></td>
                                            <td> 
                                                <a href="reporte-costo-promedio-productos?codigo=<?= $row->consecutivo ?>" target="_blank" class="btn btn-sm btn-primary rounded-circle m-1" title="Costo Promedio">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                                <a href="reporte-existencia-productos?codigo=<?= $row->consecutivo ?>" target="_blank" class="btn btn-sm btn-warning rounded-circle m-1" title="Existencia">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                                <a href="reporte-caducidad-productos?codigo=<?= $row->consecutivo ?>" target="_blank" class="btn btn-sm btn-success rounded-circle m-1" title="Caducidad">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
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


                </div>

                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.content-wrapper -->

<style>
    .col-form-label {
        font-weight: 400 !important;
        color: #060606;
        font-size: 16px;
    }

    .form-control{

        height: calc(1.75rem + 2px);
    }

    .btn-sm{
        font-size: .650rem;    
    }

    .bg-teal, .bg-teal>a {
        color: #000!important;
    }

    .select2-container--default .select2-selection--single {

        padding: .26875rem .55rem !important;

    }


    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 30px !important;
    }

    .ui-menu{
        overflow: auto;
        max-height: 200px;
    }




</style>
