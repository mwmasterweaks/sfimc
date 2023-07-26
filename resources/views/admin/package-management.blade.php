@extends('layout.adminweb')

@section('content')
  
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Package Management
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Package Management</li>
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
			                  <th>Package</th>
			                  <th>Description</th>
			                  <th style="text-align: right;">Package Price</th>
			                  <th style="text-align: right;">Product Worth</th>
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
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Package Information</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="PackageID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Package <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="Package" type="text" class="form-control" placeholder="Package" value="" style="width:100%; font-weight: normal;">
		                </div>
	            	</div>
	            	<div class="col-md-4">
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
		                <label class="col-md-12" style="font-weight: normal;">Description </label>
		                <div class="col-md-12">
	                		<textarea id="Description" class="form-control wysiwyg" cols="40" rows="8"  placeholder="Product Description" style="width:100%;"></textarea>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: bold; font-size: 18px;">Package Settings</label>
	            	</div>
            	</div>
	            <div class="row">
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Package Price<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='PackagePrice' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Product Worth<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='ProductWorth' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Sponsor Comm. Amount<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='SponsorCommission' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Package Color <span style="color:red;">*</span></label>
		                <div class="col-md-12">
			            	<input id='PackageColor' type="color" name="PackageColor" value="#00000" style='width:100%; '>
		                </div>
	            	</div>
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Package Product<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<select id='PackageProduct' class='form-control select2' style='width: 100%; font-weight:normal;'>
								<option value=''>Please Select</option>
								@foreach ($ProductList as $pkey)
									<option value='{{ $pkey->ProductID }}'>{{ $pkey->ProductCode.' - '.$pkey->ProductName }}</option>
								@endforeach			
							</select>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: bold; font-size: 18px;">Package Entry Settings</label>
	            	</div>
            	</div>
	            <div class="row">
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">No. Of Entry Share<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='NoOfEntryShare' type='text' class='form-control numberonly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Entry Share Amount<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='EntryShareAmount' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Maximum Share Amount<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='MaxShareAmount' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: bold; font-size: 18px;">Matching Commission Settings</label>
	            	</div>
            	</div>
	            <div class="row">
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Required BPV<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RequiredBPV' type='text' class='form-control numberonly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Pairing Amount<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='PairingAmount' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Maximum Match Per Day<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='MaxMatchPerDay' type='text' class='form-control numberonly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-3">
		                <label class="col-md-12" style="font-weight: normal;">Voucher On Nth Pair<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='VoucherOnNthPair' type='text' class='form-control numberonly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: bold; font-size: 18px;">Rebates Settings</label>
	            	</div>
            	</div>
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Maintaining Balance (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RebatesMaintainingBal' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Personal Rebates (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='PersonalRebatesPercent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
            	</div>
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level 1 (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RebateLevel1Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level 2 (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RebateLevel2Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level 3 (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RebateLevel3Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level 4 (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RebateLevel4Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>	            
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level 5 (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RebateLevel5Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level 6 (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RebateLevel6Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level 7 (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RebateLevel7Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level 8 (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RebateLevel8Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level 9 (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RebateLevel9Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: bold; font-size: 18px;">Ranking Lion Settings</label>
	            	</div>
            	</div>
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level</label>
		                <div class="col-md-12">
							<input id='RankLevel-1' type='text' class='form-control' value='1' style='width:100%; font-weight: normal;' readonly>
		                </div>
	            	</div>

	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Rank<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel1' type='text' class='form-control' value='' style='width:100%; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Personal Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel1APPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Group Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel1AGPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Rebates (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel1Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>

	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level</label>
		                <div class="col-md-12">
							<input id='RankLevel-2' type='text' class='form-control' value='2' style='width:100%; font-weight: normal;' readonly>
		                </div>
	            	</div>
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Rank<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel2' type='text' class='form-control' value='' style='width:100%; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Personal Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel2APPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Group Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel2AGPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Rebates (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel2Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>

	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level</label>
		                <div class="col-md-12">
							<input id='RankLevel-3' type='text' class='form-control' value='3' style='width:100%; font-weight: normal;' readonly>
		                </div>
	            	</div>
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Rank<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel3' type='text' class='form-control' value='' style='width:100%; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Personal Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel3APPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Group Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel3AGPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Rebates (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel3Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>


	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level</label>
		                <div class="col-md-12">
							<input id='RankLevel-4' type='text' class='form-control' value='4' style='width:100%; font-weight: normal;' readonly>
		                </div>
	            	</div>
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Rank<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel4' type='text' class='form-control' value='' style='width:100%; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Personal Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel4APPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Group Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel4AGPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Rebates (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel4Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>

	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level</label>
		                <div class="col-md-12">
							<input id='RankLevel-5' type='text' class='form-control' value='5' style='width:100%; font-weight: normal;' readonly>
		                </div>
	            	</div>	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Rank<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel5' type='text' class='form-control' value='' style='width:100%; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Personal Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel5APPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Group Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel5AGPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Rebates (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel5Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>

	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level</label>
		                <div class="col-md-12">
							<input id='RankLevel-6' type='text' class='form-control' value='6' style='width:100%; font-weight: normal;' readonly>
		                </div>
	            	</div>	            	
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Rank<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel6' type='text' class='form-control' value='' style='width:100%; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Personal Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel6APPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Group Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel6AGPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Rebates (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel6Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>

	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level</label>
		                <div class="col-md-12">
							<input id='RankLevel-7' type='text' class='form-control' value='7' style='width:100%; font-weight: normal;' readonly>
		                </div>
	            	</div>	
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Rank<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel7' type='text' class='form-control' value='' style='width:100%; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Personal Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel7APPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Group Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel7AGPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Rebates (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel7Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>

	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level</label>
		                <div class="col-md-12">
							<input id='RankLevel-8' type='text' class='form-control' value='8' style='width:100%; font-weight: normal;' readonly>
		                </div>
	            	</div>	
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Rank<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel8' type='text' class='form-control' value='' style='width:100%; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Personal Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel8APPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Group Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel8AGPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Rebates (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel8Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>

	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Level</label>
		                <div class="col-md-12">
							<input id='RankLevel-9' type='text' class='form-control' value='9' style='width:100%; font-weight: normal;' readonly>
		                </div>
	            	</div>	
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Rank<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel9' type='text' class='form-control' value='' style='width:100%; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Personal Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel9APPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Accumulated Group Purchases (RV)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel9AGPRV' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Rebates (%)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RankLevel9Percent' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
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
	            "order": [[ 2, "asc" ]]
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
				url: "{{ route('get-package-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.PackageList);
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

	    	tdID = vData.PackageID;

			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='EditSettings(" + vData.PackageID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Edit Settings" + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";
                      	tdOption +=
	                        " </ul> " +
	                    " </div> " ;

			tdPackage = "<span style='font-weight:normal;'>" + vData.Package + "</span>";

			tdDescription = "<span style='font-weight:normal;'>" + vData.Description + "</span>";
			tdPackagePrice = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.PackagePrice,2) + "</span>";
			tdProductWorth = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.ProductWorth,2) + "</span>";

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

			    if(rowData[0] == vData.PackageID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdOption;
			    	curData[2] = tdPackage;
			    	curData[3] = tdDescription;
			    	curData[4] = tdPackagePrice;
			    	curData[5] = tdProductWorth;
			    	curData[6] = tdStatus;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdID,
						tdOption,
						tdPackage,
						tdDescription, 
						tdPackagePrice,
						tdProductWorth,
						tdStatus
					]).draw();			
			}

	    }

	    function Clearfields(){

			$("#PackageID").val('0');

			$("#Package").val('');
			$("#Status").val('Active').change();
			
			$("#Description").val('');
			window.parent.tinymce.get('Description').setContent('');

			$("#PackagePrice").val('0.00');
			$("#ProductWorth").val('0.00');
			$("#SponsorCommission").val('0.00');
			$("#PackageColor").val('#00000');
			$("#PackageProduct").val('').change();

			$("#NoOfEntryShare").val('0');
			$("#EntryShareAmount").val('0.00');
			$("#MaxShareAmount").val('0.00');

			$("#RequiredBPV").val('0.00');
			$("#PairingAmount").val('0.00');
			$("#MaxMatchPerDay").val('0.00');
			$("#VoucherOnNthPair").val('5');

			$("#RebatesMaintainingBal").val('0.00');
			$("#PersonalRebatesPercent").val('0.00');
			$("#RebateLevel1Percent").val('0.00');
			$("#RebateLevel2Percent").val('0.00');
			$("#RebateLevel3Percent").val('0.00');
			$("#RebateLevel4Percent").val('0.00');
			$("#RebateLevel5Percent").val('0.00');
			$("#RebateLevel6Percent").val('0.00');
			$("#RebateLevel7Percent").val('0.00');
			$("#RebateLevel8Percent").val('0.00');
			$("#RebateLevel9Percent").val('0.00');

			$("#RankLevel1").val('');
			$("#RankLevel1APPRV").val('0.00');
			$("#RankLevel1AGPRV").val('0.00');
			$("#RankLevel1Percent").val('0.00');
			$("#RankLevel2").val('');
			$("#RankLevel2APPRV").val('0.00');
			$("#RankLevel2AGPRV").val('0.00');
			$("#RankLevel2Percent").val('0.00');
			$("#RankLevel3").val('');
			$("#RankLevel3APPRV").val('0.00');
			$("#RankLevel3AGPRV").val('0.00');
			$("#RankLevel3Percent").val('0.00');
			$("#RankLevel4").val('');
			$("#RankLevel4APPRV").val('0.00');
			$("#RankLevel4AGPRV").val('0.00');
			$("#RankLevel4Percent").val('0.00');
			$("#RankLevel5").val('');
			$("#RankLevel5APPRV").val('0.00');
			$("#RankLevel5AGPRV").val('0.00');
			$("#RankLevel5Percent").val('0.00');
			$("#RankLevel6").val('');
			$("#RankLevel6APPRV").val('0.00');
			$("#RankLevel6AGPRV").val('0.00');
			$("#RankLevel6Percent").val('0.00');
			$("#RankLevel7").val('');
			$("#RankLevel7APPRV").val('0.00');
			$("#RankLevel7AGPRV").val('0.00');
			$("#RankLevel7Percent").val('0.00');
			$("#RankLevel8").val('');
			$("#RankLevel8APPRV").val('0.00');
			$("#RankLevel8AGPRV").val('0.00');
			$("#RankLevel8Percent").val('0.00');
			$("#RankLevel9").val('');
			$("#RankLevel9APPRV").val('0.00');
			$("#RankLevel9AGPRV").val('0.00');
			$("#RankLevel9Percent").val('0.00');

	    }

	    function NewRecord(){

	    	isNewRecord = 1;
			Clearfields();

			$("#record-info-modal").modal();
	    }

	    function EditSettings(vRecordID){

	    	if(vRecordID > 0){
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						PackageID: vRecordID
					},
					url: "{{ route('get-package-info') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnSave", "Save", false);

						Clearfields();

						if(data.Response =='Success' && data.PackageInfo != undefined){

							$("#PackageID").val(data.PackageInfo.PackageID);
							$("#Package").val(data.PackageInfo.Package);
							$("#Status").val(data.PackageInfo.Status).change();

							window.parent.tinymce.get('Description').setContent(data.PackageInfo.Description);

							$("#PackagePrice").val(FormatDecimal(data.PackageInfo.PackagePrice,2));
							$("#ProductWorth").val(FormatDecimal(data.PackageInfo.ProductWorth,2));
							$("#SponsorCommission").val(FormatDecimal(data.PackageInfo.SponsorCommission,2));

							$("#PackageColor").val(data.PackageInfo.PackageColor);

							$("#PackageProduct").val((data.PackageInfo.ProductID > 0 ? data.PackageInfo.ProductID : '')).change();

							$("#NoOfEntryShare").val(FormatDecimal(data.PackageInfo.NoOfEntryShare,0));
							$("#EntryShareAmount").val(FormatDecimal(data.PackageInfo.EntryShareAmount,2));
							$("#MaxShareAmount").val(FormatDecimal(data.PackageInfo.MaxShareAmount,2));

							$("#RequiredBPV").val(FormatDecimal(data.PackageInfo.RequiredBPV,0));
							$("#PairingAmount").val(FormatDecimal(data.PackageInfo.PairingAmount,2));
							$("#MaxMatchPerDay").val(FormatDecimal(data.PackageInfo.MaxMatchPerDay,0));
							$("#VoucherOnNthPair").val(FormatDecimal(data.PackageInfo.VoucherOnNthPair,0));

							$("#RebatesMaintainingBal").val(FormatDecimal(data.PackageInfo.RebatesMaintainingBal,2));
							$("#PersonalRebatesPercent").val(FormatDecimal(data.PackageInfo.PersonalRebatesPercent,2));
							$("#RebateLevel1Percent").val(FormatDecimal(data.PackageInfo.RebateLevel1Percent,2));
							$("#RebateLevel2Percent").val(FormatDecimal(data.PackageInfo.RebateLevel2Percent,2));
							$("#RebateLevel3Percent").val(FormatDecimal(data.PackageInfo.RebateLevel3Percent,2));
							$("#RebateLevel4Percent").val(FormatDecimal(data.PackageInfo.RebateLevel4Percent,2));
							$("#RebateLevel5Percent").val(FormatDecimal(data.PackageInfo.RebateLevel5Percent,2));
							$("#RebateLevel6Percent").val(FormatDecimal(data.PackageInfo.RebateLevel6Percent,2));
							$("#RebateLevel7Percent").val(FormatDecimal(data.PackageInfo.RebateLevel7Percent,2));
							$("#RebateLevel8Percent").val(FormatDecimal(data.PackageInfo.RebateLevel8Percent,2));
							$("#RebateLevel9Percent").val(FormatDecimal(data.PackageInfo.RebateLevel9Percent,2));


							$("#RankLevel1").val(data.PackageInfo.RankLevel1);
							$("#RankLevel1APPRV").val(FormatDecimal(data.PackageInfo.RankLevel1APPRV,2));
							$("#RankLevel1AGPRV").val(FormatDecimal(data.PackageInfo.RankLevel1AGPRV,2));
							$("#RankLevel1Percent").val(FormatDecimal(data.PackageInfo.RankLevel1Percent,2));

							$("#RankLevel2").val(data.PackageInfo.RankLevel2);
							$("#RankLevel2APPRV").val(FormatDecimal(data.PackageInfo.RankLevel2APPRV,2));
							$("#RankLevel2AGPRV").val(FormatDecimal(data.PackageInfo.RankLevel2AGPRV,2));
							$("#RankLevel2Percent").val(FormatDecimal(data.PackageInfo.RankLevel2Percent,2));

							$("#RankLevel3").val(data.PackageInfo.RankLevel3);
							$("#RankLevel3APPRV").val(FormatDecimal(data.PackageInfo.RankLevel3APPRV,2));
							$("#RankLevel3AGPRV").val(FormatDecimal(data.PackageInfo.RankLevel3AGPRV,2));
							$("#RankLevel3Percent").val(FormatDecimal(data.PackageInfo.RankLevel3Percent,2));

							$("#RankLevel4").val(data.PackageInfo.RankLevel4);
							$("#RankLevel4APPRV").val(FormatDecimal(data.PackageInfo.RankLevel4APPRV,2));
							$("#RankLevel4AGPRV").val(FormatDecimal(data.PackageInfo.RankLevel4AGPRV,2));
							$("#RankLevel4Percent").val(FormatDecimal(data.PackageInfo.RankLevel4Percent,2));

							$("#RankLevel5Rank").val(data.PackageInfo.RankLevel5);
							$("#RankLevel5APPRV").val(FormatDecimal(data.PackageInfo.RankLevel5APPRV,2));
							$("#RankLevel5AGPRV").val(FormatDecimal(data.PackageInfo.RankLevel5AGPRV,2));
							$("#RankLevel5Percent").val(FormatDecimal(data.PackageInfo.RankLevel5Percent,2));

							$("#RankLevel6").val(data.PackageInfo.RankLevel6);
							$("#RankLevel6APPRV").val(FormatDecimal(data.PackageInfo.RankLevel6APPRV,2));
							$("#RankLevel6AGPRV").val(FormatDecimal(data.PackageInfo.RankLevel6AGPRV,2));
							$("#RankLevel6Percent").val(FormatDecimal(data.PackageInfo.RankLevel6Percent,2));

							$("#RankLevel7").val(data.PackageInfo.RankLevel7);
							$("#RankLevel7APPRV").val(FormatDecimal(data.PackageInfo.RankLevel7APPRV,2));
							$("#RankLevel7AGPRV").val(FormatDecimal(data.PackageInfo.RankLevel7AGPRV,2));
							$("#RankLevel7Percent").val(FormatDecimal(data.PackageInfo.RankLevel7Percent,2));

							$("#RankLevel8Rank").val(data.PackageInfo.RankLevel8);
							$("#RankLevel8APPRV").val(FormatDecimal(data.PackageInfo.RankLevel8APPRV,2));
							$("#RankLevel8AGPRV").val(FormatDecimal(data.PackageInfo.RankLevel8AGPRV,2));
							$("#RankLevel8Percent").val(FormatDecimal(data.PackageInfo.RankLevel8Percent,2));

							$("#RankLevel9").val(data.PackageInfo.RankLevel9);
							$("#RankLevel9APPRV").val(FormatDecimal(data.PackageInfo.RankLevel9APPRV,2));
							$("#RankLevel9AGPRV").val(FormatDecimal(data.PackageInfo.RankLevel9AGPRV,2));
							$("#RankLevel9Percent").val(FormatDecimal(data.PackageInfo.RankLevel9Percent,2));

							$("#record-info-modal").modal();

						}else{
							showJSModalMessageJS("Package Information",data.ResponseMessage,"OK");
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

	    	var PackagePrice = 0;
    		if($('#PackagePrice').length){
    			if($("#PackagePrice").val() != ""){
		            var strPackagePrice = $("#PackagePrice").val();
		            PackagePrice = parseInt(strPackagePrice.replace(",",""));
    			}
			}
	    	var ProductWorth = 0;
    		if($('#ProductWorth').length){
    			if($("#ProductWorth").val() != ""){
		            var strProductWorth = $("#ProductWorth").val();
		            ProductWorth = parseFloat(strProductWorth.replace(",",""));
    			}
			}
			var SponsorCommission = 0;
    		if($('#SponsorCommission').length){
    			if($("#SponsorCommission").val() != ""){
		            var strSponsorCommission = $("#SponsorCommission").val();
		            SponsorCommission = parseFloat(strSponsorCommission.replace(",",""));
    			}
			}

	    	var NoOfEntryShare = 0;
    		if($('#NoOfEntryShare').length){
    			if($("#NoOfEntryShare").val() != ""){
		            var strNoOfEntryShare = $("#NoOfEntryShare").val();
		            NoOfEntryShare = parseInt(strNoOfEntryShare.replace(",",""));
    			}
			}
	    	var EntryShareAmount = 0;
    		if($('#EntryShareAmount').length){
    			if($("#EntryShareAmount").val() != ""){
		            var strEntryShareAmount = $("#EntryShareAmount").val();
		            EntryShareAmount = parseFloat(strEntryShareAmount.replace(",",""));
    			}
			}
			var MaxShareAmount = 0;
    		if($('#MaxShareAmount').length){
    			if($("#MaxShareAmount").val() != ""){
		            var strMaxShareAmount = $("#MaxShareAmount").val();
		            MaxShareAmount = parseFloat(strMaxShareAmount.replace(",",""));
    			}
			}

	    	var RequiredBPV = 0;
    		if($('#RequiredBPV').length){
    			if($("#RequiredBPV").val() != ""){
		            var strRequiredBPV = $("#RequiredBPV").val();
		            RequiredBPV = parseInt(strRequiredBPV.replace(",",""));
    			}
			}
	    	var PairingAmount = 0;
    		if($('#PairingAmount').length){
    			if($("#PairingAmount").val() != ""){
		            var strPairingAmount = $("#PairingAmount").val();
		            PairingAmount = parseFloat(strPairingAmount.replace(",",""));
    			}
			}
			var MaxMatchPerDay = 0;
    		if($('#MaxMatchPerDay').length){
    			if($("#MaxMatchPerDay").val() != ""){
		            var strMaxMatchPerDay = $("#MaxMatchPerDay").val();
		            MaxMatchPerDay = parseFloat(strMaxMatchPerDay.replace(",",""));
    			}
			}
	    	var VoucherOnNthPair = 0;
    		if($('#VoucherOnNthPair').length){
    			if($("#VoucherOnNthPair").val() != ""){
		            var strVoucherOnNthPair = $("#VoucherOnNthPair").val();
		            VoucherOnNthPair = parseFloat(strVoucherOnNthPair.replace(",",""));
    			}
			}	   

	    	var RebatesMaintainingBal = 0;
    		if($('#RebatesMaintainingBal').length){
    			if($("#RebatesMaintainingBal").val() != ""){
		            var strRebatesMaintainingBal = $("#RebatesMaintainingBal").val();
		            RebatesMaintainingBal = parseFloat(strRebatesMaintainingBal.replace(",",""));
    			}
			}	    	
	    	var PersonalRebatesPercent = 0;
    		if($('#PersonalRebatesPercent').length){
    			if($("#PersonalRebatesPercent").val() != ""){
		            var strPersonalRebatesPercent = $("#PersonalRebatesPercent").val();
		            PersonalRebatesPercent = parseFloat(strPersonalRebatesPercent.replace(",",""));
    			}
			}	    	
	    	var RebateLevel1Percent = 0;
    		if($('#RebateLevel1Percent').length){
    			if($("#RebateLevel1Percent").val() != ""){
		            var strRebateLevel1Percent = $("#RebateLevel1Percent").val();
		            RebateLevel1Percent = parseFloat(strRebateLevel1Percent.replace(",",""));
    			}
			}	    	
	    	var RebateLevel2Percent = 0;
    		if($('#RebateLevel2Percent').length){
    			if($("#RebateLevel2Percent").val() != ""){
		            var strRebateLevel2Percent = $("#RebateLevel2Percent").val();
		            RebateLevel2Percent = parseFloat(strRebateLevel2Percent.replace(",",""));
    			}
			}	    	
	    	var RebateLevel3Percent = 0;
    		if($('#RebateLevel3Percent').length){
    			if($("#RebateLevel3Percent").val() != ""){
		            var strRebateLevel3Percent = $("#RebateLevel3Percent").val();
		            RebateLevel3Percent = parseFloat(strRebateLevel3Percent.replace(",",""));
    			}
			}	    	
	    	var RebateLevel4Percent = 0;
    		if($('#RebateLevel4Percent').length){
    			if($("#RebateLevel4Percent").val() != ""){
		            var strRebateLevel4Percent = $("#RebateLevel4Percent").val();
		            RebateLevel4Percent = parseFloat(strRebateLevel4Percent.replace(",",""));
    			}
			}	    	
	    	var RebateLevel5Percent = 0;
    		if($('#RebateLevel5Percent').length){
    			if($("#RebateLevel5Percent").val() != ""){
		            var strRebateLevel5Percent = $("#RebateLevel5Percent").val();
		            RebateLevel5Percent = parseFloat(strRebateLevel5Percent.replace(",",""));
    			}
			}	    	
	    	var RebateLevel6Percent = 0;
    		if($('#RebateLevel6Percent').length){
    			if($("#RebateLevel6Percent").val() != ""){
		            var strRebateLevel6Percent = $("#RebateLevel6Percent").val();
		            RebateLevel6Percent = parseFloat(strRebateLevel6Percent.replace(",",""));
    			}
			}	    	
	    	var RebateLevel7Percent = 0;
    		if($('#RebateLevel7Percent').length){
    			if($("#RebateLevel7Percent").val() != ""){
		            var strRebateLevel7Percent = $("#RebateLevel7Percent").val();
		            RebateLevel7Percent = parseFloat(strRebateLevel7Percent.replace(",",""));
    			}
			}	    	
	    	var RebateLevel8Percent = 0;
    		if($('#RebateLevel8Percent').length){
    			if($("#RebateLevel8Percent").val() != ""){
		            var strRebateLevel8Percent = $("#RebateLevel8Percent").val();
		            RebateLevel8Percent = parseFloat(strRebateLevel8Percent.replace(",",""));
    			}
			}	    	
	    	var RebateLevel9Percent = 0;
    		if($('#RebateLevel9Percent').length){
    			if($("#RebateLevel9Percent").val() != ""){
		            var strRebateLevel9Percent = $("#RebateLevel9Percent").val();
		            RebateLevel9Percent = parseFloat(strRebateLevel9Percent.replace(",",""));
    			}
			}	    	

	    	var RankLevel1APPRV = 0;
    		if($('#RankLevel1APPRV').length){
    			if($("#RankLevel1APPRV").val() != ""){
		            var strRankLevel1APPRV = $("#RankLevel1APPRV").val();
		            RankLevel1APPRV = parseFloat(strRankLevel1APPRV.replace(",",""));
    			}
			}
	    	var RankLevel1AGPRV = 0;
    		if($('#RankLevel1AGPRV').length){
    			if($("#RankLevel1AGPRV").val() != ""){
		            var strRankLevel1AGPRV = $("#RankLevel1AGPRV").val();
		            RankLevel1AGPRV = parseFloat(strRankLevel1AGPRV.replace(",",""));
    			}
			}
	    	var RankLevel1Percent = 0;
    		if($('#RankLevel1Percent').length){
    			if($("#RankLevel1Percent").val() != ""){
		            var strRankLevel1Percent = $("#RankLevel1Percent").val();
		            RankLevel1Percent = parseFloat(strRankLevel1Percent.replace(",",""));
    			}
			}

	    	var RankLevel2APPRV = 0;
    		if($('#RankLevel2APPRV').length){
    			if($("#RankLevel2APPRV").val() != ""){
		            var strRankLevel2APPRV = $("#RankLevel2APPRV").val();
		            RankLevel2APPRV = parseFloat(strRankLevel2APPRV.replace(",",""));
    			}
			}
	    	var RankLevel2AGPRV = 0;
    		if($('#RankLevel2AGPRV').length){
    			if($("#RankLevel2AGPRV").val() != ""){
		            var strRankLevel2AGPRV = $("#RankLevel2AGPRV").val();
		            RankLevel2AGPRV = parseFloat(strRankLevel2AGPRV.replace(",",""));
    			}
			}
	    	var RankLevel2Percent = 0;
    		if($('#RankLevel2Percent').length){
    			if($("#RankLevel2Percent").val() != ""){
		            var strRankLevel2Percent = $("#RankLevel2Percent").val();
		            RankLevel2Percent = parseFloat(strRankLevel2Percent.replace(",",""));
    			}
			}

	    	var RankLevel3APPRV = 0;
    		if($('#RankLevel3APPRV').length){
    			if($("#RankLevel3APPRV").val() != ""){
		            var strRankLevel3APPRV = $("#RankLevel3APPRV").val();
		            RankLevel3APPRV = parseFloat(strRankLevel3APPRV.replace(",",""));
    			}
			}
	    	var RankLevel3AGPRV = 0;
    		if($('#RankLevel3AGPRV').length){
    			if($("#RankLevel3AGPRV").val() != ""){
		            var strRankLevel3AGPRV = $("#RankLevel3AGPRV").val();
		            RankLevel3AGPRV = parseFloat(strRankLevel3AGPRV.replace(",",""));
    			}
			}
	    	var RankLevel3Percent = 0;
    		if($('#RankLevel3Percent').length){
    			if($("#RankLevel3Percent").val() != ""){
		            var strRankLevel3Percent = $("#RankLevel3Percent").val();
		            RankLevel3Percent = parseFloat(strRankLevel3Percent.replace(",",""));
    			}
			}

	    	var RankLevel4APPRV = 0;
    		if($('#RankLevel4APPRV').length){
    			if($("#RankLevel4APPRV").val() != ""){
		            var strRankLevel4APPRV = $("#RankLevel4APPRV").val();
		            RankLevel4APPRV = parseFloat(strRankLevel4APPRV.replace(",",""));
    			}
			}
	    	var RankLevel4AGPRV = 0;
    		if($('#RankLevel4AGPRV').length){
    			if($("#RankLevel4AGPRV").val() != ""){
		            var strRankLevel4AGPRV = $("#RankLevel4AGPRV").val();
		            RankLevel4AGPRV = parseFloat(strRankLevel4AGPRV.replace(",",""));
    			}
			}
	    	var RankLevel4Percent = 0;
    		if($('#RankLevel4Percent').length){
    			if($("#RankLevel4Percent").val() != ""){
		            var strRankLevel4Percent = $("#RankLevel4Percent").val();
		            RankLevel4Percent = parseFloat(strRankLevel4Percent.replace(",",""));
    			}
			}

	    	var RankLevel5APPRV = 0;
    		if($('#RankLevel5APPRV').length){
    			if($("#RankLevel5APPRV").val() != ""){
		            var strRankLevel5APPRV = $("#RankLevel5APPRV").val();
		            RankLevel5APPRV = parseFloat(strRankLevel5APPRV.replace(",",""));
    			}
			}
	    	var RankLevel5AGPRV = 0;
    		if($('#RankLevel5AGPRV').length){
    			if($("#RankLevel5AGPRV").val() != ""){
		            var strRankLevel5AGPRV = $("#RankLevel5AGPRV").val();
		            RankLevel5AGPRV = parseFloat(strRankLevel5AGPRV.replace(",",""));
    			}
			}
	    	var RankLevel5Percent = 0;
    		if($('#RankLevel5Percent').length){
    			if($("#RankLevel5Percent").val() != ""){
		            var strRankLevel5Percent = $("#RankLevel5Percent").val();
		            RankLevel5Percent = parseFloat(strRankLevel5Percent.replace(",",""));
    			}
			}

	    	var RankLevel6APPRV = 0;
    		if($('#RankLevel6APPRV').length){
    			if($("#RankLevel6APPRV").val() != ""){
		            var strRankLevel6APPRV = $("#RankLevel6APPRV").val();
		            RankLevel6APPRV = parseFloat(strRankLevel6APPRV.replace(",",""));
    			}
			}
	    	var RankLevel6AGPRV = 0;
    		if($('#RankLevel6AGPRV').length){
    			if($("#RankLevel6AGPRV").val() != ""){
		            var strRankLevel6AGPRV = $("#RankLevel6AGPRV").val();
		            RankLevel6AGPRV = parseFloat(strRankLevel6AGPRV.replace(",",""));
    			}
			}
	    	var RankLevel6Percent = 0;
    		if($('#RankLevel6Percent').length){
    			if($("#RankLevel6Percent").val() != ""){
		            var strRankLevel6Percent = $("#RankLevel6Percent").val();
		            RankLevel6Percent = parseFloat(strRankLevel6Percent.replace(",",""));
    			}
			}

	    	var RankLevel7APPRV = 0;
    		if($('#RankLevel7APPRV').length){
    			if($("#RankLevel7APPRV").val() != ""){
		            var strRankLevel7APPRV = $("#RankLevel7APPRV").val();
		            RankLevel7APPRV = parseFloat(strRankLevel7APPRV.replace(",",""));
    			}
			}
	    	var RankLevel7AGPRV = 0;
    		if($('#RankLevel7AGPRV').length){
    			if($("#RankLevel7AGPRV").val() != ""){
		            var strRankLevel7AGPRV = $("#RankLevel7AGPRV").val();
		            RankLevel7AGPRV = parseFloat(strRankLevel7AGPRV.replace(",",""));
    			}
			}
	    	var RankLevel7Percent = 0;
    		if($('#RankLevel7Percent').length){
    			if($("#RankLevel7Percent").val() != ""){
		            var strRankLevel7Percent = $("#RankLevel7Percent").val();
		            RankLevel7Percent = parseFloat(strRankLevel7Percent.replace(",",""));
    			}
			}

	    	var RankLevel8APPRV = 0;
    		if($('#RankLevel8APPRV').length){
    			if($("#RankLevel8APPRV").val() != ""){
		            var strRankLevel8APPRV = $("#RankLevel8APPRV").val();
		            RankLevel8APPRV = parseFloat(strRankLevel8APPRV.replace(",",""));
    			}
			}
	    	var RankLevel8AGPRV = 0;
    		if($('#RankLevel8AGPRV').length){
    			if($("#RankLevel8AGPRV").val() != ""){
		            var strRankLevel8AGPRV = $("#RankLevel8AGPRV").val();
		            RankLevel8AGPRV = parseFloat(strRankLevel8AGPRV.replace(",",""));
    			}
			}
	    	var RankLevel8Percent = 0;
    		if($('#RankLevel8Percent').length){
    			if($("#RankLevel8Percent").val() != ""){
		            var strRankLevel8Percent = $("#RankLevel8Percent").val();
		            RankLevel8Percent = parseFloat(strRankLevel8Percent.replace(",",""));
    			}
			}


	    	var RankLevel9APPRV = 0;
    		if($('#RankLevel9APPRV').length){
    			if($("#RankLevel9APPRV").val() != ""){
		            var strRankLevel9APPRV = $("#RankLevel9APPRV").val();
		            RankLevel9APPRV = parseFloat(strRankLevel9APPRV.replace(",",""));
    			}
			}
	    	var RankLevel9AGPRV = 0;
    		if($('#RankLevel9AGPRV').length){
    			if($("#RankLevel9AGPRV").val() != ""){
		            var strRankLevel9AGPRV = $("#RankLevel9AGPRV").val();
		            RankLevel9AGPRV = parseFloat(strRankLevel9AGPRV.replace(",",""));
    			}
			}
	    	var RankLevel9Percent = 0;
    		if($('#RankLevel9Percent').length){
    			if($("#RankLevel9Percent").val() != ""){
		            var strRankLevel9Percent = $("#RankLevel9Percent").val();
		            RankLevel9Percent = parseFloat(strRankLevel9Percent.replace(",",""));
    			}
			}

			if($('#Package').val() == "") {
				showJSMessage("Package Information","Please enter package name.","OK");

			}else if(PackagePrice == 0) {
				showJSMessage("Package Information","Please enter package price.","OK");
			}else if(ProductWorth == 0) {
				showJSMessage("Package Information","Please enter package worth of products.","OK");
			}else if(SponsorCommission == 0) {
				showJSMessage("Package Information","Please enter sponsor commission.","OK");
/*
			}else if(NoOfEntryShare == 0) {
				showJSMessage("Package Entry Share Settings","Please enter number of entry share.","OK");
			}else if(EntryShareAmount == 0) {
				showJSMessage("Package Entry Share Settings","Please enter entry share amount.","OK");
			}else if(MaxShareAmount == 0) {
				showJSMessage("Package Entry Share Settings","Please enter maximum share amount.","OK");

			}else if(RequiredBPV == 0) {
				showJSMessage("Package Matching Commission Settings","Please enter required BPV.","OK");
			}else if(PairingAmount == 0) {
				showJSMessage("Package Matching Commission Settings","Please enter matching pairing amount.","OK");
			}else if(MaxMatchPerDay == 0) {
				showJSMessage("Package Matching Commission Settings","Please enter maximum match per day.","OK");

			}else if(RebatesMaintainingBal == 0) {
				showJSMessage("Package Rebates Settings","Please enter maintaining balance to acquire rebates.","OK");
			}else if(PersonalRebatesPercent == 0) {
				showJSMessage("Package Rebates Settings","Please enter personal rebates percentage.","OK");
			}else if(RebateLevel1Percent == 0) {
				showJSMessage("Package Rebates Settings","Please enter Level 1 rebates percentage.","OK");
			}else if(RebateLevel2Percent == 0) {
				showJSMessage("Package Rebates Settings","Please enter Level 2 rebates percentage.","OK");
			}else if(RebateLevel3Percent == 0) {
				showJSMessage("Package Rebates Settings","Please enter Level 3 rebates percentage.","OK");
			}else if(RebateLevel4Percent == 0) {
				showJSMessage("Package Rebates Settings","Please enter Level 4 rebates percentage.","OK");
			}else if(RebateLevel5Percent == 0) {
				showJSMessage("Package Rebates Settings","Please enter Level 5 rebates percentage.","OK");
			}else if(RebateLevel6Percent == 0) {
				showJSMessage("Package Rebates Settings","Please enter Level 6 rebates percentage.","OK");
			}else if(RebateLevel7Percent == 0) {
				showJSMessage("Package Rebates Settings","Please enter Level 7 rebates percentage.","OK");
			}else if(RebateLevel8Percent == 0) {
				showJSMessage("Package Rebates Settings","Please enter Level 8 rebates percentage.","OK");
			}else if(RebateLevel9Percent == 0) {
				showJSMessage("Package Rebates Settings","Please enter Level 9 rebates percentage.","OK");

			}else if(RankLevel5APPRV == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 5 required personal accumulated purchases.","OK");
			}else if(RankLevel5AGPRV == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 5 required group accumulated purchases.","OK");
			}else if(RankLevel5Percent == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 5 percentage benefits.","OK");

			}else if(RankLevel6APPRV == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 6 required personal accumulated purchases.","OK");
			}else if(RankLevel6AGPRV == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 6 required group accumulated purchases.","OK");
			}else if(RankLevel6Percent == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 6 percentage benefits.","OK");

			}else if(RankLevel7APPRV == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 7 required personal accumulated purchases.","OK");
			}else if(RankLevel7AGPRV == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 7 required group accumulated purchases.","OK");
			}else if(RankLevel7Percent == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 7 percentage benefits.","OK");

			}else if(RankLevel8APPRV == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 8 required personal accumulated purchases.","OK");
			}else if(RankLevel8AGPRV == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 8 required group accumulated purchases.","OK");
			}else if(RankLevel8Percent == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 8 percentage benefits.","OK");

			}else if(RankLevel9APPRV == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 9 required personal accumulated purchases.","OK");
			}else if(RankLevel9AGPRV == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 9 required group accumulated purchases.","OK");
			}else if(RankLevel9Percent == 0) {
				showJSMessage("Package Rank Settings","Please enter Level 9 percentage benefits.","OK");
*/

			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						PackageID: $("#PackageID").val(),
						Package: $("#Package").val(),
						Description: window.parent.tinymce.get('Description').getContent(),
						Status: $("#Status").val(),

						PackagePrice: PackagePrice,
						ProductWorth: ProductWorth,
						SponsorCommission: SponsorCommission,
						PackageColor: $("#PackageColor").val(),

						ProductID: $("#PackageProduct").val(),
						
						NoOfEntryShare: NoOfEntryShare,
						EntryShareAmount: EntryShareAmount,
						MaxShareAmount: MaxShareAmount,

						RequiredBPV: RequiredBPV,
						PairingAmount: PairingAmount,
						MaxMatchPerDay: MaxMatchPerDay,
						VoucherOnNthPair: VoucherOnNthPair,

						RebatesMaintainingBal: RebatesMaintainingBal,
						PersonalRebatesPercent: PersonalRebatesPercent,
						RebateLevel1Percent: RebateLevel1Percent,
						RebateLevel2Percent: RebateLevel2Percent,
						RebateLevel3Percent: RebateLevel3Percent,
						RebateLevel4Percent: RebateLevel4Percent,
						RebateLevel5Percent: RebateLevel5Percent,
						RebateLevel6Percent: RebateLevel6Percent,
						RebateLevel7Percent: RebateLevel7Percent,
						RebateLevel8Percent: RebateLevel8Percent,
						RebateLevel9Percent: RebateLevel9Percent,

						RankLevel1: $("#RankLevel1").val(),
						RankLevel1APPRV: RankLevel1APPRV,
						RankLevel1AGPRV: RankLevel1AGPRV,
						RankLevel1Percent: RankLevel1Percent,

						RankLevel2: $("#RankLevel2").val(),
						RankLevel2APPRV: RankLevel2APPRV,
						RankLevel2AGPRV: RankLevel2AGPRV,
						RankLevel2Percent: RankLevel2Percent,

						RankLevel3: $("#RankLevel3").val(),
						RankLevel3APPRV: RankLevel3APPRV,
						RankLevel3AGPRV: RankLevel3AGPRV,
						RankLevel3Percent: RankLevel3Percent,

						RankLevel4: $("#RankLevel4").val(),
						RankLevel4APPRV: RankLevel4APPRV,
						RankLevel4AGPRV: RankLevel4AGPRV,
						RankLevel4Percent: RankLevel4Percent,

						RankLevel5: $("#RankLevel5").val(),
						RankLevel5APPRV: RankLevel5APPRV,
						RankLevel5AGPRV: RankLevel5AGPRV,
						RankLevel5Percent: RankLevel5Percent,

						RankLevel6: $("#RankLevel6").val(),
						RankLevel6APPRV: RankLevel6APPRV,
						RankLevel6AGPRV: RankLevel6AGPRV,
						RankLevel6Percent: RankLevel6Percent,

						RankLevel7: $("#RankLevel7").val(),
						RankLevel7APPRV: RankLevel7APPRV,
						RankLevel7AGPRV: RankLevel7AGPRV,
						RankLevel7Percent: RankLevel7Percent,

						RankLevel8: $("#RankLevel8").val(),
						RankLevel8APPRV: RankLevel8APPRV,
						RankLevel8AGPRV: RankLevel8AGPRV,
						RankLevel8Percent: RankLevel8Percent,

						RankLevel9: $("#RankLevel9").val(),
						RankLevel9APPRV: RankLevel9APPRV,
						RankLevel9AGPRV: RankLevel9AGPRV,
						RankLevel9Percent: RankLevel9Percent
					},
					url: "{{ route('do-save-package') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.PackageInfo);
						}else{
							showJSModalMessageJS("Save Package Information",data.ResponseMessage,"OK");
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
