<?php
$administracionController->guardaPagoEC();
$fechaInicialG="2022-01-01";
$fechaFinalG=Date("Y-m-d");
?> 

<!-- Content Wrapper. Contains page content -->
<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">


        <div class="row">
            <div class="col-12">
                <h4><i class="fas fa-money-check-alt nav-icon pr-2"></i>Aplicación de Pagos ( Empresas de Credito )</h4>
            </div>
        </div>
        <!-- ./row -->
        <div class="row">
          <div class="col-12 col-sm-12">
            <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1" style="background:#28a745!important;">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Aplicacón</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Pagos</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-lista-tab" data-toggle="pill" href="#custom-tabs-lista" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Cuentas por cobrar</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                    
                  
              <form action="#" method="POST">
                  <div class="row">
                          <div class = "col-md-2">
                               <div class = "form-group ">
                                    <label  for = "empresa">Fecha Inicial</label>
                                    <input type="date" class="form-control" id="fecha_inicial" name="" value="<?php echo Date("Y-m-d")?>">
                              </div>
                          </div>

                          <div class = "col-md-2">
                               <div class = "form-group">
                                        <label for = "empresa">Fecha Final</label>
                                    <input type="date" class="form-control" id="fecha_final" name="" value="<?php echo Date("Y-m-d")?>">
                              </div>
                          </div>

                          <div class = "col-md-3">
                               <div class = "form-group">
                                <label for = "empresa">Empresa</label>
                                    <select id="empresa" name="empresa" class="custom-select" required="">
                                        <?php echo $administracionController->listaEmpresas();?>
                                    </select>
                                    <input type="hidden" id="lista_ordenes" name="lista_ordenes">
                                    <input type="hidden" id="lista_descuentos" name="lista_descuentos">
                                    <input type="hidden" id="lista_importes" name="lista_importes">
                              </div>
                          </div>

                          <div class = "col-md-2">
                            <div class = "form-group">
                                <label for = "fpago">Forma</label>
                                    <select id="fpago" name="tipo_pago" class="custom-select" required="">
                                        <?php echo $administracionController->obtenerListaPagoM();?>
                                    </select>
                                </div>
                            </div>
                            <div class = "col-md-3">
                                <div class = "form-group">
                                    <label for = "referencia">Referencia</label>
                                    <input id="referencia" type = "text" class = "form-control form-control-border text-uppercase" name="numero_referencia" value="">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class = "col-md-2">
                                <div class = "form-group">
                                    <label  for = "monto">Monto del pago</label>
                                     <input id="monto" type = "number" step="0.1" class = "form-control form-control-border text-uppercase" name="monto" value="0" >

                                </div>
                            </div>

                            <div class = "col-md-2">
                                <div class = "form-group">
                                    <label  for = "totalordenes">Total en Ordenes</label>
                                     <input id="totalordenes" type = "text" class = "form-control form-control-border text-uppercase" name="total_pagado" value="0" readonly>

                                </div>
                            </div>

                            <div class = "col-md-2">
                                <div class = "form-group">
                                    <label  for = "saldo">Saldo $</label>
                                    <input id="saldo" type = "text" class = "form-control form-control-border text-uppercase" name="saldo_anterior" value="0" readonly>

                                </div>
                            </div>

                            <div class = "col-md-2 text-center">
                                <div class = "form-group">
                                    <br>
                                    <div class="custom-control custom-checkbox" style="margin-top:8px;">
                                        <input id="usarsaldo" type = "checkbox" class = "custom-control-input" name="uso_saldo">
                                        <label class="custom-control-label" for="usarsaldo">Usar Saldo</label>
                                    </div>  
                                </div>
                            </div>

                            <div class = "col-md-2">
                                <div class = "form-group ">
                                    <label  for = "saldofinl">Saldo Final $</label>
                                     <input id="saldoFinal" type = "text" class = "form-control form-control-border text-uppercase" name="saldo_final" value="0" readonly>

                                </div>
                            </div>

                            <div class = "col-md-2">
                                <div class = "form-group ">
                                    <label  for = "observaciones">Fecha de pago</label>
                                    <input id="fecha_pago" type = "date" class = "form-control form-control-border text-uppercase" name="fecha_pago" value="<?php echo Date("Y-m-d");?>">

                                </div>
                            </div>

                            <div class = "col-md-6">
                                <div class = "form-group ">
                                    <label  for = "observaciones">Observaciones</label>
                                    <input id="observaciones" type = "text" class = "form-control form-control-border text-uppercase" name="observaciones" >

                                </div>
                            </div>

                            <div class = "col-md-3">
                                <div class = "form-group ">
                                    <label  for = "montoOrdenes">Monto total adeudo $</label>
                                     <input id="adeudoTotal" type = "text" class = "form-control form-control-border text-uppercase" value="0" readonly>

                                </div>
                            </div>

                        </div> <!-- Acaba el Row General-->  


                                    <div class="row my-5">
                                        <div class="col-md-12">
                                            <table id="ordenes_pago" class="table table-bordered table-hover dataTable">
                                                <thead>
                                                    <tr style="text-align:center">
                                                        <th># Orden</th>
                                                        <th>Paciente</th>
                                                        <th>Fecha</th>
                                                        <th >Precio</th>
                                                        <th >Descuento</th>
                                                        <th>Total</th>
                                                        <th >Estudios</th>
                                                        <th>Aplicar &nbsp;<input type="checkbox" id="checkall"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tabla_ordenes"> 

                                                </tbody>
                                            </table>

                                            
                                            <div class="col-md-5 offset-md-3 ">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <button type="button" id="reporte" class="btn btn-block bg-gradient-success"><i class="fa fa-print  pr-2"></i> Imprimir Reporte</button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button id="save" class="btn btn-block bg-gradient-success"><i class="far fa-save  pr-2"></i> Guardar Pago</button>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div> <!--Acaba col de la tabla-->

                                    </div> <!--Acaba row de la tabla-->
                                </form>

                                <!--Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin malesuada lacus ullamcorper dui molestie, sit amet congue quam finibus. Etiam ultricies nunc non magna feugiat commodo. Etiam odio magna, mollis auctor felis vitae, ullamcorper ornare ligula. Proin pellentesque tincidunt nisi, vitae ullamcorper felis aliquam id. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin id orci eu lectus blandit suscipit. Phasellus porta, ante et varius ornare, sem enim sollicitudin eros, at commodo leo est vitae lacus. Etiam ut porta sem. Proin porttitor porta nisl, id tempor risus rhoncus quis. In in quam a nibh cursus pulvinar non consequat neque. Mauris lacus elit, condimentum ac condimentum at, semper vitae lectus. Cras lacinia erat eget sapien porta consectetur.-->
                            </div>

                            <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">

                                <div class="row my-5">
                                    <div class = "col-md-4">
                                        <div class = "form-group ">
                                            <label  for = "empresa">Empresa</label>
                                            
                                                <select id="empresa_lista"  name="empresa" class="custom-select" required="">
                                                    <?php echo $administracionController->listaEmpresas(); ?>
                                                </select>

                                           
                                        </div>
                                    </div>

                                    <div class = "col-md-3">
                                        <div class = "form-group ">
                                            <label  for = "fechainicio">F. Inicial</label>
                                            <input id="fecha_inicio" type = "date" class = "form-control form-control-border tab" name = "fecha_nac" value="<?php echo Date("Y-m-d") ?>" required="">
                                        </div>
                                    </div>
                                    <div class = "col-md-3">
                                        <div class = "form-group ">
                                            <label  for = "fechafin">F. Final</label>
                                           <input id="fecha_fin" type = "date" class = "form-control form-control-border tab" name = "fecha_nac" value="<?php echo Date("Y-m-d") ?>" required="">
                                        </div>
                                    </div>

                                    <div class="col-md-2 ">
                                        <button  class="btn btn-block bg-gradient-success" id="btn_filtrar"><i class="fas fa-filter  pr-2" ></i> Filtrar</button>
                                    </div>

                                </div>



                                <div class="col-md-12">
                                    <table id="tabla_lista_pagos" class="table table-bordered table-striped dataTable">
                                        <thead>
                                            <tr>
                                                <th>Empresa</th>
                                                <th>Tipo Pago</th>
                                                <th>Referencia</th>
                                                <th>Fecha</th>
                                                <th>Importe</th>
                                                <th>Descuento</th>
                                                <th>Total Pagado</th>
                                                <th>Saldo a favor</th>
                                                <th>ordenes</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cuerpoTablal"> 



                                        </tbody>
                                    </table>
                                </div>  <!--se acaba el col de la tabla -->

                            </div>


                            <div class="tab-pane fade" id="custom-tabs-lista" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">


                                <div class="row">
                                  <div class = "col-md-2">
                                       <div class = "form-group ">
                                            <label  for = "fechaInicialG">Fecha Inicial</label>
                                            <input type="date" class="form-control" id="fechaInicialG" name="fechaInicialG" value="<?php echo $fechaInicialG ?>">
                                      </div>
                                  </div>

                                  <div class = "col-md-2">
                                       <div class = "form-group">
                                                <label for = "fechaFinalG">Fecha Final</label>
                                            <input type="date" class="form-control" id="fechaFinalG" name="fechaFinalG" value="<?php echo $fechaFinalG ?>">
                                      </div>
                                  </div>

                                  <div class="col-md-2 ">
                                    <div class="form-group">    
                                        <label for="">&nbsp;</label>
                                        <button  class="btn btn-block bg-gradient-success" id="btn_filtrar_general"><i class="fas fa-filter  pr-2" ></i> Filtrar</button>
                                    </div>  
                                  </div>


                                <div class="col-md-12">
                                    <table id="tabla_lista_pagos_g" class="table table-bordered table-striped dataTable">
                                        <thead>
                                            <tr>
                                                <th>Empresa</th>
                                                <th>Monto Deuda</th>
                                                <th>Monto Descuento</th>
                                                <th>Total a pagar</th>
                                                <th>Ordenes con deuda</th>   
                                                <th>Ver Detalle</th>         
                                            </tr>
                                        </thead>
                                        <tbody id="cuerpoTablaEmpresasT">
                                            <?php 
                                                
                                            $administracionController->listaOrdenesEmpresa($fechaInicialG,$fechaFinalG); ?> 
                                        </tbody>
                                    </table>
                                </div>  <!--se acaba el col de la tabla -->

                            </div>

                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

        </div>


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal fade" id="lista-estudios" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Lista de estudios:<br> <span id="paciente_modal"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- //======================= MODAL PARA LA LISTA DE ESTUDIOS EN GENERAL ===================== -->
<div class="modal fade" id="modal_desglose_orden" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Desglose de ordenes de la empresa:<br> <span id="nombre_empresa_modal"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table id="tb_desglosepe" class="table table-striped">
                            <thead>
                                <th>Orden</th>
                                <th>Paciente</th>
                                <th>Fecha Registro</th>
                                <th>Deuda por clase</th> 
                            </thead>
                            <tbody id="tbb_desglosepe">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>