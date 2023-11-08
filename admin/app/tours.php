<?php
require_once '../../inc/utils.php';
$pageTitle = 'Tours';
$arAdditionalCSS[] = <<<EOQ
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
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
                        <h3>All Tours</h3>
                    </div>
                    <div class="m-3">
                        <a href="app/tour" class="btn btn-primary"><i class="mdi mdi-plus"></i> Add New</a>
                        <button class="btn btn-primary btn-sm float-right" id="btnReloadToursTable"><i class="fas fa-redo"></i> Reload</button>
                    </div>
                    <div class="card-body">
                        <table id="toursTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Destination</th>
                                    <th>Days</th>
                                    <th>Starting Price</th>
                                    <th>Featured Image</th>
                                    <th>Created Date</th>
                                    <th>Modified Date</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Destination</th>
                                    <th>Days</th>
                                    <th>Starting Price</th>
                                    <th>Featured Image</th>
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
function addNewDestination()
{
    var formId = '#destinationsForm';
    var name = $(formId+' #name').val();

    if (name.length < 3)
    {
        throwError('Please enter a destination!');
    }
    else
    {
        var form = $("#destinationsForm");
        $.ajax({
            url: 'inc/actions',
            type: 'POST',
            dataType: 'json',
            data: form.serialize(),
            beforeSend: function() {
                enableDisableBtn(formId+' #btnSubmit', 0);
            },
            complete: function() {
                enableDisableBtn(formId+' #btnSubmit', 1);
            },
            success: function(data)
            {
                if(data.status == true)
                {
                    throwSuccess('Destination added successfully!');
                    closeModal('newDestinationModal', false, ['name']);
                    reloadTable('toursTable');
                }
                else
                {
                    if(data.info !== undefined)
                    {
                        throwInfo(data.msg);
                    }
                    else
                    {
                        throwError(data.msg);
                    }
                }
            }
        });
    }
}

function deleteTour(id)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to delete this tour?',
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
                    'action': 'deletetour'
                },
                success: function(data) {
                    if(data.status == true) {
                        throwSuccess('Deleted successfully');
                        reloadTable('toursTable');
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
var toursTable = $('#toursTable').DataTable({
    processing: true,
    autoWidth: false,
    responsive: true,
    ajax: 'inc/actions?action=gettours',
    columns: [
        { data: 'sn' },
        { data: 'title' },
        { data: 'destination' },
        { data: 'days' },
        { data: 'price' },
        { data: 'img' },
        { data: 'cdate' },
        { data: 'mdate' },
        { data: 'edit' },
        { data: 'delete' }
    ],
    columnDefs: [
        {"orderable": false, "targets": [7, 8]}
    ],
    pageLength: 50,
});

$('#btnReloadToursTable').click(function() {
    reloadTable('toursTable');
});

EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>
