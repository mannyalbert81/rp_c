  
    <script src="view/bootstrap/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="view/bootstrap/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="view/bootstrap/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="view/bootstrap/dist/js/adminlte.min.js"></script>
    <script src="view/bootstrap/dist/js/demo.js"></script>
    
    
    <!-- PARA DATA TABLES -->
    <script src="view/bootstrap/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="view/bootstrap/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script>
      $(function () {
        $('#example1').DataTable()
        $('#example2').DataTable({
          'paging'      : true,
          'lengthChange': false,
          'searching'   : false,
          'ordering'    : true,
          'info'        : true,
          'autoWidth'   : false
        })
      })
	</script>