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
                    <a href="estudios" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i> Buscar</a>
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
                <form class="needs-validation" action="controller/catalogos/Estudio?opc=registro" method="post" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input type="hidden" id="tipo-resultado"  name="tipo_resultado" value="<?= $estudio[0]->resultado_componente == 1 ? "componente" : "texto" ?>">
                    <input type="hidden" id="id_cat_estudio"  name="id_cat_estudio" value="<?= $estudio[0]->id ?>">
                    <input type="hidden"  name="id_estudio" value="<?= $estudio[0]->id_estudio ?>">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="no">No.</label>
                                <div class="border-bottom w-100 "><?= $estudio[0]->no_estudio ?></div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <div class="border-bottom w-100"><?= $estudio[0]->nombre_estudio ?></div>
                                <span></span>
                            </div>
                        </div>
                        <?php
                        if ($estudio[0]->id_estudio != "") {
                            ?>
                            <div class="col-md-2">
                                <button type="button" data-id="<?= $estudio[0]->id ?>" data-nombre="<?= $estudio[0]->nombre_estudio ?>" class="btn btn-block bg-gradient-primary btn-estudio"><i class="fa fa-file-pdf pr-2"></i> Formatos</button>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="alias">Alias</label>
                                <div class="border-bottom w-100 pt-2 pb-1"><?= $estudio[0]->alias ?></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="publico">Precio público</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text  font-weight-bold">$</span>
                                    </div>
                                    <input type="text" class="form-control form-control-border"  name="publico" value="<?= $estudio[0]->precio_publico ?>" placeholder="Precio público" required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Precio público
                                    </div>                               
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="maquila">Precio maquila</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold">$</span>
                                    </div>
                                    <input type="text" class="form-control form-control-border"  name="maquila" value="<?= $estudio[0]->precio_maquila ?>" placeholder="Precio maquila">

                                </div>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <div class="border-bottom w-100 pt-2 pb-1"><?= $estudio[0]->tipo ?></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="departamento">Departamento</label>
                                <div class="border-bottom w-100"><?= $estudio[0]->departamento ?></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="seccion">Sección</label>
                                <div class="border-bottom w-100"><?= $estudio[0]->seccion ?></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="material">Material Biológica</label>
                                <div class="border-bottom w-100">&nbsp; <?= $estudio[0]->materia ?></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="recipiente">Recipiente Biológico</label>
                                <div class="border-bottom w-100">&nbsp; <?= $estudio[0]->recipiente ?></div>
                            </div>
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group">
                                <label for = "id_especialidad">Indicación</label>
                                <select class = "form-control select2" name = "id_indicaciones" style = "width: 100%;">
                                    <option value = "">Selecciona una Indicación</option>
                                    <?php
                                    foreach ($indicaciones AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $estudio[0]->id_indicaciones == $row->id ? "selected" : "" ?>><?= $row->indicacion ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class = "col-md-2">
                            <div class = "form-group">
                                <label for = "montaje">Montaje</label>
                                <input type = "text" class = "form-control form-control-border" name = "montaje" value="<?= $estudio[0]->montaje ?>" placeholder = "Montaje">
                            </div>
                        </div>

                        <div class = "col-md-2">
                            <div class = "form-group">
                                <label for = "procesos">Proceso</label>
                                <input type = "text" class = "form-control form-control-border" name = "procesos" value="<?= $estudio[0]->procesos ?>" placeholder = "Proceso">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="muestra">Muestras</label>
                                <div class="border-bottom w-100 pt-2 pb-1"><?= $estudio[0]->muestras ?></div>


                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="etiquetas">Etiquetas</label>
                                <div class="border-bottom w-100 pt-2 pb-1"><?= $estudio[0]->etiquetas ?></div>
                            </div>
                        </div>
                        <hr>
                    </div><!-- .row -->
                    <h5 class="text-primary"><i class="fas fa-clipboard-check"></i> Resultados</h5>

                    <div class="row">


                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="no">Tipo</label>
                                <div class="border-bottom w-100 pt-2 pb-1"><?= $estudio[0]->resultado_componente == 1 ? "Componente" : "Texto" ?></div>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="no">Orden</label>
                                <div class="border-bottom w-100 pt-2 pb-1"><?= $estudio[0]->orden_impresion ?></div>
                            </div>
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group">
                                <label for = "id_formato">Formato de Impresión</label>
                                <select class = "form-control select2" id="id_formato" name = "id_formato" required="" >
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

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="fur">FUR</label>
                                <div class="border-bottom w-100 pt-2 pb-1"><?= $estudio[0]->fur == 1 ? "SI" : "NO" ?></div>


                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="fud">FUD</label>
                                <div class="border-bottom w-100 pt-2 pb-1"><?= $estudio[0]->fud == 1 ? "SI" : "NO" ?></div>
                            </div>
                        </div>


                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "id_referencia">Referencia</label>
                                <select class = "form-control select2" name = "id_referencia" style = "width: 100%;">
                                    <option value = "">Selecciona una Referencia</option>
                                    <?php
                                    foreach ($referencia AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $estudio[0]->id_referencia == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group">
                                <label for = "metodo">Método Utilizado</label>
                                <input type = "text" class = "form-control form-control-border" name = "metodo" value="<?= $estudio[0]->metodo_utilizado ?>" placeholder = "Método Utilizado">
                            </div>
                        </div>

                        <div class = "col-md-2">
                            <div class = "form-group">
                                <label for = "volumen">Volumen Requerido (ML)</label>
                                <input type = "text" class = "form-control form-control-border" name = "volumen" value="<?= $estudio[0]->volumen_requerido ?>" placeholder = "Volumen Requerido(ML)">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sat">Código Sat</label>
                                <div class="border-bottom w-100 pt-2 pb-1"><?= $estudio[0]->sat ?></div>
                            </div>
                        </div>

                        <div class = "col-md-2">
                            <div class = "form-group">
                                <label for = "porcentaje">Porcentaje (%)</label>
                                <input type = "text" class = "form-control form-control-border" name = "porcentaje" value="<?= $estudio[0]->porcentaje ?>" placeholder = "Porcentaje">
                            </div>
                        </div>

                        <div class = "col-md-2">
                            <div class = "form-group">
                                <label for = "clase">Clase cobro crédito</label>
                                <select name="clase" id="clase" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <?php   
                                        foreach ($lista_clases_estudio as $row => $item) {
                                            echo '<option value="'.$item->id.'">'.$item->nombre.'</option>';
                                        } 
                                     ?>

                                </select>
                                <?php echo '<script>document.getElementById("clase").value="'.$estudio[0]->id_clase.'";</script>'; ?>
                            </div>
                        </div>

                        <div class="col-md-md-2 offset-md-5 pt-4 pb-2">
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

<div class="modal fade" id="modal-estudio" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-atom"></i> <span id="tipo-resultado_">Componentes </span><span class="text-primary" id="nombre_estudio"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="tipo-componente" class="d-none">
                    <div class="row">
                        <div class="col-md-6 mb-2 mt-n2">
                            <button type="button" class="btn btn-primary position-component"><i class="fas fa-save pr-2"></i>Guardar ordenamiento </button>
                        </div>

                        <div class="col-md-4 text-right mb-2 mt-n2">
                            <button type="button" class="btn btn-outline-primary clonar-componentes" <?= count($componentesConnections) == 0 ? "disabled " : "" ?>><i class="fas fa-atom pr-2"></i>Componentes Connections </button>
                        </div>
                        <div class="col-md-2 text-right mb-2 mt-n2">
                            <button type="button" class="btn btn-success new-component" data-id="0" data-nombre="Nueva"><i class="fas fa-atom pr-2"></i>Nueva </button>
                        </div>
                    </div>
                    <section  class="overflow-auto content-estudio" >
                        <table id="table-components" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Componente</th>
                                    <th>Alias</th>
                                    <th class="text-center">Capturable</th>
                                    <th class="text-center">Imprimible</th>
                                    <th class="text-center">Tipo</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody id="table_components">
                                <?php
                                foreach ($componentes AS $row) {
                                    ?>
                                    <tr>
                                        <td><?= $row->componente ?></td>
                                        <td><?= $row->alias ?></td>
                                        <td align="center"><?= $row->capturable == "1" ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' ?></td>
                                        <td align="center"><?= $row->imprimible == "1" ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' ?></td>
                                        <td align="center"><?= $row->tipo_componente ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm rounded-circle mr-2 new-component" data-id="<?= $row->id ?>" data-nombre="<?= $row->componente ?>" data-toggle="tooltip" title="Editar"><i class="fas fa-edit"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm rounded-circle delete-component" data-id="<?= $row->id ?>" data-nombre="<?= $row->componente ?>" data-toggle="tooltip" title="Eliminar"><i class="fas fa-trash"></i></button>
                                        </td>

                                    </tr>

                                    <?php
                                }
                                ?>

                            </tbody>
                        </table>
                    </section>

                </div>

                <form class="needs-validation" action="controller/catalogos/Estudio?opc=formato-texto" method="post" novalidate="">
                    <input type="hidden" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input type="hidden" name="id_cat_estudio" value="<?= $estudio[0]->id ?>">

                    <div id="tipo-texto" class="row d-none">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="resultado_texto"></label>
                                <textarea  id="resultado_texto" class = "" name = "resultado_texto" >
                                    <?= $estudio[0]->formato ?>
                                </textarea>
                                <div class="invalid-feedback">
                                    Favor de capturar el Formato
                                </div>  
                            </div>
                        </div>

                        <div class="col-md-2 offset-md-5 pb-2">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                        </div>

                    </div>
                </form>
            </div><!-- modal-body -->

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-component" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-atom"></i> Componente <span class="text-primary" id="nombre_componente"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <section id="" class="content-componente" >
                    <form class="needs-validation-components" action="#" method="post" novalidate="">
                        <input type="hidden" id="id_componente"  name="id_componente" value="">
                        <input type="hidden" id="tipo_componente"  name="id_cat_componente" value="">
                        <div class="row">
                            <div class="col-md-12 text-center mb-4">
                                <button type="button" class="btn btn-success mr-4 valores-referencia"><i class="fas fa-list-ol pr-2"></i>Valores de referencia </button>
                                <button type="button" class="btn btn-default tabla-referencia"><i class="fas fa-table pr-2"></i>Tabla </button>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="componente">Componente</label>
                                    <input id="nombre_componet" type = "text" class = "form-control form-control-border" name = "componente" value="" placeholder = "Componente" required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Componente
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="alias">Alias</label>
                                    <input id="alias" type = "text" class = "form-control form-control-border text-uppercase" name = "alias" value="" placeholder = "Alias" required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Alias
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="total">Total Absoluto</label>
                                    <div class="form-check pt-2 pb-1">
                                        <input id="total_componet" class="form-check-input" name="absoluto" value="total" type="radio">
                                        <label class="form-check-label">Total Absoluto</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="absoluto">Absoluto</label>
                                    <div class="form-check pt-2 pb-1">
                                        <input id="absoluto_componet" class="form-check-input" name="absoluto" value="absoluto" type="radio">
                                        <label class="form-check-label">Absoluto</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="unidad">Unidad</label>
                                    <input id="unidad" type = "text" class = "form-control form-control-border" name = "unidad" value="" placeholder = "Unidad">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Unidad
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="capturable">Capturable</label>
                                    <div class="form-check pt-2 pb-1">
                                        <input id="capturable_componet" class="form-check-input" name="capturable" type="checkbox">
                                        <label class="form-check-label">Capturable</label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="imprimible">Imprimible</label>
                                    <div class="form-check pt-2 pb-1">
                                        <input id="imprimible_componet" class="form-check-input" name="imprimible" type="checkbox">
                                        <label class="form-check-label">Imprimible</label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="linea">Linea</label>
                                    <div class="form-check pt-2 pb-1">
                                        <input id="linea_componet"  class="form-check-input" name="linea" type="checkbox">
                                        <label class="form-check-label">Linea</label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                    <div class="form-check pt-2 pb-1">
                                        <input id="observaciones_componet"  class="form-check-input" name="observaciones" type="checkbox">
                                        <label class="form-check-label">Observaciones</label>
                                    </div>
                                </div>
                            </div>

                            <div class = "col-md-3">
                                <div class = "form-group">
                                    <label for = "id_cat_componente">Tipo de componente </label>
                                    <select id="id_cat_componente" class = "form-control select2" name = "id_cat_componente" style = "width: 100%;">
                                        <option value="">Componente Título </option>
                                        <?php
                                        foreach ($cat_componentes AS $row) {
                                            ?>
                                            <option value="<?= $row->id ?>">Componente <?= $row->tipo_componente ?></option>

                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="referencia">Referencia</label>
                                    <input id="referencia" type = "text" class = "form-control form-control-border" name = "referencia" value="" placeholder = "Referencia">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Referencia
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="leyenda">Leyenda</label>
                                    <!--input id="leyenda" type = "text" class = "form-control form-control-border text-uppercase" name = "leyenda" value="" placeholder = "Leyenda"-->
                                    <textarea  id="leyenda" class = "" name = "leyenda">
                                        
                                    </textarea>
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Leyenda
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-2 offset-md-5 pb-2">
                                <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                            </div>

                        </div>
                    </form>

                </section>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-tabla" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-table"></i> Tabla <span class="text-primary" id="componente_tabla"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <section id="" class="content-component-numerica" >
                    <form class="needs-validation-component-tabla" action="#" method="post" novalidate="">
                        <input type="hidden" class="d-none" id="id_componente_tabla" name="id" value="">
                        <div class="row">
                            <div class = "col-md-8">
                                <div class="row">                                                                     
                                    <div class = "col-md-12">
                                        <div class = "form-group">
                                            <label for = "sexo_tabla">Sexo </label>
                                            <select id="sexo_tabla" class = "form-control select2" name = "sexo" style = "width: 100%;" required="">
                                                <option value="General">General </option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                                <option value="Nino">Niño(a)</option>
                                                <option value="Embarazada">Embarazada</option>
                                                <option value="Fumador">Fumador</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <textarea id="component_tabla" class="" name="component_tabla" rows="15">
                                           
                                        </textarea>
                                    </div>

                                    <div class="col-md-4 offset-md-4 pt-1 pb-2">
                                        <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                                    </div>

                                </div><!-- .row-->
                            </div><!-- .col-md-8 -->

                            <div class="col-md-3 offset-md-1 pt-1">
                                <br>
                                <div class="col-md-12 text-right mb-1 p-0">
                                    <button type="button" class="btn btn-success nueva-tabla"><i class="fas fa-table pr-2"></i>Nuevo </button>
                                </div>
                                <section  class="overflow-auto content-referencia" >
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Sexo</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody id="table_components_tabla">

                                        </tbody>
                                    </table>
                                </section>
                            </div>
                        </div><!-- .row-->
                    </form>

                </section>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-component-formula" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-table"></i> Valores de referencia fórmula <span class="text-primary" id="componente_formula"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <section id="" class="content-component-numerica" >
                    <form class="needs-validation-component-formula" action="#" method="post" novalidate="">
                        <div class="row">
                            <div class="col-md-12 text-center mb-4">
                                <button type="button" class="btn btn-success mr-4 valores-referencia-numerico"><i class="fas fa-list-ol pr-2"></i>Valores de referencia númerico </button>
                                <button type="button" class="btn btn-default tabla-referencia"><i class="fas fa-table pr-2"></i>Tabla </button>
                            </div>

                            <div class="col-md-4 offset-md-2">
                                <div class="row">
                                    <div class="col-md-12 text-info font-weight-bold mb-2">Componentes númericos</div>
                                    <div class="col-md-12">
                                        <section  class="overflow-auto content-referencia" >
                                            <table class="table table-bordered table-hover">

                                                <tbody id="table_components_formula">

                                                </tbody>
                                            </table>
                                        </section>
                                    </div>
                                </div><!-- row -->
                            </div><!-- .col-6 -->


                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12 text-info font-weight-bold mb-2">Fórmula</div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="formula"></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text  font-weight-bold">=</span>
                                                </div>
                                                <input id="component_formula" type="text" class="form-control form-control-border text-primary"  name="formula" value="" placeholder="Fórmula" required="">
                                                <div class="invalid-feedback">
                                                    Favor de capturar el campo Fórmula
                                                </div>                               
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 offset-md-4 pt-1 pb-2">
                                        <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                                    </div>
                                </div><!-- .row -->
                            </div>
                        </div>
                    </form>

                </section>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-component-numerica" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-list-ol"></i> Valores de referencia númerico <span class="text-primary" id="componente_numerica"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <section id="" class="content-component-numerica" >
                    <form class="needs-validation-component-numerica" action="#" method="post" novalidate="">
                        <input type="hidden" class="d-none" id="id_componente_num" name="id" value="">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="row">
                                    <div class = "col-md-12">
                                        <div class = "form-group">
                                            <label for = "referencia_numerica">Referencia </label>
                                            <select id="referencia_numerica" class = "form-control custom-select tab-num" name = "referencia" style = "width: 100%;" required="">
                                                <option value="General">General </option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                                <option value="Nino">Niño(a)</option>
                                                <option value="Embarazada">Embarazada</option>
                                                <option value="Fumador">Fumador</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class = "col-md-12">
                                        <div class="row">
                                            <div class="col-md-12 text-info font-weight-bold mb-2">Edad</div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edad_inicio">De</label>
                                                    <input id="edad_inicio" type = "text" class = "form-control form-control-border tab-num" name = "edad_inicio" value="" placeholder = "De" required="">
                                                    <div class="invalid-feedback">
                                                        Favor de capturar el campo De
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edad_fin">A</label>
                                                    <input id="edad_fin" type = "text" class = "form-control form-control-border tab-num" name = "edad_fin" value="" placeholder = "A" required="">
                                                    <div class="invalid-feedback">
                                                        Favor de capturar el campo A
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class = "col-md-6">
                                                <div class = "form-group">
                                                    <label for = "tipo_edad">Tipo </label>
                                                    <select id="tipo_edad" class = "form-control custom-select tab-num" name = "tipo_edad" style = "width: 100%;" required="">
                                                        <option value="" selected="selected">Selecciona un Tipo</option>
                                                        <option value="Horas">Horas</option>
                                                        <option value="Dias">Días</option>
                                                        <option value="Meses">Meses</option>
                                                        <option value="Anios">Años</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Favor de seleccionar un Tipo
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- row -->
                                    </div><!-- .col-12 -->

                                    <div class = "col-md-12">
                                        <div class="row">
                                            <div class="col-md-12 text-info font-weight-bold mb-2">Valores</div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="valores_decimales">Decimales</label>
                                                    <input id="valores_decimales" type = "text" class = "form-control form-control-border tab-num" name = "valores_decimales" value="" placeholder = "Decimales">
                                                    <div class="invalid-feedback">
                                                        Favor de capturar el campo Decimales
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="valores_unidades">Unidades</label>
                                                    <input id="valores_unidades" type = "text" class = "form-control form-control-border tab-num" name = "valores_unidades" value="" placeholder = "Unidades">
                                                    <div class="invalid-feedback">
                                                        Favor de capturar el campo Unidades
                                                    </div>  
                                                </div>
                                            </div>
                                        </div><!-- row -->
                                    </div><!-- .col-12 -->

                                    <div class = "col-md-12">
                                        <div class="row">
                                            <div class="col-md-12 text-info font-weight-bold mb-2">Valores de Referencia</div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="alta_aceptable">Alta Aceptable</label>
                                                    <input id="alta_aceptable" type = "text" class = "form-control form-control-border tab-num" name = "alta_aceptable" value="" placeholder = "Alta Aceptable">
                                                    <div class="invalid-feedback">
                                                        Favor de capturar el campo Alta Aceptable
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="alta">Alta</label>
                                                    <input id="alta" type = "text" class = "form-control form-control-border tab-num" name = "alta" value="" placeholder = "Alta">
                                                    <div class="invalid-feedback">
                                                        Favor de capturar el campo Alta
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="baja">Baja</label>
                                                    <input id="baja" type = "text" class = "form-control form-control-border tab-num" name = "baja" value="" placeholder = "Baja">
                                                    <div class="invalid-feedback">
                                                        Favor de capturar el campo Baja
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="bajo_aceptable">Baja Aceptable</label>
                                                    <input id="bajo_aceptable" type = "text" class = "form-control form-control-border tab-num" name = "bajo_aceptable" value="" placeholder = "Baja Aceptable">
                                                    <div class="invalid-feedback">
                                                        Favor de capturar el campo Baja Aceptable
                                                    </div>  
                                                </div>
                                            </div>

                                            <div class="col-md-4 offset-md-4 pt-1 pb-2">
                                                <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                                            </div>
                                        </div><!-- row -->
                                    </div><!-- .col-12 -->

                                </div><!-- .row -->
                            </div><!-- .col-6 -->

                            <div class="col-md-6">
                                <div class="row">

                                    <div class="col-md-6 text-info font-weight-bold mb-2">Referencia</div>
                                    <div class="col-md-6 text-right mb-1">
                                        <button type="button" class="btn btn-success mr-4 nuevo-valor-numerico"><i class="fas fa-list-ol pr-2"></i>Nuevo </button>
                                    </div>
                                </div><!-- .row -->
                                <section  class="overflow-auto content-referencia" >
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Sexo</th>
                                                <th>Rango</th>
                                                <th class="text-center">Alto Aceptable</th>
                                                <th class="text-center">Alto</th>
                                                <th class="text-center">Bajo</th>
                                                <th class="text-center">Bajo Aceptable</th>
                                                <th></th>

                                            </tr>
                                        </thead>
                                        <tbody id="table_components_numerica">

                                        </tbody>
                                    </table>
                                </section>
                            </div>

                        </div>
                    </form>

                </section>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div class="modal fade" id="modal-component-lista" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xxl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-list-ol"></i> Valores de referencia lista <span class="text-primary" id="componente_lista"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <section id="" class="content-component-numerica" >
                    <form class="needs-validation-component-lista" action="#" method="post" novalidate="">
                        <div class="row">

                            <div class="col-md-5 offset-md-1">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="valor">Valor</label>
                                            <input type="text" class="form-control form-control-border"  name="valor" value="" placeholder="Valor" required="">
                                            <div class="invalid-feedback">
                                                Favor de capturar el campo Valor
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inactivo">Predeterminado</label>
                                            <div class="form-check">
                                                <input class="form-check-input" name="predeterminado" type="checkbox">
                                                <label class="form-check-label">Predeterminado</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 offset-md-4 pt-1 pb-2">
                                        <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                                    </div>
                                </div><!-- row -->
                            </div><!-- .col-6 -->


                            <div class="col-md-5">
                               <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="10%">Predeterminado</th>
                                                <th class="text-center">Valor</th>
                                                <th></th>
                                            </tr>
                                        </thead>

                                        <tbody id="table_components_lista">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>

                </section>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



<!-- Eliminar Componente -->
<div class="modal fade" id="modConfirmarDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Está seguro que desea eliminar el Componente?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar el estudio <span id="nombre" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form class="needs-delete-components" action="#" method="post" novalidate="">
                    <input type="hidden" class="d-none" id="_id_componente" name="id_componente" value="">
                    <button id="" class="btn btn-danger" >Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Eliminar Componente Númerico -->
<div class="modal fade" id="modConfirmarDeleteNum" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Está seguro que desea eliminar la Referencia?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar la referencia <span id="referencia_num" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form class="needs-delete-component-num" action="#" method="post" novalidate="">
                    <input type="hidden" class="d-none" id="_id_componente_num" name="id" value="">
                    <button id="" class="btn btn-danger" >Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Eliminar Componente Lista -->
<div class="modal fade" id="modConfirmarDeleteLista" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Está seguro que desea eliminar la Referencia?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar la referencia <span id="referencia_lista" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form class="needs-delete-component-lista" action="#" method="post" novalidate="">
                    <input type="hidden" class="d-none" id="id_componente_lista" name="id" value="">
                    <button id="" class="btn btn-danger" >Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Eliminar Componente Tabla -->
<div class="modal fade" id="modConfirmarDeleteTabla" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Está seguro que desea eliminar la tabla?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar la tabla <span id="componet_tabla" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form class="needs-delete-component-tabla" action="#" method="post" novalidate="">
                    <input type="hidden" class="d-none" id="_id_componente_tabla" name="id" value="">
                    <button id="" class="btn btn-danger" >Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<style>

    .table td, .table th {
        padding: 5px; 

    }

    .content-estudio{
        height: 70vh

    }

    .content-componente{
        height: 75vh

    }

    .content-component-numerica{
        height: 77vh
    }

    .modal-xxl {
        max-width: 95%;
    }

    .content-referencia{
        height: 65vh

    }

    .elemento-formula {
        cursor: pointer;
    }

    .note-editable{
        /* height: 50vh*/

    }
    
    .table-responsive{
        height: 400px;
    }

</style>
