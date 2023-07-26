@extends('layout.adminweb')

@section('content')
  
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Member Voucher
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Member Voucher</li>
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
			                  <th>IBO Number</th>
			                  <th>Member</th>
			                  <th>Voucher Code</th>
			                  <th>Voucher Amount</th>
			                  <th>Nth Pair</th>
			                  <th>Used On</th>
			                  <th>Date/Time Used</th>
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
	  <div class="modal-dialog modal-lg" style="width: 90%;">
	    <div class="modal-content">

          	<div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Member Voucher Information</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="VoucherID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Voucher Code</label>
		                <div class="col-md-12">
		                    <input id="VoucherCode" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Date/Time</label>
		                <div class="col-md-12">
		                    <input id="VoucherDateTime" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Status</label>
		                <div class="col-md-12" style="font-weight: normal;">
		                    <input id="Status" type="text" class="form-control" value=""style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            </div>
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Voucher Amount</label>
		                <div class="col-md-12" style="font-weight: normal;">
		                    <input id="VoucherAmount" type="text" class="form-control" value=""style="width:100%; font-weight: normal;" readonly>
		                </div>
	                </div>
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Remarks</label>
		                <div class="col-md-12" style="font-weight: normal;">
		                    <input id="Remarks" type="text" class="form-control" value=""style="width:100%; font-weight: normal;" readonly>
		                </div>
	                </div>
	        	</div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Member Information</b></label>
		        	</div>
	        	</div>
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">IBO Number</label>
		                <div class="col-md-12">
		                    <input id="EntryCode" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Member Name <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="MemberName" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            </div>

	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Telephone No.</label>
		                <div class="col-md-12">
		                    <input id="TelNo" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Mobile No. <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="MobileNo" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Email Address <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="EmailAddress" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            </div>

				<div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Usage Information</b></label>
		        	</div>
	        	</div>
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Order No.</label>
		                <div class="col-md-12">
		                    <input id="OrderNo" type="text" class="form-control" value="" placeholder="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Order Date/Time</label>
		                <div class="col-md-12">
		                    <input id="OrderDateTime" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="modal-footer">
					<div class="input-group pull-right">
						<span class="input-group-btn">
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
	            "order": [[ 3, "asc" ]]
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
					MemberEntryID:0,
					SearchText: vSearchText,
					PageNo: vPageNo,
					Status: ''
				},
				url: "{{ route('get-member-voucher-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.MemberVoucherList);
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

	    function LoadRecordList(vList){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadRecordRow(vList[x]);
	    		}
	    	}

	    }

	    function LoadRecordRow(vData){

	    	var tblList = $("#tblList").DataTable();

	    	tdID = vData.VoucherID;

			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='ViewMemberVoucher(" + vData.VoucherID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> View Voucher Info " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";
            	tdOption += " </ul> " +
	                    " </div> " ;

			tdEntryCode = "<span style='font-weight:normal;'>" + vData.EntryCode + "</span>";
			tdMember = "<span style='font-weight:normal;'>" + vData.MemberName + "</span>";

			tdVoucherCode = "<span style='font-weight:normal;'>" + vData.VoucherCode + "</span>";
			tdVoucherAmount = "<span style='font-weight:normal;'>" + FormatDecimal(vData.VoucherAmount,2) + "</span>";
			tdNthPair = "<span style='font-weight:normal;'>" + vData.NthPair + "</span>";

			tdOrderNo = "<span style='font-weight:normal;'>" + vData.OrderNo + "</span>";
			tdOrderDateTime = "<span style='font-weight:normal;'>" + vData.OrderDateTime + "</span>";

			tdStatus = "";
			if(vData.Status == "Available"){
				tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}else{
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.Status + "</span>";
			}

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();

			    if(rowData[0] == vData.VoucherID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdOption;

			    	curData[2] = tdEntryCode;
			    	curData[3] = tdMember;

			    	curData[4] = tdVoucherCode;
			    	curData[5] = tdVoucherAmount;
			    	curData[6] = tdNthPair;

			    	curData[7] = tdOrderNo;
			    	curData[8] = tdOrderDateTime;
			    	curData[9] = tdStatus;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdID,
						tdOption,
						tdEntryCode, 
						tdMember,
						tdVoucherCode,
						tdVoucherAmount,
						tdNthPair,
						tdOrderNo,
						tdOrderDateTime,
						tdStatus
					]).draw();			
			}

	    }

	    function Clearfields(){

			$("#VoucherID").val('0');

			$("#VoucherCode").val('');
			$("#VoucherDateTime").val('');
			$("#Status").val('Active');
	    	
	    	$("#VoucherAmount").val('');
			$("#Remarks").val('');

			$("#EntryCode").val('');
			$("#MemberName").val('');

			$("#TelNo").val('');
			$("#MobileNo").val('');
			$("#EmailAddress").val('');

			$("#OrderNo").val('');
			$("#OrderDateTime").val('');			

	    }

	    function ViewMemberVoucher(vRecordID){

	    	if(vRecordID > 0){

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						VoucherID: vRecordID
					},
					url: "{{ route('get-member-voucher-info') }}",
					dataType: "json",
					success: function(data){

						Clearfields();

						if(data.Response =='Success' && data.MemberVoucherInfo != undefined){

								$("#VoucherID").val(data.MemberVoucherInfo.VoucherID);

								$("#VoucherCode").val(data.MemberVoucherInfo.VoucherCode);
								$("#VoucherDateTime").val(data.MemberVoucherInfo.VoucherDateTime);
								$("#Status").val(data.MemberVoucherInfo.Status);
						    	
						    	$("#VoucherAmount").val(FormatDecimal(data.MemberVoucherInfo.VoucherAmount,2));
								$("#Remarks").val(data.MemberVoucherInfo.Remarks);

								$("#EntryCode").val(data.MemberVoucherInfo.EntryCode);
								$("#MemberName").val(data.MemberVoucherInfo.MemberName);

								$("#EmailAddress").val(data.MemberVoucherInfo.EmailAddress);
								$("#TelNo").val(data.MemberVoucherInfo.TelNo);
								$("#MobileNo").val(data.MemberVoucherInfo.MobileNo);

								$("#OrderNo").val(data.MemberVoucherInfo.OrderNo);
								$("#OrderDateTime").val(data.MemberVoucherInfo.OrderDateTime);
								
								$("#record-info-modal").modal();

						}else{
							showJSModalMessageJS("Member Voucher Information",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
						console.log(data.responseText);
					},
					beforeSend:function(vData){
					}
	        	});

	    	}

	    }

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
