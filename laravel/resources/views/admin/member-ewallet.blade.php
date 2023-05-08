@extends('layout.adminweb')

@section('content')
  
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Member E-Wallet
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li>Member E-Wallet List</li>
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
			                  <th>IBO Number</th>
			                  <th>Entry Date/Time</th>
			                  <th>Member</th>
			                  <th>Package</th>
			                  <th>Tel. No.</th>
			                  <th>Mobile No.</th>
			                  <th>Email Address</th>
			                  <th style="text-align: right;">E-Wallet Balance</th>
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

	<div id="ewallet-ledger-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg" style="width: 90%;">
	    <div class="modal-content">

          	<div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>E-Wallet Ledger</b></h4>
          	</div>

          	<div class="modal-body">

	        	<input type="hidden" id="EntryID" value="0" readonly>
	        	<input type="hidden" id="MemberID" value="0" readonly>

	            <div class="row">
	            	<div class="col-md-12">
		              	<table id="tblEWalletHistory" class="table table-bordered table-hover">
			                <thead>
				                <tr>
				                  	<th>LedgerID</th>
									<td>Date/Time Earned</td>
	                              	<td>Remarks</td>
	                              	<td style="text-align: right;">IN</td>
	                              	<td style="text-align: right;">OUT</td>
	                              	<td style="text-align: right;">Balance</td>
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
                    		<a href="#" id="btnLoadmore" class="btn btn-success btn-flat" onclick="getEWalletLedgerRecordList()">Load More</a>
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
		var intEWalletHistoryPage = 0;
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
	            "order": [[ 2, "desc" ]]
	        });

	        $('#tblEWalletHistory').DataTable( {
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
					IsWithEwallet : 1
				},
				url: "{{ route('get-member-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.MemberList);
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

	    	tdID = vData.EntryID;

			tdEntryCode = "<span style='font-weight:normal;'>" + vData.EntryCode + "</span>";
			tdEntryDateTime = "<span style='font-weight:normal;'>" + vData.EntryDateTime + "</span>";

			tdMember = "<span style='font-weight:normal;'>" + vData.MemberName + "</span>";
			tdPackage = "<span style='font-weight:normal;'>" + vData.Package + "</span>";

			tdTelNo = "<span style='font-weight:normal;'>" + vData.TelNo + "</span>";
			tdMobileNo = "<span style='font-weight:normal;'>" + vData.MobileNo + "</span>";
			tdEmailAddress = "<span style='font-weight:normal;'>" + vData.EmailAddress + "</span>";
			
			tdBalance = "<button type='button' onClick='LoadEWalletLedger("+ vData.EntryID + "," + vData.MemberID + ")' class='btn btn-success' style='width:100%; text-align:right;'> " + FormatDecimal(vData.EWalletBalance,2) + "</button>";

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {
			    var rowData = this.data();

			    if(rowData[0] == vData.EntryID){
					IsRecordExist = true;
			    	//Edit Row
			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdEntryCode;
			    	curData[2] = tdEntryDateTime;
			    	curData[3] = tdMember;
			    	curData[4] = tdPackage;
			    	curData[5] = tdTelNo;
			    	curData[6] = tdMobileNo;
			    	curData[7] = tdEmailAddress;
			    	curData[8] = tdBalance;

			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				tblList.row.add([
						tdID,
						tdEntryCode, 
						tdEntryDateTime,
						tdMember,
						tdPackage,
						tdTelNo,
						tdMobileNo,
						tdEmailAddress,
						tdBalance
					]).draw();			
			}

	    }

        function LoadEWalletLedger(vEntryID, vMemberID){

      		$("#tblEWalletHistory").DataTable().clear().draw();
      		$("#EntryID").val(vEntryID);
      		$("#MemberID").val(vMemberID);
      		intEWalletHistoryPage = 0;

      		getEWalletLedgerRecordList();
    	}

    	function getEWalletLedgerRecordList(){

      		intEWalletHistoryPage = intEWalletHistoryPage + 1;
    		$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					EntryID: $("#EntryID").val(),
					MemberID: $("#MemberID").val(),
					PageNo : intEWalletHistoryPage
				},
				url: "{{ route('get-member-ewallet-ledger') }}",
				dataType: "json",
      			success: function(data){
        			LoadEWalletLedgerRecordList(data.EwalletLedger);
        			$("#divLoader").hide();
			        if(data.EwalletLedger.length <= {{ config('app.ListRowLimit') }}){
			          $("#btnLoadmore").hide();
			        }else{
			          $("#btnLoadmore").show();
			        }
          
        			$("#ewallet-ledger-modal").modal('show');
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

    	function LoadEWalletLedgerRecordList(vList){
      		if(vList.length > 0){
        		for(var x=0; x < vList.length; x++){
        			LoadEWalletLedgerRecordRow(vList[x]);
        		}
      		}
    	}

    	function LoadEWalletLedgerRecordRow(vData){

      		var tblEWalletHistory = $("#tblEWalletHistory").DataTable();

      		tdLedgerID = vData.LedgerID;
      		tdDateTimeEarned = "<span style='font-weight:normal;'>" + vData.DateTimeEarned + "</span>";
      		if(vData.EarnedFromMemberID > 0){
      			tdRemarks = "<span style='font-weight:normal;'>" + vData.Remarks + " - " + vData.EarnedFrom + "</span>";
      		}else{
	      		tdRemarks = "<span style='font-weight:normal;'>" + vData.Remarks + "</span>";
      		}
	      	tdIN = "<span style='font-weight:normal;' class='pull-right'>" + parseFloat(vData.INAmount).toFixed(2) + "</span>";
	      	tdOUT = "<span style='font-weight:normal;' class='pull-right'>" + parseFloat(vData.OUTAmount).toFixed(2) + "</span>";
	      	tdBalance = "<span style='font-weight:normal;' class='pull-right'>" + parseFloat(vData.RunningBalance).toFixed(2) + "</span>";

		  	//New Row
		    tblEWalletHistory.row.add([
		        tdLedgerID,
		        tdDateTimeEarned, 
		        tdRemarks, 
		        tdIN,
		        tdOUT,
		        tdBalance
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
