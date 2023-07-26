@extends('layout.adminweb')

@section('content')

@php($IsAllowCancelCode = Session('IS_SUPER_ADMIN')==true ? 'true' : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Allow Cancel Code'))  
  
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Code Distribution
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Code Distribution</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-success">

		            <div class="box-header">

				        <div class="col-md-3" style="margin-top: 12px;">
							<select id="SearchCenter" class="form-control select2" style="width: 100%; height: 100px; font-weight:normal;" {{ Session('IS_SUPER_ADMIN') == 1 ? "" : "disabled" }}>
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
								</span>
							</div>		            
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
			                  <th>Sort</th>
			                  <th></th>
			                  <th>Batch No.</th>
			                  <th>Date/Time</th>
			                  <th>Center</th>
			                  <th>SeriesNo<br>
			                  <th>Code</th>
			                  <th>Package</th>
			                  <th>Free</th>
			                  <th>Issued To</th>
			                  <th>Issued By</th>
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
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Code Information</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="CodeID" value="0" readonly>

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
		                <label class="col-md-12" style="font-weight: normal;">Center<span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="Center" type="text" class="form-control" value=""  placeholder="Center" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            </div>
	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Code Information</b></label>
		        	</div>
	        	</div>
	            <div class="row">
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Series No.</label>
		                <div class="col-md-12">
		                    <input id="SeriesNo" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-9">
		                <label class="col-md-12" style="font-weight: normal;">Code</label>
		                <div class="col-md-12">
		                    <input id="Code" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            </div>
	            <div class="row">
	            	<div class="col-md-9">
		                <label class="col-md-12" style="font-weight: normal;">Package</label>
		                <div class="col-md-12">
		                    <input id="Package" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Free?</label>
		                <div class="col-md-12">
		                    <input id="IsFreeCode" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            </div>
	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Other Information</b></label>
		        	</div>
	        	</div>
	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Issued To <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
		                    <input id="IssuedToMemberEntryID" type="hidden" class="form-control" value="" readonly>
							<input id="IssuedToMemberEntry" type="text" data-type="IssuedToMemberEntry" class="form-control autocomplete_txt"  autocomplete="off" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Date/Time Issued</label>
		                <div class="col-md-12">
		                    <input id="IssuedDateTime" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-9">
		                <label class="col-md-12" style="font-weight: normal;">Issued By</label>
		                <div class="col-md-12">
		                    <input id="IssuedBy" type="text" class="form-control" value="{{ Session('ADMIN_FULLNAME') }}" style="width:100%; font-weight:normal;" readonly>
		                </div>
	                </div>
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Issued Remarks</label>
		                <div class="col-md-12">
		                    <input id="IssuedRemarks" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            </div>
	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>User Information</b></label>
		        	</div>
	        	</div>
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">IBO Number</label>
		                <div class="col-md-12" style="font-weight: normal;">
							<input id="UsedByMemberEntryNo" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Member Name<span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
							<input id="UsedByMemberName" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="modal-footer">
					<div class="input-group pull-right">
						<span class="input-group-btn">
							<a id="btnProceed" href="#" class="btn btn-info btn-flat" onclick="ProceedIssue()"><i class="fa fa-save"></i> Proceed</a>
							<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Cancel</a>
						</span>
					</div>	
	            </div>

         	</div>

	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="cancel-code-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg" style="width: 90%;">
	    <div class="modal-content">

          	<div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Cancel Code</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="CancelCodeID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Are you sure you want to cancel this code?</label>
	            	</div>
	            </div>

	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Reason</label>
		                <div class="col-md-12">
		                    <input id="CancellationReason" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="modal-footer">
					<div class="input-group pull-right">
						<span class="input-group-btn">
							<a id="btnProceedCancel" href="#" class="btn btn-info btn-flat" onclick="CancelCodeNow()"><i class="fa fa-save"></i> Yes</a>
							<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> No</a>
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
	            "order": [[ 1, "asc" ], [ 0, "desc" ], [ 8, "asc" ], [ 6, "asc" ]]
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
					PageNo: vPageNo,
					Status: ''
				},
				url: "{{ route('get-code-generation-list') }}",
				dataType: "json",
				success: function(data){
			        $("#divLoader").hide();
					LoadRecordList(data.CodeGenerationList);
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

	    function LoadRecordList(vList){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadRecordRow(vList[x]);
	    		}
	    	}

	    }

	    function LoadRecordRow(vData){

	    	var tblList = $("#tblList").DataTable();

	    	tdID = vData.CodeID;
	    	tdSort = vData.SortOption;

			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='ViewInformation(" + vData.CodeID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> " + (vData.IssuedByID > 0 ? "View Information" : "Set Issuance Detail") + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";

	                           	@if($IsAllowCancelCode)
		                          	tdOption += " <li style='text-align:left;'> " +
		                              	" <a href='#' onclick='CancelCode(" + vData.CodeID + ")'>" + 
		                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Cancel Code</strong>" +
		                              	" </a> " +
		                           	" </li> ";
	                           	@endif

                      	tdOption +=
	                        " </ul> " +
	                    " </div> " ;

			tdBatchNo = "<span style='font-weight:normal;'>" + vData.BatchNo + "</span>";

			tdDateTimeGenerated = "<span style='font-weight:normal;'>" + vData.DateTimeGenerated + "</span>";
			tdCenter = "<span style='font-weight:normal;'>" + vData.CenterNo + " - " + vData.Center + "</span>";

			tdSeriesNo = "<span style='font-weight:normal;'>" + vData.SeriesNo + "</span>";
			tdCode = "<span style='font-weight:normal;'>" + vData.Code + "</span>";
			tdPackage = "<span style='font-weight:normal;'>" + vData.Package + "</span>";
			tdIsFreeCode = "<span style='font-weight:normal;'>" + (vData.IsFreeCode == 1 ? "Yes" : "No") + "</span>";

			tdIssuedTo = "<span style='font-weight:normal;'>" + vData.IssuedToEntryCode + " - " + vData.IssuedToMemberName + "</span>";
			tdIssuedBy = "<span style='font-weight:normal;'>" + vData.IssuedBy + "</span>";

			tdStatus = "";
			if(vData.Status == "{{ config('app.STATUS_AVAILABLE') }}"){
				tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}else{
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.Status + "</span>";
			}

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();

			    if(rowData[0] == vData.CodeID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdSort;
			    	curData[2] = tdOption;
			    	curData[3] = tdBatchNo;
			    	curData[4] = tdDateTimeGenerated;
			    	curData[5] = tdCenter;
			    	curData[6] = tdSeriesNo;
			    	curData[7] = tdCode;
			    	curData[8] = tdPackage;
			    	curData[9] = tdIsFreeCode;
			    	curData[10] = tdIssuedTo;
			    	curData[11] = tdIssuedBy;
			    	curData[12] = tdStatus;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdID,
						tdSort,
						tdOption,
						tdBatchNo,
						tdDateTimeGenerated, 
						tdCenter,
						tdSeriesNo,
						tdCode,
						tdPackage,
						tdIsFreeCode,
						tdIssuedTo,
						tdIssuedBy,
						tdStatus
					]).draw();			
			}

	    }

	    function Clearfields(){

			$("#CodeID").val('0');

			$("#BatchNo").val('');
			$("#DateTimeGenerated").val('');
			$("#Status").val('Approved').change();
			
			$("#Center").val('').change();

			$("#SeriesNo").val('');
			$("#Code").val('');
			$("#Package").val('');
			$("#IsFreeCode").val('No');

			$("#IssuedToMemberEntryID").val('0');
			$("#IssuedToMemberEntry").val('');
			$("#IssuedDateTime").val('{{ date("Y-m-d H:i:s") }}');
			$("#IssuedBy").val('{{ Session('ADMIN_FULLNAME') }}');
			$("#IssuedRemarks").val('');

			$("#UsedByMemberEntryNo").val('');
			$("#UsedByMemberName").val('');

			$("#IssuedToMemberEntry").prop('disabled', false);
			$("#IssuedRemarks").prop('disabled', false);

			$("#btnProceed").show();
	    }

	    function ViewInformation(vRecordID){

	    	if(vRecordID > 0){
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						CodeID: vRecordID
					},
					url: "{{ route('get-code-generation-info') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnProceed", "Proceed", false);

						Clearfields();

						if(data.Response =='Success' && data.CodeGenerationInfo != undefined){

							$("#CodeID").val(data.CodeGenerationInfo.CodeID);
							$("#BatchNo").val(data.CodeGenerationInfo.BatchNo);
							$("#DateTimeGenerated").val(data.CodeGenerationInfo.DateTimeGenerated);

							$("#Center").val(data.CodeGenerationInfo.Center);

							$("#SeriesNo").val(data.CodeGenerationInfo.SeriesNo);
							$("#Code").val(data.CodeGenerationInfo.Code);
							$("#Package").val(data.CodeGenerationInfo.Package);
							$("#IsFreeCode").val((data.CodeGenerationInfo.IsFreeCode == 1 ? "Yes" : "No"));

							if(data.CodeGenerationInfo.IssuedToMemberEntryID > 0){
								$("#IssuedToMemberEntryID").val(data.CodeGenerationInfo.IssuedToMemberEntryID);
								$("#IssuedToMemberEntry").val(data.CodeGenerationInfo.IssuedToMemberName);
								$("#IssuedDateTime").val(data.CodeGenerationInfo.IssuedDateTime);
								$("#IssuedBy").val(data.CodeGenerationInfo.IssuedBy);
								$("#IssuedRemarks").val(data.CodeGenerationInfo.IssuedRemarks);
							}

							$("#UsedByMemberEntryNo").val(data.CodeGenerationInfo.EntryCode);
							$("#UsedByMemberName").val(data.CodeGenerationInfo.UsedByMemberName);

							$("#IssuedToMemberEntry").prop('disabled', (data.CodeGenerationInfo.IssuedToMemberEntryID > 0 ? true : false));
							$("#IssuedRemarks").prop('disabled', (data.CodeGenerationInfo.IssuedToMemberEntryID > 0 ? true : false));

							if(data.CodeGenerationInfo.IssuedToMemberEntryID > 0){
								$("#btnProceed").hide();
							}else{
								$("#btnProceed").show();
							}

							$("#record-info-modal").modal();

						}else{
							showJSModalMessageJS("Batch Information",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
						buttonOneClick("btnProceed", "Proceed", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
						buttonOneClick("btnProceed", "", false);
					}
	        	});

	    	}

	    }

	    function ProceedIssue(){

			if($('#IssuedToMemberEntryID').val() == "" || $('#IssuedToMemberEntryID').val() == "0") {
				showJSMessage("Member Entry","Please select to whom you want to issue the code.","OK");
			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						CodeID: $("#CodeID").val(),
						IssuedToMemberEntryID: $("#IssuedToMemberEntryID").val(),
						IssuedRemarks: $("#IssuedRemarks").val()
					},
					url: "{{ route('do-issue-code-generation') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnProceed", "Proceed", false);
						if(data.Response =='Success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.CodeGenerationInfo);
						}else{
							showJSModalMessageJS("Code Issuance",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnProceed", "Proceed", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
				        $("#divLoader").show();
						buttonOneClick("btnProceed", "", true);
					}
	        	});
			}

	    };

	    function CancelCode(vCodeID){

	    	$("#CancelCodeID").val(vCodeID);
	    	$("#CancellationReason").val('');
			$("#cancel-code-modal").modal();

	    }

	    function CancelCodeNow(){

			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					CodeID: $("#CancelCodeID").val(),
					CancellationReason: $("#CancellationReason").val()
				},
				url: "{{ route('do-cancel-code') }}",
				dataType: "json",
				success: function(data){
			        $("#divLoader").hide();
					buttonOneClick("btnProceedCancel", "Yes", false);
					if(data.Response =='Success'){
						$("#record-info-modal").modal('hide');
						showMessage("Success",data.ResponseMessage);
						LoadRecordRow(data.CodeGenerationInfo);
					}else{
						showJSModalMessageJS("Cancel Code",data.ResponseMessage,"OK");
					}
				},
				error: function(data){
			        $("#divLoader").hide();
					buttonOneClick("btnProceedCancel", "Yes", false);
					console.log(data.responseText);
				},
				beforeSend:function(vData){
			        $("#divLoader").show();
					buttonOneClick("btnProceedCancel", "", true);
				}
        	});

	    };

		//autocomplete script
	    $(document).on('focus','.autocomplete_txt',function(){

	    	if($(this).data('type') == "IssuedToMemberEntry"){

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

		            select: function( event, ui ) {

		                var data = ui.item.data.split("|");

		                $('#IssuedToMemberEntryID').val(data[0]);
		                $('#IssuedToMemberEntry').val(data[1] + " - " + data[2]);
		            }
		        });

	    	}

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
