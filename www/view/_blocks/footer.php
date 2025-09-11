</div>
<!-- ./wrapper -->

<div class="modal fade" id="loading" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body text-center">

                <!--h4>Por favor espere</h4-->
                <h3><i class="fas fa-spinner fa-pulse mr-2 fa-sm"></i>Procesando ...</h3>
            </div>

        </div>
    </div>
</div>

<div id="modalError" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <img class="img-fluid" src="assets/images/error-500.png"/>
                    </div>
                    <div class="col-12 text-parent">
                        <span>Ha ocurrido un error, porfavor, contacte a su Administrador.</span>
                    </div>
                </div>


                <div class="col-12 mt-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>

        </div>
    </div>
</div>


<div id="modalPassword" class="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title" id="exampleModalLabel">Confirmación de Contraseña</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="col-md-12 text-center mb-4">
                    <i class="fas fa-user-lock " style="font-size: 60px;"></i>
                </div>
                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <label for="usuario" class="h3"><?= $usuario ?></label>
                        <input id="password_actual" type="password" class="form-control form-control-border text-center" value="" placeholder="Contraseña" required="">
                        <div class="invalid-feedback">
                            Favor de capturar el campo Contraseña 
                        </div>
                    </div>
                </div>

                <div class="col-md-4 offset-md-4 text-center">
                    <button id="acceso" type="button" class="btn btn-block bg-gradient-success ">Aceptar</button>
                </div>

            </div>

        </div>
    </div>
</div>

<?php
$password = $_SESSION["password"];
$ruta = $_SESSION["ruta"];
?>
<input type="hidden" id="password" value="<?= $password ?>">
<input type="hidden" id="ruta" value="<?= $ruta ?>">


</body>
</html>
