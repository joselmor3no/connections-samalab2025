
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-city nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="empresas" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i> Buscar</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $empresa[0]->id == "" ? "Alta" : "Modificación" ?> de Empresa</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/Empresa?opc=registro" method="post" novalidate="" enctype="multipart/form-data">
                    <input type="hidden" id="id_admin" name="id_admin" value="<?= $id_admin ?>">
                    <input type="hidden"  name="id_empresa" value="<?= $empresa[0]->id ?>">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="prefijo">Prefijo</label>
                                <input type="text" class="form-control form-control-border"  name="prefijo" value="<?= $empresa[0]->prefijo ?>" placeholder="Prefijo" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Prefijo
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre">Empresa</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="nombre" value="<?= $empresa[0]->nombre ?>" placeholder="Empresa" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Empresa
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="direccion" value="<?= $empresa[0]->direccion ?>" placeholder="Dirección" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Dirección
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select class="form-control select2" id="id_estado" name="id_cat_estados" style="width: 100%;" required="">
                                    <option value="">Selecciona un Estado</option>
                                    <?php
                                    foreach ($estados AS $row) {
                                        if ($empresa[0]->id_cat_estados == $row->id)
                                            $municipios = $catalogos->getMunicipios($row->estado);
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $empresa[0]->id_cat_estados == $row->id ? "selected" : "" ?>><?= $row->estado ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Estado
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="municipio">Ciudad</label>
                                <select class="form-control select2" id="id_municipio" name="id_cat_municipio" style="width: 100%;" required="">
                                    <option value="">Selecciona un Estado</option>
                                    <?php
                                    foreach ($municipios AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $empresa[0]->id_cat_municipio == $row->id ? "selected" : "" ?>><?= $row->municipio ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Ciudad
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telefono">Télefono</label>
                                <input type="text" class="form-control form-control-border"  name="telefono" value="<?= $empresa[0]->telefono ?>" placeholder="Télefono" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Télefono
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="correo">Correo electrónico</label>
                                <input type="text" class="form-control form-control-border"  name="correo" value="<?= $empresa[0]->correo ?>" placeholder="Correo electrónico" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Correo electrónico
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="max_sucursales">No máximo de sucursales</label>
                                <input type="text" class="form-control form-control-border"  name="max_sucursales" value="<?= $empresa[0]->max_sucursales ?>" placeholder="No máximo " required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo No máximo 
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="fecha_alta">Fecha de alta</label>
                                <input type="date" class="form-control form-control-border"  name="fecha_alta" value="<?= $empresa[0]->fecha_alta ?>" placeholder="Fecha de alta" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Fecha de alta
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_vence">Fecha de vencimiento</label>
                                <input type="date" class="form-control form-control-border"  name="fecha_vence" value="<?= $empresa[0]->fecha_vence ?>" placeholder="Fecha de vencimiento" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Fecha de vencimiento
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="laboratorio">Laboratorio</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="laboratorio" type="checkbox" <?= $empresa[0]->laboratorio == "1" ? "checked" : "" ?> >
                                    <label class="form-check-label">Laboratorio</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="teleradiologia">Teleradiología</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="teleradiologia" type="checkbox" <?= $empresa[0]->teleradiologia == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Teleradiología</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="maquila">Maquila</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="maquila" type="checkbox" <?= $empresa[0]->maquila == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Maquila</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="inactivo">Inactivo</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="inactivo" type="checkbox" <?= $empresa[0]->inactivo == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Inactivo</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="usuario">Usuario</label>
                                <input type="text" class="form-control form-control-border"  name="usuario" value="<?= $empresa[0]->usuario ?>" placeholder="Usuario" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Usuario
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control form-control-border"  name="password" value="<?= $empresa[0]->password ?>" placeholder="Contraseña" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Contraseña
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputFile">Logo (Máx. 250x250 px)</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile" name="file" accept=".png, .jpg, .jpeg">
                                        <label class="custom-file-label" for="exampleInputFile">Seleccionar archivo</label>
                                    </div>
                                    <!--div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div-->
                                </div>
                            </div>

                        </div>

                        <div class="col-md-3">
                            <?= $empresa[0]->img != "" ? '<img id="previewImg" class="img-thumbnail w-50" src="../images-clientes/' . $empresa[0]->img . '">' : '<img id="previewImg" class="img-thumbnail w-50" src="">' ?>
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
