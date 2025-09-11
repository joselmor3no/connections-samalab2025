<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-user-md nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a data-toggle="modal" data-target="#modAgregaDoctor" class="btn btn-block bg-gradient-success"><i class="fa fa-user-md pr-2"></i> Nuevo</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> Consulta rápida de Doctores</h3>
            </div>
            <div class="card-body">
                <form action="#" method="POST">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Promotor</label>
                                <select name="fpromotores" id="fpromotores" class="form-control">
                                    <option value="">Sin Filtro</option>
                                    <?php 
                                        foreach ($listaPromotores AS $row=> $item) {
                                            echo '<option value="'.$item->id.'">'.$item->nombre.'</option>';
                                        }
                                     ?>

                                </select>
                                <?php echo '<script>document.getElementById("fpromotores").value="'.$promotor.'"</script>' ?>
                            </div>
                        </div>                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Tipo</label>
                                <select name="ftipo" id="ftipo" class="form-control">
                                    <option value="">Sin Filtro</option>
                                    <option value="MEDICO">MEDICO</option>
                                    <option value="DENTAL">DENTAL</option>
                                </select>
                                <?php echo '<script>document.getElementById("ftipo").value="'.$tipo.'"</script>' ?>
                            </div>
                        </div>     
                        <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Nombre/Alias</label>
                                        <input type="text" name="falias"  value="<?=$alias?>" class="form-control">
                                        
                                    </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Prefijo</label>
                                <select name="prefijo" id="prefijo" class="form-control" id="">
                                    <?php
                                        foreach ($prefijos as $key => $item) {
                                           echo '<option value="'.$item->prefijo.'">'.$item->prefijo.'</option>';
                                        }
                                        
                                    ?>
                                </select>
                                <?php echo '<script>document.getElementById("prefijo").value="'.$prefijo.'"</script>' ?>
                                        
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">&nbsp;</label>
                                <button class="btn btn-success form-control" >Filtrar</button>
                            </div>
                        </div>
                   
                    </div>
                </form>
                

                <table id="lista_doctor" class="table table-bordered table-hover dataTable" >
                    <thead>
                        <tr>
                            <th>Alias</th>
                            <th>Nombre</th>
                            <th>Promotor</th>
                            <th>Zona</th>
                            <th>Especialidad</th>
                            <th>Celular</th>
                            <th>Usuario</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($datos AS $row) {
                            ?>
                            <tr>
                                <td><?= $row->alias ?></td>
                                <td><?= $row->apaterno.' '.$row->amaterno.' '. $row->nombre ?></td>
                                <td><?= $row->nombre_promotor ?></td>
                                <td><?= $row->nombre_zona ?></td>
                                <td><?= $row->nombre_especialidad ?></td>
                                <td><?php echo 'Cel:'.$row->celular.'<br>Tel:'.$row->tel ?></td>
                                <td><?= $row->expediente.' | '.$row->contrasena  ?></td>
                                <td> 
                                    <div class="row">
                                        <form class="pr-2" action="doctor"  method="POST">
                                            <input type="hidden" name="id" value="<?= $row->id ?>" class="d-none">
                                            <button type="submit" class="btn btn-sm btn-warning rounded-circle m-1"  data-toggle="tooltip" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </form>

                                        <button class="btn btn-sm btn-danger rounded-circle m-1 delete-doctor" data-id="<?= $row->id ?>" data-nombre="<?= $row->nombre ?>" data-toggle="tooltip" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>

                            <?php
                            $i++;
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


<!-- Eliminar Doctor -->
<div class="modal fade" id="modConfirmarDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Está seguro que desea eliminar el Doctor?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar el doctor <span id="nombre" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form action="controller/catalogos/Doctor?opc=delete" method="POST" >
                    <input type="hidden" class="d-none" id="id_doctor" name="id_doctor" value="">
                    <button id="" class="btn btn-danger btn-cargando" >Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!--    Busqueda de Doctor antes de agregar -->
<div class="modal fade" id="modAgregaDoctor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="doctor" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Busqueda de Doctores en el catalogo general</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">   
                        <div class="col-md-4">
                            <div class="form-group">    
                               <label for="busqueda_paterno">Apellido Paterno</label>
                               <input type="text" class="form-control" name="busqueda_paterno" id="busqueda_paterno"  required>
                            </div>  
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">    
                               <label for="busqueda_materno">Apellido Materno</label>
                               <input type="text" class="form-control" name="busqueda_materno" id="busqueda_materno" required>
                            </div>  
                        </div>  
                        <div class="col-md-4">
                            <div class="form-group">    
                               <label for="busqueda_nombre">Nombre(s)</label>
                               <input type="text" class="form-control" name="busqueda_nombre" id="busqueda_nombre" required>
                            </div>  
                        </div> 
                    </div>  
                    <div class="row">   
                       <div class="col-12"> 
                         <table class="table">
                             <tbody id="lista_doctores_catalogo">
                                 
                             </tbody>
                         </table>
                       </div>   
                    </div>  
                </div>
                <div class="modal-footer">
                    <button  class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button id="nuevo-doctor"  type="submit" class="btn btn-success" disabled>Crear Nuevo</button>
                </div>
            </div>

        </form>
        
    </div>
</div>
