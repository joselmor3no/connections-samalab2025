<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-list nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> Carga de documentos complementarios</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="documentos-complementarios" method="post" novalidate="">

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_sucursal">Codigo o Paciente</label>
                                
                                <input  list="opcionesPacientes" type="text" class="form-control"  required id="pacientes" />
                                <input type="hidden" class="form-control"  required id="id_orden" name="id_orden" />
                                <datalist id="opcionesPacientes">
                                    
                                </datalist>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="fecha_inicial">Fecha Inicial</label>
                                <input type="date" class="form-control form-control-border" name="fecha_inicial"
                                       value="<?php echo $fecha_inicial != "" ? $fecha_inicial : date("Y-m-d"); ?>">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="fecha_final">Fecha Final</label>
                                <input type="date" class="form-control form-control-border" name="fecha_final"
                                       value="<?php echo $fecha_final != "" ? $fecha_final : date("Y-m-d"); ?>">
                            </div>
                        </div>


                        <div class=" col-md-2 pt-3">
                            <div class="form-group">
                                <label for="">&nbsp;</label>
                                <button type="submit" class="btn btn-block bg-gradient-success"><i
                                    class="fa fa-search pr-2"></i> Buscar</button>
                            </div>
                        </div>

                        <div class=" col-md-2 pt-3">
                            <div class="form-group">
                                <label for="">&nbsp;</label>
                                <button type="button" class="btn btn-block bg-gradient-info" data-toggle="modal" data-target="#modal-listac"><i
                                    class="fa fa-plus pr-2" ></i> Documentos</button>
                            </div>
                        </div>


                    </div>
                    <!-- /.row -->
                </form>

            </div>
            <!-- /.card-body --> 
        </div>
        <!-- /.card -->


        <!-- Default box -->
        <?php
        if (count($_POST) > 0) {
            ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Detalle de Búsqueda</h3>
                </div>
                <div class="card-body">
                    <div class="col-md-12 table-responsive">
                        <table id="tabla_complementarios" class="table dataTable w-100">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Paciente</th>
                                    <th>Expediente</th>
                                    <th>Estudio</th>
                                    <th>Fecha Registro</th>
                                    <th>Complementarios</th>
                                    <th>Documentos Cargados</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                foreach ($tablaPacientes as $row => $item) {
                                    $arrayDocumentos=explode(',',$item->documentos);
                                    $documentos=""; 
                                    for($j=0;$j<count($arrayDocumentos);$j++){
                                        if($arrayDocumentos[$j]!=""){
                                            $documentos.='<div class="row"><div class="col-md-5"><a href="../../reportes/'.$_SESSION['ruta'].'/resultados/'.$item->id.'_'.strtoupper($arrayDocumentos[$j]).'.pdf" target="_blank"><u>'.$arrayDocumentos[$j].'</u></a></div>
                                            <div class="col-md-6">
                                                <button  class="btn btn-danger btn-sm btn_elimina_complementario" data-id_estudio="'.$item->id_estudio.'" data-documento="'.$item->id_documento.'"  data-id_orden="'.$item->id.'" data-toggle="modal" data-target="#modal-eliminar_doc" >Eliminar</button>
                                            </div>
                                        </div>';
                                        }
                                            
                                        
                                    }

                                    echo '<tr>
                                        <td>'.$item->consecutivo.'</td>
                                        <td>'.$item->paciente.'</td>
                                        <td>'.$item->expediente.'</td>
                                        <td>'.$item->nombre_estudio.'</td>
                                        <td>'.$item->fecha_registro.'</td>
                                        <td>';
                                            echo '<select id="list'.$row.'" data-id_estudio="'.$item->id_estudio.'" data-id_orden="'.$item->id.'" class="form-control lcomplementarios"> 
                                                <option value="">Seleccione...</option>';

                                            foreach ($documentosComplementarios as $key => $value) {
                                                echo '<option value="'.$value->id.'">'.$value->nombre.'</option>';
                                            }
                                    echo '</select></td>
                                        <td>
                                        '.$documentos.'
                                        </td>
                                    </tr>';
                                }
                                ?>
                            </tbody> 
                        </table>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <!-- /.row -->

            </div>
        <?php } ?>


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper --> 

<!-- Modal para agregar un documento complementario -->
<div id="modal-subirdc" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <br>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="controller/laboratorio/Reporte?opc=guarda_complementario" method="post" enctype="multipart/form-data">
        <div class="modal-body">
            
                <div class="row">
                    <div class="col-12">
                    </div>
                    <div class="col-12 text-center">
                        <div class="form-group">
                            <label for="documento"> <div class="btn btn-secondary">Seleccionar Documento</div>
                                <input id="documento" type="file" name="documento" style="display:none;" accept="application/pdf">
                            </label>
                        </div>
                        <input type="hidden" name="ordenComplementario" id="ordenComplementario" >
                        <input type="hidden" name="estudioComplementario" id="estudioComplementario" >
                        <input type="hidden" name="idComplementario" id="idComplementario" >
                        <input type="hidden" name="nombreComplementario" id="nombreComplementario">
                    </div>
                    <div class="col-12" id="documentoCargado">
                        <span style="color:Red">Sin documento cargado...</span>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Eliminar</button>
            <button type="submit" class="btn btn-success">Subir Documento</button>
        </div>
    </form>
    </div>
  </div>
</div>

<!-- Modal para agregar un item a la lista de documentos -->
<div id="modal-listac" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <br>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

        <div class="modal-body">
            <div class="row">   
                <div class="col-8"> 
                    <div class="form-group">
                        <label for="">Nombre</label>
                        <input type="text" class="form-control" name="nombre_documento" id="nombre_documento">
                        <input type="hidden" name="id_documento" id="id_documento" value="-1">
                    </div>
                </div>  
                <div class="col-4"> 
                    <div class="form-group">
                        <label for="">&nbsp;</label>
                        <button id="btn_guarda_documento" class="btn btn-success form-control" >Guardar</button>
                    </div>
                </div>  

            </div>  
            <hr>    
                <table class="table table-tr dataTable tListaComplementarios"> 
                    <thead> 
                        <tr>
                            <th>Nombre del documento</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead> 
                        <?php 
                            foreach ($documentosComplementarios as $row => $item) {
                                echo '<tr>
                                        <td>'.$item->nombre.'</td>
                                        <td><button  class="btn btn-warning btn-sm btn-edita-documento" data-nombre="'.$item->nombre.'" data-id="'.$item->id.'">Editar</button></td>
                                        <td><button  class="btn btn-danger btn-sm btn-elimina-documento" data-id="'.$item->id.'">Eliminar</td>
                                      </tr>';
                            }
                         ?>
                    <tbody> 

                    </tbody>    
                </table>    
        </div>

    </div>
  </div>
</div>


<!-- Modal para eliminar un documento complementario -->
<div id="modal-eliminar_doc" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <br>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="controller/laboratorio/Reporte?opc=elimina_complementario_orden" method="post" >
        <div class="modal-body">
            
                <div class="row">
                    <div class="col-12">
                        <h4>¿Esta seguro de eliminar el documento complementario?</h4>
                    </div>
                    <div class="col-12 text-center">
                        <input type="hidden" name="ordenComplementario" id="eordenComplementario" >
                        <input type="hidden" name="estudioComplementario" id="eestudioComplementario" >
                    </div>
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Eliminar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
    </form>
    </div>
  </div>
</div>