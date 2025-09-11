<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="padding: 1px!important;">
        <div class="container-fluid">
            <!-- Default box -->
            <div class="card mt-2">
                <div class="card-header">

                    <form class="needs-validation-codigo" action="modificacion-paciente" method="post" novalidate="">

                        <div class="row">

                            <div class="col-md-3">
                                <h3 class="card-title">Búsqueda de Paciente</h3>
                            </div>

                            <div class = "col-md-3">
                                <div class = "form-group row m-0">
                                    <label class="col-md-4 col-form-label" for = "codigo">Código</label>
                                    <div class="col-md-8"> 
                                        <input id="codigo" type="text" class="form-control form-control-border" name="codigo" value="<?= $codigo ?>" placeholder="Código" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Código
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-block bg-gradient-success"><i
                                        class="fa fa-search pr-2"></i> Buscar</button>
                            </div>
                            <?php
                            if ($id_orden != "" &&  $orden->cancelado == 0) {
                                ?>
                                <div class="col-md-2 offset-md-2">
                                    <button type="button" class="btn btn-block btn-outline-danger btn-cancear-orden" <?=( $cubierto > 0 || $orden->cancelado == 1 || $reportado)? "disabled" : "" ?>><i class="fa fa-trash  pr-2"></i> Cancelación</button>
                                </div>
                                <?php
                            }else if ($id_orden != "" && $orden->cancelado == 1 ) {
                                ?>
                                <div class="col-md-2 offset-md-2">
                                    <button type="button" class="btn btn-block btn-outline-primary btn-activar-orden"><i class="fa fa-check  pr-2"></i> Activar</button>
                                </div>
                                <?php
                            }
                            ?>

                        </div>
                        <!-- /.row -->
                    </form>
                </div>
                <!-- /.card-header -->
            </div>
            <!-- /.card -->
        </div><!-- /.container-fluid -->
    </section>

    <?php
    if ($id_orden != "") {

        ?>
        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card">
                <!--  <div class="card-header">  </div>-->

                <div class="card-body">

                    <form class="needs-validation" action="controller/admision/Paciente?opc=modificacion" method="post" novalidate>
                        <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                        <input type="hidden" name="consecutivo" value="<?= $codigo ?>">
                        <input type="hidden" id="id_orden" name="id_orden" value="<?= $id_orden ?>">
                        <input type="hidden" id="id_paciente" name="id_paciente" value="<?= $orden->id_paciente ?>">
                        <div class="row">

                            <div class = "col-md-4">
                                <div class = "form-group row m-0">
                                    <label class="col-md-5 col-form-label" for = "paterno">Apellido Paterno</label>
                                    <div class="col-md-7"> <input id="paterno" type = "text" class = "form-control form-control-border <?= $_SESSION["ruta"] != "alcala" ? "text-uppercase" : "" ?> tab" name = "paterno" value="<?= $orden->paterno ?>" required=""></div>

                                </div>
                            </div>

                            <div class = "col-md-4">
                                <div class = "form-group row m-0">
                                    <label class="col-md-5 col-form-label" for = "materno">Apellido Materno</label>
                                    <div class="col-md-7"> <input id="materno"  type = "text" class = "form-control form-control-border <?= $_SESSION["ruta"] != "alcala" ? "text-uppercase" : "" ?> tab" name = "materno" value="<?= $orden->materno ?>" required=""></div>
                                </div>
                            </div>

                            <div class = "col-md-4">
                                <div class = "form-group row m-1">
                                    <label class="col-md-4 col-form-label" for = "nombre">Nombre (s)</label>
                                    <div class="col-md-6"> 
                                        <input  id="nombre"  type = "text" class = "form-control form-control-border <?= $_SESSION["ruta"] != "alcala" ? "text-uppercase" : "" ?> tab" name = "nombre" value="<?= $orden->nombre ?>"  required="">
                                    </div>

                                </div>
                            </div>

                            <div class = "col-md-4">
                                <div class = "form-group row m-0">
                                    <label class="col-md-5 col-form-label" for = "fecha_nac">Fecha Nacimiento</label>
                                    <div class="col-md-7"> <input id="fecha_nac" type = "date" class = "form-control form-control-border tab" name = "fecha_nac" value="<?= $orden->fecha_nac ?>" required=""></div>
                                </div>
                            </div>

                            <div class = "col-md-4">
                                <div class = "form-group row m-0">
                                    <label class="col-md-2 col-form-label" for = "edad">Edad</label>
                                    <div class="col-md-3"> <input id="edad" type = "texto" class = "form-control form-control-border tab" name = "edad" value="<?= $orden->edad ?>"  required=""></div>

                                    <div class="col-md-7">
                                        <select  id="tipo_edad" name="tipo_edad" class="custom-select tab">
                                            <option value="Anios" <?= $orden->tipo_edad == "Anios" ? "selected" : "" ?>>Años</option>
                                            <option value="Meses" <?= $orden->tipo_edad == "Meses" ? "selected" : "" ?>>Meses</option>
                                            <option value="Dias" <?= $orden->tipo_edad == "Dias" ? "selected" : "" ?>>Dias</option>
                                            <option value="Horas" <?= $orden->tipo_edad == "Horas" ? "selected" : "" ?>>Horas</option>
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class = "col-md-4">
                                <div class = "form-group row m-1">
                                    <label class="col-md-4 col-form-label" for = "sexo">Sexo</label>
                                    <div class="col-md-8"> 
                                        <select id="sexo" name="sexo" class="custom-select tab" required="">
                                            <option value="Masculino" <?= $orden->sexo == "Masculino" ? "selected" : "" ?>>Hombre</option>
                                            <option value="Femenino" <?= $orden->sexo == "Femenino" ? "selected" : "" ?>>Mujer</option>
                                            <option value="Nino" <?= $orden->sexo == "Nino" ? "selected" : "" ?>>Niño (a)</option>
                                        </select>

                                    </div>
                                </div>
                            </div>

                            <div class = "col-md-4">
                                <div class = "form-group row m-0">
                                    <label class="col-md-3 col-form-label" for = "direccion">Dirección</label>
                                    <div class="col-md-9"> <input id="direccion" type = "text" class = "form-control form-control-border text-uppercase tab" name = "direccion" value="<?= $orden->direccion ?>"></div>

                                </div>
                            </div>

                            <div class = "col-md-4">
                                <div class = "form-group row m-0">
                                    <label class="col-md-3 col-form-label" for = "cpEmail">Email</label>
                                    <div class="col-md-9"> <input id="email" type = "text" class = "form-control form-control-border text-uppercase tab" name = "cpEmail" value="<?= $orden->cpEmail ?>" ></div>

                                </div>
                            </div>

                            <div class = "col-md-4 ">
                                <div class = "form-group row m-0">
                                    <label class="col-md-4 col-form-label" for = "tel">Teléfono</label>
                                    <div class="col-md-8"> <input id="tel" type = "text" class = "form-control form-control-border tab" name = "tel" value="<?= $orden->tel ?>" ></div>

                                </div>
                            </div>

                            <div class = "col-md-6">
                                <div class="row">
                                    <div class = "col-md-12">
                                        <div class = "form-group row m-0">
                                            <label class="col-md-2 col-form-label" for = "medico">Médico</label>
                                            <div class="col-md-9 mb-2"> 
                                                <input id="medico" type = "text" class = "form-control form-control-border text-uppercase tab" name = "medico" value="<?= $orden->nombre_doctor ?>" required="">
                                                <input id="id_doctor" type = "hidden" name = "id_doctor" value="<?= $orden->id_doctor ?>" >
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
                                                <select id="id_empresa" class="form-control select2 select-registro tab" name="id_empresa" <?= $orden->id_empresa != "" ? "disabled" : "" ?> style="width: 100%;">
                                                    <option value="">Selecciona una Empresa</option>
                                                    <?php
                                                    foreach ($lista_empresas AS $row) {
                                                        ?>
                                                        <option value="<?= $row->id ?>" <?= $orden->id_empresa == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>

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
                                                <div  id="fecha_entrega"  class="border-bottom w-100 pt-1 pb-2"> <?= $detalle[0]->fecha_entrega ?> </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class = "col-md-6">
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
                                                    <div class="col-md-8"> <input id="filiacion" type = "text" class = "form-control form-control-border" name = "filiacion" value="<?= $orden->filacion ?>"></div>
                                                </div> 
                                                <div class = "form-group row m-0">
                                                    <label class="col-md-4 col-form-label" for = "fur">Fecha última Regla</label>
                                                    <div class="col-md-8"> <input id="fur" type = "date" class = "form-control form-control-border" name = "fur" value="<?= $orden->fur ?>" ></div>
                                                </div> 
                                                <div class = "form-group row m-0">
                                                    <label class="col-md-4 col-form-label" for = "fud">Fecha última Dosis</label>
                                                    <div class="col-md-8"> <input id="fud" type = "date" class = "form-control form-control-border" name = "fud" value="<?= $orden->fud ?>" ></div>
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
                                                    <div class="col-md-8"> <input id="cliente" type = "text" class = "form-control form-control-border" name = "cliente" value="<?= $fiscal->nombre_fiscal ?>"></div>
                                                </div> 
                                                <div class = "form-group row m-0">
                                                    <label class="col-md-4 col-form-label" for = "rfc">RFC</label>
                                                    <div class="col-md-8"> <input id="rfc" type = "text" class = "form-control form-control-border" name = "rfc" value="<?= $fiscal->rfc ?>" ></div>
                                                </div> 
                                                <div class = "form-group row m-0">
                                                    <label class="col-md-4 col-form-label" for = "domicilio">Domicilio</label>
                                                    <div class="col-md-8"> <input id="domicilio" type = "text" class = "form-control form-control-border" name = "domicilio" value="<?= $fiscal->direccion_fiscal ?>" ></div>
                                                </div> 
                                                <div class = "form-group row m-0">
                                                    <label class="col-md-4 col-form-label" for = "cp">C.P.</label>
                                                    <div class="col-md-8"> <input id="cp" type = "text" class = "form-control form-control-border" name = "cp" value="<?= $fiscal->codigo_postal ?>" ></div>
                                                </div> 
                                                <div class = "form-group row m-0">
                                                    <label class="col-md-4 col-form-label" for = "mail">Mail</label>
                                                    <div class="col-md-8"> <input id="mail" type= "text" class = "form-control form-control-border" name = "mail" value="<?= $fiscal->correo ?>" ></div>
                                                </div> 
                                                <div class = "form-group row m-0">
                                                    <label class="col-md-4 col-form-label" for = "cfdi">Uso de Cfdi</label>
                                                    <div class="col-md-8"> 
                                                        <select id="id_cfdi" class="form-control select2 select-registro" name="id_cfdi" >
                                                            <option value="" selected="">Uso de Cfdi</option>
                                                            <?php
                                                            foreach ($cfdi AS $row) {
                                                                ?>
                                                                <option value="<?= $row->id ?>"  <?= $fiscal->uso_cfdi == $row->id ? "selected" : "" ?> ><?= $row->uso ?></option>

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
                                            <div class="col-md-10"> <input type = "text" class = "form-control form-control-border text-uppercase" name = "observaciones" value="<?= $orden->observaciones ?> "></div>
                                        </div> 
                                    </div>

                                    <div class = "col-md-3">
                                    <div class = "form-group">
                                        <div class="custom-control custom-checkbox">
                                            <?php if($sucursal->tipo=="MATRIZ"): ?>
                                                <input id="maquila" name="maquila" type = "checkbox" class = "custom-control-input" disabled>
                                            <?php else: ?>
                                                <input id="maquila" name="maquila" type = "checkbox" class = "custom-control-input">
                                            <?php endif; ?> 
                                            <label class="custom-control-label" for="maquila">Maquila a Matriz</label>
                                        </div>  
                                    </div>
                                </div>

                                <div id="orden_matriz" class = "col-md-3 d-none">
                                    <div class = "form-group row m-0">
                                        <label class="col-form-label" for = "ordenmatriz">Orden Matriz</label>
                                        <input type="hidden" name="ordenmatriz" value="<?=$orden->consecutivo_matriz?>">
                                        <div  class="border-bottom w-100 pt-1 ">  &nbsp;</div>

                                    </div>
                                </div>

                                <div class = "col-md-6">
                                    <div class="custom-control custom-checkbox">
                                            <select class="custom-select" id="tipo_orden" name="tipo_orden" required style="width: 100%;">
                                            <option value="">Referencia </option>
                                            <option value="ORDEN_SAMALAB">Orden Samalab</option>
                                            <option value="INSTITUCIONP">Institución Pública</option>
                                            <option value="RECETA">Receta Médica</option>
                                            <option value="RECETA_DIFERIDO">Receta M. Referido</option>
                                            <option value="OTRO">Orden Competencia</option>
                                            <option value="WHATS">Referido por whatsapp</option>
                                            <option value="WEB">Venta en Página WEB</option>
                                            <option value="FORMATO_RECEPCION">Formato Recepción</option>
                                            <option value="FB">PROMOS FB</option>
                                                <!--option value="3">Cotización</option-->
                                                <?php 
                                                echo '<script>document.getElementById("tipo_orden").value="'.$orden->tipo_orden.'"</script>';
                                                 ?>
                                            </select>
                                        </div> 
                                </div>

                                </div>

                            </div>

                        </div>

                        <div class="md-col-12 menssage h2 text-center font-weight-bold">
                        </div>

                        <div class="col-md-12 text-right"> 
                            <button type="button" class="btn btn-primary rounded-circle reset-orden"  title="Restaurar">
                                <i class="fas fa-history"></i> 
                            </button>

                        </div>

                        <div class="md-12 m-1">
                            <table id="table_registro" width="100%" class="table-bordered table-responsive" >
                                <thead align="center" class="bg-teal color-palette" style="background: #138496;">
                                    <tr >

                                        <th width="10%">Código</th>
                                        <th width="30%">Descripción</th>
                                        <th width="15%">Paquete</th>
                                        <th>Precio Público</th>
                                        <th>Precio Neto</th>
                                        <th>Fecha de Entrega</th>
                                        <th>Maquila</th>
                                        <th width="10%">Acciones</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    <?php
                                    for ($i = 1; $i <= count($detalle); $i++) {
                                        // var_dump($detalle[$i-1]);
                                        ?>
                                        <tr id='est_<?= $i ?>' class="text-center <?= $detalle[$i - 1]->impresion > 0 ? "text-success" : ( $detalle[$i - 1]->reportado > 0 ? "text-primary" : "text-black" ) ?>">
                                            <td>
                                                <input type="text" class="form-control form-control-border text-uppercase codigo" disabled="" data-id="<?= $i ?>"  name="codigo[]" value="<?= $detalle[$i - 1]->alias ?>" placeholder="Código" required="">
                                            </td>
                                            <td><?= $detalle[$i - 1]->nombre_estudio ?></td>
                                            <td class="paquete"><?= $detalle[$i - 1]->paquete ?></td>
                                            <td class="precio_publico" data-precio="<?= $detalle[$i - 1]->paquete != "" ? $detalle[$i - 1]->precio_detalle_paquete : $detalle[$i - 1]->precio_publico ?>" data-publico="<?= $detalle[$i - 1]->paquete != "" ? $detalle[$i - 1]->precio_detalle_paquete : $detalle[$i - 1]->precio_publico ?>" data-id_detalle="<?= $detalle[$i - 1]->id ?>"><?= $detalle[$i - 1]->precio_publico ?></td>
                                            <td class="precio_neto"><?= $detalle[$i - 1]->precio_neto_estudio ?></td>
                                            <td class="fecha_entrega"><?= $detalle[$i - 1]->fecha_entrega ?></td>
                                            <td class="maquila" id="maquila_<?= $i ?>">
                                                <input type="hidden" name="enviado_maquila[]" value="<?=$detalle[$i - 1]->id_estudio?>">
                                            <?php 
                                                if($detalle[$i - 1]->envio_maquila=="SI"){
                                                    echo $detalle[$i - 1]->precio_maquila."&nbsp;&nbsp;";
                                                    echo '<input type="checkbox" data-id="'.$detalle[$i - 1]->id_estudio.'" data-precio="'.$detalle[$i - 1]->precio_maquila.'" class="cmaquila"  checked>';
                                                }
                                                    
                                            ?>          
                                            </td>
                                            <td align='center'>                                          
                                                <button type='button' <?= $detalle[$i - 1]->paquete != "" ? "data-paq='" . $detalle[$i - 1]->paquete . "'" : "" ?> class='btn btn-sm btn-danger delete-estudio rounded-circle mt-1 mb-1'  <?= ($cubierto > 0  || $orden->cancelado == 1) ? "disabled" : "" ?> data-borrar='<?= $detalle[$i - 1]->impresion == 0 && $detalle[$i - 1]->reportado == 0 ? "1" : "0" ?>' data-id='<?= $i ?>'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                                <button type='button' class="btn btn-sm btn-info info-estudio rounded-circle m-1"  <?= ($cubierto > 0  || $orden->cancelado == 1) ? "disabled" : "" ?> data-id="<?= $i ?>" data-toggle="tooltip" title="Indicaciones">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <div class="row m-0 p-0 d-none">
                                                    <div class="col-4 m-0 p-0">
                                                         <input id='descuento_<?= $i ?>' checked='' type='checkbox' name='descuento[]' value='<?= $i ?>' class='custom-control-input descuento'>
                                                        <label class='custom-control-label' for='descuento_<?= $i ?>'></label>
                                                    </div>
                                                    <div class="col-8 m-0 p-0">
                                                        <input type="number" class="form-control pdescuento" id="pdescuento_<?= $i ?>" name="p_descuento[]" value="0" style="display: inline-block;width:100%;">
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <?php
                                    }

                                    for ($i = count($detalle) + 1; $i <= count($detalle) + 1; $i++) { 
                                        ?>
                                        <tr id='est_<?= $i ?>' class="text-center">
                                            <td>
                                                <input type="text" class="form-control form-control-border text-uppercase codigo" data-id="<?= $i ?>"  name="codigo[]" value="" placeholder="Código" >
                                            </td>
                                            <td></td>
                                            <td class="paquete"></td>
                                            <td class="precio_publico"></td>
                                            <td class="precio_neto"></td>
                                            <td class="fecha_entrega"></td>
                                            <td class="maquila" id="maquila_<?= $i ?>"></td>
                                            <td align='center'>                                          
                                                <button type='button' class='btn btn-sm btn-danger delete-estudio rounded-circle mt-1 mb-1' data-id='<?= $i ?>' >
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                                <button type='button' class="btn btn-sm btn-info info-estudio rounded-circle m-1" data-id="<?= $i ?>" data-toggle="tooltip" title="Indicaciones">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <div class="row m-0 p-0 d-none">
                                                    <div class="col-4 m-0 p-0">
                                                         <input id='descuento_<?= $i ?>' checked='' type='checkbox' name='descuento[]' value='<?= $i ?>' class='custom-control-input descuento'>
                                                        <label class='custom-control-label' for='descuento_<?= $i ?>'></label>
                                                    </div>
                                                    <div class="col-8 m-0 p-0">
                                                        <input type="number" class="form-control pdescuento" id="pdescuento_<?= $i ?>" name="p_descuento[]" value="0" style="display: inline-block;width:100%;">
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
                                        <select id="id_descuento" class="custom-select" name="id_descuento" <?= $cubierto > 0 ? "disabled" : "" ?>  <?= $cubierto > 0 || $orden->aumento > 0 ? "disabled" : "" ?>>
                                            <option value=""  data-descuento="0" data-autorizacion="0" selected="">Sin descuento</option>
                                            <?php
                                            $descuento = "0";
                                            foreach ($descuentos AS $row) {
                                                ?>
                                                <option value="<?= $row->id ?>"  <?= $orden->id_descuento == $row->id ? "selected" : "" ?> data-descuento="<?= $row->descuento ?>" data-autorizacion="<?= $row->autorizacion ?>"><?= $row->nombre ?></option>

                                                <?php
                                                $descuento = $orden->id_descuento == $row->id ? $row->descuento : 0;
                                            }
                                            ?>
                                        </select></div>
                                </div> 
                            </div>


                            <div class = "col-md-4">
                                <div class = "form-group row m-2">
                                    <label class="col-md-4 col-form-label" for = "aumento">Aumento</label>
                                    <div class="col-md-8"> <input id="aumento" type = "text" class = "form-control " <?= $cubierto > 0 || $orden->id_descuento > 0 ? "disabled" : "" ?> name = "aumento" value="<?= $orden->aumento ?>"></div>
                                </div> 
                            </div>

                            <div class = "col-md-4" >
                                <div class = "form-group row m-2">
                                    <label class="col-md-4 col-form-label " for = "total">Importe</label>
                                    <!--span class="col-form-label font-weight-bold">$0.00</span-->
                                    <input type="hidden" id="cubierto" name="cubierto" value="<?= $cubierto ?>">
                                    <div class="col-md-8"> <input id="total" type = "text" class = "form-control" <?= $cubierto > 0 || $orden->id_descuento > 0 || $orden->aumento > 0 ? "disabled" : "" ?> name = "total" value="<?= $orden->importe ?>" ></div>

                                </div>
                            </div>

                            <div class="col-md-4 offset-md-4 ">
                                <button id="save" class="btn btn-block bg-gradient-success" <?= $orden->cancelado == 1 ? "disabled" : "" ?>><i class="far fa-save  pr-2"></i> Guardar</button>
                            </div>

                        </div>
                        <input type="hidden" id="hide_descuento" name="hide_descuento" value="<?= $descuento ?>">
                        <input type="hidden" id="hide_aumento" name="hide_aumento" value="<?= $orden->aumento ?>">
                    </form>
                </div>

            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->

        <?php
    }
    ?>

</div>
<!-- /.content-wrapper -->

<!-- Cancelar Orden -->
<div class="modal fade" id="modConfirmarDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Está seguro que desea cancelar la Orden?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Cancelar" para cancelar la orden <span id="nombre_" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <!--button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button-->
                <form action="controller/admision/Paciente?opc=cancelar-orden" method="POST" >
                    <input type="hidden" class="d-none" id="id_orden_" name="id_orden" value="">
                    <button id="" class="btn btn-danger btn-cargando" >Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Eliminar Doctor -->
<div class="modal fade" id="modConfirmar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Está seguro que desea activar la Orden?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Activar" para activar la orden <span id="nombre_2" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <!--button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button-->
                <form action="controller/admision/Paciente?opc=activar-orden" method="POST" >
                    <input type="hidden" class="d-none" id="id_orden_2" name="id_orden" value="">
                    <button id="" class="btn btn-primary btn-cargando" >Activar</button>
                </form>
            </div>
        </div>
    </div>
</div>


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

