<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-clipboard-list nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="usuarios" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i> Buscar</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $user[0]->id == "" ? "Alta" : "Modificación" ?> de Usuario</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/catalogos/Usuario?opc=registro" method="post" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input id="id_usuario" type="hidden"  name="id_usuario" value="<?= $user[0]->id ?>">
                    <input id="prefijo" type="hidden"  name="prefijo" value="<?= $sucursal->prefijo ?>">
                    <div class="row">

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "user">Nombre Común</label>
                                <input id="user" type = "text" class = "form-control form-control-border" name = "user" value="<?= str_replace($sucursal->prefijo . "_", "", $user[0]->usuario) ?>" placeholder = "Nombre Común" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Nombre Común
                                </div>
                            </div>
                        </div>
                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "pass">Contraseña</label>
                                <input type = "password" class = "form-control form-control-border" name = "pass" value="<?= $user[0]->contraseña ?>" placeholder = "Contraseña" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Contraseña
                                </div>
                            </div>
                        </div>
                        
                        <div class = "col-md-6">
                            <div class = "form-group">
                                <label for = "nombre">Nombre Completo</label>
                                <input type = "text" class = "form-control form-control-border text-uppercase" name = "nombre" value="<?= $user[0]->nombre ?>" placeholder = "Nombre Completo" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Nombre Completo
                                </div>
                            </div>
                        </div>
                        
                        <?php  
        
                        if( isset($_SESSION["usuario_sistema"]) && $_SESSION["usuario_sistema"]==1):
                        
                            
                        ?>
                            <div class="col-md-12">
                                <div class = "form-group">
                                    <label for = "nombre">Acceso a sucursales:</label>
                                    <select class="select2"  name="lista_sucursales[]" id="lista_sucursales" multiple="multiple" data-placeholder="Elija las sucursales" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                    <?php 
            
                                        foreach ($listaSucursales as $row => $item) {
                                            $sel='';
                                            if (isset($arregloSucursales) && in_array($item->id, $arregloSucursales)) {
                                                $sel = ' selected="selected" ';
                                            }
                                            echo '<option '.$sel.' value="'.$item->id.'">'.$item->nombre.'</option>';
                                        }
                                    ?>
                                    </select>

                                </div>
                            </div>
                        <?php endif;?>
                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "entrada">Horario Entrada</label>
                                <input type = "time" class = "form-control form-control-border" name = "entrada" value="<?= $user[0]->entrada_trabajo ?>" placeholder = "Horario Entrada">

                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "salida">Horario Salida</label>
                                <input type = "time" class = "form-control form-control-border" name = "salida" value="<?= $user[0]->salida_trabajo ?>" placeholder = "Horario Salida">
                            </div>
                        </div>


                        <div class = "col-md-3">
                            <div class = "form-group row m-1">
                                <label class="col-sm-3 col-form-label" for = "area">Área</label>
                                <div class="col-md-9"> 
                                    <select name="id_tipo_empleado" class="custom-select" required="">    
                                        <?php
                                        foreach ($tipo_empleados AS $row) {
                                            ?>
                                            <option value="<?= $row->id ?>" <?= $row->id == $user[0]->id_tipo_empleado ? "selected" : "" ?>><?= $row->tipo ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <?php if ($user[0]->id > 0) { ?>
                            <div class = "col-md-3">
                                <button id="btn-modal-permisos" type="button" class="btn btn-block bg-gradient-info" data-id="<?= $user[0]->id ?>"><i class="fa fa-user-lock pr-2" ></i> Permisos </button>
                                <button id="btn-modal-informes" type="button" class="btn btn-block bg-gradient-success" data-id="<?= $user[0]->id ?>"><i class="far fa-file-pdf pr-2"></i> Permisos Informes</button>
                            </div>
                        <?php } ?>
                        <div class="col-md-md-2 offset-md-5 pt-4 pb-2">
                            <button type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                        </div>

                        <hr>

                    </div><!-- .row -->

                </form>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<div class="modal fade" id="modal-permisos" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="text-align:center;"><i class="fa fa-user-lock"></i> Permisos de Usuario </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="closemodal">X</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input permisos-all">
                            <label class="form-check-label font-weight-bold" for="exampleCheck1">MARCAR TODO</label>
                        </div>
                    </div> 

                    <div class="col-md-4"> 
                        <h5>Admisión</h5>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Admisión") {
                                        ?>
                                        <div class="form-check">
                                            <input id="permiso_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input permisos" name="permisos[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <h5>Laboratorio</h5>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Laboratorio") {
                                        ?>
                                        <div class="form-check">
                                            <input id="permiso_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input permisos" name="permisos[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h5>Imagenología</h5>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Imagenología") {
                                        ?>
                                        <div class="form-check">
                                            <input id="permiso_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input permisos" name="permisos[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h5>Administración</h5>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Administración") {
                                        ?>
                                        <div class="form-check">
                                            <input id="permiso_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input permisos" name="permisos[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <h5>Catálogos</h5>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Catálogos") {
                                        ?>
                                        <div class="form-check">
                                            <input id="permiso_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input permisos" name="permisos[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>

                </div><!--End Row-->


                <div class="col-md-2 offset-md-5 pt-4 pb-2">
                    <button id="btn-save-permisos" type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                </div>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-informes" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="text-align:center;"><i class="fa fa-user-lock"></i> Permisos de Usuario/ Informes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="closemodal">X</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12 text-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input informes-all">
                            <label class="form-check-label font-weight-bold" for="exampleCheck1">MARCAR TODO</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h5>Empresas</h5>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Reportes/Empresas") {
                                        ?>
                                        <div class="form-check">
                                            <input id="informe_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input informes" name="informes[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <h5>Doctores</h5>
                        <div class="col-md-12">
                            <div class="form-group">

                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Reportes/Doctores") {
                                        ?>
                                        <div class="form-check">
                                            <input id="informe_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input informes" name="informes[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h5>Departamentos</h5>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Reportes/Departamentos") {
                                        ?>
                                        <div class="form-check">
                                            <input id="informe_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input informes" name="informes[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h5>Estudios</h5>
                        <div class="col-md-12">
                            <div class="form-group">

                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Reportes/Estudios") {
                                        ?>
                                        <div class="form-check">
                                            <input id="informe_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input informes" name="informes[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h5>Administración </h5>

                        <div class="col-md-12">
                            <div class="form-group">

                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Reportes/Administración") {
                                        ?>
                                        <div class="form-check">
                                            <input id="informe_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input informes" name="informes[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h5>Listas</h5>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Reportes/Listas") {
                                        ?>
                                        <div class="form-check">
                                            <input id="informe_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input informes" name="informes[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>                  
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h5>Facturación</h5>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Reportes/Facturación") {
                                        ?>
                                        <div class="form-check">
                                            <input id="informe_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input informes" name="informes[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h5>Movimientos</h5>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php
                                foreach ($permisos AS $row) {
                                    if ($row->seccion == "Reportes/Movimientos") {
                                        ?>
                                        <div class="form-check">
                                            <input id="informe_<?= $row->id ?>" data-id="<?= $row->id ?>" class="form-check-input informes" name="informes[]"  type="checkbox">
                                            <label class="form-check-label"><?= $row->descripcion ?></label>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                </div><!--End Row-->


                <div class="col-md-2 offset-md-5 pt-4 pb-2">
                    <button id="btn-save-informes" type="submit" class="btn btn-block bg-gradient-primary"><i class="fa fa-save pr-2"></i> Guardar</button>
                </div>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<style>


    .closemodal {
        font-size: 40px;
        color: red;
    }



    .note-editable{
        /* height: 50vh*/

    }

</style>
