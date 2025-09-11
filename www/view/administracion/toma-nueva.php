<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1> <a href="toma-inventario" class=""><i class="fas fa-arrow-left pr-2"></i></a><i class="fas fa-list-ol  nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>

                <div class = "col-md-1 offset-md-5">
                    <button type="button" class="btn btn-primary rounded-circle print-diferencias"  title="Diferencias" data-toggle="modal">
                        <i class="fa fa-print"></i> 
                    </button>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> Nueva Toma</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation-toma" action="controller/administracion/InventarioC?opc=registro-toma" method="POST" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <table  id="table_registro" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Presentación</th>
                                <th>Existencia</th>
                                <th>Unidad</th>
                                <th>Conteo</th>
                                <th>Diferencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($datos AS $row) {
                                ?>
                                <tr id='est_<?= $i ?>' >
                                    <td class="codigo"><?= $row->consecutivo ?></td>
                                    <td><?= $row->nombre ?></td>
                                    <td><?= $row->presentacion . " (" . $row->unidad . ")" ?></td>
                                    <td class="existencia text-center"><?= $row->existencia ?></td>
                                    <td><?= $row->unidad_egreso ?></td>
                                    <td  width="10%"> <input type="text" class="form-control form-control-border text-center conteo tab" data-id="<?= $i ?>"  name="conteo[]" value="" placeholder="Conteo" required=""></td>
                                    <td class="diferencia text-center"></td>
                                </tr>


                                <?php
                                $i++;
                            }
                            ?>

                        </tbody>
                    </table>

                    <div class="col-md-4 offset-md-4 ">
                        <button id="save" class="btn btn-block bg-gradient-success"><i class="far fa-save  pr-2"></i> Guardar</button>
                    </div>

                </form>

            </div>

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->



<style>
    .col-form-label {
        font-weight: 400 !important;
        color: #060606;
        font-size: 16px;
    }

    .form-control{

        height: calc(1.75rem + 2px);
    }

    .table td, .table th {
        padding: 3px; 

    }


</style>

