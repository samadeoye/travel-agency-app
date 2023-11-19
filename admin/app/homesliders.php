<?php
require_once '../../inc/utils.php';
$pageTitle = 'Homepage Sliders';
$arAdditionalCSS[] = <<<EOQ
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<script src="https://cdn.tiny.cloud/1/8cw5r79obdojgicjwig1exg8q30t07nlinsebyju9p3odcrr/tinymce/6/tinymce.min.js"></script>
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
                        <h3>Current Sliders</h3>
                    </div>
                    <div class="m-3">
                        <button class="btn btn-primary" id="btnAddNewSlider"><i class="mdi mdi-plus"></i> Add New</button>
                        <button class="btn btn-primary btn-sm float-right" id="btnReloadSlidersTable"><i class="mdi mdi-reload"></i> Reload</button>
                    </div>
                    <div class="card-body">
                        <table id="slidersTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                    <th></th>
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
EOQ;

$arAdditionalJs[] = <<<EOQ
function editSlider(id)
{
    showModal('inc/popup/sliders?id='+id+'&action=updateslider', 'defaultModal');
}
function deleteSlider(id)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to delete this image?',
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
                    'action': 'deleteslider'
                },
                success: function(data) {
                    if (data.status == true) {
                        throwSuccess('Deleted successfully');
                        reloadTable('slidersTable');
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
var slidersTable = $('#slidersTable').DataTable({
    processing: true,
    autoWidth: false,
    responsive: true,
    ajax: 'inc/actions?action=getsliders',
    columns: [
        { data: 'sn' },
        { data: 'img' },
        { data: 'cdate' },
        { data: 'mdate' },
        { data: 'edit' },
        { data: 'delete' }
    ],
    columnDefs: [
        {"orderable": false, "targets": [4, 5]}
    ],
    pageLength: 50,
});

$('#btnReloadSlidersTable').click(function() {
    reloadTable('slidersTable');
});

$('#btnAddNewSlider').click(function() {
    showModal('inc/popup/sliders?action=addslider', 'defaultModal');
});

EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>
