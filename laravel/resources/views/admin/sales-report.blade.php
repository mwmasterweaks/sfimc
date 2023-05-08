@extends('layout.adminweb')

@section('content')

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Sales Report
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li class="active">Sales Report</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-success">
		            <div class="box-header">
				        <div class="col-md-8" style="padding: 2px;">
					        <div class="col-md-12">
				                <label style="font-weight: normal;">Center <span style="color:red;">*</span></label>
	                            <div class='col-md-12' style="padding:0px; margin:0px;">
									<select id="SearchCenter" class="form-control select2" style="font-weight: normal;" {{ Session('IS_SUPER_ADMIN') == 1 ? "" : "disabled" }}>
										<option value="0" {{ Session('IS_SUPER_ADMIN') == 1 ? "selected" : "" }}>All</option>
										@foreach($CenterList as $clist)
											<option value="{{ $clist->CenterID }}" {{ Session('IS_SUPER_ADMIN') == 1 ? "" : ($clist->CenterID == $CenterID ? "selected" : "") }}>{{ $clist->Center }}</option>
										@endforeach
									</select>
						        </div>
					        </div>
					        <div class="col-md-12" style="margin-top: 5px;">
				                <label style="font-weight: normal;">From <span style="color:red;">*</span></label>
	                            <div class='input-group date' id='divDateFrom'>
	                                <input id="DateFrom" name="DateFrom" type='text' class="form-control" value="{{ $DateFrom }}" style="font-weight: normal;" readonly />
	                                <span class="input-group-addon">
	                                    <span class="glyphicon glyphicon-calendar"></span>
	                                </span>
	                            </div>
					        </div>
					        <div class="col-md-12" style="margin-top: 5px;">
				                <label style="font-weight: normal;">To<span style="color:red;">*</span></label>
	                            <div class='input-group date' id='divDateTo'>
	                                <input id="DateTo" name="DateTo" type='text' class="form-control" value="{{ $DateTo }}" style="font-weight: normal;" readonly />
	                                <span class="input-group-addon">
	                                    <span class="glyphicon glyphicon-calendar"></span>
	                                </span>
	                            </div>
					        </div>
				        </div>
				        <div class="col-md-4" style="padding: 2px;">
				        	<br>
							<span class="input-group-btn">
								<a href="#" class="btn btn-info btn-flat" onclick="getRecordList()"><i class="fa fa-file-o"></i> Refresh</a>
							</span>
						</div>		            
          			</div>
		            <div class="box-body table-responsive" style="min-height: 600px;">
			            <div class="col-md-12">
				    		@include('inc.admin.adminmessage')
			            </div>
		              	<table id="tblList" class="table table-bordered table-hover" style="overflow-x:auto;">
			                <thead>
				                <tr>
				                  <th>ID</th>
				                  <th>Center No.</th>
				                  <th>Center</th>
				                  <th style="text-align: right;">Total Sales</th>
				                </tr>
			                </thead>
			                <tbody>
				            </tbody>
		              	</table>
		              	<table id="tblCenterList" class="table table-bordered table-hover" style="overflow-x:auto; display: none;">
			                <thead>
				                <tr>
				                  <th>Date</th>
				                  <th style="text-align: right;">Total Sales</th>
				                </tr>
			                </thead>
			                <tbody>
				            </tbody>
		              	</table>
		            </div>

			        <div>
			            <br>
			            <label class="col-md-8" style="text-align: right;  margin-top: 5px;">Grand Total Sales : </label>
			            <div class="col-md-4">
			                <input id="GrandTotalSales" type="text" class="form-control" placeholder="Grand Total Sales"  style="text-align: right; width:100%;" value="0.00" disabled />
			            </div>
			            <br><br><br>
			        </div>

          		</div>
          	</div>
		</div>
	</section>

	<script type="text/javascript">

	    $(document).ready(function() {

            $('#divDateFrom').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('#divDateTo').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

	        $('#tblList').DataTable( {
				"columnDefs": [
				            {
				                "targets": [ 0 ],
				                "visible": false,
				                "searchable": false
				            }
				        ],
				'paging'      : false,
				'lengthChange': false,
				'searching'   : false,
				'ordering'    : false,
				'info'        : false,
				'autoWidth'   : false
	        });

	        $('#tblCenterList').DataTable( {
				'paging'      : false,
				'lengthChange': false,
				'searching'   : false,
				'ordering'    : false,
				'info'        : false,
				'autoWidth'   : false
	        });

	        //Load Initial Data
	        getRecordList();

	    });

	    function getRecordList(){

	    	$("#tblList").hide();
	    	$("#tblCenterList").hide();

	      	$("#tblList").DataTable().clear().draw();
	      	$("#tblCenterList").DataTable().clear().draw();

	      	$("#GrandTotalSales").val("0.00");

			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					Center: $("#SearchCenter").val(),
					DateFrom: $("#DateFrom").val(),
					DateTo:$("#DateTo").val()
				},
				url: "{{ route('get-center-sales-report') }}",
				dataType: "json",
				success: function(data){
					if($("#SearchCenter").val() == "0"){
				    	$("#tblList").show();
						LoadRecordList(data.AllCenterSalesList);
					}else{
				    	$("#tblCenterList").show();
						LoadCenterSalesRecordList(data.CenterSalesList);
					}

			        $("#divLoader").hide();
				},
				error: function(data){
					console.log(data.responseText);
			        $("#divLoader").hide();
				},
				beforeSend:function(vData){
        			$("#divLoader").show();
				}
	    	});
	    };

	    function LoadRecordList(vList){
	    	var GrandTotalSales = 0;

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadRecordRow(vList[x]);

					GrandTotalSales = GrandTotalSales + parseFloat(vList[x].TotalSales);
	    		}
	    	}

	      	$("#GrandTotalSales").val(FormatDecimal(GrandTotalSales,2));

	    }

	    function LoadRecordRow(vData){

	    	var tblList = $("#tblList").DataTable();

	    	tdID = vData.CenterID;
			tdCenterNo = "<span style='font-weight:normal;'>" + vData.CenterNo + "</span>";
			tdCenter = "<span style='font-weight:normal;'>" + vData.Center + "</span>";
			tdTotalSales = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.TotalSales,2) + "</span>";

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

			    var rowData = this.data();
			    if(rowData[0] == vData.CenterID){
					IsRecordExist = true;

			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdCenterNo;
			    	curData[2] = tdCenter;
			    	curData[3] = tdTotalSales;
			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				var rowNode = tblList.row.add([
						tdID,
						tdCenterNo,
						tdCenter,
						tdTotalSales
					]).draw().node();			
			}

	    }

	    function LoadCenterSalesRecordList(vList){

	    	var GrandTotalSales = 0;

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadCenterSalesRecordRow(vList[x]);

					GrandTotalSales = GrandTotalSales + parseFloat(vList[x].TotalSales);
	    		}
	    	}

	      	$("#GrandTotalSales").val(FormatDecimal(GrandTotalSales,2));
	    }

	    function LoadCenterSalesRecordRow(vData){

	    	var tblCenterList = $("#tblCenterList").DataTable();

			tdSalesDate = "<span style='font-weight:normal;'>" + vData.SalesDate + "</span>";
			tdTotalSales = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.TotalSales,2) + "</span>";


			//Check if record already listed
			var IsRecordExist = false;
			tblCenterList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

			    var rowData = this.data();
			    if(rowData[0] == vData.CenterID){
					IsRecordExist = true;

			    	curData = tblCenterList.row(rowIdx).data();
			    	curData[0] = tdSalesDate;
			    	curData[1] = tdTotalSales;
			    	tblCenterList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				var rowNode = tblCenterList.row.add([
						tdSalesDate,
						tdTotalSales
					]).draw().node();			
			}

	    }

	</script>




@endsection



