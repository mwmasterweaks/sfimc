@extends('layout.frontweb')
@section('content')

  <!--Page Title-->
  <section class="bredcrumb">
    <div class="bg-image text-center" style="background-image: url('img/products-banner.jpg');">
      <h1>News & Event Detail</h1>
    </div>
    <div class="">
      <ul class= "middle">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('news-events') }}">News & Event</a></li>
        <li><a class="inner" href="#">News & Event Detail</a></li>
      </ul>
    </div>
  </section>

    @php($RecordID = 0)
    @php($Title = "")
    @php($SlugTitle = "")
    @php($Contents = "")
    @php($PostedBy = "")
    @php($PublishDate = "")
    @php($Status = "")
    @if(isset($NewsEventInfo))
        @php($RecordID = $NewsEventInfo->RecordID)
        @php($Title = $NewsEventInfo->Title)
        @php($SlugTitle = $NewsEventInfo->SlugTitle)
        @php($Contents = $NewsEventInfo->Contents)
        @php($PostedBy = $NewsEventInfo->PostedBy)
        @php($PublishDate = $NewsEventInfo->PublishDate)
        @php($Status = $NewsEventInfo->Status)
    @endif

     <!--Sidebar Page-->
    <div class="sidebar-page">
        <div class="container">
            <div class="row clearfix">              
                <!--Content Side--> 
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <section class="blog-container news">  
                        <div class="news-post">
                            <div class="news-post-image">
                                <img src="{{URL::to('img/newsevents/'.$RecordID.'.jpg')}}" alt="{{ $Title }}" alt="Image" class="img-responsive">
                            </div>
                            <div class="news-post-text">
                                <h3><a href="#">{{ $Title }}</a></h3>
                                <p>
                                    {!! $Contents !!}
                                </p>
                            </div>
                            <div class="clearfix"></div>
                            <div class="news-post-meta">
                                <a href="#"><i class="fa fa-user"></i>{{ $PostedBy }}</a>
                                <a href="#"><i class="fa fa-calendar"></i> {{ date_format(date_create($PublishDate),"d F Y") }}</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </section> 
                </div>
                <!--Content Side-->
                
                <!--Sidebar-->  
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="sidebar-wrapper">
                        <!--Start single sidebar--> 
                        <div class="single-sidebar">
                            <div class="wedgit-title">
                                <h3>Other News & Events</h3>
                            </div>
                            <ul class="popular-post">
                                @foreach($NewsEventsList as $newsevents)
                                    @if($newsevents->RecordID != $RecordID)
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
                                                <a href="{{ route('news-event-detail').'?Title='.$newsevents->SlugTitle }}"><h5 class="post-title">{{ $newsevents->Title }}</h5></a>
                                                <h6 class="post-date">{{ date_format(date_create($newsevents->PublishDate),"d F Y") }}</h6>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                               
                            </ul>
                        </div>
                        <!--End single sidebar-->

                    </div>                                  
                </div>
                <!--Sidebar-->                           
            </div>
        </div>
    </div>

@endsection



