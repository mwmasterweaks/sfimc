@extends('layout.adminweb')

@section('content')

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Top Direct Selling Report
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li class="active">Top Direct Selling Report</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-success">
		            <div class="box-header">
				        <div class="col-md-8" style="padding: 2px;">
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
								<a href="#" class="btn btn-info btn-flat" onclick="ReloadList(1)"><i class="fa fa-file-o"></i> Refresh</a>
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
				                  <th>IBO Number</th>
				                  <th>Member Name</th>
				                  <th style="text-align: right;">Total Sold</th>
				                </tr>
			                </thead>
			                <tbody>
				            </tbody>
		              	</table>
		            </div>

          		</div>
          	</div>
		</div>
	</section>

	<script type="text/javascript">

	    var intCurrentPage = 1;
	    var isPageFirstLoad = true;

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

	        //Load Initial Data
	      	intCurrentPage = 1;
	      	ReloadList(intCurrentPage);
	        isPageFirstLoad = false;

	    });

	    function ReloadList(vPageNo){
	      	$("#tblList").DataTable().clear().draw();
	        getRecordList(intCurrentPage);
       }

	    function getRecordList(vPageNo){

			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					DateFrom: $("#DateFrom").val(),
					DateTo: $("#DateTo").val(),
					PageNo: intCurrentPage
				},
				url: "{{ route('get-direct-selling-report') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.DirectSellingList);
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

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadRecordRow(vList[x]);
	    		}
	    	}

	    }

	    function LoadRecordRow(vData){

	    	var tblList = $("#tblList").DataTable();

	    	tdID = vData.MemberID;
			tdIBONumber = "<span style='font-weight:normal;'>" + vData.EntryCode + "</span>";
			tdMemberName = "<span style='font-weight:normal;'>" + vData.MemberName + "</span>";
			tdTotalDirectSales = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.TotalDirectSales,2) + "</span>";

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

			    var rowData = this.data();
			    if(rowData[0] == vData.MemberID){
					IsRecordExist = true;

			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdIBONumber;
			    	curData[2] = tdMemberName;
			    	curData[3] = tdTotalDirectSales;
			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				var rowNode = tblList.row.add([
						tdID,
						tdIBONumber,
						tdMemberName,
						tdTotalDirectSales
					]).draw().node();			
			}

	    }

	    $(window).scroll(function() {
	    	if(!isPageFirstLoad){
		       if($(window).scrollTop() + $(window).height() >= ($(document).height() - 10) ){
		          intCurrentPage = intCurrentPage + 1;
		          getRecordList(intCurrentPage);
		       }
		    }
	    });

	</script>




@endsection



