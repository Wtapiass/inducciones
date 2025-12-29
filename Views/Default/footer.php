

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <!-- <a class="scroll-to-top rounded">
        <i class="fas fa-angle-up"></i>
    </a> -->

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="<?php echo URL;?>authentication/salir.php">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Está segur@ de salir?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está list@ para finalizar su sesión actual.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary" type="submit">Cerrar sesión</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- General Modal-->
    <div class="modal fade" id="generalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Notificación</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div id="modalMessage" class="modal-body"></div>
                    <div id="modal-footer" class="modal-footer">
                        <button id="btnAceptarModal" class="btn btn-primary" type="button" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
        </div>
    </div>




    

    <!-- Core plugin JavaScript-->
    <script src="<?php echo URL.VIEWS.DTF; ?>vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?php echo URL.VIEWS.DTF; ?>js/jquery.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo URL.VIEWS.DTF; ?>js/sb-admin-2.min.js"></script>
   
    

    <!-- Page level plugins -->
    <script src="<?php echo URL.VIEWS.DTF; ?>vendor/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo URL.VIEWS.DTF; ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>

    
    

   

</body>

</html>