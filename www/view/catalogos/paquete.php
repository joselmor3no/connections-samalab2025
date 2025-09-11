<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-box nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="paquetes-y-perfiles" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i> Buscar</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $paquete[0]->id == "" ? "Alta" : "Modificación" ?> de Paquete o Perfil</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/catalogos/Paquete?opc=registro" method="post" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input type="hidden"  name="id_paquete" value="<?= $paquete[0]->id ?>">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="no">No.</label>
                                <div class="border-bottom w-100 pt-2 pb-1"><?= $paquete[0]->no_paquete != "" ? $paquete[0]->no_paquete : $consecutivo_paquete ?></div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="alias">Alias</label>
                                <input id="alias" type="text" class="form-control form-control-border text-uppercase"  name="alias" value="<?= $paquete[0]->alias ?>" placeholder="Alias" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Alias
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control form-control-border <?= $_SESSION["ruta"] != "alcala" ? "text-uppercase" : "" ?>"  name="nombre" value="<?= $paquete[0]->nombre ?>" placeholder="Nombre" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Nombre
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_tipo_reporte">Reporte</label>
                                <select class="form-control select2" id="id_tipo_reporte" name="id_tipo_reporte" style="width: 100%;">
                                    <option value="" <?= $paquete[0]->id_tipo_reporte == "" ? "selected" : "" ?>>&nbsp;</option>
                                    <option value="4" <?= $paquete[0]->id_tipo_reporte == "4" ? "selected" : "" ?>>REPORTE POR PAQUETE INDIVIDUAL</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="metodo">Método Utilizado</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="metodo" value="<?= $paquete[0]->metodo ?>" placeholder="Método Utilizado">
                            </div>
                        </div>



                        <table id="table_paquete" width="100%" class="table table-bordered table-hover" >
                            <thead align="center">
                                <tr>
                                    <th width="15%">Código</th>
                                    <th>Descripción</th>
                                    <th  width="15%">Paquete</th>
                                    <th  width="10%">Precio Público</th>
                                    <th  width="10%">Precio Neto</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $precio_publico = 0;
                                if (count($estudios_paquete) == 0) {
                                    for ($i = 1; $i <= 5; $i++) {
                                        ?>
                                        <tr id='est_<?= $i ?>' class="text-center">
                                            <td>
                                                <input type="text" class="form-control form-control-border text-uppercase codigo" data-id="<?= $i ?>"  name="codigo[]" value="" placeholder="Código" required="">
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="precio_publico"></td>
                                            <td class="precio_neto"></td>
                                            <td><button type='button' class='btn btn-sm <?= $i == 1 ? "btn-primary reset-estudio" : "btn-danger delete-estudio" ?> rounded-circle mt-1 mb-1' data-id='<?= $i ?>'><i class='fas <?= $i == 1 ? "fa-history" : "fa-trash" ?>'></i></button></td>
                                        </tr>
                                        <?php
                                    }
                                } else {

                                    $i = 1;
                                    foreach ($estudios_paquete AS $row) {
                                        ?>
                                        <tr id='est_<?= $i ?>' class="text-center">
                                            <td>
                                                <input type="text" class="form-control form-control-border text-uppercase codigo" data-id="<?= $i ?>"  name="codigo[]" value="<?= $row->alias ?>" disabled="" placeholder="Código" required="">
                                            </td>
                                            <td><?= $row->nombre_estudio ?></td>
                                            <td><?= $paquete[0]->alias ?></td>
                                            <td class="precio_publico"><?= number_format($row->precio_publico, 2) ?></td>
                                            <td class="precio_neto"><?= number_format($row->precio_neto, 2) ?></td>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="publico">Precio Público</label>
                                <div class="border-bottom w-100 pt-1 pb-2">$ <span  id="total_publico" ><?= number_format($precio_publico, 2) ?></span></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="precio">Precio Paquete</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text  font-weight-bold">$</span>
                                    </div>
                                    <input id="total" type="text" class="form-control form-control-border"  name="precio" value="<?= $paquete[0]->precio ?>" placeholder="Total" required="">
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Precio Paquete
                                    </div>                         
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 text-right mb-2">

                            <button type="button" class="btn btn-sm btn-success rounded-circle btn-add-paq"  data-toggle="tooltip" title="Agregar">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <div class="col-md-2 offset-md-5 pt-4 pb-2">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                        </div>


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
