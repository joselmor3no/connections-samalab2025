<!-- Content Wrapper. Contains page content -->
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


        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <form class="needs-validation" action="imagen" method="post">
                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="card-title">Editor de formatos de Imagen</h3>
                        </div>

                    </div>
                </form>

            </div><!--end card-header-->

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4" style="background:#F2F2CA;border-radius:5px;">
                        <h3>Formatos:</h3>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Sección:</label>
                                    <select class="form-control" name="f_seccion" id="f_seccion">
                                        <option value="-1">Seleccione...</option>
                                        <option value="MASTO">MASTOGRAFÍA</option>
                                        <option value="TAC">TOMOGRAFÍA</option>
                                        <option value="RX">RAYOS X</option>
                                        <option value="RMN">RESONANCIA</option>
                                        <option value="US">ULTRASONIDO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="">Estudio:</label>
                                <select class="form-control" name="f_estudio" id="f_estudio">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="height:70vh;overflow-y:scroll;">
                                <h3>Estudios Existentes</h3>
                                <table class="table table-striped" >
                                    <thead>
                                        <th>Nombre</th>
                                        <th>-</th>
                                    </thead>
                                    <tbody id="lista_formatos_existentes" >
                                        <?php 
                                            $imagenController->obtenerFormatosMedico();
                                         ?>
                                    </tbody>
                                    
                                </table>
                            </div> 
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-7">
                                <div class="form-group">
                                    <label for="">Nombre del estudio</label>
                                    <input type="text" class="form-control" id="fnombre_estudio" disabled>
                                    <input type="hidden" id="id_cat_estudio">
                                    <input type="hidden" id="id_formato" value="-1">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for=""> &nbsp; </label>
                                    <button id="btn-guardar_formato" class="btn btn-success w-100">Guardar</button>
                                </div>
                            </div>
                        </div>
                        <textarea  class="summernote" name="resultado" id="resultado" >
                            <?php 

                            ?>
                        </textarea>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->



