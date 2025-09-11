<input type="hidden" id="msg" name="siglas" value="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-chart-line nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Reportes administrativos por sucursal</h3>

            </div>
            <div class="card-body">
                <form class="needs-validation" action="reporteador-pdf" method="post" novalidate="" target="_blank" >
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_inicial">Fecha Inicial</label>
                                <input type="date" class="form-control form-control-border" name="fecha_inicial" id="fecha_inicial" value="<?= date("Y-m-d"); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_final">Fecha Final</label>
                                <input type="date" class="form-control form-control-border" name="fecha_final" id="fecha_final" value="<?= date("Y-m-d"); ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="palabra">Búsqueda</label>
                                <input type="text" class="form-control form-control-border text-uppercase" name="palabra" id="palabra" placeholder="Búsqueda" value="" />
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Búsqueda
                                </div>
                            </div>
                        </div>


                        <!--div class=" col-md-2 pt-3">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i
                                    class="fa fa-cog pr-2"></i> Procesar</button>
                        </div-->

                        <div class="col-md-12">
                            <div class="card card-success card-tabs">
                                <div class="card-header p-0 pt-1">
                                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-one-r1-tab" data-toggle="pill" href="#custom-tabs-one-r1" role="tab" aria-controls="custom-tabs-one-r1" aria-selected="true">Empresas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-r2-tab" data-toggle="pill" href="#custom-tabs-one-r2" role="tab" aria-controls="custom-tabs-one-r2" aria-selected="false">Doctores</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-r3-tab" data-toggle="pill" href="#custom-tabs-one-r3" role="tab" aria-controls="custom-tabs-one-r3" aria-selected="false">Departamentos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-r4-tab" data-toggle="pill" href="#custom-tabs-one-r4" role="tab" aria-controls="custom-tabs-one-r4" aria-selected="false">Estudios</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-r5-tab" data-toggle="pill" href="#custom-tabs-one-r5" role="tab" aria-controls="custom-tabs-one-r5" aria-selected="false">Administración</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-r6-tab" data-toggle="pill" href="#custom-tabs-one-r6" role="tab" aria-controls="custom-tabs-one-r6" aria-selected="false">Listas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-r7-tab" data-toggle="pill" href="#custom-tabs-one-r7" role="tab" aria-controls="custom-tabs-one-r7" aria-selected="false">Facturación</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-r8-tab" data-toggle="pill" href="#custom-tabs-one-r8" role="tab" aria-controls="custom-tabs-one-r8" aria-selected="false">Movimientos</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-one-tabContent">

                                        <div class="tab-pane fade active show" id="custom-tabs-one-r1" role="tabpanel" aria-labelledby="custom-tabs-one-r1-tab">
                                            <div class="row">
                                                <?php
                                                

                                                foreach ($permisos AS $row) {
                            
                                                    if ($row->seccion == "Reportes/Empresas") {
                                                        ?>
                                                        <div class="col-md-4 border-primary mb-2 ">

                                                            <button type="submit" class="btn btn-block bg-gradient-primary" name="siglas" value="<?= $row->siglas ?>"  data-toggle="tooltip" title="<?= $row->tooltip ?>">
                                                                <i class="fa fa-file-pdf pr-2"></i> <?= $row->descripcion ?>
                                                            </button>

                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>  
                                            </div>
                                        </div>


                                        <div class="tab-pane fade" id="custom-tabs-one-r2" role="tabpanel" aria-labelledby="custom-tabs-one-r2-tab">
                                            <div class="row">
                                                <?php
                                                foreach ($permisos AS $row) {
                                                    if ($row->seccion == "Reportes/Doctores") {
                                                        ?>
                                                        <div class="col-md-4 border-primary mb-2 ">

                                                            <button type="submit" tar class="btn btn-block bg-gradient-primary" name="siglas" value="<?= $row->siglas ?>" data-toggle="tooltip" title="<?= $row->tooltip ?>">
                                                                <i class="fa fa-file-pdf pr-2"></i> <?= $row->descripcion ?>
                                                            </button>

                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>  
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-r3" role="tabpanel" aria-labelledby="custom-tabs-one-r3-tab">
                                            <div class="row">
                                                <?php
                                                foreach ($permisos AS $row) {
                                                    if ($row->seccion == "Reportes/Departamentos") {
                                                        ?>
                                                        <div class="col-md-4 border-primary mb-2 ">

                                                            <button type="submit" class="btn btn-block bg-gradient-primary" name="siglas" value="<?= $row->siglas ?>" data-toggle="tooltip" title="<?= $row->tooltip ?>">
                                                                <i class="fa fa-file-pdf pr-2"></i> <?= $row->descripcion ?>
                                                            </button>

                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>  
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-r4" role="tabpanel" aria-labelledby="custom-tabs-one-r4-tab">
                                            <div class="row">
                                                <?php
                                                foreach ($permisos AS $row) {
                                                    if ($row->seccion == "Reportes/Estudios") {
                                                        ?>
                                                        <div class="col-md-4 border-primary mb-2 ">

                                                            <button type="submit" class="btn btn-block bg-gradient-primary" name="siglas" value="<?= $row->siglas ?>" data-toggle="tooltip" title="<?= $row->tooltip ?>">
                                                                <i class="fa fa-file-pdf pr-2"></i> <?= $row->descripcion ?>
                                                            </button>

                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>  

                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-r5" role="tabpanel" aria-labelledby="custom-tabs-one-r5-tab">
                                            <div class="row">
                                                <?php
                                                foreach ($permisos AS $row) {
                                                    if ($row->seccion == "Reportes/Administración") {
                                                        ?>
                                                        <div class="col-md-4 border-primary mb-2 ">
                                                            <?php  if($row->global==0){ ?>
                                                                <button type="submit" class="btn btn-block bg-gradient-primary" name="siglas" value="<?= $row->siglas ?>" data-toggle="tooltip" title="<?= $row->tooltip ?>">
                                                                    <i class="fa fa-file-pdf pr-2"></i> <?= $row->descripcion ?>
                                                                </button>
                                                            <?php }else if($row->global==1){?>
                                                                <button type="button" class="btn btn-block bg-gradient-warning btn-global" data-toggle="modal" data-target="#modal-global" title="<?= $row->tooltip ?>">
                                                                    <i class="fa fa-file-pdf pr-2"></i> <?= $row->descripcion ?>
                                                                </button>
                                                            <?php }else if($row->global==2){?>
                                                                <button type="button" class="btn btn-block bg-gradient-warning btn-global" data-toggle="modal" data-target="#modal-global-caja" title="<?= $row->tooltip ?>">
                                                                    <i class="fa fa-file-pdf pr-2"></i> <?= $row->descripcion ?>
                                                                </button>
                                                            <?php }else if($row->global==3){?>
                                                                <button type="button" class="btn btn-block bg-gradient-warning btn-global" data-toggle="modal" data-target="#modal-global-pacientes" title="<?= $row->tooltip ?>">
                                                                    <i class="fa fa-file-pdf pr-2"></i> <?= $row->descripcion ?>
                                                                </button>
                                                            <?php } ?>
                                                        </div>
                                                        <?php 
                                                    }
                                                }
                                                ?>  

                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-r6" role="tabpanel" aria-labelledby="custom-tabs-one-r6-tab">
                                            <div class="row">
                                                <?php
                                                foreach ($permisos AS $row) {
                                                    if ($row->seccion == "Reportes/Listas") {
                                                        ?>
                                                        <div class="col-md-4 border-primary mb-2 ">

                                                            <button type="submit" class="btn btn-block bg-gradient-primary" name="siglas" value="<?= $row->siglas ?>" data-toggle="tooltip" title="<?= $row->tooltip ?>">
                                                                <i class="fa fa-file-pdf pr-2"></i> <?= $row->descripcion ?>
                                                            </button>

                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>  
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-r7" role="tabpanel" aria-labelledby="custom-tabs-one-r7-tab">
                                            <div class="row">
                                                <?php
                                                foreach ($permisos AS $row) {
                                                    if ($row->seccion == "Reportes/Facturación") {
                                                        ?>
                                                        <div class="col-md-4 border-primary mb-2 ">

                                                            <button type="button" class="btn btn-block bg-gradient-primary btn_facturacion" data-desc="<?= $row->descripcion ?>" name="siglas" data-toggle="modal" data-target="#modal-facturacion" title="<?= $row->tooltip ?>">
                                                                <i class="fa fa-file-pdf pr-2"></i> <?= $row->descripcion ?>
                                                            </button>

                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>  
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-r8" role="tabpanel" aria-labelledby="custom-tabs-one-r8-tab">
                                            <div class="row">
                                                <?php
                                                foreach ($permisos AS $row) {
                                                    if ($row->seccion == "Reportes/Movimientos") {
                                                        ?>
                                                        <div class="col-md-4 border-primary mb-2 ">

                                                            <button type="submit" class="btn btn-block bg-gradient-primary" name="siglas" value="<?= $row->siglas ?>" data-toggle="tooltip" title="<?= $row->tooltip ?>">
                                                                <i class="fa fa-file-pdf pr-2"></i> <?= $row->descripcion ?>
                                                            </button>

                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>  
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>


                    </div>
                    <!-- /.row -->
                </form>

            </div>

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal para el Reporte Global -->
<div class="modal fade"  id="modal-global" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Reporte General Global</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-global" method="POST">

            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="institucion">Institución:</label>
                        <select name="institucion" id="institucion" class="form-control">
                            <option value="-1">Seleccione...</option>
                            <?php 
                                foreach ($listaEmpresa AS $row) {
                                    echo '<option value="'.$row->id.'">'.$row->nombre.'</option>';
                                }

                             ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="usuario">Usuario:</label>
                        <select name="usuario" id="usuario" class="form-control">
                            <option value="-1">Seleccione...</option>
                            <?php 
                                foreach ($listaUsuario AS $row) {
                                    echo '<option value="'.$row->id.'">'.$row->nombre.'</option>';
                                }

                             ?>

                            
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="medico">Médico:</label>
                        <select name="medico" id="medico" class="form-control">
                            <option value="-1">Seleccione...</option>
                            <?php 
                                foreach ($listaDoctor AS $row) {
                                    echo '<option value="'.$row->id.'">'.$row->nombre.'</option>';
                                }

                             ?>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="fecha_inicialg">Fecha Inicial</label>
                        <input type="date" class="form-control" name="fecha_inicial" id="fecha_inicialg">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="fecha_inicialg">Fecha Final</label>
                        <input type="date" class="form-control" name="fecha_final" id="fecha_finalg">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="estatus">Estatus:</label>
                        <select name="estatus" id="estatus" class="form-control">
                            <option value="-1">Seleccione...</option>
                            <option value="1">vigente</option>
                            <option value="2">cancelado</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="tipo_pago">Tipo de pago:</label>
                        <select name="tipo_pago" id="tipo_pago" class="form-control">
                            <option value="-1">Seleccione...</option>
                            <?php 
                                foreach ($listaFormaP AS $row) {
                                    echo '<option value="'.$row->descripcion.'">'.$row->descripcion.'</option>';
                                }

                             ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <br>
                        <button id="btn-generarRG" class="btn btn-success" style="width:100%;">Generar</button>
                    </div>
                    
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-12" style="overflow-x:scroll;">
                <table id="example1" class="table table-striped" style="width:100%;font-size:12px;">
                  <thead>
                        <th>#</th>
                        <th>Status</th>
                        <th>Folio</th>
                        <th>Institución</th>
                        <th>Condiciones</th>
                        <th>Descuento Crédito</th>
                        <th>Paciente</th>
                        <th>Telefono</th>
                        <th>Correo</th>
                        <th>Alias Medico</th>
                        <th>Medico</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Usuario</th>
                        <th>Codigo</th>
                        <th>Estudio</th>
                        <th>Precio publico</th>
                        <th>Tipo Descuento Crédito</th>
                        <th>Descuento Crédito</th>
                        <th>Precio Descuento Crédito</th>
                        <th>Nombre Descuento Recepción</th>
                        <th>Descuento Recepcion</th>
                        <th>Precio neto estudio</th>
                        <th>Total Orden</th>
                        <th>A cuenta(orden)</th>
                        <th>Resta</th>
                        <th>Pago Efectivo</th>
                        <th>Fechas pago</th>
                        <th>Pago Tarjeta</th> 
                        <th>Fechas pago</th>
                        <th>Pago Otro</th> 
                        <th>Fechas pago</th>
                        <th>Total Pagado</th>
                        <th>Sección</th>
                        <th>Paquete</th>
                        <th>Referencia</th>
                        <th>Tipo Referencia</th>
                        <th>Tipo Cliente</th>
                        <th>Departamento</th>
                  </thead>
                  <tbody id="tabla_reporteG">
                     
                  </tbody>
              </table>
            </div>
        </div>
      </div>
      
    </div>
  </div>
</div>


<!-- Modal para el Reporte de caja -->
<div class="modal fade"  id="modal-global-caja" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl " >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Reporte General Caja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-global-c" method="POST">

            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="usuarioc">Usuario:</label>
                        <select name="usuario" id="usuarioc" class="form-control">
                            <option value="-1">Seleccione...</option>
                            <?php 
                                foreach ($listaUsuario AS $row) {
                                    echo '<option value="'.$row->id.'">'.$row->nombre.'</option>';
                                }

                             ?>                            
                        </select>
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group">
                        <label for="fecha_inicialgc">Fecha Inicial</label>
                        <input type="date" class="form-control" name="fecha_inicial" id="fecha_inicialgc">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="fecha_inicialgc">Fecha Final</label>
                        <input type="date" class="form-control" name="fecha_final" id="fecha_finalgc">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <br>
                        <button id="btn-generarRGC" class="btn btn-success" style="width:100%;">Generar</button>
                    </div>
                    
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-12" style="overflow-x:scroll;">
                <table id="example2" class="table table-striped" style="width:100%;font-size:12px;">
                  <thead>
                        <th>#</th>
                        <th>Sucursal</th>
                        <th>Fecha</th>
                        <th>Folio</th>
                        <th>Institución</th>
                        <th>Nombre del Paciente</th>
                        <th>Importe</th>
                        <th>Pago</th>
                        <th>Fecha Pago</th>
                        <th>Otros pagos</th>
                        <th>Cubierto</th>
                        <th>Adeudo</th>
                        <th>Tipo de pago</th> 
                        <th>Estatus</th>
                        <th>Usuario</th>
                  </thead>
                  <tbody id="tabla_reporteGC">
                      <?php 
                        echo $cajaR;
                       ?>
                  </tbody>
              </table>
            </div>
        </div>
      </div>
      
    </div>
  </div>
</div>


<!-- Modal para el Reporte de paciente -->
<div class="modal fade"  id="modal-global-pacientes" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl " >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Reporte General Pacientes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-global-p" method="POST">

            <div class="row">
                <!-- <div class="col-4">
                    <div class="form-group">
                        <label for="usuarioc">Usuario:</label>
                        <select name="usuario" id="usuarioc" class="form-control">
                            <option value="-1">Seleccione...</option>
                            <?php 
                                foreach ($listaUsuario AS $row) {
                                    echo '<option value="'.$row->id.'">'.$row->nombre.'</option>';
                                }

                             ?>                            
                        </select>
                    </div>
                </div> -->

                <div class="col-3">
                    <div class="form-group">
                        <label for="fecha_inicialgc">Fecha Inicial</label>
                        <input type="date" class="form-control" name="fecha_inicial" id="fecha_inicialgc" value="<?= date("Y-m-d"); ?>">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="fecha_inicialgc">Fecha Final</label>
                        <input type="date" class="form-control" name="fecha_final" id="fecha_finalgc" value="<?= date("Y-m-d"); ?>">
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-group">
                        <label for="fecha_inicialgc">Estudio</label>
                        <select class="form-control" name="id_estudio" id="id_estudio">
                            <option value="">Seleccione...</option>
                            <?php
                                foreach($listaEstudios as $row=>$item){
                                    echo '<option value="'.$item->id.'">'.$item->nombre_estudio.' ('.$item->no_estudio.')</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="col-2">
                    <div class="form-group">
                        <br>
                        <button id="btn-generarPaciente" class="btn btn-success" style="width:100%;">Generar</button>
                    </div>
                    
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-12" style="overflow-x:scroll;">
                <table id="example3" class="table table-striped" style="width:100%;font-size:12px;">
                  <thead>
                        <th>#</th>
                        <th>Folio</th>
                        <th>Fecha de registro</th>
                        <th>Nombre del Paciente</th>
                        <th>Expediente</th>
                        <th>Fecha de nacimiento</th>
                        <th>Telefono</th>
                  </thead>
                  <tbody id="tabla_reportePaciente">
                      
                  </tbody>
              </table>
            </div>
        </div>
      </div>
      
    </div>
  </div>
</div>




<!-- Modal para el Reporte de facturacion -->
<div class="modal fade bd-example-modal-xl"  id="modal-facturacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl"  role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Reporte Facturación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="row">
            <div class="col-12" style="overflow-x:scroll;">
                <table id="tb_facturacion" class="table table-striped" style="width:100%;font-size:12px;">
                  <thead>
                        <th>#</th>
                        <th>Folio</th>
                        <th>Orden</th>
                        <th>Fecha</th>
                        <th>Nombre Fiscal</th>
                        <th>RFC</th>
                        <th>UUID</th>
                        <th>Subtotal</th>
                        <th>IVA</th>
                        <th>Total</th>
                        <th>Forma Pago</th>
                  </thead>
                  <tbody id="btb_Facturacion">
                      <?php 
                        
                       ?>
                  </tbody>
              </table>
            </div>
        </div>
      </div>
      
    </div>
  </div>
</div>