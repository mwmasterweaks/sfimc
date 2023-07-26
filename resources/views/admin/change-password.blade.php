@extends('layout.adminweb')

@section('content')

    <div class="box">

        <div class="box-header" style="text-align:center; background-color:#222d32; color:white; margin-left:2px; margin-right:2px;">
            <h3 class="box-title"><strong>Change User Account</strong></h3>
        </div><!-- /.box-header -->

        <div class="box-body table-responsive">
            {{-- Notification --}}
            @if (Session::has('SUCCESS_MSG'))
                <div class="box-body">
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ Session('SUCCESS_MSG') }}
                    </div>
                </div><!-- /.box-body -->
            @elseif (Session::has('ERROR_MSG'))
                <div class="box-body">
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ Session('ERROR_MSG') }}
                    </div>
                </div><!-- /.box-body -->
            @endif

            <div class="col-md-12">
                <form class="form-horizontal " action="{{ route('do-change-admin-password') }}" method="post"  enctype="multipart/form-data">

                    <input type='hidden' id='_token' name='_token' value="{{ $Token }}" readonly>

                    <div>
                        <h4 class="col-md-12">Account Information</h4>
                    </div>

                    <div>
                        <label class="col-md-12">Current Password</label>
                        <div class="col-md-12">
                            <input type="password" name="CurrentPassword" class="form-control inputStylePassword" placeholder="Current Password"  style="width:100%;" value="" />
                        </div>
                    </div>

                    <div>
                        <label class="col-md-12">New Password</label>
                        <div class="col-md-12">
                            <input type="password" name="NewPassword" class="form-control inputStylePassword" placeholder="New Password"  style="width:100%;" value="" />
                        </div>
                    </div>

                    <div>
                        <label class="col-md-12">Confirm New Password</label>
                        <div class="col-md-12">
                            <input type="password" name="ConfirmNewPassword" class="form-control inputStylePassword" placeholder="Current Password"  style="width:100%;" value="" />
                        </div>
                    </div>

                    <div style="clear:both;"></div>
                    <br>
                    <div class="col-md-12">
                        <input type="checkbox" onclick="myFunction()"> <span><label for="">Show Password</label></span>
                    </div>

                    <div style="clear:both;"></div>
                    <br>
                    <div class="col-md-12">
                        <button id="btnSave" type="submit" class="btn btn-info one-click">Save</button>
                    </div>
                    <div style="clear:both;"></div>
                    <br><br>
                </form>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <script type="text/javascript">

        function myFunction() {
            var x = document.getElementsByClassName("inputStylePassword");
            if (x[0].type === "password") {
                x[0].type = "text";
                x[1].type = "text";
                x[2].type = "text";
            } else {
                x[0].type = "password";
                x[1].type = "password";
                x[2].type = "password";
            }
        }

    </script>



@endsection

