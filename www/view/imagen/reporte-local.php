<?php 
    if(isset($_REQUEST['fecha_final']) && isset($_REQUEST['fecha_inicial'])){
        $fechaInicial=$_REQUEST['fecha_inicial'];
        $fechaFinal=$_REQUEST['fecha_final'];
    }
    else{
         $fechaInicial=Date("Y-m-d");
        $fechaFinal=Date("Y-m-d");
    }

    if(isset($_REQUEST['ft_id_orden']))
        $imagenController->ctrGuardaReporteLocal();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6 ">
                    <h1><i class="fa fa-calculator nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section> 

    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <form class="needs-validation" action="reporte-local" method="post">
                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="card-title">Lista de Resultados</h3>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label class="col-sm-4 col-form-label" for = "fecha_inicial">Fecha Inicio</label>
                                <div class="col-md-8"> <input type = "date" class = "form-control form-control-border" name = "fecha_inicial"  placeholder = "Fecha inicial" value="<?php echo $fechaInicial?>"></div>
                            </div>
                        </div> 

                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label class="col-sm-4 col-form-label" for = "fecha_final">Fecha Final</label>
                                <div class="col-md-8"> <input type = "date" class = "form-control form-control-border" name = "fecha_final"  placeholder = "Fecha final" value="<?php echo $fechaFinal ?>"></div>
                            </div>
                        </div> 

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fas fa-search pr-2"></i> Buscar</button>
                        </div>

                    </div>
                </form>

            </div><!--end card-header-->

            <div class="card-body">
                <table id="" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No.Orden </th>
                            <th>Nombre</th>
                            <th>Fecha y Hora</th>
                            <th>Estudio</th>
                            <th>Sucursal</th>
                            <th>Reportar</th>
                            <th>Reporte</th>
                        </tr>
                    </thead>
                    <tbody id="tabla_listaplocal">
                       <?php 
                            $imagenController->listaPacientesLocal($fechaInicial,$fechaFinal);
                        ?> 
                    </tbody>
                </table>

            </div>

        </div>
        <!-- /.card -->
 
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->




<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="modalReporteLocal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <form action="#" method="post">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Reportar el estudio</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body"> 
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="estudio">Estudio:</label>
                        <input type="text" class="form-control" id="ft_nombre_estudio" name="ft_nombre_estudio">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="formatop">Formato Precargado:</label>
                        <select name="formatop" id="formatop" class="form-control">
                            <?php 
                                $imagenController->listaFormatos();
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="nhojas">Numero de hojas:</label>
                        <select name="nhojas" id="nhojas" class="form-control">
                            <option value="1">1 hoja</option>
                            <option value="2">2 hojas</option>
                            <option value="3">3 hojas</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="ajustaFirma">Ajustar firma:</label>
                        <input type="hidden" name="ft_datos" id="ft_datos" value="15">
                        <input type="hidden" name="ft_firma" id="ft_firma" value="15">
                        <input type="hidden" name="ft_cuerpo" id="ft_cuerpo" value="15">
                        <input type="hidden" name="ft_id_cat_estudio" id="ft_id_cat_estudio" value="">
                        <input type="hidden" name="ft_id_paciente" id="ft_id_paciente" value="">
                        <input type="hidden" name="ft_id_orden" id="ft_id_orden" value="">
                        <input type="hidden" name="ft_id_dcm" id="ft_id_dcm" value="">
                        <input type="number" name="ft_ajustaFirma" step="1" class="form-control" id="ft_ajustaFirma"   value="5">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" style="height:70vh;">
                    <textarea  class="summernoteL" name="resultado" id="resultado" style="height: 60vh;" >

                    </textarea>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success">Guardar Reporte</button>
          </div>
        </div> 
    </form>
  </div>
</div>

