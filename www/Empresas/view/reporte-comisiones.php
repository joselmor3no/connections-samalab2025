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
                <h3 class="card-title"></h3>
            </div>
            <div class="card-body">

                <form class="needs-validation" action="reporte-comisiones" method="post" novalidate="">
                    <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $id_cliente ?>">
                    <div class="row">

                        <div class="col-md-5">
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
                    
                        $corte = $reportes->ComisionesMedicas($id_sucursal, $ini, $fin);
                        ?>

                        <input type="hidden" id="title" value="<?= "Reporte: de comisiones medicas" ?>">
                        <input type="hidden" id="text" value="<?= "Período: " . date("d/m/Y", strtotime($ini)) . " - " . date("d/m/Y", strtotime($fin)) ?>">

                        <h2 class="text-center">Reporte</h2>

                        <table id="dataTableReporte" class="table table-bordered table-hover ">
                            <thead>
                                <tr>
                                    <th>Id Médico</th>
                                    <th>Nombre</th>
                                    <th style="text-align:center;">Venta</th>
                                    <th  style="text-align:center;">Saldo Deudor</th>
                                    <th  style="text-align:center;">Venta Total</th>
                                    <th  style="text-align:center;">% Comision</th>
                                    <th  style="text-align:center;">Comision</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($corte AS $row) {
                                      $real=$row->total-$row->deuda;
                                     $total =  $real *($row->porcentaje/100);
                                    
                                    ?>
                                    <tr>
                                        <td><?= $row->id?></td>
                                        <td><?= $row->nombre?></td>
                                        <td style="text-align:center;">$ <?= number_format($row->total,2)?></td>
                                        <td style="text-align:center; color:red;">$ <?= number_format($row->deuda,2)?></td>
                                        <td style="text-align:center; color:blue;">$ <?= number_format($real,2)?></td>
                                        <td style="text-align:center; "><?= $row->porcentaje?> %</td>
                                        <td style="text-align:center; color:green;">$ <?= number_format($total,2)?> </td>
                                        
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

