@extends('layout.adminweb')

@section('content')
  
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Wire Code Generation
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Wire Code Generation</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-success">

		            <div class="box-header">
						<div class="input-group margin pull-right">
							<input type="text" placeholder="Search Here..." class="form-control searchtext">
							<span class="input-group-btn">
								<a id="btnSearch" href="#" class="btn btn-success btn-flat" style="margin-right:5px;"><i class="fa fa-search"></i></a>
								<a href="#" class="btn btn-info btn-flat"  onclick="NewRecord()"><i class="fa fa-file-o"></i> New</a>
							</span>
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
			                  <th>Gen. ID</th>
			                  <th>Wirecode</th>
			                  <th>Wirecode Active ID</th>
			                  <th>Date Gen.</th>
			                  <th>Center</th>
			                  <th>Created By</th>
			                  <th>Code Count</th>
			                  <th>Code Issued</th>
			                  <th>Code used</th>
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

	<div id="record-info-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">

          	<div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Wire Code Generation</b></h4>
          	</div>

          	<div class="modal-body">

				<input type="hidden" id="wirecodeID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Active Wire Code <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="active_wire_code" type="text" class="form-control"  style="width:100%; font-weight: normal;" disabled>
		                </div>
	            	</div>
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Created by <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
							 <input id="created_by" type="text" class="form-control" value="{{ Session('ADMIN_FULLNAME') }}" style="width:100%; font-weight: normal;" disabled>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>    

				<div class="row">
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Center <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <select id="Center" class="form-control select2" style="width: 100%; height: 100px; font-weight: normal;">
								<option value="">Please Select</option>
								@foreach ($CenterList as $ctr)
									<option value="{{ $ctr->CenterID }}"
										>{{ $ctr->Center }}</option>
								@endforeach
							</select>
		                </div>
	            	</div>
					<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Code Count <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="code_count" type="number" class="form-control" placeholder="10" value="" style="width:100%; font-weight: normal;">
		                </div>
	            	</div>
	            </div>
	         
	            <div style="clear:both;"></div>
	            <br>                

	            <div class="modal-footer">
					<div class="input-group pull-right">
						<span class="input-group-btn">
							<a id="btnSave" href="#" class="btn btn-info btn-flat" onclick="SaveRecord()"><i class="fa fa-save"></i> Save</a>
							<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Cancel</a>
						</span>
					</div>	
	            </div>

         	</div>

	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<script type="text/javascript">

	    var isNewRecord = 0;
	    var intCurrentPage = 1;
	 	var isPageFirstLoad = true;

	    $(document).ready(function() {

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
  			search_data("code", $('.searchtext').val());
	    });

	    $('.searchtext').on('keypress', function (e) {
			if(e.which === 13){
		      	$("#tblList").DataTable().clear().draw();
	  			search_data("code", $('.searchtext').val());
			}
	    });

	    function search_data(by, vSearchText){
			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					search: vSearchText,
					by: by
				},
				url: "{{ route('do-search-wire') }}",
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
					Status: ''
				},
				url: "{{ route('get-wirecode-gen') }}",
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
	    	var tblList = $("#tblList").DataTable();

	    	tdID = vData.id;
			/*
			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> " +
	                          	" <li style='text-align:left;'> " +
	                              	//" <a href='#' onclick='EditSettings(" + JSON.stringify(vData) + ")'>" + 
	                              	//	" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Edit Settings" + 
	                              	//	" </strong>" +
	                              	//" </a> " +
	                           	" </li> " +
	                        " </ul> " +
	                    " </div> " ;*/
			tdOption = "<span style='font-weight:normal;'>" + vData.id + "</span>";
			tdCode = "<span style='font-weight:normal;'>" + vData.wirecode.code + "</span>";

			tdWireCodeActiveID = "<span style='font-weight:normal;'>" + vData.wirecode_active_id + "</span>";
			tdDate = "<span style='font-weight:normal;'>" + vData.date_gen + "</span>";
			tdCenter = "<span style='font-weight:normal;'>" + vData.center.Center + "</span>";
			tdCreated_by = "<span style='font-weight:normal;'>" + vData.created_by + "</span>";
			tdCode_count = "<span style='font-weight:normal;'>" + vData.code_count + "</span>";
			tdCode_issued = "<span style='font-weight:normal;'>" + vData.code_issued + "</span>";
			tdCode_used = "<span style='font-weight:normal;'>" + vData.code_used + "</span>";


			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();
				
			    if(rowData[0] == vData.id){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdOption;
			    	curData[2] = tdCode;
			    	curData[3] = tdWireCodeActiveID;
			    	curData[4] = tdDate;
			    	curData[5] = tdCenter;
			    	curData[6] = tdCreated_by;
			    	curData[7] = tdCode_count;
			    	curData[8] = tdCode_issued;
			    	curData[9] = tdCode_used;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdID,
						tdOption,
						tdCode,
						tdWireCodeActiveID, 
						tdDate, 
						tdCenter,
						tdCreated_by,
						tdCode_count,
						tdCode_issued,
						tdCode_used
					]).draw();			
			}

	    }

	    function Clearfields(){

			$("#wirecodeID").val(0);
			$("#code").val('');
			$("#product").val('');
			$("#description").val('');
			$("#amount_acquired").val('0.00');
			$("#max_level").val(0);
			$("#minimum_qty").val(0);
			$("#status").val('Active').change();

	    }

	    function NewRecord(){
			var activeWireData = @json($active_wire);
			if(activeWireData != null)
			{
				isNewRecord = 1;
				Clearfields();
				$("#active_wire_code").val(activeWireData.wirecode.code);
				
				$("#record-info-modal").modal();
			}
			else
				showJSMessage("Wirecode Information","No Active Wirecode!","OK");
			
	    	
	    }

	    function EditSettings(vData){
			
			$("#wirecodeID").val(vData.id);
			$("#product").val(vData.productID);
			$("#code").val(vData.code);
			$("#description").val(vData.description);
			$("#amount_acquired").val(FormatDecimal(vData.amount_acquired,2));
			$("#max_level").val(vData.max_level);
			$("#minimum_qty").val(vData.minimum_qty);
			$("#status").val(vData.status).change();
			$("#record-info-modal").modal();
			
	    }

	    function SaveRecord(){

			if($('#Center').val() == "") {
				showJSMessage("Generate Wirecode","Please select center.","OK");
			}else if($('#code_count').val() == "") {
				showJSMessage("Generate Wirecode","Please enter code count","OK");
			}else{
				var activeWireData = @json($active_wire);
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						wirecode_id: activeWireData.wirecode_id,
						wirecode_active_id: activeWireData.id,
						center_id: $('#Center').val(),
						created_by: $('#created_by').val(),
						code_count: $('#code_count').val(),
					},
					url: "{{ route('do-generate-wire') }}",
					dataType: "json",
					success: function(data){
						console.log(data);
						buttonOneClick("btnSave", "Save", false);
						
						showMessage("Success","Save Successfully","OK");
						$("#record-info-modal").modal('hide');
						LoadRecordList(data);
						
					},
					error: function(data){
						showJSModalMessageJS("Save Generate Wirecode Successfully","Error: " . data,"OK");
						
						$("#record-info-modal").modal('hide');
						LoadRecordList(data);
						buttonOneClick("btnSave", "Save", false);
						console.log(data);
					},
					beforeSend:function(vData){
						buttonOneClick("btnSave", "", true);
					}
				});
	      }
	    };
	    
		/*
	    $(window).scroll(function() {
	    	if(!isPageFirstLoad){
		       if($(window).scrollTop() + $(window).height() >= ($(document).height() - 10) ){
					intCurrentPage = intCurrentPage + 1;
					getRecordList(intCurrentPage, $('.searchtext').val());
		       }
	    	}
	    });
		*/

	</script>



@endsection
