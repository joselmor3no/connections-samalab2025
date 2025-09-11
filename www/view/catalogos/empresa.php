

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-md-6 col-sm-6">
                    <h1><i class="fas fa-building nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="empresas" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i> Buscar</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $empresa[0]->id == "" ? "Alta" : "Modificación" ?> de Empresa</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/catalogos/Empresa?opc=registro" method="post" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input type="hidden"  name="id_empresa" id="id_empresa_credito" value="<?= $empresa[0]->id ?>">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="alias">Alias</label>
                                <input id="alias" type="text" class="form-control form-control-border text-uppercase"  name="alias" value="<?= $empresa[0]->alias ?>" placeholder="Alias" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Alias
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control form-control-border <?= $_SESSION["ruta"] != "alcala" ? "text-uppercase" : "" ?>"  name="nombre" value="<?= $empresa[0]->nombre ?>" placeholder="Nombre" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Nombre
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="direccion" value="<?= $empresa[0]->direccion ?>" placeholder="Dirección">
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select class="form-control select2 text-uppercase" id="id_estado" name="estado" style="width: 100%;">
                                    <option value="-1">Selecciona un Estado</option>
                                    <?php
                                    foreach ($estados AS $row) {
                                        ?>
                                        <option value="<?= $row->estado ?>" <?= $empresa[0]->estado == $row->estado ? "selected" : "" ?>><?= $row->estado ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="municipio">Municipio</label>
                                <select class="form-control select2 text-uppercase" id="id_municipio" name="ciudad" style="width: 100%;">
                                    <option value="-1">Selecciona un Estado</option>
                                    <?php
                                    foreach ($municipios AS $row) {
                                        ?>
                                        <option value="<?= $row->municipio ?>" <?= $empresa[0]->ciudad == $row->municipio ? "selected" : "" ?>><?= $row->municipio ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "cp">Código Postal</label>
                                <input type = "text" class = "form-control form-control-border" name = "cp" value="<?= $empresa[0]->cp ?>" placeholder = "Código Postal">
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "rfc">RFC</label>
                                <input type = "text" class = "form-control form-control-border text-uppercase" name = "rfc" value="<?= $empresa[0]->rfc ?>" placeholder = "RFC">
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "tel">Teléfono</label>
                                <input type = "text" class = "form-control form-control-border " name = "tel" value="<?= $empresa[0]->telefono ?>" placeholder = "Teléfono">
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "cel">Celular</label>
                                <input type = "text" class = "form-control form-control-border" name = "cel" value="<?= $empresa[0]->celular ?>" placeholder = "Celular">
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "contacto">Contacto</label>
                                <input type = "text" class = "form-control form-control-border text-uppercase" name = "contacto" value="<?= $empresa[0]->contacto ?>" placeholder = "Contacto">
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "promotor">Promotor</label>
                                <input type = "text" class = "form-control form-control-border text-uppercase" name = "promotor" value="<?= $empresa[0]->promotor ?>" placeholder = "Promotor">
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "tipo_sucursal">Tipo empresa</label>
                                <select class = "form-control select2" name = "tipo_sucursal" style = "width: 100%;">
                                    <option value=""> &nbsp; </option>
                                    <option value="Sucursal" <?= $empresa[0]->tipo == "Sucursal" ? "selected" : "" ?>>Sucursal</option>
                                    <option value="Sucursal">Crédito</option>
                                    <option value="Sucursal">Contado</option>
                                    <option value="Sucursal">Licitación</option>
                                    <option value="Sucursal">Subrogado</option>
                                </select>
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "id_lista_precios">Lista de precios</label>
                                <select id="id_lista_precios" class = "form-control select2" name = "id_lista_precios" style = "width: 100%;">
                                    <option value=""> &nbsp; </option>
                                    <?php
                                    foreach ($lista_precios AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $empresa[0]->id_lista_precios == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="descarga">Expediente</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?= $sucursal[0]->codigo . "_" ?></span>
                                        <input type="hidden" name="codigo" value="<?= $sucursal[0]->codigo ?>">
                                    </div>
                                    <input type="text" class="form-control form-control-border"  name="expediente" value="<?= str_replace($sucursal[0]->codigo . "_", "", $empresa[0]->expediente) ?>" placeholder="Expediente">

                                </div>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pass">Contraseña</label>
                                <input type="text" class="form-control form-control-border"  name="pass" value="<?= $empresa[0]->contrasena ?>" placeholder="Contraseña">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="porcentaje">Porcentaje (%)</label>
                                <input type="text" class="form-control form-control-border lista"  name="porcentaje" value="<?= $empresa[0]->porcentaje ?>"  placeholder="Porcentaje (%)" <?= $empresa[0]->id_lista_precios != "" ? "disabled" : "" ?>>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="porcentaje_pago">Porcentaje de pago (%)</label>
                                <input type="text" class="form-control form-control-border lista"  name="porcentaje_pago" value="<?= $empresa[0]->porcentaje_pago ?>"  placeholder="Porcentaje de pago  (%)" <?= $empresa[0]->id_lista_precios != "" ? "disabled" : "" ?>>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="aumento">Aumento (%)</label>
                                <input type="text" class="form-control form-control-border lista"  name="aumento" value="<?= $empresa[0]->aumento ?>"  placeholder="Aumento (%)" <?= $empresa[0]->id_lista_precios != "" ? "disabled" : "" ?>>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email">Correo eletrónico</label>
                                <input type="text" class="form-control form-control-border"  name="email" value="<?= $empresa[0]->email ?>" placeholder="Correo eletrónico">
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="credito">Crédito</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="credito" type="checkbox" <?= $empresa[0]->credito == "1" ? "checked" : "" ?> >
                                    <label class="form-check-label">Crédito</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">&nbsp;</label>
                                <button type="button" class="btn btn-info btn-sm btn-sm form-control"  data-toggle="modal" data-target="#exampleModal">% Descuentos</button>
                            </div>
                            
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="laboratorio">Laboratorio</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="laboratorio" type="checkbox" <?= $empresa[0]->laboratorio == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Laboratorio</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="logo">Mostrar logo</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="logo" type="checkbox" <?= $empresa[0]->mostrarlogo == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Mostrar logo</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inactivo">Inactivo</label>
                                <div class="form-check">
                                    <input class="form-check-input" name="inactivo" type="checkbox" <?= $empresa[0]->inactivo == "1" ? "checked" : "" ?>>
                                    <label class="form-check-label">Inactivo</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-2 offset-md-5 pt-4 pb-2">
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
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Procentaje de descuento para las clases de estudio (Empresas de Crédito)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">   
            <div class="col-12"> 
                <table class="table table table-striped"> 
                    <thead>
                        <tr>
                            <th>Clase</th>
                            <th>Tipo Descuento</th>
                            <th>Descuento Guardado</th>
                            <th>Nuevo Descuento</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($clasesEstudio AS $row=>$item) { 
                                $claseEncontrada=-1;
                                $porcentaje_guardado=0;
                                $cantidad=0;
                                $tipo="porcentaje";
                                foreach($clasesEstudioEmpresa as $rowc=>$itemc){
                                        if($itemc->id_clase==$item->id){
                                            $claseEncontrada=$rowc;
                                        }
                                }
                                if($claseEncontrada>-1){
                                    if($clasesEstudioEmpresa[$claseEncontrada]->tipo_descuento=="porcentaje"){
                                        $porcentaje_guardado=$clasesEstudioEmpresa[$claseEncontrada]->tipo_descuento.' = '.$clasesEstudioEmpresa[$claseEncontrada]->porcentaje_descuento. '%'; 
                                    }
                                    else{
                                        $porcentaje_guardado=$clasesEstudioEmpresa[$claseEncontrada]->tipo_descuento.' = $'.$clasesEstudioEmpresa[$claseEncontrada]->porcentaje_descuento; 
                                    }
                                    $cantidad=$clasesEstudioEmpresa[$claseEncontrada]->porcentaje_descuento;
                                    $tipo=$clasesEstudioEmpresa[$claseEncontrada]->tipo_descuento;
                                }
                                echo '<tr>
                                    <td>'.$item->nombre.'</td>
                                    <td>
                                        <select class="form-control" id="tipo_descuento_'.$row.'">
                                            <option value="porcentaje">Porcentaje</option>
                                            <option value="monto">Monto</option>
                                        </select>
                                        <script>document.getElementById("tipo_descuento_'.$row.'").value="'.$tipo.'"</script>
                                    </td>
                                    <td id="pguardado_'.$row.'">'.$porcentaje_guardado.'</td>
                                    <td><input type="number" class="form-control" id="porcentaje_'.$row.'" value="'.$cantidad.'"></td>
                                    <td><button class="btn btn-warning btn-sm mbtn_editar" data-fila="'.$row.'" data-id_clase="'.$item->id.'" data-tipo="'.$tipo.'" >Editar</td>
                                </tr>';
                            }
                        ?>
                    </tbody>
                </table>
                
            </div>  
        </div>  
      </div>
    </div>
  </div>
</div>