<?php
require_once '../../inc/utils.php';
$pageTitle = 'Dashboard';

$arAdditionalCSS[] = <<<EOQ
<script src="https://www.gstatic.com/charts/loader.js"></script>
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
            <div class="card card-danger card-tabs">
              <div class="card-header">
                <h3>Web Analytics Chart</h3>
              </div>
              <div class="card-body">
                <div id="analyticsChart"></div>
                <a href="app/analytics" class="btn btn-primary">More Info <i class="fas fa-arrow-circle-right"></i></a>
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
//Load Google Chart
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(getAnalyticsChartData);

EOQ;

require_once DEF_DOC_ROOT_ADMIN.'inc/foot.php';
?>
