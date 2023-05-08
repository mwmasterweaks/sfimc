<!DOCTYPE html>
<html lang="en" dir="ltr" xmlns:fb="http://ogp.me/ns/fb#">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <title>Success Formula International - Order Print</title>
    <link rel="stylesheet" href="{{URL::to('admin/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{URL::to('admin/dist/css/AdminLTE.min.css') }}">
    <link rel="shortcut icon" href="{{URL::to('img/favicon.png')}}">
    <style type="text/css">
      .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 2px;
      }

      @media screen {
        .page-break { height:10px; background:url(page-break.gif) 0 center repeat-x; border-top:1px dotted #999; margin-bottom:13px; }
      }

      @media print {
        .page-break { height:0; page-break-before:always; margin:0; border-top:none; }
      }

      .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 2px;
      }
    </style>
  </head>

  <body onload="window.print();">

    @php($OrderNo = "")
    @php($OrderDateTime = "")
    @php($CustomerType = "")

    @php($Center = "")
    @php($CenterTelNo = "")
    @php($CenterMobileNo = "")
    @php($CenterEmailAddress = "")

    @php($CustomerName = "")
    @php($EmailAddress = "")
    @php($MobileNo = "")

    @php($Remarks = "")
    @php($Status = "")
    @php($ApprovedBy = "")

    @php($GrossTotal = 0)
    @php($ShippingCharges = 0)
    @php($TotalDiscountPercent = 0)
    @php($TotalDiscountAmount = 0)
    @php($TotalAmountDue = 0)
    @php($TotalVoucherPayment = 0)
    @php($TotalCashPayment = 0)
    @php($AmountChange = 0)
    @php($TotalRebatableValue = 0)

    @php($ModeOfPayment = "Cash")

    @if(isset($OrderInfo))

      @php($OrderNo = $OrderInfo->OrderNo)
      @php($OrderDateTime = $OrderInfo->OrderDateTime)
      @php($CustomerType = $OrderInfo->CustomerType)

      @php($Center = $OrderInfo->CenterNo." - ".$OrderInfo->Center)
      @php($CenterTelNo = $OrderInfo->CenterTelNo)
      @php($CenterMobileNo = $OrderInfo->CenterMobileNo)
      @php($CenterEmailAddress = $OrderInfo->CenterEmailAddress)

      @php($CustomerName = $OrderInfo->CustomerName)
      @php($EmailAddress = $OrderInfo->EmailAddress)
      @php($MobileNo = $OrderInfo->MobileNo)

      @php($ModeOfPayment = $OrderInfo->ModeOfPayment)

      @php($Remarks = $OrderInfo->Remarks)
      @php($Status = $OrderInfo->Status)

      @php($ApprovedBy = $OrderInfo->ApprovedBy)

      @php($GrossTotal = $OrderInfo->GrossTotal)
      @php($ShippingCharges = $OrderInfo->ShippingCharges)
      @php($GrossTotal = $OrderInfo->GrossTotal)
      @php($TotalDiscountAmount = $OrderInfo->TotalDiscountAmount)
      @php($TotalAmountDue = $OrderInfo->TotalAmountDue)
      @php($TotalVoucherPayment = $OrderInfo->TotalVoucherPayment)
      @php($TotalCashPayment = $OrderInfo->TotalCashPayment)
      @php($AmountChange = $OrderInfo->AmountChange)
      @php($TotalRebatableValue = $OrderInfo->TotalRebatableValue)

    @endif

    <section class="content" style="margin-top:-30px;">
      <div class="row invoice-info" >
        <div class="col-sm-2 invoice-col" style="margin-top:13px;">
          <img src='{{URL::to('img/logo-h.png')}}' alt=""style="height:40px;width:130px;" />
        </div>
        <div class="col-sm-8 " style="margin-top:30px;">
          <h4 style="margin-left:-70px;">ORDER</h4>
        </div>
      </div>

      <div class="box">
        <section class="invoice">
          <!-- info row -->
          <div class="row invoice-info" >
            <div class="col-sm-4 invoice-col" style='font-weight:normal;font-size:10px;width: 30%;'>
              <address >
                <br>
                <b>{{ config('app.COMPANY_NAME') }}</b>
                <br>
                {{ config('app.COMPANY_ADDRESS1') }}
                <br>
                {{ config('app.COMPANY_ADDRESS2') }},
                <br>
                {{ config('app.COMPANY_ADDRESS3') }}
                <br>
                <b>Contact No.:</b>{{ config('app.COMPANY_MOBILE1') }}<br>
                <b>Email:</b> {{ config('app.COMPANY_EMAIL') }}
               </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col" style='font-weight:normal;font-size:10px;width: 35%;'>
              <br>
              <b>Center :</b> {{ $Center }} <br>
              <address style='font-weight:normal;font-size:10px;'>
                <b>Tel. No: </b> {{ $CenterTelNo }} <br>
                <b>Mobile No: </b> {{ $CenterMobileNo }} <br>
                <b>E-mail: </b> {{ $CenterEmailAddress }}
              </address>
            </div>
            <div class="col-sm-4 invoice-col" style='font-weight:normal;font-size:10px;width: 35%;'>
              <br>
              <b>Order No:</b>{{ $OrderNo }} <br>
              <b>Order Date/Time:</b>{{ $OrderDateTime }}<br>
              <b>Customer :</b>{{ $CustomerName }}<br>
            </div>

          </div>
          
          <br>
          
          <div class="row" style="margin-top:-20px;">
            <div class="col-xs-12 table-responsive">
              <div class="col-xs-12 table-responsive">
                <table class="table table-striped" style='font-size:11px;'>
                  <thead>
                    <tr>
                      <th style="text-align: center;width: 55%;">Product</th>
                      <th style="text-align: right;width: 10%;">Qty</th>
                      <th style="text-align: right;width: 15%;">Price</th>
                      <th style="text-align: right;width: 20%;">SubTotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($OrderItemList as $item)
                      <tr>
                        <td  style="width: 55%;">{{ $item->ProductCode.'-'.$item->ProductName }}</td>
                        <td  style="width: 10%; text-align: right;">{{ number_format($item->Qty,0).' '.$item->Measurement }}</td>
                        <td  style="width: 15%; text-align: right;">{{ number_format($item->Price,2) }}</td>
                        <td  style="width: 20%; text-align: right;">{{ number_format($item->SubTotal,2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            <div class="col-xs-8">
              <div class="table-responsive">
                <table class="table pull-right">
                  <tbody>            
                    <tr style="font-weight:normal;font-size:10px;">
                      <th style="width: 100px;">Remarks:</th>
                      <td>{{ $Remarks }}</td>
                    </tr>
                    <tr style="font-weight:normal;font-size:10px;">
                      <th style="width: 100px;">Approved By:</th>
                      <td>{{ $ApprovedBy }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="col-xs-4" style="float:right;">
              <div class="table-responsive">
                <table class="table pull-right">
                  <tbody>            
                    <tr style="font-weight:normal;font-size:10px;">
                      <th  style="text-align:right;">Total Gross Amount:</th>
                      <td style="text-align:right;">Php {{number_format($GrossTotal,2)}}</td>
                    </tr>
                    <tr style="font-weight:normal;font-size:10px;">
                      <th  style="text-align:right;">Shipping Charges:</th>
                      <td style="text-align:right;">Php {{number_format($ShippingCharges,2)}}</td>
                    </tr>
                    <tr style="font-weight:normal;font-size:10px;">
                      <th  style="text-align:right;">Total Discount:</th>
                      <td style="text-align:right;">Php {{number_format($TotalDiscountAmount,2)}}</td>
                    </tr>
                    <tr style="font-weight:normal;font-size:10px;">
                      <th  style="text-align:right;">Total Amount Due:</th>
                      <td style="text-align:right;">Php {{number_format($TotalAmountDue,2)}}</td>
                    </tr>
                    @if($ModeOfPayment == "Cash")
                    <tr style="font-weight:normal;font-size:10px;">
                      <th  style="text-align:right;">Total Voucher Payment:</th>
                      <td style="text-align:right;">Php {{number_format($TotalVoucherPayment,2)}}</td>
                    </tr>
                    <tr style="font-weight:normal;font-size:10px;">
                      <th  style="text-align:right;">Total Cash Payment:</th>
                      <td style="text-align:right;">Php {{number_format($TotalCashPayment,2)}}</td>
                    </tr>
                    <tr style="font-weight:normal;font-size:10px;">
                      <th  style="text-align:right;">Amount Change:</th>
                      <td style="text-align:right;">Php {{number_format($AmountChange,2)}}</td>
                    </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>

          </div>

        </section>
      </div>
    </section>

  </body>
</html>


