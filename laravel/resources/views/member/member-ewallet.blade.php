@extends('layout.memberweb')

@section('content')

	<section class="content-header">
		<h1>
			Member E-Wallet Ledger
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-success">

		            <div class="box-body table-responsive" style="min-height: 600px;">

		              	<table id="tblList" class="table table-bordered table-hover">
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
			                <tbody id="tblBodyList">
				            </tbody>
		              	</table>

		            </div>

          		</div>
          	</div>
		</div>
	</section>
	<!-- /.content -->	

	<script type="text/javascript">

	 	var isPageFirstLoad = true;
		var intLoadedAll = false;
		var intCurrentPage = 0;

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
				'ordering'    : false,
				'info'        : false,
				'autoWidth'   : false
	        });

	        getRecordList();
		 	isPageFirstLoad = false;
	    });

		function getRecordList(){

      		intCurrentPage = intCurrentPage + 1;
    		$.ajax({
				type: "post",
				data: {
					_token: '{{ $Token }}',
					EntryID: {{ Session("MEMBER_ENTRY_ID") }},
					MemberID: {{ Session("MEMBER_ID") }},
					PageNo : intCurrentPage
				},
				url: "{{ route('get-member-ewallet-ledger') }}",
				dataType: "json",
      			success: function(data){
        			LoadRecordList(data.EwalletLedger);
        			$("#divLoader").hide();
			        if(data.EwalletLedger.length <= {{ config('app.ListRowLimit') }}){
			          intLoadedAll = true;
			        }
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
		    tblList.row.add([
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
		       if($(window).scrollTop() + $(window).height() == $(document).height()){
		       		if(!intLoadedAll){
						intCurrentPage = intCurrentPage + 1;
						getRecordList(intCurrentPage, $('.searchtext').val());
		       		}
		       }
	    	}
	    });

	</script>
	
@endsection
