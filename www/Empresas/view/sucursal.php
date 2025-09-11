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
                    <a href="sucursales" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i>
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
                <h3 class="card-title"> <?= $sucursal[0]->id == "" ? "Alta" : "Modificación" ?> de Sucursal</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/Sucursal?opc=registro" method="post" novalidate="" enctype="multipart/form-data">
                    <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $id_cliente ?>">
                    <input type="hidden" name="id_sucursal" value="<?= $sucursal[0]->id ?>">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="no">Prefijo</label>
                                <div class="border-bottom w-100 pt-1 pb-2">
                                    <?= $sucursal[0]->codigo != "" ? $sucursal[0]->codigo : $consecutivo[0]->prefijo . $consecutivo[0]->consecutivo ?>
                                    <input type="hidden" name="codigo" value="<?= $sucursal[0]->codigo != "" ? $sucursal[0]->codigo : $consecutivo[0]->prefijo . $consecutivo[0]->consecutivo ?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control form-control-border text-uppercase" name="nombre"
                                       value="<?= $sucursal[0]->nombre ?>" placeholder="Nombre" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Nombre
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="direccion" value="<?= $sucursal[0]->direccion ?>" placeholder="Dirección">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="direccion2">Dirección alternativa</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="direccion2" value="<?= $sucursal[0]->direccion2 ?>" placeholder="Dirección">
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select class="form-control select2" id="id_estado" name="estado" style="width: 100%;">
                                    <option value="">Selecciona un Estado</option>
                                    <?php
                                    foreach ($estados AS $row) {
                                        if ($sucursal[0]->estado == $row->id)
                                            $municipios = $catalogos->getMunicipios($row->estado);
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $sucursal[0]->estado == $row->id ? "selected" : "" ?>><?= $row->estado ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="municipio">Ciudad</label>
                                <select class="form-control select2" id="id_municipio" name="ciudad"
                                        style="width: 100%;">
                                    <option value="">Selecciona un Estado</option>
                                    <?php
                                    foreach ($municipios AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $sucursal[0]->ciudad == $row->id ? "selected" : "" ?>><?= $row->municipio ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tel1">Teléfono</label>
                                <input type="text" class="form-control form-control-border" name="tel1"
                                       value="<?= $sucursal[0]->tel1 ?>" placeholder="Teléfono">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tel2">Teléfono 2</label>
                                <input type="text" class="form-control form-control-border" name="tel2"
                                       value="<?= $sucursal[0]->tel2 ?>" placeholder="Teléfono">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select name="tipo" id="tipo" class="form-control">
                                    <option value="SUCURSAL" >Sucursal</option>
                                    <option value="MATRIZ">Matriz</option>
                                </select>
                                <?php  echo '<script>document.getElementById("tipo").value="'.$sucursal[0]->tipo.'"</script>' ?>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email">Correo eletrónico</label>
                                <input type="text" class="form-control form-control-border" name="email"
                                       value="<?= $sucursal[0]->email ?>" placeholder="Correo eletrónico">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="quimico">Químico encargado</label>
                                <input type="text" class="form-control form-control-border  text-uppercase" name="quimico"
                                       value="<?= $sucursal[0]->quimico ?>" placeholder="Químico">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cedula">Cédula del químico encargado</label>
                                <input type="text" class="form-control form-control-border" name="cedula"
                                       value="<?= $sucursal[0]->cedula ?>" placeholder="Cédula">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="quimico_aux">Químico auxiliar</label>
                                <input type="text" class="form-control form-control-border text-uppercase" name="quimico_aux"
                                       value="<?= $sucursal[0]->quimico_aux ?>" placeholder="Químico">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cedula_aux">Cédula del Químico auxiliar</label>
                                <input type="text" class="form-control form-control-border" name="cedula_aux"
                                       value="<?= $sucursal[0]->cedula_aux ?>" placeholder="Cédula">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inicio_urgencias">Inicio urgencias</label>
                                <input type="time" class="form-control form-control-border" name="inicio_urgencias"
                                       value="<?= $sucursal[0]->inicio_urgencias ?>" placeholder="Inicio urgencias">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fin_urgencias">Fin urgencias</label>
                                <input type="time" class="form-control form-control-border" name="fin_urgencias"
                                       value="<?= $sucursal[0]->fin_urgencias ?>" placeholder="Fin urgencias">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sitio">% aumento de urgencias</label>
                                <input type="text" class="form-control form-control-border" name="aumento_urgencias"
                                       value="<?= $sucursal[0]->aumento_urgencias ?>" placeholder="Aumento">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="impresion">Medida de ticket</label>
                                <select class="form-control select2" name="impresion" style="width: 100%;">
                                    <option value="">Selecciona una Medida de ticket</option>
                                    <option value="2" <?= $sucursal[0]->impresion == 2 ? "selected" : "" ?>>6 CM
                                    </option>
                                    <option value="1" <?= $sucursal[0]->impresion == 1 || $sucursal[0]->impresion == "" ? "selected" : "" ?>>8 CM
                                    </option>
                                    <option value="3" <?= $sucursal[0]->impresion == 3 ? "selected" : "" ?>>Media carta
                                    </option>


                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputFile">Logo (Máx. 250x250 px)
                                </label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile1" name="logo"
                                               accept=".png, .jpg, .jpeg">
                                        <label class="custom-file-label" for="exampleInputFile1">Seleccionar
                                            archivo</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputFile">Firma Químico Encargado (Máx. 250x250 px)</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile2"
                                               name="firma_quimico_encargado" accept=".png, .jpg, .jpeg">
                                        <label class="custom-file-label" for="exampleInputFile2">Seleccionar
                                            archivo</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputFile">Firma Químico Auxiliar (Máx. 250x250 px)</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile3"
                                               name="firma_quimico_auxiliar" accept=".png, .jpg, .jpeg">
                                        <label class="custom-file-label" for="exampleInputFile3">Seleccionar
                                            archivo</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                        </div>
                        <div class="col-md-3">
                            <?= $sucursal[0]->img != "" ? '<img id="previewImg1" class="img-thumbnail w-50" src="../images-sucursales/' . $sucursal[0]->img . '">' : '<img id="previewImg1" class="img-thumbnail w-50" src="">' ?>
                        </div>
                        <div class="col-md-3">
                            <?= $sucursal[0]->img != "" ? '<img id="previewImg2" class="img-thumbnail w-50" src="../images-sucursales/firmas/' . $sucursal[0]->firma . '">' : '<img id="previewImg2" class="img-thumbnail w-50" src="">' ?>
                        </div>
                        <div class="col-md-3">
                            <?= $sucursal[0]->img != "" ? '<img id="previewImg3" class="img-thumbnail w-50" src="../images-sucursales/firmas/' . $sucursal[0]->firma_aux . '">' : '<img id="previewImg3" class="img-thumbnail w-50" src="">' ?>
                        </div>


                        <div class="col-md-6">


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