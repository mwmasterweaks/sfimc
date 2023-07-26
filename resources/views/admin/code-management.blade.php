@extends('layout.adminweb')

@section('content')
  
@php($BronzePackagePrice = 0.00)
@php($BronzeProductWorth = 0.00)
@if(isset($BronzePackageInfo))
	@php($BronzePackagePrice = $BronzePackageInfo->PackagePrice)
	@php($BronzeProductWorth = $BronzePackageInfo->ProductWorth)
@endif

@php($SilverPackagePrice = 0.00)
@php($SilverProductWorth = 0.00)
@if(isset($SilverPackageInfo))
	@php($SilverPackagePrice = $SilverPackageInfo->PackagePrice)
	@php($SilverProductWorth = $SilverPackageInfo->ProductWorth)
@endif

@php($GoldPackagePrice = 0.00)
@php($GoldProductWorth = 0.00)
@if(isset($GoldPackageInfo))
	@php($GoldPackagePrice = $GoldPackageInfo->PackagePrice)
	@php($GoldProductWorth = $GoldPackageInfo->ProductWorth)
@endif

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Code Management
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Code Management</li>
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

		            <div class="box-body table-responsive" style="min-height: 250px;">

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
			                  <th>Owner Name</th>

			                  <th>Bronze Codes<br>
			                  	(<span style="color:green;">Total</span>
	                  			<span> : </span>
	                  			<span style="color:red;">Used</span>)
	                  		  </th>
			                  <th>Silver Codes<br>
			                  	(<span style="color:green;">Total</span>
	                  			<span> : </span>
	                  			<span style="color:red;">Used</span>)
			                  </th>
			                  <th>Gold Codes<br>
			                  	(<span style="color:green;">Total</span>
	                  			<span> : </span>
	                  			<span style="color:red;">Used</span>)
			                  </th>

			                  <th style="text-align: right;">TotalAmountDue</th>
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
								<option value="Approved" selected>Approved</option>
								<option value="Pending">Pending</option>
							</select>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Member <span style="color:red;">*</span></label>
                        <div class="col-md-12">
                            <input type="hidden" id="MemberID" name="MemberID" readonly>
                            <input type="text" data-type="MemberName" id="MemberName" name="MemberName" placeholder="Search by Member No., Member Name" class="form-control autocomplete_txt" autocomplete="off">
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
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Bronze Count<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='BronzeCount' type='text' class='form-control numberonly RecomputeTotal' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Price Per Code<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='BronzePrice' type='text' class='form-control numberonly' value='{{ number_format($BronzePackagePrice,2) }}' style='width:100%; text-align:right; font-weight: normal;' readonly>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Product Worth<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='BronzeProductWorth' type='text' class='form-control numberonly' value='{{ number_format($BronzeProductWorth,2) }}' style='width:100%; text-align:right; font-weight: normal;' readonly>
		                </div>
	            	</div>
	            </div>
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Silver Count<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='SilverCount' type='text' class='form-control numberonly RecomputeTotal' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Price Per Code<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='SilverPrice' type='text' class='form-control numberonly' value='{{ number_format($SilverPackagePrice,2) }}' style='width:100%; text-align:right; font-weight: normal;' readonly>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Product Worth<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='SilverProductWorth' type='text' class='form-control numberonly' value='{{ number_format($SilverProductWorth,2) }}' style='width:100%; text-align:right; font-weight: normal;' readonly>
		                </div>
	            	</div>
	            </div>
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Gold Count<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='GoldCount' type='text' class='form-control numberonly RecomputeTotal' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Price Per Code<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='GoldPrice' type='text' class='form-control numberonly' value='{{ number_format($GoldPackagePrice,2) }}' style='width:100%; text-align:right; font-weight: normal;' readonly>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Product Worth<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='GoldProductWorth' type='text' class='form-control numberonly' value='{{ number_format($GoldProductWorth,2) }}' style='width:100%; text-align:right; font-weight: normal;' readonly>
		                </div>
	            	</div>
	            </div>
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
							<a id="btnGenerateCodes" href="#" class="btn btn-info btn-flat" onclick="GenerateCodes()"><i class="fa fa-save"></i> Generate Code(s)</a>
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
	            "order": [[ 1, "asc" ], [ 3, "asc" ]]
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
	                        " <button type='button' class='btn btn-success' data-toggle='dropdown'> " +
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
	                    }

                      	tdOption +=
	                        " </ul> " +
	                    " </div> " ;

			tdBatchNo = "<span style='font-weight:normal;'>" + vData.BatchNo + "</span>";

			tdDateTimeGenerated = "<span style='font-weight:normal;'>" + vData.DateTimeGenerated + "</span>";
			tdOwnerName = "<span style='font-weight:normal;'>" + vData.OwnerMemberNo + " - " + vData.OwnerMemberName + "</span>";

			tdBronzeCodes = "<span style='font-weight:normal; color: green;'>" + FormatDecimal(vData.BronzeCount,0) + "</span>";
			tdBronzeCodes += "<span style='font-weight:normal;'> : </span>";
			tdBronzeCodes += "<span style='font-weight:normal; color: red;'>" + FormatDecimal(vData.BronzeUsedCode,0) + "</span>";

			tdSilverCodes = "<span style='font-weight:normal; color: green;'>" + FormatDecimal(vData.SilverCount,0) + "</span>";
			tdSilverCodes += "<span style='font-weight:normal;'> : </span>";
			tdSilverCodes += "<span style='font-weight:normal; color: red;'>" + FormatDecimal(vData.SilverUsedCode,0) + "</span>";

			tdGoldCodes = "<span style='font-weight:normal; color: green;'>" + FormatDecimal(vData.GoldCount,0) + "</span>";
			tdGoldCodes += "<span style='font-weight:normal;'> : </span>";
			tdGoldCodes += "<span style='font-weight:normal; color: red;'>" + FormatDecimal(vData.GoldUsedCode,0) + "</span>";			

			tdTotalAmountDue = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.TotalAmountDue,2) + "</span>";

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
			    	curData[4] = tdOwnerName;
			    	curData[5] = tdBronzeCodes;
			    	curData[6] = tdSilverCodes;
			    	curData[7] = tdGoldCodes;
			    	curData[8] = tdTotalAmountDue;
			    	curData[9] = tdStatus;

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
						tdOwnerName,
						tdBronzeCodes,
						tdSilverCodes,
						tdGoldCodes,
						tdTotalAmountDue,
						tdStatus
					]).draw();			
			}

	    }

	    function Clearfields(){

			$("#BatchID").val('0');

			$("#BatchNo").val('');
			$("#DateTimeGenerated").val('');
			$("#Status").val('Approved').change();
			
			$("#MemberID").val('');
			$("#MemberName").val('');

			$("#BronzeCount").val('');
			$("#BronzePrice").val('{{ number_format($BronzePackagePrice,2) }}');
			$("#BronzeProductWorth").val('{{ number_format($BronzeProductWorth,2) }}');

			$("#SilverCount").val('');
			$("#SilverPrice").val('{{ number_format($SilverPackagePrice,2) }}');
			$("#SilverProductWorth").val('{{ number_format($SilverProductWorth,2) }}');

			$("#GoldCount").val('');
			$("#GoldPrice").val('{{ number_format($GoldPackagePrice,2) }}');
			$("#GoldProductWorth").val('{{ number_format($GoldProductWorth,2) }}');

			$("#Remarks").val('');
			$("#PreparedBy").val('{{ Session('ADMIN_FULLNAME') }}');

			$("#TotalGrossAmount").val('0.00');
			$("#TotalDiscount").val('0.00');
			$("#TotalAmountDue").val('0.00');
			$("#TotalAmountPaid").val('');
			$("#AmountChange").val('0.00');

			$("#Status").prop('disabled', false);
			$("#MemberName").prop('disabled', false);
			
			$("#BronzeCount").prop('disabled', false);
			$("#SilverCount").prop('disabled', false);
			$("#GoldCount").prop('disabled', false);

			$("#TotalDiscount").prop('disabled', false);
			$("#TotalAmountPaid").prop('disabled', false);
			
			$("#Remarks").prop('disabled', false);

			$("#btnGenerateCodes").show();
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

						buttonOneClick("btnGenerateCodes", "Generate Code(s)", false);

						Clearfields();

						if(data.Response =='Success' && data.CodeGenerationBatchInfo != undefined){

							$("#BatchID").val(data.CodeGenerationBatchInfo.BatchID);
							$("#BatchNo").val(data.CodeGenerationBatchInfo.BatchNo);
							$("#DateTimeGenerated").val(data.CodeGenerationBatchInfo.DateTimeGenerated);
							$("#Status").val(data.CodeGenerationBatchInfo.Status).change();

							$("#MemberID").val(data.CodeGenerationBatchInfo.OwnerMemberID);
							$("#MemberName").val(data.CodeGenerationBatchInfo.OwnerMemberNo + " - " + data.CodeGenerationBatchInfo.OwnerMemberName);

							$("#BronzeCount").val(FormatDecimal(data.CodeGenerationBatchInfo.BronzeCount,0));
							$("#BronzePrice").val(FormatDecimal(data.CodeGenerationBatchInfo.BronzePrice,2));
							$("#BronzeProductWorth").val(FormatDecimal(data.CodeGenerationBatchInfo.BronzeProductWorth,2));

							$("#SilverCount").val(FormatDecimal(data.CodeGenerationBatchInfo.SilverCount,0));
							$("#SilverPrice").val(FormatDecimal(data.CodeGenerationBatchInfo.SilverPrice,2));
							$("#SilverProductWorth").val(FormatDecimal(data.CodeGenerationBatchInfo.SilverProductWorth,2));

							$("#GoldCount").val(FormatDecimal(data.CodeGenerationBatchInfo.GoldCount,0));
							$("#GoldPrice").val(FormatDecimal(data.CodeGenerationBatchInfo.GoldPrice,2));
							$("#GoldProductWorth").val(FormatDecimal(data.CodeGenerationBatchInfo.GoldProductWorth,2));

							$("#Remarks").val(data.CodeGenerationBatchInfo.Remarks);
							$("#PreparedBy").val(data.CodeGenerationBatchInfo.CreatedBy);

							$("#TotalGrossAmount").val(FormatDecimal(data.CodeGenerationBatchInfo.TotalGrossAmount,2));
							$("#TotalDiscount").val(FormatDecimal(data.CodeGenerationBatchInfo.TotalDiscount,2));
							$("#TotalAmountDue").val(FormatDecimal(data.CodeGenerationBatchInfo.TotalAmountDue,2));
							$("#TotalAmountPaid").val(FormatDecimal(data.CodeGenerationBatchInfo.AmountPaid,2));
							$("#AmountChange").val(FormatDecimal(data.CodeGenerationBatchInfo.AmountChange,2));

							$("#Status").prop('disabled', true);
							$("#MemberName").prop('disabled', true);
							
							$("#BronzeCount").prop('disabled', true);
							$("#SilverCount").prop('disabled', true);
							$("#GoldCount").prop('disabled', true);

							$("#TotalDiscount").prop('disabled', true);
							$("#TotalAmountPaid").prop('disabled', true);
							
							$("#Remarks").prop('disabled', true);

							$("#btnGenerateCodes").hide();

							$("#record-info-modal").modal();

						}else{
							showJSModalMessageJS("Batch Information",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
						buttonOneClick("btnGenerateCodes", "Generate Code(s)", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
						buttonOneClick("btnGenerateCodes", "", false);
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

			tdUsedBy = "";
			if(vData.UsedByMemberID > 0){
				tdUsedBy = "<span style='width:100%; font-weight:normal;'> Used by " + vData.UsedByMemberNo + " - " + vData.UsedByMemberName + " on Entry Code " + vData.EntryCode + "</span>";
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
			    	curData[5] = tdUsedBy;
			    	curData[6] = tdStatus;

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
					tdUsedBy,
					tdStatus
				]).draw();			

			}

	    }

	    function GenerateCodes(){

	    	var BronzeCount = 0;
    		if($('#BronzeCount').length){
    			if($("#BronzeCount").val() != ""){
		            var strBronzeCount = $("#BronzeCount").val();
		            BronzeCount = parseInt(strBronzeCount.replace(",",""));
    			}
			}
	    	var BronzePrice = 0;
    		if($('#BronzePrice').length){
    			if($("#BronzePrice").val() != ""){
		            var strBronzePrice = $("#BronzePrice").val();
		            BronzePrice = parseFloat(strBronzePrice.replace(",",""));
    			}
			}
			var BronzeProductWorth = 0;
    		if($('#BronzeProductWorth').length){
    			if($("#BronzeProductWorth").val() != ""){
		            var strBronzeProductWorth = $("#BronzeProductWorth").val();
		            BronzeProductWorth = parseFloat(strBronzeProductWorth.replace(",",""));
    			}
			}

	    	var SilverCount = 0;
    		if($('#SilverCount').length){
    			if($("#SilverCount").val() != ""){
		            var strSilverCount = $("#SilverCount").val();
		            SilverCount = parseInt(strSilverCount.replace(",",""));
    			}
			}
	    	var SilverPrice = 0;
    		if($('#SilverPrice').length){
    			if($("#SilverPrice").val() != ""){
		            var strSilverPrice = $("#SilverPrice").val();
		            SilverPrice = parseFloat(strSilverPrice.replace(",",""));
    			}
			}
			var SilverProductWorth = 0;
    		if($('#SilverProductWorth').length){
    			if($("#SilverProductWorth").val() != ""){
		            var strSilverProductWorth = $("#SilverProductWorth").val();
		            SilverProductWorth = parseFloat(strSilverProductWorth.replace(",",""));
    			}
			}

	    	var GoldCount = 0;
    		if($('#GoldCount').length){
    			if($("#GoldCount").val() != ""){
		            var strGoldCount = $("#GoldCount").val();
		            GoldCount = parseInt(strGoldCount.replace(",",""));
    			}
			}
	    	var GoldPrice = 0;
    		if($('#GoldPrice').length){
    			if($("#GoldPrice").val() != ""){
		            var strGoldPrice = $("#GoldPrice").val();
		            GoldPrice = parseFloat(strGoldPrice.replace(",",""));
    			}
			}
			var GoldProductWorth = 0;
    		if($('#GoldProductWorth').length){
    			if($("#GoldProductWorth").val() != ""){
		            var strGoldProductWorth = $("#GoldProductWorth").val();
		            GoldProductWorth = parseFloat(strGoldProductWorth.replace(",",""));
    			}
			}

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

			if($('#MemberID').val() == "") {
				showJSMessage("Generate Code(s)","Please select owner name.","OK");
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

						MemberID: $("#MemberID").val(),

						BronzeCount: BronzeCount,
						BronzePrice: BronzePrice,
						BronzeProductWorth: BronzeProductWorth,

						SilverCount: SilverCount,
						SilverPrice: SilverPrice,
						SilverProductWorth: SilverProductWorth,

						GoldCount: GoldCount,
						GoldPrice: GoldPrice,
						GoldProductWorth: GoldProductWorth,

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

						buttonOneClick("btnGenerateCodes", "Generate Code(s)", false);
						if(data.Response =='Success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.CodeGenerationBatchInfo);
						}else{
							showJSModalMessageJS("Save Code Generation",data.ResponseMessage,"OK");
						}

					},
					error: function(data){
						buttonOneClick("btnGenerateCodes", "Generate Code(s)", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
						buttonOneClick("btnGenerateCodes", "", true);
					}
	        	});
	      }
	    };

		$(document).on('change keyup blur','.RecomputeTotal',function(){
	 		RecomputeTotal();
		});

	    function RecomputeTotal(){

	    	var BronzeCount = 0;
	    	var BronzePrice = {{ str_replace(",", "", number_format($BronzePackagePrice,2)) }};
    		if($('#BronzeCount').length){
    			if($("#BronzeCount").val() != ""){
    				var strBronzeCount = $("#BronzeCount").val();
		            BronzeCount = parseInt(strBronzeCount.replace(",",""));
    			}
			}

	    	var SilverCount = 0;
	    	var SilverPrice = {{ str_replace(",", "", number_format($SilverPackagePrice,2)) }};
    		if($('#SilverCount').length){
    			if($("#SilverCount").val() != ""){
    				var strSilverCount = $("#SilverCount").val();
		            SilverCount = parseInt(strSilverCount.replace(",",""));
    			}
			}

	    	var GoldCount = 0;
	    	var GoldPrice = {{ str_replace(",", "", number_format($GoldPackagePrice,2)) }};
    		if($('#GoldCount').length){
    			if($("#GoldCount").val() != ""){
    				var strGoldCount = $("#GoldCount").val();
		            GoldCount = parseInt(strGoldCount.replace(",",""));
    			}
			}

			var TotalBronze = parseFloat(BronzeCount) * parseFloat(BronzePrice);
			var TotalSilver = parseFloat(SilverCount) * parseFloat(SilverPrice);
			var TotalGold = parseFloat(GoldCount) * parseFloat(GoldPrice);

	    	var TotalGrossAmount = TotalBronze + TotalSilver + TotalGold;
			$('#TotalGrossAmount').val(FormatDecimal(TotalGrossAmount,2));

			var TotalDiscount = 0;
    		if($('#TotalDiscount').length){
    			if($("#TotalDiscount").val() != ""){
    				var strTotalDiscount = $("#TotalDiscount").val();
		            TotalDiscount = parseFloat(strTotalDiscount.replace(",",""));
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
								Status : ""
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
	                $('#MemberID').val(data[0]);

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
