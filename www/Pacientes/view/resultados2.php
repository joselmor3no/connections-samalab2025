<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-microscope nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <!--div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="doctor" class="btn btn-block bg-gradient-success"><i class="fa fa-user-md pr-2"></i> Nuevo</a>
                </div-->
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> Resultados de estudios</h3>
            </div>
            <div class="card-body">

                <div class="row mb-4">

                    <div class="col-md-2 offset-md-2 text-center">
                        <img class="img-fluid" style="width: 115px" src="../../images-sucursales/<?= $sucursal->img ?>" /> 
                    </div>

                    <div class="col-md-4 font-weight-bold text-center">
                        <?= $sucursal->cliente ?><br>
                        <?= $sucursal->nombre ?><br>
                        <?= $sucursal->direccion ?><br>
                        <?= $sucursal->tel1 . " / " . $sucursal->tel2 ?><br>
                    </div>

                </div>

                <div class="row ">
                    <div class="col-md-12 text-center">
                        <h4 class="font-weight-bold text-primary"> <?= $sucursal->paciente ?></h4>
                    </div>
                </div> 
                <div class="table-responsive">
                    <table id="" class="table table-bordered table-hover dataTable">
                        <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $sucursal->id ?>">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Estudios</th>
                                <th>Fecha</th>
                                <th>Resultado</th>
                                <th>Complemento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($ordenes as $row) {
                                $arrayDocumentos=explode(',',$row->documentos);
                                    $documentos=""; 
                                    for($j=0;$j<=count($arrayDocumentos);$j++){
                                        if($j>0)
                                            $documentos.='<br>';
                                        $documentos.='<a href="../../reportes/'.$_SESSION['ruta'].'/resultados/'.$row->id.'_'.strtoupper($arrayDocumentos[$j]).'.pdf" target="_blank"><u>'.$arrayDocumentos[$j].'</u></a>';
                                    }
                                // var_dump();
                                ?>
                                <tr>
                                    <td><?= $row->consecutivo ?></td>
                                    <td><?= $row->estudios ?></td>
                                    <td><?= $row->fecha_orden ?></td>

                                    <td> 
                                        <?php
                                        if ($row->reportado > 0) {
                                            ?>

                                            <button type="submit" class="btn btn-sm btn-danger rounded-circle m-1 imprimir" data-codigo="<?= $row->consecutivo ?>" data-expediente="<?= $row->expediente ?>" data-toggle="tooltip" title="Resultado">
                                                <i class="far fa-file-pdf"></i>
                                            </button>

                                            <?php
                                        } else {
                                            echo 'Pendiente';
                                        }
                                        ?>
                                    </td>
                                    <td> 
                                        <?php
                                        /*$estudios_pdf = explode(',', $row->documentos);
                                        foreach ($estudios_pdf as $pdf) {
                                            if ($pdf != "") {
                                                ?>
                                                <a target="_blank" href="/reportes/<?= $_SESSION["ruta"] . "/resultados/" . $pdf ?>" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="Complemento"><i class="far fa-file-pdf"></i></a>

                                                <?php
                                            }
                                        }*/
                                            echo $documentos;
                                        ?>
                                    </td>

                                </tr>
                            <?php } ?>


                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
