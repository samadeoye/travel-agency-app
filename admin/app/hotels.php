<?php
require_once '../../inc/utils.php';
$pageTitle = 'Hotels';
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
                        <h3>All Rooms</h3>
                    </div>
                    <div class="m-3">
                        <button class="btn btn-primary" id="btnAddNewHotelRoom"><i class="mdi mdi-plus"></i> Add New</button>
                        <button class="btn btn-primary btn-sm float-right" id="btnReloadHotelRoomsTable"><i class="mdi mdi-reload"></i> Reload</button>
                    </div>
                    <div class="card-body">
                        <table id="hotelRoomsTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Details</th>
                                    <th>Image</th>
                                    <!-- <th>Link</th> -->
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Details</th>
                                    <th>Image</th>
                                    <!-- <th>Link</th> -->
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
function editHotelRoom(id)
{
    showModal('inc/popup/hotels?id='+id+'&action=updatehotelroom', 'defaultModal');
}
function deleteHotelRoom(id)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to delete this room?',
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
                    'action': 'deletehotelroom'
                },
                success: function(data) {
                    if (data.status == true) {
                        throwSuccess('Deleted successfully');
                        reloadTable('hotelRoomsTable');
                    }
                    else {
                        throwError(data.msg);
                    }
                }
            });
        }
    });
}

function applyMCE()
{
    tinymce.init({
        selector: '#details',
        branding: false,
        plugins: 'anchor autolink charmap codesample emoticons link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        height: "250"
    });
}

EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root").length)
    {
        e.stopImmediatePropagation();
    }
});

$('#defaultModal').on('hide.bs.modal', function () {
    tinymce.remove("#details");
});

var hotelRoomsTable = $('#hotelRoomsTable').DataTable({
    processing: true,
    autoWidth: false,
    responsive: true,
    ajax: 'inc/actions?action=gethotelrooms',
    columns: [
        { data: 'sn' },
        { data: 'name' },
        { data: 'details' },
        { data: 'img' },
        // { data: 'link' },
        { data: 'cdate' },
        { data: 'mdate' },
        { data: 'edit' },
        { data: 'delete' }
    ],
    columnDefs: [
        {"orderable": false, "targets": [6, 7]}
    ],
    pageLength: 50,
});

$('#btnReloadHotelRoomsTable').click(function() {
    reloadTable('hotelRoomsTable');
});

$('#btnAddNewHotelRoom').click(function() {
    showModal('inc/popup/hotels?action=addhotelroom', 'defaultModal');
});

EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>
