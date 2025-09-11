<!-- Content Wrapper. Contains page content -->
<input type="hidden" id="msg" name="msg" value="<?= $msg ?>" class="d-none">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="padding: 1px!important;">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-3 offset-md-2">
                    <a href="gastos" class="btn btn-block btn-outline-info"><i class="fa fa-dollar-sign pr-2"></i> Gastos</a>
                </div>
                <div class="col-md-3">
                    <a href="corte" class="btn btn-block btn-outline-warning"><i class="fa fa-coins  pr-2"></i> Corte</a>
                </div>
                <div class="col-md-2">
                    <a href="caja" class="btn btn-block btn-success"><i class="far fa-credit-card  pr-2"></i> Pagos</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">

            <div class="card-body">
                 <div id="print-recibo" class="row d-none">
                    <div class="col-md-3 offset-md-3">
                        <button id="btn-recibo" class="btn btn-block btn-outline-primary"><i class="far fa-file-pdf  pr-2"></i> Recibo</button>
                    </div>
                    <div class="col-md-3">
                        <button id="btn-etiquetas" class="btn btn-block btn-outline-dark"><i class="far fa-file-pdf  pr-2"></i> Etiquetas</button>
                    </div>
                    <?php
                    if ($_SESSION['ruta'] == 'qa' || $_SESSION['ruta'] == 'connections') {
                    ?>
                    <div class="col-md-12 mt-2"></div>
                    <div class="col-md-3 offset-md-2">
                        <button id="btn-informado" class="btn btn-block btn-primary"><i class="fa fa-print pr-2"></i> Consentimiento Informado</a>
                    </div>
                    <div class="col-md-3">
                        <button id="btn-ginecologico" class="btn btn-block btn-primary"><i class="fa fa-print  pr-2"></i> Consentimiento Ginecológico</a>
                    </div>
                    <div class="col-md-3">
                        <button id="btn-vih" class="btn btn-block btn-primary"><i class="fa fa-print  pr-2"></i> Consentimiento VIH </a>
                    </div>
                    <?php
                   
                        
                    }
                    ?>
                </div>

                <form class="needs-validation" action="controller/admision/Caja?opc=registro" method="post" novalidate>
                    <input type="hidden" id="id_sucursal" name="id_sucursal" value="<?= $id_sucursal ?>">
                    <input type="hidden" id="id_orden" name="id_orden" value="">
                    <input type="hidden" id="id_ticket" name="id_ticket" value="<?= $sucursal->impresion ?>">

                    <div class="row">



                        <div class="col-md-3">

                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <div class="input-group input-group">
                                    <input id="codigo" type="text" class="form-control form-control-border"  name="codigo" value="<?= $codigo ?>" placeholder="Código" required="">
                                    <span class="input-group-append">
                                        <a  href="caja" class="btn btn-outline-warning btn-flat">  <i class="fas fa-eraser text-dark" title="Borrar" data-toggle="tooltip"></i></a>
                                    </span>
                                    <div class="invalid-feedback">
                                        Favor de capturar el campo Código
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="paciente">Paciente</label>
                                <div id="paciente" class="border-bottom w-100 pt-1 pb-2">&nbsp;</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha_orden">Fecha</label>
                                <div id="fecha_orden" class="border-bottom w-100 pt-1 pb-2">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="paciente">Código Matriz</label>
                                <div id="codigo_matriz" class="border-bottom w-100 pt-1 pb-2">&nbsp;</div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-2">
                            <button type="button" class="btn btn-info rounded-eye m-1 " data-toggle="collapse" href="#datosmonedero">
                                <i class="fas fa-credit-card mr-2"></i>Monedero
                            </button>

                        </div>
                        <div class="col-md-10">
                            <div class="row mb-4 mt-2 collapse multi-collapse" id="datosmonedero">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="no_tarjeta">No. Tarjeta</label>
                                        <input type="text" class="form-control form-control-border"  name="no_tarjeta" value="" placeholder="No. Tarjeta">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="saldo">Saldo en tarjeta ($)</label>
                                        <div class="border-bottom w-100 pt-1 pb-2">0.0</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="abono">Abono en tarjeta ($)</label>
                                        <div class="border-bottom w-100 pt-1 pb-2">0.0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4 offset-md-2">
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="total">Total ($)</label>
                                        <div id="total" class="border-bottom w-100 pt-1 pb-2 ">&nbsp;</div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="monto">Monto cubierto ($)</label>
                                        <div id="monto" class="border-bottom w-100 pt-1 pb-2">&nbsp;</div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="pago">Su pago ($)</label>
                                        <input id="pago" type="text" class="form-control form-control-border text-center"  name="pago" value="" placeholder="Su pago" required="">
                                        <div class="invalid-feedback">
                                            Favor de capturar el campo Su pago 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="saldo_deudor">Saldo deudor ($)</label>
                                        <div id="saldo_deudor" class="border-bottom w-100 pt-1 pb-2 text-danger">&nbsp;</div>
                                    </div>
                                </div>

                                <div id="table_caja"></div>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="estado">Forma de Pago</label>
                                        <select class="form-control select2" id="id_forma_pago" name="id_forma_pago" style="width: 100%;" required="">

                                            <?php
                                            foreach ($datos AS $row) {
                                                ?>
                                                <option value="<?= $row->id ?>"><?= $row->descripcion ?></option>

                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Favor de ingresar una Forma de Pago 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="aclaraciones">Aclaraciones</label>
                                        <textarea class="form-control form-control-border"  name="aclaraciones" value="" placeholder="Aclaraciones"></textarea>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="aclaraciones">Tipo de Referencia</label>
                                        <select class="custom-select" id="tipo_orden" name="tipo_orden" >
                                                <option value="ORDEN_SAMALAB">Orden Samalab</option>
                                                <option value="INSTITUCIONP">Institución Pública</option>
                                                <option value="RECETA">Receta Médica</option>
                                                <option value="RECETA_DIFERIDO">Receta M. Referido</option>
                                                <option value="OTRO">Orden Competencia</option>
                                                <option value="OTRO">Referido por whatsapp</option>
                                                <option value="WEB">Venta en Página WEB</option>
                                                <option value="AQC">AQC</option>
                                                <option value="FB">PROMOS FB</option>
                                                <!--option value="3">Cotización</option-->

                                            </select>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h3 id="pagado" class="text-center text-success font-weight-bold d-none">PAGADO</h3>
                                    <h3 id="credito" class="text-center text-primary font-weight-bold d-none">CRÉDITO</h3>
                                    <h3 id="cancelado" class="text-center text-danger font-weight-bold d-none">CANCELADO</h3>

                                </div>

                                <div class="col-md-6 offset-md-3 ">
                                    <button id="save-pago" class="btn btn-block bg-gradient-primary mt-4"><i class="fas fa-save  pr-2"></i> Guardar</button>
                                </div>

                            </div>
                        </div>

                    </div>
                </form>

            </div>
            <!-- /.card-body -->

        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->