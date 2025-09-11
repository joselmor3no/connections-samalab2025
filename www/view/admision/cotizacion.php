<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6 ">
                    <h1><i class="fa fa-calculator nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="registro-paciente" class="btn btn-block bg-gradient-success"><i class="far fa-handshake  pr-2"></i> Nueva</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <form class="needs-validation" action="cotizaciones" method="post" novalidate="">
                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="card-title">Lista de Resultados</h3>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label class="col-sm-4 col-form-label" for = "fecha_inicial">Fecha Incio</label>
                                <div class="col-md-8"> <input type = "date" class = "form-control form-control-border" name = "fecha_inicial" value="<?= $fecha_inicial ?>" placeholder = "Fecha inicial"></div>
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label class="col-sm-4 col-form-label" for = "fecha_final">Fecha Final</label>
                                <div class="col-md-8"> <input type = "date" class = "form-control form-control-border" name = "fecha_final" value="<?= $fecha_final ?>" placeholder = "Fecha final"></div>
                            </div>
                        </div> 

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fas fa-search pr-2"></i> Buscar</button>
                        </div>

                    </div>
                </form>

            </div><!--end card-header-->

            <div class="card-body">
                <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                <input type="hidden" id="img" name="img" value="<?= $sucursal->img ?>">
                <input type="hidden" id="sucursal" name="sucursal" value="<?= $sucursal->nombre ?>">
                <input type="hidden" id="telefono" name="telefono" value="<?= $sucursal->tel1 ?>">
                <table id="" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th>Costo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($datos as $row) {
                            ?>
                            <tr>
                                <td><?= $row->consecutivo ?></td>
                                <td><?= $row->fecha_orden ?></td>
                                <td><?= $row->paciente ?></td>
                                <td>$ <?= number_format($row->importe, 2) ?></td>
                                <td> 
                                    <div class="row">

                                        <a href="cotizacion/<?= $sucursal->impresion != 3 ? "cotizacion" : "cotizacion-media" ?>?codigo=<?= $row->consecutivo ?>" class="btn btn-sm btn-warning rounded-circle m-1" target="_blank" data-toggle="tooltip" title="Imprimir"> <i class="far fa-file-pdf"></i></a>
                                        <button class="btn btn-sm btn-success rounded-circle m-1 whatsapp" data-paciente="<?= $row->paciente ?>" data-telefono="<?= $row->telefono ?>" data-codigo="<?= $row->consecutivo ?>" data-expediente="<?= $row->expediente ?>" data-toggle="tooltip" title="WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </button>

                                        <button class="btn btn-sm btn-info rounded-circle m-1 mail" data-paciente="<?= $row->paciente ?>" data-correo="<?= $row->correo ?>" data-id="<?= $row->id ?>" data-expediente="<?= $row->expediente ?>" data-toggle="tooltip" title="Mail">
                                            <i class="far fa-envelope"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        <?php } ?>

                    </tbody>
                </table>

            </div>

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->



