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

                <form class="needs-validation" action="etiquetas-estudios" method="post" novalidate="">

                    <div class="row">

                        <div class="col-md-3">
                            <h3 class="card-title">Búsqueda de Paciente</h3>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label class="col-md-4 col-form-label" for = "codigo">Código</label>
                                <div class="col-md-8"> 
                                    <input type="text" class="form-control form-control-border" name="codigo" value="<?= $codigo ?>" placeholder="Código" required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Código
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i> Buscar</button>
                        </div>
                        <?php
                        if ($id_orden != "") {
                            ?>
                            <div class="col-md-2 offset-md-2">
                                <button type="button" class="btn btn-block btn-outline-primary btn-imprimir-etiquetas"><i class="fa fa-file-pdf  pr-2"></i> Imprimir todo</button>
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                    <!-- /.row -->
                </form>
            </div>

            <!-- /.card-header -->
        </div>
        <!-- /.card -->

        <?php
        if ($id_orden != "") {
            ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Etiquetas para Orden No. "<?= $codigo ?>"</h3>
                </div>
                <div class="card-body">

                    <table id="" class="table table-bordered table-hover dataTableEtiquetas">
                        <input id="id_sucursal" type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                        <input id="consecutivo" type="hidden" name="consecutivo" value="<?= $codigo ?>">
                        <thead>
                            <tr>
                                <th>Estudio</th>
                                <th>Tipo de Tubo</th>
                                <th>Alias</th>
                                <th>Acciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($estudios AS $row) {
                                ?>
                                <tr>
                                    <td><?= $row->paquete == "" ? $row->nombre_estudio : $row->paquete . " | " . $row->nombre_estudio ?></td>
                                    <td><?= $row->recipiente ?></td>
                                    <td>
                                        <button data-alias="<?= $row->alias ?>" class="btn btn-link estudio"> 
                                            <?= $row->paquete == "" ? $row->alias : $row->paquete_alias ?>
                                        </button>
                                    </td>
                                    <td> 
                                        <div class="row">
                                            <button class="btn btn-sm btn-info rounded-circle m-1 imprimir-etiqueta" data-alias="<?= $row->alias ?>" data-nombre="<?= $row->nombre_estudio ?>" data-toggle="tooltip" title="Imprimir">
                                                <i class="far fa-file-pdf"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody> 
                    </table>

                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-2 pt-4 pb-2">
                            <button type="button" class="btn btn-block bg-gradient-info imprimir-varias-etiquetas"><i class="fa fa-file-pdf pr-2"></i> Imprimir</button>
                        </div>

                        <div class="col-md-4 pt-4 pb-2">
                            <input id="estudios" type="text" class="form-control form-control-border" name="estudios" placeholder="" required="" >
                            <div class="invalid-feedback">
                                Favor de capturar el campo Código
                            </div>

                        </div>
                    </div>

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