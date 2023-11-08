<?php
require_once '../../inc/utils.php';
$pageTitle = 'Dashboard';

$arAdditionalCSS[] = <<<EOQ
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
EOQ;
require_once DEF_DOC_ROOT_ADMIN.'inc/head.php';

$arData = AbcTravels\Admin\Dashboard\Dashboard::getDashboardData();
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo doTypeCastInt($arData['numDestinations']);?></h3>
                <p>Destinations</p>
              </div>
              <div class="icon">
                <i class="fas fa-plane-arrival"></i>
              </div>
              <a href="app/destinations" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo doTypeCastInt($arData['numTours']);?></h3>
                <p>Tours</p>
              </div>
              <div class="icon">
                <i class="fas fa-plane-departure"></i>
              </div>
              <a href="app/tours" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo doTypeCastInt($arData['numSubmissions']);?></h3>
                <p>Submissions</p>
              </div>
              <div class="icon">
                <i class="fas fa-envelope-open-text"></i>
              </div>
              <a href="app/submissions" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
        <!-- /.row -->

        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-tabs">
              <div class="card-header">
                <h3>Recent Submissions</h3>
              </div>
              <div class="m-3">
                <button class="btn btn-primary btn-sm" id="btnReloadSubmissionsTable"><i class="fas fa-redo"></i> Reload</button>
              </div>
              <div class="card-body">
                <table id="submissionsTable" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Type</th>
                      <th>Date Submitted</th>
                      <th></th>
                      <!-- <th></th> -->
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>#</th>
                      <th>Type</th>
                      <th>Date Submitted</th>
                      <th></th>
                      <!-- <th></th> -->
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
        
      </section>
      <!-- /.Left col -->
    </div>
    <!-- /.row (main row) -->

    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
  
<?php
$arAdditionalJsScripts[] = <<<EOQ
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
EOQ;

$arAdditionalJs[] = <<<EOQ
function deleteSubmission(id)
{
  Swal.fire({
    title: '',
    text: 'Are you sure you want to delete this submission?',
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
        'action': 'deletesubmission'
      },
      success: function(data) {
        if(data.status == true) {
          throwSuccess('Deleted successfully');
          reloadTable('submissionsTable');
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
var submissionsTable = $('#submissionsTable').DataTable({
  paging: false,
  ordering: false,
  info: false,
  processing: true,
  autoWidth: false,
  responsive: true,
  ajax: 'inc/actions?action=getdashboardsubmissions',
  columns: [
    { data: 'sn' },
    { data: 'type' },
    { data: 'cdate' },
    { data: 'details' },
    //{ data: 'delete' }
  ],
  columnDefs: [
    {"orderable": false, "targets": [3]}
  ]
});

$('#btnReloadSubmissionsTable').click(function() {
  reloadTable('submissionsTable');
});

EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>
