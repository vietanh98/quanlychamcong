<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.2.0
    </div>
    <strong>Quản lý chấm công</strong>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../Public/admin/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../Public/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTabl../Public/admin/Plugins -->
<script src="../Public/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../Public/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../Public/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../Public/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../Public/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../Public/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../Public/admin/plugins/jszip/jszip.min.js"></script>
<script src="../Public/admin/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../Public/admin/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../Public/admin/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../Public/admin/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../Public/admin/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../Public/admin/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../Public/admin/dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
    $(function() {

        $('#example').dataTable({
            paging: false,
            searching: false
        });
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy","excel", "pdf", "print", "colvis",]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
</body>

</html>