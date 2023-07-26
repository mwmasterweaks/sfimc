  <div style='width:90%;border-bottom:2px dashed #140297;margin-top:10px;font-size:15px;padding:25px;font-family:Sans,helvetica,arial,sans-serif;font-size:11px;'>

    <h3>Dear {{ $CustomerName }},</h3>


    <p>
      Thank you for shopping at {{ config("app.COMPANY_NAME") }}! Your <span style='color:#301cc4'><b>Order {{ $ReferenceNo }} </b></span> has been received and it is going through a verification process. Our customer representative will contact you soon to verify your order. 
      <br><br>
      For order concerns, please call our customer service at {{ config('app.COMPANY_MOBILE1') }}.      
    </p>
    <br>
    <div style='width:90%;border-top:1px solid gray;background:#f2f2f2;padding:5px 0px 20px 10px;padding-top:5px;'>
      Received by: {{ config("app.COMPANY_NAME") }}
      <br>
      To: {{ $CustomerName }}
      <br>
      Mobile No. : {{ $MobileNo }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email Address : {{ $EmailAddress }}
      <br>
      Address : {{ $ShippingAddress }}
      <br>
    </div>
    <div style='width:90%;border-top:1px dotted #f2f2f2;background:#fff8e6;padding:5px 0px 20px 10px;'>
      <p>
        <b>Order Details</b>
      </p>
    </div>

    <table style='width:91%; border:1px dashed #e9ebee;'>
      <tr>
        <td style='width:60px; margin-right: 5px;'></td>
        <td><b>Product Name</b></td>
        <td style='text-align:right;'><b>Qty</b></td>
        <td style='text-align:right;'><b>Unit Price</b></td>
        <td style='text-align:right;'>
          <span style='color:#f37022'><b>Sub Total</b></span>
        </td>
      </tr>
      @if(count($OrderItem) > 0)
        @foreach ($OrderItem as $oi)
          
          @php($ProductID = $oi->ProductID)
          @php($ProductName = $oi->ProductName)
          @php($Measurement = $oi->Measurement)
          @php($Qty = $oi->Qty)
          @php($Price = $oi->Price)
          @php($SubTotal = $oi->SubTotal)

          @php($ImagePath = 'img/products/'.$ProductID.'/'.$ProductID.'-1-'.config('app.Thumbnail').'.jpg')
          @if (!File::exists($ImagePath))
            @php($ImagePath = 'img/products/product-no-image-'.config('app.Thumbnail').'.jpg')
          @endif

          <tr>
            <td>                  
              <img src="{{URL::to($ImagePath)}}" alt="{{ $ProductName }}" title="{{ $ProductName }}" class="img-thumbnail" style="width: 60px;" />
            </td>
            <td>
              {{ $ProductName }}
            </td>
            <td style='text-align:right;'>
              {{ number_format($Qty) }}
            </td>
            <td style='text-align:right;'>
              Php {{ number_format($Price,2) }} / {{ $Measurement }}
            </td>
            <td style='text-align:right;'>
              Php {{ number_format($SubTotal,2) }}
            </td>
          </tr>

        @endforeach
      @endif

      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style='text-align:right;'><b>Total Amount Due:</b></td>
        <td style='text-align:right;'>
          <span><b>Php {{ number_format($GrossTotal,2) }} </b></span>
        </td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style='text-align:right;'><b>Shipping Charges :</b></td>
        <td style='text-align:right;'>
          <span><b>Php {{ number_format($ShippingCharges,2) }} </b></span>
        </td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style='text-align:right;'><b>Net Amount Due:</b></td>
        <td style='text-align:right;'>
          <span><b>Php {{ number_format($TotalAmountDue,2) }}</b></span>
        </td>
      </tr>
    </table>
    <br>
    <hr>
    <p><b>Information regarding delivery:</b></p>
    <br>
    <p>
        Order cancellation is not allowed once the order has been shipped already. Upon receive of your order, kindly keep your order sheet and original packaging in case you need to return or replace the product.
        </p>
    
  </div>
