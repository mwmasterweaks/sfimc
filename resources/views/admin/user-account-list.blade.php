@extends('layout.adminweb')

@section('content')
  
<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			User Account List
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>User Account List</li>
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
			                  <th>Account Type</th>
			                  <th>Station</th>
			                  <th>Full Name</th>
			                  <th>Username</th>
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
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>User Account Information</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="UserAccountID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Account Type <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
							<select id="AccountType" class="form-control select2" style="width: 100%; font-weight: normal;">
								<option value="Admin User" selected>Admin User</option>
								<option value="Station User">Station User</option>
							</select>
		                </div>
	            	</div>
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Station <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
							<select id="Station" class="form-control select2" style="width: 100%; height: 100px; font-weight: normal;">
								<option value="">Please Select</option>
								@foreach ($StationList as $skey)
									<option value="{{ $skey->StationID }}"
										>{{ $skey->StationName }}</option>
								@endforeach
							</select>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Full Name <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="FullName" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
							<select id="Status" class="form-control select2" style="width: 100%; font-weight: normal;">
								<option value="Active" selected>Active</option>
								<option value="Inactive">Inactive</option>
							</select>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Username <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="Username" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Password <span style="color:red;">*</span><span id="spnPassword" style="color:red;">(Please leave empty to keep password unchanged)</span></label>
		                <div class="col-md-12">
		                    <input id="UserPassword" type="password" class="form-control" value="" style="width:100%; font-weight: normal;" required>
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
	            "order": [[ 4, "asc" ]]
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
				url: "{{ route('get-user-account-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.UserAccountList);
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

	    	tdID = vData.UserAccountID;

			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn btn-success' data-toggle='dropdown'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='EditInformation(" + vData.UserAccountID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Edit Account " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> " +
	                        " </ul> " +
	                    " </div> " ;
			tdAccountType = "<span style='font-weight:normal;'>" + vData.AccountType + "</span>";
			tdStation = "<span style='font-weight:normal;'>" + vData.StationName + "</span>";
			tdFullname = "<span style='font-weight:normal;'>" + vData.Fullname + "</span>";
			tdUsername = "<span style='font-weight:normal;'>" + vData.Username + "</span>";

			tdStatus = "";
			if(vData.Status == "Active"){
				tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}else{
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.Status + "</span>";
			}

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();

			    if(rowData[0] == vData.UserAccountID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdOption;
			    	curData[2] = tdAccountType;
			    	curData[3] = tdStation;
			    	curData[4] = tdFullname;
			    	curData[5] = tdUsername;
			    	curData[6] = tdStatus;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdID,
						tdOption,
						tdAccountType,
						tdStation, 
						tdFullname,
						tdUsername,
						tdStatus
					]).draw();			
			}

	    }

	    function Clearfields(){

			$("#UserAccountID").val('0');
			$("#AccountType").val('');
			$("#Station").val('').change();

			$("#FullName").val('');
			$("#Status").val('Active').change();

			$("#Username").val('');
			$("#UserPassword").val('');
			
			$("#spnPassword").hide();

	    }

	    function NewRecord(){

	    	isNewRecord = 1;
			Clearfields();
			$("#record-info-modal").modal();
	    }

	    function EditInformation(vRecordID){

	    	if(vRecordID > 0){

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						UserAccountID: vRecordID
					},
					url: "{{ route('get-user-account-info') }}",
					dataType: "json",
					success: function(data){
						if(data.Response =='Success' && data.UserAccountInfo != undefined){

							$("#UserAccountID").val(data.UserAccountInfo.UserAccountID);
							$("#AccountType").val(data.UserAccountInfo.AccountType);
							$("#Station").val(data.UserAccountInfo.StationID).change();

							$("#FullName").val(data.UserAccountInfo.Fullname);
							$("#Status").val(data.UserAccountInfo.Status).change();

							$("#Username").val(data.UserAccountInfo.Username);
							$("#UserPassword").val('');

							$("#spnPassword").show();
							
							$("#record-info-modal").modal();
							buttonOneClick("btnSave", "Save", false);

						}else{
							showJSModalMessageJS("User Account",data.ResponseMessage,"OK");
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

	    function SaveRecord(){

			if($('#AccountType').val() == "") {
				showJSMessage("Account Type","Please select account type.","OK");
			}else if($('#Station').val() == "") {
				showJSMessage("Station","Please select designated station.","OK");
			}else if($('#FullName').val() == "") {
				showJSMessage("Full Name","Please enter full name.","OK");
			}else if($('#Username').val() == "") {
				showJSMessage("Username","Please enter Username.","OK");
			}else if($('#UserAccountID').val() <= 0 && $('#UserPassword').val() == "") {
				showJSMessage("User Password","Please enter user password.","OK");
			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						UserAccountID: $("#UserAccountID").val(),
						AccountType: $("#AccountType").val(),
						StationID: $("#Station").val(),

						Fullname: $("#FullName").val(),
						Status: $("#Status").val(),

						Username: $("#Username").val(),
						Password: $("#UserPassword").val()

					},
					url: "{{ route('do-save-user-account') }}",
					dataType: "json",
					success: function(data){
						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.UserAccountInfo);
						}else{
							showJSModalMessageJS("Save User Account",data.ResponseMessage,"OK");
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
