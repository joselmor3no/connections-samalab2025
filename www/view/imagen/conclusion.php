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
<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

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
                <form class="needs-validation" action="#" method="post" novalidate="">
                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="card-title">Lista de Resultados</h3>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label class="col-sm-4 col-form-label" for = "fecha_inicial">Fecha Incio</label>
                                <div class="col-md-8"> <input type = "date" class = "form-control form-control-border" name = "fecha_inicial" value="<?= $fechaInicial ?>" placeholder = "Fecha inicial"></div>
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label class="col-sm-4 col-form-label" for = "fecha_final">Fecha Final</label>
                                <div class="col-md-8"> <input type = "date" class = "form-control form-control-border" name = "fecha_final" value="<?= $fechaFinal ?>" placeholder = "Fecha final"></div>
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
                            <th>No.Orden </th>
                            <th>Nombre</th>
                            <th>Fecha Registro</th>
                            <th>Estudio</th>
                            <th>Médico</th>
                            <th>Eliminar Conclusion</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php 
                        //$imagenController->listaPacientesMedico($fechaInicial,$fechaFinal);

                            $imagenController->listaPacientesConcluidos($fechaInicial,$fechaFinal);
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



