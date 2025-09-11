<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6 ">
                    <h1><i class="fas fa-handshake-slash nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3">
                        <h3 class="card-title" style="color: red;">Lista de Pagos antes del Corte</h3>
                    </div>
                </div>



            </div><!--end card-header-->
            <div class="card-body">
                <table id="" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Paciente</th>
                            <th>Monto</th>
                            <th>Pago</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($pagos_dia AS $row) {
                            ?>

                            <tr>
                                <td><?= $row->codigo ?></td>
                                <td><?= $row->paciente ?></td>
                                <td><?= number_format($row->pago, 2) ?></td>
                                <td><?= $row->forma_pago ?></td>
                                <td><?= $row->usuario ?></td>
                                <td><?= $row->fecha ?></td>                                
                                <td>
                                    <div class="row">

                                        <button class="btn btn-sm btn-danger rounded-circle m-1 delete-pago" data-id="<?= $row->id ?>" data-nombre="<?= $row->paciente ?>" data-toggle="tooltip" title="Eliminar">
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
                <h5 class="modal-title">¿Está seguro que desea eliminar el Pago?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar el pago de <span id="nombre" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form action="controller/admision/Caja?opc=delete-pago" method="POST" >
                    <input type="hidden" class="d-none" id="id_pago" name="id_pago" value="">
                    <button id="" class="btn btn-danger btn-cargando" >Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>




