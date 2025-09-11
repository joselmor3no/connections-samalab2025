<!-- Content Wrapper. Contains page content -->
<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-file-signature nav-icon"></i><?= $page_title ?></h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> Reporte de resultados</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/Formato?opc=laboratorio" method="post" novalidate="">
                    <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $id_cliente ?>">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="posicion_logo">Logotipo</label>
                                <select class="form-control select2" id="posicion_logo" name="posicion_logo" required
                                        style="width: 100%;">
                                    <option value="">Selecciona Pocisión</option>
                                    <option value="Centrado"
                                            <?= $editor[0]->posicion_logo == 'Centrado' ? 'selected' : '' ?>>Centrado
                                    </option>
                                    <option value="Izquierda"
                                            <?= $editor[0]->posicion_logo == 'Izquierda' ? 'selected' : '' ?>>Izquierdo
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Logotipo
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fuente">Fuente</label>
                                <select class="form-control select2" id="fuente" name="fuente" required
                                        style="width: 100%;">
                                    <option value="-1">Seleccionar Fuente</option>
                                    <option value="Arial" <?= $editor[0]->fuente == 'Arial' ? 'selected' : '' ?>>Arial
                                    </option>
                                    <option value="courier" <?= $editor[0]->fuente == 'courier' ? 'selected' : '' ?>>
                                        Calibri</option>
                                    <option value="times" <?= $editor[0]->fuente == 'times' ? 'selected' : '' ?>>Times
                                    </option>
                                    <option value="symbol" <?= $editor[0]->fuente == 'symbol' ? 'selected' : '' ?>>
                                        Symbol</option>
                                    <option value="zapfDingbats"
                                            <?= $editor[0]->fuente == 'zapfDingbats' ? 'selected' : '' ?>>Zapf</option>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Fuente
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo">
                                    Estilo
                                </label>
                                <select class="form-control select2" required id="tipo" name="tipo"
                                        style="width: 100%;">
                                    <option value="">Selecciona Estilo</option>
                                    <option value="Regular" <?= $editor[0]->tipo == 'Regular' ? 'selected' : '' ?>>
                                        Regular</option>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Estilo
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="punto"> Tamaño </label>
                                <select class="form-control select2" required id="punto" name="punto"
                                        style="width: 100%;">
                                    <option value="">Tamaño de fuente</option>
                                    <option value="8" <?= $editor[0]->punto == '8' ? 'selected' : '' ?>>8 pts</option>
                                    <option value="9" <?= $editor[0]->punto == '9' ? 'selected' : '' ?>>9 pts</option>
                                    <option value="10" <?= $editor[0]->punto == '10' ? 'selected' : '' ?>>10 pts
                                    </option>
                                    <option value="11" <?= $editor[0]->punto == '11' ? 'selected' : '' ?>>11 pts
                                    </option>
                                    <option value="12" <?= $editor[0]->punto == '12' ? 'selected' : '' ?>>12 pts
                                    </option>
                                    <option value="13" <?= $editor[0]->punto == '13' ? 'selected' : '' ?>>13 pts
                                    </option>
                                    <option value="14" <?= $editor[0]->punto == '14' ? 'selected' : '' ?>>14 pts
                                    </option>
                                    <option value="15" <?= $editor[0]->punto == '15' ? 'selected' : '' ?>>15 pts
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Tamaño
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group " required>
                                <label for="head">Encabezado (cm)</label>
                                <input type="text" class="form-control " required id="head" name="head"
                                       placeholder="3.0" value="<?= $editor[0]->head ?>">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Encabezado
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="footer">Pie de página (cm)</label>
                                <input type="text" class="form-control " required id="footer" name="footer"
                                       placeholder="3.0" value="<?= $editor[0]->footer ?>">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Pie de página
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="color_linea">Linea</label>
                                <select class="form-control select2" id="color_linea" name="color_linea"
                                        style="width: 100%;">
                                    <option value="">Selecciona Color</option>
                                    <option value="Negro" <?= $editor[0]->color_linea == 'Negro' ? 'selected' : '' ?>>
                                        Negro</option>
                                    <option value="Azul" <?= $editor[0]->color_linea == 'Azul' ? 'selected' : '' ?>>Azul
                                    </option>
                                    <option value="Rojo" <?= $editor[0]->color_linea == 'Rojo' ? 'selected' : '' ?>>Rojo
                                    </option>
                                    <option value="Rosa" <?= $editor[0]->color_linea == 'Rosa' ? 'selected' : '' ?>>Rosa
                                    </option>
                                    <option value="Gris" <?= $editor[0]->color_linea == 'Gris' ? 'selected' : '' ?>>Gris
                                    </option>
                                    <option value="Verde" <?= $editor[0]->color_linea == 'Verde' ? 'selected' : '' ?>>
                                        Verde</option>
                                    <option value="Amarillo"
                                            <?= $editor[0]->color_linea == 'Amarillo' ? 'selected' : '' ?>>Amarillo</option>
                                </select>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="separador">Separador de referencia</label>
                                <select class="form-control select2" required id="separador" name="separador"
                                        style="width: 100%;">
                                    <option value="defecto" selected="selected">Por defecto</option>
                                    <option value="a" <?= $editor[0]->separador == 'a' ? 'selected' : '' ?>>'a' Ejem. 10
                                        a 20</option>
                                    <option value="-" <?= $editor[0]->separador == '-' ? 'selected' : '' ?>>'-' Ejem. 10
                                        - 20</option>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Separador de referencia
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="asterisco">Mostrar * en intervalos fuera de rango</label>
                                <select class="form-control select2" id="asterisco" name="asterisco"
                                        style="width: 100%;">
                                    <option value="1" <?= $editor[0]->asterisco == '1' ? 'selected' : '' ?>>Si</option>
                                    <option value="0" <?= $editor[0]->asterisco == '0' ? 'selected' : '' ?>>No</option>
                                </select>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color">Color distintivo en resultados</label>
                                <select class="form-control select2" id="color" name="color" style="width: 100%;">
                                    <option value="1" <?= $editor[0]->color == '1' ? 'selected' : '' ?>>Si</option>
                                    <option value="0" <?= $editor[0]->color == '0' ? 'selected' : '' ?>>No</option>
                                </select>

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="horafecha">Hora de impresión</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="horafecha" type="checkbox"
                                           <?= $editor[0]->horafecha == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Hora de impresión</label>
                                </div>
                            </div>
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