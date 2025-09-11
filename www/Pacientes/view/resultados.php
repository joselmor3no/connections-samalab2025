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

                    <?php
                    if ($_SESSION["ruta"] == "provida") {
                        ?>
                        <div class="col-md-2 offset-md-2 text-center">
                            <img class="img-fluid" style="width: 115px" src="../../images-sucursales/<?= $sucursal->img ?>" /> 
                        </div>

                        <div class="col-md-4 font-weight-bold text-center">
                            <?= $sucursal->cliente ?><br>
                            <?= "SUCURSALES " . $sucursal->nombre ?><br>
                            <?= $sucursal->direccion . " TEL. " . $sucursal->tel1 ?><br>
                            DIEGO JOSÉ ABAD 15 OTE. COL. CENTRO, JIQUILPAN. TEL 353 5333709<br>
                            <strong>Celular[urgecias]: 353 53 63657</strong></p>
                        </div>
                        <?php
                    } else if ($_SESSION["ruta"] == "alcala") {
                        ?>
                        <div class="col-md-4 text-center">
                            <img class="img-fluid" style="width: 350px; height: 100px;" src="../../images-sucursales/<?= $sucursal->img ?>" /> 
                        </div>

                        <div class="col-md-4 font-weight-bold text-center">
                            <?= $sucursal->nombre ?><br>
                            <?= $sucursal->direccion ?><br>
                            <?= $sucursal->tel1 . $sucursal->tel2 ?><br>
                        </div>

                        <?php
                    } else {
                        ?>
                        <div class="col-md-2 offset-md-2 text-center">
                            <img class="img-fluid" style="width: 115px" src="../../images-sucursales/<?= $sucursal->img ?>" /> 
                        </div>
                        <div class="col-md-4 font-weight-bold text-center">
                            <?= $sucursal->cliente ?><br>
                            <?= $sucursal->nombre ?><br>
                            <?= $sucursal->direccion ?><br>
                            <?= $sucursal->tel1 . " / " . $sucursal->tel2 ?><br>
                        </div>
                        <?php
                    }
                    ?>

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
                                <th>Laboratorio</th>
                                <th>Imagen</th>
                                <th>Complementarios</th>
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
                                        if ($row->reportado > 0 || count($arrayDocumentos)>0) {
                                            
                                            $estudios = $resultados->reporteEstudios($row->id, $row->expediente, $sucursal->id);
                                            foreach ($estudios AS $reportes) {
                                                if ($reportes->id_tipo_reporte == 1 && $reportes->reportado > 0) {
                                                    echo 'BH <a target="_blank" href="../reportes/' . $_SESSION["ruta"] . '/reporte-biometria-paciente?codigo=' . $row->consecutivo . '&expediente=' . $row->expediente . '&lab=0" class="btn btn-sm btn-success rounded-circle m-1" data-toggle="tooltip" title="BH"><i class="far fa-file-pdf"></i></a><br>';
                                                }

                                                if ($reportes->id_tipo_reporte == 2 && $reportes->reportado > 0) {
                                                    echo 'EGO <a target="_blank" href="../reportes/' . $_SESSION["ruta"] . '/reporte-examen-orina-paciente?codigo=' . $row->consecutivo . '&expediente=' . $row->expediente . '&lab=0" class="btn btn-sm btn-success rounded-circle m-1" data-toggle="tooltip" title="EGO"><i class="far fa-file-pdf"></i></a><br>';
                                                }

                                                if ($reportes->id_tipo_reporte == 3 && $reportes->reportado > 0) {
                                                    echo 'ESTANDAR <a target="_blank" href="../reportes/' . $_SESSION["ruta"] . '/reporte-estandar-paciente?codigo=' . $row->consecutivo . '&expediente=' . $row->expediente . '&lab=0" class="btn btn-sm btn-success rounded-circle m-1" data-toggle="tooltip" title="ESTANDAR"><i class="far fa-file-pdf"></i></a><br>';
                                                }

                                                if ($reportes->id_tipo_reporte == 4 && $reportes->reportado > 0) {
                                                    echo 'PAQUETE <a target="_blank" href="../reportes/' . $_SESSION["ruta"] . '/reporte-paquete-paciente?codigo=' . $row->consecutivo . '&expediente=' . $row->expediente . '&lab=0" class="btn btn-sm btn-success rounded-circle m-1" data-toggle="tooltip" title="PAQUETE"><i class="far fa-file-pdf"></i></a><br>';
                                                }

                                                if ($reportes->id_tipo_reporte == 5 && $reportes->reportado > 0) {
                                                    echo 'TEXTO <a target="_blank" href="../reportes/' . $_SESSION["ruta"] . '/reporte-texto-paciente?codigo=' . $row->consecutivo . '&expediente=' . $row->expediente . '&lab=0" class="btn btn-sm btn-success rounded-circle m-1" data-toggle="tooltip" title="TEXTO"><i class="far fa-file-pdf"></i></a><br>';
                                                }
                                            }
                                        } else {
                                            echo 'Pendiente';
                                        }
                                        
                                        //TIPOS DE REPORTES
                                        /* $url = 'http://' . $_SERVER["SERVER_NAME"] . "/controller/laboratorio/Reporte";
                                          $post = 'opc=imprimir-reporte-paciente&codigo=' . $row->consecutivo . '&expediente=' . $row->expediente . '&id_sucursal=' . $sucursal->id;

                                          $ch = curl_init();
                                          curl_setopt($ch, CURLOPT_URL, $url);
                                          curl_setopt($ch, CURLOPT_POST, 4);
                                          curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                                          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                          $output = curl_exec($ch);
                                          curl_close($ch);

                                          $reportes = json_decode($output);

                                          if ($reportes->bh) {
                                          echo 'BH <a target="_blank" href="../reportes/' . $_SESSION["ruta"] . '/reporte-biometria-paciente?codigo=' . $row->consecutivo . '&expediente=' . $row->expediente . '" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="BH"><i class="far fa-file-pdf"></i></a><br>';
                                          }

                                          if ($reportes->ego) {
                                          echo 'EGO <a target="_blank" href="../reportes/' . $_SESSION["ruta"] . '/reporte-examen-orina-paciente?codigo=' . $row->consecutivo . '&expediente=' . $row->expediente . '" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="EGO"><i class="far fa-file-pdf"></i></a><br>';
                                          }

                                          if ($reportes->estandar) {
                                          echo 'ESTANDAR <a target="_blank" href="../reportes/' . $_SESSION["ruta"] . '/reporte-estandar-paciente?codigo=' . $row->consecutivo . '&expediente=' . $row->expediente . '" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="ESTANDAR"><i class="far fa-file-pdf"></i></a><br>';
                                          }

                                          if ($reportes->paquete) {
                                          echo 'PAQUETE <a target="_blank" href="../reportes/' . $_SESSION["ruta"] . '/reporte-paquete-paciente?codigo=' . $row->consecutivo . '&expediente=' . $row->expediente . '" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="PAQUETE"><i class="far fa-file-pdf"></i></a><br>';
                                          }

                                          if ($reportes->texto) {
                                          echo 'TEXTO <a target="_blank" href="../reportes/' . $_SESSION["ruta"] . '/reporte-texto-paciente?codigo=' . $row->consecutivo . '&expediente=' . $row->expediente . '" class="btn btn-sm btn-danger rounded-circle m-1" data-toggle="tooltip" title="TEXTO"><i class="far fa-file-pdf"></i></a><br>';
                                          }
                                          } else {
                                          echo 'Pendiente';
                                          } */

                                        $estudios_pdf = explode(',', $row->estudios_pdf);
                                        foreach ($estudios_pdf as $pdf) {
                                            if ($pdf != "") {
                                                ?>
                                                <a target="_blank" href="/reportes/<?= $_SESSION["ruta"] . "/resultados/" . $pdf ?>" class="btn btn-sm btn-success rounded-circle m-1" data-toggle="tooltip" title="Complemento"><i class="far fa-file-pdf"></i></a>

                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td> 

                                    </td>
                                    <td> 
                                        <?php
                                            echo $documentos;
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }

                                    $contoladorImagen->listaEstudiosPaciente($id_paciente); 

                            ?>


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
