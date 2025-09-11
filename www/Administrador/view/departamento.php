
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-6">
                    <h1><i class="fas fa-book-medical nav-icon pr-2"></i><?= $page_title ?></h1>
                </div>
                <div class="col-md-2 offset-md-4 col-sm-4 offset-sm-2">
                    <a href="catalogo-departamentos" class="btn btn-block bg-gradient-success"><i class="fa fa-search pr-2"></i> Buscar</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> <?= $datos[0]->id == "" ? "Alta" : "Modificación" ?> de Departamento</h3>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="controller/Estructura?opc=registro-departamento" method="post" novalidate="">
                    <input type="hidden" id="id_admin" name="id_admin" value="<?= $id_admin ?>">
                    <input type="hidden"  name="id" value="<?= $datos[0]->id ?>">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="no">Consecutivo</label>
                                <div class="border-bottom w-100 pt-1 pb-2"><?= $datos[0]->consecutivo != "" ? $datos[0]->consecutivo : $consecutivo[0]->consecutivo ?></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="codigo" value="<?= $datos[0]->codigo ?>" placeholder="Código" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Código
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="departamento">Departamento</label>
                                <input type="text" class="form-control form-control-border text-uppercase"  name="departamento" value="<?= $datos[0]->departamento ?>" placeholder="Departamento" required="">
                                <div class="invalid-feedback">
                                    Favor de capturar el campo Departamento
                                </div>
                            </div>
                        </div>


                        <div class="col-md-2 offset-md-5 pt-4 pb-2">
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
