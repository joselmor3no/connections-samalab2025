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
                <?php
                if ($cliente->max_sucursales > count($datos)) {
                    ?>
                    <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                        <a href="sucursal" class="btn btn-block bg-gradient-success"><i class="fa fa-city pr-2"></i> Nueva</a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> Consulta rápida de Sucursales</h3>
            </div>
            <div class="card-body">
                <table id="" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th>Prefijo</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Nombre del Químico</th>
                            <th>Cédula del Químico</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($datos AS $row) {
                            ?> 
                            <tr>
                                <td><?= $row->codigo ?></td>
                                <td><?= $row->nombre ?></td>
                                <td><?= $row->direccion . ", " . $row->municipio . ", " . $row->estado ?></td>
                                <td><?= $row->quimico ?></td>
                                <td><?= $row->cedula ?></td>
                                <td><?= $row->email ?></td>
                                <td><?= $row->tel1 ?></td>
                                <td> 
                                    <div class="row">
                                    <button class="btn btn-sm btn-success rounded-circle m-1 datos-sat" data-id="<?= $row->id ?>" data-nombre="<?= $row->nombre ?>" data-toggle="tooltip" title="Datos Fiscales">
                                            <i class="fas fa-file-upload"></i>
                                        </button>
                                        <form class="pr-2" action="sucursal"  method="POST">
                                            <input type="hidden" name="id" value="<?= $row->id ?>" class="d-none">
                                            <button type="submit" class="btn btn-sm btn-warning rounded-circle m-1"  data-toggle="tooltip" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </form>

                                        <button class="btn btn-sm btn-danger rounded-circle m-1 delete-sucursal" data-id="<?= $row->id ?>" data-nombre="<?= $row->nombre ?>" data-toggle="tooltip" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php if($row->tipo=="SUCURSAL"): ?>
                                        <button class="btn btn-sm btn-info rounded-circle m-1 datos-lprecios" data-id="<?= $row->id ?>" data-nombre="<?= $row->nombre ?>" data-toggle="tooltip" title="Lista Precios Maquila">
                                            <i class="fas fa-list"></i>
                                        </button>
                                    <?php endif; ?>
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
                <h5 class="modal-title">¿Está seguro que desea eliminar la Sucursal?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Seleccione "Eliminar" para eliminar la sucursal <span id="nombre" class="font-weight-bold text-primary"></span>. Esta acción no se podrá deshacer.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form action="controller/Sucursal?opc=delete" method="POST" >
                    <input type="hidden" class="d-none" id="id_sucursal" name="id_sucursal" value="">
                    <button id="" class="btn btn-danger btn-cargando" >Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal para cargar datos del sat -->
<div class="modal fade" id="modalsat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Datos Fiscales Sucursal <span id="nombre2" class="font-weight-bold text-primary"></span></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                       <div class="col-md-12">
                            <div class="form-group">
                                <label for="reginem_fiscal">Regimen Fiscal</label>
                                <select class="form-control select2" id="id_estado" name="reginem_fiscal" style="width: 100%;">
                                    <option value="">Selecciona un Regimen Fiscal</option>
                                    <?php
                                      foreach ($regimen as $row) {
                                        ?>
                                        <option value="<?= $row->id ?>"><?= $row->clave,'-',  $row->descripcion ?></option>

                                        <?php
                                    } 
                                    ?>
                                </select>
                            </div>
              
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre_fiscal">Nombre Fiscal</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="nombre_fiscal" value="<?= $sucursal[0]->direccion2 ?>" placeholder="nombre fiscal">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="rfc">RFC</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="rfc" value="<?= $sucursal[0]->direccion2 ?>" placeholder="rfc">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="calle">Calle</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="calle" value="<?= $sucursal[0]->direccion2 ?>" placeholder="calle fiscal">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="exterior">No.Exterior</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="exterior" value="<?= $sucursal[0]->direccion2 ?>" placeholder="Exterior">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="interior">No. Interior</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="interior" value="<?= $sucursal[0]->direccion2 ?>" placeholder="Interior">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="colonia">Colonia</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="colonia" value="<?= $sucursal[0]->direccion2 ?>" placeholder="colonia">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="delegacion">Delegación</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="delegacion" value="<?= $sucursal[0]->direccion2 ?>" placeholder="delegacion">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="estado" value="<?= $sucursal[0]->direccion2 ?>" placeholder="estado">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ciudad">Ciudad</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="ciudad" value="<?= $sucursal[0]->direccion2 ?>" placeholder="ciudad">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="certificado">Certificado</label>
                                <input type="file" class="form-control form-control-border text-uppercase"
                                       name="certificado" value="<?= $sucursal[0]->direccion2 ?>" placeholder="certificado">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="key">Key</label>
                                <input type="file" class="form-control form-control-border text-uppercase"
                                       name="key" value="<?= $sucursal[0]->direccion2 ?>" placeholder="key">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="llave">Contraseña LLave Privada</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="llave" value="<?= $sucursal[0]->direccion2 ?>" placeholder="Contraseña de llave Privada">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serie">Serie</label>
                                <input type="text" class="form-control form-control-border text-uppercase"
                                       name="serie" value="<?= $sucursal[0]->direccion2 ?>" placeholder="serie">
                            </div>
                        </div>
                 </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <form action="controller/Sucursal?opc=delete" method="POST" >
                    <input type="hidden" class="d-none" id="id_sucursal" name="id_sucursal" value="">
                    <button id="" class="btn btn-success btn-cargando" >Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Modal para agregar la lista de precios -->
<div class="modal fade" id="modallprecios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lista de precios maquila para la sucursal: <span id="nombre3" class="font-weight-bold text-primary"></span></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_lpmaquila">
                    <input type="hidden" id="id_sucursalm" name="id_sucursal">
                    <input type="hidden" id="id_sucursal3">
                    <input type="hidden" id="numero_estudiosm" name="numero_estudiosm">
                     <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped" style="font-size:12px;">
                                <thead>
                                    <tr>
                                        <th style="width:300px;">Estudio | Paquete</th>
                                        <th>Código</th>
                                        <th>Código Paquete</th>
                                        <th>Código Paquete Sucursal</th>
                                        <th>$ Precio Matriz</th>
                                        <th>$ Precio Maquila</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="tbt_lpreciosm">
                                    <tr id="fila_1">
                                        <td>
                                            <input type="text" class="form-control opciones-estudios" list="opcionesEstudios_1" id="estudio_1" name="estudio_1" autocomplete="off">
                                            <datalist id="opcionesEstudios_1">
              
                                            </datalist>
                                            <input type="hidden" id="id_cat_estudio_1" name="id_cat_estudio_1">
                                            <input type="hidden" id="idpaquetem_1" name="idpaquetem_1">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control w-100 p-0" id="codigo_1" disabled >
                                        </td>
                                        <td>
                                            <input type="hidden"  id="idpaquete_1" name="idpaquete_1"  >
                                            <input type="text" class="form-control w-100 p-0" id="nombrepaquete_1" name="nombrepaquete_1" disabled >
                                        </td>
                                        <td>
                                            <select name="idpaquetes_1" id="idpaquetes_1" class="form-control" disabled>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control w-100 p-0" id="precio_publico_1" disabled>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control w-100 p-0" id="costo_maquila_1" name="costo_maquila_1" value="0">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-9"></div>
                        <div class="col-md-3 text-right">
                            <button id="actualiza_lpmaquila" class="btn btn-success pull-right" >Actualizar</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        
                        <div class="col-md-12">
                            <table id="tb_listaEstudiosSucursal" class="table table-striped dataTable">
                                <thead>
                                    <tr>
                                        <th style="width:200px;">Estudio | Paquete</th>
                                        <th>Código</th>
                                        <th style="width:200px;">Paquete Sucursal</th>
                                        <th>$ Matriz</th>
                                        <th>$ Maquila</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody id="btb_lista_estudios">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                </form>
               
            </div>
        </div>
    </div>
</div>
