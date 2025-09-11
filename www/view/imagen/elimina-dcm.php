<?php 
if(isset($_REQUEST['fecha_final']) && isset($_REQUEST['fecha_inicial'])){
        $fechaInicial=$_REQUEST['fecha_inicial'];
        $fechaFinal=$_REQUEST['fecha_final'];
    }
    else{

    }
 ?>
<!-- Content Wrapper. Contains page content -->
<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6 ">
                    <h1><i class="fa fa-calculator nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                
            </div>
        </div><!-- /.container-fluid -->
    </section> 

    <!-- Main content -->
    <section class="content">
        <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <form action="#" method="post">
                    <div class="row">

                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label class="col-sm-4 col-form-label" for = "fecha_inicial">Fecha Inicio</label>
                                <div class="col-md-8"> <input type = "date" class = "form-control form-control-border" name = "fecha_inicial"  placeholder = "Fecha inicial" value="<?php echo $fechaInicial?>"></div>
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label class="col-sm-4 col-form-label" for = "fecha_final">Fecha Final</label>
                                <div class="col-md-8"> <input type = "date" class = "form-control form-control-border" name = "fecha_final"  placeholder = "Fecha final" value="<?php echo $fechaFinal ?>"></div>
                            </div>
                        </div> 

                        <div class = "col-md-3">
                            <div class = "form-group row m-0">
                                <label class="col-sm-4 col-form-label" for = "fecha_final">Sucursal</label>
                                <div class="col-md-8">
                                    <select name="sucursal" id="sucursal" class="form-control" required>
                                        <option value="">...</option>
                                        <?php 
                                            foreach ($sucursales as $row => $item) {
                                                if($item->prefijo_imagen!=''){
                                                    echo '<option value="'.$item->prefijo_imagen.'_pacientes">'.$item->nombre.'('.$item->codigo.')</option>';

                                                }
                                                
                                            }
                                         ?>
                                    </select>
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fas fa-search pr-2"></i> Eliminar</button>
                        </div>

                    </div>
                </form>

            </div><!--end card-header-->

            <div class="card-body">
                <?php 
                    if(isset($_REQUEST['fecha_final']) && isset($_REQUEST['fecha_inicial'])){
                        $fechaInicial=$_REQUEST['fecha_inicial'];
                        $fechaFinal=$_REQUEST['fecha_final'];

                        $startDate = new DateTime($_REQUEST['fecha_inicial'].' 00:00:01');
                        $endDate = new DateTime($_REQUEST['fecha_final'].' 23:59:59');
                        $directoryIterator = new DirectoryIterator("/home/admin/public_html/wado/uploads/discoDuro1/".$_REQUEST['sucursal']);
                        //---------------- Recorrer la lista de archivos
                        foreach ($directoryIterator as $file) {
                            if ($file->isDot()) {
                                continue;
                            }
                            $modificationDateTimestamp = $file->getMTime();
                            if ($modificationDateTimestamp > $startDate->getTimestamp() && $modificationDateTimestamp < $endDate->getTimestamp()) {
                                if($file->isDir()){
                                    $directorio = $file->getPath().'/'.$file->getfilename();
                                    $ficheros = scandir($directorio);
                                    foreach ($ficheros as $key => $fichero) {
                                        $rutaCompleta = $directorio . '/' . $fichero;
                                        if(is_dir($rutaCompleta) && $fichero != "." && $fichero != ".."){
                                            deleteDirectory($rutaCompleta);
                                        }
                                    }
            
                                }

                            }
                        }
                    }
                    else{
                         $fechaInicial=Date("Y-m-d");
                        $fechaFinal=Date("Y-m-d");
                    }
                ?>

            </div>

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php  

function deleteDirectory($dir) {
    if(!$dh = @opendir($dir)) return;
    while (false !== ($current = readdir($dh))) {
        if($current != '.' && $current != '..') {
            if (!@unlink($dir.'/'.$current)) 
                deleteDirectory($dir.'/'.$current);
        }       
    }
    closedir($dh);
    echo 'Se ha borrado el directorio '.$dir.'<br/>';
    @rmdir($dir);
}

?>
