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
                <h3 class="card-title"> Reporte de Resultados</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="reporte-laboratorio" method="post" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codigo">Código / Nombre Paciente / Estudio</label>
                                <input type="text" class="form-control form-control-border text-uppercase" name="codigo"
                                       placeholder="Código/ Nombre Paciente / Estudio" required=""
                                       value="<?php echo $codigo != "" ? $codigo : ''; ?>">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Código/Paciente
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_inicial">Fecha Inicial</label>
                                <input type="date" class="form-control form-control-border" name="fecha_inicial"
                                       value="<?php echo $fecha_inicial != "" ? $fecha_inicial : date("Y-m-d"); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_final">Fecha Final</label>
                                <input type="date" class="form-control form-control-border" name="fecha_final"
                                       value="<?php echo $fecha_final != "" ? $fecha_final : date("Y-m-d"); ?>">
                            </div>
                        </div>


                        <div class=" col-md-2 pt-3">
                            <button type="submit" class="btn btn-block bg-gradient-success"><i
                                    class="fa fa-search pr-2"></i> Buscar</button>
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
                        <table id="" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Paciente</th>
                                    <th>Sexo</th>
                                    <th>Edad</th>
                                    <th>Expediente</th>
                                    <th>Observaciones</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($laboratorio["laboratorio"] as $row) { ?>

                                    <tr class="">
                                        <td> <?= $row->codigo ?></td>
                                        <td> <?= $row->paciente ?></td>
                                        <td> <?= $row->sexo != "Nino" ? $row->sexo : "NIÑO" ?> </td>
                                        <td> <?= $row->edad . " " . ($row->tipo_edad != "Anios" ? $row->tipo_edad : "AÑOS") ?></td>
                                        <td> <?= $row->expediente ?></td>
                                        <td> <?= $row->observaciones ?> </td>
                                        <td> <?= $row->fecha_orden ?> </td>
                                        <td>
                                            <div class="row">
                                                <form class="w-100 text-center" action="reporte-paciente" method="GET">

                                                    <input type="hidden" name="codigo" value="<?= $row->codigo ?>" class="d-none">
                                                    <button type="submit" class="btn btn-sm btn-primary rounded-circle m-1"
                                                            data-toggle="tooltip" title="Reportar">
                                                        <i class="fas fa-book-medical"></i>
                                                    </button>
                                                </form>


                                            </div>
                                        </td>

                                    </tr>


                                    <?php
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

<style type="text/css">
    .table td {
        padding: 0rem;
        vertical-align: middle;
        padding-left: 0.25rem;

    }


</style>
