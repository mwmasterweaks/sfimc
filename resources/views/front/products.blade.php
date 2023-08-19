@extends('layout.frontweb')
@section('content')

  <!--Page Title-->
  <section class="bredcrumb">
    <div class="bg-image text-center" style="background-image: url('img/products-banner.jpg');">
      <h1>Our Products</h1>
    </div>
    <div class="">
      <ul class= "middle">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a class="inner" href="#">Products</a></li>
      </ul>
    </div>
  </section>
  <!--Page Title Ends-->

  <!--team section-->
  <section class="our-gallery">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="sidebar-wrapper">
            <!--Start single sidebar-->
            <div class="single-sidebar mt-30">
              <form class="search-form" method="get" action="{{ route('products') }}">
                <input name="SearchText" value="{{ $SearchText }}" placeholder="Search Here" type="text">
                <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
              </form>
            </div>
            <!--End single sidebar-->
            <div class="single-sidebar">
                <div class="wedgit-title">
                    <h3>Categories</h3>
                </div>
                <ul class="categories clearfix">
                    <li><a href="{{ route('products').'?SearchText=Agriculture Product' }}">Agriculture Product</a></li>
                    <li><a href="{{ route('products').'?SearchText=Cosmetic Product' }}">Cosmetic Product</a></li>
                    <li><a href="{{ route('products').'?SearchText=Health and Wellness' }}">Health and Wellness</a></li>
                    <li><a href="{{ route('products').'?SearchText=Household Product' }}">Household Product</a></li>
                    <li><a href="{{ route('products').'?SearchText=Perfume' }}">Perfume</a></li>
                    <li><a href="{{ route('products').'?SearchText=Others' }}">Others</a></li>
                </ul>
            </div>
          </div>                                  
        </div>
        <div class="col-md-9 col-sm-12">
          @foreach($InventoryList as $inv)
          <div class="col-md-4 col-sm-6 col-xs-12"  style="min-height:500px;">
            <div class="single-item">
              <div class="img-holder">
                <a href="{{ route('product-detail').'?ProductName='.$inv->ProductName}}">
                  <img style="height: 260px; width:260px; object-fit: cover;" src="{{ asset(config('app.src_name') . 'img/products/'.$inv->ProductID.'/'.$inv->ProductID.'-1-300x300.jpg')}}" alt="{{ $inv->ProductName }}"/>
                
                </a>
              </div>
              <div class="overlay">
                <div class="inner" style="height: 135px;">
                  <h4 style="text-align: center; max-height: 50px;"><a href="{{ route('product-detail').'?ProductName='.$inv->ProductName}}">{{ $inv->ProductName }}</a></h4>
                  <div style="text-align: center; padding: 5px;">
                    <span class="amount price-color">{{ (!Session('MEMBER_LOGGED_IN') ? 'Php '.number_format($inv->RetailPrice,2) : 'Php '.number_format($inv->DistributorPrice,2)). '/'. $inv->Measurement }}</span>
                  </div>
                  <div style="padding: 5px;">
                      <div style="font-size: 12px;">{{ 'Product Code : '.$inv->ProductCode }}</div>
                      <div style="font-size: 12px;">{{ 'Brand : '.$inv->Brand }}</div>
                      <div style="font-size: 12px;">{{ 'Category : '.$inv->Category }}</div>
                  </div>
                  
                </div>
                <div style="position: relative;">
                    <a href="#" onclick="AddToCart({{ $inv->ProductID }},1, '')" class="thm-btn" style="text-align: center; width: 100%">Add To Cart</a>
                  </div>
              </div>
            
            </div>
          </div>
          @endforeach
        </div>

      </div>          

    </div>
  </section>

@endsection



