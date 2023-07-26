@extends('layout.adminweb')

@section('content')

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			E-Wallet Withdrawal
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li class="active">E-Wallet Withdrawal</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-success">
		            <div class="box-header">
				        <div class="col-md-12" style="padding: 2px;">
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
				                  <th>Withdrawal No.</th>
				                  <th>Withdrawal DateTime</th>
				                  <th>Withdraw By</th>
				                  <th>Mobile No.</th>
				                  <th>Withdrawal Option</th>
				                  <th style="text-align: right;">Requested Amount</th>
				                  <th style="text-align: right;">Approved Amount</th>
								  <th style="text-align: right;">Processing Fee</th>
				                  <th style="text-align: right;">Net Amount To Receive</th>
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
		            <h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Withdrawal Information</b></span></h4>
	          	</div>
	          	<div class="modal-body">
	        		<input type="hidden" id="WithdrawalID" value="0" readonly>
		            <div class="row">
		            	<div class="col-md-12">
			            	<label class="col-md-12" style="font-size: 15px;"><i class='fa fa-shopping-cart font-size:15px;'></i>&nbsp&nbsp<b>Withdrawal Information</b></label>
			        	</div>
		        	</div>
		            <div class="row">
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Withdrawal No.</label>
			                <div class="col-md-12">
			                    <input id="WithdrawalNo" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight:bold; color:green;" readonly>
			                </div>
		            	</div>
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Date/Time<span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="WithdrawalDateTime" type="text" class="form-control" value=""  placeholder="Auto Generated" style="width:100%; font-weight:normal;" readonly>
			                </div>
		            	</div>

		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
			                <div class="col-md-12">
								<select id="Status" class="form-control select2" style="width: 100%; font-weight: normal;">
									<option value="{{ config('app.STATUS_APPROVED') }}" selected>{{ config('app.STATUS_APPROVED') }}</option>
									<option value="{{ config('app.STATUS_FOR_APPROVAL') }}">{{ config('app.STATUS_FOR_APPROVAL') }}</option>
									<option value="{{ config('app.STATUS_PENDING') }}">{{ config('app.STATUS_PENDING') }}</option>
									<option value="{{ config('app.STATUS_CANCELLED') }}">{{ config('app.STATUS_CANCELLED') }}</option>
								</select>
			                </div>
		            	</div>
		            </div>
		            <div style="clear:both;"></div>
		            <br>                
		            <div class="row">
		            	<div class="col-md-12">
			            	<label class="col-md-12" style="font-size: 15px;"><i class='fa fa-info font-size:15px;'></i>&nbsp&nbsp<b>Member Information</b></label>
			        	</div>
		        	</div>
		            <div style="clear:both;"></div>
		            <br>                
		            <div class="row">
		            	<div class="col-md-12">
			                <label class="col-md-12" style="font-weight: normal;">Member <span style="color:red;">*</span></label>
			                <div class="col-md-12">
	                            <input type="hidden" id="WithdrawByMemberID" name="WithdrawByMemberID" readonly>
	                            <input type="text" data-type="Member" id="WithdrawByMember" name="WithdrawByMember" placeholder="Search by Member No., Member Name" class="form-control autocomplete_txt" autocomplete="off">
			                </div>
		            	</div>
		            </div>

		            <div style="clear:both;"></div>
		            <br>                
		            <div class="row">
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Mobile No. <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="WithdrawByMemberMobileNo" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" readonly>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Email Address <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="WithdrawByMemberEmailAddress" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" readonly>
			                </div>
		            	</div>
		            </div>
		            <div class="row">
		            	<div class="col-md-12">
			            	<label class="col-md-12" style="font-size: 15px;"><i class='fa fa-info font-size:15px;'></i>&nbsp&nbsp<b>Withdrawal Information</b></label>
			        	</div>
		        	</div>
		            <div style="clear:both;"></div>
		            <br> 
		            <div class="row">
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Withdrawal Options <span style="color:red;">*</span></label>
			                <div class="col-md-12">
								<select id="WithdrawalOption" class="form-control select2" style="width: 100%; font-weight: normal;">
									<option value="Check" selected>Check</option>
									<option value="Bank Transfer">Bank Transfer</option>
									<option value="Cebuana Lhuillier">Cebuana Lhuillier</option>
									<option value="MLhuillier">MLhuillier</option>
									<option value="Palawan Pera Padala">Palawan Pera Padala</option>
									<option value="Western Union">Western Union</option>
									<option value="RD Pawnshop">RD Pawnshop</option>
								</select>
			                </div>
		            	</div>
		            </div>
		            <div style="clear:both;"></div>
		            <br> 
		            <div id="divCheckInfo" class="row">
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Check No. <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="CheckNo" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Check Date <span style="color:red;">*</span></label>
                            <div class='input-group date' id='divCheckDate'>
                                <input id="CheckDate" name="CheckDate" type='text' class="form-control" readonly />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Check Amount <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="CheckAmount" type="text" class="form-control" value="" style="width:100%; font-weight:normal; text-align: right;" readonly>
			                </div>
		            	</div>
		            </div>
		            <div id="divBankTransferInfo" class="row" style="display: none;">
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Bank <span style="color:red;">*</span></label>
			                <div class="col-md-12">
								<select id="Bank" class="form-control select2" style="width: 100%; font-weight: normal;">
									<option value="BDO" selected>Banco De Oro (BDO)</option>
									<option value="BPI">Bank of the Philippine Islands (BPI)</option>
									<option value="EastWest Bank">EastWest Bank</option>
									<option value="Land Bank">Land Bank</option>
									<option value="Metro Bank">Metro Bank</option>
									<option value="PNB">Philippine National Bank (PNB)</option>
									<option value="Union Bank">Union Bank</option>
								</select>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Account Name <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="BankAccountName" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Account No. <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="BankAccountNo" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            </div>
		            <div id="divSendThroughInfo" class="row" style="display: none;">
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">First Name <span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="SendToFirstName" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Last Name<span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="SendToLastName" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Middle Name<span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="SendToMiddleName" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>

		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Tel. No.</label>
			                <div class="col-md-12">
			                    <input id="SendToTelNo" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Mobile No.<span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="SendToMobileNo" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Email Address<span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="SendToEmailAddress" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-8">
			                <label class="col-md-12" style="font-weight: normal;">Sender Name<span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="SenderName" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Control No./Reference No.<span style="color:red;">*</span></label>
			                <div class="col-md-12">
			                    <input id="SendingRefNo" type="text" class="form-control" value="" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            </div>
		            <div style="clear:both;"></div>
		            <br>                
	            	<div class="row">
		            	<div class="col-md-6">
				            <div class="row">
				            	<div class="col-md-12">
					                <label class="col-md-12" style="font-weight: normal;">Notes</label>
					                <div class="col-md-12">
					                    <input id="Notes" type="text" class="form-control" value="" style="width:100%; font-weight:normal;">
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
											<a href="#" class="btn btn-warning btn-flat" style="width: 150px; text-align: right;">E-Wallet Balance</a>
										</span>
										<input id="EWalletBalance" type="text" placeholder="E-Wallet Balance" class="form-control" style="width:100%; font-weight:normal; text-align: right;" readonly>
									</div>		
								</div>		
							</div>	
				            <div class="row">
				            	<div class="col-md-12">
									<div class="input-group margin pull-right">
										<span class="input-group-btn">
											<a href="#" class="btn btn-primary btn-flat" style="width: 150px; text-align: right;">Requested Amount</a>
										</span>
										<input id="RequestedAmount" type="text" placeholder="Requested Amount" class="form-control DecimalOnly RecomputeTotal" style="width:100%; font-weight:normal; text-align: right;" required>
									</div>		
								</div>		
							</div>	
				            <div class="row">
				            	<div class="col-md-12">
									<div class="input-group margin pull-right">
										<span class="input-group-btn">
											<a href="#" class="btn btn-primary btn-flat" style="width: 150px; text-align: right;">Approved Amount</a>
										</span>
										<input id="ApprovedAmount" type="text" placeholder="Approved Amount" class="form-control DecimalOnly RecomputeTotal" style="width:100%; font-weight:normal; text-align: right;">
									</div>		
								</div>		
							</div>	
				            <div class="row">
				            	<div class="col-md-12">
									<div class="input-group margin pull-right">
										<span class="input-group-btn">
											<a href="#" class="btn btn-primary btn-flat" style="width: 150px; text-align: right;">Processing Fee</a>
										</span>
										<input id="ProcessingFee" type="text" placeholder="Processing Fee" class="form-control DecimalOnly RecomputeTotal" style="width:100%; font-weight:normal; text-align: right;" required>
									</div>		
								</div>	
							</div>	
				            <div class="row">
				            	<div class="col-md-12">
									<div class="input-group margin pull-right">
										<span class="input-group-btn">
											<a href="#" class="btn btn-success btn-flat" style="width: 150px; text-align: right;">Net Amount</a>
										</span>
										<input id="NetAmountToReceive" type="text" placeholder="Net Amount" class="form-control" style="width:100%; font-weight:normal; text-align: right;" readonly>
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
	            	<h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Cancel Withdrawal</b></span></h4>
	          	</div>

          		<div class="modal-body">
        			<input type="hidden" id="CancelledWithdrawalID" value="0" readonly>
		            <div class="row">
		            	<div class="col-md-12">
			                <label class="col-md-12" style="font-weight: normal;">Are you sure you want to cancel this withdrawal?</label>
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

	    var isNewRecord = 0;
	    var intCurrentPage = 1;

	 	var intItemCount = 0;
	 	var intDeletedItem = 0;
	 	var pDeletedItem = [];

		var isPageFirstLoad = true;

	    $(document).ready(function() {

            $('#divCheckDate').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

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

	        //Load Initial Data
	        getRecordList(intCurrentPage, '');
		 	isPageFirstLoad = false;

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
					MemberID: 0,
					SearchText: vSearchText,
					PageNo: vPageNo
				},
				url: "{{ route('get-ewallet-withdrawal-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.EWalletWithdrawalList);
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

	    	tdID = vData.WithdrawalID;
	    	tdSortOption = vData.SortOption;
			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> ";
		                  	tdOption += " <li style='text-align:left;'> " +
		                      	" <a href='#' onclick='EditRecord(" + vData.WithdrawalID + "," + (vData.Status != '{{ config('app.STATUS_CANCELLED') }}' && vData.Status != '{{ config('app.STATUS_APPROVED') }}' ? true : false) + ")'>" + 
		                      		" <strong><i class='fa fa-info-circle font-size:15px;'></i> " + (vData.Status != '{{ config('app.STATUS_CANCELLED') }}' && vData.Status != '{{ config('app.STATUS_APPROVED') }}' ? "Edit Withdrawal" : "View Withdrawal") + "</strong>" +
		                      	" </a> " +
		                   	" </li> ";
            tdOption += " </ul> " +
	                    " </div> " ;

			tdWithdrawalNo = "<span style='font-weight:bold;'>" + vData.WithdrawalNo + "</span>";
			tdWithdrawalDateTime = "<span style='font-weight:normal;'>" + vData.WithdrawalDateTime + "</span>";
			tdWithdrawBy = "<span style='font-weight:normal;'>" + vData.WithdrawByMemberEntryCode + " - " + vData.WithdrawBy + "</span>";

			tdMobileNo = "<span style='font-weight:normal;'>" + vData.WithdrawByMemberMobileNo + "</span>";


			tdWithdrawalOption = "";
			if(vData.WithdrawalOption == "Bank Transfer"){
				tdWithdrawalOption = "<span style='font-weight:normal;'>" + vData.WithdrawalOption + " - " + vData.Bank + "</span>";
			}else{
				tdWithdrawalOption = "<span style='font-weight:normal;'>" + vData.WithdrawalOption + "</span>";
			}

			tdRequestedAmount = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.RequestedAmount,2) + "</span>";
			tdApprovedAmount = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.ApprovedAmount,2) + "</span>";
			tdProcessingFee = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.ProcessingFee,2) + "</span>";
			tdNetAmountToReceive = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.NetAmountToReceive,2) + "</span>";

			tdStatus = "";
			if(vData.Status == "Pending"){
				tdStatus += "<span class='label label-warning' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}else if(vData.Status == "{{ config('app.STATUS_FOR_APPROVAL') }}"){
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#f308e0;'>" + vData.Status + "</span>";
			}else if(vData.Status == "{{ config('app.STATUS_CANCELLED') }}"){
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.Status + "</span>";
			}else{
				tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

			    var rowData = this.data();
			    if(rowData[0] == vData.WithdrawalID){
					IsRecordExist = true;

			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdSortOption;
			    	curData[2] = tdOption;
			    	curData[3] = tdWithdrawalNo;
			    	curData[4] = tdWithdrawalDateTime;
			    	curData[5] = tdWithdrawBy;
			    	curData[6] = tdMobileNo;
			    	curData[7] = tdWithdrawalOption;
			    	curData[8] = tdRequestedAmount;
			    	curData[9] = tdApprovedAmount;
			    	curData[10] = tdProcessingFee;
			    	curData[11] = tdNetAmountToReceive;
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
						tdWithdrawalNo,
						tdWithdrawalDateTime, 
						tdWithdrawBy, 
						tdMobileNo,
						tdWithdrawalOption,
						tdRequestedAmount,
						tdApprovedAmount,
						tdProcessingFee,
						tdNetAmountToReceive,
						tdStatus
					]).draw().node();			
			}

	    }

	    function Clearfields(){

			$("#WithdrawalID").val('0');
			$("#WithdrawalNo").val('');
			$("#WithdrawalDateTime").val('');
			$("#Status").val('');

			$("#WithdrawByMemberID").val('0');
			$("#WithdrawByMember").val('');
			$("#WithdrawByMemberMobileNo").val('');
			$("#WithdrawByMemberEmailAddress").val('');

			$("#WithdrawalOption").val('Check').change();

			$("#CheckNo").val('');
			$("#CheckDate").val('');
			$("#CheckAmount").val('0.00');

			$("#Bank").val('BPI').change();
			$("#BankAccountName").val('');
			$("#BankAccountNo").val('');

			$("#SendToFirstName").val('');
			$("#SendToLastName").val('');
			$("#SendToMiddleName").val('');
			$("#SendToTelNo").val('');
			$("#SendToMobileNo").val('');
			$("#SendToEmailAddress").val('');
			$("#SenderName").val('');
			$("#SendingRefNo").val('');

			$("#Notes").val('');
			$("#PreparedBy").val('{{ Session('ADMIN_FULLNAME') }}');

			$("#EWalletBalance").val("0.00");
			$("#RequestedAmount").val("0.00");
			$("#ApprovedAmount").val("0.00");
			$("#ProcessingFee").val('0.00');
			$("#NetAmountToReceive").val('0.00');

			$("#divMember").show();
			$("#divGuest").hide();

			$("#Status").prop('disabled', false);
			$("#WithdrawByMember").prop('disabled', false);
			$("#WithdrawalOption").prop('disabled', false);

			$("#CheckNo").prop('disabled', false);
			$("#CheckDate").prop('disabled', false);
			
			$("#Bank").prop('disabled', false);
			$("#BankAccountName").prop('disabled', false);
			$("#BankAccountNo").prop('disabled', false);

			$("#SendToFirstName").prop('disabled', false);
			$("#SendToLastName").prop('disabled', false);
			$("#SendToMiddleName").prop('disabled', false);
			$("#SendToTelNo").prop('disabled', false);
			$("#SendToMobileNo").prop('disabled', false);
			$("#SendToEmailAddress").prop('disabled', false);
			$("#SenderName").prop('disabled', false);
			$("#SendingRefNo").prop('disabled', false);

			$("#RequestedAmount").prop('disabled', false);
			$("#ApprovedAmount").prop('disabled', false);
			$("#ProcessingFee").prop('disabled', false);

			$("#Notes").prop('disabled', false);

			$("#divCheckInfo").show();
	        $("#divBankTransferInfo").hide();
	        $("#divSendThroughInfo").hide();

			$("#btnSave").show();

	    }

	    function NewRecord(){
	    	isNewRecord = 1;
			Clearfields();
			$("#record-info-modal").modal();
	    }

	    function EditRecord(vRecordID, vIsEditable){

	    	if(vRecordID > 0){
	    		isNewRecord = 0;
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						WithdrawalID: vRecordID
					},
					url: "{{ route('get-ewallet-withdrawal-info') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnSave", "Save", false);

						if(data.Response =='Success' && data.EWalletWithdrawalInfo != undefined){
							
							Clearfields();
							
							$("#WithdrawalID").val(data.EWalletWithdrawalInfo.WithdrawalID);
							$("#WithdrawalNo").val(data.EWalletWithdrawalInfo.WithdrawalNo);
							$("#WithdrawalDateTime").val(data.EWalletWithdrawalInfo.WithdrawalDateTime);
							$("#Status").val(data.EWalletWithdrawalInfo.Status).change();

							$("#WithdrawByMemberID").val(data.EWalletWithdrawalInfo.WithdrawByMemberID);
							$("#WithdrawByMember").val(data.EWalletWithdrawalInfo.WithdrawBy);
							$("#WithdrawByMemberMobileNo").val(data.EWalletWithdrawalInfo.WithdrawByMemberMobileNo);
							$("#WithdrawByMemberEmailAddress").val(data.EWalletWithdrawalInfo.WithdrawByMemberEmailAddress);

							$("#WithdrawalOption").val(data.EWalletWithdrawalInfo.WithdrawalOption).change();

							$("#divCheckInfo").hide();
							$("#divBankTransferInfo").hide();
							$("#divSendThroughInfo").hide();
							if(data.EWalletWithdrawalInfo.WithdrawalOption == "Check"){
								$("#divCheckInfo").show();
								$("#CheckNo").val(data.EWalletWithdrawalInfo.CheckNo);
								$("#CheckDate").val(data.EWalletWithdrawalInfo.CheckDate);
								$("#CheckAmount").val(FormatDecimal(data.EWalletWithdrawalInfo.CheckAmount,2));
							}else if(data.EWalletWithdrawalInfo.WithdrawalOption == "Bank Transfer"){
								$("#divBankTransferInfo").show();
								$("#Bank").val(data.EWalletWithdrawalInfo.Bank).change();
								$("#BankAccountName").val(data.EWalletWithdrawalInfo.BankAccountName);
								$("#BankAccountNo").val(data.EWalletWithdrawalInfo.BankAccountNo);
							}else{
								$("#divSendThroughInfo").show();
								$("#SendToFirstName").val(data.EWalletWithdrawalInfo.SendToFirstName);
								$("#SendToLastName").val(data.EWalletWithdrawalInfo.SendToLastName);
								$("#SendToMiddleName").val(data.EWalletWithdrawalInfo.SendToMiddleName);
								$("#SendToTelNo").val(data.EWalletWithdrawalInfo.SendToTelNo);
								$("#SendToMobileNo").val(data.EWalletWithdrawalInfo.SendToMobileNo);
								$("#SendToEmailAddress").val(data.EWalletWithdrawalInfo.SendToEmailAddress);
								$("#SenderName").val(data.EWalletWithdrawalInfo.SenderName);
								$("#SendingRefNo").val(data.EWalletWithdrawalInfo.SendingRefNo);
							}

							$("#Notes").val(data.EWalletWithdrawalInfo.Notes);
							$("#PreparedBy").val(data.EWalletWithdrawalInfo.ApprovedBy);

							if(data.EWalletWithdrawalInfo.Status == "{{ config('app.STATUS_APPROVED') }}" || data.EWalletWithdrawalInfo.Status == "{{ config('app.STATUS_FOR_APPROVAL') }}" || data.EWalletWithdrawalInfo.Status == "{{ config('app.STATUS_PENDING') }}"){
								$("#EWalletBalance").val(FormatDecimal(data.EWalletWithdrawalInfo.EWalletBalance,2));
							}else{
								$("#EWalletBalance").val(FormatDecimal(data.EWalletWithdrawalInfo.CurrentEWalletBalance,2));
							}

							$("#RequestedAmount").val(FormatDecimal(data.EWalletWithdrawalInfo.RequestedAmount,2));
							$("#ApprovedAmount").val(FormatDecimal(data.EWalletWithdrawalInfo.ApprovedAmount,2));
							$("#ProcessingFee").val(FormatDecimal(data.EWalletWithdrawalInfo.ProcessingFee,2));
							$("#NetAmountToReceive").val(FormatDecimal(data.EWalletWithdrawalInfo.NetAmountToReceive,2));

							$("#Status").prop('disabled', !vIsEditable);
							$("#WithdrawByMember").prop('disabled', !vIsEditable);
							$("#WithdrawalOption").prop('disabled', !vIsEditable);

							$("#CheckNo").prop('disabled', !vIsEditable);
							$("#CheckDate").prop('disabled', !vIsEditable);										
							$("#Bank").prop('disabled', !vIsEditable);
							$("#BankAccountName").prop('disabled', !vIsEditable);
							$("#BankAccountNo").prop('disabled', !vIsEditable);

							$("#SendToFirstName").prop('disabled', !vIsEditable);
							$("#SendToLastName").prop('disabled', !vIsEditable);
							$("#SendToMiddleName").prop('disabled', !vIsEditable);
							$("#SendToTelNo").prop('disabled', !vIsEditable);
							$("#SendToMobileNo").prop('disabled', !vIsEditable);
							$("#SendToEmailAddress").prop('disabled', !vIsEditable);
							$("#SenderName").prop('disabled', !vIsEditable);
							$("#SendingRefNo").prop('disabled', !vIsEditable);

							$("#RequestedAmount").prop('disabled', !vIsEditable);
							$("#ApprovedAmount").prop('disabled', !vIsEditable);
							$("#ProcessingFee").prop('disabled', !vIsEditable);

							$("#Notes").prop('disabled', !vIsEditable);

							if(vIsEditable){
								$("#btnSave").show();
							}else{
								$("#btnSave").hide();
							}

							$("#record-info-modal").modal();

						}else{
							showJSModalMessageJS("Withdrawal Information",data.ResponseMessage,"OK");
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

	    $("#WithdrawalOption").change(function(){
        	$("#divCheckInfo").hide();
        	$("#divBankTransferInfo").hide();
        	$("#divSendThroughInfo").hide();
	      	if($("#WithdrawalOption").val() == "Check"){
	        	$("#divCheckInfo").show();
	      	}else if($("#WithdrawalOption").val() == "Bank Transfer"){
	        	$("#divBankTransferInfo").show();
	      	}else{
	        	$("#divSendThroughInfo").show();
	      	}
	    });

		$(document).on('change keyup blur','.RecomputeTotal',function(){
			RecomputeTotal();
		});

	    function RecomputeTotal(){

	    	var RequestedAmount = 0;
    		if($('#RequestedAmount').length){
    			if($("#RequestedAmount").val() != ""){
		            var strRequestedAmount = $("#RequestedAmount").val();
		            RequestedAmount = parseFloat(strRequestedAmount.replace(",",""));
    			}
			}	

	    	var ApprovedAmount = 0;
    		if($('#ApprovedAmount').length){
    			if($("#ApprovedAmount").val() != ""){
		            var strApprovedAmount = $("#ApprovedAmount").val();
		            ApprovedAmount = parseFloat(strApprovedAmount.replace(",",""));
    			}
			}	

	    	var ProcessingFee = 0;
    		if($('#ProcessingFee').length){
    			if($("#ProcessingFee").val() != ""){
		            var strProcessingFee = $("#ProcessingFee").val();
		            ProcessingFee = parseFloat(strProcessingFee.replace(",",""));
    			}
			}	

	    	var NetAmountToReceive = ApprovedAmount - ProcessingFee;
			$('#NetAmountToReceive').val(FormatDecimal(NetAmountToReceive,2));

	    	var CheckAmount = 0;
	    	if($("#WithdrawalOption").val() == "Check"){
		    	CheckAmount = NetAmountToReceive;
	    	}
			$('#CheckAmount').val(FormatDecimal(CheckAmount,2));
	    }	

	    function SaveRecord(){

	    	var EWalletBalance = 0;
    		if($('#EWalletBalance').length){
    			if($("#EWalletBalance").val() != ""){
		            var strEWalletBalance = $("#EWalletBalance").val();
		            EWalletBalance = parseFloat(strEWalletBalance.replace(",",""));
    			}
			}	

	    	var RequestedAmount = 0;
    		if($('#RequestedAmount').length){
    			if($("#RequestedAmount").val() != ""){
		            var strRequestedAmount = $("#RequestedAmount").val();
		            RequestedAmount = parseFloat(strRequestedAmount.replace(",",""));
    			}
			}	

	    	var ApprovedAmount = 0;
    		if($('#ApprovedAmount').length){
    			if($("#ApprovedAmount").val() != ""){
		            var strApprovedAmount = $("#ApprovedAmount").val();
		            ApprovedAmount = parseFloat(strApprovedAmount.replace(",",""));
    			}
			}	

	    	var ProcessingFee = 0;
    		if($('#ProcessingFee').length){
    			if($("#ProcessingFee").val() != ""){
		            var strProcessingFee = $("#ProcessingFee").val();
		            ProcessingFee = parseFloat(strProcessingFee.replace(",",""));
    			}
			}	

	    	var NetAmountToReceive = ApprovedAmount - ProcessingFee;

	    	var CheckAmount = 0;
    		if($('#CheckAmount').length){
    			if($("#CheckAmount").val() != ""){
		            var strCheckAmount = $("#CheckAmount").val();
		            CheckAmount = parseFloat(strCheckAmount.replace(",",""));
    			}
			}

			if(RequestedAmount < {{ config('app.MinimumWithdrawalAmount') }}){
				showJSMessage("Requested Amount","Minimum withdrawal amount is Php {{ config('app.MinimumWithdrawalAmount') }}.","OK");
			}else if(EWalletBalance < RequestedAmount || EWalletBalance < ApprovedAmount) {
				showJSMessage("Requested Amount","Insufficient E-Wallet balance.","OK");
			}else if($('#Status').val() == "{{ config('app.STATUS_FOR_APPROVAL') }}" && ApprovedAmount <= 0) {
				showJSMessage("Approved Amount","Please enter approved amount.","OK");
			}else if($('#Status').val() == "{{ config('app.STATUS_APPROVED') }}" && ApprovedAmount <= 0) {
				showJSMessage("Approved Amount","Please enter approved amount.","OK");
			}else if(($('#Status').val() == "{{ config('app.STATUS_FOR_APPROVAL') }}" || $('#Status').val() == "{{ config('app.STATUS_APPROVED') }}") && EWalletBalance < ApprovedAmount) {
				showJSMessage("Requested Amount","Insufficient E-Wallet balance.","OK");
			}else if($('#WithdrawalOption').val() == "") {
				showJSMessage("Withdrawal Option","Please select withdrawal option.","OK");

			}else if($('#WithdrawalOption').val() == "Check" && ($('#Status').val() == "{{ config('app.STATUS_FOR_APPROVAL') }}" || $('#Status').val() == "{{ config('app.STATUS_APPROVED') }}") && $('#CheckNo').val() == "") {
				showJSMessage("Check","Please enter check number..","OK");
			}else if($('#WithdrawalOption').val() == "Check" && ($('#Status').val() == "{{ config('app.STATUS_FOR_APPROVAL') }}" || $('#Status').val() == "{{ config('app.STATUS_APPROVED') }}") && $('#CheckDate').val() == "") {
				showJSMessage("Check","Please set check date.","OK");
			}else if($('#WithdrawalOption').val() == "Check" && ($('#Status').val() == "{{ config('app.STATUS_FOR_APPROVAL') }}" || $('#Status').val() == "{{ config('app.STATUS_APPROVED') }}") && CheckAmount <= 0) {
				showJSMessage("Check","Please set approved amount.","OK");

			}else if($('#WithdrawalOption').val() == "Bank Transfer" && $('#Bank').val() == "") {
				showJSMessage("Bank","Please select a bank.","OK");
			}else if($('#WithdrawalOption').val() == "Bank Transfer" && $('#BankAccountName').val() == "") {
				showJSMessage("Bank","Please enter bank account name.","OK");
			}else if($('#WithdrawalOption').val() == "Bank Transfer" && $('#BankAccountNo').val() == "") {
				showJSMessage("Bank","Please enter banl account number.","OK");


			}else if(($('#WithdrawalOption').val() == "Palawan Pera Padala" ||
				  $('#WithdrawalOption').val() == "MLhuillier" ||
				  $('#WithdrawalOption').val() == "Cebuana Lhuillier" ||
				  $('#WithdrawalOption').val() == "Western Union" ||
				  $('#WithdrawalOption').val() == "RD Pawnshop") && $('#SendToFirstName').val() == "") {
				showJSMessage("Bank","Please enter receiver first name.","OK");
			}else if(($('#WithdrawalOption').val() == "Palawan Pera Padala" ||
				  $('#WithdrawalOption').val() == "MLhuillier" ||
				  $('#WithdrawalOption').val() == "Cebuana Lhuillier" ||
				  $('#WithdrawalOption').val() == "Western Union" ||
				  $('#WithdrawalOption').val() == "RD Pawnshop") && $('#SendToLastName').val() == "") {
				showJSMessage("Bank","Please enter receiver last name.","OK");
			}else if(($('#WithdrawalOption').val() == "Palawan Pera Padala" ||
				  $('#WithdrawalOption').val() == "MLhuillier" ||
				  $('#WithdrawalOption').val() == "Cebuana Lhuillier" ||
				  $('#WithdrawalOption').val() == "Western Union" ||
				  $('#WithdrawalOption').val() == "RD Pawnshop") && $('#SendToMiddleName').val() == "") {
				showJSMessage("Bank","Please enter receiver middle name.","OK");
			}else if(($('#WithdrawalOption').val() == "Palawan Pera Padala" ||
				  $('#WithdrawalOption').val() == "MLhuillier" ||
				  $('#WithdrawalOption').val() == "Cebuana Lhuillier" ||
				  $('#WithdrawalOption').val() == "Western Union" ||
				  $('#WithdrawalOption').val() == "RD Pawnshop") && $('#SendToMobileNo').val() == "") {
				showJSMessage("Bank","Please enter receiver mobile number.","OK");
			}else if($('#Status').val() == "{{ config('app.STATUS_APPROVED') }}" && ($('#WithdrawalOption').val() == "Palawan Pera Padala" ||
				  $('#WithdrawalOption').val() == "MLhuillier" ||
				  $('#WithdrawalOption').val() == "Cebuana Lhuillier" ||
				  $('#WithdrawalOption').val() == "Western Union" ||
				  $('#WithdrawalOption').val() == "RD Pawnshop") && $('#SenderName').val() == "") {
				showJSMessage("Bank","Please enter sender full name.","OK");
			}else if($('#Status').val() == "{{ config('app.STATUS_APPROVED') }}" && ($('#WithdrawalOption').val() == "Palawan Pera Padala" ||
				  $('#WithdrawalOption').val() == "MLhuillier" ||
				  $('#WithdrawalOption').val() == "Cebuana Lhuillier" ||
				  $('#WithdrawalOption').val() == "Western Union" ||
				  $('#WithdrawalOption').val() == "RD Pawnshop") && $('#SendingRefNo').val() == "") {
				showJSMessage("Bank","Please enter control number or reference number.","OK");
			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						WithdrawalID: $("#WithdrawalID").val(),
						WithdrawalNo: $("#WithdrawalNo").val(),
						WithdrawalDateTime: $("#WithdrawalDateTime").val(),
						Status: $("#Status").val(),

						WithdrawByMemberID: $("#WithdrawByMemberID").val(),

						WithdrawalOption: $("#WithdrawalOption").val(),

						CheckNo: $("#CheckNo").val(),
						CheckDate: $("#CheckDate").val(),
						CheckAmount: CheckAmount,

						Bank: $("#Bank").val(),
						BankAccountName: $("#BankAccountName").val(),
						BankAccountNo: $("#BankAccountNo").val(),

						SendToFirstName: $("#SendToFirstName").val(),
						SendToLastName: $("#SendToLastName").val(),
						SendToMiddleName: $("#SendToMiddleName").val(),
						SendToTelNo: $("#SendToTelNo").val(),
						SendToMobileNo: $("#SendToMobileNo").val(),
						SendToEmailAddress: $("#SendToEmailAddress").val(),
						SenderName: $("#SenderName").val(),
						SendingRefNo: $("#SendingRefNo").val(),

						Notes: $("#Notes").val(),

						EWalletBalance: EWalletBalance,
						RequestedAmount: RequestedAmount,
						ApprovedAmount: ApprovedAmount,
						ProcessingFee: ProcessingFee,
						NetAmountToReceive: NetAmountToReceive,

						ApproveRemarks : ''

					},
					url: "{{ route('do-save-ewallet-withdrawal') }}",
					dataType: "json",
					success: function(data){

				        $("#divLoader").hide();
						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.EWalletWithdrawalInfo);
						}else{
							showJSModalMessageJS("Save E-Wallet Withdrawal",data.ResponseMessage,"OK");
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

	    function CancelOrder(vWithdrawalID){
	    	if(vWithdrawalID > 0){
				$("#CancelledWithdrawalID").val(vWithdrawalID);
				$("#CancellationReason").text('');				
				$("#set-as-cancelled-modal").modal();
	    	}
	    }

	    function ProceedCancelOrder(){

	    	if($("#CancellationReason").val() == ""){
				showJSModalMessageJS("Cancel Order","Please enter the reason for the cancellation of this record.","OK");
	    	}else{
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						WithdrawalID: $("#CancelledWithdrawalID").val(),
						CancellationReason : $("#CancellationReason").val()
					},
					url: "{{ route('do-cancel-ewallet-withdrawal') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnSaveCancel", "Save", false);
						if(data.Response =='Success'){
							$("#set-as-cancelled-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.EWalletWithdrawalInfo);
						}else{
							showJSModalMessageJS("E-Wallet Withdrawal Cancellation",data.ResponseMessage,"OK");
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
	                        url: "{{ route('get-member-search-list') }}",
	                        dataType: "json",
	                        method: 'get',
							data: {
                             	SearchText: request.term,
								PageNo : 1,
								Status : "",
								IsWithEwallet : 1
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
	                console.log(data);
	                $('#WithdrawByMemberID').val(data[0]);
	                $('#WithdrawByMemberMobileNo').val(data[4]);
	                $('#WithdrawByMemberEmailAddress').val(data[5]);
	                $('#EWalletBalance').val(FormatDecimal(data[8],2));

	                $('#SendToFirstName').val(data[9]);
	                $('#SendToLastName').val(data[10]);
	                $('#SendToMiddleName').val(data[11]);
	                $('#SendToTelNo').val(data[3]);
	                $('#SendToMobileNo').val(data[4]);
	                $('#SendToEmailAddress').val(data[5]);

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



