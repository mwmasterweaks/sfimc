
@php( $UnVerifiedOrder = 0 ) 
@php( $VerifiedOrder = 0 ) 
@php( $PackedOrder = 0 ) 
@php( $ShippedOrder = 0 ) 
@php( $UnCollectedOrder = 0 ) 

@foreach($AlertLabels as $alert)  

  @php( $UnVerifiedOrder = $alert->UnVerifiedOrder)
  @php( $VerifiedOrder = $alert->VerifiedOrder ) 
  @php( $PackedOrder = $alert->PackedOrder ) 
  @php( $ShippedOrder = $alert->ShippedOrder ) 
  @php( $UnCollectedOrder = $alert->UnCollectedOrder ) 

@endforeach
@php( $OrderTask = $UnVerifiedOrder + $VerifiedOrder + $PackedOrder + $ShippedOrder + $UnCollectedOrder) 

      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset(config('app.src_name') . 'img/members/member-no-image.png') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Session('ADMIN_FULLNAME') }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> {{ Session('ADMIN_USERNAME') }}</a>
        </div>
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">

        <li class="header" style="text-align: center;">S F I</li>

        <li>
          <a href="{{ route('admin-dashboard') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        @php($IsCenterManagement = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Center Management'))  
        @if($IsCenterManagement)
          <li>
            <a href="{{ route('admin-center-management') }}">
              <i class="fa fa-list"></i>
              <span>Center Management</span>
            </a>
          </li>
        @endif

        @php($IsCodeGeneration = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Code Generation'))  
        @php($IsCodeDistribution = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Code Distribution'))  
        @if($IsCodeGeneration || $IsCodeDistribution)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-list"></i>
              <span>Code Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @if($IsCodeGeneration)
              <li><a href="{{ route('admin-code-generation') }}"><i class="fa fa-list"></i> Code Generation</a></li>
              @endif
              @if($IsCodeDistribution)
              <li><a href="{{ route('admin-code-distribution') }}"><i class="fa fa-list"></i> Code Distribution</a></li>
              @endif
            </ul>
          </li>
        @endif

        @php($wirecode = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Wire Code'))  
        @if($wirecode)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-list"></i>
              <span>Wire Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('admin-wirecode') }}"><i class="fa fa-list"></i> Wire Code </a></li>
              <li><a href="{{ route('admin-wire-history') }}"><i class="fa fa-list"></i> Wire Active History</a></li>
              <li><a href="{{ route('admin-member-active-wire') }}"><i class="fa fa-list"></i> Members Activate Wire</a></li>
            </ul>
          </li>
        @endif

        @php($IsMemberEntry = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Member Entry'))  
        @php($IsAllowEditMemberInfo = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Allow Edit Member Info'))
        @php($IsMemberVouchers = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Member Vouchers'))  
        @if($IsMemberEntry || $IsAllowEditMemberInfo || $IsMemberVouchers)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-list"></i>
              <span>Member Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @if($IsMemberEntry || $IsAllowEditMemberInfo)
              <li><a href="{{ route('admin-member-management') }}"><i class="fa fa-list"></i> Member Entry</a></li>
              @endif
              @if($IsMemberVouchers)
              <li><a href="{{ route('admin-member-voucher') }}"><i class="fa fa-list"></i> Member Vouchers</a></li>
              @endif
            </ul>
          </li>
        @endif
        @php($IsPackageManagement = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Package Management'))  
        @if($IsPackageManagement)
          <li>
            <a href="{{ route('admin-package-management') }}">
              <i class="fa fa-list"></i>
              <span>Package Management</span>
            </a>
          </li>
        @endif

        @php($IsProductManagement = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Product Management'))  
        @if($IsProductManagement)
          <li>
            <a href="{{ route('admin-product-management') }}">
              <i class="fa fa-list"></i>
              <span>Product Management</span>
            </a>
          </li>
        @endif

        @php($IsInventoryList = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Inventory List'))  
        @php($IsAllowSetBeginningBalance = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Allow Set Beginning Balance'))  
        @php($IsAllowSetMinMaxLevel = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Allow Set Min/Max Level'))  

        @php($IsInventoryAdjustment = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Inventory Adjustment'))  

        @php($IsPurchaseOrder = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Purchase Order (Centers)'))  
        @php($IsPOProcessing = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'PO Processing (Main)'))  
        @php($IsPurchaseReceive = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Purchase Receive (Centers)'))  

        @if($IsInventoryList || $IsAllowSetBeginningBalance || $IsAllowSetMinMaxLevel || $IsInventoryAdjustment || $IsPurchaseOrder || $IsPOProcessing || $IsPurchaseReceive)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-list"></i>
              <span>Inventory Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @if($IsInventoryList || $IsAllowSetBeginningBalance || $IsAllowSetMinMaxLevel)
              <li><a href="{{ route('admin-inventory-list') }}"><i class="fa fa-list"></i> Inventory List</a></li>
              @endif
              @if($IsInventoryAdjustment)
              <li><a href="{{ route('admin-inventory-adjustment') }}"><i class="fa fa-dropbox"></i> Inventory Adjustment</a></li>
              @endif
              @if($IsPurchaseOrder || $IsPOProcessing || $IsPurchaseReceive)
              <li class="header" style="background:#1e272c;margin-left:-7px;color:#fff;font-size:14px;">Inventory Replenishment Process</li>
                @if($IsPurchaseOrder)
                <li><a href="{{ route('admin-purchase-order') }}"><i class="fa fa-dropbox"></i> Purchase Order (Centers)</a></li>
                @endif
                @if($IsPOProcessing)
                <li><a href="{{ route('admin-po-processing') }}"><i class="fa fa-dropbox"></i> PO Processing (Main)</a></li>
                @endif
                @if($IsPurchaseReceive)
                <li><a href="{{ route('admin-purchase-receive') }}"><i class="fa fa-dropbox"></i> Purchase Receive (Centers)</a></li>
                @endif
              @endif
            </ul>
          </li>
        @endif

        @php($IsMemberEWallet = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Member E-Wallet'))  
        @php($IsEWalletWithdrawal = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'E-Wallet Withdrawal'))  
        @if($IsMemberEWallet || $IsEWalletWithdrawal)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-list"></i>
              <span>E-Wallet Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @if($IsMemberEWallet)
              <li><a href="{{ route('admin-member-ewallet') }}"><i class="fa fa-list"></i> Member E-Wallet List</a></li>
              @endif
              @if($IsEWalletWithdrawal)
              <li><a href="{{ route('admin-ewallet-withdrawal') }}"><i class="fa fa-dropbox"></i> E-Wallet Withdrawal</a></li>
              @endif
            </ul>
          </li>
        @endif

        @php($IsOrderHistory = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Order History'))  
        @if($IsOrderHistory)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-list"></i>
              <span>Order Management</span>
              <span class="pull-right-container" style="margin-right: 15px;">
                @if($OrderTask > 0)
                  <small class="label pull-right bg-red">{{ number_format($OrderTask) }}</small>
                @endif
              </span>    
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('admin-order-history') }}"><i class="fa fa-list"></i> Order History</a></li>
              <li class="header" style="background:#1e272c;color:#fff;font-size:14px;">Order Processing</li>
              <li>
                <a href="{{ route('admin-order-unverified') }}">
                  <i class="fa fa-list"></i> Unverified Order
                  <span class="pull-right-container">
                    @if($UnVerifiedOrder > 0)
                      <small class="label pull-right bg-red">{{ number_format($UnVerifiedOrder) }}</small>
                    @endif
                  </span>   
                </a>
              </li>
              <li>
                <a href="{{ route('admin-order-verified') }}">
                  <i class="fa fa-list"></i> Verified Order
                  <span class="pull-right-container">
                    @if($VerifiedOrder > 0)
                      <small class="label pull-right bg-red">{{ number_format($VerifiedOrder) }}</small>
                    @endif
                  </span>                  
                </a>
              </li>
              <li>
                <a href="{{ route('admin-order-packed') }}">
                  <i class="fa fa-list"></i> Packed Order
                  <span class="pull-right-container">
                    @if($PackedOrder > 0)
                      <small class="label pull-right bg-red">{{ number_format($PackedOrder) }}</small>
                    @endif
                  </span>    
                </a>
              </li>
              <li>
                <a href="{{ route('admin-order-shipped') }}">
                  <i class="fa fa-list"></i> Shipped Order
                  <span class="pull-right-container">
                    @if($ShippedOrder > 0)
                      <small class="label pull-right bg-red">{{ number_format($ShippedOrder) }}</small>
                    @endif
                  </span>    
                </a>
              </li>
              <li><a href="{{ route('admin-order-delivered') }}"><i class="fa fa-list"></i> Delivered Order</a></li>
              <li><a href="{{ route('admin-order-returned') }}"><i class="fa fa-list"></i> Returned Order</a></li>
              <li><a href="{{ route('admin-order-cancelled') }}"><i class="fa fa-list"></i> Cancelled Order</a></li>
              <li class="header" style="background:#1e272c;color:#fff;font-size:14px;">Payment Collection</li>
              <li>
                <a href="{{ route('admin-order-uncollected') }}">
                  <i class="fa fa-list"></i> Uncollected Order
                  <span class="pull-right-container">
                    @if($UnCollectedOrder > 0)
                      <small class="label pull-right bg-red">{{ number_format($UnCollectedOrder) }}</small>
                    @endif
                  </span>    
                </a>
              </li>
              <li><a href="{{ route('admin-order-collected') }}"><i class="fa fa-list"></i> Collected Order</a></li>
            </ul>
          </li>
        @endif

        @php($IsShipperManagement = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Shipper Management'))  
        @if($IsShipperManagement)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-list"></i>
              <span>Shipper Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('admin-shipper-jat') }}"><i class="fa fa-list"></i> Shipper - J&T Settings</a></li>
            </ul>
          </li>
        @endif

        @php($IsSalesReport = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Sales Report'))
        @php($IsCommissionReport = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Commission Report'))
        @php($IsWithdrawalReport = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Withdrawal Report'))
        @php($IsTopSponsorshipReport = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Top Sponsorship Report'))
        @php($IsTopDirectSellingReport = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Top Direct Selling Report'))
        @php($IsTopCenterSalesReport = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Top Center Sales Report'))
        @php($IsTopNetworkBuilderReport = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Top Network Builder Report'))

        @if($IsSalesReport || $IsCommissionReport || $IsWithdrawalReport || $IsTopSponsorshipReport || $IsTopDirectSellingReport || $IsTopCenterSalesReport || $IsTopNetworkBuilderReport)
          <li class="treeview">
            <a href="#">
              <i class="fa fa-list"></i>
              <span>Reports</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @if($IsSalesReport)
              <li><a href="{{ route('admin-sales-report') }}"><i class="fa fa-list"></i> Sales Report</a></li>
              @endif
              @if($IsCommissionReport)
              <li><a href="{{ route('admin-commission-report') }}"><i class="fa fa-list"></i> Commission Report</a></li>
              @endif
              @if($IsWithdrawalReport)
              <li><a href="{{ route('admin-withdrawal-report') }}"><i class="fa fa-list"></i> Withdrawal Report</a></li>
              @endif
              @if($IsTopSponsorshipReport)
              <li><a href="{{ route('admin-top-sponsorship-report') }}"><i class="fa fa-list"></i> Top Sponsorship Report</a></li>
              @endif
              @if($IsTopDirectSellingReport)
              <li><a href="{{ route('admin-top-direct-selling-report') }}"><i class="fa fa-list"></i> Top Direct Selling Report</a></li>
              @endif
              @if($IsTopCenterSalesReport)
              <li><a href="{{ route('admin-center-sales-report') }}"><i class="fa fa-list"></i> Top Center Sales Report</a></li>
              @endif
              @if($IsTopNetworkBuilderReport)
{{-- 
              <li><a href="{{ route('admin-top-network-builder-report') }}"><i class="fa fa-list"></i> Top Network Builder Report</a></li
                >
 --}}
              @endif
            </ul>
          </li>
        @endif

        @php($IsUserAccount = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'User Account'))  
        @if($IsUserAccount)
          <li>
            <a href="{{ route('admin-user-accounts') }}">
              <i class="fa fa-list"></i>
              <span>User Account</span>
            </a>
          </li>
        @endif

        @php($IsCompanyInformation = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Company Information'))  
        @php($IsNewsEvents = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'News & Events'))  
        @php($IsFAQ = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'FAQ'))  
        @php($IsHomePageSliders = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Home Page Sliders'))  
        @php($IsFeaturedProducts = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Featured Products'))  
        @php($IsGetInTouchToUs = Session('IS_SUPER_ADMIN')==true ? true : $UserAccountModel->getUserAccountAccess(Session('ADMIN_ACCOUNT_ID'),'Get In Touch To Us'))  

        @if($IsCompanyInformation || $IsNewsEvents || $IsFAQ || $IsHomePageSliders || $IsFeaturedProducts || $IsGetInTouchToUs)
        <li class="treeview">
          <a href="#">
            <i class="fa fa-list"></i>
            <span>Content Management</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @if($IsCompanyInformation)
            <li><a href="{{ route('admin-company-info') }}"><i class="fa fa-list"></i> Company Information</a></li>
            @endif
            @if($IsNewsEvents)
            <li><a href="{{ route('admin-news-and-events') }}"><i class="fa fa-list"></i> News & Events</a></li>
            @endif
            @if($IsFAQ)
            <li><a href="{{ route('admin-faq') }}"><i class="fa fa-list"></i> FAQ</a></li>
            @endif
          </ul>
        </li>
        @endif

        <li>
          <a href="{{ route('admin-change-password') }}">
            <i class="fa fa-lock"></i>
            <span>Change Password</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin-logout') }}">
            <i class="fa fa-sign-out"></i>
            <span>Logout</span>
          </a>
        </li>

      </ul>