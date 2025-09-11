<?php 
    if(isset($_REQUEST['m']) && $_REQUEST['m']!=''){
        $mes_busqueda=$_REQUEST['m'];
    }
    else{
        $mes_busqueda=Date('m'); 
    }

    $fechaInicialE = date('Y-m-d', strtotime('-1 month'));
    if(isset($_REQUEST['fechainicioE'])){
        $fechaInicialE=$_REQUEST['fechainicioE'];
    }
    $fechaFinalE = date('Y-m-d');
    if(isset($_REQUEST['fechafinalE'])){
        $fechaFinalE=$_REQUEST['fechafinalE'];
    } 
    
 ?>
<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">
<div class="content-wrapper">
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
        <h4><i class="fas fa-money-check-alt nav-icon pr-2"></i>Facturación Electrónica</h4>
        </div>
    </div>
        <!-- ./row -->
    <div class="row">
        <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1" style="background:#28a745!important;">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Pacientes (Facturación Individual)</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " id="custom-tabs-one-home-tab2" data-toggle="pill" href="#custom-tabs-one-home-2" role="tab" aria-controls="custom-tabs-one-home" aria-selected="false">Pacientes (Facturación Masiva)</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Empresas</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-facturadas-tab" data-toggle="pill" href="#custom-tabs-one-timbradas" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Facturas Tímbradas</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-cancelarSAT" data-toggle="pill" href="#custom-tabs-cancelar" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Cancelar Facturas SAT</a>
                  </li>
                </ul>
              </div>


              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
<!-- //========================Pacientes Individual -->
                  <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                    <form action="#" method="GET">
                        <div class="row my-5">
                            <div class = "col-md-3">
                                <div class = "form-group row m-0">
                                    <label class="col-md-3 col-form-label" for = "fechafin">Mes</label>
                                    <div class="col-md-9"> 
                                        <select class="form-control" name="m" id="mes_pacientes">
                                            <option value="1">Enero</option>
                                            <option value="2">Febrero</option>
                                            <option value="3">Marzo</option>
                                            <option value="4">Abril</option>
                                            <option value="5">Mayo</option>
                                            <option value="6">Junio</option>
                                            <option value="7">Julio</option>
                                            <option value="8">Agosto</option>
                                            <option value="9">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                        <?php 
                                        echo '<script>document.getElementById("mes_pacientes").value='.$mes_busqueda.'</script>';
                                         ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 ">
                                <button id="save" class="btn btn-block bg-gradient-success"><i class="fas fa-filter  pr-2"></i> Filtrar</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="" class="table table-bordered table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Orden</th>
                                        <th>Nombre</th>
                                        <th>Empresa</th>
                                        <th>Costo</th>
                                        <th>Datos</th>
                                        <th>Timbrar</th> 
                                    </tr>
                                </thead>
                                <tbody id="c_tabla_individuales">
                                    <?php $facturacionController->listaOrdenesIndividuales($mes_busqueda); ?>
                                </tbody>
                            </table>
                        </div> <!-- Acaba el Row General-->  
                    </div>
                </div>

<!-- //========================Pacientes Forma masiva -->
            <div class="tab-pane fade " id="custom-tabs-one-home-2" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab2">
                    <form action="#" method="GET">
                        <div class="row my-5">
                            <div class = "col-md-4">
                                <div class = "form-group row m-0">
                                    <label class="col-md-3 col-form-label" for = "fechafin">Mes</label>
                                    <div class="col-md-9"> 
                                        <select class="form-control" name="m" id="mes_pacientesm">
                                            <option value="1">Enero</option>
                                            <option value="2">Febrero</option>
                                            <option value="3">Marzo</option>
                                            <option value="4">Abril</option>
                                            <option value="5">Mayo</option>
                                            <option value="6">Junio</option>
                                            <option value="7">Julio</option>
                                            <option value="8">Agosto</option>
                                            <option value="9">Septiembre</option>
                                            <option value="10">Octubre</option>
                                            <option value="11">Noviembre</option>
                                            <option value="12">Diciembre</option>
                                        </select>
                                        <?php 
                                        echo '<script>document.getElementById("mes_pacientesm").value='.$mes_busqueda.'</script>';
                                         ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 ">
                                <button id="save" class="btn btn-block bg-gradient-success"><i class="fas fa-filter  pr-2"></i> Filtrar</button>
                            </div>

                            <div class="col-md-4 text-right">
                                
                            </div>
                            <div class="col-md-2 text-right">
                                <div id="btn_forma_masiva" class="btn btn-success pull-right">Datos de Facturación</div>
                            </div>
                        </div> 
                    </form>
                    <div class="row">
                        <div class="col-md-7">
                            <table id="" class="table table-bordered table-hover dataTable2">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Orden</th>
                                        <th>Nombre</th>
                                        <th>Empresa</th>
                                        <th>Costo</th>
                                        <th>Marcar</th>
                                    </tr>
                                </thead>
                                <tbody id="c_tabla_masiva">
                                    <?php $facturacionController->listaOrdenesMasiva($mes_busqueda); ?>
                                </tbody>
                            </table>
                        </div> 
                        <div class="col-md-5" style="background:#F6F7EE;border-radius:8px;border:solid 1px #AAA;height:60vh;">
                            <div class="row">
                                <div class="col-md-9"><b>Lista de ordenes:</b><span id="lista_consecutivos_span"></span></div>
                                <div class="col-md-3"><b>Total:</b><span id="total_consecutivos_span"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-12" style="height:50vh;overflow-y:scroll;">
                                    <table class="table" >
                                        <thead>
                                            <th>Estudio</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Subtotal</th>
                                        </thead>
                                        <tbody id="btabla_estudiosfm">
                                             
                                        </tbody>
                                    </table>

                                </div>
                            </div>   
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button id="btn_timbrar_masivo" class="btn btn-success" disabled>Timbrar</button>
                                </div>
                            </div>                    
                        </div>
                    </div>
                </div>
<!-- //========================Pacientes de empresas -->
                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                    <form action=""></form>
                    <div class="row my-5">

                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label for = "fechainicioE">Fecha Inicial</label>
                                <input id="fechainicioE" type = "date" class = "form-control form-control-border tab fechaEmpresas" name = "fechainicioE" value="<?php echo $fechaInicialE?>" required="">
                            </div>
                        </div>
                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label  for = "fechafinE">Fecha Final</label>
                                <input id="fechafinE" type = "date" class = "form-control form-control-border tab fechaEmpresas" name = "fechafinE" value="<?php echo $fechaFinalE?>" required="">
                            </div>
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group row m-1">
                                <label for = "empresa">Empresa</label>
                                    <select id="empresa" name="empresa" class="custom-select" required>
                                        <?php 
                                            $empresaController->listaEmpresasSelect($fechaInicialE,$fechaFinalE);
                                         ?>
                                    </select>
                            </div>
                        </div>

                        

                        <div class="col-md-2 ">
                            <br>
                            <button id="btn_lista_empresa" class="btn btn-block bg-gradient-success"><i class="fas fa-filter  pr-2"></i> Filtrar</button>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12 col-md-7">
                            <table id="tabla_ordenes_empresa" class="table table-bordered table-striped dataTable2">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Orden</th>
                                        <th>Empresa</th>
                                        <th>Paciente</th>
                                        <th>Fecha</th>
                                        <th>Monto</th>
                                        <th>Agregar</th>
                                    </tr>
                                </thead>
                                <tbody id="btabla_ordenes_empresa">

                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 col-md-5" style="background:#F6F7EE;border-radius:8px;border:solid 1px #AAA;height:60vh;">
                            <div class="row my-2">
                                <div class="col-12 col-md-12 text-right">
                                    <button id="btn_factura_empresa" class="btn btn-success pull-right">Datos Facturación Empresa</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9"><b>Lista de ordenes:</b><span id="lista_consecutivosE_span"></span></div>
                                <div class="col-md-3"><b>Total:</b><span id="total_consecutivosE_span"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-12" style="height:50vh;overflow-y:scroll;">
                                    <table class="table" >
                                        <thead>
                                            <th>Estudio</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Subtotal</th>
                                        </thead>
                                        <tbody id="btabla_estudiosEm">
                                            
                                        </tbody>
                                    </table>

                                </div>
                            </div>   
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button id="btn_timbrar_empresa" class="btn btn-success" disabled>Timbrar</button>
                                </div>
                            </div>                    
                        </div>

                    </div>
                </div>
<!-- //========================Facturas timbradas -->
            <div class="tab-pane fade" id="custom-tabs-one-timbradas" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                
                <form id="ft_form">
                    <div class="row my-5">
                        <div class = "col-md-1">
                            <div class = "form-group ">
                                <label for = "ft_folio">Folio:</label>
                                <input id="ft_folio" name = "ft_folio" type = "text" class = "form-control form-control-border tab">
                            </div>
                        </div>
                        <div class = "col-md-2">
                            <div class = "form-group ">
                                <label for = "ft_rfc">RFC:</label>
                                <input id="ft_rfc" name = "ft_rfc" type = "text" class = "form-control form-control-border tab">
                            </div>
                        </div>
                        <div class = "col-md-3">
                            <div class = "form-group ">
                                <label for = "ft_empresa">Empresa:</label>
                                <select name="ft_empresa" id="ft_empresa" class="form-control">
                                    <option value="">seleccione...</option>
                                    <?php 
                                            $empresaController->listaEmpresasSelect($fechaInicialE,$fechaFinalE);
                                         ?>
                                </select>
                            </div>
                        </div>
                        <div class = "col-md-2">
                            <div class = "form-group ">
                                <label for = "ft_anio">Año:</label>
                                <input id="ft_anio" name = "ft_anio" type = "number" class = "form-control form-control-border tab"  value="<?php echo Date('Y')?>" >
                            </div>
                        </div>
                        <div class = "col-md-2">
                            <div class = "form-group ">
                                <label for = "ft_mes">Mes:</label>
                                <select id="ft_mes" name = "ft_mes" class = "form-control">
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                                <?php echo '<script>document.getElementById("ft_mes").value='.Date("m").'</script>'; ?>
                        </div>
                    </div>
                        <div class="col-md-2 ">
                            <br>
                            <div id="save" class="btn btn-block bg-gradient-success btn_filtro_timbradas"><i class="fas fa-filter  pr-2"></i> Filtrar</div>
                        </div>

                    </div> 
                </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="example1" class="table table-bordered table-striped " style="width:100%;">
                                <thead>
                                    <tr>
                                        <th width="5%">Folio</th>
                                        <th width="20%">uuid</th>
                                        <th width="10%">Orden</th>
                                        <th width="15%">Fecha Certificación</th>
                                        <th width="10%">RFC</th>
                                        <th width="10%" >Empresa</th> 
                                        <th width="30%">Documentos</th>
                                    </tr>
                                </thead>
                                <tbody id="btabla_facturas_timbradas">
                                    <?php 
                                        $fecha= Date('Y-m');
                                        $facturacionController->listaFacturasTimbradas(null,null,null,$fecha); 
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
               
<!-- //========================Termina Facturas timbradas -->

<!-- //========================Facturas precanceladas -->
            <div class="tab-pane fade" id="custom-tabs-cancelar" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                <form action="facturacion?msg=fcancelado" method="POST">
                    <?php 
                        if(isset($_REQUEST['fechaCanceladas']))
                            $fecha_canceladas=$_REQUEST['fechaCanceladas'];
                        else
                            $fecha_canceladas=DATE('Y-m-d');
                    ?>
                    <div class="row my-5">
                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label for = "fechafin">Fecha de certificación:</label>
                                <input id="fechaCanceladas" type = "date" class = "form-control form-control-border tab" name = "fechaCanceladas" value="<?php echo $fecha_canceladas;?>" required="">
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <button id="save" class="btn btn-block bg-gradient-success"><i class="fas fa-filter  pr-2"></i> Filtrar</button>
                        </div> 
                    </div>
                </form>
                     
                    <div class="row">
                        <div class="col-12">
                            <table id="example1" class="table table-bordered table-striped dataTable">
                                <thead>
                                    <tr>
                                        <th>Folio</th>
                                        <th >UUID</th>
                                        <th>Ordenes</th>
                                        <th>Fecha Certificación</th>
                                        <th>RFC</th>
                                        <th style="width:200px;">Estatus Cancelación</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody id="btabla_facturas_canceladas">
                                    <?php 
                                        $facturacionController->listaFacturasPreCanceladas($fecha_canceladas); 
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
               
<!-- //========================Termina Facturas timbradas -->
            </div>
        </div> <!-- /.card body-->
              
    </div><!-- /.card primary-->
    </div><!-- /.col-12-->        
    </div><!-- /.row-->


</section>

</div>
<!-- /.content-wrapper

<!-- Modal Datos de Facturación por ordenes individuales -->
<div class="modal fade" id="modal_datosfp" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Datos de Facturación:<br><span id="span_df"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="controller/administracion/facturacion?opc=guarda_datos_factura_paciente" method="POST">
      <div class="modal-body">
            <input type="hidden" name="m_actualizar" id="m_actualizar" value="0">
            <input type="hidden" name="m_id_paciente" id="m_id_paciente">
            <input type="hidden" name="m" value="<?php echo $_REQUEST['m']?>">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">RFC</div>
                        <div class="col-5">
                            <input type="text" maxlength="13" name="m_rfc" id="m_rfc" class="form-control" required></div>
                        <div class="col-4">
                            <div class="btn btn-warning publico_general">Público General</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">Nombre Físcal</div>
                        <div class="col-9"><input type="text"  name="m_nombre_fiscal" id="m_nombre_fiscal" class="form-control" required></div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">Correo</div>
                        <div class="col-9"><input type="text"  name="m_correo" id="m_correo" class="form-control" ></div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">Dirección</div>
                        <div class="col-9"><input type="text"  name="m_direccion" id="m_direccion" class="form-control"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">Códgio Postal</div>
                        <div class="col-3"><input type="number"  name="m_codigo_postal" id="m_codigo_postal" class="form-control" value="0" ></div>
                    </div>
                </div>
            </div>
            
            <hr>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Condiciones de pago</div>
                        <div class="col-8">
                            <select name="m_condiciones_pago" id="m_condiciones_pago" class="form-control" required>
                                <option value="1">Factura de Contado</option>
                                <option value="2">Factura de Crédito</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Método de pago</div>
                        <div class="col-8">
                            <select name="m_metodo_pago" id="m_metodo_pago" class="form-control" required>
                                <option value="PUE">Pago en una sola exhibición </option>
                                <option value="PPD">Pago en parcialidades o diferido</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Forma de pago</div>
                        <div class="col-8">
                            <select name="m_forma_pago" id="m_forma_pago" class="form-control" required>
                                <?php echo $facturacionController->obtenerListaPago();?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Uso del CFDI</div>
                        <div class="col-8">
                            <select name="m_usocfdi" id="m_usocfdi" class="form-control" required>
                                <?php echo $facturacionController->obtenerUsoCFDI();?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">CFDI Relacionado</div>
                        <div class="col-8">
                            <input type="text" id="m_cfdir" name="m_cfdir" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Observaciones</div>
                        <div class="col-8">
                            <input type="text" id="m_observaciones" name="m_observaciones" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
      </form>
    </div>
  </div>
</div>



<!-- Modal Datos de Facturación de forma masiva -->
<div class="modal fade" id="modal_datos_forma_masiva" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Datos de Facturación para timbrado de varias ordenes:<br><span id="span_df"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_factura_masiva" action="#" >
      <div class="modal-body">
            <input type="hidden" name="fm_actualizar" id="fm_actualizar" value="0">
            <input type="hidden" name="m" value="<?php echo $_REQUEST['m']?>">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">RFC</div>
                        <div class="col-5">
                            <input type="text" maxlength="13" name="fm_rfc" id="fm_rfc" class="form-control" required></div>
                        <div class="col-4">
                            <div class="btn btn-warning publico_general">Público General</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">Nombre Físcal</div>
                        <div class="col-9"><input type="text"  name="fm_nombre_fiscal" id="fm_nombre_fiscal" class="form-control" required></div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">Correo</div>
                        <div class="col-9"><input type="text"  name="fm_correo" id="fm_correo" class="form-control" ></div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">Dirección</div>
                        <div class="col-9"><input type="text"  name="fm_direccion" id="fm_direccion" class="form-control"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">Códgio Postal</div>
                        <div class="col-3"><input type="number"  name="fm_codigo_postal" id="fm_codigo_postal" class="form-control" value="0" ></div>
                    </div>
                </div>
            </div>
            
            <hr>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Condiciones de pago</div>
                        <div class="col-8">
                            <select name="fm_condiciones_pago" id="fm_condiciones_pago" class="form-control" required>
                                <option value="1">Factura de Contado</option>
                                <option value="2">Factura de Crédito</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Método de pago</div>
                        <div class="col-8">
                            <select name="fm_metodo_pago" id="fm_metodo_pago" class="form-control" required>
                                <option value="PUE">Pago en una sola exhibición </option>
                                <option value="PPD">Pago en parcialidades o diferido</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Forma de pago</div>
                        <div class="col-8">
                            <select name="fm_forma_pago" id="fm_forma_pago" class="form-control" required>
                                <?php echo $facturacionController->obtenerListaPago();?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Uso del CFDI</div>
                        <div class="col-8">
                            <select name="fm_usocfdi" id="fm_usocfdi" class="form-control" required>
                                <?php echo $facturacionController->obtenerUsoCFDI();?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">CFDI Relacionado</div>
                        <div class="col-8">
                            <input type="text" id="fm_cfdir" name="fm_cfdir" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Observaciones</div>
                        <div class="col-8">
                            <input type="text" id="fm_observaciones" name="fm_observaciones" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Usar Descripción</div>
                        <div class="col-1">
                            <input type="checkbox" id="fm_udescripcion" name="fm_udescripcion" class="form-control" style="width:15px;">
                        </div>
                        <div class="col-7" style="color:red;">(No se visualiza la lista de ordenes en la Factura)</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Descripción</div>
                        <div class="col-8">
                            <input type="text" id="fm_ddescripcion" name="fm_ddescripcion" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button id="enviar_datos_fm" type="button" class="btn btn-primary">Actualizar datos</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Datos de Facturación Empresa -->
<div class="modal fade" id="modal_datos_empresa" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Datos de Facturación:<br><span id="span_dempresa"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_factura_empresa" action="#" >
      <div class="modal-body">
            <input type="hidden" name="empresa_id" id="empresa_id" value="0">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">RFC</div>
                        <div class="col-5">
                            <input type="text" maxlength="13" name="empresa_rfc" id="empresa_rfc" class="form-control" required></div>
                        <div class="col-4">
                            <div class="btn btn-warning publico_general">Público General</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">Nombre Físcal</div>
                        <div class="col-9"><input type="text"  name="empresa_nombre_fiscal" id="empresa_nombre_fiscal" class="form-control" required></div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">Correo</div>
                        <div class="col-9"><input type="text"  name="empresa_correo" id="empresa_correo" class="form-control" ></div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">Dirección</div>
                        <div class="col-9"><input type="text"  name="empresa_direccion" id="empresa_direccion" class="form-control"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">Códgio Postal</div>
                        <div class="col-3"><input type="text"  name="empresa_codigo_postal" id="empresa_codigo_postal" class="form-control" value="0" ></div>
                    </div>
                </div>
            </div>
            
            <hr>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Condiciones de pago</div>
                        <div class="col-8">
                            <select name="empresa_condiciones_pago" id="empresa_condiciones_pago" class="form-control" required>
                                <option value="1">Factura de Contado</option>
                                <option value="2">Factura de Crédito</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Método de pago</div>
                        <div class="col-8">
                            <select name="empresa_metodo_pago" id="empresa_metodo_pago" class="form-control" required>
                                <option value="PUE">Pago en una sola exhibición </option>
                                <option value="PPD">Pago en parcialidades o diferido</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Forma de pago</div>
                        <div class="col-8">
                            <select name="empresa_forma_pago" id="empresa_forma_pago" class="form-control" required>
                                <?php echo $facturacionController->obtenerListaPago();?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Uso del CFDI</div>
                        <div class="col-8">
                            <select name="empresa_usocfdi" id="empresa_usocfdi" class="form-control" required>
                                <?php echo $facturacionController->obtenerUsoCFDI();?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">CFDI Relacionado</div>
                        <div class="col-8">
                            <input type="text" id="empresa_cfdir" name="empresa_cfdir" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Observaciones</div>
                        <div class="col-8">
                            <input type="text" id="empresa_observaciones" name="empresa_observaciones" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Usar Descripción</div>
                        <div class="col-1">
                            <input type="checkbox" id="empresa_udescripcion" name="empresa_udescripcion" class="form-control" style="width:15px;">
                        </div>
                        <div class="col-7" style="color:red;">(No se visualiza la lista de ordenes en la Factura)</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-4">Descripción</div>
                        <div class="col-8">
                            <input type="text" id="empresa_ddescripcion" name="empresa_ddescripcion" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button id="enviar_datos_empresa" type="button" class="btn btn-primary">Actualizar datos</button>
      </div>
      </form>
    </div>
  </div>
</div>



<!-- Modal Datos de Facturación Empresa -->
<div class="modal fade" id="modal_motivo_cancelacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Datos de Cancelación:<br><span id="span_dempresa"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
            <input type="hidden" name="empresa_id" id="empresa_id" value="0">
            <div class="row">
                <div class="col-12">
                    <label for="">ID CFDI</label>
                    <input id="idCfdiC" type="text" class="form-control" readonly  >
                    <input type="hidden" id="id_cfdi_tabla">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label for="motivo">Motivo de cancelación</label>
                    <select name="motivoC" id="motivoC" class="form-control">
                        <option value="01">01-Comprobantes emitidos con errores con relación.</option>
                        <option value="02">02-Comprobantes emitidos con errores sin relación.</option>
                        <option value="03">03-No se llevó a cabo la operación.</option>
                        <option value="04">04-Operación nominativa relacionada en una factura global.</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <label for="motivo">UUID QUE SUSTITUYE AL CANCELADO</label>
                    <input type="text" id="uuidC" name="uuidC" class="form-control">
                </div>
            </div> 
            <div class="row">
                <div id="div_respuesta" class="col-12 text-center" style="color:green;">
                    
                </div>
            </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button id="cancelar_Sat" type="button" class="btn btn-warning">CANCELAR SAT</button>
      </div>

    </div>
  </div>
</div>