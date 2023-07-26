@extends('layout.frontweb')
@section('content')
  
    @php($CustomerName = "")
    @php($MobileNo = "")
    @php($EmailAddress = "")

    @php($MobileNo = "")
    @php($EmailAddress = "")

    @php($Address = "")
    @php($CityID = "")
    @php($StateProvince = "")
    @php($ZipCode = "")
    @php($CountryID = "")

    @if(Session("MEMBER_LOGGED_IN"))

      @php($CustomerName = Session("MEMBER_FIRSTNAME").' '.Session("MEMBER_MIDDLENAME").' '.Session("MEMBER_LASTNAME"))
      @php($MobileNo = Session("MEMBER_MOBILE_NO"))
      @php($EmailAddress = Session("MEMBER_EMAIL_ADDRESS"))

      @php($Address = Session("MEMBER_ADDRESS"))
      @php($CityID = Session("MEMBER_CITYID"))
      @php($StateProvince = Session("MEMBER_STATEPROVINCE"))
      @php($ZipCode = Session("MEMBER_ZIPCODE"))
      @php($CountryID = Session("MEMBER_COUNTRYID"))

    @endif

    <!--Page Title-->
    <section class="bredcrumb">
      <div class="bg-image text-center" style="background-image: url('img/products-banner.jpg');">
        <h1>Checkout</h1>
      </div>
      <div class="">
        <ul class= "middle">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a class="inner" href="#">Checkout</a></li>
        </ul>
      </div>
    </section>
    <!--Page Title Ends-->

    <!--team section-->
    <section class="our-gallery">
      <div class="container">
        <div class="row">

          <div class="col-sm-12">
            <div class="col-md-8 col-xs-12">
                     
              <div class="col-xs-12 mt-30" style="padding-right: 0px;padding-left: 0px;">
                <h6 class="gold-header">Customer Information</h6>
                <br>
                <div class="row clearfix">
                  <div class="col-md-12 col-xs-12">
                      <label class="col-md-12" style="font-weight: normal;">Customer Name <span style="color:red;">*</span></label>
                      <div class="col-md-12">
                        <input id="CustomerName" type="text" value="{{ $CustomerName }}" placeholder="Customer Name*" style="width: 100%;" required="required">
                      </div>                    
                  </div>
                  <div style="clear:both;"></div>
                  <br> 
                  <div class="col-md-6">  
                      <label class="col-md-12" style="font-weight: normal;">Mobile No. <span style="color:red;">*</span></label>
                      <div class="col-md-12">
                        <input id="MobileNo" type="text" value="{{ $MobileNo }}" placeholder="Mobile No.*" style="width: 100%;" required="required">
                      </div>                    
                  </div>
                  <div class="col-md-6">  
                      <label class="col-md-12" style="font-weight: normal;">Email Address <span style="color:red;">*</span></label>
                      <div class="col-md-12">
                        <input id="EmailAddress" type="text" value="{{ $EmailAddress }}" placeholder="Email Address*" style="width: 100%;" required="required">
                      </div>                    
                  </div>

                </div>
              </div>

              <div class="col-xs-12 mt-30" style="padding-right: 0px;padding-left: 0px;">
                <h6 class="gold-header">Shipping/Billing Information</h6>
                <br>
                <div class="row clearfix">
                  <div class="col-md-12">  
                      <label class="col-md-12" style="font-weight: normal;">Address <span style="color:red;">*</span></label>
                      <div class="col-md-12">
                        <input id="Address" type="text" value="{{ $Address }}" placeholder="Address*" style="width: 100%;" required="required">
                      </div>                    
                  </div>                  
                </div>                  
                <div style="clear:both;"></div>
                <br> 
                <div class="row clearfix">
                  <div class="col-md-12">  
                      <label class="col-md-12" style="font-weight: normal;">City <span style="color:red;">*</span></label>
                      <div class="col-md-12">
                        <select id="City" style="width: 100%;">
                          <option value="">Please Select City</option>
                          @foreach($CountryCityList as $ckey)
                            <option value="{{ $ckey->CityID }}"
                                    data-cityid="{{$ckey->CityID}}"
                                    data-cityprovince="{{$ckey->Province}}"
                                    data-cityzipcode="{{$ckey->ZipCode}}"
                                     {{ $ckey->CityID == $CityID ? "selected" : "" }}
                              >{{ $ckey->City }}</option>
                          @endforeach
                        </select>
                      </div>                    
                  </div>
                </div>
                <div style="clear:both;"></div>
                <br> 
                <div class="row clearfix">
                  <div class="col-md-8">  
                      <label class="col-md-12" style="font-weight: normal;">State/Province </label>
                      <div class="col-md-12">
                        <input id="StateProvince" type="text" value="{{ $StateProvince }}" placeholder="State/Province" style="width: 100%;" required="required">
                      </div>                    
                  </div>
                  <div class="col-md-4">  
                      <label class="col-md-12" style="font-weight: normal;">Zip Code </label>
                      <div class="col-md-12">
                        <input id="ZipCode" type="text" value="{{ $ZipCode }}" placeholder="Zip Code" style="width: 100%;" required="required">
                      </div>                    
                  </div>
                  <div style="clear:both;"></div>
                  <br> 
                  <div class="col-md-12">  
                      <label class="col-md-12" style="font-weight: normal;">Country <span style="color:red;">*</span></label>
                      <div class="col-md-12">
                        <select id="Country" style="width: 100%;">
                          <option value="174" {{ $CountryID == 174 ? "selected" : "" }}>Philippines</option>
                        </select>
                      </div>                    
                  </div>
                </div>
              
                <div class="row clearfix">
                  <div class="col-sm-12">

                    <div class="col-md-12 col-xs-12">
                      <div class="table_block table-responsive mb-40" id="order-detail-content">
                        <table class="table table-bordered" id="cart_summary">
                          <thead>
                            <tr>
                               <th colspan="4" class="cart_product first_item">Order Summary</th>
                            </tr>
                            <tr>
                               <th class="cart_product first_item" style="background: none repeat scroll 0 0 #b5b8b4; border-color: #b5b8b4;">Product name</th>
                               <th class="cart_unit item"  style="background: none repeat scroll 0 0 #b5b8b4; border-color: #b5b8b4;">Price</th>
                               <th class="cart_quantity item"  style="background: none repeat scroll 0 0 #b5b8b4; border-color: #b5b8b4; width: 100px;">QTY</th>
                               <th class="cart_delete last_item"  style="background: none repeat scroll 0 0 #b5b8b4; border-color: #b5b8b4;">SUBTOTAL</th>
                            </tr>
                          </thead>
                          @if(count($Cart) <= 0)
                            <tbody class="dropdown-product-list">
                              <tr class="cart_item first_item address_0 odd dd-product-group">
                                <td colspan="4">Your cart is empty</td>
                              </tr>
                          @else
                            <tbody class="dropdown-product-list">

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

                                  @php($ImagePath = 'img/products/'.$ProductID.'/'.$ProductID.'-1-'.config('app.Thumbnail').'.jpg')
                                  @if (!File::exists($ImagePath))
                                    @php($ImagePath = 'img/products/product-no-image-'.config('app.Thumbnail').'.jpg')
                                  @endif

                                  <tr class="cart_item first_item address_0 odd dd-product-group">
                                     <td data-title="Product Name" class="cart_description">
                                        <p class="product-name">
                                           <a href="{{ route('product-detail').'?ProductName='.$ProductName}}">{{ $ProductName }}</a>
                                        </p>
                                     </td>
                                     <td data-title="Price" class="cart_unit" style="text-align: right;">
                                        <span class="price" style="font-weight: normal;">{{ (!Session('MEMBER_LOGGED_IN') ? 'Php '.number_format($RetailPrice,2) : 'Php '.number_format($DistributorPrice,2)). '/'. $Measurement }}</span>
                                     </td>
                                     <td class="cart_quantity text-center"  data-title="Qty" style="text-align: right;">
                                        <span class="price" style="font-weight: normal;">{{ number_format($Qty) }}</span>
                                     </td>
                                     <td class="subtotal" data-title="Sub Total" style="text-align: right; font-weight: normal; padding: 10px;">
                                          Php {{number_format((!Session('MEMBER_LOGGED_IN') ? $RetailPrice : $DistributorPrice) * $Qty,2)}}
                                     </td>
                                  </tr>
                                @endforeach
                              <tr class="cart_item first_item address_0 odd dd-product-group">
                                 <td colspan="3" class="cart_quantity" style="text-align: right;">Total Amount Due</td>
                                 <td class="subtotal" style="text-align: right; padding: 10px;">
                                      Php {{number_format($CartTotalAmountDue,2)}}
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
            <div class="col-md-4 col-xs-12 pull-right">

              <div class="col-xs-12 mt-30" style="padding-right: 0px;padding-left: 0px;">
                <h6 class="gold-header">Payment Information</h6>
                <br>
                <div class="row clearfix">
                    <label class="col-md-12" style="font-weight: normal;">Mode Of Payment </label>
                    <div class="col-md-12">
                      <input id="ModeOfPayment" type="text" value="COD" placeholder="Mode Of Payment" style="width: 100%;" readonly>
                    </div>    
                </div>
                <div style="clear:both;"></div>
                <br> 
                <div class="row clearfix">
                    <label class="col-md-12" style="font-weight: normal;">Shipper </label>
                    <div class="col-md-12">
                      <input id="Shipper" type="text" value="J&T" placeholder="Shipper" style="width: 100%;" readonly>
                    </div>    
                </div>
                <div style="clear:both;"></div>
                <br> 
                <div class="row clearfix">
                    <label class="col-md-12" style="font-weight: normal;">Instructions </label>
                    <div class="col-md-12">
                      <input id="Remarks" type="text" value="" placeholder="Instructions" style="width: 100%;">
                    </div>    
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12 cart-detail">
                  <h6 class="gold-header">TOTAL ORDER DETAILS</h6>
                  <p class="info">
                    <span class="pull-left col-xs-6">Total Amount Due:</span>
                    <span class="pull-right col-xs-6 text-right">Php <span id="spnTotalAmountDue">{{ number_format($CartTotalAmountDue,2) }}</span></span>
                  </p>
                  <p class="info">
                    <span class="pull-left col-xs-6">Shipping Charges:</span>
                    <span class="pull-right col-xs-6 text-right">Php <span id="spnShippingCharges">{{ number_format($JATShippingCharges,2) }}</span></span>
                  </p>
                  <p class="info">
                    <span class="pull-left col-xs-6">Net Amount Due:</span>
                    <span class="pull-right col-xs-6 text-right">Php <span id="spnNetAmountDue">{{ number_format($CartTotalAmountDue + $JATShippingCharges,2) }}</span></span>
                  </p>
                  <div style="clear:both;"></div>
                  <br> 
                  <div class="form-group">
                     <a href="#" class="thm-btn" style="width: 100%; text-align: center; background: #ed6663; border: 2px solid #ed6663; color: #fff;"  onclick="doProceedCheckout()">Checkout</a>
                  </div>


                </div>
              </div>

            </div>
          </div>

        </div>          
      </div>
    </section>

    <script type="text/javascript">
        
      $("#City").change(function(){

        if($("#City").find('option:selected').data('cityprovince') != undefined){
          $("#StateProvince").val($("#City").find('option:selected').data('cityprovince'));
        }

        if($("#City").find('option:selected').data('cityzipcode') != undefined){
          $("#ZipCode").val($("#City").find('option:selected').data('cityzipcode'));
        }
    
        doCheckShippingCharges();

      });      

      function doCheckShippingCharges(){

        if($("#City").val() != ''){

            $.ajax({
              type: "post",
              data: {
                _token: '{{ $Token }}',
                City: $("#City").val(),
                TotalWeightKG: {{ $TotalWeightKG }}
              },
              url: "{{ route('do-check-shipping-charges') }}",
              dataType: "json",
              success: function(data){

                if(data.Response =='Success'){

                  $("#spnShippingCharges").text(FormatDecimal(data.ShippingCharges,2));

                  TotalAmountDue = 0;
                  if($('#spnTotalAmountDue').length){
                    if($("#spnTotalAmountDue").text() != ""){
                          var strTotalAmountDue = $("#spnTotalAmountDue").text();
                          TotalAmountDue = parseFloat(strTotalAmountDue.replace(",",""));
                      }
                  }
                            
                  var NetAmountDue = TotalAmountDue + data.ShippingCharges;
                  $('#spnNetAmountDue').text(FormatDecimal(NetAmountDue,2)); 

                }else{
                  showJSModalMessageJS("Shipping Charges",data.ResponseMessage,"OK");
                }

              },
              error: function(data){
                console.log(data.responseText);
              },
              beforeSend:function(vData){
              }
            });

        }
      }

      function doProceedCheckout(){

          if($('#CustomerName').val() == "") {
            showJSMessage("Customer Name","Please enter customer complete name.","OK");
          }else if($('#MobileNo').val() == "") {
            showJSMessage("Mobile No.","Please enter customer active mobile number.","OK");

          }else if($('#Address').val() == "") {
            showJSMessage("Address","Please enter shipping address.","OK");
          }else if($('#City').val() == "") {
            showJSMessage("City","Please select shipping city address.","OK");
          }else if($('#StateProvince').val() == "") {
            showJSMessage("State/Provincial Address","Please enter state/provincial address.","OK");
          }else if($('#Country').val() == "") {
            showJSMessage("Country","Please select country address.","OK");

          }else{

            $.ajax({
              type: "post",
              data: {
                _token: '{{ $Token }}',

                CustomerName: $("#CustomerName").val(),
                MobileNo: $("#MobileNo").val(),
                EmailAddress: $("#EmailAddress").val(),

                Address: $("#Address").val(),
                City: $("#City").val(),
                StateProvince: $("#StateProvince").val(),
                ZipCode: $("#ZipCode").val(),
                Country: $("#Country").val(),

                ModeOfPayment: $("#ModeOfPayment").val(),
                TotalWeightKG: {{ $TotalWeightKG }},

                Remarks: $("#Remarks").val()
              },
              url: "{{ route('do-checkout-order') }}",
              dataType: "json",
              success: function(data){

                buttonOneClick("btnProceed", "Proceed", false);
                if(data.Response =='Success'){
                  window.location='{{URL::route('checkout-response')}}?RefNo=' + data.OrderNo;
                }else{
                  showJSModalMessageJS("Proceed Checkout",data.ResponseMessage,"OK");
                }

              },
              error: function(data){
                buttonOneClick("btnProceed", "Proceed", false);
                console.log(data.responseText);
              },
              beforeSend:function(vData){
                buttonOneClick("btnProceed", "", true);
              }
            });
          }
      }

    </script>

@endsection



