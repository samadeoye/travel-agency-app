function throwError(msg)
{
    toastr.error(msg);
}
function throwInfo(msg)
{
    toastr.info(msg);
}
function throwWarning(msg)
{
    toastr.warning(msg);
}
function throwSuccess(msg)
{
    toastr.success(msg);
}

function enableDisableBtn(id, status)
{
    disable = true;
    if(status == 1) {
        disable = false;
    }
    $(id).attr('disabled', disable);
    if(disable) {
        $(id).append(' <div class="spinner-border text-light spinner-border-sm" role="status"><span class="sr-only">Processing...</span></div>');
    }
    else {
        $('.spinner-border').remove();
    }
}

function doOpenLogoutModal()
{
  Swal.fire({
    title: '',
    text: 'Are you sure you want to logout?',
    icon: 'error',
    showCancelButton: true,
    reverseButtons: true,
    confirmButtonText: 'Logout',
    confirmButtonColor: '#d33',
    customClass: 'swalWide',
    }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'app/logout';
    }
  });
}

function showModal(modalUrl, modalId)
{
    if(modalId == undefined || modalId == '')
    {
        modalId = 'defaultModal';
    }
    $('#'+modalId+' .modal-content').load(modalUrl, function() {
        $('#'+modalId).modal('show');
    });
}

function closeModal(modalId, clearContent, arFields)
{
    if (modalId == undefined || modalId == '')
    {
        modalId = 'defaultModal';
    }
    if (clearContent == undefined)
    {
        clearContent = false;
    }
    if (arFields == undefined)
    {
        arFields = [];
    }

    if (arFields.length > 0)
    {
        for (field of arFields)
        {
            $('#'+modalId+' #'+field).val('');
        }
    }
    if (clearContent)
    {
        $('#'+modalId+' .modal-content').html('');
    }
    $('#'+modalId).modal('hide');
}

function reloadTable(tableId)
{
    var table = $('#'+tableId).DataTable();
    table.ajax.reload();
}

function goToUrl(url)
{
    window.location.href = url;
}