@extends('layout.adminweb')

@section('content')
	
	@php($CompanyName = "")

	@php($CompanyAddress = "")  
	@php($TelNo = "")  
	@php($MobileNo = "")  
	@php($EmailAddress = "")  

	@php($AboutCompany = "")  
	@php($Mission = "")  
	@php($Vision = "")  

	@if(isset($CompanyInfo))

		@php($CompanyName = $CompanyInfo->CompanyName)  

		@php($CompanyAddress = $CompanyInfo->CompanyAddress)  
		@php($TelNo = $CompanyInfo->TelNo)  
		@php($MobileNo = $CompanyInfo->MobileNo)  
		@php($EmailAddress = $CompanyInfo->EmailAddress)  

		@php($AboutCompany = $CompanyInfo->AboutCompany)  
		@php($Mission = $CompanyInfo->Mission)  
		@php($Vision = $CompanyInfo->Vision)  

	@endif


	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Company Information
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-success">

		            <div class="box-body table-responsive" style="min-height: 600px;">

			            <div class="row">
				            <div class="col-md-12">
					    		@include('inc.admin.adminmessage')
				            </div>
			            </div>

			            <div class="row">
			            	<div class="col-md-12">
				                <label class="col-md-12" style="font-weight: normal;">Company Name</label>
				                <div class="col-md-12">
				                    <input id="CompanyName" type="text" class="form-control" value="{{ $CompanyName }}" placeholder="Company Name" style="width:100%; font-weight: normal;" required>
				                </div>
			            	</div>
			            </div>
			            <div class="row">
			            	<div class="col-md-12">
				                <label class="col-md-12" style="font-weight: normal;">More About The Company <span style="color:red;">*</span></label>
				                <div class="col-md-12">
	                				<textarea id="AboutCompany" class="form-control wysiwyg" cols="40" rows="8"  placeholder="More About The Company" style="width:100%;" required>{{ $AboutCompany }}</textarea>
				                </div>
			            	</div>
			            </div>

			            <div style="clear:both;"></div>
			            <br>                
			            <div class="row">
			            	<div class="col-md-12">
				            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Company Address and Contact Information</b></label>
				        	</div>
			        	</div>
			            <div class="row">
			            	<div class="col-md-12">
				                <label class="col-md-12" style="font-weight: normal;">Complete Address <span style="color:red;">*</span></label>
				                <div class="col-md-12">
				                    <input id="CompanyAddress" type="text" class="form-control" value="{{ $CompanyAddress }}" style="width:100%; font-weight: normal;" required>
				                </div>
			            	</div>
		            	</div>

			            <div class="row">
			            	<div class="col-md-4">
				                <label class="col-md-12" style="font-weight: normal;">Telephone No.</label>
				                <div class="col-md-12">
				                    <input id="TelNo" type="text" class="form-control" value="{{ $TelNo }}" style="width:100%; font-weight: normal;" required>
				                </div>
			            	</div>
			            	<div class="col-md-4">
				                <label class="col-md-12" style="font-weight: normal;">Mobile No. <span style="color:red;">*</span></label>
				                <div class="col-md-12">
				                    <input id="MobileNo" type="text" class="form-control" value="{{ $MobileNo }}" style="width:100%; font-weight: normal;" required>
				                </div>
			            	</div>
			            	<div class="col-md-4">
				                <label class="col-md-12" style="font-weight: normal;">Email Address <span style="color:red;">*</span></label>
				                <div class="col-md-12">
				                    <input id="EmailAddress" type="text" class="form-control" value="{{ $EmailAddress }}" style="width:100%; font-weight: normal;" required>
				                </div>
			            	</div>
			            </div>

			            <div style="clear:both;"></div>
			            <br>                
			            <div class="row">
			            	<div class="col-md-12">
				            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Mission and Vision</b></label>
				        	</div>
			        	</div>
			            <div class="row">
			            	<div class="col-md-12">
				                <label class="col-md-12" style="font-weight: normal;">Mission <span style="color:red;">*</span></label>
				                <div class="col-md-12">
	                				<textarea id="Mission" class="form-control wysiwyg" cols="40" rows="8"  placeholder="Mission" style="width:100%;" required>{{ $Mission }}</textarea>
				                </div>
			            	</div>
			            </div>
			            <div class="row">
			            	<div class="col-md-12">
				                <label class="col-md-12" style="font-weight: normal;">Vision <span style="color:red;">*</span></label>
				                <div class="col-md-12" style="font-weight: normal;">
	                				<textarea id="Vision" class="form-control wysiwyg" cols="40" rows="8"  placeholder="Vision" style="width:100%;" required>{{ $Vision }}</textarea>
				                </div>
			            	</div>
			        	</div>

	                    <div style="clear:both;"></div>
	                    <br>
	                    <div class="col-md-12">
	                        <button id="btnSave" type="button" onclick="SaveRecord()" class="btn btn-info one-click">Save</button>
	                    </div>
	                    <div style="clear:both;"></div>
	                    <br><br>

		            </div>

          		</div>
          	</div>
		</div>
	</section>
	<!-- /.content -->	

	<script type="text/javascript">

	    function SaveRecord(){

			if($('#CompanyName').val() == "") {
				showJSMessage("Company Name","Please enter company name.","OK");
			}else if(window.parent.tinymce.get('AboutCompany').getContent() == "") {
				showJSMessage("About The Company","Please tell more about the company.","OK");

			}else if($('#Address').val() == "") {
				showJSMessage("Complete Address","Please enter complete address.","OK");
			}else if($('#Telephone').val() == "") {
				showJSMessage("Tel. No.","Please enter telephone number.","OK");
			}else if($('#MobileNo').val() == "") {
				showJSMessage("Mobile No.","Please enter active mobile number.","OK");
			}else if($('#EmailAddress').val() == "") {
				showJSMessage("Email Address","Please enter email address.","OK");

			}else if(window.parent.tinymce.get('Mission').getContent() == "") {
				showJSMessage("Company Mission","Please enter company mission.","OK");
			}else if(window.parent.tinymce.get('Vision').getContent() == "") {
				showJSMessage("Company Vision","Please enter company vision.","OK");
			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						CompanyName: $("#CompanyName").val(),
						AboutCompany: window.parent.tinymce.get('AboutCompany').getContent(),

						CompanyAddress: $("#CompanyAddress").val(),
						TelNo: $("#TelNo").val(),
						MobileNo: $("#MobileNo").val(),
						EmailAddress: $("#EmailAddress").val(),

						Mission: window.parent.tinymce.get('Mission').getContent(),
						Vision: window.parent.tinymce.get('Vision').getContent()
					},
					url: "{{ route('do-save-company-info') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							showMessage("Success",data.ResponseMessage);
						}else{
							showJSModalMessageJS("Save Company Information",data.ResponseMessage,"OK");
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

	</script>



@endsection
