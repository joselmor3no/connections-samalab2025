<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        background-color: #006fe6 !important;
    }
</style>
<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-city nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section> 

    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> Listado de Doctores</h3>
            </div>
            <div class="card-body">
                <?php  
                ?>
                <form action="#" method="POST">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sucursales">Sucursales</label>
                                <select name="sucursal" id="sucursales" class="form-control">

                                    <?php 
                                        foreach ($listaSucursales as $key => $item){
                                            $sel="";
                                            if($sucursal==$item->id)
                                                $sel="selected";
                                            echo '<option '.$sel.' value="'.$item->id.'">'.$item->nombre.'</option>';
                                        }

                                     ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">&nbsp</label>
                                <button class="btn btn-success form-control">Filtrar</button>
                            </div>
                            
                        </div>
                    </div>
                </form>
                <table id="" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th>Alias</th>
                            <th>Nombre</th>
                            <th>Promotor</th>
                            <th>Especialidad</th>
                            <th>Lista de sucursales</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="btb_lista_doctores">
                        <?php 
  
                        foreach ($listaDoctores as $key => $item){
                            $listaSucursalesD=explode(",", $item->id_sucursales);
                            $id_sucursales=$item->id_sucursales;
                            $nombred=$item->apaterno.' '.$item->amaterno.' '.$item->nombre;
                            $id_medico=$item->id_doctor;
                            echo '<tr>
                                <td>'.$item->alias.'</td>
                                <td>'.$nombred.'</td>
                                <td>'.$item->promotor.'</td>
                                <td>'.$item->especialidad.'</td>
                                <td style="font-size:12px;width:100px;">
                                    <select class="select2"  name="lista_zonas[]" id="lista_zonas'.$key.'" multiple="multiple" data-placeholder="Lista de zonas" data-dropdown-css-class="select2-purple" style="width: 100%;" disabled>';
                                        
                                             $arregloSucursales=[];
                                            foreach($listaSucursalesD as $row => $item){
                                                array_push($arregloSucursales, $listaSucursalesD[$row]);
                                            }
                                            foreach ($listaSucursales as $row => $item) {
                                                $sel='';
                                                if (in_array($item->id, $arregloSucursales)) {
                                                    $sel = ' selected="selected" ';
                                                }
                                                echo '<option '.$sel.' value="'.$item->id.'">'.$item->nombre.'</option>';
                                            }
                                echo '
                                    </select> 

                                </td>
                                <td>
                                    <button type="submit" data-nombre="'.$nombred.'" data-id="'.$id_medico.'" data-sucursales="'.$id_sucursales.'" class="btn btn-sm btn-warning rounded-circle m-1"  data-toggle="modal" data-target="#modEditarDoctor">
                                                <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>';
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



<!-- Editar Doctor -->
<div class="modal fade" id="modEditarDoctor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar los datos del Doctor:<b> <span id="dnombre_doctor"></span></b></h5>
                    <input type="hidden" id="id_doctor_sucursales">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sucursales">Lista de sucursales</label>
                                <select class="select2"  name="lista_sucursales[]" id="lista_sucursales" multiple="multiple" data-placeholder="Elija las sucursales" data-dropdown-css-class="select2-purple" style="width: 100%;">';
                                        <?php 


                                            /*$arregloSucursales=[];
                                            foreach($listaSucursalesD as $row => $item){
                                                array_push($arregloSucursales, $listaSucursalesD[$row]);
                                            }*/
                                            foreach ($listaSucursales as $row => $item) {
                                                $sel='';
                                               /* if (in_array($item->id, $arregloSucursales)) {
                                                    $sel = ' selected="selected" ';
                                                }*/
                                                echo '<option '.$sel.' value="'.$item->id.'">'.$item->nombre.'</option>';
                                            }
                                         ?>
                                             
                                    </select> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <button id="editar_medico" class="btn btn-success" >Editar</button>
                </div>
            </div>
        </form>
    </div>
</div>