<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-file-alt nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Consulta rapida de status por sucursal</h3>
            </div>
            <div class="card-body">

                <form class="needs-validation" action="reporte-saldos" method="post" novalidate="">
                    <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $id_cliente ?>">
                    <div class="row">



                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="de">Fecha de</label>
                                <input type="date" class="form-control form-control-border"  name="ini" value="<?= $ini ?>" placeholder="De" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Fecha de
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="a">Fecha hasta</label>
                                <input type="date" class="form-control form-control-border"  name="fin" value="<?= $fin ?>" placeholder="Hasta" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Fecha hasta
                                </div>
                            </div>
                        </div>



                        <div class="col-md-2 mb-4">

                            <br>
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-cog pr-2"></i> Procesar</button>
                        </div>

                    </div>
                    <!-- /.row -->
                </form>

                <?php
                if (count($_POST) > 0) {
                    $reportes = new Reportes();

                    $corte = $reportes->getSucursales($id_cliente, $ini, $fin);
                    ?>

                    <input type="hidden" id="title" value="<?= "Reporte: ventas y saldos por sucursal" ?>">
                    <input type="hidden" id="text" value="<?= "Período: " . date("d/m/Y", strtotime($ini)) . " - " . date("d/m/Y", strtotime($fin)) ?>">

                    <h2 class="text-center">Reporte</h2>

                    <table id="dataTableReporte" class="table table-bordered table-hover ">
                        <thead>
                            <tr>
                                <th>Sucursal</th>
                                <th>Venta Neta</th>
                                <th>Credito</th>
                                <th style="text-align:center;">Contado</th>
                                <th  style="text-align:center;">Saldos deudores</th>
                                <th  style="text-align:center;">Cancelaciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($corte AS $row) {
                                $total = $row->costo_credito + $row->costo_contado;
                                ?>
                                <tr>
                                    <td style="text-align:center;"><?= $row->nombresuc ?></td>
                                    <td style="text-align:center; text-color:green;">$ <?= number_format($total, 2) ?></td>
                                    <td style="text-align:center;">$ <?= number_format($row->costo_credito, 2) ?></td>
                                    <td style="text-align:center;">$ <?= number_format($row->costo_contado, 2) ?></td>
                                    <td style="text-align:center;">$ <?= number_format($row->saldo_deudor, 2) ?></td>
                                    <td style="text-align:center;">$ <?= number_format($row->saldo_cancelado, 2) ?></td>

                                </tr>

                                <?php
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>


                    <?php
                }
                ?>

            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

