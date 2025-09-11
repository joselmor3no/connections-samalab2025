<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-calendar-alt nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <!--div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="doctor" class="btn btn-block bg-gradient-success"><i class="fa fa-user-md pr-2"></i> Nuevo</a>
                </div-->
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Registro de agenda</h3>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class = "col-md-12">
                        <div class = "form-group row m-1">
                            <label class="col-md-2 col-form-label" for = "id_seccion">Secciones</label>
                            <div class="col-md-6"> 
                                <select id="id_seccion" name="id_seccion" class="form-control select2">    
                                    <?php
                                    foreach ($secciones AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>"><?= $row->seccion ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" >
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

        <div class="modal fade" id="cita" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Citas</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" action="#" method="POST" novalidate="">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="paciente">Paciente</label>
                                        <input id="paciente" type = "text" class = "form-control form-control-border" name = "paciente" value="" placeholder = "Paciente" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Paciente
                                        </div>  
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono</label>
                                        <input id="telefono" type = "text" class = "form-control form-control-border" name = "telefono" value="" placeholder = "Teléfono" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Teléfono
                                        </div>  
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="observaciones">Observaciones</label>
                                        <input id="observaciones" type = "text" class = "form-control form-control-border" name = "observaciones" value="" placeholder = "Observaciones">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Observaciones
                                        </div>  
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inicio">Inicio</label>
                                        <input id="inicio" type = "datetime-local" class = "form-control form-control-border" name = "inicio" value="" placeholder = "Inicio" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Inicio
                                        </div>  
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="final">Final</label>
                                        <input id="final" type = "datetime-local" class = "form-control form-control-border" name = "final" value="" placeholder = "Final" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Final
                                        </div>  
                                    </div>
                                </div>


                            </div>

                            <div class="col-md-4 offset-md-4 pb-2">
                                <button id="submitButton" type="submit" class="btn btn-block bg-gradient-primary event"><i
                                        class="fa fa-save pr-2"></i> Guardar</button>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="edit_cita" tabindex="-1" role="dialog" aria-labelledby="edit_cita" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Citas</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-4 offset-md-4">
                                <button  type="button" class="btn btn-block bg-gradient-danger delete-event">
                                    <i class="fa fa-trash pr-2"></i> Borrar
                                </button>
                            </div>

                            <div class="col-md-4">
                                <button  type="button" class="btn btn-block bg-gradient-warning cancelar-event">
                                    <i class="fa fa-times pr-2"></i> Cancelar
                                </button>
                                </di>
                            </div>

                            <form class="needs-validation-edit" action="#" method="POST" novalidate="">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="paciente_">Paciente</label>
                                            <input id="paciente_" type = "text" class = "form-control form-control-border" name = "paciente" value="" placeholder = "Paciente" required="">
                                            <div class="invalid-feedback">
                                                Favor de capturar el campo Paciente
                                            </div>  
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono</label>
                                            <input id="telefono_" type = "text" class = "form-control form-control-border" name = "telefono" value="" placeholder = "Teléfono" required="">
                                            <div class="invalid-feedback">
                                                Favor de capturar el campo Teléfono
                                            </div>  
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="observaciones">Observaciones</label>
                                            <input id="observaciones_" type = "text" class = "form-control form-control-border" name = "observaciones" value="" placeholder = "Observaciones">
                                            <div class="invalid-feedback">
                                                Favor de capturar el campo Observaciones
                                            </div>  
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inicio">Inicio</label>
                                            <input id="inicio_" type = "datetime-local" class = "form-control form-control-border" name = "inicio" value="" placeholder = "Inicio" required="">
                                            <div class="invalid-feedback">
                                                Favor de capturar el campo Inicio
                                            </div>  
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="final">Final</label>
                                            <input id="final_" type = "datetime-local" class = "form-control form-control-border" name = "final" value="" placeholder = "Final" required="">
                                            <div class="invalid-feedback">
                                                Favor de capturar el campo Final
                                            </div>  
                                        </div>
                                    </div>


                                </div>
                                <input type="hidden" id="id" name="id" value="">
                                <div class="col-md-4 offset-md-4 pb-2">
                                    <button id="submitButtonEdit" type="submit" class="btn btn-block bg-gradient-primary event"><i
                                            class="fa fa-save pr-2"></i> Guardar</button>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Eliminar Cita -->
        <div class="modal fade" id="modConfirmarDelete" tabindex="-1" role="dialog" aria-labelledby="modConfirmarDelete" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">¿Está seguro que desea eliminar la Cita?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Seleccione "Eliminar" para eliminar la cita del paciente <span id="nombre" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
                    </div>
                    <div class="modal-footer">
                        <button id="btn-delete-event" class="btn btn-danger" >Eliminar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Cancelar Cita -->
        <div class="modal fade" id="modConfirmarCancelacion" tabindex="-1" role="dialog" aria-labelledby="modConfirmarCancelacion" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">¿Está seguro que desea cancelar la Cita?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Seleccione "Cancelar" para cancelar la cita del paciente <span id="nombre2" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
                    </div>
                    <div class="modal-footer">
                        <button id="btn-cancelar-event" class="btn btn-danger" >Cancelar</button>
                    </div>
                </div>
            </div>
        </div>



    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

