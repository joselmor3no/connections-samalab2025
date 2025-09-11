<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-clipboard-list nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="nuevo-estudio" class="btn btn-block bg-gradient-success"><i class="fa fa-clipboard-list  pr-2"></i> Nuevo</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> Consulta rápida de Estudios (Disponibles)</h3>
            </div>
            <div class="card-body">
                <table id="" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nombre</th>
                            <th>Alias</th>
                            <th>Precio</th>
                            <th>Clase</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($datos AS $row) { 
                            ?>
                            <tr>
                                <td><?= $row->no_estudio ?></td>
                                <td><?= $row->nombre_estudio ?></td>
                                <td><?= $row->alias ?></td>
                                <td>$ <?= number_format($row->precio, 2) ?></td>
                                <td><?= $row->clase ?></td>
                                <td> 
                                    <div class="row">
                                        <form class="pr-2" action="estudio"  method="POST">
                                            <input type="hidden" name="id" value="<?= $row->id ?>" class="d-none">
                                            <input type="hidden" name="id_sucursal" value="<?= $id_sucursal ?>" class="d-none">
                                            <button type="submit" class="btn btn-sm btn-primary rounded-circle m-1"  data-toggle="tooltip" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </form>

                                        <button class="btn btn-sm btn-danger rounded-circle m-1 delete-estudio" data-id="<?= $row->id_estudio ?>" data-nombre="<?= $row->nombre_estudio ?>" data-toggle="tooltip" title="Eliminar">
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


<!-- Eliminar Estudio -->
<div class="modal fade" id="modConfirmarDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Está seguro que desea eliminar el Estudio?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar el estudio <span id="nombre" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form action="controller/catalogos/Estudio?opc=delete" method="POST" >
                    <input type="hidden" class="d-none" id="id_estudio" name="id_estudio" value="">
                    <button id="" class="btn btn-danger btn-cargando" >Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
