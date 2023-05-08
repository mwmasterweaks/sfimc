@extends('layout.frontweb')
@section('content')

  @php($IsInCart = false)
  @php($Qty = 1)
  @if(isset($Cart))
    @foreach($Cart as $ckey)
      @if($ProductID == $ckey->ProductID)
        @php($IsInCart = true)
        @php($Qty = $ckey->Qty)
      @endif
    @endforeach
  @endif

  <!--Page Title-->
  <section class="bredcrumb">
    <div class="bg-image text-center" style="background-image: url('img/products-banner.jpg');">
      <h1>Product Details</h1>
    </div>
    <div class="">
      <ul class= "middle">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a class="inner" href="#">Product Details</a></li>
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
                  <input name="SearchText" value="" placeholder="Search Here" type="text">
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
            <div class="col-md-5 col-sm-12 col-xs-12">
              <div class="single-item">
                <div class="img-holder">            
                  <img src="{{URL::to('img/products/'.$ProductID.'/'.$ProductID.'-1-300x300.jpg')}}" alt="{{ $ProductName }}"/>
                </div>
              </div>
            </div>

            <div class="col-md-7 col-sm-6 col-xs-12">
              <div class="entry-summary">
                <div class="product-info-main style4">
                  <div class="product-name">
                    <a href="{{ route('product-detail').'?ProductName='.$ProductName}}">{{ $ProductName }}</a>
                  </div>
                  <div class="star-rating" title="Rated 5 out of 5"> 
                    <span style="width:100%">
                      <strong class="rating">5</strong> out of 5
                    </span> 
                  </div>
                  <p>
                    {{ 'Product Code : '.$ProductCode }}<br>
                    {{ 'Category : '.$Category }}<br>
                    {{ 'Brand : '.$Brand }}
                  </p>
                  <div class="price">
                      <ins>{{ (!Session('MEMBER_LOGGED_IN') ? 'Php '.number_format($RetailPrice,2) : 'Php '.number_format($DistributorPrice,2)). '/'. $Measurement }}</ins>
                  </div>
                  <div class="col-md-6" style="margin: 0px; padding: 0px; height: 45px !important;">
                    <input id="txtProductDetailQty" type="text" class="form-control text-center NumberOnly" style="height: 45px !important;" value="{{ number_format($Qty,0) }}">
                  </div>
                  @if($IsInCart)
                    <div class="col-md-6" style="margin: 0px; padding: 0px; height: 45px !important;">
                      <button class="thm-btn" onclick="UpdateCartItem({{ $ProductID }}, $('#txtProductDetailQty').val())">
                          Update Cart
                      </button>
                    </div>
                  @else
                    <div class="col-md-6" style="margin: 0px; padding: 0px; height: 45px !important;">
                      <button class="thm-btn" onclick="AddToCart({{ $ProductID }}, $('#txtProductDetailQty').val())">
                        Add To Cart
                      </button>
                    </div>
                  @endif
                  <div style="clear:both;"></div>
                  <br>                
                  <p class="product-des">
                    Description : <br>
                    {!! $Description !!}
                  </p>

                  <p class="product-des">
                    Specifications : <br>
                    {!! $Specification !!}
                  </p>
                  
                </div>
              </div>
            </div>
            

          </div>


        </div>          
      </div>
    </section>

@endsection



