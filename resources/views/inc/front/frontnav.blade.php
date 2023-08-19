        <div class="container">
          <div class="row">
            <div class="col-md-5">
              <div class="main-logo">
                <a href="index.html"><img src="{{ asset(config('app.src_name') . '/img/logo-head.png') }}" alt=""></a>
              </div>
            </div>
            <div class="col-md-7 menu-column">
              <nav class="main-menu">
                <div class="navbar-header">     
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  </button>
                </div>
                <div class="navbar-collapse collapse clearfix">
                  <ul class="navigation clearfix pull-right">
                    <li class="{{ $Page == 'home' ? 'current' : '' }}">
                      <a href="{{ route('home') }}">Home</a>
                    </li>                 
                    <li class="{{ $Page == 'centers' ? 'current' : '' }}">
                      <a href="{{ route('centers') }}">Centers</a>
                    </li>
                    <li class="{{ $Page == 'products' ? 'current' : '' }}">
                      <a href="{{ route('products') }}">Products</a>
                    </li>
                    <li class="{{ $Page == 'newsevents' ? 'current' : '' }}">
                      <a href="{{ route('news-events') }}">News & Events</a>
                    </li>
                    <li class="{{ $Page == 'about-us' ? 'current' : '' }}">
                      <a href="{{ route('about-us') }}">About</a>
                    </li>
                    <li class="{{ $Page == 'contact-us' ? 'current' : '' }}">
                      <a href="{{ route('contact-us') }}">Contact Us</a>
                    </li>
                    <li>
                      <a href="{{ route('cart') }}">
                          <span id="spnCartItemCount" style="color: red;">
                                  {{ $CartItemCount }}
                          </span>
                          <i class="fa fa-shopping-cart" ></i>
                      </a>
                    </li>
                  </ul>
                  <ul class="mobile-menu clearfix">
                    <li class="{{ $Page == 'home' ? 'current' : '' }}">
                      <a href="{{ route('home') }}">Home</a>
                    </li>                 
                    <li class="{{ $Page == 'centers' ? 'current' : '' }}">
                      <a href="{{ route('centers') }}">Centers</a>
                    </li>
                    <li class="{{ $Page == 'products' ? 'current' : '' }}">
                      <a href="{{ route('products') }}">Products</a>
                    </li>
                    <li class="{{ $Page == 'newsevents' ? 'current' : '' }}">
                      <a href="{{ route('news-events') }}">News & Events</a>
                    </li>
                    <li class="{{ $Page == 'about-us' ? 'current' : '' }}">
                      <a href="{{ route('about-us') }}">About</a>
                    </li>
                    <li class="{{ $Page == 'contact-us' ? 'current' : '' }}">
                      <a href="{{ route('contact-us') }}">Contact Us</a>
                    </li>
                    <li>
                      <a href="{{ route('cart') }}">
                          <span id="spnCartItemCount" style="color: red;">
                                  {{ $CartItemCount }}
                          </span>
                          <i class="fa fa-shopping-cart"></i>
                      </a>
                    </li>
                  </ul>
                </div>
              </nav>
            </div>
           
          </div>
        </div>