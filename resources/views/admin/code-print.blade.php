<!DOCTYPE html>
<html lang="en" dir="ltr" xmlns:fb="http://ogp.me/ns/fb#">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <title>Success Formula International - Print Codes</title>
    <link rel="stylesheet" href="{{ asset(config('app.src_name') . 'admin/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset(config('app.src_name') . 'admin/dist/css/AdminLTE.min.css') }}">
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

    @php($BatchNo = "")
    @php($DateTimeGenerated = "")

    @php($Center = "")
    @php($EmailAddress = "")
    @php($TelNo = "")
    @php($MobileNo = "")

    @php($Type = "")
    @php($TotalGrossAmount = 0)
    @php($TotalDiscount = 0)
    @php($TotalAmountDue = 0)

    @if(isset($CodeGenerationBatchInfo))
      @php($BatchNo = $CodeGenerationBatchInfo->BatchNo)
      @php($DateTimeGenerated = $CodeGenerationBatchInfo->DateTimeGenerated)

      @php($Center = $CodeGenerationBatchInfo->CenterNo." - ".$CodeGenerationBatchInfo->Center)
      @php($EmailAddress = $CodeGenerationBatchInfo->EmailAddress)
      @php($TelNo = $CodeGenerationBatchInfo->TelNo)
      @php($MobileNo = $CodeGenerationBatchInfo->MobileNo)

      @php($Type = $CodeGenerationBatchInfo->IsFreeCode)
      @php($TotalGrossAmount = $CodeGenerationBatchInfo->TotalGrossAmount)
      @php($TotalDiscount = $CodeGenerationBatchInfo->TotalDiscount)
      @php($TotalAmountDue = $CodeGenerationBatchInfo->TotalAmountDue)
    @endif

    <section class="content" style="margin-top:-30px;">
      <div class="row invoice-info" >
        <div class="col-sm-2 invoice-col" style="margin-top:13px;">
          <img src='{{ asset(config('app.src_name') . 'img/logo-h.png')}}' alt=""style="height:40px;width:130px;" />
        </div>
        <div class="col-sm-8 " style="margin-top:30px;">
          <h4 style="margin-left:-70px;">CODE BATCH INFORMATION</h4>
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
                <b>Contact No.:</b>PLDT {{ config('app.COMPANY_TEL') }} / {{ config('app.COMPANY_MOBILE1') }} / {{ config('app.COMPANY_MOBILE2') }}<br>
                <b>Email:</b> {{ config('app.COMPANY_EMAIL') }}
               </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col" style='font-weight:normal;font-size:10px;width: 35%;'>
              <br>
              <b>Center :</b> {{ $Center }} <br>
              <b>Date:</b>{{ $DateTimeGenerated }} <br>
              <b>Batch No:</b>{{ $BatchNo }} <br>

              <address style='font-weight:normal;font-size:10px;'>
                <b>Tel. No: </b> {{ $TelNo }} <br>
                <b>Mobile No: </b> {{ $MobileNo }} <br>
                <b>E-mail: </b> {{ $EmailAddress }}  <br>
              </address>
            </div>

          </div>
          
          <br>
          
          <div class="row" style="margin-top:-20px;">
            <div class="col-xs-12 table-responsive">
              <div class="col-xs-12 table-responsive">
                <table class="table table-striped" style='font-size:11px;'>
                  <thead>
                    <tr>
                      <th style="text-align: center;width: 5%">No.</th>
                      <th style="text-align: center;width: 25%">Package</th>
                      <th style="text-align: center;width: 30%">Code</th>
                      <th style="text-align: center;width: 20%">Type</th>
                      <th style="text-align: center;width: 20%">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php($x=1)
                    @foreach ($CodeGenerationByBatch as $code)
                      <tr>
                        <td  style="text-align: center;width: 5%">{{ $x }}</td>
                        <td  style="width: 25%">{{ $code->Package }}</td>
                        <td  style="width: 30%">{{ $code->Code }}</td>
                        <td  style="width: 20%">{{ ($code->IsFreeCode == 1 ? "Free" : "Paid") }}</td>
                        <td  style="width: 20%">{{ $code->Status }}</td>
                      </tr>
                      @php($x++)
                    @endforeach
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
                      <td style="text-align:right;">Php {{number_format($TotalGrossAmount,2)}}</td>
                    </tr>
                    <tr style="font-weight:normal;font-size:10px;">
                      <th  style="text-align:right;">Total Discount:</th>
                      <td style="text-align:right;">Php {{number_format($TotalDiscount,2)}}</td>
                    </tr>
                    <tr style="font-weight:normal;font-size:10px;">
                      <th  style="text-align:right;">Total Amount Due:</th>
                      <td style="text-align:right;">Php {{number_format($TotalAmountDue,2)}}</td>
                    </tr>
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


