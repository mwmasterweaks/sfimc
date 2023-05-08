@extends('layout.frontweb')

@section('content')

  <!--Order Data  -->
  @php($OrderID = 0)
  @php($OrderNo = null)
  @php($OrderDateTime = null)

  @php($CustomerNo = null)
  @php($CustomerName = null)
  @php($EmailAddress = null)
  @php($MobileNo = null)

  <!--Shipping Data  -->
  @php($Address = null)
  @php($City = null)
  @php($ZipCode = null)
  @php($StateProvince = null)
  @php($ShipCountry = null)

  @php($GrossAmount = 0)
  @php($ShippingCharges = 0)
  @php($TotalDiscountAmount = 0)
  @php($TotalAmountDue = 0)

  @php($Remarks = null)

  @php($Status = null)

  @if(isset($OrderInfo))

    @php($OrderID = $OrderInfo->OrderID)
    @php($OrderNo = $OrderInfo->OrderNo)
    @php($OrderDateTime = date_format(date_create($OrderInfo->OrderDateTime),"m/d/Y g:i A"))

    @php($CustomerName = $OrderInfo->CustomerName)
    @php($EmailAddress = $OrderInfo->EmailAddress)
    @php($MobileNo = $OrderInfo->MobileNo)

    <!--Shipping Data  -->
    @php($Address = $OrderInfo->Address)
    @php($City = $OrderInfo->City)
    @php($ZipCode = $OrderInfo->ZipCode)
    @php($StateProvince = $OrderInfo->StateProvince)
    @php($Country = $OrderInfo->Country)

    @php($GrossAmount = $OrderInfo->GrossTotal)
    @php($ShippingCharges = $OrderInfo->ShippingCharges)
    @php($TotalDiscountAmount = $OrderInfo->TotalDiscountAmount)
    @php($TotalAmountDue = $OrderInfo->TotalAmountDue)

    @php($ModeOfPayment = $OrderInfo->ModeOfPayment)

    @php($Remarks = $OrderInfo->Remarks)

    @php($Status = $OrderInfo->Status)

  @endif


@if($OrderID <= 0)
  <div class="page-heading">
    <div class="page-title">
      <div class="container">
        <div class="col-sm-12">
            <h2>Sorry! Failed To Process Your Order.</h2>
            <p>Please try again.</p>
        </div>
      </div>
    </div>
  </div>
@else

  <div class="main-container col2-right-layout">
    <div class="main container">
      <div class="row">                
        <div id="content" class="col-sm-12">
          <div class="col-main">
            <div class="my-account">
              <br>
              <br>
              <div class="container">
                <h2 style="color:green;font-weight:bold;">Thank you for your order.</h2>
                <p>We have sent you a copy on your email address. Our customer representative will contact you soon to verify your order.</p>
              </div>

              <div style="clear:both;"></div>
              <br>   

              <div class="container">
                <h2>Order Details</h2>
              </div>

              <div class="container">
                <br>
                <div class="col-md-4" style="font-weight:normal;">
                  <label class="col-md-12" style="font-weight: normal;">Order Reference No. : <span style="color:green;"><b>{{ $OrderNo }}</b></span></label>
                </div>    
                <div class="col-md-4" style="font-weight:normal;">
                  <label class="col-md-12" style="font-weight: normal;">Order Date/Time : <span><b>{{ $OrderDateTime }}</b></span></label>
                </div>    
              </div>

              <div style="clear:both;"></div>
              <br>   

              <div class="container">
                <h2>Customer Details</h2>
              </div>
              <div class="container">
                <br>
                <div class="col-md-8" style="font-weight:normal;">
                  <label class="col-md-12" style="font-weight: normal;">Customer Name : <span>{{ $CustomerName }}</span></label>
                </div>    
              </div>
              <div class="container">
                <div class="col-md-12" style="font-weight:normal;">
                  <label class="col-md-12" style="font-weight: normal;">Mobile No. : <span>{{ $MobileNo }}</span></label>
                </div>    
              </div>
              <div class="container">
                <div class="col-md-12" style="font-weight:normal;">
                  <label class="col-md-12" style="font-weight: normal;">Email Address : <span>{{ $EmailAddress }}</span></label>
                </div>    
              </div>
              <div class="container">
                <div class="col-md-12" style="font-weight:normal;">
                  <label class="col-md-12" style="font-weight: normal;">Address. : <span>{{ $Address.', '.$City.', '.$StateProvince.', '.$ZipCode.' '.$Country }}</span></label>
                </div>    
              </div>

              <div style="clear:both;"></div>
              <br>   

              <div class="container">
                <h2>Order Summary</h2>
              </div>

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
                          @if(isset($OrderItemInfo))
                            <tbody class="dropdown-product-list">

                              @foreach ($OrderItemInfo as $key)

                                @php($ProductID = $key->ProductID)
                                @php($ProductName = $key->ProductName)
                                @php($ProductCode = $key->ProductCode)
                                @php($Category = $key->Category)
                                @php($Brand = $key->Brand)
                                @php($Qty = $key->Qty)
                                @php($Price = $key->Price)
                                @php($SubTotal = $key->SubTotal)
                                @php($Measurement = $key->Measurement)

                                @php($ImagePath = 'public/img/products/'.$ProductID.'/'.$ProductID.'-1-'.config('app.Thumbnail').'.jpg')
                                @if (!File::exists($ImagePath))
                                  @php($ImagePath = 'public/img/products/product-no-image-'.config('app.Thumbnail').'.jpg')
                                @endif

                                <tr class="cart_item first_item address_0 odd dd-product-group">
                                   <td class="cart_product">
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
                                      <span class="price">{{ 'Php '.number_format($Price,2). '/'. $Measurement }}</span>
                                   </td>
                                   <td class="cart_quantity text-center">
                                      <span class="price">{{ number_format($Qty) }}</span>
                                   </td>
                                   <td class="subtotal" data-title="SUBTOTAL"  style="text-align: right;">
                                        Php {{ number_format($SubTotal,2) }}
                                   </td>
                                </tr>
                              @endforeach
                              <tr class="cart_item first_item address_0 odd dd-product-group">
                                 <td colspan="4" class="cart_quantity" style="text-align: right;">Total Amount Due</td>
                                 <td class="subtotal" data-title="SUBTOTAL"  style="text-align: right;">
                                      Php {{ number_format($GrossAmount,2) }}
                                 </td>
                              </tr>
                              <tr class="cart_item first_item address_0 odd dd-product-group">
                                 <td colspan="4" class="cart_quantity" style="text-align: right;">Shipping Charges</td>
                                 <td class="subtotal" data-title="SUBTOTAL"  style="text-align: right;">
                                      Php {{ number_format($ShippingCharges,2) }}
                                 </td>
                              </tr>
                              <tr class="cart_item first_item address_0 odd dd-product-group">
                                 <td colspan="4" class="cart_quantity" style="text-align: right;">Net Amount Due</td>
                                 <td class="subtotal" data-title="SUBTOTAL"  style="text-align: right;">
                                      Php {{ number_format($TotalAmountDue,2) }}
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




            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endif

@endsection