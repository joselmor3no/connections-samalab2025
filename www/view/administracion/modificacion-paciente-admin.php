<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="padding: 1px!important;">
        <div class="container-fluid">
            <!-- Default box -->
            <div class="card mt-2">
                <div class="card-header">

                    <form class="needs-validation-codigo" action="modificacion-paciente-admin" method="post" novalidate="">

                        <div class="row">

                            <div class="col-md-3">
                                <h3 class="card-title">Búsqueda de Paciente (Usuario Maestro)</h3>
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

                    <form  action="controller/admision/Paciente?opc=modificacion_maestro" method="post" novalidate>
                        <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                        <input type="hidden" name="consecutivo" value="<?= $codigo ?>">
                        <input type="hidden" id="id_orden" name="id_orden" value="<?= $id_orden ?>">
                        <input type="hidden" id="id_paciente" name="id_paciente" value="<?= $orden->id_paciente ?>">
                        <div class="row">

                            <div class = "col-md-4">
                                <div class = "form-group row m-0">
                                    <label class="col-md-3 col-form-label" for = "paterno">Paciente</label>
                                    <div class="col-md-9"> <input  type = "text" class = "form-control form-control-border"  value="<?= $orden->nombre.' '.$orden->paterno.' '.$orden->materno ?>" readonly></div>

                                </div>
                            </div>

                            <div class = "col-md-4">
                                <div class="row">
                                    <div class = "col-md-12">
                                    <div class = "form-group row m-0">
                                            <label class="col-md-2 col-form-label" for = "doctor">Doctor</label>
                                            <div class="col-md-9 mb-2"> 
                                                <select class="form-control h-100" name="doctor" id="doctor">
                                                <?php       
                                                                                         
                                                    foreach ($lista_doctores as $row => $item) {
                                                        echo '<option value='.$item->id.'>'.$item->nombre.' '.$item->apaterno.' '.$item->amaterno.' - '.$item->alias.'</option>';
                                                    }
                                                ?>
                                                </select>
                                                <?php  echo '<script>document.getElementById("doctor").value="'.$orden->id_doctor.'"</script>'; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class = "col-md-4">
                                <div class="row">
                                    <div class = "col-md-12">
                                        <div class = "form-group row m-0">
                                            <label class="col-md-2 col-form-label" for = "empresa">Empresa</label>
                                            <div class="col-md-9 mb-2"> 
                                                <select class="form-control h-100" name="empresa" id="empresa">
                                                    <option value="null">Seleccione...</option>
                                                <?php                                                 
                                                    foreach ($lista_empresas as $row => $item) {
                                                        echo '<option value='.$item->id.'>'.$item->nombre.'</option>';
                                                    }
                                                ?>
                                                </select>
                                                <?php  echo '<script>document.getElementById("empresa").value="'.$orden->empresa_id.'"</script>'; ?>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>

         

                        </div>

        

        

                        <div class="row mt-4">
                            <div class="col-md-4 offset-md-4 ">
                                <button id="save" class="btn btn-block bg-gradient-success" <?= $orden->cancelado == 1 ? "disabled" : "" ?>><i class="far fa-save  pr-2"></i> Actualizar Datos del Paciente</button>
                            </div>

                        </div>
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

