@extends('layout.adminweb')

@section('content')
  
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Wire Management
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Wire Management</li>
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
			                  <th></th>
			                  <th>Code</th>
			                  <th>Description</th>
			                  <th>Product Name</th>
			                  <th style="text-align: right;">Amount Acquired</th>
			                  <th style="text-align: right;">Max Level</th>
			                  <th style="text-align: right;">Minimum qty</th>
			                  <th>Status</th>
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
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Wire Information</b></h4>
          	</div>

          	<div class="modal-body">

				<input type="hidden" id="wirecodeID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Wire Code <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="code" type="text" class="form-control" placeholder="Wirecode" value="" style="width:100%; font-weight: normal;">
		                </div>
	            	</div>
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
							<select id="status" class="form-control select2" style="width: 100%; font-weight: normal;">
								<option value="Active" selected>Active</option>
								<option value="Inactive">Inactive</option>
							</select>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>    

				<div class="row">
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Description <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="description" type="text" class="form-control" placeholder="Description" value="" style="width:100%; font-weight: normal;">
		                </div>
	            	</div>
					<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Amount Aquired <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="amount_acquired" type="number" class="form-control" placeholder="Amount Aquired" value="" style="width:100%; font-weight: normal;">
		                </div>
	            	</div>
	            </div>

				
	            <div style="clear:both;"></div>
	            <br>  

				<div class="row">
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Product Name<span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                	<select id="product" class="form-control select2" style="width: 100%; height: 100px; font-weight: normal;">
								<option value="">Please Select</option>
								@foreach ($ProductList as $pkey)
									<option value="{{ $pkey->ProductID }}"
									data-brand="{{$pkey->Brand}}"
									data-category="{{$pkey->Category}}"
									data-measurement="{{$pkey->Measurement}}"
									data-centerprice="{{$pkey->CenterPrice}}"
									data-retailprice="{{$pkey->RetailPrice}}"
									data-distributorprice="{{$pkey->DistributorPrice}}"
									data-rebatablevalue="{{$pkey->RebateValue}}" 
									(vData.ProductID ==  {{ $pkey->ProductID }} ? "selected" : "")
									>{{ $pkey->ProductCode." - ".$pkey->ProductName }}</option>
								@endforeach
							</select>
		                </div>
	            	</div>
					<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Minimun Item Quantity <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="minimum_qty" type="number" class="form-control" placeholder="10" value="" style="width:100%; font-weight: normal;">
		                </div>
	            	</div>
	            </div>

				<div class="row">
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Max Level <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="max_level" type="number" class="form-control" placeholder="9" value="" style="width:100%; font-weight: normal;">
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
				url: "{{ route('get-wirecode') }}",
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
			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='EditSettings(" + JSON.stringify(vData) + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Edit Settings" + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> " +
	                        " </ul> " +
	                    " </div> " ;

			tdCode = "<span style='font-weight:normal;'>" + vData.code + "</span>";

			tdDescription = "<span style='font-weight:normal;'>" + vData.description + "</span>";
			tdProductName = "<span style='font-weight:normal;'>" + vData.product.ProductName + "</span>";
			tdAmount_acquired = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.amount_acquired,2) + "</span>";
			tdMax_level = "<span style='font-weight:normal;' class='pull-right'>" + vData.max_level + "</span>";
			tdMinimum_qty = "<span style='font-weight:normal;' class='pull-right'>" + vData.minimum_qty + "</span>";

			tdStatus = "";
			if(vData.status == "Active"){
				tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.status + "</span>";
			}else{
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.status + "</span>";
			}

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
			    	curData[3] = tdDescription;
			    	curData[4] = tdProductName;
			    	curData[5] = tdAmount_acquired;
			    	curData[6] = tdMax_level;
			    	curData[7] = tdMinimum_qty;
			    	curData[8] = tdStatus;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdID,
						tdOption,
						tdCode,
						tdDescription, 
						tdProductName, 
						tdAmount_acquired,
						tdMax_level,
						tdMinimum_qty,
						tdStatus
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

	    	isNewRecord = 1;
			Clearfields();
			$("#record-info-modal").modal();
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

			if($('#code').val() == "") {
				showJSMessage("Wirecode Information","Please enter Wirecode","OK");
			}else if($('#product').val() == "") {
				showJSMessage("Wirecode Information","Please enter product name","OK");
			}else if($('#status').val() == "") {
				showJSMessage("Wirecode Information","Please enter Status","OK");
			}else if($('#description').val() == "") {
				showJSMessage("Wirecode Information","Please enter  Description","OK");
			}else if($('#amount_acquired').val() == "") {
				showJSMessage("Wirecode Information","Please enter Amount acquired","OK");
			}else if($('#max_level').val() == "") {
				showJSMessage("Wirecode Information","Please enter Max Level","OK");
			}else if($('#minimum_qty').val() == "") {
				showJSMessage("Wirecode Information","Please enter Minimum Quantity","OK");
			}else{

				
				if($("#wirecodeID").val() == 0)
				{

					$.ajax({
						type: "post",
						data: {
							_token: '{{ $Token }}',
							code: $("#code").val(),
							productID: $("#product").val(),
							status: $("#status").val(),
							description: $("#description").val(),
							amount_acquired: $("#amount_acquired").val(),
							max_level: $("#max_level").val(),
							minimum_qty: $("#minimum_qty").val(),
						},
						url: "{{ route('do-save-wire') }}",
						dataType: "json",
						success: function(data){
							//console.log(data);
							buttonOneClick("btnSave", "Save", false);
							
							showMessage("Success","Save Successfully","OK");
							$("#record-info-modal").modal('hide');
							LoadRecordList(data);
							
						},
						error: function(data){
							showJSModalMessageJS("Save Package Failed","Error: " . data,"OK");
							buttonOneClick("btnSave", "Save", false);
							//console.log(data);
						},
						beforeSend:function(vData){
							buttonOneClick("btnSave", "", true);
						}
					});
				}
				else
				{
					$.ajax({
						type: "post",
						data: {
							_token: '{{ $Token }}',
							id: $("#wirecodeID").val(),
							productID: $("#product").val(),
							code: $("#code").val(),
							status: $("#status").val(),
							description: $("#description").val(),
							amount_acquired: $("#amount_acquired").val(),
							max_level: $("#max_level").val(),
							minimum_qty: $("#minimum_qty").val(),
						},
						url: "{{ route('do-update-wire') }}",
						dataType: "json",
						success: function(data){
							//console.log(data);
							buttonOneClick("btnSave", "Save", false);
							
							showMessage("Success","Updated Successfully","OK");
							$("#record-info-modal").modal('hide');
							LoadRecordList(data);
							
						},
						error: function(data){
							showJSModalMessageJS("Update Package Failed","Error: " . data,"OK");
							buttonOneClick("btnSave", "Save", false);
							//console.log(data);
						},
						beforeSend:function(vData){
							buttonOneClick("btnSave", "", true);
						}
					});
				}
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
