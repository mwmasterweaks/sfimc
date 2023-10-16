@extends('layout.adminweb')

@section('content')

@php($IsAllowCancelCode = Session('IS_SUPER_ADMIN')==true ? 'true' : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Allow Cancel Code'))  
  
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Wire Code Distribution
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Wire Code Distribution</li>
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
			                  <th></th>
			                  <th>Wirecode gen ID</th>
			                  <th>Date Gen.</th>
			                  <th>Center</th>
			                  <th>Code</th>
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
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Wirecode Gen ID <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="wirecode_gen_id" type="text" class="form-control" placeholder="Auto Generated" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Date Generated<span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="date_gen" type="text" class="form-control" value=""  placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            </div>
	            <div style="clear:both;"></div>
	            <br>      

				<div class="row">
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Center <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="txtbox_center" type="text" class="form-control" placeholder="Auto Generated" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Code<span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="code" type="text" class="form-control" value=""  placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
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
	            	
	            </div>
	            <div style="clear:both;"></div>
	            <br>                
	          
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
	            "order": [[ 8, "asc" ], [0, "asc"]]
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
				url: "{{ route('get-wirecode-list') }}",
				dataType: "json",
				success: function(data){
			        $("#divLoader").hide();
					console.log(data)
					LoadRecordList(data);
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

	    	td1 = vData.id;

			td2 = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='ViewInformation(" + JSON.stringify(vData)  + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> " + (vData.issued_by > 0 ? "View Information" : "Set Issuance Detail") + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";
	                        " </ul> " +
	                    " </div> " ;

			td3 = "<span style='font-weight:normal;'>" + vData.wirecode_gen_id + "</span>";

			td4 = "<span style='font-weight:normal;'>" + vData.wirecode_gen.date_gen + "</span>";
			td5 = "<span style='font-weight:normal;'>" + vData.wirecode_gen.center.CenterNo + " - " + vData.wirecode_gen.center.Center + "</span>";

			td6 = "<span style='font-weight:normal;'>" + vData.code + "</span>";

			if(vData.issued_to != null)
				td7 = "<span style='font-weight:normal;'>" + vData.issued_to.member_entry.EntryCode + " - " + vData.issued_to.FirstName + " " + vData.issued_to.LastName + "</span>";
			else
				td7 = "<span style='font-weight:normal;'>-</span>";
			
			if(vData.issued_by != null)
				td8 = "<span style='font-weight:normal;'>" + vData.issued_by.Fullname + "</span>";
			else
				td8 = "<span style='font-weight:normal;'> - </span>";


			td9 = "";
			if(vData.status == "available"){
				td9 += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.status + "</span>";
			}else if(vData.status == "issued"){
				td9 += "<span class='label' style='font-weight:normal; text-align:center; background-color:#fcbb17;'>" + vData.status + "</span>";
			}else{
				td9 += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.status + "</span>";
			}

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();

			    if(rowData[0] == vData.CodeID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = td1;
			    	curData[1] = td2;
			    	curData[2] = td3;
			    	curData[3] = td4;
			    	curData[4] = td5;
			    	curData[5] = td6;
			    	curData[6] = td7;
			    	curData[7] = td8;
			    	curData[8] = td9;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						td1,
						td2,
						td3,
						td4,
						td5,
						td6,
						td7,
						td8,
						td9
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

	    function ViewInformation(vData){

			
			$("#CodeID").val(vData.id);
			$("#wirecode_gen_id").val(vData.wirecode_gen_id);
			$("#date_gen").val(vData.wirecode_gen.date_gen);
			$("#txtbox_center").val(vData.wirecode_gen.center.Center);
			$("#code").val(vData.code);
			
			IssuedToMemberEntry

			if(vData.status != "available"){
				$("#btnProceed").hide();
				$("#IssuedToMemberEntry").prop('disabled', true);
				$("#UsedByMemberEntryNo").val(vData.issued_by);
				$("#UsedByMemberName").val(vData.issued_by);

			}else{
				$("#btnProceed").show();
				$("#IssuedToMemberEntry").prop('disabled', false);
				$("#IssuedToMemberEntry").val('');
				$("#UsedByMemberEntryNo").val('');
				$("#UsedByMemberName").val('');
			}
			$("#record-info-modal").modal();
			
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
						IssuedToMemberEntryID: $("#IssuedToMemberEntryID").val()
					},
					url: "{{ route('do-issue-wirecode') }}",
					dataType: "json",
					success: function(data){
						
						console.log(data);
				        $("#divLoader").hide();
						buttonOneClick("btnProceed", "Proceed", false);
						if(data.Response =='success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success","Issued successfully!");
							$("#tblList").DataTable().clear().draw();
							getRecordList(1, '');
						}else{
							showJSModalMessageJS("Code Issuance",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnProceed", "Proceed", false);
						console.log(data);
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
