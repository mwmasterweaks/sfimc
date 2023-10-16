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
						@foreach($upline as $key => $upline)
							<strong>{{ "Level ".$upline->depth }}</strong>
								<ul style="list-style-type:square;">
									<li>
										{{ $upline->EntryCode }} - {{ ucwords(strtolower($upline->FirstName)) }} {{ ucwords(strtolower($upline->LastName)) }}
									</li>
								</ul>
						@endforeach
							
						{{-- @foreach($downline as $key => $downline)
						
							<strong>{{ "Level ".$key }} </strong>
								<ul style="list-style-type:square;">
									@foreach($downline as $down)
									<li>
										{{ $down->EntryCode }} - {{ ucwords(strtolower($down->FirstName)) }} {{ ucwords(strtolower($down->LastName)) }}
									</li>
									@endforeach
								</ul>
						@endforeach --}}
					</div>
          		</div>
          	</div>
		</div>
	</section>
	<!-- /.content -->
@endsection
