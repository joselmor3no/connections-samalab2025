<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">

                    <h1> <a href="inventario-salida" class=""><i class="fas fa-arrow-left pr-2"></i></a><i class="fas fa-list-ol  nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="toma-nueva" class="btn btn-block bg-gradient-success"><i class="fas fa-list-ol pr-2"></i> Nuevo</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> Consulta rápida de Toma de Inventario</h3>
            </div>
            <div class="card-body">
                <table id="" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($datos AS $row) {
                            ?>
                            <tr>
                                <td><?= $row->codigo ?></td>
                                <td><?= $row->fecha ?></td>
                                <td> 
                                    <div class="row">
                                        <a href="reporte-toma-inventario?codigo=<?= $row->codigo ?>" target="_blank" class="btn btn-sm btn-warning rounded-circle m-1" title="Inventario">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </div>
                                </td>

                            </tr>


                            <?php
                            $i++;
                        }
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

