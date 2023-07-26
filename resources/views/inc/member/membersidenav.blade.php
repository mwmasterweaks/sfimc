
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ URL::to('img/members/member-no-image.png') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Session('MEMBER_NAME') }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> {{ Session('MEMBER_ENTRY_CODE') }}</a>
        </div>
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">

        <li class="header" style="text-align: center;">SFI</li>

        <li>
          <a href="{{ route('home') }}">
            <i class="fa fa-home"></i> <span>Home</span>
          </a>
        </li>

        <li>
          <a href="{{ route('member-dashboard') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        <li>
          <a href="{{ route('member-profile') }}">
            <i class="fa fa-list"></i> <span>My Profile</span>
          </a>
        </li>

        <li>
          <a href="{{ route('member-genealogy') }}?MemberEntryID={{ Session('MEMBER_ENTRY_ID') }}&MaxLevel=100&level=6">
            <i class="fa fa-list"></i> <span>My Genealogy</span>
          </a>
        </li>

        <li>
          <a href="{{ route('member-ewallet-ledger') }}">
            <i class="fa fa-list"></i> <span>E-Wallet Ledger</span>
          </a>
        </li>
         <li>
          <a href="#">
            <i class="fa fa-list"></i> <span>Rebates Income History</span>
          </a>
        </li>

        <li>
          <a href="{{ route('member-ewallet-withdrawal') }}">
            <i class="fa fa-list"></i> <span>Encashment</span>
          </a>
        </li>

        <li>
          <a href="{{ route('member-vouchers') }}">
            <i class="fa fa-list"></i> <span>Vouchers</span>
          </a>
        </li>

        <li>
          <a href="{{ route('member-order-history') }}">
            <i class="fa fa-list"></i> <span>Order History</span>
          </a>
        </li>

        <li>
          <a href="{{ route('member-upgrade-entry') }}">
            <i class="fa fa-list"></i> <span>Upgrade Entry</span>
          </a>
        </li>

        <li>
          <a href="{{ route('member-change-password') }}">
            <i class="fa fa-lock"></i>
            <span>Change Password</span>
          </a>
        </li>
        <li>
          <a href="{{ route('member-logout') }}">
            <i class="fa fa-sign-out"></i>
            <span>Logout</span>
          </a>
        </li>

      </ul>