@extends('layout.memberweb')

@section('content')
	
	@php($MatchingLeft = 0)
	@php($MatchingRight = 0)
	@php($AccumulatedPersonalPurchase = 0)
	@php($AccumulatedGroupPurchase = 0)
	@php($TotalEntryShare = 0)
	@php($TotalRewards = 0)
	@php($TotalEncashment = 0)
	@php($AvailableEwallet = 0)
	
	@if(isset($DashboardFigures))
		@foreach($DashboardFigures as $fig)
			@php($MatchingLeft = $fig->LRunningBalance)
			@php($MatchingRight = $fig->RRunningBalance)
			@php($AccumulatedPersonalPurchase = $fig->PersonalRunningBalance)
			@php($AccumulatedGroupPurchase = $fig->GroupRunningBalance)

			@php($TotalEntryShare = $fig->TotalEntryShare)
			@php($TotalRewards = $fig->TotalRewards)
			@php($TotalEncashment = $fig->TotalEncashment)
			@php($AvailableEwallet = $fig->AvailableEwallet)

		@endforeach
	@endif

	<!-- Main content -->
	<section class="content">
		<div class="box box-widget widget-user-2">
			<div class="widget-user-header" style="background-color: #dfb407;">
      			<div class="row">
					<div class="col-md-7">
						<div class="widget-user-image">
							@if(File::exists('img/members/'.Session('MEMBER_ENTRY_ID').'.jpg'))
								<img id="imgMember" class="img-circle" src="{{ asset(config('app.src_name') . 'img/members/'.Session('MEMBER_ENTRY_ID').'.jpg') }}" alt="Member Picture" onclick="UploadImage()">
							@else
								<img id="imgMember" class="img-circle" src="{{ asset(config('app.src_name') . 'img/members/member-no-image.png') }}" alt="Member Picture"  onclick="UploadImage()">
							@endif
						</div>
						<h5 class="widget-user-desc" style="color:white;">{{ Session('MEMBER_NAME') }}</h5>
						<h4 class="widget-user-desc" style="color:white;">{{ 'IBO Number : '.Session('MEMBER_ENTRY_CODE').' - '.Session('MEMBER_PACKAGE') }}</h4>
						<h4 class="widget-user-desc" style="color:white;">{{ 'Rank Level : '.Session('MEMBER_RANK_LEVEL').' '.Session('MEMBER_RANK') }}</h4>
					</div>
					<div class="col-md-5">
						<h4 class="widget-user-desc" style="color:white;">WIRE Income Projection: <span style="color:red;">(soon)</span></h4>
						<h4 class="widget-user-desc" style="color:white;">REBATES Income Projection: <span style="color:red;">(soon)</span></h4>
					</div>
				</div>
			</div>
		</div>

	    <div class="row">

	        <div class="col-md-6 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-yellow" style="background: #1545f8 !important;">
	            	<i class="ion ion-ios-people-outline"></i>
	            </span>
	            <div class="info-box-content">
	              <span class="info-box-text">Matching Left (Waiting)</span>
	              <span class="info-box-number">{{ number_format($MatchingLeft,0) }}</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>
	     
	        <div class="col-md-6 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-yellow" style="background: #1545f8 !important;">
	            	<i class="ion ion-ios-people-outline"></i>
	            </span>
	            <div class="info-box-content">
	              <span class="info-box-text">Matching Right (Waiting)</span>
	              <span class="info-box-number">{{ number_format($MatchingRight,0) }}</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>

	        <div class="col-md-6 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-yellow" style="background: #e15a1f !important;">
	            	<i class="ion ion-ios-cart-outline"></i>
	            </span>
	            <div class="info-box-content">
	              <span class="info-box-text">Accumulated Personal Purchase</span>
	              <span class="info-box-number">{{ number_format($AccumulatedPersonalPurchase,2) }}</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>
	     
	        <div class="col-md-6 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-yellow"  style="background: #e15a1f !important;">
	            	<i class="ion ion-ios-cart-outline"></i>
	            </span>
	            <div class="info-box-content">
	              <span class="info-box-text">Accumulated Group Purchase</span>
	              <span class="info-box-number">{{ number_format($AccumulatedGroupPurchase,2) }}</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>

	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-yellow" style="background: #e50aed !important;">
	            	<i class="fa fa-money"></i>
	            </span>
	            <div class="info-box-content">
	              <span class="info-box-text">Total Cashback</span>
	              <span class="info-box-number">{{ number_format($TotalEntryShare,2) }}</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>

	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-yellow">
	            	<i class="fa fa-money"></i>
	            </span>
	            <div class="info-box-content">
	              <span class="info-box-text">Total Rewards</span>
	              <span class="info-box-number">{{ number_format($TotalRewards,2) }}</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>

	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-red">
	            	<i class="fa fa-money"></i>
	            </span>

	            <div class="info-box-content">
	              <span class="info-box-text">Total Encashment</span>
	              <span class="info-box-number">{{ number_format($TotalEncashment,2) }}</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>

	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-green">
	            	<i class="fa fa-money"></i>
	            </span>

	            <div class="info-box-content">
	              <span class="info-box-text">Available E-Wallet</span>
	              <span class="info-box-number">{{ number_format($AvailableEwallet,2) }}</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>

      	</div>

	</section>
	<!-- /.content -->	

	<div id="upload-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">

          	<div class="modal-header" style="background-color: #3c8dbc;">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title"  style="text-align:center; color:white;"><b>Product Photo</b></h4>
          	</div>

			<form class="" method="post" action="{{URL('do-upload-member-photo')}}" enctype="multipart/form-data">

          	<div class="modal-body">

                {!! csrf_field() !!}   <!--Token -->

	        	<input type="hidden" id="UploadMemberEntryID" name="MemberEntryID" value="{{ Session('MEMBER_ENTRY_ID') }}" readonly>

	            <div class="row">
	            	<div class="col-md-12">
                       	<p class="help-block">

                     		Note: Fields marked with (*) denotes of required fields
                         	<br>Accepts jpg &amp; png image type with max [2048KB] file size,
                        	<br><span style="color:red;"> <b>Best Quality fit for member image dimension is 500x500</b></span>
                        </p>
                        <input type="file" accept="image/*"  name="memberimage[]" onchange="loadFile(event)" required>
                        <br>
                        <div class="file-preview-frame">
                            <!--Product Image Display Here  -->
                            <img id="output" src="{{ asset(config('app.src_name') . 'img/members/member-no-image.png') }}" style="max-width: 500px;" />
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
		
	    function UploadImage(){
			$("#upload-modal").modal();
	    }

	</script>

@endsection
