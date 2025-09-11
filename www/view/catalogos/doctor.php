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
                    <a href="doctores" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i> Buscar</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $doctor[0]->id == "" ? "Alta" : "Modificación" ?> de Doctor</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/catalogos/Doctor?opc=registro" method="post" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $id_cliente ?>">
                    <input type="hidden"  name="id_doctor" value="<?= $doctor[0]->id ?>">
                    <div class="row">
                        <!-- <div class="col-md-2">
                            <div class="form-group">
                                <label for="alias">Alias</label>
                                <input id="alias" type="text" class="form-control form-control-border text-uppercase"  name="alias" value="<?= $doctor[0]->alias ?>" <?php echo ($doctor[0] && $id_cliente==81) ? 'readonly' : ''?> placeholder="Alias" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Alias
                                </div>
                            </div>
                        </div> -->


                        <?php  
                            if($id_sucursal==121){
                                
                                if($doctor[0]->prefijo!=""){
                                    echo '<div class="col-md-1">
                                        <div class="form-group">
                                            <label for="prefijo">Prefijo</label>
                                            <select class="form-control" name="prefijo" id="prefijo" readonly>
                                                <option value="TEX">TEX</option>
                                                <option value="CH">CH</option>
                                            </select>
                                        </div>
                                    </div>';
                                    echo '<script>document.getElementById("prefijo").value="'.$doctor[0]->prefijo.'"</script>';
                                }
                                else{
                                    echo '<div class="col-md-1">
                                        <div class="form-group">
                                            <label for="prefijo">Prefijo</label>
                                            <select class="form-control" name="prefijo" id="prefijo">
                                                <option value="TEX">TEXCOCO</option>
                                                <option value="CH">CHIMALHUACAN</option>
                                            </select>
                                        </div>
                                    </div>';
                                }
                            }
                            else{
                         ?>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="prefijo">Prefijo</label>
                                <input id="prefijo" type="text" class="form-control form-control-border text-uppercase"  name="prefijo" value="<?= $prefijo_doctor_sucursal ?>" required="" readonly>
                            </div>
                        </div>
                        <?php   } ?>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="consecutivo_prefijo">Consecutivo</label>
                                <input id="consecutivo_prefijo" type="text" class="form-control form-control-border text-uppercase"  name="consecutivo_prefijo" value="<?php echo ($doctor[0]->consecutivo_prefijo) ? $doctor[0]->consecutivo_prefijo : $Ultimoconsecutivo->consecutivo; ?>"  placeholder="Consecutivo" required="" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control form-control-border <?= $_SESSION["ruta"] != "alcala" ? "text-uppercase" : "" ?>"  name="nombre" value="<?= $doctor[0]->nombre ?>" placeholder="Nombre(s)" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Nombre
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="apaterno">Apellido Paterno</label>
                                <input type="text" class="form-control form-control-border <?= $_SESSION["ruta"] != "alcala" ? "text-uppercase" : "" ?>"  name="apaterno" value="<?= $doctor[0]->apaterno ?>" placeholder="Apellido Paterno" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Apellido Paterno
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="amaterno">Apellido Materno</label>
                                <input type="text" class="form-control form-control-border <?= $_SESSION["ruta"] != "alcala" ? "text-uppercase" : "" ?>"  name="amaterno" value="<?= $doctor[0]->amaterno ?>" placeholder="Apellido Materno" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Apellido Materno
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Correo eletrónico</label>
                                <input type="text" class="form-control form-control-border"  name="email" value="<?= $doctor[0]->email ?>" placeholder="Correo eletrónico">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Promotor</label>
                                <select class = "form-control select2" name = "id_promotor" style = "width: 100%;">
                                    <option value = "">Selecciona a un promotor</option>
                                    <?php
                                    foreach ($listaPromotores AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $doctor[0]->promotor == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Zona</label>
                                <select class = "form-control select2" name = "id_zona" style = "width: 100%;">
                                    <option value = "">Selecciona a una zona</option>
                                    <?php 
                                    foreach ($listaZonas AS $row) { 
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $doctor[0]->zona == $row->id ? "selected" : "" ?>><?= $row->nombre ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="direccion" value="<?= $doctor[0]->direccion ?>" placeholder="Dirección">
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select class="form-control select2" id="id_estado" name="estado" style="width: 100%;">
                                    <option value="-1">Selecciona un Estado</option>
                                    <?php
                                    foreach ($estados AS $row) {
                                        ?>
                                        <option value="<?= $row->estado ?>" <?= $doctor[0]->estado == $row->estado ? "selected" : "" ?>><?= $row->estado ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="municipio">Municipio</label>
                                <select class="form-control select2" id="id_municipio" name="ciudad" style="width: 100%;">
                                    <option value="-1">Selecciona un Estado</option>
                                    <?php
                                    if($doctor[0]){
                                        foreach ($municipios AS $row) {
                                            ?>
                                            <option value="<?= $row->municipio ?>" <?= $doctor[0]->ciudad == $row->municipio ? "selected" : "" ?>><?= $row->municipio ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "cp">Código Postal</label>
                                <input type = "text" class = "form-control form-control-border" name = "cp" value="<?= $doctor[0]->cp ?>" placeholder = "Código Postal">
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "tel">Teléfono</label>
                                <input type = "text" class = "form-control form-control-border" name = "tel" value="<?= $doctor[0]->tel ?>" placeholder = "Teléfono">
                            </div>
                        </div>
                        
                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "cel">Celular</label>
                                <input type = "text" class = "form-control form-control-border" name = "cel" value="<?= $doctor[0]->celular ?>" placeholder = "Celular">
                            </div>
                        </div>

                        <div class = "col-md-3">
                            <div class = "form-group">
                                <label for = "id_especialidad">Especialidad</label>
                                <select class = "form-control select2" name = "id_especialidad" style = "width: 100%;">
                                    <option value = "">Selecciona un Especialidad</option>
                                    <?php
                                    foreach ($especialidad AS $row) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $doctor[0]->id_especialidad == $row->id ? "selected" : "" ?>><?= $row->especialidad ?></option>

                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <?php 
                           $doctor[0]->expediente= str_replace("SAMA1_", "", $doctor[0]->expediente);

                         ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="descarga">Código de Descarga</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"> 
                                        <?php if($_SESSION['id_cliente']==81): ?>
                                            <span class="input-group-text">sama1_</span>
                                            <input type="hidden" name="codigo" value="sama1">
                                            <input type="text" class="form-control form-control-border"  name="descarga" value="<?= str_replace("sama1_", "", $doctor[0]->expediente) ?>" placeholder="Código de Descarga">
                                        <?php else: ?>
                                            <span class="input-group-text"><?= $sucursal[0]->codigo . "_" ?></span>
                                            <input type="hidden" name="codigo" value="<?= $sucursal[0]->codigo ?>">
                                            <input type="text" class="form-control form-control-border"  name="descarga" value="<?= str_replace($sucursal[0]->codigo . "_", "", $doctor[0]->expediente) ?>" placeholder="Código de Descarga">
                                        <?php endif; ?>
                                        
                                    </div>
                                    

                                </div>

                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="pass">Contraseña</label>
                                <input type="text" class="form-control form-control-border"  name="pass" value="<?= $doctor[0]->contrasena ?>" placeholder="Contraseña">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="porcentaje">(%) Laboratorio </label>
                                <input type="text" class="form-control form-control-border"  name="porcentaje"  value="<?= $doctor[0]->porcentaje ?>" placeholder="Porcentaje (%)">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="porcentaje">(%) Imagen </label>
                                <input type="text" class="form-control form-control-border"  name="porcentajeI"  value="<?= $doctor[0]->porcentaje_imagen ?>" placeholder="Porcentaje (%)">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sitio">Sitio Web</label>
                                <input type="text" class="form-control form-control-border"  name="sitio" value="<?= $doctor[0]->sitio ?>" placeholder="Sitio Web">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select name="tipo" id="tipo" class="form-control" required>
                                    <option value=""></option>
                                    <option value="MEDICO">MEDICO</option>
                                    <option value="DENTAL">DENTAL</option>
                                </select>
                                <?php   echo '<script>document.getElementById("tipo").value="'.$doctor[0]->tipo.'"</script>' ?>
                            </div>
                        </div>




                        <div class="col-md-2 offset-md-3 pt-4 pb-2">
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
