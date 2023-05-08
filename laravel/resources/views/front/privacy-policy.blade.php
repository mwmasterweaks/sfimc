@extends('layout.frontweb')
@section('content')

  <!--Page Title-->
  <section class="bredcrumb">
    <div class="bg-image text-center" style="background-image: url('img/products-banner.jpg');">
      <h1>Privacy Policy</h1>
    </div>
    <div class="">
      <ul class= "middle">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a class="inner" href="#">Privacy Policy</a></li>
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
                            <h2 class="left">Privacy Policy</h2>
                        </div>
                        <br>
                        <ul class="s-list list-unstyled mb-20">
                            <li>
                                <span class="fa fa-check"></span>
                                SECTION 1 - WHAT DO WE DO WITH YOUR INFORMATION?
                                <br><br>
                                <p>
                                    When you purchase something from our store, as part of the buying and selling process, we collect the personal information you give us such as your name, address and email address. When you browse our store, we also automatically receive your computer’s internet protocol (IP) address in order to provide us with information that helps us learn about your browser and operating system. Email marketing (if applicable): With your permission, we may send you emails about our store, new products and other updates.
                                </p>
                                <br>
                            </li>
                            <li>
                                <span class="fa fa-check"></span>
                                SECTION 2 - CONSENT
                                <br><br>
                                <p>
                                    How do you get my consent? When you provide us with personal information to complete a transaction, verify your credit card, place an order, arrange for a delivery or return a purchase, we imply that you consent to our collecting it and using it for that specific reason only. If we ask for your personal information for a secondary reason, like marketing, we will either ask you directly for your expressed consent, or provide you with an opportunity to say no. How do I withdraw my consent? If after you opt-in, you change your mind, you may withdraw your consent for us to contact you, for the continued collection, use or disclosure of your information, at anytime, by contacting us at info@sfimc.org 
                                </p>
                                <br>
                            </li>
                            <li>
                                <span class="fa fa-check"></span>
                                SECTION 3 - DISCLOSURE
                                <br><br>
                                <p>
                                    We may disclose your personal information if we are required by law to do so or if you violate our Terms of Service.
                                </p>
                                <br>
                            </li>
                            <li>
                                <span class="fa fa-check"></span>
                                SECTION 4 - THIRD-PARTY SERVICES
                                <br><br>
                                <p>
                                    In general, the third-party providers used by us will only collect, use and disclose your information to the extent necessary to allow them to perform the services they provide to us. However, certain third-party service providers, such as payment gateways and other payment transaction processors, have their own privacy policies in respect to the information we are required to provide to them for your purchase-related transactions. For these providers, we recommend that you read their privacy policies so you can understand the manner in which your personal information will be handled by these providers. In particular, remember that certain providers may be located in or have facilities that are located a different jurisdiction than either you or us. So if you elect to proceed with a transaction that involves the services of a third-party service provider, then your information may become subject to the laws of the jurisdiction(s) in which that service provider or its facilities are located.
                                </p>
                                <br>
                                <p>
                                    Links, when you click on links on our store, they may direct you away from our site. We are not responsible for the privacy practices of other sites and encourage you to read their privacy statements.
                                </p>
                                <br>
                            </li>
                            <li>
                                <span class="fa fa-check"></span>
                                SECTION 5 - SECURITY
                                <br><br>
                                <p>
                                    To protect your personal information, we take reasonable precautions and follow industry best practices to make sure it is not inappropriately lost, misused, accessed, disclosed, altered or destroyed. If you provide us with your credit card information, the information is encrypted using secure socket layer technology (SSL) and stored with a AES-256 encryption. Although no method of transmission over the Internet or electronic storage is 100% secure, we follow all PCI-DSS requirements and implement additional generally accepted industry standards.
                                </p>
                                <br>
                            </li>
                            <li>
                                <span class="fa fa-check"></span>
                                SECTION 6 - COOKIES
                                <br><br>
                                <p>
                                    At {{ config('app.COMPANY_NAME') }} we are committed to safeguarding your personal information, and we are transparent about how we handle your personal information. Accordingly, we developed this Cookies Notice to inform you about our use of cookies across our websites. We want you to understand what cookies are, how {{ config('app.COMPANY_NAME') }} uses cookies and what your choices are.
                                </p>
                                <br>
                                <p>
                                    <b>What are cookies?</b>
                                </p>
                                <br>
                                <p>
                                    A cookie is a small text file, often containing a unique identifier, stored by your web browser when you visit a website. Websites use cookies to help provide certain functions, and to personalize your web experience by remembering things like your language preferences or items in your shopping cart.
                                </p>
                                <br>
                                <p>
                                    <b>How does {{ config('app.COMPANY_NAME') }} use cookies?</b>
                                </p>
                                <br>
                                <p>
                                    {{ config('app.COMPANY_NAME') }} uses cookies to understand how you interact with our websites, communications, services and selected third party websites primarily with the aim of improving your user experience. The following is a summary of how {{ config('app.COMPANY_NAME') }} uses cookies:
                                    <br>
                                    {{ config('app.COMPANY_NAME') }} uses cookies that are essential for navigating and enabling certain functioning of {{ config('app.COMPANY_NAME') }} websites. For example, these types of cookies assist {{ config('app.COMPANY_NAME') }} in authenticating you and your device or allows {{ config('app.COMPANY_NAME') }} to administer secure websites for security purposes. If you choose to disable these cookies, specific features of the {{ config('app.COMPANY_NAME') }} websites, such as accessing your account or making a purchase, may not be available.
                                    <br>
                                    {{ config('app.COMPANY_NAME') }} uses cookies to understand how customers use {{ config('app.COMPANY_NAME') }} websites and services in order to improve performance. Performance cookies generate aggregated, de-identified information which provide insights into trends and usage patterns that may be used for business analysis, website and service improvements, and for determining performance metrics. For example, these types of cookies help track the number of visitors to our websites.
                                    <br>
                                    {{ config('app.COMPANY_NAME') }} uses cookies to remember your selections while visiting a {{ config('app.COMPANY_NAME') }} website. These types of cookies allow {{ config('app.COMPANY_NAME') }} to recognize your device so that you do not have to provide the same information repeatedly. For example, we may use cookies to remember your language preferences or province selected.
                                    <br>
                                    {{ config('app.COMPANY_NAME') }} uses cookies to personalize your experience while visiting a {{ config('app.COMPANY_NAME') }} website. These cookies may be used to deliver {{ config('app.COMPANY_NAME') }} content to you that is customized to your interests based on an inference from your browsing patterns on our websites or from your customer relationship with us. For example, a relevant Offer about a product or service may be displayed to you based on the existing products and services you currently have with {{ config('app.COMPANY_NAME') }}
                                    <br>
                                    {{ config('app.COMPANY_NAME') }} uses cookies to deliver tailored {{ config('app.COMPANY_NAME') }} advertising to you. We may work with advertising agencies to place cookies to help deliver ads on third party websites where we purchase advertising. These types of third party cookies enable us to understand what {{ config('app.COMPANY_NAME') }} ads you see and interact with, both on our website and on third party websites. This helps us to measure the success of our advertising campaigns. For example, when you click on a {{ config('app.COMPANY_NAME') }} ad on a third-party website, cookies are used to track the effectiveness of the advertising campaign. As part of this process, we do not disclose your personal information to the third-party website.
                                    <br>
                                    In addition to cookies, {{ config('app.COMPANY_NAME') }} also uses pixel tags which are small transparent images embedded in a web page or an email. We use pixel tags in connection with cookies to help operate our website and emails. For example, {{ config('app.COMPANY_NAME') }} may include pixel tags in email messages or newsletters in order to determine whether or not they are opened or acted on. Third parties may also use pixel tags to help compile aggregated statistics and determine the effectiveness of {{ config('app.COMPANY_NAME') }} promotional campaigns.
                                </p>
                                <br>
                                <p>
                                    <b>What are your options with cookies?</b>
                                </p>
                                <br>
                                <p>
                                    You have a number of options on how you manage your cookies. Through your browser settings, you can accept, refuse or delete cookies. However, if you choose to delete or refuse to accept cookies, some or all of the functions on our {{ config('app.COMPANY_NAME') }} websites may not be available to you. To change your browser cookies settings you can visit the following help pages:
                                    <br>
                                    Google Chrome 
                                    <br>
                                    https://support.google.com/chrome/answer/95647
                                    <br>
                                    Microsoft Internet Explorer
                                    <br>
                                    https://support.microsoft.com/en-ph/help/17442/windows-internet-explorer-delete-manage-cookies
                                    <br>
                                    Apple Safari 
                                    <br>
                                    https://support.apple.com/en-ph/guide/safari/manage-cookies-and-website-data-sfri11471/mac
                                    <br>
                                    Mozilla Firefox 
                                    <br>
                                    https://support.mozilla.org/en-US/kb/enable-and-disable-cookies-website-preferences
                                    <br>
                                </p>
                                <br>
                            </li>
                            <li>
                                <span class="fa fa-check"></span>
                                SECTION 7 - AGE OF CONSENT
                                <br><br>
                                <p>
                                    By using this site, you represent that you are at least the age of majority in your state or province of residence, or that you are the age of majority in your state or province of residence and you have given us your consent to allow any of your minor dependents to use this site.
                                </p>
                                <br>
                            </li>
                            <li>
                                <span class="fa fa-check"></span>
                                SECTION 8 - CHANGES TO THIS PRIVACY POLICY
                                <br><br>
                                <p>
                                    We reserve the right to modify this privacy policy at any time, so please review it frequently. Changes and clarifications will take effect immediately upon their posting on the website. If we make material changes to this policy, we will notify you here that it has been updated, so that you are aware of what information we collect, how we use it, and under what circumstances, if any, we use and/or disclose it. If our store is acquired or merged with another company, your information may be transferred to the new owners so that we may continue to sell products to you.
                                </p>
                                <br>
                            </li>
                            <li>
                                <span class="fa fa-check"></span>
                                QUESTIONS AND CONTACT INFORMATION
                                <br><br>
                                <p>
                                    If you would like to: access, correct, amend or delete any personal information we have about you, register a complaint, or simply want more information contact our Customer Service at info@sfimc.org
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



