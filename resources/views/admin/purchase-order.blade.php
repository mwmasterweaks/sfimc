@extends('layout.adminweb')

@section('content')

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Purchase Order
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li class="active">Purchase Order</li>
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
				                  <th>PO No.</th>
				                  <th>PO DateTime</th>
				                  <th>PO Type</th>
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
		            <h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Purchase Order Information</b></span></h4>
	          	</div>
	          	<div class="modal-body">
	        		<input type="hidden" id="POID" value="0" readonly>
		            <div class="row">
		            	<div class="col-md-12">
			            	<label class="col-md-12" style="font-size: 15px;"><i class='fa fa-shopping-cart font-size:15px;'></i>&nbsp&nbsp<b>Purchase Order Information</b></label>
			        	</div>
		        	</div>

		            <div class="row">
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">PO No.</label>
			                <div class="col-md-12">
			                    <input id="PONo" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight:bold; color:green;" readonly>
			                </div>
		            	</div>
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">PO Date/Time<span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="PODateTime" type="text" class="form-control" value=""  placeholder="Auto Generated" style="width:100%; font-weight:normal;" readonly>
			                </div>
		            	</div>
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">PO Type <span style="color:red;">*</span></label>
			                <div class="col-md-12">
								<select id="POType" class="form-control select2" style="width: 100%; height: 100px; font-weight: normal;">
									<option value="Regular PO" selected>Regular PO</option>
									<option value="Voucher Replenishment">Voucher Replenishment</option>
								</select>
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
		            	<div class="col-md-12">
			                <label class="col-md-12" style="font-weight: normal;">Center <span style="color:red;">*</span></label>
			                <div class="col-md-12">
								<select id="Center" class="form-control select2" style="width: 100%; height: 100px; font-weight: normal;">
									<option value="">Please Select</option>
									@foreach ($CenterList as $ctr)
										<option value="{{ $ctr->CenterID }}"
											data-telno="{{ $ctr->TelNo }}"
											data-mobileno="{{ $ctr->MobileNo }}"
											data-emailaddress="{{ $ctr->EmailAddress }}"
											data-address="{{ $ctr->Address.', '.$ctr->City.', '.$ctr->StateProvince.', '.$ctr->ZipCode.' '.$ctr->Country }}"
											>{{ $ctr->Center }}</option>
									@endforeach
								</select>
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
					                  <th>POItemID</th>
					                  <th>POID</th>
					                  <th style="width: 25%;">Product Name <span style="color:red;">*</span></th>
					                  <th style="text-align: right;">Qty <span style="color:red;">*</span></th>
					                  <th style="text-align: right;">Unit Measure</th>
					                  <th style="text-align: right;">Price <span style="color:red;">*</span></th>
					                  <th style="text-align: right;">Sub Total <span style="color:red;">*</span></th>
					                  <th style="width: 90px; text-align: right;"></th>
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
					            	<span class="col-md-12" style="font-weight: normal; font-size: 15px; color: red;">Note : All prices will be set to Retail Price if you use voucher for payment.</span>
					        	</div>
				        	</div>
				            <div class="row">
				            	<div class="col-md-12">
					              	<table id="tblVoucherList" class="table table-bordered table-hover">
						                <thead>
							                <tr>
							                  <th>ID</th>
							                  <th>VoucherID</th>
							                  <th>POID</th>
							                  <th style="width: 15px;"></th>
							                  <th style="width: 25%;">Voucher Code<span style="color:red;">*</span></th>
							                  <th style="text-align: right;">Voucher Amount<span style="color:red;">*</span></th>
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
	            	<h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Cancel Purchase Order</b></span></h4>
	          	</div>

          		<div class="modal-body">
        			<input type="hidden" id="CancelledPOID" value="0" readonly>
		            <div class="row">
		            	<div class="col-md-12">
			                <label class="col-md-12" style="font-weight: normal;">Are you sure you want to cancel this PO?</label>
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
								<a id="btnSaveCancel" href="#" class="btn btn-info btn-flat" onclick="ProceedCancelPO()"><i class="fa fa-save"></i> Proceed</a>
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

	 	var intVoucherItemCount = 0;

		var isPageFirstLoad = true;
		var isLoadingInfo = false;

		var POVoucherList = undefined;

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

	        $('#tblVoucherList').DataTable( {
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
				'ordering'    : false,
				'info'        : false,
				'autoWidth'   : false
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
				url: "{{ route('get-purchase-order-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.POList);
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

	    	tdID = vData.POID;
	    	tdSortOption = vData.SortOption;
			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown'  style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> ";
		                  	tdOption += " <li style='text-align:left;'> " +
		                      	" <a href='#' onclick='EditPO(" + vData.POID + ")'>" + 
		                      		" <strong><i class='fa fa-info-circle font-size:15px;'></i> " + (vData.Status == '{{ config('app.STATUS_PENDING') }}' ? "Edit PO" : "View PO") + "</strong>" +
		                      	" </a> " +
		                   	" </li> ";
		                   	if(vData.Status != '{{ config('app.STATUS_CANCELLED') }}'){
		                   		if(vData.ProcessID <= 0){
				                  	tdOption += " <li style='text-align:left;'> " +
				                      	" <a href='#' onclick='CancelPO(" + vData.POID + "," + false + ")'>" + 
				                      		" <strong><i class='fa fa-info-circle font-size:15px;'></i> Cancel PO</strong>" +
				                      	" </a> " +
				                   	" </li> ";
		                   		}
		                   	}
            tdOption += " </ul> " +
	                    " </div> " ;

			tdPONo = "<span style='font-weight:bold;'>" + vData.PONo + "</span>";
			tdPODateTime = "<span style='font-weight:normal;'>" + vData.PODateTime + "</span>";
			
			tdPOType = "<span style='font-weight:normal;'>" + vData.POType + "</span>";
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
			    if(rowData[0] == vData.POID){
					IsRecordExist = true;

			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdSortOption;
			    	curData[2] = tdOption;
			    	curData[3] = tdPONo;
			    	curData[4] = tdPODateTime;
			    	curData[5] = tdPOType;
			    	curData[6] = tdCenter;
			    	curData[7] = tdTelNo;
			    	curData[8] = tdMobileNo;
			    	curData[9] = tdEmailAddress;
			    	curData[10] = tdTotalAmountDue;
			    	curData[11] = tdApprovedBy;
			    	curData[12] = tdApprovedDateTime;
			    	curData[13] = tdStatus;
			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				var rowNode = tblList.row.add([
						tdID,
						tdSortOption,
						tdOption,
						tdPONo,
						tdPODateTime, 
						tdPOType,
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

			$("#POID").val('0');

			$("#PONo").val('');
			$("#PODateTime").val('');
			$("#POType").val('Regular PO').change();
			$("#Status").val('{{ config('app.STATUS_PENDING') }}').change();

			$("#TelNo").val('');
			$("#MobileNo").val('');
			$("#EmailAddress").val('');
			$("#Address").val('');

			$("#Center").val('{{ Session('ADMIN_CENTER_ID') }}').change();

			$("#Remarks").val('');
			$("#PreparedBy").val('{{ Session('ADMIN_FULLNAME') }}');

			$("#TotalGrossAmount").val("0.00");
			$("#TotalVoucherPayment").val('0.00');
			$("#TotalAmountDue").val('0.00');

			$("#Status").prop('disabled', false);
			$("#POType").prop('disabled', false);
			$("#Center").prop('disabled', false);
			$("#Remarks").prop('disabled', false);

	        //Load Initial Data
    		intItemCount = 0;
    		intDeletedItem = 0;
    		pDeletedItem=[];
    		$("#tblProductList").DataTable().clear().draw();

	        AddEmptyRow(0);

	        //Clear Voucher Data
    		intVoucherCntr = 0;
    		POVoucherList = undefined;
    		$("#tblVoucherList").DataTable().clear().draw();

			$("#btnSave").show();

	    }

	    function NewRecord(){
	    	isNewRecord = 1;
			Clearfields();
			$("#record-info-modal").modal();
	    }

	    function EditPO(vRecordID){

	    	if(vRecordID > 0){
	    		isNewRecord = 0;
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						POID: vRecordID
					},
					url: "{{ route('get-purchase-order-info') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						if(data.Response =='Success' && data.POInfo != undefined){
							
							Clearfields();

							$("#POID").val(data.POInfo.POID);

							$("#PONo").val(data.POInfo.PONo);
							$("#PODateTime").val(data.POInfo.PODateTime);
							$("#POType").val(data.POInfo.POType).change();
							$("#Status").val(data.POInfo.Status).change();

							$("#Center").val(data.POInfo.CenterID).change();

							$("#TelNo").val(data.POInfo.TelNo);
							$("#MobileNo").val(data.POInfo.MobileNo);
							$("#EmailAddress").val(data.POInfo.EmailAddress);
							$("#Address").val(data.POInfo.Address + ", " + data.POInfo.City + ", " + data.POInfo.StateProvince + ", " + data.POInfo.ZipCode + " " + data.POInfo.Country);

							$("#Remarks").val(data.POInfo.Remarks);
							$("#PreparedBy").val(data.POInfo.ApprovedBy);

							var vIsEditable = false;
							if(data.POInfo.Status == "{{ config('app.STATUS_PENDING') }}"){
								vIsEditable = true;
							}

							$("#TotalGrossAmount").val(FormatDecimal(data.POInfo.TotalGrossAmount,2));
							$("#TotalVoucherPayment").val(FormatDecimal(data.POInfo.TotalVoucherPayment,2));
							$("#TotalAmountDue").val(FormatDecimal(data.POInfo.TotalAmountDue,2));

							$("#POType").prop('disabled', !vIsEditable);
							$("#Center").prop('disabled', !vIsEditable);
							$("#Status").prop('disabled', !vIsEditable);
							$("#Remarks").prop('disabled', !vIsEditable);

							getPOVoucher(data.POInfo.POID, vIsEditable);
							getPOItem(data.POInfo.POID, vIsEditable);

							if(!vIsEditable){
								$("#btnSave").hide();
							}else{
								$("#btnSave").show();
							}

						}else{
							showJSModalMessageJS("PO Information",data.ResponseMessage,"OK");
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

	    $("#POType").change(function(){
	      	if(!isLoadingInfo){
		    	if($("#POType").val() == "Voucher Replenishment"){
					intVoucherCntr = 0;
			      	$("#tblVoucherList").DataTable().clear().draw();
		    		getVoucherRecordList(($("#Center").val() == "" ? 0 : $("#Center").val()));
		    	}else{
					intVoucherCntr = 0;
			      	$("#tblVoucherList").DataTable().clear().draw();
		    	}

				ResetProductPrices();
	    	}

      	}); 

	    $("#Center").change(function(){
	      	if($("#Center").find('option:selected').data('telno') != undefined){
	      		var TelNo = $("#Center").find('option:selected').data('telno');
	        	$("#TelNo").val(TelNo);
	      	}
	      	if($("#Center").find('option:selected').data('mobileno') != undefined){
	      		var MobileNo = $("#Center").find('option:selected').data('mobileno');
	        	$("#MobileNo").val(MobileNo);
	      	}
	      	if($("#Center").find('option:selected').data('emailaddress') != undefined){
	      		var EmailAddress = $("#Center").find('option:selected').data('emailaddress');
	        	$("#EmailAddress").val(EmailAddress);
	      	}
	      	if($("#Center").find('option:selected').data('address') != undefined){
	      		var Address = $("#Center").find('option:selected').data('address');
	        	$("#Address").val(Address);
	      	}

	    	if($("#POType").val() == "Voucher Replenishment"){
		      	if(!isLoadingInfo){
					intVoucherCntr = 0;
			      	$("#tblVoucherList").DataTable().clear().draw();
		    		getVoucherRecordList(($("#Center").val() == "" ? 0 : $("#Center").val()));
		    	}
	    	}else{
				intVoucherCntr = 0;
		      	$("#tblVoucherList").DataTable().clear().draw();
	    	}

			ResetProductPrices();


      	}); 

	    function getPOItem(vPOID, vIsEditable){

	    	if(vPOID > 0){
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						POID : vPOID
					},
					url: "{{ route('get-purchase-order-item-list') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						if(data.Response =='Success' && data.POItemList != undefined){

					        //Load Initial Data
				    		intItemCount = 0;
				    		intDeletedItem = 0;
				    		pDeletedItem=[];
				    		$("#tblProductList").DataTable().clear().draw();

					        if(data.POItemList.length > 0){
						        LoadPOItemList(vPOID, data.POItemList, vIsEditable);
					        }else{
						        AddEmptyRow(vPOID);
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

    	function LoadPOItemList(vPOID, vList, vIsEditable){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadPOItemRow(vPOID, vList[x], vIsEditable);
	    		}
	    	}
	    }

	    function LoadPOItemRow(vPOID, vData, vIsEditable){

	    	var tblProductList = $("#tblProductList").DataTable();

	    	intItemCount  = intItemCount  + 1;

	    	tdID = intItemCount ;
	    	tdPOItemID = vData.POItemID;
	    	tdPOID = vPOID;

	    	if(vIsEditable){
				tdProduct = "<select id='Product-" + intItemCount  + "' class='form-control select2' onChange='SelectProduct("  +  intItemCount + ")' style='width: 100%; font-weight:normal;'>";
				tdProduct += "<option value=''>Please Select</option>";
					@foreach ($ProductList as $pkey)
						tdProduct += "<option value='{{ $pkey->ProductID }}' ";
						tdProduct += " data-brand='{{$pkey->Brand}}' ";
						tdProduct += " data-category='{{$pkey->Category}}' ";
						tdProduct += " data-measurement='{{$pkey->Measurement}}' ";
						tdProduct += " data-centerprice='{{$pkey->CenterPrice}}' ";
						tdProduct += " data-retailprice='{{$pkey->RetailPrice}}' ";
						tdProduct += " data-distributorprice='{{$pkey->DistributorPrice}}' ";
						tdProduct += " data-rebatablevalue='{{$pkey->RebateValue}}' ";
						tdProduct += (vData.ProductID ==  {{ $pkey->ProductID }} ? "selected" : "");
						tdProduct += ">";
						tdProduct += "{{ $pkey->ProductCode.' - '.$pkey->ProductName }}</option>";
					@endforeach			
				tdProduct += "</select>";
	    	}else{
		    	tdProduct = "<input id='Product-" + intItemCount  + "'  type='text' class='form-control' value='" + vData.ProductName + " - " + vData.ProductName + "' style='width:100%; font-weight:normal;' readonly>";
	    	}

			tdQty = "<input id='Qty-" + intItemCount  + "'  type='text' class='form-control DecimalOnly ChangeQty' value='" + FormatDecimal(vData.Qty,2) + "' style='width:100%; text-align:right; font-weight:normal;' " + (vIsEditable ? "" : "readonly") + ">";

			tdUnitMeasure = "<input id='UnitMeasure-" + intItemCount  + "'  type='text' class='form-control' value='" + vData.Measurement + "' style='width:100%; font-weight:normal;' readonly>";

			tdPrice = "<input id='Price-" + intItemCount + "' type='text' class='form-control' value='" + FormatDecimal(vData.Price,2) + "' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdSubTotal = "<input id='SubTotal-" + intItemCount + "' type='text' class='form-control' value='" + FormatDecimal(vData.SubTotal,2) + "' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdButtons = "<div class='col-md-12' style='margin-top:5px;'>";
			tdButtons +="	<div class='pull-right'>";
			if(vIsEditable){
				tdButtons += "		<label style='cursor:pointer;font-size:12px;margin-right:2px;' class='label label-primary'  onClick='AddEmptyRow(" + vPOID + ")'>";
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
			    if(rowData[1] == vData.ProductID){
					IsRecordExist = true;

			    	//Edit Row
			    	curData = tblProductList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdPOItemID;
			    	curData[2] = tdPOID;
			    	curData[3] = tdProduct;
			    	curData[4] = tdQty;
			    	curData[5] = tdUnitMeasure;
			    	curData[6] = tdPrice;
			    	curData[7] = tdSubTotal;
			    	curData[8] = tdButtons;

			    	tblProductList.row(rowIdx).data(curData).invalidate().draw();
			    }
			});

			if(!IsRecordExist){

		    	//New Row
				var rowNode = tblProductList.row.add([
						tdID,
						tdPOItemID,
						tdPOID,
						tdProduct, 
						tdQty,
						tdUnitMeasure,
						tdPrice,
						tdSubTotal,
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

	    function AddEmptyRow(vPOID){

	    	var tblProductList = $("#tblProductList").DataTable();

	    	intItemCount = intItemCount + 1;

	    	tdID = intItemCount ;
			tdPOItemID = 0;
	    	tdPOID = vPOID;

			tdProduct = "<select id='Product-" + intItemCount  + "' class='form-control select2' onChange='SelectProduct("  +  intItemCount + ")' style='width: 100%; font-weight:normal;'>";
			tdProduct += "<option value=''>Please Select</option>";
				@foreach ($ProductList as $pkey)
					tdProduct += "<option value='{{ $pkey->ProductID }}' ";
					tdProduct += " data-brand='{{$pkey->Brand}}' ";
					tdProduct += " data-category='{{$pkey->Category}}' ";
					tdProduct += " data-measurement='{{$pkey->Measurement}}' ";
					tdProduct += " data-centerprice='{{$pkey->CenterPrice}}' ";
					tdProduct += " data-retailprice='{{$pkey->RetailPrice}}' ";
					tdProduct += " data-distributorprice='{{$pkey->DistributorPrice}}' ";
					tdProduct += " data-rebatablevalue='{{$pkey->RebateValue}}' ";
					tdProduct += ">";
					tdProduct += "{{ $pkey->ProductCode.' - '.$pkey->ProductName }}</option>";
				@endforeach			
			tdProduct += "</select>";

			tdQty = "<input id='Qty-" + intItemCount  + "'  type='text' class='form-control DecimalOnly ChangeQty' value='' style='width:100%; text-align:right; font-weight:normal;'>";

			tdUnitMeasure = "<input id='UnitMeasure-" + intItemCount  + "'  type='text' class='form-control' value='' style='width:100%; font-weight:normal;' readonly>";

			tdPrice = "<input id='Price-" + intItemCount + "' type='text' class='form-control' value='' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdSubTotal = "<input id='SubTotal-" + intItemCount + "' type='text' class='form-control' value='' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdButtons = "<div class='col-md-12' style='margin-top:5px;'>";
			tdButtons +="	<div class='pull-right'>";
			tdButtons += "		<label style='cursor:pointer;font-size:12px;margin-right:2px;' class='label label-primary'  onClick='AddEmptyRow(" + vPOID + ")'>";
			tdButtons +="			<i class='fa fa-plus'></i>";
			tdButtons +="		</label>";
			tdButtons +="		<label style='cursor:pointer;font-size:12px;' class='label label-danger' onClick='DeleteRow(" + intItemCount + ")'>";
			tdButtons +="			<i class='fa fa-trash'></i> ";
			tdButtons +="		</label>";
			tdButtons +="	</div>";
			tdButtons +="</div>";

			tblProductList.row.add([
				tdID,
				tdPOItemID,
				tdPOID,
				tdProduct,
				tdQty,
				tdUnitMeasure,
				tdPrice,
				tdSubTotal,
				tdButtons
			]).draw().node();		

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
				    	RecomputeTotal();

				    	vIsDeleted = true;
				    }
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

		$(document).on('change','.RecomputeTotalVoucher',function(){
			RecomputeTotalVoucher();
		});

	    function SelectProduct(vIndex){

	      	if($("#Product-" + vIndex).find('option:selected').data('measurement') != undefined){

	      		var Measurement = $("#Product-" + vIndex).find('option:selected').data('measurement');
	      		var DistributorPrice = $("#Product-" + vIndex).find('option:selected').data('distributorprice');
	      		var RetailPrice = $("#Product-" + vIndex).find('option:selected').data('retailprice');

	        	$("#UnitMeasure-" + vIndex).val(Measurement);

	        	if($("#POType").val() == "Voucher Replenishment"){
	        		$("#Price-" + vIndex).val(FormatDecimal(RetailPrice,2));
	        	}else{
	        		$("#Price-" + vIndex).val(FormatDecimal(DistributorPrice,2));
	        	}
	      	}

	    }

	    function ResetProductPrices(){

	    	for (var i = 1; i <= intItemCount; i++) {

	      		var DistributorPrice = $("#Product-" + i).find('option:selected').data('distributorprice');
	      		var RetailPrice = $("#Product-" + i).find('option:selected').data('retailprice');

				if($("#POType").val() == "Voucher Replenishment"){
	        		$("#Price-" + i).val(FormatDecimal(RetailPrice,2));
	        	}else{
	        		$("#Price-" + i).val(FormatDecimal(DistributorPrice,2));
	        	}

	        	ReComputeFields(i);
			}

	    }

	    function ReComputeFields(vIndex){

	    	Qty = $("#Qty-" + vIndex).val();
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

	    function getPOVoucher(vPOID, vIsEditable){

	    	if(vPOID > 0){
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						POID : vPOID
					},
					url: "{{ route('get-purchase-order-voucher-list') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						if(data.Response =='Success' && data.POVoucherList != undefined){

					        LoadVoucherRecordList(vPOID, data.POVoucherList, vIsEditable);
							POVoucherList = data.POVoucherList;
						}

			    		isLoadingInfo = false;
			    		if(vIsEditable){
							getVoucherRecordList($("#Center").val());
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

	    function getVoucherRecordList(vCenterID){

	    	if(vCenterID > 0){
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						CenterID: vCenterID,
						SearchText: '',
						PageNo: 0
					},
					url: "{{ route('get-center-voucher-list') }}",
					dataType: "json",
					success: function(data){
						LoadVoucherRecordList($("#POID").val(), data.CenterVoucherList, true);
				        $("#divLoader").hide();
					},
					error: function(data){
				        $("#divLoader").hide();
						console.log(data.responseText);
					},
					beforeSend:function(vData){
	        			$("#divLoader").show();
					}
		    	});
	    	}

	    };

	    function LoadVoucherRecordList(vPOID, vList, vIsEditable){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
    				IsExist = false;
	    			if(POVoucherList != undefined){
			    		for(var y=0; y < POVoucherList.length; y++){
			    			if(POVoucherList[y].VoucherID == vList[x].VoucherID){
			    				IsExist = true;
			    			}
			    		}
	    			}

		    		if(!IsExist){
						LoadVoucherRecordRow(vPOID, vList[x], vIsEditable);
		    		}
	    		}
	    	}else{
	    		RecomputeTotalVoucher();
	    	}

	    }

	    function LoadVoucherRecordRow(vPOID, vData, vIsEditable){

	    	var tblVoucherList = $("#tblVoucherList").DataTable();

	    	intVoucherItemCount  = intVoucherItemCount  + 1;

	    	tdID = intVoucherItemCount;
	    	tdVoucherID = vData.VoucherID;
	    	tdPOID = vPOID;

	    	if(vIsEditable){
				tdCheckbox = "<span style='font-weight:normal;'><input id='chkVoucher" + intVoucherItemCount + "' class='RecomputeTotalVoucher' type='checkbox' " + (isLoadingInfo ? "checked" : "") + "></span>";
	    	}else{
				tdCheckbox = "<span style='font-weight:normal;'><input id='chkVoucher" + intVoucherItemCount + "' class='RecomputeTotalVoucher' type='checkbox' checked disabled></span>";
	    	}

			tdVoucherCode = "<span id='VoucherCode" + intVoucherItemCount + "' style='font-weight:normal;'>" + vData.VoucherCode + "</span>";
			tdVoucherAmount = "<span id='VoucherAmount" + intVoucherItemCount + "' class='pull-right' style='font-weight:normal;'>" + FormatDecimal(vData.VoucherAmount,2) + "</span>";

			//Check if record already listed
			var IsRecordExist = false;
			tblVoucherList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();

			    if(rowData[0] == vData.VoucherID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblVoucherList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdVoucherID;
			    	curData[2] = tdPOID;
			    	curData[3] = tdCheckbox;
			    	curData[4] = tdVoucherCode;
			    	curData[5] = tdVoucherAmount;

			    	tblVoucherList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblVoucherList.row.add([
						tdID,
						tdVoucherID,
						tdPOID,
						tdCheckbox, 
						tdVoucherCode,
						tdVoucherAmount
					]).draw();			
			}

	    }

	    function RecomputeTotalVoucher(){

	    	if(!isLoadingInfo){

		    	var tblVoucherList = $("#tblVoucherList").DataTable();
		    	var TotalVoucherPayment = 0;
		    	//Check Vouchers
		    	for (var i = 1; i <= intVoucherItemCount; i++) {

	    		    if ($("#chkVoucher"+i).prop("checked")){
	    		    	
	    		    	var VoucherAmount = $("#VoucherAmount"+i).text();
	    		    	VoucherAmount = VoucherAmount.replace(",", "");

			    		TotalVoucherPayment = parseFloat(TotalVoucherPayment) + parseFloat(VoucherAmount);
					}
		    	}

				$('#TotalVoucherPayment').val(FormatDecimal(TotalVoucherPayment,2));
				RecomputeTotal();
	    	}

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

	    	var TotalAmountDue = TotalGrossAmount - TotalVoucherPayment;
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

	    	var TotalVoucherPayment = 0;
    		if($('#TotalVoucherPayment').length){
    			if($("#TotalVoucherPayment").val() != ""){
		            var strTotalVoucherPayment = $("#TotalVoucherPayment").val();
		            TotalVoucherPayment = parseFloat(strTotalVoucherPayment.replace(",",""));
    			}
			}

	    	var TotalAmountDue = TotalGrossAmount - TotalVoucherPayment;

			if($('#Center').val() == "") {
				showJSMessage("Center","Please select center.","OK");
			}else{

		    	var tblVoucherList = $("#tblVoucherList").DataTable();
	            var pVoucherData = [];
	            var intVoucherCntr = 0;

		    	//Check Vouchers
		    	for (var i = 1; i <= intVoucherItemCount; i++) {

		    		var VoucherID = 0;
		    		var POID = 0;

	    		    if ($("#chkVoucher"+i).prop("checked")){

						tblVoucherList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
						    var rowVoucherData = this.data();

						    if(rowVoucherData[0] == i){
						    	VoucherID = rowVoucherData[1]
						    	POID = rowVoucherData[2]
						    }
						});

						pVoucherData[intVoucherCntr] = {
							VoucherID:VoucherID,
							POID:POID
						};

						intVoucherCntr = intVoucherCntr + 1;

					}
		    	}


		    	var tblProductList = $("#tblProductList").DataTable();
	            var pData = [];
	            var intCntr = 0;

		    	//Check Product Fields
		    	for (var i = 1; i <= intItemCount; i++) {
		    		var POItemID = 0;
		    		var POID = 0;
		    		var ProductID = 0;
		    		var UnitMeasure = "";
		    		var Qty = 0;
		    		var Price = 0;
		    		var SubTotal = 0;
			    	var blnIsIncomplete = false;

					tblProductList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
					    var rowData = this.data();

					    if(rowData[0] == i){
					    	POItemID = rowData[1]
					    	POID = rowData[2]
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

		    		if($('#Qty-' + i).length){
		    			if($("#Qty-" + i).val()!= ""){
				            Qty = $("#Qty-" + i).val();
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
							POItemID:POItemID,
							POID:POID,
							ProductID:ProductID,
							Qty:Qty,
							UnitMeasure:UnitMeasure,
							Price:Price,
							SubTotal:SubTotal
						};

						intCntr = intCntr + 1;
		    		}
		    	}

				if(pData.length <= 0){
					showJSModalMessageJS("Save PO","Please enter purchase order items.","OK");
				}else{

					$.ajax({
						type: "post",
						data: {
							_token: '{{ $Token }}',
							POID: $("#POID").val(),

							PONo: $("#PONo").val(),
							PODateTime: $("#PODateTime").val(),

							POType: $("#POType").val(),

							Status: $("#Status").val(),

							CenterID: $("#Center").val(),

							GrossAmount: TotalGrossAmount,
							TotalVoucherPayment: TotalVoucherPayment,
							TotalAmountDue: TotalAmountDue,

							Remarks: $("#Remarks").val(),
							VoucherData : pVoucherData,
							POItems: pData,
							POItemsDeleted: pDeletedItem
						},
						url: "{{ route('do-save-purchase-order') }}",
						dataType: "json",
						success: function(data){

					        $("#divLoader").hide();
							buttonOneClick("btnSave", "Save", false);
							if(data.Response =='Success'){
								$("#record-info-modal").modal('hide');
								showJSModalMessageJS("Success",data.ResponseMessage,"OK");
								LoadRecordRow(data.POInfo);
							}else{
								showJSModalMessageJS("Save Order",data.ResponseMessage,"OK");
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

	    function CancelPO(vPOID){
	    	if(vPOID > 0){
				$("#CancelledPOID").val(vPOID);
				$("#CancellationReason").text('');				
				$("#set-as-cancelled-modal").modal();
	    	}
	    }

	    function ProceedCancelPO(){

	    	if($("#CancellationReason").val() == ""){
				showJSModalMessageJS("Cancel PO","Please enter the reason for the cancellation of this PO.","OK");
	    	}else{
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						POID: $("#CancelledPOID").val(),
						Reason : $("#CancellationReason").val()
					},
					url: "{{ route('do-cancel-purchase-order') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnSaveCancel", "Save", false);
						if(data.Response =='Success'){
							$("#set-as-cancelled-modal").modal('hide');
							showJSModalMessageJS("Success",data.ResponseMessage,"OK");
							LoadRecordRow(data.POInfo);
						}else{
							showJSModalMessageJS("PO Cancellation",data.ResponseMessage,"OK");
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



