@extends('layout.adminweb')

@section('content')
  
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Center Management
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Center Management</li>
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
			                  <th>Center No.</th>
			                  <th>Center Name</th>
			                  <th>Incharge</th>
			                  <th>Tel. No.</th>
			                  <th>Mobile No.</th>
			                  <th>Email Address</th>
			                  <th>Address</th>
			                  <th>Updated By</th>
			                  <th>Date/Time Updated</th>
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
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Center Information</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="CenterID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Center No.</label>
		                <div class="col-md-12">
		                    <input id="CenterNo" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Center</label>
		                <div class="col-md-12">
		                    <input id="Center" type="text" class="form-control" value="" placeholder="Center" style="width:100%; font-weight: normal;">
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
	            	<div class="col-md-12">
		            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Incharge Information</b></label>
		        	</div>
	        	</div>
	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Incharge <span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<select id="Incharge" class="form-control select2" style="width: 100%; height: 100px; font-weight: normal;">
								<option value="">Please Select</option>
								@foreach ($UserAccountList as $uac)
									<option value="{{ $uac->UserAccountID }}"
										>{{ $uac->Fullname }}</option>
								@endforeach
							</select>
		                </div>
	            	</div>
	            </div>
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Telephone No.</label>
		                <div class="col-md-12">
		                    <input id="TelNo" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Mobile No. <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="MobileNo" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Email Address <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="EmailAddress" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            </div>
	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Address Information</b></label>
		        	</div>
	        	</div>
	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Address <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="Address" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">City <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
							<select id="City" class="form-control select2" style="width: 100%; height: 100px; font-weight: normal;">
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
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">State/Province</label>
		                <div class="col-md-12">
		                    <input id="StateProvince" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            </div>
	            <div class="row">
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Zip Code</label>
		                <div class="col-md-12">
		                    <input id="ZipCode" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-9">
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
					SearchText: vSearchText,
					PageNo: vPageNo,
					Status: ''
				},
				url: "{{ route('get-center-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.CenterList);
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

	    	tdID = vData.CenterID;

			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='EditInformation(" + vData.CenterID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Edit Information " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";
            	tdOption += " </ul> " +
	                    " </div> " ;
			tdCenterNo = "<span style='font-weight:normal;'>" + vData.CenterNo + "</span>";
			tdCenter = "<span style='font-weight:normal;'>" + vData.Center + "</span>";

			tdIncharge = "<span style='font-weight:normal;'>" + vData.Incharge + "</span>";
			tdTelNo = "<span style='font-weight:normal;'>" + vData.TelNo + "</span>";
			tdMobileNo = "<span style='font-weight:normal;'>" + vData.MobileNo + "</span>";
			tdEmailAddress = "<span style='font-weight:normal;'>" + vData.EmailAddress + "</span>";
			
			tdAddress = "<span style='font-weight:normal;'>" + vData.Address + ", " + vData.City + ", " + vData.StateProvince + " " + vData.ZipCode + " " + vData.Country + "</span>";

			tdUpdatedBy = "<span style='font-weight:normal;'>" + vData.UpdatedBy + "</span>";
			tdDateTimeUpdated = "<span style='font-weight:normal;'>" + vData.DateTimeUpdated + "</span>";

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

			    if(rowData[0] == vData.CenterID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdOption;
			    	curData[2] = tdCenterNo;
			    	curData[3] = tdCenter;
			    	curData[4] = tdIncharge;
			    	curData[5] = tdTelNo;
			    	curData[6] = tdMobileNo;
			    	curData[7] = tdEmailAddress;
			    	curData[8] = tdAddress;
			    	curData[9] = tdUpdatedBy;
			    	curData[10] = tdDateTimeUpdated;
			    	curData[11] = tdStatus;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
			    	tdID,
			    	tdOption,
			    	tdCenterNo,
			    	tdCenter,
			    	tdIncharge,
			    	tdTelNo,
			    	tdMobileNo,
			    	tdEmailAddress,
			    	tdAddress,
			    	tdUpdatedBy,
			    	tdDateTimeUpdated,
			    	tdStatus
				]).draw();			
			}

	    }

	    function Clearfields(){

			$("#CenterID").val('0');

			$("#CenterNo").val('');
			$("#Center").val('');
			$("#Status").val('Active').change();
	    	
	    	$("#Incharge").val('').change();
			$("#TelNo").val('');
			$("#MobileNo").val('');
			$("#EmailAddress").val('');

			$("#Address").val('');
			$("#City").val('').change();
			$("#StateProvince").val('');
			$("#ZipCode").val('');
			$("#Country").val(174).change();

			$("#btnSave").show();

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
						CenterID: vRecordID
					},
					url: "{{ route('get-center-info') }}",
					dataType: "json",
					success: function(data){

						if(data.Response =='Success' && data.CenterInfo != undefined){

								$("#CenterID").val(data.CenterInfo.CenterID);

								$("#CenterNo").val(data.CenterInfo.CenterNo);
								$("#Center").val(data.CenterInfo.Center);
								$("#Status").val(data.CenterInfo.Status).change();
						    	
						    	$("#Incharge").val(data.CenterInfo.InchargeID).change();
								$("#TelNo").val(data.CenterInfo.TelNo);
								$("#MobileNo").val(data.CenterInfo.MobileNo);
								$("#EmailAddress").val(data.CenterInfo.EmailAddress);

								$("#Address").val(data.CenterInfo.Address);
								$("#City").val(data.CenterInfo.CityID).change();
								$("#StateProvince").val(data.CenterInfo.StateProvince);
								$("#ZipCode").val(data.CenterInfo.ZipCode);
								$("#Country").val(data.CenterInfo.CountryID).change();

								$("#record-info-modal").modal();

						}else{
							showJSModalMessageJS("Center Information",data.ResponseMessage,"OK");
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

	    $("#City").change(function(){

	      if($("#City").find('option:selected').data('cityprovince') != undefined){
	        $("#StateProvince").val($("#City").find('option:selected').data('cityprovince'));
	      }

	      if($("#City").find('option:selected').data('cityzipcode') != undefined){
	        $("#ZipCode").val($("#City").find('option:selected').data('cityzipcode'));
	      }
	 
	    });

	    function SaveRecord(){

			if($('#Center').val() == "") {
				showJSMessage("Center","Please enter center name.","OK");
			}else if($('#Incharge').val() == "") {
				showJSMessage("Center","Please select incharge.","OK");

			}else if($('#TelNo').val() == "" && $('#MobileNo').val() == "") {
				showJSMessage("Center","Please enter enter telephone or mobile number.","OK");
			}else if($('#EmailAddress').val() == "") {
				showJSMessage("Center","Please enter member email address.","OK");

			}else if($('#Address').val() == "") {
				showJSMessage("Center","Please enter member address.","OK");
			}else if($('#City').val() == "") {
				showJSMessage("Center","Please select member city address.","OK");
			}else if($('#Country').val() == "") {
				showJSMessage("Center","Please select member country address.","OK");
			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						CenterID: $("#CenterID").val(),

						CenterNo: $("#CenterNo").val(),
						Center: $("#Center").val(),

						InchargeID: $("#Incharge").val(),
						TelNo: $("#TelNo").val(),
						MobileNo: $("#MobileNo").val(),
						EmailAddress: $("#EmailAddress").val(),

						Address: $("#Address").val(),
						CityID: $("#City").val(),
						StateProvince: $("#StateProvince").val(),
						ZipCode: $("#ZipCode").val(),
						CountryID: $("#Country").val(),

						Status: $("#Status").val()
					},
					url: "{{ route('do-save-center') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.CenterInfo);
						}else{
							showJSModalMessageJS("Save Center",data.ResponseMessage,"OK");
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
