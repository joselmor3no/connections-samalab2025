<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

        <div class="container-fluid pt-4">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-3">
                    <!-- small card -->
                    <div class="small-box bg-gradient-cyan">
                        <div class="inner">
                            <h3><?= number_format($ordenes) ?></h3>

                            <p>Pacientes del Día<br>
                                <small class="mt-n1 text-sm text-muted">&nbsp;</small>
                            </p>

                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-md-3">
                    <!-- small card -->
                    <div class="small-box bg-gradient-maroon">
                        <div class="inner">
                            <h3><?= number_format($cotizaciones) ?></h3>

                            <p>Cotizaciones del Día<br>
                                <small class="mt-n1 text-sm text-muted">&nbsp;</small>
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-md-3">
                    <!-- small card -->
                    <div class="small-box bg-gradient-teal">
                        <div class="inner">
                            <h3><?= number_format($reportados) ?></h3>

                            <p>Estudios Reportados<br>
                                <small class="mt-n1 text-sm text-muted">(Últimos 30 días)</small>
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-md-3">
                    <!-- small card -->
                    <div class="small-box bg-gradient-warning">
                        <div class="inner">
                            <h3><?= number_format($sinReportar) ?></h3>

                            <p>Estudios sin Reportar<br>
                                <small class="mt-n1 text-sm text-muted">(Últimos 30 días)</small>
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <!-- ./col -->

                <div id="piechart_3d" class="col-md-6"  style="height: 500px;">

                </div>

                <div id="chart_div" class="col-md-6"  style="height: 500px;">

                </div>



            </div>
            <!-- /.row -->
            <!-- Main row -->

        </div><!-- /.container-fluid -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->