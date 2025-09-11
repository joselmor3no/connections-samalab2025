<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-user-secret nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Busqueda de Paciente</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="bitacora" method="post" novalidate="">

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codigo">Orden</label>
                                <input type="text" class="form-control form-control-border" name="codigo" value="<?= $codigo ?>" placeholder="Orden" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Código
                                </div>
                            </div>
                        </div>


                        <div class="col-md-2 pt-4 pb-2">
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
        <?php
        if ($id_orden != "") {
            ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalle de Movimientos Orden No. "<?= $codigo ?>"</h3>
                </div>
                <div class="card-body">
                    <!--h3 class="card-title">Paciente "<?= $codigo ?>"</h3-->
                    <table id="" class="table table-bordered table-hover dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Concepto</th>
                                <th>Generales</th>
                                <th>Estudios</th>
                                <th>Monto</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($datos AS $row) {
                                ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $row->concepto ?></td>
                                    <td><?= $row->generales ?></td>
                                    <td><?= $row->estudios ?></td>
                                    <td><?= number_format($row->monto, 2) ?></td>
                                    <td><?= $row->usuario ?></td>
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

            <?php
        }
        ?>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->