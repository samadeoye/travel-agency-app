<?php
require_once '../../inc/utils.php';
$pageTitle = 'Analytics';
$arAdditionalCSS[] = <<<EOQ
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<script src="https://www.gstatic.com/charts/loader.js"></script>
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
                <div class="card card-danger card-tabs">
                    <div class="card-header">
                        <h3>Web Analytics Chart</h3>
                    </div>
                    <div class="card-body">
                        <div id="analyticsChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header">
                        <h3>Website Analytics</h3>
                    </div>
                    <div class="m-3">
                        <button class="btn btn-primary btn-sm" id="btnReloadAnalyticsTable"><i class="fas fa-redo"></i> Reload</button>
                    </div>
                    <div class="card-body">
                        <table id="analyticsTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>IP Address</th>
                                    <th>Page Visited</th>
                                    <th>Date Visited</th>
                                    <th>Country</th>
                                    <th>Country Code</th>
                                    <th>Region</th>
                                    <th>Region Code</th>
                                    <th>City</th>
                                    <th>Timezone</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>IP Address</th>
                                    <th>Page Visited</th>
                                    <th>Date Visited</th>
                                    <th>Country</th>
                                    <th>Country Code</th>
                                    <th>Region</th>
                                    <th>Region Code</th>
                                    <th>City</th>
                                    <th>Timezone</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
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
var chartRows = [];
function getAnalyticsChartData()
{
    $.ajax({
        url: 'inc/actions',
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'getanalyticschartdata'
        },
        success: function(data)
        {
            if (data.status == true)
            {
                var chartData = data.data;
                for (var r of chartData)
                {
                    chartRows.push([r['country_name'], parseInt(r['count'])]);
                }
                drawAnalyticsChart();
            }
            else
            {
                console.log(data.msg);
            }
        }
    });
}

function drawAnalyticsChart()
{
    //Create the data table.
    var datag = new google.visualization.DataTable();
    datag.addColumn('string', 'Country');
    datag.addColumn('number', 'Total');
    
    datag.addRows(chartRows);
    //Set chart options
    var options = {
        'title': 'Visits to the Website'
    };

    //Instantiate and draw chart
    var chart = new google.visualization.PieChart(document.getElementById('analyticsChart'));
    chart.draw(datag, options);
}

$(window).resize(function(){
  drawAnalyticsChart();
});

EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ
var analyticsTable = $('#analyticsTable').DataTable({
    processing: true,
    autoWidth: false,
    responsive: true,
    ajax: 'inc/actions?action=getanalytics',
    columns: [
        { data: 'ip' },
        { data: 'page' },
        { data: 'cdate' },
        { data: 'country_name' },
        { data: 'country_code' },
        { data: 'region_name' },
        { data: 'region_code' },
        { data: 'city' },
        { data: 'timezone' },
        { data: 'latitude' },
        { data: 'longitude' }
    ],
    pageLength: 100,
});

$('#btnReloadAnalyticsTable').click(function() {
    reloadTable('analyticsTable');
});

//Load Google Chart
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(getAnalyticsChartData);

EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>