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
        <form class="needs-validation" action="controller/Formato?opc=recibo" method="post" novalidate="">
            <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $id_cliente ?>">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Generales</h3>
                </div>
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="logotipo_posicion">Pocisión del Logotipo</label>
                                <select class="form-control select2" id="logotipo_posicion" name="logotipo_posicion"
                                        required style="width: 100%;">
                                    <option value="">Selecciona Pocisión</option>
                                    <option value="Centrado"
                                            <?= $editor[0]->logotipo_posicion == 'Centrado' ? 'selected' : '' ?>>Centrado
                                    </option>
                                    <option value="Superior_Derecha"
                                            <?= $editor[0]->logotipo_posicion == 'Superior_Derecha' ? 'selected' : '' ?>>
                                        Superior Derecha</option>
                                    <option value="Superior_Izquierda"
                                            <?= $editor[0]->logotipo_posicion == 'Superior_Izquierda' ? 'selected' : '' ?>>
                                        Superior Izquierda</option>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo de Pocisión del logotipo
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="logotipo_tamano">Tamaño del logotipo</label>
                                <select class="form-control select2" id="logotipo_tamano" name="logotipo_tamano"
                                        required style="width: 100%;">
                                    <option value="">Seleccionar el tamaño</option>
                                    <option value="30" <?= $editor[0]->logotipo_tamano == '30' ? 'selected' : '' ?>>30pt
                                    </option>
                                    <option value="35" <?= $editor[0]->logotipo_tamano == '35' ? 'selected' : '' ?>>35pt
                                    </option>
                                    <option value="40" <?= $editor[0]->logotipo_tamano == '40' ? 'selected' : '' ?>>40pt
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Fuente
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="campo_fuente">
                                    Estilo de Campos
                                </label>
                                <select class="form-control select2" required id="campo_fuente" name="campo_fuente"
                                        style="width: 100%;">
                                    <option value="">Selecciona Estilo</option>
                                    <option value="Arial" <?= $editor[0]->campo_fuente == 'Arial' ? 'selected' : '' ?>>
                                        Arial
                                    </option>
                                    <option value="courier"
                                            <?= $editor[0]->campo_fuente == 'courier' ? 'selected' : '' ?>>
                                        Calibri</option>
                                    <option value="times" <?= $editor[0]->campo_fuente == 'times' ? 'selected' : '' ?>>
                                        Times
                                    </option>
                                    <option value="symbol"
                                            <?= $editor[0]->campo_fuente == 'symbol' ? 'selected' : '' ?>>
                                        Symbol</option>
                                    <option value="zapfDingbats"
                                            <?= $editor[0]->campo_fuente == 'zapfDingbats' ? 'selected' : '' ?>>Zapf
                                    </option>
                                    <option value="Roboto"
                                            <?= $editor[0]->campo_fuente == 'Roboto' ? 'selected' : '' ?>>Roboto
                                    </option>
                                    <option value="Montserrat"
                                            <?= $editor[0]->campo_fuente == 'Montserrat' ? 'selected' : '' ?>>Montserrat
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Estilo
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="campo_tipo"> Tipo de campo </label>
                                <select class="form-control select2" required id="campo_tipo" name="campo_tipo"
                                        style="width: 100%;">
                                    <option value="">Tipo de fuente</option>
                                    <option value="Regular"
                                            <?= $editor[0]->campo_tipo == 'Regular' ? 'selected' : '' ?>>Regular</option>
                                    <option value="Light" <?= $editor[0]->campo_tipo == 'Light' ? 'selected' : '' ?>>
                                        Light</option>
                                    <option value="Bold" <?= $editor[0]->campo_tipo == 'Bold' ? 'selected' : '' ?>>Bold
                                    </option>
                                    <option value="Semi_Bold"
                                            <?= $editor[0]->campo_tipo == 'Semi_Bold' ? 'selected' : '' ?>>Semi Bold
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Tipo de campo
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="campo_tamano"> Tamaño de campo </label>
                                <select class="form-control select2" required id="campo_tamano" name="campo_tamano"
                                        style="width: 100%;">
                                    <option value="">Tamaño de fuente</option>
                                    <option value="12" <?= $editor[0]->campo_tamano == '12' ? 'selected' : '' ?>>12pt
                                    </option>
                                    <option value="13" <?= $editor[0]->campo_tamano == '13' ? 'selected' : '' ?>>13pt
                                    </option>
                                    <option value="14" <?= $editor[0]->campo_tamano == '14' ? 'selected' : '' ?>>14pt
                                    </option>
                                    <option value="15" <?= $editor[0]->campo_tamano == '15' ? 'selected' : '' ?>>15pt
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Tamaño de campo
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="horafecha">Alineación</label>
                                <div class="form-check">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="alineacion1" name="alineacion1"
                                               value="D" <?= $editor[0]->alineacion1 == 'D' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="alineacion1">Derecha</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="alineacion1" name="alineacion1"
                                               value="C" <?= $editor[0]->alineacion1 == 'C' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="alineacion1">Centrado</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="alineacion1" name="alineacion1"
                                               value="I" <?= $editor[0]->alineacion1 == 'I' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="alineacion1">Izquierda</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Encabezado </h3>
                </div>
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombreclinica1">Nombre de la Clinica</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="nombre_clinica1" type="checkbox"
                                           id="nombreclinica1" <?= $editor[0]->nombre_clinica1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Nombre de la Clinica</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sucursal1">Sucursal</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="sucursal1" id="sucursal1" type="checkbox"
                                           <?= $editor[0]->sucursal1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Sucursal</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="orden1">Orden</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="orden1" id="orden1" type="checkbox"
                                           <?= $editor[0]->orden1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Orden</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="orden_interna">Orden interna</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="orden_interna" id="orden_interna"
                                           type="checkbox" <?= $editor[0]->orden_interna == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Orden interna</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="fecha">Fecha</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="fecha1" type="checkbox"
                                           <?= $editor[0]->fecha1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Fecha</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.row -->

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Datos del Paciente</h3>
                </div>
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="no_paciente1">No. del Paciente</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="no_paciente1" id="no_paciente1"
                                           type="checkbox" <?= $editor[0]->no_paciente1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">No. del Paciente</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nombre_paciente1">Nombre del Paciente</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="nombre_paciente1" id="nombre_paciente1"
                                           type="checkbox" <?= $editor[0]->nombre_paciente1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Nombre del Paciente</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="domicilio1">Domicilio</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="domicilio1" id="domicilio1" type="checkbox"
                                           <?= $editor[0]->domicilio1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Domicilio</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="domicilio2">Domicilio alternativo</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="domicilio2" id="domicilio2" type="checkbox"
                                           <?= $editor[0]->domicilio2 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Domicilio alternativo</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="medico1">Medico</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="medico1" id="medico1" type="checkbox"
                                           <?= $editor[0]->medico1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Medico</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="empresa1">Empresa</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="empresa1" id="empresa1" type="checkbox"
                                           <?= $editor[0]->empresa1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Empresa</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="edad1">Edad</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="edad1" id="edad1" type="checkbox"
                                           <?= $editor[0]->edad1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Edad</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sexo1">Sexo</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="sexo1" id="sexo1" type="checkbox"
                                           <?= $editor[0]->sexo1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Sexo</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.row -->

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Datos Generales</h3>
                </div>
                <div class="card-body">

                    <div class="row">


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_entrega1">Fecha de Entrega</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="fecha_entrega1" id="fecha_entrega1"
                                           type="checkbox" <?= $editor[0]->fecha_entrega1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Fecha de Entrega</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dir_hospital1">Dirección Hospital</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="dir_hospital1" id="dir_hospital1"
                                           type="checkbox" <?= $editor[0]->dir_hospital1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Dirección Hospital</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telefono_g1">Teléfono</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="telefono_g1" id="telefono_g1" type="checkbox"
                                           <?= $editor[0]->telefono_g1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Teléfono</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="telefono_g2">Teléfono 2</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="telefono2_g1" id="telefono_g2" type="checkbox"
                                           <?= $editor[0]->telefono2_g1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Teléfono 2</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="web1">Sitio Web</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="web1" id="web1" type="checkbox"
                                           <?= $editor[0]->web1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Sitio Web</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="descripcion_g1">Descripción</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="descripcion_g1" id="descripcion_g1"
                                           type="checkbox" <?= $editor[0]->descripcion_g1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Descripción</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="informacion1">Información</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="informacion1" id="informacion1"
                                           type="checkbox" <?= $editor[0]->informacion1 == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Información</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                        </div>

                        <div class="col-md-2 offset-md-5 pt-4 pb-2">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i
                                    class="fa fa-save pr-2"></i>
                                Guardar</button>
                        </div>

                    </div>
                    <!-- /.row -->

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </form>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->