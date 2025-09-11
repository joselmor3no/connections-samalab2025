<!-- Content Wrapper. Contains page content -->
<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="padding: 1px!important;">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-3 offset-md-2">
                    <a href="gastos" class="btn btn-block btn-outline-info"><i class="fa fa-dollar-sign pr-2"></i>
                        Gastos</a>
                </div>
                <div class="col-md-2">
                    <a href="corte" class="btn btn-block btn-warning"><i class="fa fa-coins  pr-2"></i> Corte</a>
                </div>
                <div class="col-md-3">
                    <a href="caja" class="btn btn-block btn-outline-success"><i class="far fa-credit-card  pr-2"></i>
                        Pagos</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 offset-md-6">
                        <a class="btn btn-block btn-outline-dark" data-toggle="collapse" href="#datosingresos"><i
                                class="far fa-credit-card  pr-2"></i> Detalles de Ingreso</a>
                    </div>

                    <div class="col-md-3">
                        <a class="btn btn-block btn-outline-dark" data-toggle="collapse" href="#datosegresos"><i
                                class="far fa-credit-card  pr-2"></i> Detalles de Egresos</a>
                    </div>

                </div>
                <form class="needs-validation" action="controller/admision/Caja?opc=corte" method="post" novalidate>

                    <input type="hidden"  name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input type="hidden" name="egresos" value="<?= $egresos ?>">
                    <input type="hidden" name="ingresos" value="<?= $ingresos ?>">
                    <input type="hidden"  name="no_corte" value="<?= $datos_corte[0]->no_corte ?>">

                    <div class="row mt-4">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="no_corte">Corte No.</label>
                                <div id="no_corte" class="border-bottom w-100 pt-1 pb-2">
                                    <?= $datos_corte[0]->no_corte ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ingresos">Ingresos del día ($)</label>
                                <div id="ingresos" class="border-bottom w-100 pt-1 pb-2">
                                    <?= number_format($ingresos, 2) ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="gastos">Gastos del día ($)</label>
                                <div id="gastos" class="border-bottom w-100 pt-1 pb-2"><?= number_format($egresos, 2) ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="codigo">Total en Caja ($)</label>
                                <div class="border-bottom w-100 pt-1 pb-2"><?= number_format($ingresos - $egresos, 2) ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 ">
                            <button class="btn btn-block bg-gradient-primary mt-4"><i class="fas fa-save  pr-2"></i>
                                Guardar</button>
                        </div>


                    </div>
                </form>

                <div class="row mb-4 mt-2 collapse multi-collapse" id="datosingresos">

                    <div class="col-md-12">
                        <h1 class="text-center h4 text-primary">Detalles de Ingreso</h1>
                        <table id="" class="table table-bordered table-hover dataTableCorte">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Paciente</th>
                                    <th>Pago</th>
                                    <th>Forma de Pago</th>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos_ingresos as $row) {
                                    ?>
                                    <tr>
                                        <td><?= $row->codigo ?></td>
                                        <td><?= $row->paciente ?></td>
                                        <td><?= number_format($row->pago, 2) ?></td>
                                        <td><?= $row->forma_pago ?></td>
                                        <td><?= $row->usuario ?></td>
                                        <td><?= $row->fecha ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mb-4 mt-2 collapse multi-collapse" id="datosegresos">
                    <div class="col-md-12">
                        <h1 class="text-center h4 text-primary">Detalles de Egresos</h1>
                        <table id="" class="table table-bordered table-hover dataTableCorte">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Cantidad</th>
                                    <th>Observación</th>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($datos_egresos as $datos) {
                                    ?>
                                    <tr>
                                        <td><?= $datos->concepto ?></td>
                                        <td><?= number_format($datos->importe, 2) ?></td>
                                        <td><?= $datos->aclaracion ?></td>
                                        <td><?= $datos->usuario ?></td>
                                        <td><?= $datos->fecha ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->