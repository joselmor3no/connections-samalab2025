<?php 
    if(isset($_REQUEST['fecha_final']) && isset($_REQUEST['fecha_inicial'])){
        $fechaInicial=$_REQUEST['fecha_inicial'];
        $fechaFinal=$_REQUEST['fecha_final'];
    }
    else{
         $fechaInicial=Date("Y-m-d");
        $fechaFinal=Date("Y-m-d");
    }
?>
<!-- Content Wrapper. Contains page content -->
<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">
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
        <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
        <input type="hidden" id="img" name="img" value="<?= $sucursal->img ?>">
        <input type="hidden" id="sucursal" name="sucursal" value="<?= $sucursal->nombre ?>">
        <input type="hidden" id="telefono" name="telefono" value="<?= $sucursal->tel1 ?>">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <form action="estudios-dentales" method="post">
                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="card-title">Pacientes con estudios dentales</h3>
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
                <table id="dentales" class="table table-bordered table-hover ">
                    <thead>
                        <tr>
                            <th>No.Orden </th>
                            <th>Paciente</th>
                            <th>Fecha de registro</th>
                            <th>Expediente</th>
                            <th>Estudio</th>
                            <th>Carpetas | Asignar Estudio</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php 
                        //$imagenController->listaPacientesMedico($fechaInicial,$fechaFinal);
                            $imagenController->listaPacientesDental($fechaInicial,$fechaFinal,1);
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



