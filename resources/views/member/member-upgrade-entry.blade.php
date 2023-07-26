@extends('layout.memberweb')

@section('content')
  	
  	@php($UpgradeSponsorEntryID = 0)
  	@php($UpgradeSponsor = "")
  	@if(isset($MemberEntryInfo))
	  	@php($UpgradeSponsorEntryID = $MemberEntryInfo->SponsorEntryID)
	  	@php($UpgradeSponsor = $MemberEntryInfo->SponsorMemberNo." - ".$MemberEntryInfo->SponsorMemberName)
  	@endif

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Member Upgrade Entry
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<input type="hidden" id="UpgradeEntryID" value="{{ Session('MEMBER_ENTRY_ID') }}" readonly>
        <input id="UpgradeCurrentPackageID" type="hidden" class="form-control" value="{{ Session('MEMBER_PACKAGE_ID') }}" readonly>

        <div class="row">
        	<div class="col-md-12">
            	<label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Code Information</b></label>
        	</div>
    	</div>
        <div class="row">
        	<div class="col-md-6">
                <label class="col-md-12" style="font-weight: normal;">Code <span style="font-size: 10px; color:red;">*</span></label>
                <div class="col-md-12">
                    <input id="UpgradeCode" type="text" class="form-control"  value="" style="width:100%; font-weight: normal;" required>
                </div>
        	</div>
        	<div class="col-md-12">
                <label class="col-md-12" style="font-weight: normal;">Sponsor/Owner <span style="color:red;">*</span></label>
                <div class="col-md-12" style="font-weight: normal;">
                    <input id="UpgradeSponsorEntryID" type="hidden" class="form-control" value="{{ $UpgradeSponsorEntryID }}" readonly>
					<input id="UpgradeSponsor" type="text" data-type="UpgradeSponsor" class="form-control autocomplete_txt"  autocomplete="off" value="{{ $UpgradeSponsor }}" style="width:100%; font-weight: normal;" readonly>
                </div>
        	</div>
        </div>

        <div style="clear:both;"></div>
        <br>
        <div class="col-md-12">
            <button id="btnSave" type="button" onclick="UpgradeNow()" class="btn btn-info one-click">Upgrade</button>
        </div>
        <div style="clear:both;"></div>
        <br><br>

	</section>
	<!-- /.content -->	

	<script type="text/javascript">

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
						Source: "Member",
						EntryID: $("#UpgradeEntryID").val(),
						Code: $("#UpgradeCode").val(),
						CurrentPackageID: $("#UpgradeCurrentPackageID").val(),
						PackageID: 0,
						SponsorEntryID: $("#UpgradeSponsorEntryID").val()
					},
					url: "{{ route('do-upgrade-member-entry') }}",
					dataType: "json",
					success: function(data){

						buttonOneClick("btnUpgrade", "Upgrade", false);
						if(data.Response =='Success'){
							showJSModalMessageJS("Success",data.ResponseMessage,"OK");
							window.location.href = "{{ route('member-dashboard') }}"
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

	    	if($(this).data('type') == "UpgradeSponsor"){

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

	</script>



@endsection
