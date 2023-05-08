@extends('layout.frontweb')
@section('content')

    <section class="about">
      <div class="container">
        <div class="item-list">
          <div class="row">
            
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="sec-title">
                  <h2 class="left">About Success Formula International</h2>
                  <p>
                    Started its operation on October 8, 2016, as Success Formula Marketing, in Davao City. In just a year, its humble beginning gain rewards for itself and made it as one of the successful corporation from the incomparable efforts of the founder, Mr. Michael Siega Javier with his beloved partner Lovely Braga Javier. Success Formula is now a family corporation, built thru the expert and passion, and has now grown as one and awarded as the leading and innovative Multi-level Marketing that provides their individual consumer a very easy and profitable kind way of business.
                  </p>
                </div>
                <div class="row">
                  <div class="col-md-12">
                      <p>
                        Achieve your desires in life,
                      </p>
                  </div>
                  <div class="col-md-12" style="text-align: center;">
                      <span style="font-size: 40px; color: #2a9409;">
                        <span class="fa fa-quote-left" style="color: #fab915;"></span>
                        Your Success Belongs Here
                        <span class="fa fa-quote-right" style="color: #fab915;"></span>
                      </span>
                  </div>
                </div>
            </div>
            <div class="col-md-5 col-sm-10 col-xs-12">
              <div class="item">
                <figure class="image-box">
                  <img src="{{URL::to('public/img/who-we-are.jpg')}}" alt="" class="img-responsive">
                </figure>
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

    <section id="how-it-work" class="how-it-work">
        <div class="container text-center">
          <div class="sec-title">
            <h2 class="center">Company Awards and Recognition</h2>
            <p>Success Formula International proud to be publicly recognized as a great company to work with. We work with ambitious leaders who want to define the future, not hide from it. Together, we achieve extraordinary outcomes.</p>
          </div>
          <div class="how-one-container">
              <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 5px;">
                 <div class="inner-box">
                    <div class="icon-box">
                       <img src="{{URL::to('public/img/award1.jpg')}}" alt="2019 Golden Globe Awards For Business Excellence and Filipino Achiever">
                    </div>
                 </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 5px;">
                 <div class="inner-box">
                    <div class="icon-box">
                       <img src="{{URL::to('public/img/award2.jpg')}}" alt="38th People's Choice Excellence Awards 2018">
                    </div>
                 </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 5px;">
                 <div class="inner-box">
                    <div class="icon-box">
                       <img src="{{URL::to('public/img/award3.jpg')}}" alt="29th Asia Pacific Excellence Awards 2017">
                    </div>
                 </div>
              </div>

          </div>
        </div>
    </section>

@endsection



