<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-clipboard-list nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="estudios" class="btn btn-block bg-gradient-dark"><i class="fa fa-arrow-left  pr-2"></i> Regresar</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Nuevos Estudios</h3>
            </div>
            <div class="card-body">
                <table id="" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Alias</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($datos AS $row) {
                            ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $row->no_estudio ?></td>
                                <td><?= $row->nombre_estudio ?></td>
                                <td><?= $row->alias ?></td>
                                <td> 
                                    <div class="row">
                                        <form class="pr-2" action="estudio"  method="POST">
                                            <input type="hidden" name="id" value="<?= $row->id ?>" class="d-none">
                                            <button type="submit" class="btn btn-sm btn-primary rounded-circle m-1"  data-toggle="tooltip" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </form>
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

