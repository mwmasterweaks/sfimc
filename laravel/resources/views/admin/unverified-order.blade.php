@extends('layout.adminweb')

@section('content')

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Unverified Order
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Order Management</li>
			<li class="active">Unverified Order</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-theme">
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
				                  <th>Center</th>
				                  <th>Order No.</th>
				                  <th>Order DateTime</th>
				                  <th>Customer</th>
				                  <th>Mobile No.</th>
				                  <th>Email Address</th>
				                  <th>Prepared By</th>
				                  <th>Mode Of Payment</th>
				                  <th style="text-align: right;">Net Amount Due</th>
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
		            <h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Order Information</b></span></h4>
	          	</div>
	          	<div class="modal-body">
	        		<input type="hidden" id="OrderID" value="0" readonly>
		            <div class="row">
		            	<div class="col-md-12">
			            	<label class="col-md-12" style="font-size: 15px;"><i class='fa fa-shopping-cart font-size:15px;'></i>&nbsp&nbsp<b>Order Information</b></label>
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
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Order No.</label>
			                <div class="col-md-12">
			                    <input id="OrderNo" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight:bold; color:green;" readonly>
			                </div>
		            	</div>
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Order Date/Time<span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="OrderDateTime" type="text" class="form-control" value=""  placeholder="Auto Generated" style="width:100%; font-weight:normal;" readonly>
			                </div>
		            	</div>

		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="Status" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" readonly>
			                </div>
		            	</div>
		            </div>
		            <div style="clear:both;"></div>
		            <br>                
		            <div class="row">
		            	<div class="col-md-12">
			            	<label class="col-md-12" style="font-size: 15px;"><i class='fa fa-info font-size:15px;'></i>&nbsp&nbsp<b>Customer Information</b></label>
			        	</div>
		        	</div>
		            <div style="clear:both;"></div>
		            <br>                
		            <div class="row">
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Customer Type</label>
			                <div class="col-md-12">
								<select id="CustomerType" class="form-control select2" style="width: 100%; height: 100px; font-weight:normal;">
									<option value="Member" selected>Member</option>
									<option value="Guest">Guest</option>
								</select>
			                </div>
		            	</div>
		            	<div id="divMember" class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Member 
			                Name <span style="color:red;">*</span></label>
			                <div class="col-md-12">
	                            <input type="hidden" id="CustomerEntryID" name="EntryID" readonly>
	                            <input type="text" data-type="MemberName" id="MemberName" name="MemberName" placeholder="Search by Member No., Member Name" class="form-control autocomplete_txt" autocomplete="off" style="width:100%; font-weight:normal;">
			                </div>
		            	</div>
		            	<div id="divGuest" class="col-md-4" style="display: none;">
			                <label class="col-md-12" style="font-weight: normal;">Customer Name <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="CustomerName" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            </div>

		            <div style="clear:both;"></div>
		            <br>                
		            <div class="row">
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Mobile No. <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="MobileNo" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Email Address <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="EmailAddress" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            </div>
		            <div style="clear:both;"></div>
		            <br>                
		            <div class="row">
		            	<div class="col-md-12">
			            	<label class="col-md-12" style="font-size: 15px;"><i class='fa fa-info font-size:15px;'></i>&nbsp&nbsp<b>Shipping/Billing Address</b></label>
			        	</div>
		        	</div>
		            <div style="clear:both;"></div>
		            <br>                  
		            <div class="row">
		            	<div class="col-md-12">
			                <label class="col-md-12" style="font-weight: normal;">Address <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="Address" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">City <span style="color:red;">*</span></label>
			                <div class="col-md-12" style="font-weight: normal;">
								<select id="City" class="form-control select2" style="width: 100%; font-weight: normal;">
									<option value="">Please Select</option>
									@foreach ($CountryCityList as $ckey)
										<option value="{{ $ckey->CityID }}"
			                                data-cityid="{{$ckey->CityID}}"
			                                data-cityprovince="{{$ckey->Province}}"
			                                data-cityzipcode="{{$ckey->ZipCode}}"
											>{{ $ckey->City }}</option>
									@endforeach
								</select>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">State/Province</label>
			                <div class="col-md-12">
			                    <input id="StateProvince" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Zip Code</label>
			                <div class="col-md-12">
			                    <input id="ZipCode" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-12">
			                <label class="col-md-12" style="font-weight: normal;">Country <span style="color:red;">*</span></label>
			                <div class="col-md-12" style="font-weight: normal;">
								<select id="Country" class="form-control select2" style="width: 100%; font-weight: normal;">
									<option value="174" selected>Philippines</option>
								</select>
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
					                  <th>OrderItemID</th>
					                  <th>OrderID</th>
					                  <th style="width: 25%;">Product Name <span style="color:red;">*</span></th>
					                  <th style="text-align: right;">Qty <span style="color:red;">*</span></th>
					                  <th style="text-align: right;">SOH <span style="color:red;">*</span></th>
					                  <th style="text-align: right;">RV <span style="color:red;">*</span></th>
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
					                <label class="col-md-12" style="font-weight: normal;">Mode Of Payment</label>
					                <div class="col-md-12">
										<select id="ModeOfPayment" class="form-control select2" style="width: 100%; height: 100px; font-weight:normal;">
											<option value="Cash" selected>Cash</option>
											<option value="COD">COD</option>
										</select>
					                </div>
				            	</div>
			            	</div>

				            <div class="row">
				            	<div class="col-md-12">
					                <label class="col-md-12" style="font-weight: normal;">Shipper</label>
					                <div class="col-md-12">
										<select id="Shipper" class="form-control select2" style="width: 100%; height: 100px; font-weight:normal;">
											<option value="" selected>Pick-Up</option>
											@foreach($ShipperList as $shpr)
											<option value="{{ $shpr->ShipperID }}">{{ $shpr->ShipperName }}</option>
											@endforeach
										</select>
					                </div>
				            	</div>
			            	</div>
			            	
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
							                  <th>OrderID</th>
							                  <th style="width: 15px;"></th>
							                  <th style="width: 150px;">Nth Pair</th>
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
											<a href="#" class="btn btn-primary btn-flat" style="width: 150px; text-align: right;">Total Amount Due</a>
										</span>
										<input id="TotalGrossAmount" type="text" placeholder="Total Gross Amount" class="form-control DecimalOnly" style="width:100%; font-weight:normal; text-align: right;" readonly>
									</div>		
								</div>		
							</div>	
				            <div class="row">
				            	<div class="col-md-12">
									<div class="input-group margin pull-right">
										<span class="input-group-btn">
											<a href="#" class="btn btn-primary btn-flat" style="width: 150px; text-align: right;">Shipping Charges</a>
										</span>
										<input id="ShippingCharges" type="text" placeholder="Shipping Charges" class="form-control DecimalOnly RecomputeTotal" style="width:100%; font-weight:normal; text-align: right;" required>
									</div>		
								</div>		
							</div>	
				            <div class="row">
				            	<div class="col-md-12">
									<div class="input-group margin pull-right">
										<span class="input-group-btn">
											<a href="#" class="btn btn-primary btn-flat" style="width: 150px; text-align: right;">Total Discount</a>
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
											<a href="#" class="btn btn-primary btn-flat" style="width: 150px; text-align: right;">Net Amount Due</a>
										</span>
										<input id="TotalAmountDue" type="text" placeholder="Total Amount Due" class="form-control" style="width:100%; font-weight:normal; text-align: right;" readonly>
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
				            <div id="divCashPayment" class="row">
				            	<div class="col-md-12">
									<div class="input-group margin pull-right">
										<span class="input-group-btn">
											<a href="#" class="btn btn-success btn-flat" style="width: 150px; text-align: right;">Total Cash Payment</a>
										</span>
										<input id="TotalCashPayment" type="text" placeholder="Total Cash Payment" class="form-control DecimalOnly RecomputeTotal" style="width:100%; font-weight:normal; text-align: right;" required>
									</div>		
								</div>	
							</div>	
				            <div id="divAmountChange" class="row">
				            	<div class="col-md-12">
									<div class="input-group margin pull-right">
										<span class="input-group-btn">
											<a href="#" class="btn btn-danger btn-flat" style="width: 150px; text-align: right;">Amount Change</a>
										</span>
										<input id="AmountChange" type="text" placeholder="Amount Change" class="form-control" style="width:100%; font-weight:normal; text-align: right;" readonly>
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

	<div id="set-verified-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">

	          <div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Order - Set As Verified</b></span></h4>
	          </div>

	          <div class="modal-body">

	        	<input type="hidden" id="VerifyOrderID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Are you sure you want to set this order as verified?</label>
	            	</div>
            	</div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="modal-footer">
					<div class="input-group pull-right">
						<span class="input-group-btn">
							<a id="btnVerifyOrder" href="#" class="btn btn-info btn-flat" onclick="ProceedVerifyOrder()"><i class="fa fa-save"></i> Proceed</a>
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
	    var CustomerType = 'Member';

		var isVoucherUsed = false;

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
					CustomerEntryID: 0,
					SearchText: vSearchText,
					PageNo: vPageNo,
					Status: "{{ config('app.STATUS_UNVERIFIED') }}"
				},
				url: "{{ route('get-order-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.OrderList);
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

	    	tdID = vData.OrderID;
	    	tdSortOption = vData.SortOption;
			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown'  style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> ";
		                  	tdOption += " <li style='text-align:left;'> " +
		                      	" <a href='#' onclick='EditOrder(" + vData.OrderID + "," + (vData.IsPaid == 1 || vData.Status == '{{ config('app.STATUS_CANCELLED') }}' || vData.Status == '{{ config('app.STATUS_RETURNED') }}' || vData.Status == '{{ config('app.STATUS_DELIVERED') }}' ? false : true) + ")'>" + 
		                      		" <strong><i class='fa fa-info-circle font-size:15px;'></i> " + (vData.IsPaid == 1 || vData.Status == '{{ config('app.STATUS_CANCELLED') }}' || vData.Status == '{{ config('app.STATUS_RETURNED') }}' || vData.Status == '{{ config('app.STATUS_DELIVERED') }}' ? "View Order" : "Edit Order") + 
		                      		"</strong>" +
		                      	" </a> " +
		                   	" </li> ";
                      	tdOption += " <li style='text-align:left;'> " +
                          	" <a href='#' onclick='SetAsVerified(" + vData.OrderID + ")'>" + 
                          		" <strong><i class='fa fa-minus-circle' style='font-size:15px;'></i> Set As Verified " + 
                          		" </strong>" +
                          	" </a> " +
                       	" </li> ";

            tdOption += " </ul> " +
	                    " </div> " ;

			tdCenter = "<span style='font-weight:normal; text-align:center;'>" + vData.Center + "</span>";

			tdOrderNo = "<span style='font-weight:normal;'>" + vData.OrderNo + "</span>";
			tdOrderDateTime = "<span style='font-weight:normal;'>" + vData.OrderDateTime + "</span>";
			tdCustomerName = "<span style='font-weight:normal;'>" + vData.CustomerType + ' - ' + vData.CustomerName + "</span>";

			tdMobileNo = "<span style='font-weight:normal;'>" + vData.MobileNo + "</span>";
			tdEmailAddress = "<span style='font-weight:normal;'>" + vData.EmailAddress + "</span>";
			tdPreparedBy = "<span style='font-weight:normal;'>" + vData.ApprovedBy + "</span>";
			tdModeOfPayment = "<span style='font-weight:normal;'>" + vData.ModeOfPayment + "</span>";
			tdTotalAmountDue = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.TotalAmountDue,2) + "</span>";

			tdStatus = "";
			if(vData.Status == "Pending"){
				tdStatus += "<span class='label label-warning' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}else if(vData.Status == "{{ config('app.STATUS_UNVERIFIED') }}"){
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ffae00;'>" + vData.Status + "</span>";
			}else if(vData.Status == "{{ config('app.STATUS_VERIFIED') }}"){
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#b7d10d;'>" + vData.Status + "</span>";
			}else if(vData.Status == "{{ config('app.STATUS_PACKED') }}"){
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#0414b0;'>" + vData.Status + "</span>";
			}else if(vData.Status == "{{ config('app.STATUS_SHIPPED') }}"){
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#0db4d1;'>" + vData.Status + "</span>";
			}else if(vData.Status == "{{ config('app.STATUS_DELIVERED') }}"){
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff00f0;'>" + vData.Status + "</span>";
			}else if(vData.Status == "{{ config('app.STATUS_CANCELLED') }}" || vData.Status == "{{ config('app.STATUS_RETURNED') }}"){
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.Status + "</span>";
			}else{
				tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}

			if(vData.IsPaid == 0){
				tdStatus += "<span class='label label-danger' style='font-weight:normal; text-align:center;'>" + (vData.IsPaid == 0 ? "Unpaid" : "Paid") + "</span>";
			}else{
				tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + (vData.IsPaid == 0 ? "Unpaid" : "Paid") + "</span>";
			}

			tdStatus += "<br><span class='label label-success' style='font-weight:normal; text-align:center;'> Order From " + vData.Source + "</span>";

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

			    var rowData = this.data();
			    if(rowData[0] == vData.OrderID){
					IsRecordExist = true;

			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdSortOption;
			    	curData[2] = tdOption;
			    	curData[3] = tdCenter;
			    	curData[4] = tdOrderNo;
			    	curData[5] = tdOrderDateTime;
			    	curData[6] = tdCustomerName;
			    	curData[7] = tdMobileNo;
			    	curData[8] = tdEmailAddress;
			    	curData[9] = tdPreparedBy;
			    	curData[10] = tdModeOfPayment;
			    	curData[11] = tdTotalAmountDue;
			    	curData[12] = tdStatus;
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
						tdOrderNo,
						tdOrderDateTime, 
						tdCustomerName, 
						tdMobileNo,
						tdEmailAddress,
						tdPreparedBy,
						tdModeOfPayment,
						tdTotalAmountDue,
						tdStatus
					]).draw().node();			
			}

	    }

	    function Clearfields(){

	    	CustomerType = "Member";

			$("#CenterID").val('{{ Session('ADMIN_CENTER_ID') }}');
			$("#Center").val('{{ Session('ADMIN_CENTER') }}');

			$("#OrderID").val('0');
			$("#OrderNo").val('');
			$("#OrderDateTime").val('');
			$("#Status").val('');

			$("#CustomerType").val(CustomerType).change();
			$("#CustomerEntryID").val('0');
			$("#MemberName").val('');
			$("#CustomerName").val('');
			$("#MobileNo").val('');
			$("#EmailAddress").val('');

			$("#Address").val('');
			$("#City").val('').change();
			$("#StateProvince").val('');
			$("#ZipCode").val('');
			$("#Country").val(174).change();

			$("#ModeOfPayment").val('Cash').change();
			$("#Shipper").val('').change();
			$("#divCashPayment").show();
			$("#divAmountChange").show();

			$("#Remarks").val('');
			$("#PreparedBy").val('{{ Session('ADMIN_FULLNAME') }}');

			$("#TotalGrossAmount").val("0.00");
			$("#ShippingCharges").val("0.00");
			$("#TotalDiscountPercent").val('0.00');
			$("#TotalDiscount").val('0.00');
			$("#TotalAmountDue").val('0.00');
			$("#TotalVoucherPayment").val('0.00');
			$("#TotalCashPayment").val("0.00");
			$("#AmountChange").val('0.00');

			$("#divMember").show();
			$("#divGuest").hide();

			$("#CustomerType").prop('disabled', false);
			$("#MemberName").prop('disabled', false);
			$("#CustomerName").prop('disabled', false);
			$("#MobileNo").prop('disabled', false);
			$("#EmailAddress").prop('disabled', false);

			$("#Address").prop('disabled', false);
			$("#City").prop('disabled', false);
			$("#StateProvince").prop('disabled', false);
			$("#ZipCode").prop('disabled', false);
			$("#Country").prop('disabled', false);

			$("#ModeOfPayment").prop('disabled', false);
			$("#Shipper").prop('disabled', false);
			$("#Remarks").prop('disabled', false);

			$("#ShippingCharges").prop('disabled', false);
			$("#TotalDiscountPercent").prop('disabled', false);
			$("#TotalDiscount").prop('disabled', false);
			$("#TotalCashPayment").prop('disabled', false);

	        //Load Initial Data
    		intItemCount = 0;
    		intDeletedItem = 0;
    		pDeletedItem=[];
    		$("#tblProductList").DataTable().clear().draw();

	        AddEmptyRow(0);

	        //Clear Voucher Data
    		intVoucherCntr = 0;
    		$("#tblVoucherList").DataTable().clear().draw();

			$("#btnSave").show();

	    }

	    function NewRecord(){
	    	isNewRecord = 1;
			Clearfields();
			$("#record-info-modal").modal();
	    }

	    function EditOrder(vRecordID, vIsEditable){

	    	if(vRecordID > 0){
	    		isNewRecord = 0;
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						OrderID: vRecordID
					},
					url: "{{ route('get-order-info') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						if(data.Response =='Success' && data.OrderInfo != undefined){
							
							Clearfields();

							$("#CenterID").val(data.OrderInfo.CenterID);
							$("#Center").val(data.OrderInfo.Center);
							
							$("#OrderID").val(data.OrderInfo.OrderID);
							$("#OrderNo").val(data.OrderInfo.OrderNo);
							$("#OrderDateTime").val(data.OrderInfo.OrderDateTime);
							$("#Status").val(data.OrderInfo.Status);

							$("#CustomerType").val(data.OrderInfo.CustomerType).change();
							$("#divMember").hide();
							$("#divGuest").hide();
							if(data.OrderInfo.CustomerType == "Member"){
								$("#divMember").show();
								$("#CustomerEntryID").val(data.OrderInfo.CustomerEntryID);
								$("#MemberName").val(data.OrderInfo.CustomerName);
							}else{
								$("#divGuest").show();
								$("#CustomerName").val(data.OrderInfo.CustomerName);
							}

							$("#MobileNo").val(data.OrderInfo.MobileNo);
							$("#EmailAddress").val(data.OrderInfo.EmailAddress);

							$("#Address").val(data.OrderInfo.Address);
							$("#City").val(data.OrderInfo.CityID).change();
							$("#StateProvince").val(data.OrderInfo.StateProvince);
							$("#ZipCode").val(data.OrderInfo.ZipCode);
							$("#Country").val(data.OrderInfo.CountryID).change();

							$("#ModeOfPayment").val(data.OrderInfo.ModeOfPayment).change();
							$("#Shipper").val(data.OrderInfo.ShipperID).change();
							$("#Remarks").val(data.OrderInfo.Remarks);
							$("#PreparedBy").val(data.OrderInfo.ApprovedBy);

							$("#TotalGrossAmount").val(FormatDecimal(data.OrderInfo.TotalGrossAmount,2));
							$("#ShippingCharges").val(FormatDecimal(data.OrderInfo.ShippingCharges,2));
							$("#TotalDiscountPercent").val(FormatDecimal(data.OrderInfo.TotalDiscountPercent,2));
							$("#TotalDiscount").val(FormatDecimal(data.OrderInfo.TotalDiscountAmount,2));
							$("#TotalAmountDue").val(FormatDecimal(data.OrderInfo.TotalAmountDue,2));
							$("#TotalVoucherPayment").val(FormatDecimal(data.OrderInfo.TotalVoucherPayment,2));
							$("#TotalCashPayment").val(FormatDecimal(data.OrderInfo.TotalCashPayment,2));
							$("#AmountChange").val(FormatDecimal(data.OrderInfo.AmountChange,2));

							$("#CustomerType").prop('disabled', !vIsEditable);
							$("#MemberName").prop('disabled', !vIsEditable);
							$("#CustomerName").prop('disabled', !vIsEditable);
							$("#MobileNo").prop('disabled', !vIsEditable);
							$("#EmailAddress").prop('disabled', !vIsEditable);

							$("#Address").prop('disabled', !vIsEditable);
							$("#City").prop('disabled', !vIsEditable);
							$("#StateProvince").prop('disabled', !vIsEditable);
							$("#ZipCode").prop('disabled', !vIsEditable);
							$("#Country").prop('disabled', !vIsEditable);

							$("#ModeOfPayment").prop('disabled', !vIsEditable);
							$("#Shipper").prop('disabled', !vIsEditable);
							$("#Remarks").prop('disabled', !vIsEditable);

							$("#ShippingCharges").prop('disabled', !vIsEditable);
							$("#TotalDiscountPercent").prop('disabled', !vIsEditable);
							$("#TotalDiscount").prop('disabled', !vIsEditable);
							$("#TotalCashPayment").prop('disabled', !vIsEditable);

							getOrderVoucher(data.OrderInfo.OrderID, vIsEditable);
							getOrderItem(data.OrderInfo.OrderID, vIsEditable);

							if(vIsEditable){
								$("#btnSave").show();
							}else{
								$("#btnSave").hide();
							}

						}else{
							showJSModalMessageJS("Order Information",data.ResponseMessage,"OK");
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

	    $("#CustomerType").change(function(){
        	$("#divMember").hide();
        	$("#divGuest").hide();
	      	if($("#CustomerType").val() == "Member"){
	        	$("#divMember").show();
	      	}else{
	        	$("#divGuest").show();
	      	}
	    });

	    $("#City").change(function(){

	      if($("#City").find('option:selected').data('cityprovince') != undefined){
	        $("#StateProvince").val($("#City").find('option:selected').data('cityprovince'));
	      }

	      if($("#City").find('option:selected').data('cityzipcode') != undefined){
	        $("#ZipCode").val($("#City").find('option:selected').data('cityzipcode'));
	      }
	 
	    });

	    $("#ModeOfPayment").change(function(){

			$("#divCashPayment").hide();
			$("#divAmountChange").hide();
			if($("#ModeOfPayment").val() == 'Cash'){
				$("#divCashPayment").show();
				$("#divAmountChange").show();
			}

	    });

	    function getOrderItem(vOrderID, vIsEditable){

	    	if(vOrderID > 0){
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						OrderID : vOrderID
					},
					url: "{{ route('get-order-item-list') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						if(data.Response =='Success' && data.OrderItemList != undefined){

					        //Load Initial Data
				    		intItemCount = 0;
				    		intDeletedItem = 0;
				    		pDeletedItem=[];
				    		$("#tblProductList").DataTable().clear().draw();

					        if(data.OrderItemList.length > 0){
						        LoadOrderItemList(vOrderID, data.OrderItemList, vIsEditable);
					        }else{
						        AddEmptyRow(vOrderID);
					        }
					 		RecomputeTotal();

							$("#record-info-modal").modal();

							buttonOneClick("btnSave", "Save", false);
						}else{
							showJSModalMessageJS("Order Item",data.ResponseMessage,"OK");
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

    	function LoadOrderItemList(vOrderID, vList, vIsEditable){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadOrderItemRow(vOrderID, vList[x], vIsEditable);
	    		}
	    	}
	    }

	    function LoadOrderItemRow(vOrderID, vData, vIsEditable){

	    	var tblProductList = $("#tblProductList").DataTable();

	    	intItemCount  = intItemCount  + 1;

	    	tdID = intItemCount ;
	    	tdOrderItemID = vData.OrderItemID;
	    	tdOrderID = vOrderID;

	    	if(vIsEditable){
				tdProduct = "<select id='Product-" + intItemCount  + "' class='form-control select2' onChange='SelectProduct("  +  intItemCount + ")' style='width: 100%; font-weight:normal;'>";
				tdProduct += "<option value=''>Please Select</option>";
					@foreach ($ProductList as $pkey)
						tdProduct += "<option value='{{ $pkey->ProductID }}' ";
						tdProduct += " data-brand='{{$pkey->Brand}}' ";
						tdProduct += " data-category='{{$pkey->Category}}' ";
						tdProduct += " data-measurement='{{$pkey->Measurement}}' ";
						tdProduct += " data-stockonhand='{{$pkey->StockOnHand}}' ";
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

			tdSOH = "<input id='SOH-" + intItemCount  + "'  type='text' class='form-control' value='" + FormatDecimal(vData.StockOnHand,0) + "' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdRV = "<input id='RebatableValue-" + intItemCount  + "'  type='text' class='form-control' value='" + FormatDecimal(vData.RebatableValue,2) + "' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdUnitMeasure = "<input id='UnitMeasure-" + intItemCount  + "'  type='text' class='form-control' value='" + vData.Measurement + "' style='width:100%; font-weight:normal;' readonly>";

			tdPrice = "<input id='Price-" + intItemCount + "' type='text' class='form-control' value='" + FormatDecimal(vData.Price,2) + "' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdSubTotal = "<input id='SubTotal-" + intItemCount + "' type='text' class='form-control' value='" + FormatDecimal(vData.SubTotal,2) + "' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdButtons = "<div class='col-md-12' style='margin-top:5px;'>";
			tdButtons +="	<div class='pull-right'>";
			if(vIsEditable){
				tdButtons += "		<label style='cursor:pointer;font-size:12px;margin-right:2px;' class='label label-primary'  onClick='AddEmptyRow(" + vOrderID + ")'>";
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
			    	curData[1] = tdOrderItemID;
			    	curData[2] = tdOrderID;
			    	curData[3] = tdProduct;
			    	curData[4] = tdQty;
			    	curData[5] = tdSOH;
			    	curData[6] = tdRV;
			    	curData[7] = tdUnitMeasure;
			    	curData[8] = tdPrice;
			    	curData[9] = tdSubTotal;
			    	curData[10] = tdButtons;

			    	tblProductList.row(rowIdx).data(curData).invalidate().draw();
			    }
			});

			if(!IsRecordExist){

		    	//New Row
				var rowNode = tblProductList.row.add([
						tdID,
						tdOrderItemID,
						tdOrderID,
						tdProduct, 
						tdQty,
						tdSOH,
						tdRV,
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

	    function AddEmptyRow(vOrderID){

	    	var tblProductList = $("#tblProductList").DataTable();

	    	intItemCount = intItemCount + 1;

	    	tdID = intItemCount ;
	    	tdOrderItemID = 0;
	    	tdOrderID = vOrderID;

			tdProduct = "<select id='Product-" + intItemCount  + "' class='form-control select2' onChange='SelectProduct("  +  intItemCount + ")' style='width: 100%; font-weight:normal;'>";
			tdProduct += "<option value=''>Please Select</option>";
				@foreach ($ProductList as $pkey)
					tdProduct += "<option value='{{ $pkey->ProductID }}' ";
					tdProduct += " data-brand='{{$pkey->Brand}}' ";
					tdProduct += " data-category='{{$pkey->Category}}' ";
					tdProduct += " data-measurement='{{$pkey->Measurement}}' ";
					tdProduct += " data-stockonhand='{{$pkey->StockOnHand}}' ";
					tdProduct += " data-centerprice='{{$pkey->CenterPrice}}' ";
					tdProduct += " data-retailprice='{{$pkey->RetailPrice}}' ";
					tdProduct += " data-distributorprice='{{$pkey->DistributorPrice}}' ";
					tdProduct += " data-rebatablevalue='{{$pkey->RebateValue}}' ";
					tdProduct += ">";
					tdProduct += "{{ $pkey->ProductCode.' - '.$pkey->ProductName }}</option>";
				@endforeach			
			tdProduct += "</select>";

			tdQty = "<input id='Qty-" + intItemCount  + "'  type='text' class='form-control DecimalOnly ChangeQty' value='' style='width:100%; text-align:right; font-weight:normal;'>";

			tdSOH = "<input id='SOH-" + intItemCount  + "'  type='text' class='form-control' value='' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdRV = "<input id='RebatableValue-" + intItemCount + "' type='text' class='form-control' style='width:100%; text-align:right; font-weight:normal;' value='' readonly>"

			tdUnitMeasure = "<input id='UnitMeasure-" + intItemCount  + "'  type='text' class='form-control' value='' style='width:100%; font-weight:normal;' readonly>";

			tdPrice = "<input id='Price-" + intItemCount + "' type='text' class='form-control' value='' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdSubTotal = "<input id='SubTotal-" + intItemCount + "' type='text' class='form-control' value='' style='width:100%; text-align:right; font-weight:normal;' readonly>";

			tdButtons = "<div class='col-md-12' style='margin-top:5px;'>";
			tdButtons +="	<div class='pull-right'>";
			tdButtons += "		<label style='cursor:pointer;font-size:12px;margin-right:2px;' class='label label-primary'  onClick='AddEmptyRow(" + vOrderID + ")'>";
			tdButtons +="			<i class='fa fa-plus'></i>";
			tdButtons +="		</label>";
			tdButtons +="		<label style='cursor:pointer;font-size:12px;' class='label label-danger' onClick='DeleteRow(" + intItemCount + ")'>";
			tdButtons +="			<i class='fa fa-trash'></i> ";
			tdButtons +="		</label>";
			tdButtons +="	</div>";
			tdButtons +="</div>";

			tblProductList.row.add([
				tdID,
				tdOrderItemID,
				tdOrderID,
				tdProduct,
				tdQty,
				tdSOH,
				tdRV,
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
	      		var RebatableValue = $("#Product-" + vIndex).find('option:selected').data('rebatablevalue');
	      		var SOH = $("#Product-" + vIndex).find('option:selected').data('stockonhand');

        		$("#SOH-" + vIndex).val(FormatDecimal(SOH,0));
        		$("#RebatableValue-" + vIndex).val(FormatDecimal(RebatableValue,2));
	        	$("#UnitMeasure-" + vIndex).val(Measurement);

	        	if(isVoucherUsed){
	        		$("#Price-" + vIndex).val(FormatDecimal(RetailPrice,2));
	        	}else{
		        	if($("#CustomerType").val() == "Member"){
		        		$("#Price-" + vIndex).val(FormatDecimal(DistributorPrice,2));
		        	}else{
		        		$("#Price-" + vIndex).val(FormatDecimal(RetailPrice,2));
		        	}
	        	}

	      	}
	    }

	    function ResetProductPrices(){

	    	for (var i = 1; i <= intItemCount; i++) {

	      		var DistributorPrice = $("#Product-" + i).find('option:selected').data('distributorprice');
	      		var RetailPrice = $("#Product-" + i).find('option:selected').data('retailprice');
	      		var RebatableValue = $("#Product-" + i).find('option:selected').data('rebatablevalue');

	        	if(isVoucherUsed){
	        		$("#Price-" + i).val(FormatDecimal(RetailPrice,2));
	        	}else{
		        	if($("#CustomerType").val() == "Member"){
		        		$("#Price-" + i).val(FormatDecimal(DistributorPrice,2));
		        	}else{
		        		$("#Price-" + i).val(FormatDecimal(RetailPrice,2));
		        	}
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

	    function getOrderVoucher(vOrderID, vIsEditable){

	    	if(vOrderID > 0){
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						OrderID : vOrderID
					},
					url: "{{ route('get-order-voucher-list') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						if(data.Response =='Success' && data.OrderVoucherList != undefined){
					        LoadVoucherRecordList(vOrderID, data.OrderVoucherList, vIsEditable);
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

	    function getVoucherRecordList(vEntryID){

			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					MemberEntryID: vEntryID,
					SearchText: '',
					PageNo: 0,
					Status: 'Available'
				},
				url: "{{ route('get-member-voucher-list') }}",
				dataType: "json",
				success: function(data){
		    		intVoucherCntr = 0;
			      	$("#tblVoucherList").DataTable().clear().draw();
					LoadVoucherRecordList($("#OrderID").val(), data.MemberVoucherList, true);
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

	    };

	    function LoadVoucherRecordList(vOrderID, vList, vIsEditable){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadVoucherRecordRow(vOrderID, vList[x], vIsEditable);
	    		}
	    	}

	    }

	    function LoadVoucherRecordRow(vOrderID, vData, vIsEditable){

	    	var tblVoucherList = $("#tblVoucherList").DataTable();

	    	intVoucherItemCount  = intVoucherItemCount  + 1;

	    	tdID = intVoucherItemCount;
	    	tdVoucherID = vData.VoucherID;
	    	tdOrderID = vOrderID;

	    	if(vIsEditable){
				tdCheckbox = "<span style='font-weight:normal;'><input id='chkVoucher" + intVoucherItemCount + "' class='RecomputeTotalVoucher' type='checkbox'></span>";
	    	}else{
				tdCheckbox = "<span style='font-weight:normal;'><input id='chkVoucher" + intVoucherItemCount + "' class='RecomputeTotalVoucher' type='checkbox' checked disabled></span>";
	    	}

			tdNthPair = "<span id='VoucherNthPair" + intVoucherItemCount + "' style='font-weight:normal;'> Pair No. " + vData.NthPair + "</span>";
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
			    	curData[2] = tdOrderID;

			    	curData[3] = tdCheckbox;

			    	curData[4] = tdNthPair;
			    	curData[5] = tdVoucherCode;
			    	curData[6] = tdVoucherAmount;

			    	tblVoucherList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblVoucherList.row.add([
						tdID,
						tdVoucherID,
						tdOrderID,
						tdCheckbox, 
						tdNthPair,
						tdVoucherCode,
						tdVoucherAmount
					]).draw();			
			}

	    }

	    function RecomputeTotalVoucher(){

	    	if(!isLoadingInfo){

		    	var tblVoucherList = $("#tblVoucherList").DataTable();
		    	var TotalVoucherPayment = 0;
		    	isVoucherUsed = false;

		    	//Check Vouchers
		    	for (var i = 1; i <= intVoucherItemCount; i++) {

	    		    if ($("#chkVoucher"+i).prop("checked")){
	    		    	
				    	isVoucherUsed = true;
	    		    	var VoucherAmount = $("#VoucherAmount"+i).text();
	    		    	VoucherAmount = VoucherAmount.replace(",", "");

			    		TotalVoucherPayment = parseFloat(TotalVoucherPayment) + parseFloat(VoucherAmount);
					}
		    	}

				$('#TotalVoucherPayment').val(FormatDecimal(TotalVoucherPayment,2));
				ResetProductPrices();
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

	    	var ShippingCharges = 0;
    		if($('#ShippingCharges').length){
    			if($("#ShippingCharges").val() != ""){
		            var strShippingCharges = $("#ShippingCharges").val();
		            ShippingCharges = parseFloat(strShippingCharges.replace(",",""));
    			}
			}	

	    	var TotalDiscount = 0;
    		if($('#TotalDiscount').length){
    			if($("#TotalDiscount").val() != ""){
		            var strTotalDiscount = $("#TotalDiscount").val();
		            TotalDiscount = parseFloat(strTotalDiscount.replace(",",""));
    			}
			}	

	    	var TotalAmountDue = TotalGrossAmount + ShippingCharges - TotalDiscount;
			$('#TotalAmountDue').val(FormatDecimal(TotalAmountDue,2));

	    	var TotalVoucherPayment = 0;
    		if($('#TotalVoucherPayment').length){
    			if($("#TotalVoucherPayment").val() != ""){
		            var strTotalVoucherPayment = $("#TotalVoucherPayment").val();
		            TotalVoucherPayment = parseFloat(strTotalVoucherPayment.replace(",",""));
    			}
			}

	    	var TotalCashPayment = 0;
    		if($('#TotalCashPayment').length){
    			if($("#TotalCashPayment").val() != ""){
		            var strTotalCashPayment = $("#TotalCashPayment").val();
		            TotalCashPayment = parseFloat(strTotalCashPayment.replace(",",""));
    			}
			}

	    	var AmountChange = TotalCashPayment + TotalVoucherPayment - TotalAmountDue;
			$('#AmountChange').val(FormatDecimal(AmountChange,2));

	    }	

	    function SaveRecord(){

	    	var TotalRebatableValue = 0;
	    	var TotalGrossAmount = 0;
	    	for (var i = 1; i <= intItemCount; i++) {
		    	var SubTotal = 0;
	    		if($('#SubTotal-' + i).length){
	    			if($("#SubTotal-" + i).val() != ""){
			            SubTotal = $("#SubTotal-" + i).val();
	    			}
				}
				TotalGrossAmount = TotalGrossAmount + parseFloat(SubTotal);

		    	var Qty = 0;
	    		if($('#Qty-' + i).length){
	    			if($("#Qty-" + i).val() != ""){
			            Qty = $("#Qty-" + i).val();
	    			}
				}
		    	var RebatableValue = 0;
	    		if($('#RebatableValue-' + i).length){
	    			if($("#RebatableValue-" + i).val() != ""){
			            RebatableValue = $("#RebatableValue-" + i).val();
	    			}
				}

				TotalRebatableValue = TotalRebatableValue + (parseFloat(Qty) * parseFloat(RebatableValue));
			}

	    	var ShippingCharges = 0;
    		if($('#ShippingCharges').length){
    			if($("#ShippingCharges").val() != ""){
		            var strShippingCharges = $("#ShippingCharges").val();
		            ShippingCharges = parseFloat(strShippingCharges.replace(",",""));
    			}
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

	    	var TotalAmountDue = TotalGrossAmount - TotalDiscount;

	    	var TotalVoucherPayment = 0;
    		if($('#TotalVoucherPayment').length){
    			if($("#TotalVoucherPayment").val() != ""){
		            var strTotalVoucherPayment = $("#TotalVoucherPayment").val();
		            TotalVoucherPayment = parseFloat(strTotalVoucherPayment.replace(",",""));
    			}
			}

	    	var TotalCashPayment = 0;
	    	var AmountChange = 0;
	    	if($('#ModeOfPayment').val() == "Cash"){
	    		if($('#TotalCashPayment').length){
	    			if($("#TotalCashPayment").val() != ""){
			            var strTotalCashPayment = $("#TotalCashPayment").val();
			            TotalCashPayment = parseFloat(strTotalCashPayment.replace(",",""));
	    			}
				}

		    	AmountChange = TotalCashPayment + TotalVoucherPayment - TotalAmountDue;

	    	}

			if($('#CustomerType').val() == "") {
				showJSMessage("Customer Type","Please select customer type.","OK");
			}else if($('#CustomerType').val() == "Member" && ($('#CustomerEntryID').val() == '' || $('#CustomerEntryID').val() == '0') ) {
				showJSMessage("Customer","Please select customer.","OK");
			}else if($('#CustomerType').val() == "Guest" && $('#CustomerName').val() == '') {
				showJSMessage("Customer","Please enter customer name.","OK");

			}else if($('#MobileNo').val() == "") {
				showJSMessage("Mobile No.","Please enter mobile number.","OK");
			}else if($('#EmailAddress').val() == "") {
				showJSMessage("Email Address","Please enter email address.","OK");


			}else if($('#Address').val() == "") {
				showJSMessage("Address","Please enter shipping/billing address.","OK");
			}else if($('#City').val() == "") {
				showJSMessage("Address","Please select shipping/billing city address.","OK");
			}else if($('#StateProvince').val() == "") {
				showJSMessage("Address","Please enter shipping/billing state/provincial address.","OK");
			}else if($('#Country').val() == "") {
				showJSMessage("Address","Please select shipping/billing country address.","OK");

			}else if($('#ModeOfPayment').val() == "Cash" && TotalAmountDue > (TotalCashPayment + TotalVoucherPayment)) {
				showJSMessage("Amount Paid","Please pay total amount due.","OK");
			}else{

		    	var tblVoucherList = $("#tblVoucherList").DataTable();
	            var pVoucherData = [];
	            var intVoucherCntr = 0;

		    	//Check Vouchers
		    	for (var i = 1; i <= intVoucherItemCount; i++) {

		    		var VoucherID = 0;
		    		var OrderID = 0;

	    		    if ($("#chkVoucher"+i).prop("checked")){

						tblVoucherList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
						    var rowVoucherData = this.data();

						    if(rowVoucherData[0] == i){
						    	VoucherID = rowVoucherData[1]
						    	OrderID = rowVoucherData[2]
						    }
						});

						pVoucherData[intVoucherCntr] = {
							VoucherID:VoucherID,
							OrderID:OrderID
						};

						intVoucherCntr = intVoucherCntr + 1;

					}
		    	}


		    	var tblProductList = $("#tblProductList").DataTable();
	            var pData = [];
	            var intCntr = 0;

		    	//Check Product Fields
		    	for (var i = 1; i <= intItemCount; i++) {
		    		var OrderItemID = 0;
		    		var OrderID = 0;
		    		var ProductID = 0;
		    		var UnitMeasure = "";
		    		var Qty = 0;
		    		var Price = 0;
		    		var SubTotal = 0;
		    		var RebatableValue = 0;
			    	var blnIsIncomplete = false;

					tblProductList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
					    var rowData = this.data();

					    if(rowData[0] == i){
					    	OrderItemID = rowData[1]
					    	OrderID = rowData[2]
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

		    		if($('#RebatableValue-' + i).length){
		    			if($("#RebatableValue-" + i).val() != ""){
				            RebatableValue = $("#RebatableValue-" + i).val();
		    			}else{
				    		blnIsIncomplete = true;
		    			}
		    		}else{
			    		blnIsIncomplete = true;
	    			}

		    		if(!blnIsIncomplete){

						pData[intCntr] = {
							OrderItemID:OrderItemID,
							OrderID:OrderID,
							ProductID:ProductID,
							Qty:Qty,
							UnitMeasure:UnitMeasure,
							Price:Price,
							SubTotal:SubTotal,
							RebatableValue:RebatableValue
						};

						intCntr = intCntr + 1;
		    		}
		    	}

				if(pData.length <= 0){
					showJSModalMessageJS("Save Order","Please enter order items.","OK");
				}else{

					$.ajax({
						type: "post",
						data: {
							_token: '{{ $Token }}',
							CenterID: $("#CenterID").val(),
							OrderID: $("#OrderID").val(),
							OrderNo: $("#OrderNo").val(),
							OrderDateTime: $("#OrderDateTime").val(),
							Status: '{{ config('app.STATUS_APPROVED') }}',

							CustomerType: $("#CustomerType").val(),
							CustomerEntryID: $("#CustomerEntryID").val(),
							CustomerName: $("#CustomerName").val(),
							EmailAddress: $("#EmailAddress").val(),
							MobileNo: $("#MobileNo").val(),

							Address: $("#Address").val(),
							City: $("#City").val(),
							StateProvince: $("#StateProvince").val(),
							ZipCode: $("#ZipCode").val(),
							Country: $("#Country").val(),

							GrossAmount: TotalGrossAmount,
							ShippingCharges: ShippingCharges,
							TotalDiscountPercent: TotalDiscountPercent,
							TotalDiscount: TotalDiscount,
							TotalAmountDue: TotalAmountDue,
							TotalVoucherPayment: TotalVoucherPayment,
							TotalEWalletPayment: 0,
							TotalCashPayment: TotalCashPayment,
							AmountChange: AmountChange,
							TotalRebatableValue: TotalRebatableValue,

							ModeOfPayment: $("#ModeOfPayment").val(),
							Remarks: $("#Remarks").val(),
							VoucherData : pVoucherData,
							OrderItems: pData,
							OrderItemsDeleted: pDeletedItem
						},
						url: "{{ route('do-save-order') }}",
						dataType: "json",
						success: function(data){

					        $("#divLoader").hide();
							buttonOneClick("btnSave", "Save", false);
							if(data.Response =='Success'){
								$("#record-info-modal").modal('hide');
								showMessage("Success",data.ResponseMessage);
								LoadRecordRow(data.OrderInfo);
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

	    function SetAsVerified(vOrderID){

	    	if(vOrderID > 0){

				$("#VerifyOrderID").val(vOrderID);
				$("#set-verified-modal").modal();

	    	}

	    }

	    function ProceedVerifyOrder(){

			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					OrderID: $("#VerifyOrderID").val()
				},
				url: "{{ route('do-verify-order') }}",
				dataType: "json",
				success: function(data){
					buttonOneClick("btnVerifyOrder", "Proceed", false);

					if(data.Response =='Success'){
						$("#set-verified-modal").modal('hide');

						//Remove Row
				    	var tblList = $("#tblList").DataTable();
						tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
						    var rowData = this.data();

						    if(rowData[0] == data.OrderID){
						    	tblList.row(rowIdx).remove().draw();
						    }

						});

						showMessage("Success",data.ResponseMessage);

					}else{
						showJSModalMessageJS("Verify Order",data.ResponseMessage,"OK");
					}
				},
				error: function(data){
					buttonOneClick("btnVerifyOrder", "Proceed", false);
					console.log(data.responseText);
				},
				beforeSend:function(vData){
					buttonOneClick("btnVerifyOrder", "", true);
				}
        	});

	    };

	    //autocomplete script
	    $(document).on('focus','.autocomplete_txt',function(){

	        $(this).autocomplete({

	            source: function( request, response ) {

	                if(request.term.length >= 3){
	                    $.ajax({
	                        url: "{{ route('get-member-search-list') }}",
	                        dataType: "json",
	                        method: 'get',
							data: {
                             	SearchText: request.term,
								PageNo : 1,
								Status : "{{ config('app.STATUS_ACTIVE') }}",
								IsWithEwallet : 0
							},
	                        success: function( data ) {
	                            response( $.map( data, function( item ) {
	                                var code = item.split("|");
	                                console.log(code);
	                                return {
	                                    label: code[1] + " - " + code[2],
	                                    value: code[1] + " - " + code[2],
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

	                $('#CustomerEntryID').val(data[0]);
        			$("#MobileNo").val(data[4]);
					$("#EmailAddress").val(data[5]);

					$("#Address").val(data[8]);
					$("#City").val(data[9]).change();
					$("#StateProvince").val(data[11]);
					$("#ZipCode").val(data[12]);
					$("#Country").val(data[13]).change()

	                getVoucherRecordList(data[0]);

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



