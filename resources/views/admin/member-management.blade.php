@extends('layout.adminweb')

@section('content')
  
@php($IsAllowEditMemberInfo = Session('IS_SUPER_ADMIN')==true ? 'true' : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Allow Edit Member Info'))  

@php($IsAllowTransferPosition = Session('IS_SUPER_ADMIN')==true ? 'true' : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Allow Transfer Position'))  

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Member Management
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Member Management</li>
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
			                  <th>IBO Number</th>
			                  <th>Entry Date/Time</th>
			                  <th>Member</th>
			                  <th>Tel. No.</th>
			                  <th>Mobile No.</th>
			                  <th>Email Address</th>
			                  <th>Sponsor</th>
			                  <th>Placement</th>
			                  <th>Position</th>
			                  <th>Used Code</th>
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
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Member Entry Information member</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="EntryID" value="0" readonly>
	        	<input type="hidden" id="MemberID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">IBO Number</label>
		                <div class="col-md-12">
		                    <input id="EntryCode" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Date/Time</label>
		                <div class="col-md-12">
		                    <input id="EntryDateTime" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
		                	@if(Session('IS_SUPER_ADMIN'))
								<select id="Status" class="form-control select2" style="width: 100%; height: 100px; font-weight: normal;">
									<option value="{{ config('app.STATUS_ACTIVE') }}">{{ config('app.STATUS_ACTIVE') }}</option>
									<option value="{{ config('app.STATUS_INACTIVE') }}">{{ config('app.STATUS_INACTIVE') }}</option>
									<option value="{{ config('app.STATUS_BLOCKED') }}">{{ config('app.STATUS_BLOCKED') }}</option>
								</select>
		                	@else
			                    <input id="Status" type="text" class="form-control" value=""style="width:100%; font-weight: normal;" readonly>
		                	@endif
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
		                    <input id="FirstName" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Last Name <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="LastName" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Middle Name <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="MiddleName" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
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
	            </div>

	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Email Address <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="EmailAddress" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Password <span style="font-size:10px; color:red;">(Please remember your password to gain access of your back office)</span></label>
		                <div class="col-md-12">
		                    <input id="Password" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
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
	            <div class="row">
	            	<div class="col-md-12">
		            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Code Information</b></label>
		        	</div>
	        	</div>
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Code <span style="font-size: 10px; color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="Code" type="text" data-type="Code" class="form-control"  value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div id="divPackage" class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Package <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
		                    <input id="PackageID" type="hidden" class="form-control" value="" readonly>
		                    <input id="Package" type="test" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div id="divType" class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Type <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
		                    <input id="IsFreeCode" type="test" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Sponsor/Owner <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
		                    <input id="SponsorEntryID" type="hidden" class="form-control" value="" readonly>
							<input id="Sponsor" type="text" data-type="SponsorEntryCode" class="form-control autocomplete_txt"  autocomplete="off" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            </div>

	            <div class="row">
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Please enter the IBO Number of the upline.<span style="font-size: 10px; color:red;">(Please type atleast 3 characters of upline IBO Number)</span></label>
		                <div class="col-md-12">
		                    <input id="ParentEntryID" type="hidden" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                    <input id="ParentEntry" type="text" data-type="ParentEntryCode" class="form-control autocomplete_txt"  autocomplete="off" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
            	</div>
	            <div class="row">
	            	<div class="col-md-12">
	                	<label class="col-md-12" style="font-weight: normal;">Position</span></label>
	            		<div class="col-md-12 form-group">
			                <label>
			                  <input type="radio" id="rdoL" name="rdoPosition" value="L" class="flat-green">
			                  <span id="spnRDOLeft">LEFT</span>
			                </label>
			                &nbsp&nbsp&nbsp&nbsp&nbsp
			                <label>
			                  <input type="radio" id="rdoR" name="rdoPosition" value="R" class="flat-green">
			                  <span id="spnRDORight">RIGHT</span>
			                </label>
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

	<div id="transfer-position-info-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg" style="width: 90%;">
	    <div class="modal-content">

          	<div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Transfer Position</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="TransferPositionEntryID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Please enter the IBO Number of the new upline.<span style="font-size: 10px; color:red;">(Please type atleast 3 characters of upline IBO Number)</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
		                    <input id="TransferPositionParentEntryID" type="hidden" class="form-control" value="" readonly>
							<input id="TransferPositionParentEntry" type="text" data-type="TransferPositionParentEntry" class="form-control autocomplete_txt"  autocomplete="off" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            </div>

	            <div class="row">
	            	<div class="col-md-12">
	                	<label class="col-md-12" style="font-weight: normal;">Position</span></label>
	            		<div class="col-md-12 form-group">
			                <label>
			                  <input type="radio" id="rdoTransferPositionL" name="rdoTransferPosition" value="L" class="flat-green">
			                  <span id="spnTransferPositionRDOLeft">LEFT</span>
			                </label>
			                &nbsp&nbsp&nbsp&nbsp&nbsp
			                <label>
			                  <input type="radio" id="rdoTransferPositionR" name="rdoTransferPosition" value="R" class="flat-green">
			                  <span id="spnTransferPositionRDORight">RIGHT</span>
			                </label>
	            		</div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="modal-footer">
					<div class="input-group pull-right">
						<span class="input-group-btn">
							<a id="btnTransferPositionProceed" href="#" class="btn btn-info btn-flat" onclick="TransferPositionNow()"><i class="fa fa-save"></i> Proceed</a>
							<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Cancel</a>
						</span>
					</div>	
	            </div>

         	</div>

	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="upgrade-info-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg" style="width: 90%;">
	    <div class="modal-content">

          	<div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Upgrade Member Entry Information</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="UpgradeEntryID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-12">
		            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Code Information</b></label>
		        	</div>
	        	</div>
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Code <span style="font-size: 10px; color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="UpgradeCode" type="text" class="form-control"  value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-4" style="display: none;">
		                <label class="col-md-12" style="font-weight: normal;">Package <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
		                    <input id="UpgradeCurrentPackageID" type="hidden" class="form-control" value="" readonly>
		                    <input id="UpgradePackageID" type="hidden" class="form-control" value="" readonly>
		                    <input id="UpgradePackage" type="test" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            	<div class="col-md-4" style="display: none;">
		                <label class="col-md-12" style="font-weight: normal;">Type <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
		                    <input id="UpgradeIsFreeCode" type="test" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>

	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Sponsor/Owner <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
		                    <input id="UpgradeSponsorEntryID" type="hidden" class="form-control" value="" readonly>
							<input id="UpgradeSponsor" type="text" data-type="UpgradeSponsor" class="form-control autocomplete_txt"  autocomplete="off" value="" style="width:100%; font-weight: normal;" readonly>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="modal-footer">
					<div class="input-group pull-right">
						<span class="input-group-btn">
							<a id="btnUpgrade" href="#" class="btn btn-info btn-flat" onclick="UpgradeNow()"><i class="fa fa-save"></i> Upgrade</a>
							<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Cancel</a>
						</span>
					</div>	
	            </div>

         	</div>

	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="matching-entries-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">

          	<div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Member Matching Entries</b></h4>
          	</div>

          	<div class="modal-body">

	            <div class="row">
	            	<div class="col-md-12">
		              	<table id="tblMemberMatchingEntries" class="table table-bordered table-hover">
			                <thead>
				                <tr>
				                  <th></th>
				                  <th>EntryID</th>
				                  <th>Source Entry</th>
				                  <th>Package</th>
				                  <th>Position</th>
				                  <th style="text-align: right;">Level No.</th>
				                  <th style="text-align: right;">BPV</th>
				                  <th style="text-align: right;">Left Running Balance</th>
				                  <th style="text-align: right;">Right Running Balance</th>
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

	<div id="accumulated-purchases-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">

          	<div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Accumulated Purchases</b></h4>
          	</div>

          	<div class="modal-body">

	            <div class="row">
	            	<div class="col-md-12">
		              	<table id="tblMemberAccumulatedPurchases" class="table table-bordered table-hover">
			                <thead>
				                <tr>
				                  <th></th>
				                  <th>EntryID</th>
				                  <th>Source</th>
				                  <th>Order No.</th>
				                  <th style="text-align: right;">Level No.</th>
				                  <th style="text-align: right;">Rebatable Value</th>
				                  <th style="text-align: right;">Accumulated Personal Purchases</th>
				                  <th style="text-align: right;">Accumulated Group Purchases</th>
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
	 	var IsFreeCode = 0;

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

	        $('#tblMemberMatchingEntries').DataTable( {
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
				'ordering'    : false,
				'info'        : false,
				'autoWidth'   : false,
				"order": [[ 0, "desc" ]]
	        });

	        $('#tblMemberAccumulatedPurchases').DataTable( {
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
				'ordering'    : false,
				'info'        : false,
				'autoWidth'   : false
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
					Status: '',
					IsWithEwallet:0
				},
				url: "{{ route('get-member-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.MemberList);
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

	    	tdID = vData.EntryID;

			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> ";
	                        @if($IsAllowEditMemberInfo)
	                          	tdOption += " <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='EditInformation(" + vData.EntryID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Edit Information " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";
	                        @endif
	                        tdOption += " <li style='text-align:left;'> " +
	                              	" <a href='{{ route('admin-member-genealogy') }}?MemberEntryID=" + vData.EntryID + "&MaxLevel=100&level=6' target='blank_' >" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> View Genealogy " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='ViewMatchingEntries(" + vData.EntryID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> View Matching Entries " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='ViewAccumulatedPurchases(" + vData.EntryID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> View Accumulated Purchases " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";
		                        @if($IsAllowTransferPosition)
		                          	tdOption += " <li style='text-align:left;'> " +
		                              	" <a href='#' onclick='TransferPosition(" + vData.EntryID + ")'>" + 
		                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Transfer Position " + 
		                              		" </strong>" +
		                              	" </a> " +
		                           	" </li> ";
		                        @endif
		                        @if($IsAllowTransferPosition)
		                          	tdOption += " <li style='text-align:left;'> " +
		                              	" <a href='{{ route('auto-login') }}?MemberEntryID=" + vData.EntryID + "&access=6' target='_blank' >" + 
		                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Access Account " + 
		                              		" </strong>" +
		                              	" </a> " +
		                           	" </li> ";
		                        @endif
	                           	if(vData.PackageID < {{ config('app.GoldPackageID') }}){
	                          		tdOption += " <li style='text-align:left;'> " +
				                              		" <a href='#' onclick='UpgradeEntry(" + vData.EntryID + "," + vData.PackageID + ',' + vData.SponsorEntryID + ',"' + vData.SponsorEntryCode + " - " + vData.SponsorMemberName + "\")'>" + 
					                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Upgrade Entry " + 
					                              		" </strong>" +
					                              	" </a> " +
					                           	" </li> ";
	                           	}
            	tdOption += " </ul> " +
	                    " </div> " ;
			tdEntryCode = "<span style='font-weight:normal;'>" + vData.EntryCode + "</span>";
			tdEntryDateTime = "<span style='font-weight:normal;'>" + vData.EntryDateTime + "</span>";

			tdMember = "<span style='font-weight:normal;'>" + vData.MemberName + "</span>";
			tdTelNo = "<span style='font-weight:normal;'>" + vData.TelNo + "</span>";
			tdMobileNo = "<span style='font-weight:normal;'>" + vData.MobileNo + "</span>";
			tdEmailAddress = "<span style='font-weight:normal;'>" + vData.EmailAddress + "</span>";
			
			tdSponsor = "<span style='font-weight:normal;'>" + vData.SponsorEntryCode + " - " + vData.SponsorMemberName + "</span>";

			tdPlacement = "<span style='font-weight:normal;'>" + vData.ParentEntryCode + " - " + vData.ParentEntryMemberName + "</span>";

			tdPosition = "<span style='font-weight:normal;'>" + vData.ParentPosition + "</span>";

			tdUsedCode = "<span style='font-weight:normal;'>" + vData.Code + " - " + vData.Package + "</span>";

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

			    if(rowData[0] == vData.EntryID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdOption;
			    	curData[2] = tdEntryCode;
			    	curData[3] = tdEntryDateTime;
			    	curData[4] = tdMember;
			    	curData[5] = tdTelNo;
			    	curData[6] = tdMobileNo;
			    	curData[7] = tdEmailAddress;
			    	curData[8] = tdSponsor;
			    	curData[9] = tdPlacement;
			    	curData[10] = tdPosition;
			    	curData[11] = tdUsedCode;
			    	curData[12] = tdStatus;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdID,
						tdOption,
						tdEntryCode, 
						tdEntryDateTime,
						tdMember,
						tdTelNo,
						tdMobileNo,
						tdEmailAddress,
						tdSponsor,
				    	tdPlacement,
				    	tdPosition,
						tdUsedCode,
						tdStatus
					]).draw();			
			}

	    }

	    function Clearfields(){

	    	IsFreeCode = 0;

			$("#EntryID").val('0');

			$("#EntryCode").val('');
			$("#EntryDateTime").val('');
			$("#Status").val('Active').change();
	    	
	    	$("#MemberID").val('');
			$("#FirstName").val('');
			$("#LastName").val('');
			$("#MiddleName").val('');

			$("#TelNo").val('');
			$("#MobileNo").val('');
			$("#EmailAddress").val('');
			$("#Password").val('');

			$("#Address").val('');
			$("#City").val('').change();
			$("#StateProvince").val('');
			$("#ZipCode").val('');
			$("#Country").val(174).change();

			$("#Code").val('');
			$("#PackageID").val('0');
			$("#Package").val('');
			$("#IsFreeCode").val('');
			$("#SponsorEntryID").val('0');
			$("#Sponsor").val('');

			$("#ParentEntryID").val('0');
			$("#ParentEntry").val('');

			$("#spnRDOLeft").text('Left');
			$("#rdoL").prop("checked", false);

			$("#spnRDORight").text('Right');
			$("#rdoR").prop("checked", false);

			$("#FirstName").prop('disabled', false);
			$("#LastName").prop('disabled', false);
			$("#MiddleName").prop('disabled', false);

			$("#TelNo").prop('disabled', false);
			$("#MobileNo").prop('disabled', false);
			$("#EmailAddress").prop('disabled', false);
			$("#Password").prop('disabled', false);

			$("#Address").prop('disabled', false);
			$("#City").prop('disabled', false);
			$("#StateProvince").prop('disabled', false);
			$("#ZipCode").prop('disabled', false);
			$("#Country").prop('disabled', false);

			$("#Code").prop('disabled', false);
			$("#ParentEntry").prop('disabled', false);
			$("#Sponsor").prop('disabled', false);

			$("#rdoL").prop('disabled', false);
			$("#rdoR").prop('disabled', false);

			$("#divPackage").hide();
			$("#divType").hide();

			$("#btnSave").show();

	    }

	    function NewRecord(){
			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}'
				},
				url: "{{ route('get-member-temp-password') }}",
				dataType: "json",
				success: function(data){
			    	isNewRecord = 1;
					Clearfields();
					$("#Password").val(data.TempPassword);
					$("#record-info-modal").modal();
				},
				error: function(data){
					console.log(data.responseText);
				},
				beforeSend:function(vData){
				}
        	});
	    }

	    function EditInformation(vRecordID){

	    	if(vRecordID > 0){

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						EntryID: vRecordID
					},
					url: "{{ route('get-member-info') }}",
					dataType: "json",
					success: function(data){

						if(data.Response =='Success' && data.MemberInfo != undefined){

								$("#EntryID").val(data.MemberInfo.EntryID);

								$("#EntryCode").val(data.MemberInfo.EntryCode);
								$("#EntryDateTime").val(data.MemberInfo.EntryDateTime);
								$("#Status").val(data.MemberInfo.Status);
						    	
						    	$("#MemberID").val(data.MemberInfo.MemberID);
								$("#FirstName").val(data.MemberInfo.FirstName);
								$("#LastName").val(data.MemberInfo.LastName);
								$("#MiddleName").val(data.MemberInfo.MiddleName);

								$("#EmailAddress").val(data.MemberInfo.EmailAddress);
								$("#TelNo").val(data.MemberInfo.TelNo);
								$("#MobileNo").val(data.MemberInfo.MobileNo);

								$("#Address").val(data.MemberInfo.Address);
								$("#City").val(data.MemberInfo.CityID).change();
								$("#StateProvince").val(data.MemberInfo.StateProvince);
								$("#ZipCode").val(data.MemberInfo.ZipCode);
								$("#Country").val(data.MemberInfo.CountryID).change();

								$("#Code").val(data.MemberInfo.Code);
								$("#PackageID").val(data.MemberInfo.PackageID);
								$("#Package").val(data.MemberInfo.Package);
								$("#IsFreeCode").val(data.MemberInfo.IsFreeCode == 1 ? "Free Code" : "Paid Code");
								$("#SponsorEntryID").val(data.MemberInfo.SponsorEntryID);
								$("#Sponsor").val(data.MemberInfo.SponsorEntryCode + " - " + data.MemberInfo.SponsorMemberName);

								$("#ParentEntryID").val(data.MemberInfo.ParentEntryID);
								$("#ParentEntry").val(data.MemberInfo.ParentEntryCode + " - " + data.MemberInfo.ParentEntryMemberName);

								if(data.MemberInfo.ParentPosition == "L"){
									$("#rdoL").prop("checked", true);
								}else if(data.MemberInfo.ParentPosition == "R"){
									$("#rdoR").prop("checked", true);
								}

								$("#FirstName").prop('disabled', false);
								$("#LastName").prop('disabled', false);
								$("#MiddleName").prop('disabled', false);

								$("#TelNo").prop('disabled', false);
								$("#MobileNo").prop('disabled', false);
								$("#EmailAddress").prop('disabled', false);
								$("#Password").prop('disabled', false);

								$("#Address").prop('disabled', false);
								$("#City").prop('disabled', false);
								$("#StateProvince").prop('disabled', false);
								$("#ZipCode").prop('disabled', false);
								$("#Country").prop('disabled', false);

								$("#Code").prop('disabled', true);
								$("#ParentEntry").prop('disabled', true);
								$("#Sponsor").prop('disabled', true);

								$("#rdoL").prop('disabled', true);
								$("#rdoR").prop('disabled', true);

								$("#divPackage").show();
								$("#divType").show();

								$("#record-info-modal").modal();

						}else{
							showJSModalMessageJS("Member Entry Information",data.ResponseMessage,"OK");
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

			}else if($('#EntryID').val()=="0" && $('#Code').val() == "") {
				showJSMessage("Member Entry","Please enter code.","OK");
			}else if($('#EntryID').val()=="0" &&$('#ParentEntryID').val() == "") {
				showJSMessage("Member Entry","Please select member upline.","OK");
			}else if($('#EntryID').val()=="0" && strPosition == "") {
				showJSMessage("Member Entry","Please select position.","OK");

			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						EntryID: $("#EntryID").val(),

						MemberID: $("#MemberID").val(),
						FirstName: $("#FirstName").val(),
						LastName: $("#LastName").val(),
						MiddleName: $("#MiddleName").val(),

						TelNo: $("#TelNo").val(),
						MobileNo: $("#MobileNo").val(),
						EmailAddress: $("#EmailAddress").val(),
						Password: $("#Password").val(),

						Address: $("#Address").val(),
						City: $("#City").val(),
						StateProvince: $("#StateProvince").val(),
						ZipCode: $("#ZipCode").val(),
						Country: $("#Country").val(),

						Status: $("#Status").val(),

						Code: $("#Code").val(),
						PackageID: $("#PackageID").val(),
						IsFreeCode : IsFreeCode,
						SponsorEntryID: $("#SponsorEntryID").val(),
						ParentEntryID: $("#ParentEntryID").val(),
						ParentPosition: strPosition

					},
					url: "{{ route('do-save-member-entry') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.MemberInfo);
						}else{
							showJSModalMessageJS("Save Member Entry",data.ResponseMessage,"OK");
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

	    function ViewMatchingEntries(vRecordID){

	    	if(vRecordID > 0){

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						MemberEntryID: vRecordID
					},
					url: "{{ route('get-member-matching-entries') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();

						$("#tblMemberMatchingEntries").DataTable().clear().draw();
				        LoadRecordMatchingEntries(vRecordID, data.MemberMatchingEntries);

						$("#matching-entries-modal").modal();
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

	    function LoadRecordMatchingEntries(vRecordID, vList){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadRecordMatchingEntriesRow(vRecordID, vList[x]);
	    		}
	    	}
	    }

	    function LoadRecordMatchingEntriesRow(vRecordID, vData){

	    	var tblMemberMatchingEntries = $("#tblMemberMatchingEntries").DataTable();

	    	tdID = vData.MatchID;
	    	tdEntryID = vData.EntryID;
	    	if(vData.LevelNo == 0){
				tdSourceEntry = "<span style='font-weight:normal;'>" + vData.Remarks + "</span>";
	    	}else{
		    	if(vData.Remarks != ""){
					tdSourceEntry = "<span style='font-weight:normal;'>" + vData.EntryCode + " - " + vData.EntryMemberName + " (" + vData.Remarks + ")" + "</span>";
		    	}else{
					tdSourceEntry = "<span style='font-weight:normal;'>" + vData.EntryCode + " - " + vData.EntryMemberName + "</span>";
		    	}
	    	}
			tdEntryPackage = "<span style='font-weight:normal;'>" + vData.EntryPackage + "</span>";
			tdMatchPosition = "<span style='font-weight:normal;'>" + vData.MatchPosition + "</span>";
			tdLevelNo = "<span style='font-weight:normal;' class='pull-right'>" + vData.LevelNo + "</span>";
			tdBPV = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.BPV,2) + "</span>";
			tdLRunningBalance = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.LRunningBalance,2) + "</span>";
			tdRRunningBalance = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.RRunningBalance,2) + "</span>";

			//Check if record already listed
			var IsRecordExist = false;
			tblMemberMatchingEntries.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

			    var rowData = this.data();
			    if(rowData[0] == vData.MatchID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblMemberMatchingEntries.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdEntryID;
			    	curData[2] = tdSourceEntry;
			    	curData[3] = tdEntryPackage;
			    	curData[4] = tdMatchPosition;
			    	curData[5] = tdLevelNo;
			    	curData[6] = tdBPV;
			    	curData[7] = tdLRunningBalance;
			    	curData[8] = tdRRunningBalance;

			    	tblMemberMatchingEntries.row(rowIdx).data(curData).invalidate().draw();
			    }
			});

			if(!IsRecordExist){

				tblMemberMatchingEntries.row.add([
					tdID,
					tdEntryID,
					tdSourceEntry,
					tdEntryPackage,
					tdMatchPosition,
					tdLevelNo,
					tdBPV,
					tdLRunningBalance,
					tdRRunningBalance
				]).draw();			

			}

	    }

	    function ViewAccumulatedPurchases(vRecordID){

	    	if(vRecordID > 0){

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						MemberEntryID: vRecordID
					},
					url: "{{ route('get-member-accumulated-purchases') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();

						$("#tblMemberAccumulatedPurchases").DataTable().clear().draw();
				        LoadRecordAccumulatedPurchases(vRecordID, data.MemberAccumulatedPurchases);

						$("#accumulated-purchases-modal").modal();
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

	    function LoadRecordAccumulatedPurchases(vRecordID, vList){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadRecordAccumulatedPurchasesRow(vRecordID, vList[x]);
	    		}
	    	}
	    }

	    function LoadRecordAccumulatedPurchasesRow(vRecordID, vData){

	    	var tblMemberAccumulatedPurchases = $("#tblMemberAccumulatedPurchases").DataTable();

	    	tdID = vData.AccumulatedOrderID;
	    	tdEntryID = vData.EntryID;
			tdSource = "<span style='font-weight:normal;'>" + vData.EntryCode + " - " + vData.EntryMemberName + "</span>";

			if(vData.Remarks == "Beginning Balance"){
				tdOrderNo = "<span style='font-weight:normal;'>" + vData.Remarks + "</span>";
			}else{
				tdOrderNo = "<span style='font-weight:normal;'>" + vData.OrderNo + "</span>";
			}

			if(vData.Remarks == "Beginning Balance"){
				tdLevelNo = "<span style='font-weight:normal;' class='pull-right'></span>";
			}else{
				tdLevelNo = "<span style='font-weight:normal;' class='pull-right'>" + (vData.LevelNo == 0 ? "Personal Purchase" : vData.LevelNo) + "</span>";
			}
			tdRebatableValue = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.RebatableValue,2) + "</span>";
			tdPersonalRunningBalance = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.PersonalRunningBalance,2) + "</span>";
			tdGroupRunningBalance = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.GroupRunningBalance,2) + "</span>";

			//Check if record already listed
			var IsRecordExist = false;
			tblMemberAccumulatedPurchases.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

			    var rowData = this.data();
			    if(rowData[1] == vData.MatchID){
					IsRecordExist = true;

			    	//Edit Row
			    	curData = tblMemberAccumulatedPurchases.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdEntryID;
			    	curData[2] = tdSource;
			    	curData[3] = tdOrderNo;
			    	curData[4] = tdLevelNo;
			    	curData[5] = tdRebatableValue;
			    	curData[6] = tdPersonalRunningBalance;
			    	curData[7] = tdGroupRunningBalance;

			    	tblMemberAccumulatedPurchases.row(rowIdx).data(curData).invalidate().draw();
			    }
			});

			if(!IsRecordExist){

				tblMemberAccumulatedPurchases.row.add([
					tdID,
					tdEntryID,
					tdSource,
					tdOrderNo,
					tdLevelNo,
					tdRebatableValue,
					tdPersonalRunningBalance,
					tdGroupRunningBalance
				]).draw();			

			}

	    }

	    function TransferPosition(vEntryID){

			$("#TransferPositionEntryID").val(vEntryID);

			$("#TransferPositionParentEntryID").val("");
			$("#TransferPositionParentEntry").val("");

			$("#spnTransferPositionRDOLeft").text('Left');
			$("#rdoTransferPositionL").prop("checked", false);

			$("#spnTransferPositionRDORight").text('Right');
			$("#rdoTransferPositionR").prop("checked", false);

			$("#transfer-position-info-modal").modal();
	    }

	    function TransferPositionNow(){

	    	var strPosition = "";
	    	if($("#rdoTransferPositionL").is(":checked")){
	    		strPosition = "L";
	    	}else if($("#rdoTransferPositionR").is(":checked")){
	    		strPosition = "R";
	    	}

			if($('#TransferPositionParentEntryID').val() == "") {
				showJSMessage("Upline","Please select new upline.","OK");
			}else if(strPosition == "") {
				showJSMessage("Position","Please select position.","OK");
			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						EntryID: $("#TransferPositionEntryID").val(),
						ParentEntryID: $("#TransferPositionParentEntryID").val(),
						Position: strPosition
					},
					url: "{{ route('do-transfer-member-position') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnTransferPositionProceed", "Proceed", false);
						if(data.Response =='Success'){
							$("#transfer-position-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.MemberInfo);
						}else{
							showJSModalMessageJS("Transfer Member Position",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
						buttonOneClick("btnTransferPositionProceed", "Proceed", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
						buttonOneClick("btnTransferPositionProceed", "", true);
					}
	        	});
	      	}
	    };

	    function UpgradeEntry(vEntryID, vPackageID, vUpgradeSponsorEntryID, vUpgradeSponsorEntry){

			$("#UpgradeEntryID").val(vEntryID);

			$("#UpgradeCode").val('');

			$("#UpgradeCurrentPackageID").val(vPackageID);
			$("#UpgradePackageID").val("0");
			$("#UpgradePackage").val('');

			$("#UpgradeSponsorEntryID").val(vUpgradeSponsorEntryID);
			$("#UpgradeSponsor").val(vUpgradeSponsorEntry);

			$("#upgrade-info-modal").modal();
	    }

	    function UpgradeNow(){

			if($('#UpgradeCode').val() == "") {
				showJSMessage("Upgrade Member Entry","Please enter code.","OK");
			}else if($('#UpgradeSponsorEntryID').val() == "" || $('#UpgradeSponsorEntryID').val() == "0") {
				showJSMessage("Upgrade Member Entry","Please select sponsor.","OK");
			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						Source: "Admin",
						EntryID: $("#UpgradeEntryID").val(),
						Code: $("#UpgradeCode").val(),
						CurrentPackageID: $("#UpgradeCurrentPackageID").val(),
						PackageID: $("#UpgradePackageID").val(),
						SponsorEntryID: $("#UpgradeSponsorEntryID").val()

					},
					url: "{{ route('do-upgrade-member-entry') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnUpgrade", "Upgrade", false);
						if(data.Response =='Success'){
							$("#upgrade-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.MemberInfo);
						}else{
							showJSModalMessageJS("Upgrade Member Entry",data.ResponseMessage,"OK");
						}
					},
					error: function(data){
						buttonOneClick("btnUpgrade", "Upgrade", false);
						console.log(data.responseText);
					},
					beforeSend:function(vData){
						buttonOneClick("btnUpgrade", "", true);
					}
	        	});
	      	}
	    };

		//autocomplete script
	    $(document).on('focus','.autocomplete_txt',function(){

	    	if($(this).data('type') == "SponsorEntryCode"){

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

		                $('#SponsorEntryID').val(data[0]);
		                $('#Sponsor').val(data[1] + " - " + data[2]);
		            }
		        });

	    	}else if($(this).data('type') == "ParentEntryCode"){

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

		                $('#ParentEntryID').val(data[0]);
		                $('#ParentEntry').val(data[1] + " - " + data[2]);

						var intLeftEntryID = data[6]
						var intRightEntryID = data[7]

						if(intLeftEntryID > 0){
							$("#spnRDOLeft").text('Left (Occupied)');
							$("#rdoL").prop('disabled', true);
						}else{
							$("#spnRDOLeft").text('Left - Available');
							$("#rdoL").prop('disabled', false);
						}

						if(intRightEntryID > 0){
							$("#spnRDORight").text('Right (Occupied)');
							$("#rdoR").prop('disabled', true);
						}else{
							$("#spnRDORight").text('Right - Available');
							$("#rdoR").prop('disabled', false);
						}

		            }
		        });

	    	}else if($(this).data('type') == "TransferPositionParentEntry"){

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

		                $('#TransferPositionParentEntryID').val(data[0]);
		                $('#TransferPositionParentEntry').val(data[1] + " - " + data[2]);

						var intLeftEntryID = data[6]
						var intRightEntryID = data[7]

						if(intLeftEntryID > 0){
							$("#spnTransferPositionRDOLeft").text('Left (Occupied)');
							$("#rdoTransferPositionL").prop('disabled', true);
						}else{
							$("#spnTransferPositionRDOLeft").text('Left - Available');
							$("#rdoTransferPositionL").prop('disabled', false);
						}

						if(intRightEntryID > 0){
							$("#spnTransferPositionRDORight").text('Right (Occupied)');
							$("#rdoTransferPositionR").prop('disabled', true);
						}else{
							$("#spnTransferPositionRDORight").text('Right - Available');
							$("#rdoTransferPositionR").prop('disabled', false);
						}

		            }
		        });

	    	}else if($(this).data('type') == "UpgradeSponsor"){

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

		                $('#UpgradeSponsorEntryID').val(data[0]);
		                $('#UpgradeSponsor').val(data[1] + " - " + data[2]);
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
