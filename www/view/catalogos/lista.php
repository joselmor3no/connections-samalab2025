<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-list-ol nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="listas-precios" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i> Buscar</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $lista[0]->id == "" ? "Alta" : "Modificación" ?> de Paquete o Perfil</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/catalogos/Lista?opc=registro" method="post" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input type="hidden" id="id_lista"  name="id_lista" value="<?= $lista[0]->id ?>">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="no">No.</label>
                                <div class="border-bottom w-100 pt-2 pb-1"><?= $lista[0]->no != "" ? $lista[0]->no : $consecutivo_lista ?></div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="alias">Alias</label>
                                <input id="alias" type="text" class="form-control form-control-border text-uppercase"  name="alias" value="<?= $lista[0]->alias ?>" placeholder="Alias" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Alias
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="nombre" value="<?= $lista[0]->nombre ?>" placeholder="Nombre" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Nombre
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <a  href="reporte-lista-precio?no=<?= $lista[0]->no ?>&tipo=pdf" target="_blank" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="pdf">
                                <i class="fas fa-file-pdf"></i>
                            </a>

                            <a  href="reporte-lista-precio?no=<?= $lista[0]->no ?>&tipo=excel" target="_blank" class="btn btn-sm btn-success rounded-circle m-1" data-toggle="tooltip" title="Excel">
                                <i class="fas fa-file-excel"></i>
                            </a>

                        </div>

                        <table id="table_lista" width="100%" class="table table-bordered table-hover" >
                            <thead align="center">
                                <tr>
                                    <th width="15%">Código</th>
                                    <th>Descripción</th>
                                    <th  width="10%">Paquete</th>
                                    <th  width="10%">Precio Público</th>
                                    <th  width="15%">Precio Neto</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($estudios_paquetes) == 0) {
                                    for ($i = 1; $i <= 5; $i++) {
                                        ?>
                                        <tr id='est_<?= $i ?>' class="text-center">
                                            <td>
                                                <input type="text" class="form-control form-control-border text-uppercase codigo" data-id="<?= $i ?>"  name="codigo[]" value="" placeholder="Código" required="">
                                            </td>
                                            <td></td>
                                            <td class="paquete"></td>
                                            <td class="precio_publico"></td>
                                            <td> <input type="text" class="form-control form-control-border precio_neto" data-id="<?= $i ?>"  name="precio_neto[]" value="" placeholder="Precio Neto" required=""></td>
                                            <td><button type='button' class='btn btn-sm <?= $i == 1 ? "btn-primary reset-estudio" : "btn-danger delete-estudio" ?> rounded-circle mt-1 mb-1' data-id='<?= $i ?>'><i class='fas <?= $i == 1 ? "fa-history" : "fa-trash" ?>'></i></button></td>
                                        </tr>
                                        <?php
                                    }
                                } else {

                                    $i = 1;
                                    foreach ($estudios_paquetes AS $row) {
                                        ?>
                                        <tr id='est_<?= $i ?>' class="text-center">
                                            <td>
                                                <input type="text" class="form-control form-control-border text-uppercase codigo" data-id="<?= $i ?>"  name="codigo[]" value="<?= $row->codigo ?>" disabled="" placeholder="Código" required="">
                                            </td>
                                            <td><?= $row->descripcion ?></td>
                                            <td class="paquete"><?= $row->tipo == "Paquete" ? $row->codigo : "" ?></td>
                                            <td class="precio_publico"><?= number_format($row->precio_publico, 2) ?></td>
                                            <td> <input type="text" class="form-control form-control-border precio_neto" data-id="<?= $i ?>"  name="precio_neto[]" value="<?= $row->precio_neto ?>" placeholder="Precio Neto" required=""></td>
                                            <td><button type='button' class='btn btn-sm <?= $i == 1 ? "btn-primary reset-estudio" : "btn-danger delete-estudio" ?> rounded-circle mt-1 mb-1' data-id='<?= $i ?>'><i class='fas <?= $i == 1 ? "fa-history" : "fa-trash" ?>'></i></button></td>
                                        </tr>
                                        <?php
                                        $precio_publico += $row->precio_publico;
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

                        <div class="col-md-12 text-right mb-2">
                            <button type="button" class="btn btn-sm btn-success rounded-circle btn-add-paq"  data-toggle="tooltip" title="Agregar">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        
                        <?php
                        if ($lista[0]->id == "" || $i < 50) {
                            ?>
                            <div class="col-md-2 offset-md-5 pt-4 pb-2">
                                <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                            </div>
                            <?php
                        }
                        ?>



                    </div>
                    <!-- /.row -->
                </form>


            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<style>
    .table td, .table th {
        padding: 0px; 

    }
    .ui-menu{
        overflow: auto;
        max-height: 200px;
    }

</style>
