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
                <h3 class="card-title"> Consulta rápida de Estudios Vendidos por sucusal</h3>
            </div>
            <div class="card-body">

                <form class="needs-validation" action="reporte-venta" method="post" novalidate="">
                    <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $id_cliente ?>">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_sucursal_origen">Sucursal</label>
                                <select class="form-control select2" id="id_sucursal_origen" name="id_sucursal" style="width: 100%;" required="">
                                    <option value="">Selecciona una Empresa - Sucursal</option>
                                    <?php
                                    foreach ($sucursales AS $row) {
                                        if ($row->activo == 1) {
                                            ?>
                                            <option value="<?= $row->id ?>" <?= $row->id == $id_sucursal ? "selected" : "" ?>>
                                                <?= $row->cliente . " - " . $row->nombre ?>
                                            </option>

                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Empresa - Sucursal
                                </div>
                            </div>
                        </div>

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

                    

                        <div class="col-md-2">

                            <br>
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-cog pr-2"></i> Procesar</button>
                        </div>

                        <div class="col-md-2">

                            <br>
                            <button type="button" class="btn btn-block bg-gradient-success"><i class="fas fa-list-ul pr-2"></i>Sucursales</button>
                        </div>

                    </div>
                    <!-- /.row -->
                </form>

                <?php
                if (count($_POST) > 0) {
                    $reportes = new Reportes();
                    
                        $corte = $reportes->getVenta($id_sucursal, $ini, $fin);
                        ?>

                        <input type="hidden" id="title" value="<?= "Reporte: Ventas por Estudio" ?>">
                        <input type="hidden" id="text" value="<?= "Período: " . date("d/m/Y", strtotime($ini)) . " - " . date("d/m/Y", strtotime($fin)) ?>">

                        <h2 class="text-center">Reporte</h2>

                        <table id="dataTableReporte" class="table table-bordered table-hover ">
                            <thead>
                                <tr>
                                    <th>Id Estudio</th>
                                    <th>Nombre</th>
                                    <th  style="text-align:center;">Venta</th>
                                    <th  style="text-align:center;">Importe Total</th>
                                    <th style="text-align:center;">Credito</th>
                                    <th style="text-align:center;">Importe Credito</th>
                                    <th  style="text-align:center;">Contado</th>
                                    <th style="text-align:center;">Importe Contado</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($corte AS $row) {
                                    $total=$row->costo_credito+$row->costo_contado;
                                    ?>
                                    <tr>
                                        <td><?= $row->no_estudio?></td>
                                        <td><?= $row->nombre_estudio?></td>
                                        <td style="text-align:center;"><?= $row->ventas?></td>
                                        <td style="text-align:center;">$ <?= number_format($total,2)?></td>
                                        <td style="text-align:center;"><?= $row->creditoSI?></td>
                                        <td style="text-align:center;">$ <?= number_format($row->costo_credito,2)?></td>
                                        <td style="text-align:center;"><?= $row->creditoNo?></td>
                                        <td style="text-align:center;">$ <?= number_format($row->costo_contado,2)?></td>
                                        
                                        
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

