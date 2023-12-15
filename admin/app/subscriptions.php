<?php
require_once '../../inc/utils.php';
$pageTitle = 'Subscriptions';
$arAdditionalCSS[] = <<<EOQ
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
EOQ;
require_once DEF_DOC_ROOT_ADMIN.'inc/head.php';
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?php echo $pageTitle;?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo DEF_ROOT_PATH_ADMIN;?>/app/">Home</a></li>
              <li class="breadcrumb-item active"><?php echo $pageTitle;?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <!-- ./row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header">
                        <h3>All Subscriptions</h3>
                    </div>
                    <div class="m-3">
                        <button class="btn btn-primary btn-sm" id="btnReloadSubscriptionsTable"><i class="fas fa-redo"></i> Reload</button>
                    </div>
                    <div class="card-body">
                        <table id="subscriptionsTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Date Submitted</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Date Submitted</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- /.content -->
</div>
  
<?php
$arAdditionalJsScripts[] = <<<EOQ
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
EOQ;

$arAdditionalJs[] = <<<EOQ
function deleteSubscription(id)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to delete this email?',
        icon: 'error',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: 'Delete',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed)
        {
            $.ajax({
                url: 'inc/actions',
                type: 'POST',
                dataType: 'json',
                data: {
                    'id': id,
                    'action': 'deletesubscription'
                },
                success: function(data) {
                    if(data.status == true) {
                        throwSuccess('Deleted successfully');
                        reloadTable('subscriptionsTable');
                    }
                    else {
                        throwError(data.msg);
                    }
                }
            });
        }
    });
}
EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ
var subscriptionsTable = $('#subscriptionsTable').DataTable({
    processing: true,
    autoWidth: false,
    responsive: true,
    dom: 'Blfrtip',
    buttons: ["csv", "excel"],
    ajax: 'inc/actions?action=getsubscriptions',
    columns: [
        { data: 'sn' },
        { data: 'email' },
        { data: 'cdate' },
        { data: 'delete' }
    ],
    columnDefs: [
        {"orderable": false, "targets": [3]}
    ],
    pageLength: 50,
});
subscriptionsTable.buttons().container().appendTo('#subscriptionsTable_wrapper .col-md-6:eq(0)');

$('#btnReloadSubscriptionsTable').click(function() {
    reloadTable('subscriptionsTable');
});

EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>
