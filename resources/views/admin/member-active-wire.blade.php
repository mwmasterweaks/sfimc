@extends('layout.adminweb')

@section('content')
  
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Member Wirecode Active History
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
			                  <th>IBO</th>
			                  <th>Member Name</th>
			                  <th>Active Wire ID</th>
			                  <th>Wire Code</th>
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
				url: "{{ route('do-search-member-active-wire') }}",
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
				url: "{{ route('get-member-active-wire') }}",
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
			//console.log("LoadRecordList");
	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadRecordRow(vList[x]);
	    		}
	    	}

	    }
	    function LoadRecordRow(vData){
			console.log("LoadRecordRow");
			//console.log(vData);
			var member = vData.member;
			var member_entry = member.member_entry;
			//var order = vData.order;
			var wirecode_active = vData.wirecode_active;
			var wirecode = wirecode_active.wirecode;
	    	var tblList = $("#tblList").DataTable();

	    	tdID = vData.id;
	
			tdIBO = "<span style='font-weight:normal;'>" + member_entry.EntryCode + "</span>";
			tdMemberName = "<span style='font-weight:normal;'>" + member.FirstName + " " + member.LastName + "</span>";
			//tdOrder = "<span style='font-weight:normal;'>" + order.OrderNo + "</span>";
			tdActiveWireID = "<span style='font-weight:normal;'>" + wirecode_active.id + "</span>";
			tdWireCode = "<span style='font-weight:normal;'>" + wirecode.code + "</span>";

			
			tdStartDate = "<span style='font-weight:normal;' class='pull-right'>" + wirecode_active.start_date + "</span>";
			tdEndDate = "<span style='font-weight:normal;' class='pull-right'>" + wirecode_active.end_date + "</span>";
			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();
			    if(rowData[0] == vData.id){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdIBO;
			    	curData[2] = tdMemberName;
			    	//curData[3] = tdOrder;
			    	curData[3] = tdActiveWireID;
			    	curData[4] = tdWireCode;
			    	curData[5] = tdStartDate;
			    	curData[6] = tdEndDate;
			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }
			});
			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdID,
						tdIBO,
						tdMemberName, 
						//tdOrder,
						tdActiveWireID,
						tdWireCode,
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
