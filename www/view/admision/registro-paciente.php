<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="padding: 1px!important;">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-1 col-md-1">
                </div>
                <div class="col-md-3 col-md-3">
                    <a href="caja" class="btn btn-block btn-outline-info"><i class="far fa-credit-card  pr-2"></i> Pagos</a>
                </div>
                <div class="col-md-3 col-md-3">
                    <a href="agenda" class="btn btn-block btn-outline-warning"><i class="far fa-calendar  pr-2"></i> Agenda</a>
                </div>
                <div class="col-md-3 col-md-3">
                    <a href="etiquetas-estudios" class="btn btn-block btn-outline-primary"><i class="far fa-bookmark  pr-2"></i> Etiquetas</a>
                </div>
            </div>
        </div><!-- /.container-fluid --> 
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <!--  <div class="card-header">  </div>-->

            <div class="card-body">

                <form class="needs-validation" action="controller/admision/Paciente?opc=registro" method="post" novalidate>
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input type="hidden" id="id_ticket" name="id_ticket" value="<?= $sucursal->impresion ?>">
                    <input type="hidden" id="id_orden" name="id_orden" value="">
                    <input type="hidden" id="id_paciente" name="id_paciente" value="">
                    <div class="row">

                        <div class = "col-md-12">
                            <div id="folio" class="text-gray-800 pl-2 h4 text-center"></div>
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group row m-0">
                                <label class="col-md-5 col-form-label" for = "paterno">Apellido Paterno</label>
                                <div class="col-md-7"> <input id="paterno" type = "text" class = "form-control form-control-border <?= $_SESSION["ruta"] != "alcala" ? "text-uppercase" : "" ?> tab" name = "paterno" value="" autocomplete="off" required=""></div>

                            </div>
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group row m-0">
                                <label class="col-md-5 col-form-label" for = "materno">Apellido Materno</label>
                                <div class="col-md-7"> <input id="materno"  type = "text" class = "form-control form-control-border <?= $_SESSION["ruta"] != "alcala" ? "text-uppercase" : "" ?> tab" name = "materno" value="" readonly></div>
                            </div>
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group row m-1">
                                <label class="col-md-4 col-form-label" for = "nombre">Nombre (s)</label>
                                <div class="col-md-6"> 
                                    <input  id="nombre"  type = "text" class = "form-control form-control-border <?= $_SESSION["ruta"] != "alcala" ? "text-uppercase" : "" ?> tab" name = "nombre" value=""  readonly>
                                </div>

                                <div class="col-md-2"> 
                                    <button type="button" class="btn btn-primary rounded-circle load-ordenes"  title="Buscar Pacientes" data-toggle="modal" data-target="#modalPacientes">
                                        <i class="fas fa-users"></i> 
                                    </button>

                                </div>
                            </div>
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group row m-0">
                                <label class="col-md-5 col-form-label" for = "fecha_nac">Fecha Nacimiento</label>
                                <div class="col-md-7"> <input id="fecha_nac" type = "date" class = "form-control form-control-border tab" name = "fecha_nac" value="" required=""></div>
                            </div>
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group row m-0">
                                <label class="col-md-2 col-form-label" for = "edad">Edad</label>
                                <div class="col-md-3"> <input id="edad" type = "texto" class = "form-control form-control-border tab" name = "edad" value=""  required=""></div>

                                <div class="col-md-7">
                                    <select  id="tipo_edad" name="tipo_edad" class="custom-select tab">
                                        <option value="Anios">Años</option>
                                        <option value="Meses">Meses</option>
                                        <option value="Dias">Dias</option>
                                        <option value="Horas">Horas</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group row m-1">
                                <label class="col-md-4 col-form-label" for = "sexo">Sexo</label>
                                <div class="col-md-8"> 
                                    <select id="sexo" name="sexo" class="custom-select tab" required="">
                                        <option value="Masculino">Hombre</option>
                                        <option value="Femenino">Mujer</option>
                                        <option value="Nino">Niño (a)</option>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group row m-0">
                                <label class="col-md-3 col-form-label" for = "direccion">Dirección</label>
                                <div class="col-md-9"> <input id="direccion" type = "text" class = "form-control form-control-border text-uppercase tab" name = "direccion" value=""></div>

                            </div>
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group row m-0">
                                <label class="col-md-3 col-form-label" for = "cpEmail">Email</label>
                                <div class="col-md-9"> <input id="email" type = "text" class = "form-control form-control-border text-uppercase tab" name = "cpEmail" value="" ></div>

                            </div>
                        </div>

                        <div class = "col-md-4 ">
                            <div class = "form-group row m-0">
                                <label class="col-md-4 col-form-label" for = "tel">Teléfono</label>
                                <div class="col-md-8"> <input id="tel" type = "text" class = "form-control form-control-border tab" name = "tel" value="" ></div>

                            </div>
                        </div>

                        <div class = "col-md-5">
                            <div class="row">
                                <div class = "col-md-12">
                                    <div class = "form-group row m-0">
                                        <label class="col-md-2 col-form-label" for = "medico">Médico</label>
                                        <div class="col-md-7 mb-2"> 
                                            <input type="text" id="busca-doctor" list="opcionesDoctores" class="form-control" placeholder="buscar" name="medico" required>
                                            <datalist id="opcionesDoctores">
                  
                                            </datalist>
                                            <input id="id_doctor" type = "hidden" name = "id_doctor" >
                                        </div>
                                        <div class="col-md-2 mb-2"> 
                                            <input id="dalias" type = "text" disabled class="form-control" >
                                        </div>
                                        <div class="col-md-1"> 
                                            <button type="button" class="btn btn-sm btn-primary rounded-circle clear-medico"  title="Limpiar" data-toggle="tooltip" data-target="#modalPacientes">
                                                <i class="fas fa-redo-alt"></i>
                                            </button>
                                        </div>
                                    </div> 
                                </div>

                                <div class = "col-md-12">
                                    <div class = "form-group row m-0">
                                        <label class="col-md-2 col-form-label" for = "empresa">Empresa</label>
                                        <div class="col-md-10 mb-2"> 
                                            <select id="id_empresa" class="form-control select2 select-registro tab" name="id_empresa" style="width: 100%;">
                                                <option value="">Selecciona una Empresa</option>
                                                <?php
                                                foreach ($lista_empresas AS $row) {
                                                    ?>
                                                    <option value="<?= $row->id ?>"><?= $row->nombre ?></option>

                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div> 
                                </div>
                                <div class = "col-md-12">
                                    <div class = "form-group row m-0">
                                        <label class="col-md-4 col-form-label" for = "fechaen">Fecha de Entrega</label>
                                        <div class="col-md-8 font-weight-bold"> 
                                            <div  id="fecha_entrega"  class="border-bottom w-100 pt-1 pb-2">  &nbsp; </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class = "col-md-7">
                            <div class = "row m-1" style="border:1px solid; border-color: #17a2b8;">

                                <div class = "col-md-4">
                                    <div class = "form-group row m-0">
                                        <label class="col-md-8 col-form-label" for = "adicionales">Adicionales</label>
                                        <div class="col-md-4"> 
                                            <button type="button" class="btn btn-sm btn-info rounded-eye m-1 "  data-toggle="collapse" href="#datosadicionales" title="Datos Adicionales">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div> 
                                </div>

                                <div class = "col-md-4">
                                    <div class = "form-group row m-0">
                                        <label class="col-md-8 col-form-label" for = "obervaciones">Monedero</label>
                                        <div class="col-md-4"> 
                                            <button type="button" class="btn btn-sm btn-info rounded-eye m-1 " data-toggle="collapse" href="#datosmonedero" title="Monedero Electrónico">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div> 
                                </div>

                                <div class = "col-md-4">
                                    <div class = "form-group row m-0">
                                        <label class="col-md-8 col-form-label" for = "factura">Factura</label>
                                        <div class="col-md-4"> 
                                            <button type="button" class="btn btn-sm btn-info rounded-eye m-1 "  data-toggle="collapse" href="#datosfactura"  title="Facturación Electrónica">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div> 
                                </div>

                                <div class="col-md-12">
                                    <div class="collapse multi-collapse" id="datosadicionales">
                                        <div class="card card-body">
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "filiacion">Filiación</label>
                                                <div class="col-md-8"> <input id="filiacion" type = "text" class = "form-control form-control-border" name = "filiacion" value=""></div>
                                            </div> 
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "fur">Fecha última Regla</label>
                                                <div class="col-md-8"> <input id="fur" type = "date" class = "form-control form-control-border" name = "fur" value="" ></div>
                                            </div> 
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "fud">Fecha última Dosis</label>
                                                <div class="col-md-8"> <input id="fud" type = "date" class = "form-control form-control-border" name = "fud" value="" ></div>
                                            </div> 

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="collapse multi-collapse" id="datosmonedero">
                                        <div class="card card-body">
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "nueva_tarjeta">Nueva Tarjeta</label>
                                                <div class="col-md-7"> <input id="nueva_tarjeta" type = "text" class = "form-control form-control-border" name = "nueva_tarjeta" value="" placeholder="Nueva Tarjeta"></div>
                                                <div class="col-md-1"> 
                                                    <button type="button" class="btn btn-sm btn-primary rounded-circle btn-save-tarjeta"  data-toggle="tooltip" title="Guardar">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </div>
                                            </div> 
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "no_tarjeta">No. Tarjeta</label>
                                                <div class="col-md-7"> <input id="no_tarjeta" type = "text" class = "form-control form-control-border" name = "no_tarjeta" value="" placeholder="No. Tarjeta" ></div>
                                                <div class="col-md-1"> 
                                                    <button type="button" class="btn btn-sm btn-primary rounded-circle btn-search-tarjeta"  data-toggle="tooltip" title="Buscar">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                            </div> 
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "saldo">Saldo ($)</label>
                                                <div id="saldo"  class="border-bottom w-100 pt-1 pl-3 col-md-8 ">  &nbsp;</div>
                                            </div> 
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "abono">Abono ($)</label>
                                                <div id="abono" class="border-bottom w-100 pt-1 pl-3 col-md-8 ">  &nbsp;</div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="collapse multi-collapse" id="datosfactura">
                                        <div class="card card-body">
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "cliente">Cliente</label>
                                                <div class="col-md-8"> <input id="cliente" type = "text" class = "form-control form-control-border" name = "cliente" value=""></div>
                                            </div> 
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "rfc">RFC</label>
                                                <div class="col-md-8"> <input id="rfc" type = "text" class = "form-control form-control-border" name = "rfc" value="" ></div>
                                            </div> 
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "domicilio">Domicilio</label>
                                                <div class="col-md-8"> <input id="domicilio" type = "text" class = "form-control form-control-border" name = "domicilio" value="" ></div>
                                            </div> 
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "cp">C.P.</label>
                                                <div class="col-md-8"> <input id="cp" type = "text" class = "form-control form-control-border" name = "cp" value="" ></div>
                                            </div> 
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "mail">Mail</label>
                                                <div class="col-md-8"> <input id="mail" type= "text" class = "form-control form-control-border" name = "mail" value="" ></div>
                                            </div> 
                                            <div class = "form-group row m-0">
                                                <label class="col-md-4 col-form-label" for = "cfdi">Uso de Cfdi</label>
                                                <div class="col-md-8"> 
                                                    <select id="id_cfdi" class="form-control select2 select-registro" name="id_cfdi" >
                                                        <option value="" selected="">Uso de Cfdi</option>
                                                        <?php
                                                        foreach ($cfdi AS $row) {
                                                            ?>
                                                            <option value="<?= $row->id ?>" ><?= $row->uso ?></option>

                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <!--input id="cfdi" type = "text" class = "form-control form-control-border" name = "cfdi" value="" -->
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class = "col-md-12">
                                    <div class = "form-group row m-0">
                                        <label class="col-md-2 col-form-label" for = "observaciones">Notas</label>
                                        <div class="col-md-10"> <input type = "text" class = "form-control form-control-border text-uppercase" name = "observaciones" value=""></div>
                                    </div> 
                                </div>

                                

                                <div class = "col-md-3">
                                    <div class = "form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input id="cotizacion" name="cotizacion" type = "checkbox" class = "custom-control-input">
                                            <label class="custom-control-label" for="cotizacion">Cotización</label>
                                        </div>  
                                    </div>
                                </div>

                                <div class = "col-md-3">
                                    <div class = "form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input id="descuento_estudio" name="descuento_estudio" type = "checkbox" class = "custom-control-input">
                                            <label class="custom-control-label" for="descuento_estudio">Descuento X Estudio</label>
                                        </div>  
                                    </div>
                                </div>
                                <?php if($sucursal->tipo=="SUCURSAL"): ?>
                                <div class = "col-md-2">
                                    <div class = "form-group">
                                        <div class="custom-control custom-checkbox">
                                                <input id="maquila" name="maquila" type = "checkbox" class = "custom-control-input">
                                            
                                            <label class="custom-control-label" for="maquila">Maquila a Matriz</label>
                                        </div>  
                                    </div>
                                </div>
                                <?php endif; ?> 
                                <div id="orden_matriz" class = "col-md-2 d-none">
                                    <div class = "form-group row m-0">
                                        <label class="col-form-label" for = "ordenmatriz">Orden Matriz</label>
                                        <div  class="border-bottom w-100 pt-1 ">  &nbsp;</div>

                                    </div>
                                </div>

                                <div class = "col-md-2">
                                    <select class="custom-select" id="tipo_orden" name="tipo_orden" required style="width: 100%;">
                                        <option value="">Referencia </option>
                                        <option value="ORDEN_SAMALAB">Orden Samalab</option>
                                        <option value="INSTITUCIONP">Institución Pública</option>
                                        <option value="RECETA">Receta Médica</option>
                                        <option value="RECETA_REFERIDO">Receta M. Referido</option>
                                        <option value="OTRO">Orden Competencia</option>
                                        <option value="ORDEN_CREFERIDA">Orden Competencia Referida</option>
                                        <option value="WHATS">Referido por whatsapp</option>
                                        <option value="WEB">Venta en Página WEB</option>
                                        <option value="FORMATO_RECEPCION">Formato Recepción</option>
                                        <option value="FB">PROMOS FB</option>
                                                <!--option value="3">Cotización</option-->
                                    </select>
                                </div>

                                <div class = "col-md-2">
                                    <select class="custom-select" id="tipo_cliente" name="tipo_cliente" style="width: 100%;" required>
                                        <option value="">Tipo|Cliente</option>
                                        <!-- <option value="AQC">AQC</option>
                                        <option value="REFERIDO">REFERIDO</option>
                                        <option value="CLINICA">CLINICA</option>
                                        <option value="MAQUILA">MAQUILA</option> -->

                                    </select>
                                </div>

                                

                            </div>

                        </div>

                    </div>

                    <div class="md-col-12 menssage h2 text-center font-weight-bold">
                    </div>

                    <div class="md-12 m-1">
                        <table id="table_registro" width="100%" class="table-bordered table-responsive" >
                            <thead align="center" class="bg-teal color-palette" style="background: #138496;">
                                <tr >

                                    <th width="10%">Código</th>
                                    <th width="30%">Descripción</th>
                                    <th width="12%">Paquete</th>
                                    <th>Precio Público</th>
                                    <th>Precio Neto</th>
                                    <th>Fecha de Entrega</th>
                                    <th ><span style="background:yellow !important;">Maquila</span></th>

                                    <th width="8%">Acciones</th>
                                    <th width="10%">%</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <?php
                                for ($i = 1; $i <= 1; $i++) {
                                    ?>
                                    <tr id='est_<?= $i ?>' class="text-center">
                                        <td>
                                            <input type="text" class="form-control form-control-border text-uppercase codigo" data-id="<?= $i ?>"  name="codigo[]" value="" placeholder="Código" required="">
                                        </td>
                                        <td></td>
                                        <td class="paquete"></td>
                                        <td class="precio_publico" id="precio_publico_<?= $i ?>"></td>
                                        <td class="precio_neto" id="precio_neto_<?= $i ?>"></td>
                                        <td class="fecha_entrega"></td>
                                        <td class="maquila" id="maquila_<?= $i ?>"></td>
                                        <td align='center'>                                          
                                            <button type='button' class='btn btn-sm btn-danger delete-estudio rounded-circle mt-1 mb-1' data-id='<?= $i ?>'>
                                                <i class='fas fa-trash'></i>
                                            </button>
                                            <button type='button' class="btn btn-sm btn-info info-estudio rounded-circle m-1" data-id="<?= $i ?>" data-toggle="tooltip" title="Indicaciones">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <div class='custom-control custom-checkbox d-none display-descuento'>
                                                <div class="row m-0 p-0">
                                                    <div class="col-1 m-0 p-0">
                                                         <input id='descuento_<?= $i ?>' checked='' type='checkbox' name='descuento[]' value='<?= $i ?>' class='custom-control-input descuento'>
                                                        <label class='custom-control-label' for='descuento_<?= $i ?>'></label>
                                                    </div>
                                                    <div class="col-11 m-0 p-0">
                                                        <input type="number" class="form-control pdescuento" id="pdescuento_<?= $i ?>" name="p_descuento[]" value="0" style="display: inline-block;width:100%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        

                                    </tr>
                                    <?php
                                }
                                ?>

                            </tbody>
                        </table>

                    </div>

                    <div class="row">

                        <div class = "col-md-12 text-right">
                            <button type="button" class="btn btn-sm btn-success rounded-circle btn-add-paq"  data-toggle="tooltip" title="Agregar">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>


                        <div class = "col-md-4">
                            <div class = "form-group row m-2">
                                <label class="col-md-4 col-form-label" for = "id_descuento">Descuento</label>
                                <div class="col-md-8">     
                                    <select id="id_descuento" class="custom-select" name="id_descuento">
                                        <option value=""  data-descuento="0" data-autorizacion="0" selected="">Sin descuento</option>
                                        <?php
                                        foreach ($descuentos AS $row) {
                                            ?>
                                            <option value="<?= $row->id ?>" data-descuento="<?= $row->descuento ?>" data-autorizacion="<?= $row->autorizacion ?>"><?= $row->nombre ?></option>

                                            <?php
                                        }
                                        ?>
                                    </select></div>
                            </div> 
                        </div>

                        <div class = "col-md-4">
                            <div class = "form-group row m-2">
                                <label class="col-md-4 col-form-label" for = "aumento">Aumento</label>
                                <div class="col-md-8"> <input id="aumento" type = "text" class = "form-control " name = "aumento" value=""></div>
                            </div> 
                        </div>

                        <div class = "col-md-4" >
                            <div class = "form-group row m-2">
                                <label class="col-md-4 col-form-label " for = "total">Importe</label>
                                <!--span class="col-form-label font-weight-bold">$0.00</span-->
                                <div class="col-md-8"> <input id="total" type = "text" class = "form-control" name = "total" value="0.0" readonly=""></div>

                            </div>
                        </div>

                        <div class="col-md-4 offset-md-4 ">
                            <button id="save" class="btn btn-block bg-gradient-success"><i class="far fa-save  pr-2"></i> Guardar</button>
                        </div>

                    </div>

                </form>
            </div>

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->

    <div class="modal fade" id="modalPacientes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Búsqueda de Pacientes</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation-pacientes" action="#" method="POST" novalidate="">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="palabra">Paciente / Folio</label>
                                    <input id="palabra" type = "text" class = "form-control form-control-border text-uppercase" name = "palabra" value="" placeholder = "Paciente / Folio">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Paciente / Folio
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_ini">De</label>
                                    <input id="nombre_componet" type = "date" class = "form-control form-control-border text-uppercase" name = "fecha_ini" value="<?= date("Y-m-d") ?>" placeholder = "De" required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo De
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_fin">A</label>
                                    <input id="nombre_componet" type = "date" class = "form-control form-control-border text-uppercase" name = "fecha_fin" value="<?= date("Y-m-d") ?>" placeholder = "A" required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo A
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-2 mt-4">
                                <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="table-responsive" style="height: 400px">
                            <table id="tablePacientes" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Orden</th>
                                        <th>Paciente</th>
                                        <th>Fecha Nacimiento</th>
                                        <th>Expediente</th>
                                        <th>Fecha Orden</th>
                                        <th>Saldo Deudor</th>
                                        <th>Maquila</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="table-pacientes"></tbody>
                            </table>
                        </div>
                    </div>


                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>


    <!---- Modal para el registro de pacientes  ---->
    <div class="modal fade" id="modalBusquedaPacientes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Búsqueda de Pacientes</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation-pacientes" action="#" method="POST" novalidate="">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bpaterno">Fecha Nacimiento (Opcional)</label>
                                    <input id="bfn" name="bfn" type="date" class="form-control" >
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bpaterno">Paterno</label>
                                    <input id="bpaterno" name="bpaterno" type="text" class="form-control" >
                                </div>
                            </div> 

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bmaterno">Materno</label>
                                    <input id="bmaterno" name="bmaterno" type="text" class="form-control" >
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bnombre">Nombre</label>
                                    <input id="bnombre" name="bnombre" type="text" disabled class="form-control" >
                                </div>
                            </div>


                        </div>
                    </form>
                    <br><br>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Expediente</th>
                                <th>Número Ordenes</th>
                                <th>Nombre</th>
                                <th>Fecha Nacimiento</th>
                                <th>Fecha Registro</th>
                                <th>-</th>
                            </tr>
                        </thead>
                        <tbody id="bt_pacientes_buscados">
                            
                        </tbody>
                        
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" id="bregistrar" disabled>Registrar Nuevo</button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.content-wrapper -->

<style>
    .col-form-label {
        font-weight: 400 !important;
        color: #060606;
        font-size: 16px;
    }

    .form-control{

        height: calc(1.75rem + 2px);
    }

    .btn-sm{
        font-size: .650rem;    
    }

    .bg-teal, .bg-teal>a {
        color: #000!important;
    }

    .select2-container--default .select2-selection--single {

        padding: .26875rem .55rem !important;

    }


    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 30px !important;
    }

    .ui-menu{
        overflow: auto;
        max-height: 200px;
    }




</style>

