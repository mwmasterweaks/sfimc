@extends('layout.adminweb')
@section('content')

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Inventory Adjustment
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Inventory Management</li>
			<li class="active">Inventory Adjustment</li>
		</ol>

	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-success">
		            <div class="box-header">
				        <div class="col-md-3" style="margin-top: 12px;">
							<select id="SearchCenter" class="form-control input-sm pull-right select2" {{ Session('IS_SUPER_ADMIN') == 1 ? "" : "disabled" }}>
								<option value="0" {{ Session('IS_SUPER_ADMIN') == 1 ? "selected" : "" }}>All</option>
								@foreach($CenterList as $clist)
									<option value="{{ $clist->CenterID }}" {{ Session('IS_SUPER_ADMIN') == 1 ? "" : ($clist->CenterID == Session('ADMIN_CENTER_ID') ? "selected" : "") }}>{{ $clist->Center }}</option>
								@endforeach
							</select>
				        </div>
				        <div class="col-md-9" style="padding: 2px;">
							<div class="input-group margin pull-right">
								<input type="text" placeholder="Search Here..." class="form-control searchtext">
								<span class="input-group-btn">
									<a id="btnSearch" href="#" class="btn btn-success btn-flat" style="margin-right:5px;"><i class="fa fa-search"></i></a>
									<a href="#" class="btn btn-info btn-flat" onclick="NewRecord()"><i class="fa fa-file-o"></i> New</a>
								</span>
							</div>		            
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
				                  <th></th>
				                  <th></th>
				                  <th>Center</th>
				                  <th>Adjustment No.</th>
				                  <th>DateTime</th>
				                  <th>Remarks</th>
				                  <th>Prepared By</th>
				                  <th>Approved By</th>
				                  <th>Status</th>
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

	<!-- /.content -->	
	<div id="record-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  	<div class="modal-dialog modal-lg" style="width: 90%;">
	    	<div class="modal-content">
          		<div class="modal-header" style="background-color: #3c8dbc;">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            	<h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Inventory Adjustment Information</b></span></h4>
	          	</div>
	          	<div class="modal-body">
	        		<input type="hidden" id="AdjustmentID" value="0" readonly>
	            	
	            	<div class="row">
	            		<div class="col-md-12">
		            		<label class="col-md-12" style="font-size: 15px;"><i class='fa fa-shopping-cart font-size:15px;'></i>&nbsp&nbsp<b>Inventory Adjustment Information</b></label>
	        			</div>
	        		</div>

		            <div class="row">
		            	<div class="col-md-12">
				            <div class="row">
				            	<div class="col-md-12">
					                <label class="col-md-12" style="font-weight: normal;">Center</label>
					                <div class="col-md-12">
		        						<input type="hidden" id="CenterID" value="0" readonly>
					                    <input id="Center" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" readonly>
					                </div>
				                </div>
							</div>	
						</div>	
					</div>

	            	<div class="row">
	            		<div class="col-md-4">
		                	<label class="col-md-12" style="font-weight: normal;">Adjustment No.</label>
		                	<div class="col-md-12">
		                    	<input id="AdjustmentNo" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight:bold; color:green;" readonly>
		                	</div>
	            		</div>
	            		<div class="col-md-4">
		                	<label class="col-md-12" style="font-weight: normal;">Date/Time<span style="color:red;">*</span></label>
		                	<div class="col-md-12">
		                    	<input id="AdjustmentDateTime" type="text" class="form-control" value=""  placeholder="Auto Generated" style="width:100%; font-weight:normal;" readonly>
	                		</div>
	            		</div>
	            		<div class="col-md-4">
		                	<label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
		                	<div class="col-md-12">
								<select id="Status" class="form-control select2" style="width: 100%; height: 100px;">
									<option value="Approved">Approved</option>
									<option value="Pending" selected>Pending</option>
									<option value="Cancelled">Cancelled</option>
								</select>
		                	</div>
	            		</div>
            		</div>

		            <div style="clear:both;"></div>
		            <br>                
		          
		            <div class="row">
		            	<div class="col-md-12">
			            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-file-text-o margin-r-5"></i> <b>Adjusted Items</b></label>
			        	</div>
		        	</div>

		            <div class="row">
		            	<div class="col-md-12">
			              	<table id="tblProductList" class="table table-bordered table-hover">
				                <thead>
					                <tr>
					                  <th>Count</th>
					                  <th>AdjustmentItemID</th>
					                  <th>AdjustmentID</th>
					                  <th style="width: 300px;">Product Name <span style="color:red;">*</span></th>
					                  <th>Type</th>
					                  <th style="width: 80px; text-align: right;">Qty <span style="color:red;">*</span></th>
					                  <th>Unit Measure</th>
					                  <th>Remarks</th>
					                  <th style="width: 80px; text-align: right;"></th>
					                </tr>
				                </thead>
				                <tbody>
					            </tbody>
			              	</table>
						</div>	
					</div>

		            <div style="clear:both;"></div>
		            <br>                

		            <div class="row">
		            	<div class="col-md-12">
				            <div class="row">
				            	<div class="col-md-12">
					                <label class="col-md-12" style="font-weight: normal;">Remarks</label>
					                <div class="col-md-12">
					                    <input id="Remarks" type="text" class="form-control" value="" style="width:100%; font-weight:normal;">
					                </div>
				                </div>
							</div>	
				            <div class="row">
				            	<div class="col-md-12">
					                <label class="col-md-12" style="font-weight: normal;">Prepared By</label>
					                <div class="col-md-12">
					                    <input id="PreparedBy" type="text" class="form-control" value="{{ Session('ADMIN_FULLNAME') }}" style="width:100%; font-weight:normal;" readonly>
					                </div>
				                </div>
							</div>	
						</div>	
					</div>

		            <div style="clear:both;"></div>
		            <br>    

            	</div>

	            <div class="modal-footer">
					<div class="input-group pull-right">
						<span class="input-group-btn">
							<a id="btnSave" href="#" class="btn btn-info btn-flat" onclick="SaveRecord()"><i class="fa fa-save"></i> Save</a>
							<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Cancel</a>
						</span>
					</div>	
	            </div>

	    	</div><!-- /.modal-content -->
	  	</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="set-as-cancelled-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  	<div class="modal-dialog modal-lg">
	    	<div class="modal-content">
	          	<div class="modal-header" style="background-color: #3c8dbc;">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            	<h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Cancel Inventory Adjustment</b></span></h4>
	          	</div>
	          	<div class="modal-body">
	        		<input type="hidden" id="CancelledAdjustmentID" value="0" readonly>
	            	<div class="row">
	            		<div class="col-md-12">
		                	<label class="col-md-12" style="font-weight: normal;">Are you sure you want to cancel this inventory adjustment?</label>
	            		</div>
            		</div>
	            	<div style="clear:both;"></div>
	            	<br>                
	            	<div class="row">
	            		<div class="col-md-12">
		                	<label class="col-md-12" style="font-weight: normal;">Reason </label>
		                	<div class="col-md-12">
	                			<textarea id="CancellationReason" class="form-control" cols="40" rows="8"  placeholder="Reason" style="width:100%;"></textarea>
		                	</div>
	            		</div>
	            	</div>

	            	<div style="clear:both;"></div>
	            	<br>                

	            	<div class="modal-footer">
						<div class="input-group pull-right">
							<span class="input-group-btn">
								<a id="btnSaveCancel" href="#" class="btn btn-info btn-flat" onclick="ProceedCancel()"><i class="fa fa-save"></i> Proceed</a>
								<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Cancel</a>
							</span>
						</div>	
	            	</div>
	          	</div>
	    	</div><!-- /.modal-content -->
	  	</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<script type="text/javascript">

	    var intCurrentPage = 1;
	 	var intItemCount = 0;
		var intDeletedItem = 0;
		var pDeletedItem = [];
		var isPageFirstLoad = true;
		var isCleared = 0;

	    $(document).ready(function() {

	        $('#tblList').DataTable( {
				"columnDefs": [
				            {
				                "targets": [ 0 ],
				                "visible": false,
				                "searchable": false
				            },
				            {
				                "targets": [ 1 ],
				                "visible": false,
				                "searchable": false
				            }				        ],
				'paging'      : false,
				'lengthChange': false,
				'searching'   : false,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false,
	            "order": [[ 1, "asc" ], [ 5, "desc" ]]
	        });

	        $('#tblProductList').DataTable( {
				"columnDefs": [
				            {
				                "targets": [ 0 ],
				                "visible": false,
				                "searchable": false
				            },
				            {
				                "targets": [ 1 ],
				                "visible": false,
				                "searchable": false
				            },
				            {
				                "targets": [ 2 ],
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
	            "order": [[ 3, "asc" ]]
	        });

	        //Load Initial Data
	        getRecordList(intCurrentPage, '');
		 	isPageFirstLoad = false;

	    });

	    $("#SearchCenter").change(function(){
	      	$("#tblList").DataTable().clear().draw();
	      	intCurrentPage = 1;
  			getRecordList(intCurrentPage, $('.searchtext').val());
	    });

	    $("#btnSearch").click(function(){
	      	$("#tblList").DataTable().clear().draw();
	      	intCurrentPage = 1;
  			getRecordList(intCurrentPage, $('.searchtext').val());
	    });

	    $('.searchtext').on('keypress', function (e) {
			if(e.which === 13){
		      	$("#tblList").DataTable().clear().draw();
				intCurrentPage = 1;
				getRecordList(intCurrentPage, $('.searchtext').val());
			}
	    });

	    function getRecordList(vPageNo, vSearchText){

			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					CenterID: $("#SearchCenter").val(),
					Status: '',
					SearchText: vSearchText,
					PageNo: vPageNo
				},
				url: "{{ route('get-inventory-adjustment-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.InventoryAdjustmentList);
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
	    	hideMessage();
	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadRecordRow(vList[x]);
	    		}
	    	}
	    }

	    function LoadRecordRow(vData){

	    	var tblList = $("#tblList").DataTable();

	    	tdID = vData.AdjustmentID;
	    	tdSortOption = vData.SortOption;
			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn btn-success' data-toggle='dropdown'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> ";
		                  	tdOption += " <li style='text-align:left;'> " +
		                      	" <a href='#' onclick='EditRecord(" + vData.AdjustmentID + "," + (vData.Status == "Approved" || vData.Status == 'Cancelled' ? false : true) + ")'>" + 
		                      		" <strong><i class='fa fa-info-circle font-size:15px;'></i> " + (vData.Status == "Approved"  || vData.Status == 'Cancelled' ? "View Record" : "Edit Record") + 
		                      		" </strong>" +
		                      	" </a> " +
		                   	" </li> ";

	        			if(vData.Status != 'Cancelled'){
                          	tdOption +=
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='CancelInventoryAdjustment(" + vData.AdjustmentID + ")'>" + 
	                              		" <strong><i class='fa fa-close font-size:15px;'></i> Cancel Inventory Adjustment" + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";
		        		}

            tdOption += " </ul> " +
                    " </div> " ;

			tdCenter = "<span style='font-weight:normal; text-align:center;'>" + vData.Center + "</span>";

			tdAdjustmentNo = "<span style='font-weight:bold;'>" + vData.AdjustmentNo + "</span>";
			tdAdjustmentDateTime = "<span style='font-weight:normal;'>" + vData.AdjustmentDateTime + "</span>";

			tdRemarks = "<span style='font-weight:normal; text-align:center;'>" + vData.Remarks + "</span>";

			tdPreparedBy = "<span style='font-weight:normal; text-align:center;'>" + vData.CreatedBy + "</span>";
			tdApprovedBy = "<span style='font-weight:normal; text-align:center;'>" + vData.ApprovedBy + "</span>";

			if(vData.Status == "Pending"){
				tdStatus = "<span class='label label-warning' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}else if(vData.Status == "Cancelled"){
				tdStatus = "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.Status + "</span>";
			}else{
				tdStatus = "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

			    var rowData = this.data();
			    if(rowData[0] == vData.AdjustmentID){
					IsRecordExist = true;

			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdSortOption;
			    	curData[2] = tdOption;
			    	curData[3] = tdCenter;
			    	curData[4] = tdAdjustmentNo;
			    	curData[5] = tdAdjustmentDateTime;
			    	curData[6] = tdRemarks;
			    	curData[7] = tdPreparedBy;
			    	curData[8] = tdApprovedBy;
			    	curData[9] = tdStatus;
			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }
			});

			if(!IsRecordExist){
		    	//New Row
				var rowNode = tblList.row.add([
						tdID,
						tdSortOption,
						tdOption,
						tdCenter,
						tdAdjustmentNo,
						tdAdjustmentDateTime, 
						tdRemarks,
						tdPreparedBy,
						tdApprovedBy,
						tdStatus
					]).draw().node();			
			}
	    }

	    function Clearfields(){

			$("#AdjustmentID").val('0');

			$("#AdjustmentNo").val('');
			$("#AdjustmentDateTime").val('');
			$("#Status").val('Pending').change();

			$("#CenterID").val('{{ Session('ADMIN_CENTER_ID') }}');
			$("#Center").val('{{ Session('ADMIN_CENTER') }}');

			$("#Remarks").val('');
			$("#PreparedBy").val('{{ Session('ADMIN_FULLNAME') }}');

	        //Load Initial Data
    		intItemCount = 0;
    		intDeletedItem = 0;
    		pDeletedItem=[];

    		$("#tblProductList").DataTable().clear().draw();

	        AddEmptyRow(0);

			$("#Status").prop('disabled', false);
			$("#Remarks").prop('disabled', false);

			$("#btnSave").show();

            IsCleared = 0;

	    }

	    function NewRecord(){

			Clearfields();
			$("#record-modal").modal();

	    }

	    function EditRecord(vRecordID, vIsEditable){

	    	if(vRecordID > 0){

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						AdjustmentID: vRecordID
					},
					url: "{{ route('get-inventory-adjustment-info') }}",
					dataType: "json",
					success: function(data){

				        $("#divLoader").hide();

						if(data.Response =='Success' && data.InventoryAdjustmentInfo != undefined){
							Clearfields();

							$("#AdjustmentID").val(data.InventoryAdjustmentInfo.AdjustmentID);
							$("#AdjustmentNo").val(data.InventoryAdjustmentInfo.AdjustmentNo);
							$("#AdjustmentDateTime").val(data.InventoryAdjustmentInfo.AdjustmentDateTime);
							$("#Status").val(data.InventoryAdjustmentInfo.Status).change();

							$("#CenterID").val(data.InventoryAdjustmentInfo.CenterID);
							$("#Center").val(data.InventoryAdjustmentInfo.Center);

							$("#Remarks").val(data.InventoryAdjustmentInfo.Remarks);
							$("#PreparedBy").val(data.InventoryAdjustmentInfo.CreatedBy);

							$("#Status").prop('disabled', !vIsEditable);
							$("#Remarks").prop('disabled', !vIsEditable);

							getItem(data.InventoryAdjustmentInfo.AdjustmentID, data.InventoryAdjustmentInfo.Status, vIsEditable);

							if(vIsEditable){
								$("#btnSave").show();
							}else{
								$("#btnSave").hide();
							}

						}else{
							showJSModalMessageJS("Inventory Adjustment Information",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnSave", "Save", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
				        $("#divLoader").show();
						buttonOneClick("btnSave", "", false);
					}
	        	});
	    	}
	    }

	    function getItem(vAdjustmentID, vStatus, vIsEditable){

	    	if(vAdjustmentID > 0){
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						AdjustmentID: vAdjustmentID
					},
					url: "{{ route('get-inventory-adjustment-item-list') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();

						if(data.Response =='Success' && data.InventoryAdjustmentItemList != undefined){

					        //Load Initial Data
				    		intItemCount = 0;
				    		intDeletedItem = 0;
				    		pDeletedItem=[];
				    		$("#tblProductList").DataTable().clear().draw();

					        if(data.InventoryAdjustmentItemList.length > 0){
						        LoadItemList(vAdjustmentID, vStatus, data.InventoryAdjustmentItemList, vIsEditable);
					        }else{
						        AddEmptyRow(vAdjustmentID);
					        }

							$("#record-modal").modal();
							buttonOneClick("btnSave", "Save", false);
						}else{
							showJSModalMessageJS("Inventory Adjustment Item",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnSave", "Save", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
				        $("#divLoader").show();
						buttonOneClick("btnSave", "Save", false);
					}
	        	});
	    	}
	    }

	    function LoadItemList(vAdjustmentID, vStatus, vList, vIsEditable){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadItemRow(vAdjustmentID, vStatus, vList[x], vIsEditable);
	    		}
	    	}
	    }

	    function LoadItemRow(vAdjustmentID, vStatus, vData, vIsEditable){

	    	var tblProductList = $("#tblProductList").DataTable();
	    	intItemCount  = intItemCount  + 1;

	    	tdID = intItemCount;
	    	tdAdjustmentItemID = vData.AdjustmentItemID;
	    	tdAdjustmentID = vAdjustmentID;

	    	if(vIsEditable){
				tdProduct = "<select id='Product-" + intItemCount  + "' class='form-control select2' onChange='SelectProduct("  +  intItemCount + ")' style='width: 100%; font-weight:normal;'>";
				tdProduct += "<option value=''>Please Select</option>";
					@foreach ($ProductList as $pkey)
						tdProduct += "<option value='{{ $pkey->ProductID }}' ";
						tdProduct += " data-brand='{{$pkey->Brand}}' ";
						tdProduct += " data-category='{{$pkey->Category}}' ";
						tdProduct += " data-measurement='{{$pkey->Measurement}}' ";
						tdProduct += (vData.ProductID ==  {{ $pkey->ProductID }} ? "selected" : "");
						tdProduct += ">";
						tdProduct += "{{ $pkey->ProductCode.' - '.$pkey->ProductName }}</option>";
					@endforeach			
				tdProduct += "</select>";
	    	}else{
		    	tdProduct = "<input id='Product-" + intItemCount  + "'  type='text' class='form-control' value='" + vData.ProductName + " - " + vData.ProductName + "' style='width:100%; font-weight:normal;' readonly>";
	    	}

	    	if(vIsEditable){
				tdType = "<select id='Type-" + intItemCount  + "' class='form-control select2' style='width: 100%; font-weight:normal;'>";
				tdType += "<option value=''>Please Select</option>";
				tdType += "<option value='Add To Inventory' " + (vData.Type == 'Add To Inventory' ? "selected" : "") + ">Add To Inventory</option>";
				tdType += "<option value='Remove From Inventory' " + (vData.Type == 'Remove From Inventory' ? "selected" : "") + ">Remove From Inventory</option>";
				tdType += "</select>";
	    	}else{
				tdType = "<input id='Type-" + intItemCount  + "'  type='text' class='form-control' value='" + vData.Type + "' style='width:100%; font-weight:normal;' readonly>"
	    	}

			tdQty = "<input id='Qty-" + intItemCount  + "'  type='text' class='form-control numberonly' value='" + FormatDecimal(vData.Qty,0) + "' style='width:100%; text-align:right; font-weight:normal;' " + (vIsEditable ? "" : "readonly") + ">";

			tdUnitMeasure = "<input id='UnitMeasure-" + intItemCount  + "'  type='text' class='form-control' value='" + vData.Measurement + "' style='width:100%; font-weight:normal;' readonly>";

			tdRemarks = "<input id='Remarks-" + intItemCount  + "'  type='text' class='form-control' value='" + vData.Remarks + "' style='width:100%; font-weight:normal;' " + (vIsEditable ? "" : "readonly") + " >";

			tdButtons = "<div class='col-md-12' style='margin-top:5px;'>";
			tdButtons +="	<div class='pull-right'>";
			if(vIsEditable){
				tdButtons += "		<label style='cursor:pointer;font-size:12px;margin-right:2px;' class='label label-primary'  onClick='AddEmptyRow(" + vAdjustmentID + ")'>";
				tdButtons +="			<i class='fa fa-plus'></i>";
				tdButtons +="		</label>";
				tdButtons +="		<label style='cursor:pointer;font-size:12px;' class='label label-danger' onClick='DeleteRow(" + intItemCount + ")'>";
				tdButtons +="			<i class='fa fa-trash'></i> ";
				tdButtons +="		</label>";
			}
			tdButtons +="	</div>";
			tdButtons +="</div>";

			//Check if record already listed
			var IsRecordExist = false;
			tblProductList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();
			    if(rowData[1] == vData.AdjustmentItemID){

					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblProductList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdAdjustmentItemID;
			    	curData[2] = tdAdjustmentID;
			    	curData[3] = tdProduct;

			    	curData[4] = tdType;
			    	curData[5] = tdQty;
			    	curData[6] = tdUnitMeasure;

			    	curData[7] = tdRemarks;
			    	curData[8] = tdButtons;

			    	tblProductList.row(rowIdx).data(curData).invalidate().draw();
			    }
			});

			if(!IsRecordExist){
		    	//New Row
				var rowNode = tblProductList.row.add([
						tdID,
						tdAdjustmentItemID,
						tdAdjustmentID,
						tdProduct,
						tdType, 
						tdQty,
						tdUnitMeasure,
						tdRemarks,
						tdButtons
					]).draw().node();			
			}

			$('.select2').select2();
			$(".select2").css("font-weight", "normal");
          	$(".DecimalOnly").on("keypress keyup blur",function (event) {
              $(this).val($(this).val().replace(/[^0-9\.]/g,''));
              if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                  event.preventDefault();
              }
          	});
	    }

	    function AddEmptyRow(vAdjustmentID){

	    	var tblProductList = $("#tblProductList").DataTable();
	    	intItemCount = intItemCount + 1;

	    	tdID = intItemCount ;
	    	tdAdjustmentItemID = 0;
	    	tdAdjustmentID = vAdjustmentID;

			tdProduct = "<select id='Product-" + intItemCount  + "' class='form-control select2' onChange='SelectProduct("  +  intItemCount + ")' style='width: 100%; font-weight:normal;'>";
			tdProduct += "<option value=''>Please Select</option>";
				@foreach ($ProductList as $pkey)
					tdProduct += "<option value='{{ $pkey->ProductID }}' ";
					tdProduct += " data-brand='{{$pkey->Brand}}' ";
					tdProduct += " data-category='{{$pkey->Category}}' ";
					tdProduct += " data-measurement='{{$pkey->Measurement}}' ";
					tdProduct += ">";
					tdProduct += "{{ $pkey->ProductCode.' - '.$pkey->ProductName }}</option>";
				@endforeach			
			tdProduct += "</select>";

			tdType = "<select id='Type-" + intItemCount  + "' class='form-control select2' style='width: 100%; font-weight:normal;'>";
			tdType += "<option value=''>Please Select</option>";
			tdType += "<option value='Add To Inventory'>Add To Inventory</option>";
			tdType += "<option value='Remove From Inventory'>Remove From Inventory</option>";
			tdType += "</select>";

			tdQty = "<input id='Qty-" + intItemCount  + "'  type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight:normal;'" + ">";

			tdUnitMeasure = "<input id='UnitMeasure-" + intItemCount  + "'  type='text' class='form-control' value='' style='width:100%; font-weight:normal;' readonly>";

			tdRemarks = "<input id='Remarks-" + intItemCount  + "'  type='text' class='form-control' value='' style='width:100%; font-weight:normal;'>";

			tdButtons = "<div class='col-md-12' style='margin-top:5px;'>";
			tdButtons +="	<div class='pull-right'>";
			tdButtons += "		<label style='cursor:pointer;font-size:12px;margin-right:2px;' class='label label-primary'  onClick='AddEmptyRow(" + vAdjustmentID + ")'>";
			tdButtons +="			<i class='fa fa-plus'></i>";
			tdButtons +="		</label>";
			tdButtons +="		<label style='cursor:pointer;font-size:12px;' class='label label-danger' onClick='DeleteRow(" + intItemCount + ")'>";
			tdButtons +="			<i class='fa fa-trash'></i> ";
			tdButtons +="		</label>";
			tdButtons +="	</div>";
			tdButtons +="</div>";

			tblProductList.row.add([
					tdID,
					tdAdjustmentItemID,
					tdAdjustmentID,
					tdProduct,
					tdType, 
					tdQty,
					tdUnitMeasure,
					tdRemarks,
					tdButtons
				]).draw();			

			$('.select2').select2();
			$(".select2").css("font-weight", "normal");
          	$(".DecimalOnly").on("keypress keyup blur",function (event) {
            $(this).val($(this).val().replace(/[^0-9\.]/g,''));

	          	if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {

	              event.preventDefault();

	          	}
          	});


	    }

	    function DeleteRow(vID){

			//Remove Row
			var vIsDeleted = false;
	    	var tblProductList = $("#tblProductList").DataTable();

			tblProductList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();
			    if(!vIsDeleted){
				    if(rowData[0] == vID){
				    	if(rowData[1] > 0){
				    		pDeletedItem[intDeletedItem] = rowData[1]; 
					    	intDeletedItem = intDeletedItem + 1;
				    	}

				    	tblProductList.row(rowIdx).remove().draw();
				    	vIsDeleted = true;
				    }
				}
			});
	    }

	    function SelectProduct(vIndex){

	      	if($("#Product-" + vIndex).find('option:selected').data('measurement') != undefined){

	      		var Measurement = $("#Product-" + vIndex).find('option:selected').data('measurement');


	        	$("#UnitMeasure-" + vIndex).val(Measurement);

	      	}

	    }

	    function SaveRecord(){

	    	var tblProductList = $("#tblProductList").DataTable();
            var pData = [];
            var intCntr = 0;

	    	//Check Supplier Product Fields
	    	for (var i = 1; i <= intItemCount; i++) {

	    		var AdjustmentItemID = 0;
	    		var AdjustmentID = 0;
	    		var ProductID = 0;
	    		var Type = "";
	    		var Qty = 0;
	    		var UnitMeasure = "";
	    		var Remarks = "";
		    	var blnIsIncomplete = false;

	    		//Get Supplier Product ID and Supplier ID
				tblProductList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

				    var rowData = this.data();
				    if(rowData[0] == i){
				    	AdjustmentItemID = rowData[1]
				    	AdjustmentID = rowData[2]
				    }
				});

	    		if($('#Product-' + i).length){
	    			if($("#Product-" + i).val() != ""){
			            ProductID = $("#Product-" + i).val();
	    			}else{
			    		blnIsIncomplete = true;
	    			}
	    		}else{
		    		blnIsIncomplete = true;
    			}

	    		if($('#Type-' + i).length){
	    			if($("#Type-" + i).val()!= ""){
			            Type = $("#Type-" + i).val();
	    			}else{
			    		blnIsIncomplete = true;
	    			}
	    		}else{
			    		blnIsIncomplete = true;
    			}

	    		if($('#Qty-' + i).length){
	    			if($("#Qty-" + i).val()!= ""){
			            Qty = $("#Qty-" + i).val();
	    			}else{
			    		blnIsIncomplete = true;
	    			}
	    		}else{
			    		blnIsIncomplete = true;
    			}

    			if($("#UnitMeasure-" + i).val() != ""){
		            UnitMeasure = $("#UnitMeasure-" + i).val();
    			}

	    		if($('#Remarks-' + i).length){
	    			if($("#Remarks-" + i).val() != ""){
			            Remarks = $("#Remarks-" + i).val();
	    			}else{
			    		blnIsIncomplete = true;
	    			}
	    		}else{
			    		blnIsIncomplete = true;
    			}

	    		if(!blnIsIncomplete){
					pData[intCntr] = {
						AdjustmentItemID:AdjustmentItemID,
						AdjustmentID:AdjustmentID,
						ProductID:ProductID,
						Type:Type,
						Qty:Qty,
						UnitMeasure:UnitMeasure,
						Remarks:Remarks
					};

					intCntr = intCntr + 1;
	    		}
	    	}

			if(pData.length <= 0){
				showJSModalMessageJS("Save Inventory Adjustment","Please complete inventory adjustment item required information.","OK");
			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						AdjustmentID: $("#AdjustmentID").val(),
						CenterID: $("#CenterID").val(),
						Remarks: $("#Remarks").val(),
						Status: $("#Status").val(),

						InvAdjItems: pData,
						InvAdjItemsDeleted: pDeletedItem

					},
					url: "{{ route('do-save-inventory-adjustment') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							$("#record-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.InventoryAdjustmentInfo);
						}else{
							showJSModalMessageJS("Save Inventory Adjustment",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnSave", "Save", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
				        $("#divLoader").show();
						buttonOneClick("btnSave", "", true);
					}
	        	});
			}
	    };

	    function CancelInventoryAdjustment(vAdjustmentID){

	    	if(vAdjustmentID > 0){

				$("#CancelledAdjustmentID").val(vAdjustmentID);
				$("#CancellationReason").text('');				
				$("#set-as-cancelled-modal").modal();

	    	}
	    }

	    function ProceedCancel(){

	    	if($("#CancellationReason").val() == ""){

				showJSModalMessageJS("Cancel Inventory Adjustment","Please enter the reason for the cancellation of this inventory adjustment.","OK");

	    	}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						AdjustmentID: $("#CancelledAdjustmentID").val(),
						CancellationReason : $("#CancellationReason").val()
					},

					url: "{{ route('do-cancel-inventory-adjustment') }}",

					dataType: "json",

					success: function(data){

				        $("#divLoader").hide();
						buttonOneClick("btnSaveCancel", "Save", false);
						if(data.Response =='Success'){
							$("#set-as-cancelled-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.InventoryAdjustmentInfo);
						}else{
							showJSModalMessageJS("Inventory Adjustment Cancellation",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnSaveCancel", "Proceed", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
				        $("#divLoader").show();
						buttonOneClick("btnSaveCancel", "", true);
					}
	        	});

	    	}



	    };

	    $(window).scroll(function() {
	    	if(!isPageFirstLoad){
		       if($(window).scrollTop() + $(window).height() >= ($(document).height() - 10) ){
		          	intCurrentPage = intCurrentPage + 1;
		          	getRecordList(intCurrentPage, $('.searchtext').val());
		       	}
	    	}
	    });


	</script>















@endsection



