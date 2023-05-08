<div class="row">
  	<aside class="col-md-12">
      	<ul class="crypto-userinfo">
            <li>
              <i class="fa fa fa-home"></i> {{ config('app.COMPANY_ADDRESS1').', '.config('app.COMPANY_ADDRESS2').', '.config('app.COMPANY_ADDRESS3') }}
            </li>
            <li><i class="fa fa-phone"></i> {{ config('app.COMPANY_TEL') }}</li>
            @if(Session("MEMBER_LOGGED_IN"))            
              <li class="pull-right"> 
                <a href="{{ route('member-logout') }}" style="color:#fff;"><i class="fa fa-sign-out"></i> Logout</a>
              </li>
              <li class="pull-right"> 
                <a href="{{ route('member-dashboard') }}" style="color:#fff;"><i class="fa fa-dashboard"></i> My Account</a>
              </li>
            @else
              <li class="pull-right"> 
                <a href="{{ route('member-login') }}" style="color:#fff;"><i class="fa fa-user"></i> Member Login</a>
              </li>
            @endif
      	</ul>
  	</aside>
  	
</div>