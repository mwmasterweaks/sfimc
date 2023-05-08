<!-- jQuery js -->
    <script src="{{URL::to('front/js/jquery.js')}}"></script>
    <!-- bootstrap js -->
    <script src="{{URL::to('front/js/bootstrap.min.js')}}"></script>
    <!-- jQuery ui js -->
    <script src="{{URL::to('front/js/jquery-ui.js')}}"></script>
    <!-- owl carousel js -->
    <script src="{{URL::to('front/js/owl.carousel.min.js')}}"></script>
    
    <!-- mixit up -->
    <script src="{{URL::to('front/js/wow.js')}}"></script>
    <script src="{{URL::to('front/js/jquery.mixitup.min.js')}}"></script>
    <script src="{{URL::to('front/js/jquery.fitvids.js')}}"></script>
    <script src="{{URL::to('front/js/bootstrap-select.min.js')}}"></script>

    <!-- revolution slider js -->
    <script src="{{URL::to('front/assets/revolution/js/jquery.themepunch.tools.min.js')}}"></script>
    <script src="{{URL::to('front/assets/revolution/js/jquery.themepunch.revolution.min.js')}}"></script>
    <script src="{{URL::to('front/assets/revolution/js/extensions/revolution.extension.actions.min.js')}}"></script>
    <script src="{{URL::to('front/assets/revolution/js/extensions/revolution.extension.carousel.min.js')}}"></script>
    <script src="{{URL::to('front/assets/revolution/js/extensions/revolution.extension.kenburn.min.js')}}"></script>
    <script src="{{URL::to('front/assets/revolution/js/extensions/revolution.extension.layeranimation.min.js')}}"></script>
    <script src="{{URL::to('front/assets/revolution/js/extensions/revolution.extension.migration.min.js')}}"></script>
    <script src="{{URL::to('front/assets/revolution/js/extensions/revolution.extension.navigation.min.js')}}"></script>
    <script src="{{URL::to('front/assets/revolution/js/extensions/revolution.extension.parallax.min.js')}}"></script>
    <script src="{{URL::to('front/assets/revolution/js/extensions/revolution.extension.slideanims.min.js')}}"></script>
    <script src="{{URL::to('front/assets/revolution/js/extensions/revolution.extension.video.min.js')}}"></script>

    <!-- fancy box -->
    <script src="{{URL::to('front/js/jquery.fancybox.pack.js')}}"></script>
    <script src="{{URL::to('front/js/jquery.polyglot.language.switcher.js')}}"></script>
    <script src="{{URL::to('front/js/nouislider.js')}}"></script>
    <script src="{{URL::to('front/js/jquery.bootstrap-touchspin.js')}}"></script>
    <script src="{{URL::to('front/js/SmoothScroll.js')}}"></script>
    <script src="{{URL::to('front/js/jquery.appear.js')}}"></script>
    <script src="{{URL::to('front/js/jquery.flexslider.js')}}"></script>
    <script src="{{URL::to('front/js/custom.js')}}"></script>

        <script type="text/javascript">

                var loadFile = function(event) {
                  var reader = new FileReader();
                  reader.onload = function(){
                    var output = document.getElementById('output');
                    output.src = reader.result;
                  };
                  reader.readAsDataURL(event.target.files[0]);
                };
                
                $(function () {
                    $(".NumberOnly").on("keypress keyup blur",function (event) {
                        $(this).val($(this).val().replace(/[^\d].+/, ""));
                        if ((event.which < 48 || event.which > 57)) {
                            event.preventDefault();
                        }
                    });
                });

                function buttonOneClick(vID, vLabel, vIsDisabled){
                        var btn = $("#"+vID);
                        if(vIsDisabled){
                                btn.html('<img src="{{ URL::to('img/button-loader.gif') }}" style="max-height:15px;">');
                                btn.attr("disabled", true);
                        }else{
                                btn.html(vLabel);
                                btn.removeAttr("disabled");
                        }
                }

                function showJSModalMessageJS(vHeader,vMessage,vButtonLabel){
                        $("#spnMessageHeader").text(vHeader);
                        $("#divMessage").text(vMessage);
                        $("#spnMessageButtonLabel").text(vButtonLabel);

                        $("#message-modal").modal();
                }

                function AddToCart(vProductID, vQty, vRemarks){
                        var vSessionID = 0;
                        var vMemberEntryID = 0;

                        @if(!Session::get('SESSION_ID'))
                            <?php
                                Session::put('SESSION_ID',date("YmdHis"));
                            ?>
                        @endif
                        vSessionID = {{ Session::get('SESSION_ID') }};

                        @if(Session::get('MEMBER_ENTRY_ID'))
                            vMemberEntryID = {{ Session::get('MEMBER_ENTRY_ID') }};
                        @endif

                        $.ajax({
                                type: "post",
                                data: {
                                        _token: '{{ $Token }}',
                                        SessionID : vSessionID,
                                        MemberEntryID : vMemberEntryID,
                                        ProductID : vProductID,
                                        Qty : vQty,
                                        Remarks : vRemarks
                                },
                                url: "{{ route('add-to-cart-with-qty-background') }}",
                                dataType: "json",
                                success: function(data){
                                        $("#spnCartItemCount").text(data.CartCount);
                                        $("#divLoader").hide();
                                        showJSMessage("Cart","Product successfully added to your cart.","OK");
                                },
                                error: function(data){
                                        $("#divLoader").hide();
                                        console.log(data.responseText);
                                },
                                beforeSend:function(vData){
                                        $("#divLoader").show();
                                }
                        });
            
                }

                function UpdateCartItem(vProductID, vQty){
                    var vSessionID = 0;
                    var vMemberEntryID = 0;

                    @if(!Session::get('SESSION_ID'))
                        <?php
                            Session::put('SESSION_ID',date("YmdHis"));
                        ?>
                    @endif
                    vSessionID = {{ Session::get('SESSION_ID') }};

                    @if(Session::get('MEMBER_ENTRY_ID'))
                        vMemberEntryID = {{ Session::get('MEMBER_ENTRY_ID') }};
                    @endif

                    $.ajax({
                        type: "post",
                        data: {
                         _token: '{{ $Token }}',
                           SessionID : vSessionID,
                          MemberEntryID : vMemberEntryID,                          
                          ProductID : vProductID,
                          Qty : vQty,
                          Remarks : ''
                        },
                        url: "{{ route('update-item-cart-single') }}",
                        dataType: "json",
                        success: function(data){

                          if(data.Response == "Success"){
                              $("#spnCartItemCount").text(data.CartCount);
                              showJSMessage("Update Cart","Product successfully updated to your cart.","OK");
                          }

                          $("#divLoader").hide();
                        },
                        error: function(data){
                          $("#divLoader").hide();
                          console.log(data.responseText);
                        },
                        beforeSend:function(vData){
                             $("#divLoader").show();
                        }

                    });

                }

                function RemoveFromCart(vProductID){
                    var vSessionID = 0;
                    var vMemberEntryID = 0;

                    @if(!Session::get('SESSION_ID'))
                        <?php
                            Session::put('SESSION_ID',date("YmdHis"));
                        ?>
                    @endif
                    vSessionID = {{ Session::get('SESSION_ID') }};

                    @if(Session::get('MEMBER_ENTRY_ID'))
                        vMemberEntryID = {{ Session::get('MEMBER_ENTRY_ID') }};
                    @endif

                    $.ajax({
                        type: "post",
                        data: {
                            _token: '{{ $Token }}',
                            SessionID : vSessionID,
                            MemberEntryID : vMemberEntryID,
                            ProductID : vProductID
                        },
                        url: "{{ route('remove-item-cart') }}",
                        dataType: "json",
                        success: function(data){
                          $("#divLoader").hide();
                          if(data.Response == "Success"){
                              location.reload();
                          }
                        },
                        error: function(data){
                          $("#divLoader").hide();
                          console.log(data.responseText);
                        },
                        beforeSend:function(vData){
                             $("#divLoader").show();
                        }

                    });   

                }

                function FormatDecimal(vValue, vDecimal){
                  var vReturn
                  try {
                    vReturn = vValue.toString().replace(",","");
                    vReturn = parseFloat(vValue);
                    vReturn = vReturn.toFixed(vDecimal);
                  }
                  catch(err) {
                    vReturn = 0;
                  }

                  return vReturn;
                }
                
        </script>

    