<?php
error_reporting(E_ALL);
?>

<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">

                <div class="col-md-12">
                    <div class="row">

                        <div class="col-md-2 mb-2">
                            <h3 class="card-title"> Reporte de Resultados</h3>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button id="imprimir-reporte" type="" class="btn btn-block bg-gradient-primary"><i class="fa fa-file-pdf pr-2"></i>Imprimir</button>
                        </div>

                        <div class="col-md-2 mb-2">
                            <button type="" class="btn btn-block bg-gradient-secondary mail"><i class="fa fa-envelope pr-2"></i>Correo</button>
                        </div>

                        <div class="col-md-3 mb-2">
                            <button type="" class="btn btn-block bg-gradient-success whatsapp"><i class="fab fa-whatsapp pr-2"></i>Whatsapp</button>
                        </div>
                        <div class="col-md-2 text-right mb-2">

                            <a href="reporte-laboratorio" class="btn btn-primary rounded-circle" title="Buscar Pacientes" data-toggle="tooltip" >
                                <i class="fa fa-search"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="codigo">Código</label>
                            <div id="paciente_codigo" class="border-bottom w-100 "></div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="epediente">Expediente</label>
                            <div id="paciente_expediente" class="border-bottom w-100 "></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="paciente">Paciente</label>
                            <div id="paciente_nombre" class="border-bottom w-100 "></div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <div id="paciente_fecha"  class="border-bottom w-100 "></div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sexo">Sexo</label>
                            <div id="paciente_sexo"  class="border-bottom w-100 text-uppercase"></div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="edad">Edad</label>
                            <div id="paciente_edad"  class="border-bottom w-100 text-uppercase"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="medico">Médico</label>
                            <div  id="paciente_medico"  class="border-bottom w-100 "></div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="observaciones">Observaciones de paciente</label>
                            <div  id="paciente_observaciones"  class="border-bottom w-100 "></div>
                        </div>
                    </div>
                    
                     <!--div class="col-md-2">
                        <div class="custom-control custom-checkbox">
                            <input id="ingles" type="checkbox" <?= $paciente[0]->ingles == 1 ? "checked" : "" ?> class="custom-control-input">
                            <label class="custom-control-label" for="ingles">Inglés</label>
                        </div>
                    </div-->

                    <div class="col-md-6 pt-2 mb-2" style="overflow:auto;max-height:180px" >
                        <!--div class="col-md-4">
                                <button type="submit" class="btn btn-block bg-gradient-info"><i
                                        class="fa fa-save pr-2"></i>Guardar Orden</button>
                            </div-->
                        <table id="table-estudios"
                               class="table table-bordered table-estudios">
                            <thead>
                                <tr>
                                    <th class="text-center">Pacientes </th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($estudios AS $estudio) {
                                    ?>
                                    <tr id="index_<?= $estudio->id ?>" class="bg-estudios">
                                        <td>

                                            <input id="imprimir_<?= $estudio->id ?>" disabled type="checkbox" name="imprimir[]" value="<?= $estudio->id ?>" class="imprimir d-none">
                                            <input id="pagina_<?= $estudio->id ?>" disabled type="checkbox" name="pagina[]" value="<?= $estudio->id ?>"  class="pagina d-none">

                                            <button id="btn-estudio-<?= $estudio->id ?>" data-id="<?= $estudio->id ?>" data-id_orden="<?= $estudio->id_orden ?>" data-estudio="<?= $estudio->estudio ?>" data-impresion="<?= $estudio->impresion ?>" data-reportado="<?= $estudio->reportado ?>" 
                                                    class="<?= $estudio->impresion > 0 ? "text-success" : ( $estudio->reportado > 0 ? "text-primary" : "text-black" ) ?> btn btn-link pt-0 pb-0 estudio"  data-tipo="<?= $estudio->resultado_componente == 1 ? "componente" : "texto" ?>"> 
                                                        <?= $estudio->paciente ?>
                                            </button>
                                        </td>
                                        <td class="text-center p-1">
                                            <button type="button" data-id="<?= $estudio->id ?>"  data-estudio="<?= $estudio->paciente ?>" class="btn btn-sm btn-primary rounded-circle load-activity "  title="Bitácora" data-toggle="tooltip" >
                                                <i class="fas fa-clipboard-list"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <?php
                                }
                                ?>

                            </tbody>
                        </table>

                    </div>


                    <div class="col-md-6">

                        <div class="row">

                            <div class="col-md-4 mb-2"> <button id="restablecer" type="button" class="btn btn-block bg-gradient-danger"><i
                                        class="fa fa-history"></i> Reestablecer</button>
                            </div> 

                            <div class="form-group col-md-6">
                                <label for="reestablecio">Reestableció</label>
                                <div class="border-bottom w-100 "> &nbsp; <span id="restablecio"></span></div>
                            </div>
                        </div> <!-- .row -->
                        <form id="load-pdf" class="needs-validation-pdf" action="#" method="post" novalidate="" enctype="multipart/form-data">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <!--label for="exampleInputFile">Subir PDF</label-->
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="exampleInputFile" name="file" accept=".pdf" required="">
                                                <label class="custom-file-label" for="exampleInputFile">Seleccionar archivo PDF</label>
                                                <div class="invalid-feedback">
                                                    Favor de seleccionar archivo PDF
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div id="pdf_resultado" class="col-md-3 d-none">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-block bg-gradient-dark"><i class="fas fa-file-pdf"></i> Guardar</button>
                                </div>


                            </div> <!-- .row -->
                        </form>

                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="observaciones_resultado">Observaciones de estudio</label>
                                    <input id="observaciones" type="text" class="form-control form-control-border text-uppercase" autocomplete="off" name="observaciones" placeholder="Observaciones">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="custom-control custom-checkbox">
                                    <input id="ingles" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="ingles">Inglés</label>
                                </div>
                            </div>

                        </div> <!-- .row -->

                    </div><!-- col-6 -->

                </div>
                <!-- /.row -->

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 id="estudio" class="card-title text-center w-100 font-weight-bold"> </h3>
            </div>

            <div class="card-body">
                <form id="reporte-estudios" class="needs-validation" action="controller/laboratorio/Reporte?opc=save-resultados-lab" method="post" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input type="hidden" id="fecha_ini" name="ini" value="<?= $fecha_inicial ?>">
                    <input type="hidden" id="fecha_fin" name="fin" value="<?= $fecha_final ?>">
                    <input type="hidden" id="no_estudio" name="estudio" value="<?= $no_estudio ?>">
                    <input type="hidden" id="codigo" name="codigo" value="">
                    <input type="hidden" id="id_orden" name="id_orden" value="">
                    <input type="hidden" id="id_estudio" name="id_estudio" value="">
                    <input type="hidden" id="id_paciente" name="id_paciente" value="">
                    <input type="hidden" id="hide_edad" name="hide_edad" value="">
                    <input type="hidden" id="hide_tipo_edad" name="hide_tipo_edad" value="">
                    <input type="hidden" id="hide_sexo" name="hide_sexo" value="">
                    <input type="hidden" id="hide_observaciones" name="hide_observaciones" value="">
                    <input type="hidden" id="hide_tipo" name="hide_tipo" value="">

                    <div id="tipo-componente"  class="col-md-8 offset-md-2 d-none">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Resultado</th>
                                    <th class="text-center">BAJA</th>
                                    <th class="text-center">ALTA</th>
                                    <th class="text-center">Referencia/Unidad</th>
                                </tr>
                            </thead>
                            <tbody id="table-componentes">

                            </tbody>
                        </table>

                        <div id="table-imprimir">

                        </div>

                    </div>

                    <div id="tipo-texto" class="col-md-12 d-none" >
                        <div class="form-group">

                            <textarea  id="reporte-texto" class = "" name = "reporte-texto">
                                        
                            </textarea>

                        </div>
                    </div>

                    <div class="col-md-4 offset-md-4 ">
                        <button id="save" type="submit" class="btn btn-block bg-gradient-primary " disabled>
                            <i class="fa fa-save pr-2"></i>
                            Guardar/Validar</button>
                    </div>

                </form>
            </div>
        </div>
        <!-- /.card -->

    </section>

    <div class="modal fade" id="modalBitocara" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5  class="modal-title">Bitácora <span id="bitacora" class="text-primary"></span></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <table id="tableBitacora" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Actividad</th>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody id="table-bitacora"></tbody>
                        </table>

                    </div>


                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>


<!-- /.content-wrapper -->
<style type="text/css">

    .table-responsive {
        height: 300px;
        overflow: scroll;
    }

    .form-control {
        height: 1.25rem;
        padding: 0px;
    }

    .table td,
    .table th {
        padding: 0px;
        font-size: 0.95rem;

    }

    .custom-control {
        min-height: 0.25rem; 
    }

    label {
        margin-bottom: 0px;
    }

    .form-group {
        margin-bottom: 0.25rem;
    }

    .text-black {
        color: #343a40 !important;

    }

    .text-danger {
        color: #dc3545!important;
    }

    .text-primary {
        color: #007bff!important;
    }
</style>