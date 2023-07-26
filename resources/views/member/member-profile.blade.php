@extends('layout.memberweb')

@section('content')
	
	@php($EntryID = 0)  
	@php($EntryCode = "")  
	@php($EntryDateTime = "")  
	@php($Status = "")  

	@php($MemberID = 0)  
	@php($FirstName = "")  
	@php($LastName = "")  
	@php($MiddleName = "")  

	@php($TelNo = "")  
	@php($MobileNo = "")  
	@php($EmailAddress = "")  

	@php($Address = "")  
	@php($CityID = 0)  
	@php($City = "")  
	@php($StateProvince = "")  
	@php($ZipCode = "")  
	@php($CountryID = 0)  
	@php($Country = "")  

	@php($Code = "")  
	@php($PackageID = 0)  
	@php($Package = "")  
	@php($IsFreeCode = 0)  

	@php($ParentEntryID = 0)  
	@php($ParentEntryCode = "")  
	@php($ParentEntryMemberName = "")  
	@php($ParentPosition = "")  

	@php($SponsorEntryID = 0)
	@php($SponsorEntryCode = "")  
	@php($SponsorMemberName = "")  

	@if(isset($MemberInfo))
		@php($EntryID = $MemberInfo->EntryID)  
		@php($EntryCode = $MemberInfo->EntryCode)  
		@php($EntryDateTime = $MemberInfo->EntryDateTime)  
		@php($Status = $MemberInfo->Status)  

		@php($MemberID = $MemberInfo->MemberID)  
		@php($FirstName = $MemberInfo->FirstName)  
		@php($LastName = $MemberInfo->LastName)  
		@php($MiddleName = $MemberInfo->MiddleName)  

		@php($TelNo = $MemberInfo->TelNo)  
		@php($MobileNo = $MemberInfo->MobileNo)  
		@php($EmailAddress = $MemberInfo->EmailAddress)  

		@php($Address = $MemberInfo->Address)  
		@php($CityID = $MemberInfo->CityID)  
		@php($City = $MemberInfo->City)  
		@php($StateProvince = $MemberInfo->StateProvince)  
		@php($ZipCode = $MemberInfo->ZipCode)  
		@php($CountryID = $MemberInfo->CountryID)  
		@php($Country = $MemberInfo->Country)  

		@php($Code = $MemberInfo->Code)  
		@php($PackageID = $MemberInfo->PackageID)  
		@php($Package = $MemberInfo->Package)  
		@php($IsFreeCode = $MemberInfo->IsFreeCode)  

		@php($ParentEntryID = $MemberInfo->ParentEntryID)  
		@php($ParentEntryCode = $MemberInfo->ParentEntryCode)  
		@php($ParentEntryMemberName = $MemberInfo->ParentEntryMemberName)  
		@php($ParentPosition = $MemberInfo->ParentPosition)  

		@php($SponsorEntryID = $MemberInfo->SponsorEntryID)
		@php($SponsorEntryCode = $MemberInfo->SponsorEntryCode)  
		@php($SponsorMemberName = $MemberInfo->SponsorMemberName)  
	@endif
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Member Profile
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
			            	<div class="col-md-3">
				                <label class="col-md-12" style="font-weight: normal;">IBO Number</label>
				                <div class="col-md-12">
				                    <input id="EntryCode" type="text" class="form-control" value="{{ $EntryCode }}" placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
				                </div>
			            	</div>
			            	<div class="col-md-3">
				                <label class="col-md-12" style="font-weight: normal;">Date/Time</label>
				                <div class="col-md-12">
				                    <input id="EntryDateTime" type="text" class="form-control" value="{{ $EntryDateTime }}" placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
				                </div>
			            	</div>
			            	<div class="col-md-3">
				                <label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
				                <div class="col-md-12" style="font-weight: normal;">
				                    <input id="Status" type="text" class="form-control" value="{{ $Status }}" style="width:100%; font-weight: normal;" readonly>
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
				                <label class="col-md-12" style="font-weight: normal;">First Name <span style="color:red;">*</span></label>
				                <div class="col-md-12">
				                    <input id="FirstName" type="text" class="form-control" value="{{ $FirstName }}" style="width:100%; font-weight: normal;" readonly>
				                </div>
			            	</div>
			            	<div class="col-md-4">
				                <label class="col-md-12" style="font-weight: normal;">Last Name <span style="color:red;">*</span></label>
				                <div class="col-md-12">
				                    <input id="LastName" type="text" class="form-control" value="{{ $LastName }}" style="width:100%; font-weight: normal;" readonly>
				                </div>
			            	</div>
			            	<div class="col-md-4">
				                <label class="col-md-12" style="font-weight: normal;">Middle Name <span style="color:red;">*</span></label>
				                <div class="col-md-12">
				                    <input id="MiddleName" type="text" class="form-control" value="{{ $MiddleName }}" style="width:100%; font-weight: normal;" readonly>
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
				            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Address Information</b></label>
				        	</div>
			        	</div>
			            <div class="row">
			            	<div class="col-md-12">
				                <label class="col-md-12" style="font-weight: normal;">Address <span style="color:red;">*</span></label>
				                <div class="col-md-12">
				                    <input id="Address" type="text" class="form-control" value="{{ $Address }}" style="width:100%; font-weight: normal;" required>
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
				                                {{ $CityID == $ckey->CityID ? "selected" : "" }}
												>{{ $ckey->City }}</option>
										@endforeach
									</select>
				                </div>
			            	</div>
			            	<div class="col-md-6">
				                <label class="col-md-12" style="font-weight: normal;">State/Province</label>
				                <div class="col-md-12">
				                    <input id="StateProvince" type="text" class="form-control" value="{{ $StateProvince }}" style="width:100%; font-weight: normal;" required>
				                </div>
			            	</div>
			            </div>

			            <div class="row">
			            	<div class="col-md-3">
				                <label class="col-md-12" style="font-weight: normal;">Zip Code</label>
				                <div class="col-md-12">
				                    <input id="ZipCode" type="text" class="form-control" value="{{ $ZipCode }}" style="width:100%; font-weight: normal;" required>
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
			            <div class="row">
			            	<div class="col-md-12">
				            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Code Information</b></label>
				        	</div>
			        	</div>
			            <div class="row">
			            	<div class="col-md-4">
				                <label class="col-md-12" style="font-weight: normal;">Code <span style="font-size: 10px; color:red;">*</span></label>
				                <div class="col-md-12">
				                    <input id="Code" type="text" data-type="Code" class="form-control"  value="{{ $Code }}" style="width:100%; font-weight: normal;" readonly>
				                </div>
			            	</div>
			            	<div id="divPackage" class="col-md-4">
				                <label class="col-md-12" style="font-weight: normal;">Package <span style="color:red;">*</span></label>
				                <div class="col-md-12" style="font-weight: normal;">
				                    <input id="Package" type="test" class="form-control" value="{{ $Package }}" style="width:100%; font-weight: normal;" readonly>
				                </div>
			            	</div>
			            	<div id="divType" class="col-md-4">
				                <label class="col-md-12" style="font-weight: normal;">Type <span style="color:red;">*</span></label>
				                <div class="col-md-12" style="font-weight: normal;">
				                    <input id="IsFreeCode" type="test" class="form-control" value="{{ ($IsFreeCode == 1 ? "Free" : "Paid") }}" style="width:100%; font-weight: normal;" readonly>
				                </div>
			            	</div>
			            	<div class="col-md-12">
				                <label class="col-md-12" style="font-weight: normal;">Sponsor/Owner <span style="color:red;">*</span></label>
				                <div class="col-md-12" style="font-weight: normal;">
									<input id="Sponsor" type="text" class="form-control" value="{{ $SponsorEntryCode." - ".$SponsorMemberName }}" style="width:100%; font-weight: normal;" readonly>
				                </div>
			            	</div>
			            </div>

			            <div class="row">
			            	<div class="col-md-6">
				                <label class="col-md-12" style="font-weight: normal;">Upline</label>
				                <div class="col-md-12">
				                    <input id="ParentEntry" type="text" class="form-control"  value="{{ $ParentEntryCode." - ".$ParentEntryMemberName }}" style="width:100%; font-weight: normal;" readonly>
				                </div>
			            	</div>
		            	</div>
			            <div class="row">
			            	<div class="col-md-12">
			                	<label class="col-md-12" style="font-weight: normal;">Position</span></label>
			            		<div class="col-md-12 form-group">
					                <label>
					                  <input type="radio" id="rdoL" name="rdoPosition" value="L" class="flat-green" {{ $ParentPosition == "L" ? "checked" : ""  }} disabled>
					                  <span id="spnRDOLeft">LEFT</span>
					                </label>
					                &nbsp&nbsp&nbsp&nbsp&nbsp
					                <label>
					                  <input type="radio" id="rdoR" name="rdoPosition" value="R" class="flat-green" {{ $ParentPosition == "R" ? "checked" : ""  }} disabled>
					                  <span id="spnRDORight">RIGHT</span>
					                </label>
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

	    	var strPosition = "";
	    	if($("#rdoL").is(":checked")){
	    		strPosition = "L";
	    	}else if($("#rdoR").is(":checked")){
	    		strPosition = "R";
	    	}

			if($('#FirstName').val() == "") {
				showJSMessage("Member Entry","Please enter member first name.","OK");
			}else if($('#LastName').val() == "") {
				showJSMessage("Member Entry","Please enter member last name.","OK");
			}else if($('#FirstName').val() == "") {
				showJSMessage("Member Entry","Please enter member firstname.","OK");
			
			}else if($('#MobileNo').val() == "") {
				showJSMessage("Member Entry","Please enter member active mobile number.","OK");
			}else if($('#EmailAddress').val() == "") {
				showJSMessage("Member Entry","Please enter member email address.","OK");

			}else if($('#Address').val() == "") {
				showJSMessage("Member Entry","Please enter member address.","OK");
			}else if($('#City').val() == "") {
				showJSMessage("Member Entry","Please select member city address.","OK");
			}else if($('#StateProvince').val() == "") {
				showJSMessage("Member Entry","Please enter member state/provincial address.","OK");
			}else if($('#Country').val() == "") {
				showJSMessage("Member Entry","Please select member country address.","OK");

			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						EntryID: {{ $EntryID }},

						MemberID: {{ $MemberID }},
						FirstName: $("#FirstName").val(),
						LastName: $("#LastName").val(),
						MiddleName: $("#MiddleName").val(),

						TelNo: $("#TelNo").val(),
						MobileNo: $("#MobileNo").val(),
						EmailAddress: $("#EmailAddress").val(),
						Password: "",

						Address: $("#Address").val(),
						City: $("#City").val(),
						StateProvince: $("#StateProvince").val(),
						ZipCode: $("#ZipCode").val(),
						Country: $("#Country").val(),

						Status: $("#Status").val(),

						Code: $("#Code").val(),
						PackageID: {{ $PackageID }},
						IsFreeCode : {{ $IsFreeCode }},

						SponsorEntryID: {{ $SponsorEntryID }},
						ParentEntryID: {{ $ParentEntryID }},
						ParentPosition: "{{ $ParentPosition }}"

					},
					url: "{{ route('do-save-member-entry') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							showMessage("Success",data.ResponseMessage);
						}else{
							showJSModalMessageJS("Save Member Information",data.ResponseMessage,"OK");
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
