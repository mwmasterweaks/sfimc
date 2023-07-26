@extends('layout.adminweb')

@section('content')

@php($BronzeMemberCount = 0)
@php($SilverMemberCount = 0)
@php($GoldMemberCount = 0)
@php($CodeCount = 0)
@php($CodeUsed = 0)
@php($EWalletBalance = 0)
@php($TotalSales = 0)
@php($ProductCount = 0)
@foreach($DashboardFigures as $fig)
	@php($BronzeMemberCount = $fig->BronzeMemberCount)
	@php($SilverMemberCount = $fig->SilverMemberCount)
	@php($GoldMemberCount = $fig->GoldMemberCount)
	@php($CodeCount = $fig->CodeCount)
	@php($CodeUsed = $fig->CodeUsed)
	@php($EWalletBalance = $fig->EWalletBalance)
	@php($TotalSales = $fig->TotalSales)
	@php($ProductCount = $fig->ProductCount)
@endforeach
  
<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Dashboard
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">

	    <div class="row">

	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-yellow" style="background: #e15a1f !important;">
	            	<i class="ion ion-ios-people-outline"></i>
	            </span>
	            <div class="info-box-content">
	              <span class="info-box-text">Bronze Members</span>
	              <span class="info-box-number">{{number_format($BronzeMemberCount,0) }}</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>
	     
	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-gray">
	            	<i class="ion ion-ios-people-outline"></i>
	            </span>
	            <div class="info-box-content">
	              <span class="info-box-text">Silver Members</span>
	              <span class="info-box-number">{{number_format($SilverMemberCount,0) }}</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>

	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-yellow">
	            	<i class="ion ion-ios-people-outline"></i>
	            </span>
	            <div class="info-box-content">
	              <span class="info-box-text">Gold Members</span>
	              <span class="info-box-number">{{number_format($GoldMemberCount,0) }}</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>

	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-green">
	            	<i class="ion ion-ios-cart-outline"></i>
	            </span>

	            <div class="info-box-content">
	              <span class="info-box-text">Sales</span>
	              <span class="info-box-number">{{number_format($TotalSales,2) }}</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>

		</div>

	    <div class="row">
	        <div class="col-md-6">
	          <!-- LINE CHART -->
	          <div class="box box-info">
	            <div class="box-header with-border">
	              <h3 class="box-title">Sales Chart</h3>

	              <div class="box-tools pull-right">
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
	              </div>
	            </div>
	            <div class="box-body">
	              <div class="chart">
	                <canvas id="lineChart" style="height:250px"></canvas>
	              </div>
	            </div>
	            <!-- /.box-body -->
	          </div>
	          <!-- /.box -->
	      	</div>


        <div class="col-md-6">

          <!-- /.info-box -->
          <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Codes</span>
              <span class="info-box-number">{{number_format($CodeCount,0)." Available : ".number_format($CodeUsed,0)." Used" }}</span>

              <div class="progress">
                <div class="progress-bar" style="width: 40%"></div>
              </div>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->

                    <!-- /.info-box -->
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">E-Wallets</span>
              <span class="info-box-number">{{number_format($EWalletBalance,2) }}</span>

              <div class="progress">
                <div class="progress-bar" style="width: 20%"></div>
              </div>
            </div>
            <!-- /.info-box-content -->
          </div>

          <!-- Info Boxes Style 2 -->
          <div class="info-box bg-maroon">
            <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Products</span>
              <span class="info-box-number">{{number_format($ProductCount,0) }}</span>

              <div class="progress">
                <div class="progress-bar" style="width: 50%"></div>
              </div>
            </div>
            <!-- /.info-box-content -->
          </div>

        </div>
        <!-- /.col -->

      	</div>

	</section>
	<!-- /.content -->	

	<script type="text/javascript">

	    var areaChartData = {
	      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
	      datasets: [
	        {
	          label               : 'Electronics',
	          fillColor           : 'rgba(210, 214, 222, 1)',
	          strokeColor         : 'rgba(210, 214, 222, 1)',
	          pointColor          : 'rgba(210, 214, 222, 1)',
	          pointStrokeColor    : '#c1c7d1',
	          pointHighlightFill  : '#fff',
	          pointHighlightStroke: 'rgba(220,220,220,1)',
	          data                : [65, 59, 80, 81, 56, 55, 40]
	        },
	        {
	          label               : 'Digital Goods',
	          fillColor           : 'rgba(60,141,188,0.9)',
	          strokeColor         : 'rgba(60,141,188,0.8)',
	          pointColor          : '#3b8bba',
	          pointStrokeColor    : 'rgba(60,141,188,1)',
	          pointHighlightFill  : '#fff',
	          pointHighlightStroke: 'rgba(60,141,188,1)',
	          data                : [28, 48, 40, 19, 86, 27, 90]
	        }
	      ]
	    }

	    var areaChartOptions = {
	      //Boolean - If we should show the scale at all
	      showScale               : true,
	      //Boolean - Whether grid lines are shown across the chart
	      scaleShowGridLines      : false,
	      //String - Colour of the grid lines
	      scaleGridLineColor      : 'rgba(0,0,0,.05)',
	      //Number - Width of the grid lines
	      scaleGridLineWidth      : 1,
	      //Boolean - Whether to show horizontal lines (except X axis)
	      scaleShowHorizontalLines: true,
	      //Boolean - Whether to show vertical lines (except Y axis)
	      scaleShowVerticalLines  : true,
	      //Boolean - Whether the line is curved between points
	      bezierCurve             : true,
	      //Number - Tension of the bezier curve between points
	      bezierCurveTension      : 0.3,
	      //Boolean - Whether to show a dot for each point
	      pointDot                : false,
	      //Number - Radius of each point dot in pixels
	      pointDotRadius          : 4,
	      //Number - Pixel width of point dot stroke
	      pointDotStrokeWidth     : 1,
	      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
	      pointHitDetectionRadius : 20,
	      //Boolean - Whether to show a stroke for datasets
	      datasetStroke           : true,
	      //Number - Pixel width of dataset stroke
	      datasetStrokeWidth      : 2,
	      //Boolean - Whether to fill the dataset with a color
	      datasetFill             : true,
	      //String - A legend template
	      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
	      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
	      maintainAspectRatio     : true,
	      //Boolean - whether to make the chart responsive to window resizing
	      responsive              : true
	    }

	    var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
	    var lineChart                = new Chart(lineChartCanvas)
	    var lineChartOptions         = areaChartOptions
	    lineChartOptions.datasetFill = false
	    lineChart.Line(areaChartData, lineChartOptions)		

	</script>

@endsection
