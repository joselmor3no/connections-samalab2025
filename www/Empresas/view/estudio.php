
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
                    <a href="catalogo-estudios" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i> Buscar</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $estudio[0]->id == "" ? "Alta" : "Modificación" ?> de Estudio</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/Estudio?opc=registro" method="post" novalidate="">
                    <input type="hidden" id="id_admin" name="id_admin" value="<?= $id_admin ?>">
                    <input type="hidden"  name="id_estudio" value="<?= $estudio[0]->id ?>">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="no_estudio">No.</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="no_estudio" value="<?= $estudio[0]->no_estudio ?>" placeholder="No." required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo No.
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre_estudio">Nombre</label>
                                <input type="text" class="form-control form-control-border"  name="nombre_estudio" value="<?= $estudio[0]->nombre_estudio ?>" placeholder="Nombre" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Nombre
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="alias">Alias</label>
                                <input type="text" class="form-control form-control-border text-uppercase" id="alias"  name="alias" value="<?= $estudio[0]->alias ?>" placeholder="Alias" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Alias
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo">Tipo estudio</label>
                                <select class="form-control select2"  name="tipo" style="width: 100%;" required="">
                                    <option value="Estudios" <?= $estudio[0]->tipo == 'Estudios' ? "selected" : "" ?>>Estudios</option>
                                    <option value="Gabinete" <?= $estudio[0]->tipo == 'Gabinete' ? "selected" : "" ?>>Gabinete</option>

                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Tipo estudio
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_departamento">Departamento</label>
                                <select class="form-control select2" name="id_departamento" style="width: 100%;" required="">
                                    <option value = "">Selecciona un Formato</option>
                                    <?php
                                    foreach ($departamentos AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $estudio[0]->id_departamento == $row->id ? "selected" : "" ?>><?= $row->departamento ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Departamento
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estado">Sección</label>
                                <select class="form-control select2" name="id_secciones" style="width: 100%;" required="">
                                    <option value = "">Selecciona una Sección</option>
                                    <?php
                                    foreach ($secciones AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $estudio[0]->id_secciones == $row->id ? "selected" : "" ?>><?= $row->seccion ?></option>

                                        <?php
                                    }
                                    ?>

                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Sección
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estado">Materia Biológica</label>
                                <select class="form-control select2" name="id_materia_biologica" style="width: 100%;" required="">
                                    <option value = "">Selecciona una Materia</option>
                                    <?php
                                    foreach ($materia AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $estudio[0]->id_materia_biologica == $row->id ? "selected" : "" ?>><?= $row->materia ?></option>

                                        <?php
                                    }
                                    ?>

                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Materia Biológica
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estado">Recipiente Biológico</label>
                                <select class="form-control select2" name="id_recipiente_biologico" style="width: 100%;" required="">
                                    <option value = "">Selecciona un Recipiente</option>
                                    <?php
                                    foreach ($recipiente AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $estudio[0]->id_recipiente_biologico == $row->id ? "selected" : "" ?>><?= $row->recipiente ?></option>

                                        <?php
                                    }
                                    ?>

                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Recipiente Biológico
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="muestras">Muestras</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="muestras" value="<?= $estudio[0]->muestras ?>" placeholder="Muestras" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Muestras
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="etiquetas">Etiquetas</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="etiquetas" value="<?= $estudio[0]->etiquetas ?>" placeholder="Etiquetas" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Etiquetas
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sat">Código SAT</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="sat" value="<?= $estudio[0]->sat ?>" placeholder="Código SAT" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Código SAT 
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="resultado">Tipo resultado</label>
                                <select class="form-control select2"  name="resultado" style="width: 100%;" required="">
                                    <option value="componente" <?= $estudio[0]->resultado_componente == "1" ? "selected" : "" ?> >Componente</option>
                                    <option value="texto" <?= $estudio[0]->resultado_texto == "1" ? "selected" : "" ?>>Texto</option>

                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Tipo resultado
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="orden_impresion">Orden impresión</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="orden_impresion" value="<?= $estudio[0]->orden_impresion ?>" placeholder=" Orden impresión" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Orden impresión
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_tipo_reporte">Formato de Impresión</label>
                                <select class="form-control select2"  name="id_tipo_reporte" style="width: 100%;" required="">
                                    <option value = "">Selecciona un Formato</option>
                                    <?php
                                    foreach ($reporte AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $estudio[0]->id_tipo_reporte == $row->id ? "selected" : "" ?>><?= $row->reporte ?></option>

                                        <?php
                                    }
                                    ?>

                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Formato de Impresión
                                </div>
                            </div>
                        </div>


                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="fur">FUR</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="fur" type="checkbox" <?= $estudio[0]->fur == "1" ? "checked" : "" ?> >
                                    <label class="form-check-label">FUR</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="fud">FUD</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="fud" type="checkbox" <?= $estudio[0]->fud == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">FUD</label>
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
