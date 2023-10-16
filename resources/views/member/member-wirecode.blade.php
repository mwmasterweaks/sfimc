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
			Wire Code Activation
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
                    <input id="activateCode" type="text" class="form-control"  value="" style="width:100%; font-weight: normal;" required>
                </div>
        	</div>
        </div>

        <div style="clear:both;"></div>
        <br>
        <div class="col-md-12">
            <button id="btnSave" type="button" onclick="btnActivate()" class="btn btn-info one-click">Activate</button>
        </div>
        <div style="clear:both;"></div>
        <br><br>

	</section>
	<!-- /.content -->	

	<script type="text/javascript">

	    function btnActivate(){

			if($('#activateCode ').val() == "") {
				showJSMessage("Activate Wirecode","Please enter code.","OK");
			}else{
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						Source: "Member",
						code: $("#activateCode").val(),
					
					},
					url: "{{ route('do-activate-wire') }}",
					dataType: "json",
					success: function(data){

						console.log(data);
						buttonOneClick("btnUpgrade", "Upgrade", false);
						if(data.Response =='success'){
							showJSModalMessageJS("Success", data.ResponseMessage,"OK");
							window.location.href = "{{ route('member-dashboard') }}"
						}else{
							showJSModalMessageJS("Activate Wirecode ", data.ResponseMessage,"OK");
						}
					},
					error: function(data){
						buttonOneClick("btnUpgrade", "Upgrade", false);
						console.log(data);
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
