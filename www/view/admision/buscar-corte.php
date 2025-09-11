<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="padding: 1px!important;">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-3">
                    <a href="gastos" class="btn btn-block btn-outline-info"><i class="fa fa-dollar-sign pr-2"></i> Gastos</a>
                </div>
                <div class="col-md-3">
                    <a href="corte" class="btn btn-block btn-outline-warning"><i class="fa fa-coins  pr-2"></i> Corte</a>
                </div>
                <div class="col-md-3">
                    <a href="buscar-corte" class="btn btn-block btn-primary"><i class="fa fa-search  pr-2"></i> Buscar Corte</a>
                </div>
                <div class="col-md-3">
                    <a href="caja" class="btn btn-block btn-outline-success"><i class="far fa-credit-card  pr-2"></i> Pagos</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">

            <div class="card-body">
                <div class="row">

                    <!--div class="col-md-12">
                        <h4>Búsqueda de Corte</h4>
                    </div-->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="codigo">Corte No.</label>
                            <input type="text" class="form-control form-control-border"  name="codigo" value="" placeholder="Corte No.">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="codigo">Fecha Inicio</label>
                            <input type="text" class="form-control form-control-border"  name="codigo" value="" placeholder="Corte No.">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="codigo">Fecha Fin</label>
                            <input type="text" class="form-control form-control-border"  name="codigo" value="" placeholder="Corte No.">
                        </div>
                    </div>

                    <div class="col-md-2 ">
                        <button class="btn btn-block bg-gradient-info mt-4"><i class="fa fa-search  pr-2"></i> Buscar</button>
                    </div>

                </div>

                <table id="" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                        <tr>
                            <th>Corte No.</th>
                            <th>Ingresos</th>
                            <th>Gastos</th>
                            <th>Total</th>
                            <th>Fecha</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->