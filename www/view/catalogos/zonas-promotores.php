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
                        <a class="nav-link active" href="zonas-promotores" aria-selected="true">Zonas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="zonas-promotores-p" aria-selected="false">Promotores</a>
                    </li>
                </ul>                                                                                                                                          

            </div>
            <div class="card-body">

                <div class="row">


                    <div class="col-md-5">
                        <form class="needs-validation" action="controller/catalogos/Doctor?opc=registro-zonas" method="post" novalidate="">
                            <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                            <input type="hidden"  name="id_zona" value="<?php echo ($informacionZona!='') ? $informacionZona->id : '-1' ?>">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="codigo">No</label>
                                        <input id="alias-descuento" type="text" class="form-control form-control-border text-uppercase"  name="znumero" value="<?php echo ($informacionZona!='') ? $informacionZona->numero : $ultimoConsecutivoZona ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="codigo">Nombre de la zona</label>
                                        <input id="alias-descuento" type="text" class="form-control form-control-border"  name="znombre" value="<?php echo ($informacionZona!='') ? $informacionZona->nombre : '' ?>" placeholder="Nombre" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Nombre
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 offset-md-7 pt-4 pb-2">
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
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($listaZonas as $row => $item) {
                                    echo '<tr>
                                        <td>'.$item->numero.'</td>
                                        <td>'.$item->nombre.'</td>
                                        <td> 
                                            <div class="row">
                                                <form class="pr-2" action="zonas-promotores"  method="POST">
                                                    <input type="hidden" name="id" value="'.$item->id.'" class="d-none">
                                                    <button type="submit" class="btn btn-sm btn-warning rounded-circle m-1"  data-toggle="tooltip" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </form>

                                                <button class="btn btn-sm btn-danger rounded-circle m-1 delete-zonas" data-id="'.$item->id.'" data-nombre="'.$item->nombre.'" data-toggle="tooltip" title="Eliminar">
                                                    <i class="fas fa-trash"></i> 
                                                </button>
                                            </div>
                                        </td>
                                    </tr>';
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
                <h5 class="modal-title">¿Está seguro que desea eliminar la zona?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar la zona <span id="nombre" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form action="controller/catalogos/Doctor?opc=delete-zona" method="POST" >
                    <input type="hidden" class="d-none" id="id_zona" name="id_zona" value="">
                    <button  class="btn btn-danger btn-cargando" >Eliminar</button>
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