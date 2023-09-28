@extends('layout.memberweb')
@section('content')
	
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Member Tree
		</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-success">
					
		            <div class="col-md-6">
					  <ul>
							{{-- @foreach($upline as $key => $upline)
							  <li>
								{{ "Level ".$upline->depth }} 
								  	<ul>
										<li>
											{{ $upline->EntryCode }} - {{ strtoupper($upline->FirstName) }} {{ strtoupper($upline->LastName) }}
										</li>
									</ul>
							  </li>
						  	@endforeach --}}
						  	@foreach($downline as $key => $downline)
							  <li>
								{{ "Level ".$key }} 
								  	<ul>
										@foreach($downline as $down)
										<li>
											{{ $down->EntryCode }} - {{ strtoupper($down->FirstName) }} {{ strtoupper($down->LastName) }}
										</li>
										@endforeach
									</ul>
							  </li>
						  	@endforeach
					  </ul>
					</div>


          		</div>
          	</div>
		</div>
	</section>
	<!-- /.content -->
@endsection
