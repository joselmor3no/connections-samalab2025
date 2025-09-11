<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-users nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="usuario" class="btn btn-block bg-gradient-success"><i class="fa fa-users pr-2"></i> Nuevo</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> Consulta rápida de Usuarios</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="clientes" method="post" novalidate="">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_rol">Base de Datos</label>
                                <select class="form-control select2" name="base" style="width: 100%;" required="">
                                    <option value="">Selecciona un Base</option>
                                    <?php
                                    foreach ($bases AS $row) {
                                        ?>
                                        <option value="<?= $row->Database ?>" <?= $row->Database == $base ? "selected" : "" ?>><?= $row->Database ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Base
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 pt-4 pb-2">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Buscar</button>
                        </div>


                    </div>
                    <!-- /.row -->
                </form>
                <table id="" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Contaseña</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($datos AS $row) {
                            ?>
                            <tr>
                                <td><?= $row->usuario ?></td>
                                <td><?= $row->password ?></td>


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
                <h5 class="modal-title">¿Está seguro que desea eliminar el Usuario?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar el usuario <span id="nombre" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form action="controller/Usuario?opc=delete" method="POST" >
                    <input type="hidden" class="d-none" id="id_usuario" name="id_usuario" value="">
                    <button id="" class="btn btn-danger btn-cargando" >Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
