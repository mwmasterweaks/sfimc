@extends('layout.frontweb')
@section('content')

    <!--Start rev slider wrapper-->     
    <section class="rev_slider_wrapper">
      <div id="slider1" class="rev_slider"  data-version="5.0">
        <ul>
          <li data-transition="fade">
            <img src="{{URL::to('public/img/slide1.jpg')}}"  alt="" width="1920" height="700" data-bgposition="top center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="1" >
          </li>
          <li data-transition="fade">
            <img src="{{URL::to('public/img/slide2.jpg')}}"  alt="" width="1920" height="700" data-bgposition="top center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="1" >
          </li>
          <li data-transition="fade">
            <img src="{{URL::to('public/img/slide3.jpg')}}"  alt="" width="1920" height="700" data-bgposition="top center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="1" >
          </li>
          <li data-transition="fade">
            <img src="{{URL::to('public/img/slide4.jpg')}}"  alt="" width="1920" height="700" data-bgposition="top center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="1" >
          </li>
          <li data-transition="fade">
            <img src="{{URL::to('public/img/slide5.jpg')}}"  alt="" width="1920" height="700" data-bgposition="top center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="1" >
          </li>

        </ul>
      </div>
    </section>
    <!--End rev slider wrapper--> 

<section class="our-services rotated-bg">
      <div class="container">
        <div class="sec-title">
          <h2 class="center">Our Featured Products</h2>
          <p>Discover our best products in every category. Our friendly team are standing by to help you with your needs.</p>
        </div>
        <div class="row clearfix">
          <!--Start single service icon-->
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="single-service-item">
            
              <div class="service-left-bg"></div>
              <div class="service-icon">
                <a href="{{ route('products') }}">
                  <img src="{{URL::to('public/img/cosmetic-products.jpg')}}" alt="Cosmetic Products">
                </a>
              </div>
              <div class="service-text">
                <h4><a href="{{ route('products') }}">Cosmetic Products</a></h4>
              </div>
            </div>
          </div>
          <!--End single service icon-->
          <!--Start single service icon-->
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="single-service-item">
            
              <div class="service-left-bg"></div>
              <div class="service-icon">
                <a href="{{ route('products') }}">
                  <img src="{{URL::to('public/img/oil-products.jpg')}}" alt="Massage Oil">
                </a>
              </div>
              <div class="service-text">
                <h4><a href="{{ route('products') }}">Massage Oil</a></h4>
              </div>
            </div>
          </div>
          <!--End single service icon-->
          <!--Start single service icon-->
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="single-service-item">
            
              <div class="service-left-bg"></div>
              <div class="service-icon">
                <a href="{{ route('products') }}">
                  <img src="{{URL::to('public/img/soap-products.jpg')}}" alt="Beauty Soap">
                </a>
              </div>
              <div class="service-text">
                <h4><a href="{{ route('products') }}">Beauty Soap</a></h4>
              </div>
            </div>
          </div>
          <!--End single service icon-->
          <!--Start single service icon-->
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="single-service-item">
              
              <div class="service-left-bg"></div>
              <div class="service-icon">
                <a href="{{ route('products') }}">
                  <img src="{{URL::to('public/img/top-product1.jpg')}}" alt="Millionaires Vapor Rub">
                </a>
              </div>
              <div class="service-text">
                <h4><a href="{{ route('products') }}">Millionaires Vapor Rub</a></h4>
              </div>
            </div>
          </div>
          <!--End single service icon-->
          <!--Start single service icon-->
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="single-service-item">
              
              <div class="service-left-bg"></div>
              <div class="service-icon">
                <a href="{{ route('products') }}">
                  <img src="{{URL::to('public/img/top-product2.jpg')}}" alt="Millionaires Massage Oil">
                </a>
              </div>
              <div class="service-text">
                <h4><a href="{{ route('products') }}">Millionaires Massage Oil</a></h4>
              </div>
            </div>
          </div>
          <!--End single service icon-->
          <!--Start single service icon-->
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="single-service-item">
              
              <div class="service-left-bg"></div>
              <div class="service-icon">
                <a href="{{ route('products') }}">
                  <img src="{{URL::to('public/img/top-product3.jpg')}}" alt="Millionaires Miracle Oil">
                </a>
              </div>
              <div class="service-text">
                <h4><a href="{{ route('products') }}">Millionaires Miracle Oil</a></h4>
              </div>
            </div>
          </div>

          <div class="col-md-12" style="text-align: center;">
              <div class="field-inner theme-btn">
                <a href="{{ route('products') }}" class="thm-btn">Show More Products</a>
              </div>
          </div>

          <!--End single service icon-->
        </div>
      </div>
    </section>

    <section class="about about-2" style="padding-bottom:0">
      <div class="container">
        <div class="item-list">
          <div class="row">
          
            <div class="col-md-6 col-xs-12">
              <div class="item">
                <figure class="image-box">
                  <img src="{{URL::to('public/img/who-we-are.jpg')}}" alt="" class="img-responsive">
                </figure>
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="item clearfix">
                <div class="sec-title">
                  <h2 class="left">Who we are?</h2>
                </div>
                <div class="content-box">
                  <h4>Success Formula International Marketing Corporation</h4>
                  <p>Started its operation on October 8, 2016, as Success Formula Marketing, in Davao City. In just a year, its humble beginning gain rewards for itself and made it as one of the successful corporation from the incomparable efforts of the founder, Mr. Michael Siega Javier with his beloved partner Lovely Braga Javier. Success Formula is now a family corporation, built thru the expert and passion, and has now grown as one and awarded as the leading and innovative Multi-level Marketing that provides their individual consumer a very easy and profitable kind way of business.
                  </p>
                  <a href="{{ route('about-us') }}" class="thm-btn">More About Us</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="min-features">
      <div class="container">
        <div class="small-features">
          <div class="row">   
            <div class="col-md-4">
              <div class="single-features">
                <div class="media">
                  <img class="mr-3" src="{{URL::to('public/img/leader.png')}}" alt=" image">
                  <div class="media-body">
                  <h5 class="mt-0">Business with Thought Leadership</h5>
                  </div>
                </div>  
              </div>  
            </div>
            <div class="col-md-4">
              <div class="single-features">
                <div class="media">
                  <img class="mr-3" src="{{URL::to('public/img/world-map.png')}}" alt="image">
                  <div class="media-body">
                  <h5 class="mt-0">Global consumer insights for business</h5>
                  </div>
                </div>  
              </div>  
            </div>
            <div class="col-md-4">
              <div class="single-features">
                <div class="media">
                  <img class="mr-3" src="{{URL::to('public/img/money.png')}}" alt="Generic placeholder image">
                  <div class="media-body">
                  <h5 class="mt-0">Segment of focused investors </h5>
                  </div>
                </div>  
              </div>  
            </div>          
          </div>
        </div>
      </div>
    </section>
    
    <section class="ask-question">
      <div class="container">

        <div class="row">
          <div class="col-md-6 pull-right col-sm-10 col-sm-offset-1 col-md-offset-0 col-xs-12">
            <!--ask item -->
            <div class="ask-box active">
              <div class="ask-circle">
                <span class="fa fa-rocket"></span>
              </div>
              <div class="ask-info">
                <h3 class="text-white">OUR MISSION</h3>
                <p class="text-white">To provide high quality products and services, to organized profitable and stable business to our leaders. Bottomline, to introduce more successful entrepreneurs.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 pull-right col-sm-10 col-sm-offset-1 col-md-offset-0 col-xs-12">
            <!--ask item -->
            <div class="ask-box active mt-30">
              <div class="ask-circle">
                <span class="fa fa-eye"></span>
              </div>
              <div class="ask-info">
                <h3 class="text-white">Our VISION</h3>
                <p class="text-white">To be one of a successful network marketing company in the Philippines and globally.</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- latest-news -->
    <section class="news">
      <div class="container">
        <div class="sec-title">
          <h2 class="left">Latest News & Events</h2>
          <p>Keeping you up-to-date with all the latest information, news, and events on Success Formula International.</p>
        </div>
        <div class="latest-news-carousel owl-carousel owl-theme">

          @foreach($NewsEventsList as $newsevents)
            <div class="news-post">
              <div class="news-post-image">
                <div class="news-overlay"></div>  
                <a href="{{ route('news-event-detail').'?Title='.$newsevents->SlugTitle }}">
                  <img src="{{URL::to('public/img/newsevents/'.$newsevents->RecordID.'.jpg')}}" alt="{{ $newsevents->Title }}" class="img-responsive">
                </a>
              </div>
              <div class="news-post-text" style="min-height: 200px;">
                <h3>
                  <a href="{{ route('news-event-detail').'?Title='.$newsevents->SlugTitle }}">{{ $newsevents->Title }}</a>
                </h3>
                <p>
                  {!! Str::limit($newsevents->Contents,100) !!}
                </p>
              </div>
              <div class="news-post-meta">
                <a href="#"><i class="fa fa-user"></i>{{ $newsevents->PostedBy }}</a>
                <a href="#">
                  <i class="fa fa-calendar"></i>
                  {{ date_format(date_create($newsevents->PublishDate),"d F Y") }}
                </a>
                <a href="{{ route('news-event-detail').'?Title='.$newsevents->SlugTitle }}"><i class="fa fa-book"></i> Read More</a>
              </div>
            </div>
          @endforeach
                 
        </div>
      </div>  
    </section>

    <section class="get-quote-section" style=" background-image:url(public/img/getquote-bg-img.jpg);">
        <div class="container">
            <div class="row clearfix">
                  
              <!--Form Column-->
              <div class="form-column col-lg-7 col-md-8 col-sm-12 col-xs-12">
                <!--Title-->
                  <div class="sec-title ">
                      <h2 class="left">Get In Touch To Us</h2>
                      <p>Send your inquiry and leave us a message. One of our representatives will happily email/contact you. We'll do everything we can to respond to you as quickly as possible. Please complete the form below. We respect your privacy and your information is safe and will never be shared. We look forward to hearing from you.</p>
                  </div>
          
                <div class="form-box default-form">
                      <form method="post" action="{{ route('send-message') }}">
                      
                          <div class="row clearfix">
                              <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                  <div class="field-inner"><input type="text" name="form_name" value="" placeholder="Name" required=""></div>
                              </div>
                              <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                  <div class="field-inner"><input type="email" name="form_email" value="" placeholder="Email" required=""></div>
                              </div>
                              <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                  <div class="field-inner"><input type="text" name="form_phone" value="" placeholder="Phone"></div>
                              </div>
                              <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                  <div class="field-inner"><input type="text" name="form_subject" value="" placeholder="subject"></div>
                              </div>
                              <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                  <div class="field-inner"><textarea name="form_message" placeholder="Message"></textarea></div>
                              </div>
                              <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                  <div class="field-inner theme-btn"><button type="submit" class="thm-btn">Submit Now</button></div>
                              </div>
                          </div>
                          
                      </form>
                  </div>
              </div>
              
              <!--Image Column-->
              <div class="image-column col-lg-5 col-md-4 col-sm-12 col-xs-12">
              <figure class="image"><img src="{{URL::to('public/img/contact-us.png')}}" alt=""></figure>
              </div>
              
          </div>
      </div>
    </section>
@endsection



