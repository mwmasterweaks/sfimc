@extends('layout.frontweb')
@section('content')

  <!--Page Title-->
  <section class="bredcrumb">
    <div class="bg-image text-center" style="background-image: url('img/products-banner.jpg');">
      <h1>News & Events</h1>
    </div>
    <div class="">
      <ul class= "middle">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a class="inner" href="#">News & Events</a></li>
      </ul>
    </div>
  </section>
  <!--Page Title Ends-->
    <section class="news">
        <div class="container">
            <div class="row">
                @foreach($NewsEventsList as $newsevents)
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="news-post">
                          <div class="news-post-image">
                            <div class="news-overlay"></div>  
                            <a href="{{ route('news-event-detail').'?Title='.$newsevents->SlugTitle }}">
                              <img src="{{URL::to('img/newsevents/'.$newsevents->RecordID.'.jpg')}}" alt="{{ $newsevents->Title }}" class="img-responsive">
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
                    </div>
                @endforeach
            </div>  
        </div>  
    </section>

@endsection



