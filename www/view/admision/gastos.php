<!-- Content Wrapper. Contains page content -->
<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="padding: 1px!important;">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-2 offset-md-2">
                    <a href="gastos" class="btn btn-block btn-info"><i class="fa fa-dollar-sign pr-2"></i> Gastos</a>
                </div>
                <div class="col-md-3">
                    <a href="corte" class="btn btn-block btn-outline-warning"><i class="fa fa-coins  pr-2"></i> Corte</a>
                </div>
                <div class="col-md-3">
                    <a href="caja" class="btn btn-block btn-outline-success"><i class="far fa-credit-card  pr-2"></i> Pagos</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">

            <div class="card-body">
                <form class="needs-validation" action="controller/admision/Caja?opc=registro-gasto" method="post" novalidate>
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="concepto">Concepto</label>
                                <input type="text" class="form-control form-control-border"  name="concepto" value="" placeholder="Concepto" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Concepto
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="importe">Cantidad</label>
                                <input  type="text" class="form-control form-control-border"  name="importe" value="" placeholder="Cantidad" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Cantidad
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="aclaracion">Aclaraciones</label>
                                <textarea class="form-control form-control-border"  name="aclaracion" value="" placeholder="Aclaraciones"></textarea>

                            </div>
                        </div>

                        <div class="col-md-2 ">
                            <button  class="btn btn-block bg-gradient-primary mt-4"><i class="fas fa-save  pr-2"></i> Guardar</button>
                        </div>
                    </div>

                </form>

                <table id="" class="table table-bordered table-hover dataTableCorte">
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Cantidad</th>
                            <th>Aclaraciones</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $i = 1;
                        foreach ($datos AS $row) {
                            ?>
                            <tr>
                                <td><?= $row->concepto ?></td>
                                <td><?= number_format($row->importe, 2) ?></td>
                                <td><?= $row->aclaracion ?></td>
                                <td><?= $row->fecha ?></td>
                            </tr>

                            <?php
                            $i++;
                        }
                        ?>

                    </tbody>
                </table>

            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->