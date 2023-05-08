@extends('layout.frontweb')
@section('content')

  <!--Page Title-->
  <section class="bredcrumb">
    <div class="bg-image text-center" style="background-image: url('public/img/products-banner.jpg');">
      <h1>Cart</h1>
    </div>
    <div class="">
      <ul class= "middle">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a class="inner" href="#">Cart</a></li>
      </ul>
    </div>
  </section>
  <!--Page Title Ends-->

    <!--team section-->
    <section class="our-gallery">
      <div class="container">
        <div class="row">

          <div class="col-sm-12">
            <div class="col-md-12 col-xs-12">
              <div class="table_block table-responsive mb-40" id="order-detail-content">
                <table class="table table-bordered" id="cart_summary">
                  <thead>
                    <tr>
                       <th colspan="2" class="cart_product first_item">Product name</th>
                       <th class="cart_unit item">Price</th>
                       <th class="cart_quantity item">QTY</th>
                       <th class="cart_delete last_item">SUBTOTAL</th>
                    </tr>
                  </thead>
                  @if(count($Cart) <= 0)
                    <tbody class="dropdown-product-list">
                      <tr class="cart_item first_item address_0 odd dd-product-group">
                        <td colspan="2">Your cart is empty</td>
                        <th></th>
                        <th></th>
                        <th></th>
                      </tr>
                  @else
                    <tbody class="dropdown-product-list">

                      <form id="frmCart" action="{{ route('update-item-cart') }}" method="post">
                        
                        <input type='hidden' id='_token' name='_token' value="{{ $Token }}">
                        
                        @if(Session::get('SESSION_ID'))
                          <input type='hidden' name='SessionID' value="{{ Session::get('SESSION_ID') }}">
                        @else
                          <input type='hidden' name='SessionID' value="0">
                        @endif

                        @if(Session::get('MEMBER_ENTRY_ID'))
                          <input type='hidden' name='MemberEntryID' value="{{ Session::get('MEMBER_ENTRY_ID') }}">
                        @else
                          <input type='hidden' name='MemberEntryID' value="0">
                        @endif

                        @php($CartTotalAmountDue = 0)
                        @foreach ($Cart as $key)
                          @php($CartTotalAmountDue = $CartTotalAmountDue + ($key->Qty * (!Session('MEMBER_LOGGED_IN') ? $key->RetailPrice : $key->DistributorPrice)))

                          @php($ProductID = $key->ProductID)
                          @php($ProductName = $key->ProductName)
                          @php($ProductCode = $key->ProductCode)
                          @php($Category = $key->Category)
                          @php($Brand = $key->Brand)
                          @php($Qty = $key->Qty)
                          @php($DistributorPrice = $key->DistributorPrice)
                          @php($RetailPrice = $key->RetailPrice)
                          @php($Measurement = $key->Measurement)
                          @php($Remarks = $key->Remarks)

                          @php($ImagePath = 'public/img/products/'.$ProductID.'/'.$ProductID.'-1-'.config('app.Thumbnail').'.jpg')
                          @if (!File::exists($ImagePath))
                            @php($ImagePath = 'public/img/products/product-no-image-'.config('app.Thumbnail').'.jpg')
                          @endif

                          <tr class="cart_item first_item address_0 odd dd-product-group">
                             <td class="cart_product">

                                <input type='hidden' name='arrayProductID[]' value='{{ $ProductID }}'>
                                <input type='hidden' name='Remarks{{$ProductID}}' value='{{ $Remarks }}'>                              

                                <a href="{{ route('product-detail').'?ProductName='.$key->ProductName}}">
                                   <img alt="{{ $ProductName }}" src="{{URL::to($ImagePath)}}">
                                </a>
                             </td>
                             <td class="cart_description">
                                <p class="product-name">
                                   <a href="{{ route('product-detail').'?ProductName='.$ProductName}}">{{ $ProductName }}</a>
                                </p>
                                <small>Product Code: <span>{{ $ProductCode }}</span></small>
                                <small>Brand: <span>{{ $Brand }}</span></small>
                                <small>Category: <span>{{ $Category }}</span></small>
                             </td>
                             <td data-title="PRICE" class="cart_unit">
                                <span class="price">{{ (!Session('MEMBER_LOGGED_IN') ? 'Php '.number_format($RetailPrice,2) : 'Php '.number_format($DistributorPrice,2)). '/'. $Measurement }}</span>
                             </td>
                             <td class="cart_quantity text-center">
                                <div class="cart_quantity_button clrfix">
                                  <input type="text" name="Qty{{$ProductID}}" value="{{ number_format($Qty) }}" class="cart_quantity_input form-control grey count NumberOnly" autocomplete="off" size="2">
                                  <span class="input-group-btn">
                                          <button type="submit" data-toggle="tooltip" title="Update" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
                                  </span>
                                  <span class="input-group-btn">
                                    <button type="button" data-toggle="tooltip" title="Remove" class="btn btn-danger" onclick="RemoveFromCart({{ $ProductID }})"><i class="fa fa-times-circle"></i></button>
                                  </span>
                                </div>
                             </td>
                             <td class="subtotal" data-title="SUBTOTAL"  style="text-align: right;">
                                  Php {{number_format((!Session('MEMBER_LOGGED_IN') ? $RetailPrice : $DistributorPrice) * $Qty,2)}}
                             </td>
                          </tr>
                        @endforeach
                      </form>
                      <tr class="cart_item first_item address_0 odd dd-product-group">
                         <td colspan="4" class="cart_quantity" style="text-align: right;">Total Amount Due</td>
                         <td class="subtotal" data-title="SUBTOTAL"  style="text-align: right;">
                              Php {{number_format($CartTotalAmountDue,2)}}
                         </td>
                      </tr>
                      <tr class="cart_item first_item address_0 odd dd-product-group">
                         <td colspan="5" style="text-align: right;">
                            <button id="btnAddMoreProduct" class="thm-btn" style="background: #f2c21a; border: 2px solid #f2c21a; color: #fff;">
                            Add More Product</button>
                            <button id="btnCheckout" class="thm-btn" style="background: #ed6663; border: 2px solid #ed6663; color: #fff;">
                            Checkout Now</button>
                         </td>
                      </tr>
                    </tbody>
                  @endif
                </table>
              </div>
            </div>

          </div>

        </div>          
      </div>
    </section>

    <script type="text/javascript">
        
      $("#btnAddMoreProduct").click(function(){
        window.location.replace('{{ route('products') }}');
      });

      $("#btnCheckout").click(function(){
        window.location.replace('{{ route('checkout') }}');
      });

    </script>
@endsection



