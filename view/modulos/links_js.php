
    
    <script src="view/bootstrap/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="view/bootstrap/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="view/bootstrap/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="view/bootstrap/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="view/bootstrap/dist/js/adminlte.min.js"></script>
    <script src="view/bootstrap/dist/js/demo.js"></script>
    
     <script src="view/bootstrap/otros/datatables/datatables.min.js" >  </script>
	
	
	 <script>
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                pageLength: 10,
                responsive: true,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                dom: '<"html5buttons">lfrtipB',
              // dom: 'lfrtipB',
    
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'Reporte'},
                    {extend: 'pdf', title: 'Reporte'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });

        });

    </script>