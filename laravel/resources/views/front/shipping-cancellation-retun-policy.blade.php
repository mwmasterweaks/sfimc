@extends('layout.frontweb')
@section('content')

  <!--Page Title-->
  <section class="bredcrumb">
    <div class="bg-image text-center" style="background-image: url('public/img/products-banner.jpg');">
      <h1>Shipping, Cancellation and Return Policy</h1>
    </div>
    <div class="">
      <ul class= "middle">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a class="inner" href="#">Shipping, Cancellation and Return Policy</a></li>
      </ul>
    </div>
  </section>
  <!--Page Title Ends-->

    <section class="about">
        <div class="container">
            <div class="item-list">
                <div class="row">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="sec-title">
                            <h2 class="left">Shipping, Cancellation & Return Policy</h2>
                        </div>
                        <br>
                        <ul class="s-list list-unstyled mb-20">
                            <li>
                                <span class="fa fa-check"></span>
                                SHIPPING
                                <br><br>
                                <p>
                                    We ship domestically via J&T.  Since we ship through these couriers, our website will allow you only these shipping options.
                                </p>
                                <br>
                                <p>
                                    Most orders are shipped within 1-3 business days from your order. Shipping is done from Monday to Friday from 8 am to 5 pm (Philippine Time). We make every attempt to deliver your products as fast as possible but we cannot exact guarantee delivery time. To ensure that you receive your order when you need it, please plan accordingly. During holidays, please place your order at least a week ahead prior to expected delivery date to ensure timely delivery.
                                </p>
                                <br>
                                <p>
                                    1. By placing an Order via {{ config('app.COMPANY_NAME') }} website, the shipper acknowledges & understands that {{ config('app.COMPANY_NAME') }} products are shipped via third party couriers over which {{ config('app.COMPANY_NAME') }} has no control.
                                    <br>
                                    2. BY placing an Order via {{ config('app.COMPANY_NAME') }} website, the shipper agrees to hold {{ config('app.COMPANY_NAME') }} harmless & free from any and all liability related to shipment status or delay.
                                    <br>
                                    If you have other concerns regarding our courier services, please contact us at sales@sfimc.org or CALL {{ config('app.COMPANY_MOBILE1').'/'.config('app.COMPANY_MOBILE2') }} and ask for Marketing personnel.
                                </p>
                                <br>
                            </li>
                            <li>
                                <span class="fa fa-check"></span>
                                SHIPPING RATES
                                <br><br>
                                <p>
                                    We do not charge a flat rate for shipping. Instead, we charge you that which the shipping company or courier charges us. The charge is based on the location, weight, and dimension.  
                                </p>
                                <br>
                            </li>
                            <li>
                                <span class="fa fa-check"></span>
                                EMAIL CONFIRMATION
                                <br><br>
                                <p>
                                    Once your Order has been confirmed, you will receive an email informing you of your package being shipped including tracking number and courier website where you can follow your order. IF you have ANY further concerns and/or inquiries, please contact us at sales@sfimc.org or CALL {{ config('app.COMPANY_MOBILE1').'/'.config('app.COMPANY_MOBILE2') }} and ask for Marketing personnel.
                                </p>
                                <br>
                            </li>
                            <li>
                                <span class="fa fa-check"></span>
                                ORDER CANCELLATION
                                <br><br>
                                <p>
                                    You have the Right to cancel an Order for the purchase of goods for any reason. This cancellation period begins on the DAY you ORDER the goods and expires 2 days after the date the goods are delivered to you (the "Cancellation Period"). This cancellation right does not apply to sealed goods that were unsealed (opened) after delivery and/or partially consumed products.
                                </p>
                                <br>
                                <p>
                                    TO exercise your cancellation rights you must send us notice of your decision in writing before the end of the Cancellation Period. There is no cancellation form but we need in your notice the Order number and product(s) and states that the Order is cancelled. Send your notice by email at sales@sfimc.org.
                                </p>
                                <br>
                                <p>
                                    You must RETURN the goods to us in a fully saleable condition at your own cost and risk not later than 5 days from the date on which you informed us of your decision to cancel your order. Please review our RETURNS Policy for instructions on how to return goods to us. 
                                </p>
                                <br>
                            </li>
                            <li>
                                <span class="fa fa-check"></span>
                                RETURN POLICY
                                <br><br>
                                <p>
                                    BEFORE accepting shipment of ANY product, kindly ensure that the product's packaging is not damaged or tampered. If You observe that the package is damaged or tampered, we request You to refuse to accept delivery and inform us within 24 hours. The return process of the product may be restricted by {{ config('app.COMPANY_NAME') }}, depending on the nature and category of the product. 
                                </p>
                                <br>
                                <p>
                                    Please call our customer care at {{ config('app.COMPANY_MOBILE1').'/'.config('app.COMPANY_MOBILE2') }} or email us at sales@sfimc.org mentioning your Order Number and the products involved. 
                                </p>
                                <br>
                                <p>
                                    We will personally ensure that a replacement is issued to you at no additional cost provided that notice to us via sales@sfimc.org is sent within 24 hours upon your receipt of the goods. Please make sure that the original product and packing is intact when you send us the product back.
                                </p>
                                <br>
                                <p>
                                    Our return address is:
                                    <br>
                                    Door 3&4, EC Business Center, C.M. Recto Ave., Davao City, 8000 Philppines
                                </p>
                                <br>
                            </li>
                        </ul>
                      </div>
                </div>
            </div>
        </div>
    </section>


@endsection



