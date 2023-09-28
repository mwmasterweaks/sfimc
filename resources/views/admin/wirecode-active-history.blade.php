@extends('layout.adminweb')

@section('content')
  
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Wirecode Active History
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Wirecode Active History</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-success">

		            <div class="box-header">
						<div class="input-group margin pull-right">
							<label style="font-weight: normal;">Search active wirecode on a specific date</label>
							<div class='input-group date' id='divDateFrom'>
								<input id="DateFrom" name="DateFrom" type='text' class="form-control" style="font-weight: normal;" readonly />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
								<span class="input-group-btn">
								<a id="btnSearch" href="#" class="btn btn-success btn-flat" style="margin-right:5px;"><i class="fa fa-search"></i></a>
								<a id="btnClear" href="#" class="btn btn-success btn-flat" style="margin-right:5px;">clear</a>
							</span>
							</div>
						</div>		            
          			</div>
		            <div class="box-body table-responsive" style="min-height: 600px;">
			            <div class="col-md-12">
				    	@include('inc.admin.adminmessage')
			            </div>
		              	<table id="tblList" class="table table-bordered table-hover">
		                <thead>
			                <tr>
			                  <th>ID</th>
			                  <th>Code</th>
			                  <th>Description</th>
			                  <th style="text-align: right;">Amount Acquired</th>
			                  <th style="text-align: right;">Max Level</th>
			                  <th style="text-align: right;">Minimum qty</th>
			                  <th>Status</th>
			                  <th>Start Date</th>
			                  <th>End Date</th>
			                </tr>
		                </thead>
		                <tbody id="tblBodyList">
			            </tbody>
		              </table>
		            </div>
          		</div>
          	</div>
		</div>
	</section>
	<!-- /.content -->	

	<script type="text/javascript">

	    var isNewRecord = 0;
	    var intCurrentPage = 1;
	 	var isPageFirstLoad = true;

	    $(document).ready(function() {

			 $('#divDateFrom').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

	        $('#tblList').DataTable( {
				'paging'      : false,
				'lengthChange': false,
				'searching'   : false,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false,
	            "order": [[ 2, "asc" ]]
	        });

	        //Load Initial Data
	        getRecordList(intCurrentPage, '');
		 	isPageFirstLoad = false;

	    });

	    $("#btnSearch").click(function(){
	      	$("#tblList").DataTable().clear().draw();
  			search_data();
	    });

	    $("#btnClear").click(function(){
	      	$("#tblList").DataTable().clear().draw();
			$("#DateFrom").val(null)
  			getRecordList(intCurrentPage, '');
	    });

	    function search_data(){
			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					date: $("#DateFrom").val(),
				},
				url: "{{ route('do-search-active-wire') }}",
				dataType: "json",
				success: function(data){
					console.log(data);
					LoadRecordList(data);
				},
				error: function(data){
					
				},
				beforeSend:function(vData){
				}
	    	});

	    };
	    function getRecordList(vPageNo, vSearchText){
			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					status: ''
				},
				url: "{{ route('get-wirecode-history') }}",
				dataType: "json",
				success: function(data){
					console.log(data);
					LoadRecordList(data);
				},
				error: function(data){
					
				},
				beforeSend:function(vData){
				}
	    	});

	    };

	    function LoadRecordList(vList){
			console.log("LoadRecordList");
	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadRecordRow(vList[x]);
	    		}
	    	}

	    }
	    function LoadRecordRow(vData){
			console.log("LoadRecordRow");
			console.log(vData);
			
	    	var tblList = $("#tblList").DataTable();

	    	//tdID = vData.id;
		
			tdID = "<span style='font-weight:normal;'>" + vData.id + "</span>";

			tdCode = "<span style='font-weight:normal;'>" + vData.wirecode.code + "</span>";

			tdDescription = "<span style='font-weight:normal;'>" + vData.wirecode.description + "</span>";
			tdAmount_acquired = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.wirecode.amount_acquired,2) + "</span>";
			tdMax_level = "<span style='font-weight:normal;' class='pull-right'>" + vData.wirecode.max_level + "</span>";
			tdMinimum_qty = "<span style='font-weight:normal;' class='pull-right'>" + vData.wirecode.minimum_qty + "</span>";

			tdStatus = "";
			if(vData.wirecode.status == "Active"){
				tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.wirecode.status + "</span>";
			}else{
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.wirecode.status + "</span>";
			}
			tdStartDate = "<span style='font-weight:normal;' class='pull-right'>" + vData.start_date + "</span>";
			tdEndDate = "<span style='font-weight:normal;' class='pull-right'>" + vData.end_date + "</span>";
			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();
				console.log("check exist " + rowData[0] + " == " + vData.id);
			    if(rowData[0] == vData.id){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdCode;
			    	curData[2] = tdDescription;
			    	curData[3] = tdAmount_acquired;
			    	curData[4] = tdMax_level;
			    	curData[5] = tdMinimum_qty;
			    	curData[6] = tdStatus;
			    	curData[7] = tdStartDate;
			    	curData[8] = tdEndDate;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdID,
						tdCode,
						tdDescription, 
						tdAmount_acquired,
						tdMax_level,
						tdMinimum_qty,
						tdStatus,
						tdStartDate,
						tdEndDate
					]).draw();			
			}

	    }

	  

	    /*
	    $(window).scroll(function() {
	    	if(!isPageFirstLoad){
		       if($(window).scrollTop() + $(window).height() >= ($(document).height() - 10) ){
					intCurrentPage = intCurrentPage + 1;
					getRecordList(intCurrentPage, $('.searchtext').val());
		       }
	    	}
	    });*/

	</script>



@endsection
