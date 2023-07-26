@extends('layout.adminweb')

@section('content')
  
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Code Generation
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Code Generation</li>
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
			                  <th>Batch No.</th>
			                  <th>Date/Time</th>
			                  <th>Center</th>
			                  <th>Free</th>

			                  @foreach($PackageList as $package)
				                  <th>{{ $package->Package }}<br>
				                  	(<span style="color:green;">Total</span>
		                  			<span> : </span>
		                  			<span style="color:#dfb407;">Issued</span>
		                  			<span> : </span>
		                  			<span style="color:red;">Used</span>)
		                  		  </th>
			                  @endforeach

			                  <th style="text-align: right;">TotalAmountDue</th>
			                  <th>Prepared By</th>
			                  <th>Approved By</th>
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
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Batch Information</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="BatchID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Batch No. <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="BatchNo" type="text" class="form-control" placeholder="Auto Generated" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Date/Time Generated<span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="DateTimeGenerated" type="text" class="form-control" value=""  placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
							<select id="Status" class="form-control select2" style="width: 100%; font-weight: normal;">
								<option value="Approved">Approved</option>
								<option value="Pending" selected>Pending</option>
							</select>
		                </div>
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
										>{{ $ctr->Center }}</option>
								@endforeach
							</select>
		                </div>
	            	</div>

	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Free Code? <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
							<select id="IsFreeCode" class="form-control select2" style="width: 100%; font-weight: normal;">
								<option value="1">Yes</option>
								<option value="0" selected>No</option>
							</select>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                

	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">How many code(s) to generate?</label>
	            	</div>
            	</div>

	            @foreach($PackageList as $package)
		            <div class="row">
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">{{ $package->Package }} Count<span style="color:red;">*</span></label>
			                <div class="col-md-12">
								<input id='Package{{ $package->PackageID }}Count' type='text' class='form-control numberonly RecomputeTotal' value='' style='width:100%; text-align:right; font-weight: normal;'>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Price Per Code<span style="color:red;">*</span></label>
			                <div class="col-md-12">
								<input id='Package{{ $package->PackageID }}Price' type='text' class='form-control numberonly' value='{{ number_format($package->PackagePrice,2) }}' style='width:100%; text-align:right; font-weight: normal;' readonly>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Product Worth<span style="color:red;">*</span></label>
			                <div class="col-md-12">
								<input id='Package{{ $package->PackageID }}ProductWorth' type='text' class='form-control numberonly' value='{{ number_format($package->ProductWorth,2) }}' style='width:100%; text-align:right; font-weight: normal;' readonly>
			                </div>
		            	</div>
		            </div>
	            @endforeach

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Remarks</label>
		                <div class="col-md-12">
							<input id='Remarks' type='text' class='form-control' value='' style='width:100%; font-weight: normal;'>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                

	            <div class="row">
	            	<div class="col-md-6">
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
										<a href="#" class="btn btn-primary btn-flat" style="width: 150px; text-align: right;">Total Discount</a>
									</span>
									<input id="TotalDiscount" type="text" placeholder="Total Discount" class="form-control DecimalOnly RecomputeTotal" style="width:100%; font-weight:normal; text-align: right;">
								</div>		
							</div>		
						</div>	
			            <div class="row">
			            	<div class="col-md-12">
								<div class="input-group margin pull-right">
									<span class="input-group-btn">
										<a href="#" class="btn btn-primary btn-flat" style="width: 150px; text-align: right;">Total Amount Due</a>
									</span>
									<input id="TotalAmountDue" type="text" placeholder="Total Amount Due" class="form-control" style="width:100%; font-weight:bold; text-align: right;" readonly>
								</div>		
							</div>	
						</div>	
			            <div class="row">
			            	<div class="col-md-12">
								<div class="input-group margin pull-right">
									<span class="input-group-btn">
										<a href="#" class="btn btn-success btn-flat" style="width: 150px; text-align: right;">Total Amount Paid</a>
									</span>
									<input id="TotalAmountPaid" type="text" placeholder="Total Amount Paid" class="form-control DecimalOnly RecomputeTotal" style="width:100%; font-weight:normal; text-align: right;" required>
								</div>		
							</div>	
						</div>	
			            <div class="row">
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
							<a id="btnSave" href="#" class="btn btn-info btn-flat" onclick="GenerateCodes()"><i class="fa fa-save"></i> Save</a>
							<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Cancel</a>
						</span>
					</div>	
	            </div>

         	</div>

	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="code-generation-list-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">

          	<div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Code Information</b></h4>
          	</div>

          	<div class="modal-body">

	            <div class="row">
	            	<div class="col-md-12">
		              	<table id="tblCodes" class="table table-bordered table-hover">
			                <thead>
				                <tr>
				                  <th></th>
				                  <th>PackageID</th>
				                  <th>Package Type</th>
				                  <th style="width: 75px;">Series No.</th>
				                  <th>Code</th>
				                  <th>Issued To</th>
				                  <th>Used By</th>
				                  <th>Status</th>
				                </tr>
			                </thead>
			                <tbody>
				            </tbody>
		              	</table>
					</div>	
				</div>	

	            <div style="clear:both;"></div>
	            <br>                

	            <div class="modal-footer">
					<div class="input-group pull-right">
						<span class="input-group-btn">
							<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Close</a>
						</span>
					</div>	
	            </div>

         	</div>

	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="approve-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  	<div class="modal-dialog modal-lg">
	    	<div class="modal-content">
	          	<div class="modal-header" style="background-color: #3c8dbc;">
	            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            	<h4 class="modal-title"  style="text-align:center; color:white;"><span><b>Approve Batch Code</b></span></h4>
	          	</div>

          		<div class="modal-body">
        			<input type="hidden" id="ApproveBatchID" value="0" readonly>
		            <div class="row">
		            	<div class="col-md-12">
			                <label class="col-md-12" style="font-weight: normal;">Are you sure you want to approve this batch code?</label>
		            	</div>
	            	</div>
		            <div style="clear:both;"></div>
		            <br>                
		            <div class="modal-footer">
						<div class="input-group pull-right">
							<span class="input-group-btn">
								<a id="btnSaveApprove" href="#" class="btn btn-info btn-flat" onclick="ProceedApprove()"><i class="fa fa-save"></i> Proceed</a>
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
	            "order": [[ 3, "desc" ]]
	        });

	        $('#tblCodes').DataTable( {
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
	            "order": [[ 0, "asc" ], [ 1, "asc" ], [ 3, "asc" ]]
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
					SearchText: vSearchText,
					PageNo: vPageNo,
					Status: ''
				},
				url: "{{ route('get-code-generation-batch-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.CodeGenerationBatchList);
				},
				error: function(data){
					console.log(data.responseText);
				},
				beforeSend:function(vData){
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

	    	tdID = vData.BatchID;

			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='ViewInformation(" + vData.BatchID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> View Information " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";

						if(vData.Status == "Approved"){
                          	tdOption +=
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='ViewCodesGenerated(" + vData.BatchID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> View Generated Codes" + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";

                          	tdOption += 
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='{{ route('admin-print-codes') }}?BatchID=" + vData.BatchID + "' target='blank_' >" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Print Codes " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";
	                    }else if(vData.Status == "Pending"){
                          	tdOption +=
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='ApproveBatchCode(" + vData.BatchID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Approve" + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";                  	
	                    }

                      	tdOption +=
	                        " </ul> " +
	                    " </div> " ;

			tdBatchNo = "<span style='font-weight:normal;'>" + vData.BatchNo + "</span>";

			tdDateTimeGenerated = "<span style='font-weight:normal;'>" + vData.DateTimeGenerated + "</span>";
			tdCenter = "<span style='font-weight:normal;'>" + vData.CenterNo + " - " + vData.Center + "</span>";

			tdIsFreeCode = "<span style='font-weight:normal;'>" + (vData.IsFreeCode == 1 ? "Yes" : "No") + "</span>";

            @foreach($PackageList as $package)

				tdPackage{{$package->PackageID}}Codes = "<span style='font-weight:normal; color: green;'>" + FormatDecimal(vData.Package{{ $package->PackageID }}Count,0) + "</span>";
				tdPackage{{$package->PackageID}}Codes += "<span style='font-weight:normal;'> : </span>";
				tdPackage{{$package->PackageID}}Codes += "<span style='font-weight:normal; color: #dfb407;'>" + FormatDecimal(vData.Package{{ $package->PackageID }}IssuedCode,0) + "</span>";
				tdPackage{{$package->PackageID}}Codes += "<span style='font-weight:normal;'> : </span>";
				tdPackage{{$package->PackageID}}Codes += "<span style='font-weight:normal; color: red;'>" + FormatDecimal(vData.Package{{ $package->PackageID }}UsedCode,0) + "</span>";

            @endforeach

			tdTotalAmountDue = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.TotalAmountDue,2) + "</span>";

			tdPreparedBy = "<span style='font-weight:normal;'>" + vData.CreatedBy + "</span>";

			tdApprovedBy = "<span style='font-weight:normal;'></span>";
			if(vData.Status == "Approved"){
				tdApprovedBy = "<span style='font-weight:normal;'>" + vData.UpdatedBy + "</span>";
			}

			tdStatus = "";
			if(vData.Status == "Approved"){
				tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}else{
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.Status + "</span>";
			}

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();

			    if(rowData[0] == vData.BatchID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdOption;
			    	curData[2] = tdBatchNo;
			    	curData[3] = tdDateTimeGenerated;
			    	curData[4] = tdCenter;
			    	curData[5] = tdIsFreeCode;

			    	@php($intCntr = 6)
		            @foreach($PackageList as $package)
				    	curData[{{$intCntr}}] = tdPackage{{$package->PackageID}}Codes;
				    	@php($intCntr = $intCntr + 1)
		            @endforeach

			    	curData[{{$intCntr}}] = tdTotalAmountDue;

			    	@php($intCntr = $intCntr + 1)
			    	curData[{{$intCntr}}] = tdPreparedBy;
			    	@php($intCntr = $intCntr + 1)
			    	curData[{{$intCntr}}] = tdApprovedBy;
			    	@php($intCntr = $intCntr + 1)
			    	curData[{{$intCntr}}] = tdStatus;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdID,
						tdOption,
						tdBatchNo,
						tdDateTimeGenerated, 
						tdCenter,
						tdIsFreeCode,
			            @foreach($PackageList as $package)
					    	tdPackage{{$package->PackageID}}Codes,
			            @endforeach
						tdTotalAmountDue,
						tdPreparedBy,
						tdApprovedBy,
						tdStatus
					]).draw();			
			}

	    }

	    function Clearfields(){

			$("#BatchID").val('0');

			$("#BatchNo").val('');
			$("#DateTimeGenerated").val('');
			$("#Status").val('Approved').change();
			
			$("#Center").val('').change();

			$("#IsFreeCode").val('0').change();

            @foreach($PackageList as $package)
				$("#Package{{ $package->PackageID }}Count").val('0');
				$("#Package{{ $package->PackageID }}Price").val('{{ number_format($package->PackagePrice,2) }}');
				$("#Package{{ $package->PackageID }}ProductWorth").val('{{ number_format($package->ProductWorth,2) }}');

				$("#Package{{ $package->PackageID }}Count").prop('disabled', false);

            @endforeach

			$("#Remarks").val('');
			$("#PreparedBy").val('{{ Session('ADMIN_FULLNAME') }}');

			$("#TotalGrossAmount").val('0.00');
			$("#TotalDiscount").val('0.00');
			$("#TotalAmountDue").val('0.00');
			$("#TotalAmountPaid").val('');
			$("#AmountChange").val('0.00');

			$("#Status").prop('disabled', false);
			$("#Center").prop('disabled', false);
			$("#IsFreeCode").prop('disabled', false);
			
			$("#TotalDiscount").prop('disabled', false);
			$("#TotalAmountPaid").prop('disabled', false);
			
			$("#Remarks").prop('disabled', false);

			$("#btnSave").show();
	    }

	    function NewRecord(){

	    	isNewRecord = 1;
			Clearfields();

			$("#record-info-modal").modal();
	    }

	    function ViewInformation(vRecordID){

	    	if(vRecordID > 0){
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						BatchID: vRecordID
					},
					url: "{{ route('get-code-generation-batch-info') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnSave", "Save", false);

						Clearfields();

						if(data.Response =='Success' && data.CodeGenerationBatchInfo != undefined){

							$("#BatchID").val(data.CodeGenerationBatchInfo.BatchID);
							$("#BatchNo").val(data.CodeGenerationBatchInfo.BatchNo);
							$("#DateTimeGenerated").val(data.CodeGenerationBatchInfo.DateTimeGenerated);
							$("#Status").val(data.CodeGenerationBatchInfo.Status).change();

							$("#Center").val(data.CodeGenerationBatchInfo.CenterID).change();

							$("#IsFreeCode").val(data.CodeGenerationBatchInfo.IsFreeCode).change();

				            @foreach($PackageList as $package)
								$("#Package{{ $package->PackageID }}Count").val(FormatDecimal(data.CodeGenerationBatchInfo.Package{{ $package->PackageID }}Count,0));
								$("#Package{{ $package->PackageID }}Price").val(FormatDecimal(data.CodeGenerationBatchInfo.Package{{ $package->PackageID }}Price,2));
								$("#Package{{ $package->PackageID }}ProductWorth").val(FormatDecimal(data.CodeGenerationBatchInfo.Package{{ $package->PackageID }}ProductWorth,2));

								$("#Package{{ $package->PackageID }}Count").prop('disabled', true);

				            @endforeach

							$("#Remarks").val(data.CodeGenerationBatchInfo.Remarks);
							$("#PreparedBy").val(data.CodeGenerationBatchInfo.CreatedBy);

							$("#TotalGrossAmount").val(FormatDecimal(data.CodeGenerationBatchInfo.TotalGrossAmount,2));
							$("#TotalDiscount").val(FormatDecimal(data.CodeGenerationBatchInfo.TotalDiscount,2));
							$("#TotalAmountDue").val(FormatDecimal(data.CodeGenerationBatchInfo.TotalAmountDue,2));
							$("#TotalAmountPaid").val(FormatDecimal(data.CodeGenerationBatchInfo.AmountPaid,2));
							$("#AmountChange").val(FormatDecimal(data.CodeGenerationBatchInfo.AmountChange,2));

							$("#Status").prop('disabled', true);
							$("#Center").prop('disabled', true);
							$("#IsFreeCode").prop('disabled', true);

							$("#TotalDiscount").prop('disabled', true);
							$("#TotalAmountPaid").prop('disabled', true);
							
							$("#Remarks").prop('disabled', true);

							$("#btnSave").hide();

							$("#record-info-modal").modal();

						}else{
							showJSModalMessageJS("Batch Information",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
						buttonOneClick("btnSave", "Save", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
						buttonOneClick("btnSave", "", false);
					}
	        	});

	    	}

	    }

	    function ViewCodesGenerated(vRecordID){

	    	if(vRecordID > 0){

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						BatchID: vRecordID
					},
					url: "{{ route('get-code-generation-by-batch') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();

						$("#tblCodes").DataTable().clear().draw();
				        LoadRecordItemList(vRecordID, data.CodeGenerationByBatch);

						$("#code-generation-list-modal").modal();
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
	    }

	    function LoadRecordItemList(vRecordID, vList){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadRecordItemRow(vRecordID, vList[x]);
	    		}
	    	}
	    }

	    function LoadRecordItemRow(vRecordID, vData){

	    	var tblCodes = $("#tblCodes").DataTable();

	    	tdID = vData.CodeID;
	    	tdPackageID = vData.PackageID;
			tdPackage = "<span style='width:100%; font-weight:normal;'>" + vData.Package + "</span>";
			tdSeriesNo = "<span style='width:100%; font-weight:normal;'>" + vData.SeriesNo + "</span>";
			tdCode = "<span style='width:100%; font-weight:normal;'>" + vData.Code + "</span>";

			tdIssuedTo = "";
			if(vData.IssuedToMemberEntryID > 0){
				tdIssuedTo = "<span style='width:100%; font-weight:normal;'>" + vData.IssuedToEntryCode + " - " + vData.IssuedToMemberName + "</span>";
			}

			tdUsedBy = "";
			if(vData.UsedByMemberID > 0){
				tdUsedBy = "<span style='width:100%; font-weight:normal;'>" + vData.EntryCode + " - " + vData.UsedByMemberName + " on IBO Number " + vData.EntryCode + "</span>";
			}
			
			tdStatus = "";
			if(vData.Status == "{{ config('app.STATUS_AVAILABLE') }}"){
				tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}else{
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.Status + "</span>";
			}

			//Check if record already listed
			var IsRecordExist = false;
			tblCodes.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

			    var rowData = this.data();
			    if(rowData[1] == vData.CodeID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblCodes.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdPackageID;
			    	curData[2] = tdPackage;
			    	curData[3] = tdSeriesNo;
			    	curData[4] = tdCode;
			    	curData[5] = tdIssuedTo;
			    	curData[6] = tdUsedBy;
			    	curData[7] = tdStatus;

			    	tblCodes.row(rowIdx).data(curData).invalidate().draw();
			    }
			});

			if(!IsRecordExist){

				tblCodes.row.add([
					tdID,
					tdPackageID,
					tdPackage,
					tdSeriesNo,
					tdCode,
					tdIssuedTo,
					tdUsedBy,
					tdStatus
				]).draw();			

			}

	    }

	    function GenerateCodes(){

            @foreach($PackageList as $package)
		    	var Package{{$package->PackageID}}Count = 0;
	    		if($('#Package{{$package->PackageID}}Count').length){
	    			if($("#Package{{$package->PackageID}}Count").val() != ""){
			            var strPackageCount = $("#Package{{$package->PackageID}}Count").val();
			            Package{{$package->PackageID}}Count = parseInt(strPackageCount.replace(",",""));
	    			}
				}

		    	var Package{{$package->PackageID}}Price = 0;
	    		if($('#Package{{$package->PackageID}}Price').length){
	    			if($("#Package{{$package->PackageID}}Price").val() != ""){
			            var strPackagePrice = $("#Package{{$package->PackageID}}Price").val();
			            Package{{$package->PackageID}}Price = parseFloat(strPackagePrice.replace(",",""));
	    			}
				}
				var Package{{$package->PackageID}}ProductWorth = 0;
	    		if($('#Package{{$package->PackageID}}ProductWorth').length){
	    			if($("#Package{{$package->PackageID}}ProductWorth").val() != ""){
			            var strPackageProductWorth = $("#Package{{$package->PackageID}}ProductWorth").val();
			            Package{{$package->PackageID}}ProductWorth = parseFloat(strPackageProductWorth.replace(",",""));
	    			}
				}

            @endforeach

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

			var TotalAmountDue = 0;
    		if($('#TotalAmountDue').length){
    			if($("#TotalAmountDue").val() != ""){
		            var strTotalAmountDue = $("#TotalAmountDue").val();
		            TotalAmountDue = parseFloat(strTotalAmountDue.replace(",",""));
    			}
			}	

			var TotalAmountPaid = 0;
    		if($('#TotalAmountPaid').length){
    			if($("#TotalAmountPaid").val() != ""){
		            var strTotalAmountPaid = $("#TotalAmountPaid").val();
		            TotalAmountPaid = parseFloat(strTotalAmountPaid.replace(",",""));
    			}
			}

			var AmountChange = 0;
    		if($('#AmountChange').length){
    			if($("#AmountChange").val() != ""){
		            var strAmountChange = $("#AmountChange").val();
		            AmountChange = parseFloat(strAmountChange.replace(",",""));
    			}
			}

			if($('#Center').val() == "") {
				showJSMessage("Generate Code(s)","Please select center.","OK");
			}else if($('#BronzeCount').val() == 0 && $('#SilverCount').val() == 0 && $('#GoldCount').val() == 0) {
				showJSMessage("Generate Code(s)","Please enter how many code(s) you want to generate.","OK");
			}else if(TotalAmountDue > TotalAmountPaid) {
				showJSMessage("Amount Paid","Please pay total amount due.","OK");
			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						BatchID: $("#BatchID").val(),
						BatchNo: $("#BatchNo").val(),
						DateTimeGenerated: $("#DateTimeGenerated").val(),
						Status: $("#Status").val(),

						CenterID: $("#Center").val(),
						IsFreeCode: $("#IsFreeCode").val(),

			            @foreach($PackageList as $package)
							Package{{$package->PackageID}}Count: Package{{$package->PackageID}}Count,
							Package{{$package->PackageID}}Price: Package{{$package->PackageID}}Price,
							Package{{$package->PackageID}}ProductWorth: Package{{$package->PackageID}}ProductWorth,
			            @endforeach

						TotalGrossAmount: TotalGrossAmount,
						TotalDiscount: TotalDiscount,
						TotalAmountDue: TotalAmountDue,
						AmountPaid: TotalAmountPaid,
						AmountChange: AmountChange,

						Remarks: $("#Remarks").val()
					},
					url: "{{ route('do-code-generation-batch') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.CodeGenerationBatchInfo);
						}else{
							showJSModalMessageJS("Save Code Generation",data.ResponseMessage,"OK");
						}

					},
					error: function(data){
						buttonOneClick("btnSave", "Save", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
						buttonOneClick("btnSave", "", true);
					}
	        	});
	      }
	    };

	    $("#IsFreeCode").change(function(){
    		RecomputeTotal();
	    });

		$(document).on('change keyup blur','.RecomputeTotal',function(){
	 		RecomputeTotal();
		});

	    function RecomputeTotal(){

	    	var TotalGrossAmount = 0;

            @foreach($PackageList as $package)
		    	var Package{{$package->PackageID}}Count = 0;
	    		if($('#Package{{$package->PackageID}}Count').length){
	    			if($("#Package{{$package->PackageID}}Count").val() != ""){
			            var strPackageCount = $("#Package{{$package->PackageID}}Count").val();
			            Package{{$package->PackageID}}Count = parseInt(strPackageCount.replace(",",""));
	    			}
				}

		    	var Package{{$package->PackageID}}Price = 0;
	    		if($('#Package{{$package->PackageID}}Price').length){
	    			if($("#Package{{$package->PackageID}}Price").val() != ""){
			            var strPackagePrice = $("#Package{{$package->PackageID}}Price").val();
			            Package{{$package->PackageID}}Price = parseFloat(strPackagePrice.replace(",",""));
	    			}
				}

		    	TotalGrossAmount = TotalGrossAmount + (parseFloat(Package{{$package->PackageID}}Count) * parseFloat(Package{{$package->PackageID}}Price));

            @endforeach

			$('#TotalGrossAmount').val(FormatDecimal(TotalGrossAmount,2));

			var TotalDiscount = 0;
	    	if($("#IsFreeCode").val() == "1"){
    			$("#TotalDiscount").val(FormatDecimal(TotalGrossAmount,2));
    			TotalDiscount = TotalGrossAmount;
	    	}else{
	    		if($('#TotalDiscount').length){
	    			if($("#TotalDiscount").val() != ""){
	    				var strTotalDiscount = $("#TotalDiscount").val();
			            TotalDiscount = parseFloat(strTotalDiscount.replace(",",""));
	    			}
				}
	    	}

	    	var TotalAmountDue = TotalGrossAmount - TotalDiscount;
			$('#TotalAmountDue').val(FormatDecimal(TotalAmountDue,2));

	    	var TotalAmountPaid = 0;
    		if($('#TotalAmountPaid').length){
    			if($("#TotalAmountPaid").val() != ""){
		            var strTotalAmountPaid = $("#TotalAmountPaid").val();
		            TotalAmountPaid = parseFloat(strTotalAmountPaid.replace(",",""));
    			}
			}

	    	var AmountChange = TotalAmountPaid - TotalAmountDue;
			$('#AmountChange').val(FormatDecimal(AmountChange,2));
	    }	

	    function ApproveBatchCode(vBatchID){
	    	if(vBatchID > 0){
				$("#ApproveBatchID").val(vBatchID);
				$("#approve-modal").modal();
	    	}
	    }

	    function ProceedApprove(){

			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					BatchID: $("#ApproveBatchID").val()
				},
				url: "{{ route('do-approve-code-generation-batch') }}",
				dataType: "json",
				success: function(data){
			        $("#divLoader").hide();
					buttonOneClick("btnSaveApprove", "Proceed", false);
					if(data.Response =='Success'){
						$("#approve-modal").modal('hide');
						showMessage("Success",data.ResponseMessage);
						LoadRecordRow(data.CodeGenerationBatchInfo);
					}else{
						showJSModalMessageJS("Batch Code Generation - Approved",data.ResponseMessage,"OK");
					}
				},
				error: function(data){
			        $("#divLoader").hide();
					buttonOneClick("btnSaveApprove", "Proceed", false);
					console.log(data.responseText);
				},
				beforeSend:function(vData){
			        $("#divLoader").show();
					buttonOneClick("btnSaveApprove", "", true);
				}
        	});
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
