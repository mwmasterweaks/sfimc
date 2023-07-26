@extends('layout.adminweb')

@section('content')

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Purchase Order - Processing
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li class="active">Purchase Order - Processing</li>
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
				                  <th>Process No.</th>
				                  <th>DateTime</th>
				                  <th>Type</th>
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
		            <h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Purchase Order - Processing Information</b></span></h4>
	          	</div>
	          	<div class="modal-body">
	        		<input type="hidden" id="ProcessID" value="0" readonly>
		            <div class="row">
		            	<div class="col-md-12">
			            	<label class="col-md-12" style="font-size: 15px;"><i class='fa fa-shopping-cart font-size:15px;'></i>&nbsp&nbsp<b>Purchase Order Processing Information</b></label>
			        	</div>
		        	</div>

		            <div class="row">
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Process No.</label>
			                <div class="col-md-12">
			                    <input id="ProcessNo" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight:bold; color:green;" readonly>
			                </div>
		            	</div>
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Date/Time<span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="ProcessDateTime" type="text" class="form-control" value=""  placeholder="Auto Generated" style="width:100%; font-weight:normal;" readonly>
			                </div>
		            	</div>
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Process Type <span style="color:red;">*</span></label>
			                <div class="col-md-12">
								<select id="ProcessType" class="form-control select2" style="width: 100%; height: 100px; font-weight: normal;">
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
		            	<div class="col-md-8">
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
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">PO No. <span style="color:red;">*</span></label>
			                <div class="col-md-12" style="font-weight: normal;">
			                    <input id="POID" type="hidden" class="form-control" value="" readonly>
								<input id="PONo" type="text" data-type="PONo" class="form-control autocomplete_txt"  autocomplete="off" value="" style="width:100%; font-weight: normal;" required>
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
					                  <th>ProcessItemID</th>
					                  <th>ProcessID</th>
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
					              	<table id="tblVoucherList" class="table table-bordered table-hover">
						                <thead>
							                <tr>
							                  <th>ID</th>
							                  <th>VoucherID</th>
							                  <th>ProcessID</th>
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
											<a href="#" class="btn btn-success btn-flat" style="width: 150px; text-align: right;">Total Discount</a>
										</span>
										<input id="TotalDiscountPercent" type="text" placeholder="Total Discount (%)" class="form-control DecimalOnly RecomputeDiscountPercent" style="width:50%; font-weight:normal; text-align: right;" required>
										<input id="TotalDiscount" type="text" placeholder="Total Discount" class="form-control DecimalOnly RecomputeDiscount" style="width:50%; font-weight:normal; text-align: right;" required>
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
	            	<h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Cancel Purchase Order Processing</b></span></h4>
	          	</div>

          		<div class="modal-body">
        			<input type="hidden" id="CancelledPOProcessID" value="0" readonly>
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
								<a id="btnSaveCancel" href="#" class="btn btn-info btn-flat" onclick="ProceedCancelPOProcess()"><i class="fa fa-save"></i> Proceed</a>
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
				url: "{{ route('get-po-processing-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.POProcessList);
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

	    	tdID = vData.ProcessID;
	    	tdSortOption = vData.SortOption;
			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown'  style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> ";
		                  	tdOption += " <li style='text-align:left;'> " +
		                      	" <a href='#' onclick='EditPOProcess(" + vData.ProcessID + ")'>" + 
		                      		" <strong><i class='fa fa-info-circle font-size:15px;'></i> " + (vData.Status == '{{ config('app.STATUS_PENDING') }}' ? "Edit Record" : "View Record") + "</strong>" +
		                      	" </a> " +
		                   	" </li> ";
		                   	if(vData.Status != '{{ config('app.STATUS_CANCELLED') }}'){
		                   		if(vData.ReceiveID <= 0){
				                  	tdOption += " <li style='text-align:left;'> " +
				                      	" <a href='#' onclick='CancelPOProcess(" + vData.ProcessID + "," + false + ")'>" + 
				                      		" <strong><i class='fa fa-info-circle font-size:15px;'></i> Cancel Record</strong>" +
				                      	" </a> " +
				                   	" </li> ";
			                   }
		                   	}
            tdOption += " </ul> " +
	                    " </div> " ;

			tdProcessNo = "<span style='font-weight:bold;'>" + vData.ProcessNo + "</span>";
			tdProcessDateTime = "<span style='font-weight:normal;'>" + vData.ProcessDateTime + "</span>";

			tdProcessType = "<span style='font-weight:normal;'>" + vData.ProcessType + "</span>";
			
			tdPONo = "<span style='font-weight:normal;'>" + vData.PONo + "</span>";
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
			    if(rowData[0] == vData.ProcessID){
					IsRecordExist = true;

			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdSortOption;
			    	curData[2] = tdOption;
			    	curData[3] = tdProcessNo;
			    	curData[4] = tdProcessDateTime;
			    	curData[5] = tdProcessType;
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
						tdProcessNo,
						tdProcessDateTime, 
						tdProcessType,
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

			$("#ProcessID").val('0');

			$("#ProcessNo").val('');
			$("#ProcessDateTime").val('');
			$("#ProcessType").val('Regular PO').change();
			$("#Status").val('{{ config('app.STATUS_PENDING') }}').change();

			$("#POID").val('0');
			$("#PONo").val('');

			$("#TelNo").val('');
			$("#MobileNo").val('');
			$("#EmailAddress").val('');
			$("#Address").val('');
			$("#Center").val('{{ Session('ADMIN_CENTER_ID') }}').change();

			$("#Remarks").val('');
			$("#PreparedBy").val('{{ Session('ADMIN_FULLNAME') }}');

			$("#TotalGrossAmount").val("0.00");
			$("#TotalVoucherPayment").val('0.00');
			$("#TotalDiscountPercent").val('0.00');
			$("#TotalDiscount").val('0.00');
			$("#TotalAmountDue").val('0.00');

			$("#Status").prop('disabled', false);
			$("#PONo").prop('disabled', false);
			$("#ProcessType").prop('disabled', false);
			$("#Center").prop('disabled', false);
			$("#TotalDiscountPercent").prop('disabled', false);
			$("#TotalDiscount").prop('disabled', false);
			$("#Remarks").prop('disabled', false);

	        //Load Initial Data
    		intItemCount = 0;
    		intDeletedItem = 0;
    		pDeletedItem=[];
    		$("#tblProductList").DataTable().clear().draw();

	        AddEmptyRow(0);

	        //Clear Voucher Data
    		intVoucherItemCount = 0;
    		$("#tblVoucherList").DataTable().clear().draw();

			$("#btnSave").show();

	    }

	    function NewRecord(){
	    	isNewRecord = 1;
			Clearfields();
			$("#record-info-modal").modal();
	    }

	    function EditPOProcess(vRecordID){

	    	if(vRecordID > 0){
	    		isNewRecord = 0;
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						POID: vRecordID
					},
					url: "{{ route('get-po-processing-info') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						if(data.Response =='Success' && data.POProcessInfo != undefined){
							
							Clearfields();

							$("#ProcessID").val(data.POProcessInfo.ProcessID);
							$("#ProcessNo").val(data.POProcessInfo.ProcessNo);

							$("#ProcessDateTime").val(data.POProcessInfo.ProcessDateTime);
							$("#ProcessType").val(data.POProcessInfo.ProcessType).change();
							$("#Status").val(data.POProcessInfo.Status).change();

							$("#POID").val(data.POProcessInfo.POID);
							$("#PONo").val(data.POProcessInfo.PONo);

							$("#Center").val(data.POProcessInfo.CenterID).change();

							$("#TelNo").val(data.POProcessInfo.TelNo);
							$("#MobileNo").val(data.POProcessInfo.MobileNo);
							$("#EmailAddress").val(data.POProcessInfo.EmailAddress);
							$("#Address").val(data.POProcessInfo.Address + ", " + data.POProcessInfo.City + ", " + data.POProcessInfo.StateProvince + ", " + data.POProcessInfo.ZipCode + " " + data.POProcessInfo.Country);

							$("#Remarks").val(data.POProcessInfo.Remarks);
							$("#PreparedBy").val(data.POProcessInfo.ApprovedBy);

							var vIsEditable = false;
							if(data.POProcessInfo.Status == "{{ config('app.STATUS_PENDING') }}"){
								vIsEditable = true;
							}

							$("#TotalGrossAmount").val(FormatDecimal(data.POProcessInfo.TotalGrossAmount,2));
							$("#TotalVoucherPayment").val(FormatDecimal(data.POProcessInfo.TotalVoucherPayment,2));
							$("#TotalDiscountPercent").val(FormatDecimal(data.POProcessInfo.TotalDiscountPercent,2));
							$("#TotalDiscount").val(FormatDecimal(data.POProcessInfo.TotalDiscount,2));
							$("#TotalAmountDue").val(FormatDecimal(data.POProcessInfo.TotalAmountDue,2));

							$("#PONo").prop('disabled', !vIsEditable);
							$("#ProcessType").prop('disabled', !vIsEditable);
							$("#Center").prop('disabled', !vIsEditable);
							$("#TotalDiscountPercent").prop('disabled', !vIsEditable);
							$("#TotalDiscount").prop('disabled', !vIsEditable);
							$("#Status").prop('disabled', !vIsEditable);
							$("#Remarks").prop('disabled', !vIsEditable);

							getPOProcessVoucher(data.POProcessInfo.ProcessID, vIsEditable);
							getPOProcessItem(data.POProcessInfo.ProcessID, vIsEditable);

							if(!vIsEditable){
								$("#btnSave").hide();
							}else{
								$("#btnSave").show();
							}

						}else{
							showJSModalMessageJS("PO Processing Information",data.ResponseMessage,"OK");
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

	    $("#ProcessType").change(function(){
			ResetProductPrices();
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

			ResetProductPrices();

      	}); 

	    function getPOProcessItem(vProcessID, vIsEditable){

	    	if(vProcessID > 0){
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
						        LoadPOProcessItemList(vProcessID, data.POProcessItemList, vIsEditable);
					        }else{
						        AddEmptyRow(vProcessID);
					        }
					 		RecomputeTotal();

							$("#record-info-modal").modal();

							buttonOneClick("btnSave", "Save", false);
						}else{
							showJSModalMessageJS("PO Processing Item",data.ResponseMessage,"OK");
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

	    function getPOItem(vPOID){

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
						        LoadPOProcessItemList($("#ProcessID").val(), data.POItemList, true);
					        }else{
						        AddEmptyRow($("#ProcessID").val());
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

    	function LoadPOProcessItemList(vProcessID, vList, vIsEditable){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadPOProcessItemRow(vProcessID, vList[x], vIsEditable);
	    		}
	    	}
	    }

	    function LoadPOProcessItemRow(vProcessID, vData, vIsEditable){

	    	var tblProductList = $("#tblProductList").DataTable();

	    	intItemCount  = intItemCount  + 1;

	    	tdID = intItemCount ;
	    	tdProcessItemID = vData.ProcessItemID;
	    	tdProcessID = vProcessID;

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
				tdButtons += "		<label style='cursor:pointer;font-size:12px;margin-right:2px;' class='label label-primary'  onClick='AddEmptyRow(" + vProcessID + ")'>";
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
			    	curData[1] = tdProcessItemID;
			    	curData[2] = tdProcessID;
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
						tdProcessItemID,
						tdProcessID,
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

	    function AddEmptyRow(vProcessID){

	    	var tblProductList = $("#tblProductList").DataTable();

	    	intItemCount = intItemCount + 1;

	    	tdID = intItemCount ;
			tdProcessItemID = 0;
	    	tdProcessID = vProcessID;

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
			tdButtons += "		<label style='cursor:pointer;font-size:12px;margin-right:2px;' class='label label-primary'  onClick='AddEmptyRow(" + vProcessID + ")'>";
			tdButtons +="			<i class='fa fa-plus'></i>";
			tdButtons +="		</label>";
			tdButtons +="		<label style='cursor:pointer;font-size:12px;' class='label label-danger' onClick='DeleteRow(" + intItemCount + ")'>";
			tdButtons +="			<i class='fa fa-trash'></i> ";
			tdButtons +="		</label>";
			tdButtons +="	</div>";
			tdButtons +="</div>";

			tblProductList.row.add([
				tdID,
				tdProcessItemID,
				tdProcessID,
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

		$(document).on('change keyup blur','.RecomputeDiscount',function(){
			RecomputeDiscount();
		});

		$(document).on('change keyup blur','.RecomputeDiscountPercent',function(){
			RecomputeDiscountPercent();
		});

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

	        	if($("#ProcessType").val() == "Voucher Replenishment"){
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

				if($("#ProcessType").val() == "Voucher Replenishment"){
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

	    function getPOProcessVoucher(vProcessID){

	    	if(vProcessID > 0){
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						ProcessID : vProcessID
					},
					url: "{{ route('get-po-processing-voucher-list') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						if(data.Response =='Success' && data.POProcessVoucherList != undefined){
					        LoadVoucherRecordList(vProcessID, data.POProcessVoucherList);
						}

			    		isLoadingInfo = false;
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

	    function getPOVoucherRecordList(vPOID){

	    	if(vPOID > 0){
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						POID: vPOID,
						SearchText: '',
						PageNo: 0
					},
					url: "{{ route('get-purchase-order-voucher-list') }}",
					dataType: "json",
					success: function(data){
						LoadVoucherRecordList($("#ProcessID").val(), data.POVoucherList);
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

	    function LoadVoucherRecordList(vProcessID, vList){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadVoucherRecordRow(vProcessID, vList[x]);
	    		}
	    	}
			RecomputeTotalVoucher();
	    }

	    function LoadVoucherRecordRow(vProcessID, vData){

	    	var tblVoucherList = $("#tblVoucherList").DataTable();

	    	intVoucherItemCount  = intVoucherItemCount  + 1;

	    	tdID = intVoucherItemCount;
	    	tdVoucherID = vData.VoucherID;
	    	tdProcessID = vProcessID;

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
			    	curData[2] = tdProcessID;
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
						tdProcessID,
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

    		    	var VoucherAmount = $("#VoucherAmount"+i).text();
    		    	VoucherAmount = VoucherAmount.replace(",", "");

		    		TotalVoucherPayment = parseFloat(TotalVoucherPayment) + parseFloat(VoucherAmount);
		    	}

				$('#TotalVoucherPayment').val(FormatDecimal(TotalVoucherPayment,2));
				RecomputeTotal();
	    	}

	    }	

	    function RecomputeDiscountPercent(){

	    	var TotalGrossAmount = 0;
    		if($('#TotalGrossAmount').length){
    			if($("#TotalGrossAmount").val() != ""){
		            var strTotalGrossAmount = $("#TotalGrossAmount").val();
		            TotalGrossAmount = parseFloat(strTotalGrossAmount.replace(",",""));
    			}
			}

	    	var TotalDiscountPercent = 0;
    		if($('#TotalDiscountPercent').length){
    			if($("#TotalDiscountPercent").val() != ""){
		            var strTotalDiscountPercent = $("#TotalDiscountPercent").val();
		            TotalDiscountPercent = parseFloat(strTotalDiscountPercent.replace(",",""));
    			}
			}

			TotalDiscount = TotalGrossAmount * (TotalDiscountPercent/100); 
			$('#TotalDiscount').val(FormatDecimal(TotalDiscount,2));

			RecomputeTotal();

	    }	

	    function RecomputeDiscount(){

	    	var TotalGrossAmount = 0;
    		if($('#TotalGrossAmount').length){
    			if($("#TotalGrossAmount").val() != ""){
		            var strTotalGrossAmount = $("#TotalGrossAmount").val();
		            TotalGrossAmount = parseFloat(strTotalGrossAmount.replace(",",""));
    			}
			}

	    	var TotalDiscount = 0;
    		if($('#TotalDiscount').length){
    			if($("#TotalDiscount").val() != ""){
		            var strTotalDiscount = $("#TotalDiscount").val();
		            TotalDiscount = parseFloat(strTotalDiscount.replace(",",""));
    			}
			}

			TotalDiscountPercent = (TotalDiscount / TotalGrossAmount) * 100; 
			$('#TotalDiscountPercent').val(FormatDecimal(TotalDiscountPercent,2));

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

			if($('#Center').val() == "") {
				showJSMessage("Center","Please select center.","OK");
			}else{

		    	var tblVoucherList = $("#tblVoucherList").DataTable();
	            var pVoucherData = [];
	            var intVoucherCntr = 0;

		    	//Check Vouchers
		    	for (var i = 1; i <= intVoucherItemCount; i++) {

		    		var VoucherID = 0;
		    		var ProcessID = 0;

					tblVoucherList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
					    var rowVoucherData = this.data();

					    if(rowVoucherData[0] == i){
					    	VoucherID = rowVoucherData[1]
					    	ProcessID = rowVoucherData[2]
					    }
					});

					pVoucherData[intVoucherCntr] = {
						VoucherID:VoucherID,
						ProcessID:ProcessID
					};

					intVoucherCntr = intVoucherCntr + 1;
		    	}


		    	var tblProductList = $("#tblProductList").DataTable();
	            var pData = [];
	            var intCntr = 0;

		    	//Check Product Fields
		    	for (var i = 1; i <= intItemCount; i++) {
		    		var ProcessItemID = 0;
		    		var ProcessID = 0;
		    		var ProductID = 0;
		    		var UnitMeasure = "";
		    		var Qty = 0;
		    		var Price = 0;
		    		var SubTotal = 0;
			    	var blnIsIncomplete = false;

					tblProductList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
					    var rowData = this.data();

					    if(rowData[0] == i){
					    	ProcessItemID = rowData[1]
					    	ProcessID = rowData[2]
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
							ProcessItemID:ProcessItemID,
							ProcessID:ProcessID,
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
					showJSModalMessageJS("Save PO Processing","Please enter items.","OK");
				}else{

					$.ajax({
						type: "post",
						data: {
							_token: '{{ $Token }}',
							ProcessID: $("#ProcessID").val(),

							ProcessNo: $("#ProcessNo").val(),
							ProcessDateTime: $("#ProcessDateTime").val(),
							ProcessType: $("#ProcessType").val(),
							Status: $("#Status").val(),

							POID: $("#POID").val(),
							CenterID: $("#Center").val(),

							GrossAmount: TotalGrossAmount,
							TotalVoucherPayment: TotalVoucherPayment,
							TotalDiscountPercent: TotalDiscountPercent,
							TotalDiscount: TotalDiscount,
							TotalAmountDue: TotalAmountDue,

							Remarks: $("#Remarks").val(),
							VoucherData : pVoucherData,
							POProcessItems: pData,
							POProcessItemsDeleted: pDeletedItem
						},
						url: "{{ route('do-save-po-processing') }}",
						dataType: "json",
						success: function(data){

					        $("#divLoader").hide();
							buttonOneClick("btnSave", "Save", false);
							if(data.Response =='Success'){
								$("#record-info-modal").modal('hide');
								showJSModalMessageJS("Success",data.ResponseMessage,"OK");
								LoadRecordRow(data.POProcessInfo);
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

	    function CancelPOProcess(vPOID){
	    	if(vPOID > 0){
				$("#CancelledPOProcessID").val(vPOID);
				$("#CancellationReason").text('');				
				$("#set-as-cancelled-modal").modal();
	    	}
	    }

	    function ProceedCancelPOProcess(){

	    	if($("#CancellationReason").val() == ""){
				showJSModalMessageJS("Cancel PO","Please enter the reason for the cancellation of this PO.","OK");
	    	}else{
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						ProcessID: $("#CancelledPOProcessID").val(),
						Reason : $("#CancellationReason").val()
					},
					url: "{{ route('do-cancel-po-processing') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnSaveCancel", "Save", false);
						if(data.Response =='Success'){
							$("#set-as-cancelled-modal").modal('hide');
							showJSModalMessageJS("Success",data.ResponseMessage,"OK");
							LoadRecordRow(data.POProcessInfo);
						}else{
							showJSModalMessageJS("PO Process Cancellation",data.ResponseMessage,"OK");
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
	                        url: "{{ route('get-purchase-order-search-list') }}",
	                        dataType: "json",
	                        method: 'get',
							data: {
                             	SearchText: request.term,
								PageNo : 1,
								Status : "{{ config('app.STATUS_APPROVED') }}",
								IsUnProcessedOnly:1
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
	                
	                Clearfields();

	                $('#POID').val(data[0]);
	                $('#Center').val(data[2]).change();
	                $('#ProcessType').val(data[3]).change();
	                getPOItem(data[0]);
	                getPOVoucherRecordList(data[0]);

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



