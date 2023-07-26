@extends('layout.adminweb')

@section('content')
  
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Shipper - J&T Settings
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Shipper Management</li>
			<li>Shipper - J&T Settings</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-theme">

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
			                  <th>Destination</th>
			                  <th>Weight Limit (KG)</th>
			                  <th>Rate</th>
			                  <th>Additional Rate/Kg</th>
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
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>J&T Settings Information</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="SettingsID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Destination <span style="color:red;">*</span></label>
		                <div class="col-md-12" style="font-weight: normal;">
							<select id="Destination" class="form-control select2" style="width: 100%; font-weight: normal;">
								<option value="" selected></option>
								<option value="NCR">NCR</option>
								<option value="Luzon">Luzon</option>
								<option value="Visayas">Visayas</option>
								<option value="Mindanao">Mindanao</option>
							</select>
		                </div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Weight Limit (KG)<span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="WeightLimit" type="text" class="form-control DecimalOnly" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Rates<span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="Rates" type="text" class="form-control DecimalOnly" value="" style="width:100%; font-weight: normal;" required>
		                </div>
	            	</div>
	            	<div class="col-md-4">
		                <label class="col-md-12" style="font-weight: normal;">Additional Rate/KG<span style="color:red;">*</span></label>
		                <div class="col-md-12">
		                    <input id="AdditionalRatesPerKg" type="text" class="form-control DecimalOnly" value="" style="width:100%; font-weight: normal;" required>
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
	            "order": [[ 2, "asc" ],[ 3, "asc" ]]
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
				url: "{{ route('get-shipper-jat-bracket') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.ShipperJATList);
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

	    	tdID = vData.SettingsID;

			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown'  style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> " +
	                          	" <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='EditRecord(" + vData.SettingsID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Edit Record " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";
            	tdOption += " </ul> " +
	                    " </div> " ;
			tdDestination = "<span style='font-weight:normal;'>" + vData.Destination + "</span>";

			tdWeightLimit = "<span style='font-weight:normal;'>" + FormatDecimal(vData.WeightLimit,2) + "</span>";
			tdRates = "<span style='font-weight:normal;'>" + FormatDecimal(vData.Rates,2) + "</span>";
			tdAdditionalRatesPerKg = "<span style='font-weight:normal;'>" + FormatDecimal(vData.AdditionalRatesPerKg,2) + "</span>";

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();

			    if(rowData[0] == vData.SettingsID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdOption;
			    	curData[2] = tdDestination;
			    	curData[3] = tdWeightLimit;
			    	curData[4] = tdRates;
			    	curData[5] = tdAdditionalRatesPerKg;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
			    	tdID,
			    	tdOption,
			    	tdDestination,
			    	tdWeightLimit,
			    	tdRates,
			    	tdAdditionalRatesPerKg
				]).draw();			
			}

	    }

	    function Clearfields(){

			$("#SettingsID").val('0');

			$("#Destination").val('').change();
	    	$("#WeightLimit").val('');
			$("#Rates").val('');
			$("#AdditionalRatesPerKg").val('');

			$("#btnSave").show();

	    }

	    function NewRecord(){

	    	isNewRecord = 1;
			Clearfields();

			$("#record-info-modal").modal();
	    }

	    function EditRecord(vRecordID){

	    	if(vRecordID > 0){

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						SettingsID: vRecordID
					},
					url: "{{ route('get-shipper-jat-bracket-info') }}",
					dataType: "json",
					success: function(data){

						if(data.Response =='Success' && data.ShipperJATInfo != undefined){

								$("#SettingsID").val(data.ShipperJATInfo.SettingsID);

								$("#Destination").val(data.ShipperJATInfo.Destination).change();
								$("#WeightLimit").val(FormatDecimal(data.ShipperJATInfo.WeightLimit,2));
								$("#Rates").val(FormatDecimal(data.ShipperJATInfo.Rates,2));
								$("#AdditionalRatesPerKg").val(FormatDecimal(data.ShipperJATInfo.AdditionalRatesPerKg,2));

								$("#record-info-modal").modal();

						}else{
							showJSModalMessageJS("Shipper - J&T Settings",data.ResponseMessage,"OK");
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

	    function SaveRecord(){

	    	var WeightLimit = 0;
    		if($('#WeightLimit').length){
    			if($("#WeightLimit").val() != ""){
		            var strWeightLimit = $("#WeightLimit").val();
		            WeightLimit = parseFloat(strWeightLimit.replace(",",""));
    			}
			}

	    	var Rates = 0;
    		if($('#Rates').length){
    			if($("#Rates").val() != ""){
		            var strRates = $("#Rates").val();
		            Rates = parseFloat(strRates.replace(",",""));
    			}
			}

	    	var AdditionalRatesPerKg = 0;
    		if($('#AdditionalRatesPerKg').length){
    			if($("#AdditionalRatesPerKg").val() != ""){
		            var strAdditionalRatesPerKg = $("#AdditionalRatesPerKg").val();
		            AdditionalRatesPerKg = parseFloat(strAdditionalRatesPerKg.replace(",",""));
    			}
			}
						
			if($('#Destination').val() == "") {
				showJSMessage("Destination","Please select destination.","OK");

			}else if(WeightLimit <= 0) {
				showJSMessage("Weight Limit","Please enter weight limit.","OK");

			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						SettingsID: $("#SettingsID").val(),
						Destination: $("#Destination").val(),
						WeightLimit: WeightLimit,
						Rates: Rates,
						AdditionalRatesPerKg: AdditionalRatesPerKg
					},
					url: "{{ route('do-save-shipper-jat-bracket') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.ShipperJATInfo);
						}else{
							showJSModalMessageJS("Save Shipper - J&T Settings",data.ResponseMessage,"OK");
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
