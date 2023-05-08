@extends('layout.adminweb')

@section('content')

@php($IsAllowSetBeginningBalance = Session('IS_SUPER_ADMIN')==true ? 'true' : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Allow Set Beginning Balance'))  
@php($IsAllowSetMinMaxLevel = Session('IS_SUPER_ADMIN')==true ? 'true' : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Allow Set Min/Max Level'))  

  
<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Inventory List
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Inventory Management</li>
			<li class="active">Inventory List</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-success">

		            <div class="box-header">
			            <div class="row">

					        <div class="col-md-3" style="margin-top: 12px;">
								<select id="SearchCenter" class="form-control input-sm pull-right select2" {{ Session('IS_SUPER_ADMIN') == 1 ? "" : "disabled" }}>
									@foreach($CenterList as $clist)
										<option value="{{ $clist->CenterID }}" {{ $clist->CenterID == Session('ADMIN_CENTER_ID') ? "selected" : "" }}>{{ $clist->Center }}</option>
									@endforeach
								</select>
					        </div>

			            	<div class="col-md-9">
								<div class="input-group margin pull-right">
									<input type="text" placeholder="Search Here..." class="form-control searchtext">
									<span class="input-group-btn">
										<a id="btnSearch" href="#" class="btn btn-success btn-flat" style="margin-right:5px;"><i class="fa fa-search"></i></a>
									</span>
								</div>		            
							</div>		            
						</div>		            
          			</div>

					@if(session('Success_Msg'))
			            <div class="box-header">
							<div class="alert alert-success alert-dismissible">
                				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                				<i class="icon fa fa-check"></i> {{session('Success_Msg')}}
              				</div>
						</div>
					@endif

					@if(session('Error_Msg'))
			            <div class="box-header">
							<div class="alert alert-danger alert-dismissible">
                				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                				<i class="icon fa fa-ban"></i> {{session('Error_Msg')}}
              				</div>
						</div>
					@endif

		            <div class="box-body table-responsive">

			            <div class="col-md-12">
				    	@include('inc.admin.adminmessage')
			            </div>

		              	<table id="tblList" class="table table-bordered table-hover">
		                <thead>
			                <tr>
			                  <th>ID</th>
			                  <th></th>
			                  <th></th>
			                  <th>Center</th>
			                  <th>Product Code</th>
			                  <th>Product Name</th>
			                  <th>Measurement</th>
			                  <th style="text-align: right;">Beginning Balance</th>
			                  <th style="text-align: right;">Total Stock In</th>
			                  <th style="text-align: right;">Total Stock Out</th>
			                  <th style="text-align: right;">Stock On Hand</th>
			                  <th style="text-align: right;">Minimum Level</th>
			                  <th style="text-align: right;">Maximum Level</th>
			                </tr>
		                </thead>
		                <tbody>
			            </tbody>

		              </table>
		            </div>


          		</div>
          	</div>
		</div>
	</section>
	<!-- /.content -->	

	<div id="begbal-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">

	          <div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Product Beginning Balance</b></h4>
	          </div>

	          <div class="modal-body">

	        	<input type="hidden" id="BegBalInvID" value="0" readonly>
	        	<input type="hidden" id="BegBalCenterID" value="0" readonly>
	        	<input type="hidden" id="BegBalProductID" value="0" readonly>
	            
	            <div class="row">
	            	<div class="col-md-8">
		                <label class="col-md-12" style="font-weight: normal; color: red;">Please note that when you set a beginning balance, it will reset the Total Stock In, Total Stock Out and Stock On Hand of the product.</label>
	            	</div>
            	</div>
	            <div style="clear:both;"></div>
	            <br>  

	            <div class="row">
	            	<div class="col-md-12">
		                <label class="col-md-12" style="font-weight: normal;">Beginning Balance Date/Time<span style="color:red;">*</span></label>
		                <div class="col-md-12">
                            <div class='input-group date' id='divBegBalDateTime'>
                                <input id='BegBalDateTime' name="BegBalDateTime" type='text' class="form-control" value="{{ date("m/d/Y") }}" readonly />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
		                </div>
	            	</div>
            	</div>
	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
                      	<label class="col-md-12">Beginning Balance</label>
                      	<div class="col-md-12"> 
                        	<div>
                            	<input id="BegBalance" name="BegBalance" type="text" class="form-control DecimalOnly" placeholder="Beginning Balance" value="0" style="width:150px; text-align: right;">
                         	</div>
                      	</div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
                      	<label class="col-md-12">Remarks</label>
                      	<div class="col-md-12">
                        	<div>
                            	<input id="BegBalRemarks" name="BegBalRemarks" type="text" class="form-control" placeholder="Remarks" value="">
                         	</div>
                      	</div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="modal-footer">
					<div class="input-group pull-right">
						<span class="input-group-btn">
							<a id="btnBegBalSave" href="#" class="btn btn-info btn-flat" onclick="SaveBegBal()"><i class="fa fa-save"></i> Save</a>
							<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Cancel</a>
						</span>
					</div>	
	            </div>

	          </div>


	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="minmax-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">

	          <div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Product Minimum/Maximum Level</b></h4>
	          </div>

	          <div class="modal-body">

	        	<input type="hidden" id="MinMaxInvID" value="0" readonly>
	        	<input type="hidden" id="MinMaxCenterID" value="0" readonly>
	        	<input type="hidden" id="MinMaxProductID" value="0" readonly>
	            
	            <div class="row">
	            	<div class="col-md-12">
                      	<label class="col-md-12">Minimum Level</label>
                      	<div class="col-md-12"> 
                        	<div>
                            	<input id="MinimumLevel" name="MinimumLevel" type="text" class="form-control DecimalOnly" placeholder="Minimum Level" value="0" style="width:150px; text-align: right;">
                         	</div>
                      	</div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="row">
	            	<div class="col-md-12">
                      	<label class="col-md-12">Maximum Level</label>
                      	<div class="col-md-12"> 
                        	<div>
                            	<input id="MaximumLevel" name="MaximumLevel" type="text" class="form-control DecimalOnly" placeholder="Maximum Level" value="0" style="width:150px; text-align: right;">
                         	</div>
                      	</div>
	            	</div>
	            </div>

	            <div style="clear:both;"></div>
	            <br>                
	            <div class="modal-footer">
					<div class="input-group pull-right">
						<span class="input-group-btn">
							<a id="btnMinMaxSave" href="#" class="btn btn-info btn-flat" onclick="SaveMinMax()"><i class="fa fa-save"></i> Save</a>
							<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Cancel</a>
						</span>
					</div>	
	            </div>

	          </div>


	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="inv-ledger-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">

	          <div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Inventory Ledger</b></h4>
	          </div>

	          <div class="modal-body">

	        	<input type="hidden" id="InvLedgerCenterID" value="0" readonly>
	        	<input type="hidden" id="InvLedgerProductID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-12">
		              	<table id="tblInvLedgerList" class="table table-bordered table-hover">
			                <thead>
				                <tr>
				                  <th style="width: 20%; ">Transaction Date/Time</th>
				                  <th style="width: 35%;">Transaction Type</th>
				                  <th style="width: 15%; text-align: right;">IN</th>
				                  <th style="width: 15%; text-align: right;">OUT</th>
				                  <th style="width: 15%; text-align: right;">Stock On Hand</th>
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
							<a id="btnInvLedgerLoadMore" href="#" class="btn btn-info btn-flat" onclick="LoadMoreInvLedger()"><i class="fa fa-save"></i> Load More</a>
							<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Close</a>
						</span>
					</div>	
	            </div>


	          </div>


	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<script type="text/javascript">

	    var intCurrentPage = 1;
	    var intLedgerPage = 1;
	    var isPageFirstLoad = true;

	    $(document).ready(function() {

            $('#divBegBalDateTime').datepicker({
                autoclose: true,
                format: 'mm/dd/yyyy'
            });

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
	            "order": [[ 10, "desc" ],[ 5, "asc" ]]
	        });

	        $('#tblInvLedgerList').DataTable( {
				'paging'      : false,
				'lengthChange': false,
				'searching'   : false,
				'ordering'    : false,
				'info'        : false,
				'autoWidth'   : false
	        });

	        //Load Initial Data
    		$("#tblList").DataTable().clear().draw();
	        getRecordList(1, '');
	        isPageFirstLoad = false;
	    });

	    $("#SearchCenter").change(function(){
	      	$("#tblList").DataTable().clear().draw();
	      	intCurrentPage = 1;
  			getRecordList(intCurrentPage, $('.searchtext').val());
	    });

	    $("#btnSearch").click(function(){
	      	intCurrentPage = 1;
    		$("#tblList").DataTable().clear().draw();
	        getRecordList(intCurrentPage, $('.searchtext').val());
	    });

	    $('.searchtext').on('keypress', function (e) {
			if(e.which === 13){
		      	intCurrentPage = 1;
	    		$("#tblList").DataTable().clear().draw();
		        getRecordList(intCurrentPage, $('.searchtext').val());
			}
	    });

	    function getRecordList(vPageNo, vSearchText){
			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					CenterID: $("#SearchCenter").val(),
					Status: "",
					SearchText: vSearchText,
					PageNo: vPageNo
				},
				url: "{{ route('get-inventory-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.InventoryList);
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

	    	hideMessage();
	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadRecordRow(vList[x]);
	    		}

	    	}
	    }

	    function LoadRecordRow(vData){

	    	var tblList = $("#tblList").DataTable();

	    	tdID = vData.InventoryID;

			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn btn-success' data-toggle='dropdown'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> ";
	                        @if($IsAllowSetBeginningBalance)
	                          	tdOption += " <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='SetBegBal(" + vData.InventoryID + "," + vData.CenterID + "," + vData.ProductID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Set Beginning Balance " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";
	                        @endif
	                        @if($IsAllowSetBeginningBalance)
	                          	tdOption += " <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='SetMinMax(" + vData.InventoryID + "," + vData.CenterID + "," + vData.ProductID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> Set Minimum/Maximum Level " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> ";
	                        @endif
	                        tdOption += " <li style='text-align:left;'> " +
	                              	" <a href='#' onclick='ViewInvLedger(" + vData.CenterID + "," + vData.ProductID + ")'>" + 
	                              		" <strong><i class='fa fa-info-circle'font-size:15px;'></i> View Inventory Ledger " + 
	                              		" </strong>" +
	                              	" </a> " +
	                           	" </li> " +
	                        " </ul> " +
	                    " </div> " ;


			tdProductPhoto = "<img src='public/img/products/" + vData.ProductID + "/" + vData.ProductID + "-1-{{ config('app.Thumbnail') }}.jpg' style='max-width:50px;'>";

			tdCenter = "<span style='font-weight:normal;'>" + vData.Center + "</span>";

			tdProductCode = "<span style='font-weight:normal;" + (parseFloat(vData.StockOnHand) < parseFloat(vData.MinimumLevel) ? "color:red;" : "") + "'>" + vData.ProductCode + "</span>";
			tdProductName = "<span style='font-weight:normal;" + (parseFloat(vData.StockOnHand) < parseFloat(vData.MinimumLevel) ? "color:red;" : "") + "'>" + vData.ProductName + "</span>";

			tdMeasurement = "<span style='font-weight:normal;" + (parseFloat(vData.StockOnHand) < parseFloat(vData.MinimumLevel) ? "color:red;" : "") + "'>" + vData.Measurement + "</span>";

			tdBegBal = "<span style='font-weight:normal;" + (parseFloat(vData.StockOnHand) < parseFloat(vData.MinimumLevel) ? "color:red;" : "") + "' class='pull-right'>" + FormatDecimal(vData.BegBalance,2) + "</span>";

			tdTotalStockIn = "<span style='font-weight:normal;" + (parseFloat(vData.StockOnHand) < parseFloat(vData.MinimumLevel) ? "color:red;" : "") + "' class='pull-right'>" + FormatDecimal(vData.TotalStockIn,2) + "</span>";

			tdTotalStockOut = "<span style='font-weight:normal;" + (parseFloat(vData.StockOnHand) < parseFloat(vData.MinimumLevel) ? "color:red;" : "") + "' class='pull-right'>" + FormatDecimal(vData.TotalStockOut,2) + "</span>";

			tdStockOnHand = "<span style='font-weight:normal;" + (parseFloat(vData.StockOnHand) < parseFloat(vData.MinimumLevel) ? "color:red;" : "") + "' class='pull-right'>" + FormatDecimal(vData.StockOnHand,2) + "</span>";

			tdMinimumLevel = "<span style='font-weight:normal;" + (parseFloat(vData.StockOnHand) < parseFloat(vData.MinimumLevel) ? "color:red;" : "") + "' class='pull-right'>" + FormatDecimal(vData.MinimumLevel,2) + "</span>";

			tdMaximumLevel = "<span style='font-weight:normal;" + (parseFloat(vData.StockOnHand) < parseFloat(vData.MinimumLevel) ? "color:red;" : "") + "' class='pull-right'>" + FormatDecimal(vData.MaximumLevel,2) + "</span>";

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();

			    if(rowData[0] == vData.InventoryID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdOption;
			    	curData[2] = tdProductPhoto;
			    	curData[3] = tdCenter;
			    	curData[4] = tdProductCode;
			    	curData[5] = tdProductName;
			    	curData[6] = tdMeasurement;
			    	curData[7] = tdBegBal;
			    	curData[8] = tdTotalStockIn;
			    	curData[9] = tdTotalStockOut;
			    	curData[10] = tdStockOnHand;
			    	curData[11] = tdMinimumLevel;
			    	curData[12] = tdMaximumLevel;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdID,
						tdOption,
						tdProductPhoto,
						tdCenter,
						tdProductCode, 
						tdProductName,
						tdMeasurement,
						tdBegBal,
						tdTotalStockIn,
						tdTotalStockOut,
						tdStockOnHand,
						tdMinimumLevel,
						tdMaximumLevel
					]).draw();			
			}

	    }

	    function SetBegBal(vInventoryID, vCenterID, vProductID){

			$("#BegBalInvID").val(vInventoryID);
			$("#BegBalCenterID").val(vCenterID);
			$("#BegBalProductID").val(vProductID);
			$("#BegBalDateTime").val('{{ date("m/d/Y") }}').change();
			$("#BegBalance").val('');
			$("#BegBalRemarks").val('');

			$("#begbal-modal").modal();

	    }

	    function SaveBegBal(){

			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					InventoryID: $("#BegBalInvID").val(),
					CenterID: $("#BegBalCenterID").val(),
					ProductID: $("#BegBalProductID").val(),
					BegBalDateTime: $("#BegBalDateTime").val(),
					BegBalance: $("#BegBalance").val(),
					BegBalRemarks: $("#BegBalRemarks").val()
				},
				url: "{{ route('do-set-beginning-balance') }}",
				dataType: "json",
				success: function(data){
					buttonOneClick("btnBegBalSave", "Save", false);

					if(data.Response =='Success'){
						$("#begbal-modal").modal('hide');
						showMessage("Success",data.ResponseMessage);
						LoadRecordRow(data.InventoryInfo);
				        $("#divLoader").hide();
					}else{
				        $("#divLoader").hide();
						showJSModalMessageJS("Set Beginning Balance",data.ResponseMessage,"OK");
					}
				},
				error: function(data){
			        $("#divLoader").hide();
					buttonOneClick("btnBegBalSave", "Save", false);
					console.log(data.responseText);
				},
				beforeSend:function(vData){
			        $("#divLoader").show();
					buttonOneClick("btnBegBalSave", "", true);
				}
        	});


	    }

		function SetMinMax(vInventoryID, vCenterID, vProductID){
			$("#MinMaxInvID").val(vInventoryID);
			$("#MinMaxCenterID").val(vCenterID);
			$("#MinMaxProductID").val(vProductID);
			$("#MinimumLevel").val('');
			$("#MaximumLevel").val('');

			$("#minmax-modal").modal();
	    }

	    function SaveMinMax(){

			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					InventoryID: $("#MinMaxInvID").val(),
					CenterID: $("#MinMaxCenterID").val(),
					ProductID: $("#MinMaxProductID").val(),
					MinimumLevel: $("#MinimumLevel").val(),
					MaximumLevel: $("#MaximumLevel").val()
				},
				url: "{{ route('do-set-min-max') }}",
				dataType: "json",
				success: function(data){
					buttonOneClick("btnMinMaxSave", "Save", false);

					if(data.Response =='Success'){
						$("#minmax-modal").modal('hide');
						showMessage("Success",data.ResponseMessage);
						LoadRecordRow(data.InventoryInfo);
				        $("#divLoader").hide();
					}else{
				        $("#divLoader").hide();
						showJSModalMessageJS("Set Minimum/Maximum Level",data.ResponseMessage,"OK");
					}
				},
				error: function(data){
			        $("#divLoader").hide();
					buttonOneClick("btnMinMaxSave", "Save", false);
					console.log(data.responseText);
				},
				beforeSend:function(vData){
			        $("#divLoader").show();
					buttonOneClick("btnMinMaxSave", "", true);
				}
        	});


	    }

		function ViewInvLedger(vCenterID, vProductID){

			intLedgerPage = 0;
			$("#InvLedgerCenterID").val(vCenterID);
			$("#InvLedgerProductID").val(vProductID);
    		$("#tblInvLedgerList").DataTable().clear().draw();

    		LoadMoreInvLedger();
	    }

	    function LoadMoreInvLedger(){

	    	intLedgerPage = intLedgerPage + 1;

			$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					CenterID: $("#InvLedgerCenterID").val(),
					ProductID: $("#InvLedgerProductID").val(),
					PageNo: intLedgerPage
				},
				url: "{{ route('get-inventory-ledger') }}",
				dataType: "json",
				success: function(data){
					if(data.Response =='Success' && data.InventoryLedger != undefined){

				        //Load Initial Data
				        if(data.InventoryLedger.length > 0){
					        LoadInventoryLedgerList(data.InventoryLedger);
				        }

						buttonOneClick("btnInvLedgerLoadMore", "Load More", false);
				        $("#divLoader").hide();

						$("#inv-ledger-modal").modal();

					}else{
						showJSModalMessageJS("Inventory Ledger",data.ResponseMessage,"OK");
				        $("#divLoader").hide();
					}

				},
				error: function(data){
					buttonOneClick("btnInvLedgerLoadMore", "Load More", false);
					console.log(data.responseText);
			        $("#divLoader").hide();
				},
				beforeSend:function(vData){
					buttonOneClick("btnInvLedgerLoadMore", "", false);
			        $("#divLoader").show();
				}
        	});


	    }
	    
	    function LoadInventoryLedgerList(vList){

	    	if(vList.length > 0){
	    		for(var x=0; x < vList.length; x++){
					LoadInventoryLedgerRow(vList[x]);
	    		}

	    	}
	    }

	    function LoadInventoryLedgerRow(vData){

	    	var tblInvLedgerList = $("#tblInvLedgerList").DataTable();

	    	tdTransDateTime = "<span style='font-weight:normal;'>" + vData.TransDateTime + "</span>";

	    	if(vData.Remarks != ""){
	    		tdTransactionType = "<span style='font-weight:normal;'>" + vData.TransactionType + " ( " + vData.Remarks + " )" + "</span>";
	    	}else{
	    		tdTransactionType = "<span style='font-weight:normal;'>" + vData.TransactionType + "</span>";
	    	}
	    	tdQtyIn = "<span style='font-weight:normal;' class='pull-right'>" +  FormatDecimal(vData.QtyIn,2) + "</span>";
	    	tdQtyOut = "<span style='font-weight:normal;' class='pull-right'>" +  FormatDecimal(vData.QtyOut,2) + "</span>";
	    	tdNewStockOnhand = "<span style='font-weight:normal;' class='pull-right'>" +  FormatDecimal(vData.NewStockOnhand,2) + "</span>";

	    	//New Row
			tblInvLedgerList.row.add([
					tdTransDateTime,
					tdTransactionType,
					tdQtyIn, 
					tdQtyOut, 
					tdNewStockOnhand
				]).draw();			

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
