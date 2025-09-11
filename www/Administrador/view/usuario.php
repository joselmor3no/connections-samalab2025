
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
                    <a href="usuarios" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i> Buscar</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $usuario_[0]->id == "" ? "Alta" : "Modificación" ?> de Usuario</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/Usuario?opc=registro" method="post" novalidate="">
                    <input type="hidden" id="id_admin" name="id_admin" value="<?= $id_admin ?>">
                    <input type="hidden"  name="id_usuario" value="<?= $usuario_[0]->id ?>">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="nombre" value="<?= $usuario_[0]->nombre ?>" placeholder="Nombre" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Nombre
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="usuario">Usuario</label>
                                <input type="text" class="form-control form-control-border"  name="usuario" value="<?= $usuario_[0]->usuario ?>" placeholder="Usuario" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Usuario
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control form-control-border"  name="password" value="<?= $usuario_[0]->password ?>" placeholder="Contraseña" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Contraseña
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_rol">Rol</label>
                                <select class="form-control select2" name="id_rol" style="width: 100%;" required="">
                                    <option value="">Selecciona un Rol</option>
                                    <?php
                                    foreach ($rol AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $usuario_[0]->id_rol == $row->id ? "selected" : "" ?>><?= $row->rol ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Rol
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 offset-md-5 pt-4 pb-2">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
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
