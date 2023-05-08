@extends('layout.frontweb')
@section('content')

  <!--Page Title-->
  <section class="bredcrumb">
    <div class="bg-image text-center" style="background-image: url('public/img/products-banner.jpg');">
      <h1>Centers</h1>
    </div>
    <div class="">
      <ul class= "middle">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a class="inner" href="#">Centers</a></li>
      </ul>
    </div>
  </section>
  <!--Page Title Ends-->

  <section class="news">
    <div class="container">
      <div class="row">

        @foreach($CenterList as $ctr)
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="news-post" style="height: 700px; max-height: 700px;">
            <div class="news-post-image">
              <div class="news-overlay"></div>  
              @if($ctr->CenterID == 1)
                <div class="news-category"><a href="#">MAIN</a></div> 
              @endif
              <img src="{{ URL::to('public/img/logo.png') }}" alt="{{ $ctr->Center }}" class="img-responsive" style="padding: 20px;">
            </div>
            <div class="news-post-text">
              <h3>{{ $ctr->Center }}</h3>
              <p>
                <span style="color:#2a9409;">Center No.</span> : <span style="color:#fab915;">{{ $ctr->CenterNo }}</span>
                <br>
                <span style="color:#2a9409;">Incharge</span> : <span style="color:#fab915;">{{ $ctr->Incharge }}</span>
                <br>
                <span style="color:#2a9409;">Tel. No.</span> : <span style="color:#fab915;">{{ $ctr->TelNo }}</span>
                <br>
                <span style="color:#2a9409;">Mobile No.</span> : <span style="color:#fab915;">{{ $ctr->MobileNo }}</span>
                <br>
                <span style="color:#2a9409;">Email Address</span> : <span style="color:#fab915;">{{ $ctr->EmailAddress }}</span>
                <br>
                <span style="color:#2a9409;">Address</span> : <span style="color:#fab915;">{{ $ctr->Address.', '.$ctr->City.', '.$ctr->StateProvince.', '.$ctr->ZipCode.' '.$ctr->Country }}</span>
              </p>
            </div>
          </div>
        </div>
        @endforeach
        
      </div>  
    </div>  
  </section>

@endsection



