
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <div class="footer-text">
              <a href="{{ route('home') }}">
                <img src="{{URL::to('img/logo-h.png')}}" alt="logo">
              </a>
            </div>

            <div class="links">
              <div class="col-md-6">
              <ul class="">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('products') }}">Products</a></li>
                <li><a href="{{ route('centers') }}">Centers</a></li>
                <li><a href="{{ route('news-events') }}">News & Events</a></li>
                <li><a href="{{ route('about-us') }}">About</a></li>
                <li><a href="{{ route('contact-us') }}">Contact Us</a></li>
              </ul>
              </div>
              <div class="col-md-6">
              <ul class="">
                <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                <li><a href="{{ route('shipping-cancellation-return-policy') }}">Shipping, Cancellation & Return Policy</a></li>
              </ul>
              </div>
            </div>

          </div>
          <div class="col-md-4">
            <div class="location">
              <h3>Location</h3>
              <ul>
                <li><i class="fa fa-home"></i> &nbsp&nbsp&nbsp&nbsp&nbsp{{ config('app.COMPANY_ADDRESS1').', '.config('app.COMPANY_ADDRESS2').', '.config('app.COMPANY_ADDRESS3') }}</li>
                <li><i class="fa fa-phone"></i> <a href="">{{ config('app.COMPANY_MOBILE1').' / '.config('app.COMPANY_MOBILE2') }}</a></li>
                <li><i class="fa fa-fax"></i> <a href="">{{ config('app.COMPANY_TEL') }}</a></li>
                <li><i class="fa fa-envelope"></i> <a href="{{ config('app.COMPANY_EMAIL') }}"> {{ config('app.COMPANY_EMAIL') }} </a></li>
              </ul>
            </div>
            <div class="social-icons">
              <a href="https://www.facebook.com/lionsAdminpost" class="btn btn-social btn-social-o facebook"  target="_blank">
                <i class="fa fa-facebook-f"></i>
              </a>
              <a href="https://www.youtube.com/channel/UCsENr7HD29n8SQNPXDJ8Upw" class="btn btn-social btn-social-o skype" target="_blank">
                <i class="fa fa-youtube"></i>
              </a>
            </div>
          </div>
          <div class="col-md-4">
            <div class="sidebar-wrapper">
              <div class="single-sidebar">
                            <div class="wedgit-title">
                                <h3>Popular News & Events</h3>
                            </div>
                            <ul class="popular-post">
                                @php($NewsEventCntr = 0)
                                @foreach($NewsEventsList as $newsevents)
                                  @if($NewsEventCntr <= 2)
                                    <li>
                                        <div class="img-holder">
                                            <a href="{{ route('news-event-detail').'?Title='.$newsevents->SlugTitle }}">
                                              <img src="{{URL::to('img/newsevents/'.$newsevents->RecordID.'.jpg')}}" alt="{{ $newsevents->Title }}">
                                            </a>
                                            <div class="overlay-style-one">
                                                <div class="box">
                                                    <div class="content">
                                                        <a href="{{ route('news-event-detail').'?Title='.$newsevents->SlugTitle }}"><i class="fa fa-link" aria-hidden="true"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="title-holder">
                                            <a href="{{ route('news-event-detail').'?Title='.$newsevents->SlugTitle }}">
                                              <h5 class="post-title">{{ $newsevents->Title }} - {{ $newsevents->PostedBy }}</h5>
                                            </a>
                                            <h6 class="post-date">{{ date_format(date_create($newsevents->PublishDate),"d F Y") }}</h6>
                                        </div>
                                    </li>
                                  @endif
                                  @php($NewsEventCntr = $NewsEventCntr + 1)
                                @endforeach
                            </ul>
                        </div>
            </div>
          </div>
        </div>
        <!-- COPY RIGHT -->
        <div class="copyright">
          <hr>
          <div class="row justify-content-center">
            <div class="col-sm-12">
              <div class="copyRight_text text-center">
                <p> © 2023 <a href="https://www.fandaitservices.com">Success Formula International.</a>  All Copyright Reserved.</p>
              </div>
            </div>
          </div>
        </div>
      </div>