@extends('layout.adminweb')

@section('content')
  
<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Product Management
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Product Management</li>
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
			                  <th>Product ID</th>
			                  <th></th>
			                  <th></th>
			                  <th>Product Code</th>
			                  <th>Product Name</th>
			                  <th>Net Weight</th>
			                  <th style="text-align : right;">Distributor Price</th>
			                  <th style="text-align : right;">Retail Price</th>
			                  <th style="text-align : right;">Rebatable Value</th>
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
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Product Information</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="ProductID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Brand <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="Brand" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Category <span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<select id="Category" class="form-control select2" style="width: 100%; font-weight: normal;" required>
								<option value="" selected>Please Select</option>
								<option value="Agriculture Product">Agriculture Product</option>
								<option value="Cosmetic Product">Cosmetic Product</option>
								<option value="Health and Wellness">Health and Wellness</option>
								<option value="Household Product">Household Product</option>
								<option value="Perfume">Perfume</option>
								<option value="Others">Others</option>
							</select>
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
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Product Code <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="ProductCode" type="text" class="form-control" value="" style="width:100%; font-weight: normal;">
		                </div>
	            	</div>
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal;">Product Name <span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="ProductName" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            </div>
	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Net Weight (gm)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="NetWeight" type="text" class="form-control" value="" style="width:100%; font-weight: normal;">
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
		                <label class="col-md-12" style="font-weight: normal;">Specification </label>
		                <div class="col-md-12">
	                		<textarea id="Specification" class="form-control wysiwyg" cols="40" rows="8"  placeholder="Product Specification" style="width:100%;"></textarea>
		                </div>
	            	</div>
	            </div>
	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Is this a complan package set?<span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<select id="IsPackageSet" class="form-control" style="font-weight: normal; width: 100%;">
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>
		                </div>
	            	</div>
            	</div>
	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-size: 25px; font-weight: bold;">Price Details </label>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                

	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Distributor Price <span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='DistributorPrice' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Retail Price <span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='RetailPrice' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Rebatable Value <span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<input id='Rebates' type='text' class='form-control DecimalOnly' value='' style='width:100%; text-align:right; font-weight: normal;'>
		                </div>
	            	</div>
	            </div>
	            <div class="row">
	            	<div class="col-md-6">
		                <label class="col-md-12" style="font-weight: normal;">Measurement <span style="color:red;">*</span></label>
		                <div class="col-md-12">
							<select id="Measurement" class="form-control select2" style="font-weight: normal; width: 100%; height: 100px;">
								<option value="">Please Select</option>
								<option value="BOT">Bottle</option>
								<option value="BOX">Box</option>
								<option value="PCK">Pack</option>
								<option value="PC">PC</option>
								<option value="SET">Set</option>
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

	<div id="upload-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">

          	<div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Product Photo</b></h4>
          	</div>

			<form class="" method="post" action="{{URL('do-upload-product-photo')}}" enctype="multipart/form-data">

          	<div class="modal-body">

                {!! csrf_field() !!}   <!--Token -->

	        	<input type="hidden" id="UploadProductID" name="ProductID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-12">
                       	<p class="help-block">

                     		Note: Fields marked with (*) denotes of required fields
                         	<br>Accepts jpg &amp; png image type with max [2048KB] file size,
                        	<br><span style="color:red;"> <b>Best Quality fit for product image dimension is 500x500</b></span>
                        </p>
                        <input type="file" accept="image/*"  name="productimage[]" onchange="loadFile(event)" required>
                        <br>
                        <div class="file-preview-frame">
                            <!--Product Image Display Here  -->
                            <img id="output" src="{{ URL::to('img/products/product-no-image-300x300.jpg') }}" style="max-width: 500px;" />
                       	</div>
                   	</div>
            	</div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="modal-footer">
					<div class="input-group pull-right">
						<span class="input-group-btn">
							<button type="submit" class="btn btn-info btn-flat">
								<i class="fa fa-save"></i> Upload
							</button>
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
				url: "{{ route('get-product-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.ProductList);
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

	    	tdProductID = vData.ProductID;

			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='EditInformation(" + vData.ProductID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Edit Information " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='UploadPhoto(" + vData.ProductID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Upload Photo " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> " +
	                        " </ul> " +
	                    " </div> " ;

			tdProductPhoto = "<img src='img/products/" + vData.ProductID + "/" + vData.ProductID + "-1-{{ config('app.Thumbnail') }}.jpg' style='max-width:50px;'>";

			tdProductCode = "<span style='font-weight:normal;'>" + vData.ProductCode + "</span>";
			tdProductName = "<span style='font-weight:normal;'>" + vData.ProductName + "</span>";
			tdNetWeight = "<span style='font-weight:normal;'>" + FormatDecimal(vData.NetWeight,2) + " gm</span>";

			tdDistributorPrice = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.DistributorPrice,2)  + "/" + vData.Measurement + "</span>";
			tdRetailPrice = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.RetailPrice,2)  + "/" + vData.Measurement + "</span>";
			tdRebates = "<span style='font-weight:normal;' class='pull-right'>" + FormatDecimal(vData.RebateValue,2) + "</span>";

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

			    if(rowData[0] == vData.ProductID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdProductID;
			    	curData[1] = tdOption;
			    	curData[2] = tdProductPhoto;
			    	curData[3] = tdProductCode;
			    	curData[4] = tdProductName;
			    	curData[5] = tdNetWeight;
			    	curData[6] = tdDistributorPrice;
			    	curData[7] = tdRetailPrice;
			    	curData[8] = tdRebates;
			    	curData[9] = tdStatus;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdProductID,
						tdOption,
						tdProductPhoto,
						tdProductCode,
						tdProductName,
						tdNetWeight,
						tdDistributorPrice,
						tdRetailPrice,
						tdRebates,
						tdStatus
					]).draw();			
			}

	    }

	    function Clearfields(){

			$("#ProductID").val('0');

			$("#Brand").val('SFI');
			$("#Category").val('').change();
			$("#Status").val('Active').change();

			$("#ProductCode").val('');
			$("#ProductName").val('');
			$("#NetWeight").val('');

			$("#IsPackageSet").val('0').change();

			window.parent.tinymce.get('Description').setContent('');
			window.parent.tinymce.get('Specification').setContent('');
			
			$("#Measurement").val('').change();
			$("#DistributorPrice").val('');
			$("#RetailPrice").val('');
			$("#Rebates").val('');

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
						ProductID: vRecordID
					},
					url: "{{ route('get-product-info') }}",
					dataType: "json",
					success: function(data){
						if(data.Response =='Success' && data.ProductInfo != undefined){

							$("#ProductID").val(data.ProductInfo.ProductID);

							$("#Brand").val(data.ProductInfo.Brand);
							$("#Category").val(data.ProductInfo.Category).change();
							$("#Status").val(data.ProductInfo.Status).change();

							$("#ProductCode").val(data.ProductInfo.ProductCode);
							$("#ProductName").val(data.ProductInfo.ProductName);
							window.parent.tinymce.get('Description').setContent(data.ProductInfo.Description);
							window.parent.tinymce.get('Specification').setContent(data.ProductInfo.Specification);
							
							$("#NetWeight").val(FormatDecimal(data.ProductInfo.NetWeight,2));
							
							$("#IsPackageSet").val(data.ProductInfo.IsPackageSet).change();

							$("#Measurement").val(data.ProductInfo.Measurement).change();
							$("#DistributorPrice").val(FormatDecimal(data.ProductInfo.DistributorPrice,2));
							$("#RetailPrice").val(FormatDecimal(data.ProductInfo.RetailPrice,2));
							$("#Rebates").val(FormatDecimal(data.ProductInfo.RebateValue,2));
							
							$("#record-info-modal").modal();
							buttonOneClick("btnSave", "Save", false);

						}else{
							showJSModalMessageJS("Product Information",data.ResponseMessage,"OK");
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

	    	var DistributorPrice = 0;
    		if($('#DistributorPrice').length){
    			if($("#DistributorPrice").val() != ""){
		            var strDistributorPrice = $("#DistributorPrice").val();
		            DistributorPrice = parseInt(strDistributorPrice.replace(",",""));
    			}
			}
	    	var RetailPrice = 0;
    		if($('#RetailPrice').length){
    			if($("#RetailPrice").val() != ""){
		            var strRetailPrice = $("#RetailPrice").val();
		            RetailPrice = parseInt(strRetailPrice.replace(",",""));
    			}
			}
	    	var Rebates = 0;
    		if($('#Rebates').length){
    			if($("#Rebates").val() != ""){
		            var strRebates = $("#Rebates").val();
		            Rebates = parseInt(strRebates.replace(",",""));
    			}
			}

			if($('#Brand').val() == "") {
				showJSMessage("Product Brand","Please enter product brand.","OK");
			}else if($('#Category').val() == "") {
				showJSMessage("Product Category","Please select product category.","OK");
			}else if($('#ProductName').val() == "") {
				showJSMessage("Product Name","Please enter product name.","OK");
			}else if($('#NetWeight').val() == "") {
				showJSMessage("Net Weight","Please enter net weight of the product.","OK");

			}else if($('#Measurement').val() == "") {
				showJSMessage("Measurement","Please select product measurement.","OK");
			}else if(DistributorPrice <= 0) {
				showJSMessage("Distributor Price","Please enter distributor price.","OK");
			}else if(RetailPrice <= 0) {
				showJSMessage("Retail Price","Please enter retail price.","OK");
			}else if(Rebates <= 0) {
				showJSMessage("Rebate Value","Please enter rebate value.","OK");

			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						ProductID: $("#ProductID").val(),
						Brand: $("#Brand").val(),
						Category: $("#Category").val(),
						Status: $("#Status").val(),

						ProductCode: $("#ProductCode").val(),
						ProductName: $("#ProductName").val(),
						NetWeight: $("#NetWeight").val(),

						IsPackageSet: $("#IsPackageSet").val(),
						
						Description: window.parent.tinymce.get('Description').getContent(),
						Specification: window.parent.tinymce.get('Specification').getContent(),

						Measurement: $("#Measurement").val(),
						DistributorPrice: DistributorPrice,
						RetailPrice: RetailPrice,
						RebateValue : Rebates
					},
					url: "{{ route('do-save-product') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.ProductInfo);
						}else{
							showJSModalMessageJS("Save Product Information",data.ResponseMessage,"OK");
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

	    function UploadPhoto(vRecordID){

			$("#UploadProductID").val(vRecordID);
			$("#upload-modal").modal();

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
