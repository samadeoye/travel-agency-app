function throwError(msg, position)
{
    if (position == undefined)
    {
        position = '';
    }
    initToastOptions(position);
    toastr.error(msg);
}
function throwInfo(msg, position)
{
    if (position == undefined)
    {
        position = '';
    }
    initToastOptions(position);
    toastr.info(msg);
}
function throwWarning(msg, position)
{
    if (position == undefined)
    {
        position = '';
    }
    initToastOptions(position);
    toastr.warning(msg);
}
function throwSuccess(msg, position)
{
    if (position == undefined)
    {
        position = '';
    }
    initToastOptions(position);
    toastr.success(msg);
}
function initToastOptions(position)
{
    if (position == '')
    {
        toastr.optionsOverride = 'positionclass = "toast-bottom-left"';
        toastr.options.positionClass = 'toast-bottom-left';
    }
    else
    {
        toastr.optionsOverride = 'positionclass = "'+position+'"';
        toastr.options.positionClass = position;
    }
    toastr.options.closeButton = true;
    toastr.options.progressBar = true;
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

function goToUrl(url)
{
    window.location.href = url;
}

function doGetLogoutModal()
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to exit this application?',
        icon: 'error',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: 'Logout',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
          goToUrl('auth/logout.php');
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
