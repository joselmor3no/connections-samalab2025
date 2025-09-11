
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-calendar-alt nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="secciones-agenda" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i> Buscar</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $doctor[0]->id == "" ? "Alta" : "Modificación" ?> de Sección Agenda</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/catalogos/Seccion?opc=registro" method="post" novalidate="">
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input type="hidden"  name="id_seccion" value="<?= $seccion[0]->id ?>">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="seccion">Sección</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="seccion" value="<?= $seccion[0]->seccion ?>" placeholder="Sección" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Sección
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select class="form-control select2" id="tipo" name="tipo" style="width: 100%;">
                                    <option value="laboratorio" <?= $seccion[0]->tipo == "laboratorio" ? "selected" : "" ?>>Laboratorio</option>
                                    <option value="teleradiologia"  <?= $seccion[0]->tipo == "teleradiologia" ? "selected" : "" ?>>Teleradiología</option>

                                </select>
                            </div>
                        </div>


                        <div class="col-md-2 pt-4 mt-2">
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
