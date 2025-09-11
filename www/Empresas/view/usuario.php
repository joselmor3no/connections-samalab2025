<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-user-plus nav-icon"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="usuarios" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i>
                        Buscar</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $usuario_sucursal[0]->id == "" ? "Alta" : "Modificación" ?> de Usuario</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/Sucursal?opc=registro-usuario" method="post" novalidate="">
                    <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $id_cliente ?>">
                    <input type="hidden" name="id_usuario" value="<?= $usuario_sucursal[0]->id ?>">
                    <div class="row">


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control form-control-border text-uppercase" name="nombre"
                                       value="<?= $usuario_sucursal[0]->nombre ?>" placeholder="Nombre" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Nombre
                                </div>
                            </div>
                        </div>
                        <?php
                        list($codigo, $user) = explode("_", $usuario_sucursal[0]->usuario);
                        ?>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="descarga">Usuario</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span id="codigo_" class="input-group-text"><?= $codigo . "_" ?></span>
                                        <input type="hidden" id="codigo" name="codigo" value="<?= $codigo ?>">
                                    </div>
                                    <input type="text" class="form-control form-control-border"  name="usuario" value="<?= $user ?>" placeholder="Usuario">

                                </div>

                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control form-control-border"
                                       name="password" value="<?= $usuario_sucursal[0]->contraseña ?>"
                                       placeholder="Contraseña">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_sucursal">Sucursal</label>
                                <select class="form-control select2" id="id_sucursal" name="id_sucursal" required=""
                                        style="width: 100%;">
                                    <option value="">Selecciona una Sucursal</option>
                                    <?php
                                    foreach ($lista_sucursales as $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" data-codigo='<?= $row->codigo ?>'
                                                <?= $usuario_sucursal[0]->id_sucursal == $row->id ? "selected" : "" ?>>
                                            <?= $row->nombre . " ($row->codigo)" ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Sucursal
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