@extends('layout.adminweb')

@section('content')

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Purchase Receive
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li class="active">Purchase Receive</li>
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
							<div class="input-group">
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
				                  <th>Sort</th>
				                  <th></th>
				                  <th>Receive No.</th>
				                  <th>DateTime</th>
				                  <th>Process No.</th>
				                  <th>PO No.</th>
				                  <th>Center</th>
				                  <th>Tel. No.</th>
				                  <th>Mobile No.</th>
				                  <th>Email Address</th>
				                  <th style="text-align: right;">Total Amount Due</th>
				                  <th>Approve By</th>
				                  <th>Approve Date/Time</th>
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
	<div id="record-info-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  	<div class="modal-dialog modal-lg" style="width: 90%;">
		    <div class="modal-content">
	         	<div class="modal-header" style="background-color: #3c8dbc;">
		            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		            <h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Purchase Receive Information</b></span></h4>
	          	</div>
	          	<div class="modal-body">
	        		<input type="hidden" id="ReceiveID" value="0" readonly>
		            <div class="row">
		            	<div class="col-md-12">
			            	<label class="col-md-12" style="font-size: 15px;"><i class='fa fa-shopping-cart font-size:15px;'></i>&nbsp&nbsp<b>Purchase Receive Information</b></label>
			        	</div>
		        	</div>

		            <div class="row">
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Process No.</label>
			                <div class="col-md-12">
			                    <input id="ReceiveNo" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight:bold; color:green;" readonly>
			                </div>
		            	</div>
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Date/Time<span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="ReceiveDateTime" type="text" class="form-control" value=""  placeholder="Auto Generated" style="width:100%; font-weight:normal;" readonly>
			                </div>
		            	</div>

		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
			                <div class="col-md-12">
								<select id="Status" class="form-control select2" style="width: 100%; height: 100px; font-weight: normal;">
									<option value="{{ config('app.STATUS_PENDING') }}" selected>{{ config('app.STATUS_PENDING') }}</option>
									<option value="{{ config('app.STATUS_APPROVED') }}">{{ config('app.STATUS_APPROVED') }}</option>
									<option value="{{ config('app.STATUS_CANCELLED') }}">{{ config('app.STATUS_CANCELLED') }}</option>
								</select>
			                </div>
		            	</div>
		            </div>
		            <div style="clear:both;"></div>
		            <br>                
		            <div class="row">
		            	<div class="col-md-12">
			            	<label class="col-md-12" style="font-size: 15px;"><i class='fa fa-info font-size:15px;'></i>&nbsp&nbsp<b>Center Information</b></label>
			        	</div>
		        	</div>
		            <div style="clear:both;"></div>
		            <br>                
		            <div class="row">
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Process No. <span style="color:red;">*</span></label>
			                <div class="col-md-12" style="font-weight: normal;">
			                    <input id="ProcessID" type="hidden" class="form-control" value="" readonly>
								<input id="ProcessNo" type="text" data-type="ProcessNo" class="form-control autocomplete_txt"  autocomplete="off" value="" style="width:100%; font-weight: normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">PO No. <span style="color:red;">*</span></label>
			                <div class="col-md-12" style="font-weight: normal;">
								<input id="PONo" type="text" class="form-control"  value="" style="width:100%; font-weight: normal;" readonly>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Center <span style="color:red;">*</span></label>
			                <div class="col-md-12" style="font-weight: normal;">
			                    <input id="CenterID" type="hidden" class="form-control" value="" readonly>
								<input id="Center" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
			                </div>
		            	</div>
					</div>
		            <div style="clear:both;"></div>
		            <br>                
		            <div class="row">
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Tel. No. <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="TelNo" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" readonly>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Mobile No. <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="MobileNo" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" readonly>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Email Address <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="EmailAddress" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" readonly>
			                </div>
		            	</div>
		            	<div class="col-md-12">
			                <label class="col-md-12" style="font-weight: normal;">Address <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="Address" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" readonly>
			                </div>
		            	</div>
		            </div>
		            <div style="clear:both;"></div>
		            <br>                
		            <div class="row">
		            	<div class="col-md-12">
			              	<table id="tblProductList" class="table table-bordered table-hover">
				                <thead>
					                <tr>
					                  <th>Count</th>
					                  <th>ReceiveItemID</th>
					                  <th>ReceiveID</th>
					                  <th style="width: 25%;">Product Name <span style="color:red;">*</span></th>
					                  <th style="text-align: right;">Qty <span style="color:red;">*</span></th>
					                  <th style="text-align: right;">Receive Qty <span style="color:red;">*</span></th>
					                  <th style="text-align: right;">Unit Measure</th>
					                  <th style="text-align: right;">Price <span style="color:red;">*</span></th>
					                  <th style="text-align: right;">Sub Total <span style="color:red;">*</span></th>
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
		            	<div class="col-md-6">
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
		            	<div class="col-md-6">
				            <div class="row">
				            	<div class="col-md-12">
									<div class="input-group margin pull-right">
										<span class="input-group-btn">
											<a href="#" class="btn btn-primary btn-flat" style="width: 150px; text-align: right;">Total Gross Amount</a>
										</span>
										<input id="TotalGrossAmount" type="text" placeholder="Total Gross Amount" class="form-control DecimalOnly" style="width:100%; font-weight:normal; text-align: right;" readonly>
									</div>		
								</div>		
							</div>	
				            <div class="row">
				            	<div class="col-md-12">
									<div class="input-group margin pull-right">
										<span class="input-group-btn">
											<a href="#" class="btn btn-success btn-flat" style="width: 150px; text-align: right;">Total Discount</a>
										</span>
										<input id="TotalDiscountPercent" type="text" placeholder="Total Discount (%)" class="form-control DecimalOnly RecomputeDiscountPercent" style="width:50%; font-weight:normal; text-align: right;" readonly>
										<input id="TotalDiscount" type="text" placeholder="Total Discount" class="form-control DecimalOnly RecomputeDiscount" style="width:50%; font-weight:normal; text-align: right;" readonly>
									</div>		
								</div>	
							</div>	
				            <div class="row">
				            	<div class="col-md-12">
									<div class="input-group margin pull-right">
										<span class="input-group-btn">
											<a href="#" class="btn btn-success btn-flat" style="width: 150px; text-align: right;">Total Voucher Payment</a>
										</span>
										<input id="TotalVoucherPayment" type="text" placeholder="Total Voucher Payment" class="form-control DecimalOnly RecomputeTotal" style="width:100%; font-weight:normal; text-align: right;" readonly>
									</div>		
								</div>	
							</div>	
				            <div class="row">
				            	<div class="col-md-12">
									<div class="input-group margin pull-right">
										<span class="input-group-btn">
											<a href="#" class="btn btn-primary btn-flat" style="width: 150px; text-align: right;">Total Amount Due</a>
										</span>
										<input id="TotalAmountDue" type="text" placeholder="Total Amount Due" class="form-control" style="width:100%; font-weight:normal; text-align: right;" readonly>
									</div>		
								</div>	
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

	<div id="set-as-cancelled-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  	<div class="modal-dialog modal-lg">
	    	<div class="modal-content">
	          	<div class="modal-header" style="background-color: #3c8dbc;">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            	<h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Cancel Purchase Receive</b></span></h4>
	          	</div>

          		<div class="modal-body">
        			<input type="hidden" id="CancelledReceiveID" value="0" readonly>
		            <div class="row">
		            	<div class="col-md-12">
			                <label class="col-md-12" style="font-weight: normal;">Are you sure you want to cancel this transaction?</label>
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
								<a id="btnSaveCancel" href="#" class="btn btn-info btn-flat" onclick="ProceedCancelRecord()"><i class="fa fa-save"></i> Proceed</a>
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

	 	var intItemCount = 0;
	 	var intDeletedItem = 0;
	 	var pDeletedItem = [];

		var isPageFirstLoad = true;
		var isLoadingInfo = false;
		var isRemoveAllItems = false;

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
				            }
				        ],
				'paging'      : false,
				'lengthChange': false,
				'searching'   : false,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false,
	            "order": [[ 1, "asc" ], [ 4, "desc" ]]
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
					SearchText: vSearchText,
					PageNo: vPageNo
				},
				url: "{{ route('get-purchase-receive-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.PurchaseReceiveList);
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

	    	tdID = vData.ReceiveID;
	    	tdSortOption = vData.SortOption;
			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown'  style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> ";
		                  	tdOption += " <li style='text-align:left;'> " +
		                      	" <a href='#' onclick='EditRecord(" + vData.ReceiveID + ")'>" + 
		                      		" <strong><i class='fa fa-info-circle font-size:15px;'></i> " + (vData.Status == '{{ config('app.STATUS_PENDING') }}' ? "Edit Record" : "View Record") + "</strong>" +
		                      	" </a> " +
		                   	" </li> ";
		                   	if(vData.Status == '{{ config('app.STATUS_PENDING') }}'){
			                  	tdOption += " <li style='text-align:left;'> " +
			                      	" <a href='#' onclick='CancelRecord(" + vData.ReceiveID + "," + false + ")'>" + 
			                      		" <strong><i class='fa fa-info-circle font-size:15px;'></i> Cancel Record</strong>" +
			                      	" </a> " +
			                   	" </li> ";
		                   	}
            tdOption += " </ul> " +
	                    " </div> " ;

			tdReceiveNo = "<span style='font-weight:bold;'>" + vData.ReceiveNo + "</span>";
			tdReceiveDateTime = "<span style='font-weight:normal;'>" + vData.ReceiveDateTime + "</span>";
			
			tdProcessNo = "<span style='font-weight:bold;'>" + vData.ProcessNo + "</span>";
			tdPONo = "<span style='font-weight:bold;'>" + vData.PONo + "</span>";

			tdCenter = "<span style='font-weight:normal;'>" + vData.Center + "</span>";
			tdTelNo = "<span style='font-weight:normal;'>" + vData.TelNo + "</span>";
			tdMobileNo = "<span style='font-weight:normal;'>" + vData.MobileNo + "</span>";
			tdEmailAddress = "<span style='font-weight:normal;'>" + vData.EmailAddress + "</span>";
			
			tdTotalAmountDue = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.TotalAmountDue,2) + "</span>";

			tdApprovedBy = "<span style='font-weight:normal;'>" + vData.ApprovedBy + "</span>";
			tdApprovedDateTime = "<span style='font-weight:normal;'>" + vData.ApprovedDateTime + "</span>";

			tdStatus = "";
			if(vData.Status == "Pending"){
				tdStatus += "<span class='label label-warning' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}else if(vData.Status == "{{ config('app.STATUS_CANCELLED') }}"){
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.Status + "</span>";
			}else{
				tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

			    var rowData = this.data();
			    if(rowData[0] == vData.ReceiveID){
					IsRecordExist = true;

			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdSortOption;
			    	curData[2] = tdOption;
			    	curData[3] = tdReceiveNo;
			    	curData[4] = tdReceiveDateTime;
			    	curData[5] = tdProcessNo;
			    	curData[6] = tdPONo;
			    	curData[7] = tdCenter;
			    	curData[8] = tdTelNo;
			    	curData[9] = tdMobileNo;
			    	curData[10] = tdEmailAddress;
			    	curData[11] = tdTotalAmountDue;
			    	curData[12] = tdApprovedBy;
			    	curData[13] = tdApprovedDateTime;
			    	curData[14] = tdStatus;
			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				var rowNode = tblList.row.add([
						tdID,
						tdSortOption,
						tdOption,
						tdReceiveNo,
						tdReceiveDateTime, 
						tdProcessNo,
						tdPONo,
						tdCenter,
						tdTelNo,
						tdMobileNo,
						tdEmailAddress,
						tdTotalAmountDue,
						tdApprovedBy,
						tdApprovedDateTime,
						tdStatus
					]).draw().node();			
			}

	    }

	    function Clearfields(){

	    	isRemoveAllItems = false;

			$("#ReceiveID").val('0');

			$("#ReceiveNo").val('');
			$("#ReceiveDateTime").val('');
			$("#Status").val('{{ config('app.STATUS_PENDING') }}').change();

			$("#ProcessID").val('0');
			$("#ProcessNo").val('');
			$("#PONo").val('');

			$("#CenterID").val('0');
			$("#Center").val('');
			$("#TelNo").val('');
			$("#MobileNo").val('');
			$("#EmailAddress").val('');
			$("#Address").val('');

			$("#Remarks").val('');
			$("#PreparedBy").val('{{ Session('ADMIN_FULLNAME') }}');

			$("#TotalGrossAmount").val("0.00");
			$("#TotalVoucherPayment").val('0.00');
			$("#TotalDiscountPercent").val('0.00');
			$("#TotalDiscount").val('0.00');
			$("#TotalAmountDue").val('0.00');

			$("#Status").prop('disabled', false);
			$("#ProcessNo").prop('disabled', false);
			$("#Remarks").prop('disabled', false);

	        //Load Initial Data
    		intItemCount = 0;
    		intDeletedItem = 0;
    		pDeletedItem=[];
    		$("#tblProductList").DataTable().clear().draw();

			$("#btnSave").show();

	    }

	    function NewRecord(){
	    	isNewRecord = 1;
			Clearfields();
			$("#record-info-modal").modal();
	    }

	    function EditRecord(vRecordID){

	    	if(vRecordID > 0){
	    		isNewRecord = 0;
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						ReceiveID: vRecordID
					},
					url: "{{ route('get-purchase-receive-info') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						if(data.Response =='Success' && data.PurchaseReceiveInfo != undefined){
							
					    	isRemoveAllItems = false;
							Clearfields();

							$("#ReceiveID").val(data.PurchaseReceiveInfo.ReceiveID);
							$("#ReceiveNo").val(data.PurchaseReceiveInfo.ReceiveNo);
							$("#ReceiveDateTime").val(data.PurchaseReceiveInfo.ReceiveDateTime);
							$("#Status").val(data.PurchaseReceiveInfo.Status).change();

							$("#ProcessID").val(data.PurchaseReceiveInfo.ProcessID);
							$("#ProcessNo").val(data.PurchaseReceiveInfo.ProcessNo);
							$("#PONo").val(data.PurchaseReceiveInfo.PONo);

							$("#CenterID").val(data.PurchaseReceiveInfo.CenterID);
							$("#Center").val(data.PurchaseReceiveInfo.Center);
							$("#TelNo").val(data.PurchaseReceiveInfo.TelNo);
							$("#MobileNo").val(data.PurchaseReceiveInfo.MobileNo);
							$("#EmailAddress").val(data.PurchaseReceiveInfo.EmailAddress);
							$("#Address").val(data.PurchaseReceiveInfo.Address + ", " + data.PurchaseReceiveInfo.City + ", " + data.PurchaseReceiveInfo.StateProvince + ", " + data.PurchaseReceiveInfo.ZipCode + " " + data.PurchaseReceiveInfo.Country);

							$("#Remarks").val(data.PurchaseReceiveInfo.Remarks);
							$("#PreparedBy").val(data.PurchaseReceiveInfo.ApprovedBy);

							var vIsEditable = false;
							if(data.PurchaseReceiveInfo.Status == "{{ config('app.STATUS_PENDING') }}"){
								vIsEditable = true;
							}

							$("#TotalGrossAmount").val(FormatDecimal(data.PurchaseReceiveInfo.TotalGrossAmount,2));
							$("#TotalVoucherPayment").val(FormatDecimal(data.PurchaseReceiveInfo.TotalVoucherPayment,2));
							$("#TotalDiscountPercent").val(FormatDecimal(data.PurchaseReceiveInfo.TotalDiscountPercent,2));
							$("#TotalDiscount").val(FormatDecimal(data.PurchaseReceiveInfo.TotalDiscount,2));
							$("#TotalAmountDue").val(FormatDecimal(data.PurchaseReceiveInfo.TotalAmountDue,2));

							$("#Status").prop('disabled', !vIsEditable);
							$("#ProcessNo").prop('disabled', !vIsEditable);
							$("#Remarks").prop('disabled', !vIsEditable);

							getRecordItem(data.PurchaseReceiveInfo.ReceiveID, vIsEditable);

							if(!vIsEditable){
								$("#btnSave").hide();
							}else{
								$("#btnSave").show();
							}

						}else{
							showJSModalMessageJS("Purchase Receive Information",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
			    		isLoadingInfo = false;
				        $("#divLoader").hide();
						buttonOneClick("btnSave", "Save", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
			    		isLoadingInfo = true;
				        $("#divLoader").show();
						buttonOneClick("btnSave", "", false);
					}
	        	});
	    	}
	    }

	    function getRecordItem(vReceiveID, vIsEditable){

	    	if(vReceiveID > 0){
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						ReceiveID : vReceiveID
					},
					url: "{{ route('get-purchase-receive-item-list') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						if(data.Response =='Success' && data.PurchaseReceiveItemList != undefined){

					        //Load Initial Data
				    		intItemCount = 0;
				    		intDeletedItem = 0;
				    		pDeletedItem=[];
				    		$("#tblProductList").DataTable().clear().draw();

					        if(data.PurchaseReceiveItemList.length > 0){
						        LoadRecordItemList(vReceiveID, data.PurchaseReceiveItemList, vIsEditable, false);
					        }
					 		RecomputeTotal();

							$("#record-info-modal").modal();

							buttonOneClick("btnSave", "Save", false);
						}else{
							showJSModalMessageJS("Purchase Receive Item",data.ResponseMessage,"OK");
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

	    function getProcessItem(vProcessID){

	    	if(vProcessID > 0){

	    		if($("#ReceiveID").val() != "" && $("#ReceiveID").val() != "0"){
	    			isRemoveAllItems = true;
	    		}

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						ProcessID : vProcessID
					},
					url: "{{ route('get-po-processing-item-list') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						if(data.Response =='Success' && data.POProcessItemList != undefined){

					        //Load Initial Data
				    		intItemCount = 0;
				    		intDeletedItem = 0;
				    		pDeletedItem=[];
				    		$("#tblProductList").DataTable().clear().draw();

					        if(data.POProcessItemList.length > 0){
						        LoadRecordItemList($("#ReceiveID").val(), data.POProcessItemList, true, true);
					        }
					 		RecomputeTotal();

							$("#record-info-modal").modal();

							buttonOneClick("btnSave", "Save", false);
						}else{
							showJSModalMessageJS("PO Item",data.ResponseMessage,"OK");
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

    	function LoadRecordItemList(vReceiveID, vList, vIsEditable, vIsProcessItem){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadRecordItemRow(vReceiveID, vList[x], vIsEditable, vIsProcessItem);
	    		}
	    	}
	    }

	    function LoadRecordItemRow(vReceiveID, vData, vIsEditable, vIsProcessItem){

	    	var tblProductList = $("#tblProductList").DataTable();

	    	intItemCount  = intItemCount  + 1;

	    	tdID = intItemCount ;
	    	tdReceiveItemID = vData.ReceiveItemID;
	    	tdReceiveID = vReceiveID;

	    	tdProduct = "<input id='ProductID-" + intItemCount  + "'  type='hidden' class='form-control' value='" + vData.ProductID + "' style='width:100%; font-weight:normal;' readonly>";
	    	tdProduct += "<input id='Product-" + intItemCount  + "'  type='text' class='form-control' value='" + vData.ProductName + " - " + vData.ProductName + "' style='width:100%; font-weight:normal;' readonly>";

			tdQty = "<input id='Qty-" + intItemCount  + "'  type='text' class='form-control' value='" + FormatDecimal(vData.Qty,2) + "' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdReceiveQty = "<input id='ReceiveQty-" + intItemCount  + "'  type='text' class='form-control DecimalOnly ChangeQty' value='" + FormatDecimal((vIsProcessItem ? vData.Qty : vData.ReceiveQty),2) + "' style='width:100%; text-align:right; font-weight:normal;' " + (vIsEditable ? "" : "readonly") + ">";

			tdUnitMeasure = "<input id='UnitMeasure-" + intItemCount  + "'  type='text' class='form-control' value='" + vData.Measurement + "' style='width:100%; font-weight:normal;' readonly>";

			tdPrice = "<input id='Price-" + intItemCount + "' type='text' class='form-control' value='" + FormatDecimal(vData.Price,2) + "' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdSubTotal = "<input id='SubTotal-" + intItemCount + "' type='text' class='form-control' value='" + FormatDecimal(vData.SubTotal,2) + "' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			//Check if record already listed
			var IsRecordExist = false;
			tblProductList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();
			    if(rowData[1] == vData.ProductID){
					IsRecordExist = true;

			    	//Edit Row
			    	curData = tblProductList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdReceiveItemID;
			    	curData[2] = tdReceiveID;
			    	curData[3] = tdProduct;
			    	curData[4] = tdQty;
			    	curData[5] = tdReceiveQty;
			    	curData[6] = tdUnitMeasure;
			    	curData[7] = tdPrice;
			    	curData[8] = tdSubTotal;

			    	tblProductList.row(rowIdx).data(curData).invalidate().draw();
			    }
			});

			if(!IsRecordExist){

		    	//New Row
				var rowNode = tblProductList.row.add([
						tdID,
						tdReceiveItemID,
						tdReceiveID,
						tdProduct, 
						tdQty,
						tdReceiveQty,
						tdUnitMeasure,
						tdPrice,
						tdSubTotal
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

		$(document).on('change keyup blur','.RecomputeTotal',function(){
			RecomputeTotal();
		});

		$(document).on('change keyup blur','.ChangeQty',function(){
			var vID = $(this).attr('id');
			var arrID = vID.split("-");
			var vIndex = arrID[1];

	 		ReComputeFields(vIndex);
		});

	    function ReComputeFields(vIndex){

	    	Qty = $("#ReceiveQty-" + vIndex).val();
			if(Qty == ''){
				Qty = 0;
			}

	    	Price = $("#Price-" + vIndex).val();
			if(Price == ''){
				Price = 0;
			}

			SubTotal = parseFloat(Qty)*parseFloat(Price);
			$('#SubTotal-' + vIndex).val(FormatDecimal(SubTotal,2));

	 		RecomputeTotal();
	    }

	    function RecomputeTotal(){

	    	var TotalGrossAmount = 0;
	    	for (var i = 1; i <= intItemCount; i++) {
		    	var SubTotal = 0;
	    		if($('#SubTotal-' + i).length){
	    			if($("#SubTotal-" + i).val() != ""){
			            SubTotal = $("#SubTotal-" + i).val();
	    			}
				}

				TotalGrossAmount = TotalGrossAmount + parseFloat(SubTotal);
			}
			$('#TotalGrossAmount').val(FormatDecimal(TotalGrossAmount,2));

	    	var TotalVoucherPayment = 0;
    		if($('#TotalVoucherPayment').length){
    			if($("#TotalVoucherPayment").val() != ""){
		            var strTotalVoucherPayment = $("#TotalVoucherPayment").val();
		            TotalVoucherPayment = parseFloat(strTotalVoucherPayment.replace(",",""));
    			}
			}

	    	var TotalDiscount = 0;
    		if($('#TotalDiscount').length){
    			if($("#TotalDiscount").val() != ""){
		            var strTotalDiscount = $("#TotalDiscount").val();
		            TotalDiscount = parseFloat(strTotalDiscount.replace(",",""));
    			}
			}

	    	var TotalAmountDue = TotalGrossAmount - TotalVoucherPayment - TotalDiscount;
			$('#TotalAmountDue').val(FormatDecimal(TotalAmountDue,2));

	    }	

	    function SaveRecord(){

	    	var TotalGrossAmount = 0;
	    	for (var i = 1; i <= intItemCount; i++) {
		    	var SubTotal = 0;
	    		if($('#SubTotal-' + i).length){
	    			if($("#SubTotal-" + i).val() != ""){
			            SubTotal = $("#SubTotal-" + i).val();
	    			}
				}
				TotalGrossAmount = TotalGrossAmount + parseFloat(SubTotal);
			}

	    	var TotalDiscountPercent = 0;
    		if($('#TotalDiscountPercent').length){
    			if($("#TotalDiscountPercent").val() != ""){
		            var strTotalDiscountPercent = $("#TotalDiscountPercent").val();
		            TotalDiscountPercent = parseFloat(strTotalDiscountPercent.replace(",",""));
    			}
			}

	    	var TotalDiscount = 0;
    		if($('#TotalDiscount').length){
    			if($("#TotalDiscount").val() != ""){
		            var strTotalDiscount = $("#TotalDiscount").val();
		            TotalDiscount = parseFloat(strTotalDiscount.replace(",",""));
    			}
			}

	    	var TotalVoucherPayment = 0;
    		if($('#TotalVoucherPayment').length){
    			if($("#TotalVoucherPayment").val() != ""){
		            var strTotalVoucherPayment = $("#TotalVoucherPayment").val();
		            TotalVoucherPayment = parseFloat(strTotalVoucherPayment.replace(",",""));
    			}
			}

	    	var TotalAmountDue = TotalGrossAmount - TotalVoucherPayment - TotalDiscount;

			if($('#ProcessID').val() == "") {
				showJSMessage("Center","Please select Process Number.","OK");
			}else{

		    	var tblProductList = $("#tblProductList").DataTable();
	            var pData = [];
	            var intCntr = 0;

		    	//Check Product Fields
		    	for (var i = 1; i <= intItemCount; i++) {
		    		var ReceiveItemID = 0;
		    		var ReceiveID = 0;
		    		var ProductID = 0;
		    		var UnitMeasure = "";
		    		var Qty = 0;
		    		var ReceiveQty = 0;
		    		var Price = 0;
		    		var SubTotal = 0;
			    	var blnIsIncomplete = false;

					tblProductList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
					    var rowData = this.data();

					    if(rowData[0] == i){
					    	ReceiveItemID = rowData[1];
					    	ReceiveID = rowData[2];
					    }
					});

		    		if($('#ProductID-' + i).length){
		    			if($("#ProductID-" + i).val() != ""){
				            ProductID = $("#ProductID-" + i).val();
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

		    		if($('#ReceiveQty-' + i).length){
		    			if($("#ReceiveQty-" + i).val()!= ""){
				            ReceiveQty = $("#ReceiveQty-" + i).val();
		    			}else{
				    		blnIsIncomplete = true;
		    			}
		    		}else{
			    		blnIsIncomplete = true;
	    			}

		    		if($('#UnitMeasure-' + i).length){
		    			if($("#UnitMeasure-" + i).val()!= ""){
				            UnitMeasure = $("#UnitMeasure-" + i).val();
		    			}else{
				    		blnIsIncomplete = true;
		    			}
		    		}else{
			    		blnIsIncomplete = true;
	    			}

		    		if($('#Price-' + i).length){
		    			if($("#Price-" + i).val() != ""){
				            Price = $("#Price-" + i).val();
		    			}else{
				    		blnIsIncomplete = true;
		    			}
		    		}else{
			    		blnIsIncomplete = true;
	    			}

		    		if($('#SubTotal-' + i).length){
		    			if($("#SubTotal-" + i).val() != ""){
				            SubTotal = $("#SubTotal-" + i).val();
		    			}else{
				    		blnIsIncomplete = true;
		    			}
		    		}else{
			    		blnIsIncomplete = true;
	    			}

		    		if(!blnIsIncomplete){

						pData[intCntr] = {
							ReceiveItemID:ReceiveItemID,
							ReceiveID:ReceiveID,
							ProductID:ProductID,
							Qty:Qty,
							ReceiveQty:ReceiveQty,
							UnitMeasure:UnitMeasure,
							Price:Price,
							SubTotal:SubTotal
						};

						intCntr = intCntr + 1;
		    		}
		    	}

				if(pData.length <= 0){
					showJSModalMessageJS("Save Purchase Receive","Please enter receive items.","OK");
				}else{

					$.ajax({
						type: "post",
						data: {
							_token: '{{ $Token }}',
							ReceiveID: $("#ReceiveID").val(),
							ReceiveNo: $("#ReceiveNo").val(),
							Status: $("#Status").val(),

							ProcessID: $("#ProcessID").val(),
							CenterID: $("#CenterID").val(),

							GrossAmount: TotalGrossAmount,
							TotalVoucherPayment: TotalVoucherPayment,
							TotalDiscountPercent: TotalDiscountPercent,
							TotalDiscount: TotalDiscount,
							TotalAmountDue: TotalAmountDue,

							Remarks: $("#Remarks").val(),
							IsRemoveAllItems: (isRemoveAllItems ? 1 : 0),
							ReceiveItems: pData,
							ReceiveItemsDeleted: pDeletedItem
						},
						url: "{{ route('do-save-purchase-receive') }}",
						dataType: "json",
						success: function(data){

					        $("#divLoader").hide();
							buttonOneClick("btnSave", "Save", false);
							if(data.Response =='Success'){
								$("#record-info-modal").modal('hide');
								showJSModalMessageJS("Success",data.ResponseMessage,"OK");
								LoadRecordRow(data.PurchaseReceiveInfo);
							}else{
								showJSModalMessageJS("Save Purchase Receive",data.ResponseMessage,"OK");
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
	      	}
	    };

	    function CancelRecord(vPOID){
	    	if(vPOID > 0){
				$("#CancelledReceiveID").val(vPOID);
				$("#CancellationReason").text('');				
				$("#set-as-cancelled-modal").modal();
	    	}
	    }

	    function ProceedCancelRecord(){

	    	if($("#CancellationReason").val() == ""){
				showJSModalMessageJS("Cancel PO","Please enter the reason for the cancellation of this transation.","OK");
	    	}else{
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						ReceiveID: $("#CancelledReceiveID").val(),
						Reason : $("#CancellationReason").val()
					},
					url: "{{ route('do-cancel-purchase-receive') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnSaveCancel", "Save", false);
						if(data.Response =='Success'){
							$("#set-as-cancelled-modal").modal('hide');
							showJSModalMessageJS("Success",data.ResponseMessage,"OK");
							LoadRecordRow(data.PurchaseReceiveInfo);
						}else{
							showJSModalMessageJS("Purchase Receive Cancellation",data.ResponseMessage,"OK");
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

		//autocomplete script
	    $(document).on('focus','.autocomplete_txt',function(){

	        $(this).autocomplete({

	            source: function( request, response ) {

	                if(request.term.length >= 3){
	                    $.ajax({
	                        url: "{{ route('get-po-processing-search-list') }}",
	                        dataType: "json",
	                        method: 'get',
							data: {
                             	SearchText: request.term,
								PageNo : 1,
								Status : "{{ config('app.STATUS_APPROVED') }}",
								IsUnReceivedOnly:1
							},
	                        success: function( data ) {
	                            response( $.map( data, function( item ) {
	                                var code = item.split("|");
	                                console.log(code);
	                                return {
	                                    label: code[1],
	                                    value: code[1],
	                                    data : item
	                                }
	                            }));
	                        },
							error: function(data){
								console.log(data.responseText);
							}
	                    });
	                }
	            },
	            autoFocus: true,
	            minLength: 0,
	            appendTo: "#modal-fullscreen",

	            select: function( event, ui ) {

	                var data = ui.item.data.split("|");

					$("#ProcessID").val(data[0]);
					$("#ProcessNo").val(data[1]);
					$("#PONo").val(data[3]);

					$("#CenterID").val(data[4]);
					$("#Center").val(data[6]);
					$("#TelNo").val(data[7]);
					$("#MobileNo").val(data[8]);
					$("#EmailAddress").val(data[9]);
					$("#Address").val(data[10] + ", " + data[11] + ", " + data[12] + ", " + data[13] + " " + data[14]);

					$("#TotalGrossAmount").val(data[15]);
					$("#TotalDiscountPercent").val(data[17]);
					$("#TotalDiscount").val(data[18]);
					$("#TotalVoucherPayment").val(data[16]);
					$("#TotalAmountDue").val(data[19]);

	                getProcessItem(data[0]);

	            }
	        });

	    });

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



