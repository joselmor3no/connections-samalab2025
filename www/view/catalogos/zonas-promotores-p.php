<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-cog nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card ">
            <div class="card-header">
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link " href="zonas-promotores" aria-selected="false">Zonas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="zonas-promotores-p" aria-selected="true">Promotores</a>
                    </li>
                </ul>                                                                                                                                          

            </div>
            <div class="card-body">

                <div class="row">


                    <div class="col-md-5">
                        <form class="needs-validation" action="controller/catalogos/Doctor?opc=registro-promotores" method="post" novalidate="">
                            <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                        
                            <input type="hidden"  name="id_promotor" value="<?php echo ($informacionPromotor!='') ? $informacionPromotor->id : '-1' ?>">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="codigo">No</label>
                                        <?php if(!$ultimoConsecutivoPromotor>0){$ultimoConsecutivoPromotor=1;}?>
                                        <input id="alias-descuento" type="text" class="form-control form-control-border text-uppercase"  name="pnumero" value="<?php echo ($informacionPromotor!='') ? $informacionPromotor->numero : $ultimoConsecutivoPromotor ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="pnombre">Nombre del promotor</label>
                                        <input id="alias-descuento" type="text" class="form-control form-control-border"  name="pnombre" value="<?php echo ($informacionPromotor!='') ? $informacionPromotor->nombre : '' ?>" placeholder="Nombre" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Nombre
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="pnombre">Zonas del promotor</label>
                                        <div class="select2-purple">
                                         <select class="select2"  name="lista_zonas[]" id="lista_zonas" multiple="multiple" data-placeholder="Lista de zonas" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                            <?php 
                                             $arregloZonas=[];
                                            foreach($listaZonasPromotor as $row => $item){
                                                array_push($arregloZonas, $item->id);
                                            }
                                            foreach ($listaZonas as $row => $item) {
                                                $sel='';
                                                if (in_array($item->id, $arregloZonas)) {
                                                    $sel = ' selected="selected" ';
                                                }
                                                echo '<option '.$sel.' value="'.$item->id.'">'.$item->nombre.'</option>';
                                            }
                                             ?>
                                        </select> 
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="sucursal">Sucursal Destino:</label>
                                        <select name="sucursalo" id="sucursalo" class="form-control">
                                        <?php 

                                        foreach($informacionSucursal as $row=>$item){
                                            if($id_sucursal==121)
                                                echo '<option value="'.$item->id.'">'.$item->nombre.'</option>';    
                                            else{
                                                if($item->id==$id_sucursal){
                                                    echo '<option value="'.$item->id.'">'.$item->nombre.'</option>'; 
                                                }
                                            }    
                                        }
                                         ?>
                                        }
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-3 offset-md-7 pt-4 pb-2">
                                    <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button> 
                                </div>

                            </div>
                            <!-- /.row -->
                        </form>
                        <form class="needs-validation"  action="controller/catalogos/Doctor?opc=intercambio_promotor" method="POST">
                            <div class="row">
                                <div class="col-12">
                                    <h4>Switch de Promotores</h4>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="">Sucursal</label>
                                        <select name="swsucursal" id="" class="form-control" required>
                                            <option value="">Seleccione</option>
                                            <?php
                                                foreach($informacionSucursal as $row=>$item){
                                                    echo '<option value="'.$item->id.'">'.$item->nombre.'</option>';    
                                                }
                                            ?>
                                            <option value="todos">Todas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="sworigen">Origen</label>
                                        <select name="sworigen" id="" class="form-control" required>
                                            <option value="">Seleccione</option>
                                            <?php
                                                foreach ($listaPromotores as $row => $item) {
                                                    echo '<option value="'.$item->id.'">'.$item->nombre.'</option>';    
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="swdestino">Destino</label>
                                        <select name="swdestino" id="" class="form-control" required>
                                            <option value="">Seleccione</option>
                                            <?php
                                                foreach ($listaPromotores as $row => $item) {
                                                    echo '<option value="'.$item->id.'">'.$item->nombre.'</option>';    
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="swaccion">Acción</label>
                                        <select name="swaccion" id="" class="form-control" required>
                                            <option value="">Seleccione</option>
                                            <option value="intercambio">Switch</option>
                                            <option value="unilateral">Origen a Destino</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="">&nbsp;</label>
                                        <button type="submit" class="btn btn-success">Ejecutar Cambio</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <table id="" class="table table-bordered table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>Consecutivo</th>
                                    <th>Nombre</th>
                                    <th>Sucursal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($listaPromotores as $row => $item) {
                                    echo '<tr>
                                        <td>'.$item->numero.'</td>
                                        <td>'.$item->nombre.'</td>
                                        <td>'.$item->nombre_sucursal.'</td>
                                        <td> 
                                            <div class="row">
                                                <form class="pr-2" action="zonas-promotores-p"  method="POST">
                                                    <input type="hidden" name="id" value="'.$item->id.'" class="d-none">
                                                    <button type="submit" class="btn btn-sm btn-warning rounded-circle m-1"  data-toggle="tooltip" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </form>

                                                <button class="btn btn-sm btn-danger rounded-circle m-1 delete-promotores" data-id="'.$item->id.'" data-nombre="'.$item->nombre.'" data-toggle="tooltip" title="Eliminar">
                                                    <i class="fas fa-trash"></i> 
                                                </button>
                                            </div>
                                        </td>
                                    </tr>';
                                }

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


<!-- Eliminar Doctor -->
<div class="modal fade" id="modConfirmarDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Está seguro que desea eliminar el promotor?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar al promotor <span id="nombre" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form action="controller/catalogos/Doctor?opc=delete-promotor" method="POST" >
                    <input type="hidden" class="d-none" id="id_promotor" name="id_promotor" value="">
                    <button  class="btn btn-danger btn-cargando" >Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card-header {
        border-bottom: transparent
    }

</style>