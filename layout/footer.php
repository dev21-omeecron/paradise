 <!-- Footer -->
 <footer class="main-footer">
   <strong>Copyright &copy; 2024-2025
     <a href="#">Hotel Paradise</a>.</strong>
   All rights reserved.
   <div class="float-right d-none d-sm-inline-block">
     <b>Version</b> 1.0.0
   </div>
 </footer>
 </div>
 <!-- ./wrapper -->

 <!-- Scripts -->
 <!-- jQuery -->
 <script src="<?= BASE_URL ?>/plugins/jquery/jquery.min.js"></script>
 <!-- jQuery UI -->
 <script src="<?= BASE_URL ?>/plugins/jquery-ui/jquery-ui.min.js"></script>
 <!-- Bootstrap 4 -->
 <script src="<?= BASE_URL ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
 <!-- AdminLTE App -->
 <script src="<?= BASE_URL ?>/dist/js/adminlte.min.js"></script>
 <!-- Additional Scripts -->
 <script src="<?= BASE_URL ?>/plugins/chart.js/Chart.min.js"></script>
 <script src="<?= BASE_URL ?>/plugins/jqvmap/jquery.vmap.min.js"></script>
 <script src="<?= BASE_URL ?>/dist/js/dashboard.js"></script>

 <script>
   $.widget.bridge("uibutton", $.ui.button);

   // Initialize all modals
   $(document).ready(function() {
     $('.modal').modal({
       show: false
     });
   });
 </script>
 </body>

 </html>