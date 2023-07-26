@extends('layout.adminweb')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      User Accounts
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin-dashboard') }}">Home</a></li>
      <li class="active">User Accounts</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
              <div class="box box-success">
                <div class="box-header">
                <div class="col-md-12" style="padding: 2px;">
              <div class="input-group">
                <input type="text" placeholder="Search Here..." class="form-control searchtext">
                <span class="input-group-btn">
                  <a id="btnSearch" href="#" class="btn btn-success btn-flat" style="margin-right:5px;"><i class="fa fa-search"></i></a>
                  <a href="#" class="btn btn-info btn-flat" onclick="NewRecord()"><i class="fa fa-file-o"></i> New</a>
                </span>
              </div>                
            </div>                
                </div>
                <div class="box-body table-responsive" style="min-height: 600px;">
                  <div class="col-md-12">
                @include('inc.admin.adminmessage')
                  </div>
                    <table id="tblList" class="table table-bordered table-hover" style="overflow-x:auto;">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th></th>
                          <th>Center</th>
                          <th>Fullname</th>
                          <th>Username</th>
                          <th>IsSuperAdmin</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                    </tbody>
                    </table>
                </div>
              </div>
            </div>
    </div>
  </section>

  <!-- /.content -->  
  <div id="record-info-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
      <div class="modal-dialog modal-lg" style="width: 90%;">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #3c8dbc;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"  style="text-align:center; color:white;"><span><b>User Account Information</b></span></h4>
          </div>
          <div class="modal-body">
            <input type="hidden" id="UserAccountID" value="0" readonly>

            <div class="col-md-6">
              <div class="row">
                  <div class="col-md-12">
                    <label class="col-md-12" style="font-size: 15px;"><i class='fa fa-info font-size:15px;'></i>&nbsp&nbsp<b>User Information</b></label>
                </div>
              </div>
              <div style="clear:both;"></div>
              <br>                
              <div class="row">

                <div class="col-md-8">
                    <label class="col-md-12" style="font-weight: normal;">Center <span style="color:red;">*</span></label>
                    <div class="col-md-12">
                      <select id="Center" class="form-control select2" style="width: 100%; height: 100px; font-weight: normal;">
                        <option value="">Please Select</option>
                        @foreach ($CenterList as $ctr)
                          <option value="{{ $ctr->CenterID }}"
                            >{{ $ctr->Center }}</option>
                        @endforeach
                      </select>
                    </div>
                </div>

                <div class="col-md-12">
                  <label class="col-md-12" style="font-weight: normal;">Full Name <span style="color:red;">*</span></label>
                  <div class="col-md-12">
                    <input type="text" id="Fullname" name="Fullname" placeholder="Fullname" class="form-control">
                  </div>
                </div>
              </div>
              <div style="clear:both;"></div>
              <br>                
              <div class="row">
                <div class="col-md-6">
                    <label class="col-md-12" style="font-weight: normal;">Username <span style="color:red;">*</span></label>
                    <div class="col-md-12">
                        <input id="Username" type="text" class="form-control" value="" style="width:100%; font-weight:normal;">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="col-md-12" style="font-weight: normal;">Password <span style="color:red;">*</span></label>
                    <div class="col-md-12">
                        <input id="UserPassword" type="password" class="form-control" value="" style="width:100%; font-weight:normal;">
                    </div>
                </div>
              </div>

              <div style="clear:both;"></div>
              <br>                
              <div class="row">
                  <div class="col-md-6">
                      <label class="col-md-12" style="font-weight: normal;">Super Admin <span style="color:red;">*</span></label>
                      <div class="col-md-12">
                        <select id="IsSuperAdmin" class="form-control select2" style="width: 100%; font-weight: normal;">
                          <option value="1" selected>Yes</option>
                          <option value="0">No</option>
                        </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
                      <div class="col-md-12">
                        <select id="Status" class="form-control select2" style="width: 100%; font-weight: normal;">
                          <option value="Active" selected>Active</option>
                          <option value="Inactive">Inactive</option>
                        </select>
                      </div>
                  </div>
              </div>
            </div>
            <div class="col-md-6">
              <div style="clear:both;"></div>
              <br> 
              <div class="col-md-12" style="border-left:1px solid lightgray;">
                <div class="form-group" style="padding:5px; background-color:lightgray;text-align: center;">
                  <label>Navigation Access</label>                        
                </div>

                <p style="font-weight:normal;color:red;">Please check if applied for user menu access:</p>

                <div class="col-md-12" style="height:730px;padding:5px; background-color: #222d32;color:#222d32;color:#b8c7ce;overflow-y: scroll;"> 
                  <div>                       
                      <label style="font-size: 15px;"><i class="fa fa-minus-square" style="margin-right: 10px;"></i>
                       Dashboard                          
                      </label>                        
                  </div>
                  <div>
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess1" value="1">&nbsp&nbsp&nbsp Center Management
                    </label>
                  </div>
                  <div>                       
                      <label style="font-size: 15px;">
                        <i class="fa fa-minus-square" style="margin-right: 10px;"></i>
                       Code Management   
                      </label>                        
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess2" value="2">&nbsp&nbsp&nbsp Code Generation
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess21" value="21">&nbsp&nbsp&nbsp Code Distribution
                    </label>
                  </div>
                  <div style="margin-left: 50px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess35" value="35">&nbsp&nbsp&nbsp Allow Cancel Code
                    </label>
                  </div>
                  <div>                       
                      <label style="font-size: 15px;">
                        <i class="fa fa-minus-square" style="margin-right: 10px;"></i>
                       Member Management   
                      </label>                        
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess3" value="3">&nbsp&nbsp&nbsp Member Entry
                    </label>
                  </div>
                  <div style="margin-left: 50px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess23" value="23">&nbsp&nbsp&nbsp Allow Edit Member Info
                    </label>
                  </div>
                  <div style="margin-left: 50px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess36" value="36">&nbsp&nbsp&nbsp Allow Transfer Position
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess22" value="22">&nbsp&nbsp&nbsp Member Vouchers
                    </label>
                  </div>
                  <div>
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess4" value="4">&nbsp&nbsp&nbsp Package Management
                    </label>
                  </div>
                  <div>
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess5" value="5">&nbsp&nbsp&nbsp Product Management
                    </label>
                  </div>
                  <div>                       
                      <label style="font-size: 15px;">
                        <i class="fa fa-minus-square" style="margin-right: 10px;"></i>
                       Inventory Management   
                      </label>                        
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess6" value="6">&nbsp&nbsp&nbsp Inventory List
                    </label>
                  </div>
                  <div style="margin-left: 50px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess24" value="24">&nbsp&nbsp&nbsp Allow Set Beginning Balance
                    </label>
                  </div>
                  <div style="margin-left: 50px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess25" value="25">&nbsp&nbsp&nbsp Allow Set Min/Max Level
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess7" value="7">&nbsp&nbsp&nbsp Inventory Adjustment
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess26" value="26">&nbsp&nbsp&nbsp Purchase Order (Centers)
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess27" value="27">&nbsp&nbsp&nbsp PO Processing (Main)
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess28" value="28">&nbsp&nbsp&nbsp Purchase Receive (Centers)
                    </label>
                  </div>
                  <div>                       
                      <label style="font-size: 15px;">
                        <i class="fa fa-minus-square" style="margin-right: 10px;"></i>
                       E-Wallet Management   
                      </label>                        
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess8" value="8">&nbsp&nbsp&nbsp Member E-Wallet List
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess9" value="9">&nbsp&nbsp&nbsp E-Wallet Withdrawal
                    </label>
                  </div>
                  <div>
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess10" value="10">&nbsp&nbsp&nbsp Order History
                    </label>
                  </div>
                  <div>
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess37" value="37">&nbsp&nbsp&nbsp Shipper Management
                    </label>
                  </div>
                  <div>                       
                      <label style="font-size: 15px;">
                        <i class="fa fa-minus-square" style="margin-right: 10px;"></i>
                       Reports
                      </label>                        
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess12" value="12">&nbsp&nbsp&nbsp Sales Report
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess13" value="13">&nbsp&nbsp&nbsp Commission Report
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess14" value="14">&nbsp&nbsp&nbsp Withdrawal Report
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess15" value="15">&nbsp&nbsp&nbsp Top Sponsorship Report
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess16" value="16">&nbsp&nbsp&nbsp Top Direct Selling Report
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess17" value="17">&nbsp&nbsp&nbsp Top Center Sales Report
                    </label>
                  </div>
                  <div style="margin-left: 25px; display: none;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess18" value="18">&nbsp&nbsp&nbsp Top Network Builder Report
                    </label>
                  </div>
                  <div>
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess19" value="19">&nbsp&nbsp&nbsp User Account
                    </label>
                  </div>
                  <div>                       
                      <label style="font-size: 15px;">
                        <i class="fa fa-minus-square" style="margin-right: 10px;"></i>
                       Content Management
                      </label>                        
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess29" value="29">&nbsp&nbsp&nbsp Company Information
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess30" value="30">&nbsp&nbsp&nbsp News & Events
                    </label>
                  </div>
                  <div style="margin-left: 25px;">
                    <label style="font-size:12px;font-family: 'Open Sans', sans-serif, sans-serif;">
                      <input type="checkbox" id="chkUserAccess31" value="31">&nbsp&nbsp&nbsp FAQ
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div style="clear:both;"></div>
            <br> 
            <div class="modal-footer">
              <div class="input-group pull-right">
                <span class="input-group-btn">
                  <a id="btnSave" href="#" class="btn btn-info btn-flat" onclick="SaveRecord()"><i class="fa fa-save"></i> Save</a>
                  <a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Cancel</a>
                </span>
              </div>  
            </div>
          </div>

        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <script type="text/javascript">

      var isNewRecord = 0;
      var intCurrentPage = 1;
      var isPageFirstLoad = true;

      $(document).ready(function() {

        $('#tblList').DataTable( {
          "columnDefs": [
                      {
                          "targets": [ 0 ],
                          "visible": false,
                          "searchable": false
                      }
                  ],
          'paging'      : false,
          'lengthChange': false,
          'searching'   : false,
          'ordering'    : true,
          'info'        : false,
          'autoWidth'   : false,
                "order": [[ 2, "asc" ], [ 3, "asc" ]]
          });

          //Load Initial Data
          getRecordList(intCurrentPage, '');
          isPageFirstLoad = false;

      });

      $("#btnSearch").click(function(){
        $("#tblList").DataTable().clear().draw();

        intCurrentPage = 1;
        getRecordList(intCurrentPage, $('.searchtext').val());
      });

      $('.searchtext').on('keypress', function (e) {
        if(e.which === 13){
              $("#tblList").DataTable().clear().draw();

          intCurrentPage = 1;
          getRecordList(intCurrentPage, $('.searchtext').val());
        }
      });

      function getRecordList(vPageNo, vSearchText){
      $.ajax({
        type: "post",
        data: {
          _token: '{{ $Token }}',
          SearchText: vSearchText,
          PageNo: vPageNo
        },
        url: "{{ route('get-user-accounts-list') }}",
        dataType: "json",
        success: function(data){
          LoadRecordList(data.UserAccountList);
              $("#divLoader").hide();
        },
        error: function(data){
          console.log(data.responseText);
              $("#divLoader").hide();
        },
        beforeSend:function(vData){
              $("#divLoader").show();
        }
        });
      };

      function LoadRecordList(vList){
        if(vList.length > 0){
          for(var x=0; x < vList.length; x++){
          LoadRecordRow(vList[x]);
          }
        }
      }

      function LoadRecordRow(vData){

        var tblList = $("#tblList").DataTable();

        tdID = vData.UserAccountID;
        tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
                          " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
                            " <span class='fa fa-caret-down'></span> " +
                          "</button> " +
                          "<ul class='dropdown-menu'> " +
                          "   <li style='text-align:left;'> " +
                          "     <a href='#' onclick='EditRecord(" + vData.UserAccountID + ")'>" + 
                          "       <strong><i class='fa fa-info-circle font-size:15px;'></i> Edit Record</strong>" +
                          "     </a> " +
                          "   </li> " +
                          "</ul> " +
                      " </div> " ;
        tdCenter = "<span style='font-weight:normal;'>" + vData.Center + "</span>";
        tdFullname = "<span style='font-weight:normal;'>" + vData.Fullname + "</span>";
        tdUsername = "<span style='font-weight:normal;'>" + vData.Username + "</span>";
        tdIsSuperAdmin = "<span style='font-weight:normal;'>" + (vData.IsSuperAdmin == 1 ? "Yes" : "No") + "</span>";

        tdStatus = "";
        if(vData.Status != "{{ config('app.STATUS_ACTIVE') }}"){
          tdStatus += "<span class='label label-danger' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
        }else{
          tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
        }

      //Check if record already listed
      var IsRecordExist = false;
      tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

          var rowData = this.data();
          if(rowData[0] == vData.UserAccountID){
          IsRecordExist = true;

            curData = tblList.row(rowIdx).data();
            curData[0] = tdID;
            curData[1] = tdOption;
            curData[2] = tdCenter;
            curData[3] = tdFullname;
            curData[4] = tdUsername;
            curData[5] = tdIsSuperAdmin;
            curData[6] = tdStatus;
            tblList.row(rowIdx).data(curData).invalidate().draw();
          }

      });

      if(!IsRecordExist){
          //New Row
        var rowNode = tblList.row.add([
            tdID,
            tdOption,
            tdCenter,
            tdFullname,
            tdUsername, 
            tdIsSuperAdmin, 
            tdStatus
          ]).draw().node();     
      }

      }

      function Clearfields(){

          $("#UserAccountID").val('0');

          $("#Center").val('').change();
          $("#Fullname").val('');
          $("#Username").val('');
          $("#UserPassword").val('');

          $("#IsSuperAdmin").val('0').change();
          $("#Status").val('Active').change();

          $("#chkUserAccess1").prop("checked", false);
          $("#chkUserAccess2").prop("checked", false);
          $("#chkUserAccess3").prop("checked", false);
          $("#chkUserAccess4").prop("checked", false);
          $("#chkUserAccess5").prop("checked", false);
          $("#chkUserAccess6").prop("checked", false);
          $("#chkUserAccess7").prop("checked", false);
          $("#chkUserAccess8").prop("checked", false);
          $("#chkUserAccess9").prop("checked", false);
          $("#chkUserAccess10").prop("checked", false);

          $("#chkUserAccess12").prop("checked", false);
          $("#chkUserAccess13").prop("checked", false);
          $("#chkUserAccess14").prop("checked", false);
          $("#chkUserAccess15").prop("checked", false);
          $("#chkUserAccess16").prop("checked", false);
          $("#chkUserAccess17").prop("checked", false);
          $("#chkUserAccess18").prop("checked", false);
          $("#chkUserAccess19").prop("checked", false);

          $("#chkUserAccess21").prop("checked", false);
          $("#chkUserAccess22").prop("checked", false);
          $("#chkUserAccess23").prop("checked", false);
          $("#chkUserAccess24").prop("checked", false);
          $("#chkUserAccess25").prop("checked", false);
          $("#chkUserAccess26").prop("checked", false);
          $("#chkUserAccess27").prop("checked", false);
          $("#chkUserAccess28").prop("checked", false);
          $("#chkUserAccess29").prop("checked", false);
          $("#chkUserAccess30").prop("checked", false);
          $("#chkUserAccess31").prop("checked", false);

          $("#chkUserAccess35").prop("checked", false);
          $("#chkUserAccess36").prop("checked", false);
          $("#chkUserAccess37").prop("checked", false);

          $("#btnSave").show();

      }

      function NewRecord(){
        isNewRecord = 1;
        Clearfields();
        $("#record-info-modal").modal();
      }

      function EditRecord(vRecordID){

        if(vRecordID > 0){

          isNewRecord = 0;
          $.ajax({
            type: "post",
            data: {
              _token: '{{ $Token }}',
              UserAccountID: vRecordID
            },
            url: "{{ route('get-user-accounts-info') }}",
            dataType: "json",
            success: function(data){
              $("#divLoader").hide();
              buttonOneClick("btnSave", "Save", false);

              if(data.Response =='Success' && data.UserAccountInfo != undefined){
                
                Clearfields();
                
                $("#UserAccountID").val(data.UserAccountInfo.UserAccountID);

                $("#Center").val(data.UserAccountInfo.CenterID).change();
                $("#Fullname").val(data.UserAccountInfo.Fullname);
                $("#Username").val(data.UserAccountInfo.Username);

                $("#IsSuperAdmin").val(data.UserAccountInfo.IsSuperAdmin).change();
                $("#Status").val(data.UserAccountInfo.Status).change();

                if(data.UserAccountModuleList.length > 0){
                  for(var x=0; x < data.UserAccountModuleList.length; x++){
                    if(data.UserAccountModuleList[x].SysModuleID == 1){
                      $("#chkUserAccess1").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 2){
                      $("#chkUserAccess2").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 3){
                      $("#chkUserAccess3").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 4){
                      $("#chkUserAccess4").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 5){
                      $("#chkUserAccess5").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 6){
                      $("#chkUserAccess6").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 7){
                      $("#chkUserAccess7").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 8){
                      $("#chkUserAccess8").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 9){
                      $("#chkUserAccess9").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 10){
                      $("#chkUserAccess10").prop("checked", true);
                    }

                    if(data.UserAccountModuleList[x].SysModuleID == 12){
                      $("#chkUserAccess12").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 13){
                      $("#chkUserAccess13").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 14){
                      $("#chkUserAccess14").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 15){
                      $("#chkUserAccess15").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 16){
                      $("#chkUserAccess16").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 17){
                      $("#chkUserAccess17").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 18){
                      $("#chkUserAccess18").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 19){
                      $("#chkUserAccess19").prop("checked", true);
                    }

                    if(data.UserAccountModuleList[x].SysModuleID == 21){
                      $("#chkUserAccess21").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 22){
                      $("#chkUserAccess22").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 23){
                      $("#chkUserAccess23").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 24){
                      $("#chkUserAccess24").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 25){
                      $("#chkUserAccess25").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 26){
                      $("#chkUserAccess26").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 27){
                      $("#chkUserAccess27").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 28){
                      $("#chkUserAccess28").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 29){
                      $("#chkUserAccess29").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 30){
                      $("#chkUserAccess30").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 31){
                      $("#chkUserAccess31").prop("checked", true);
                    }

                    if(data.UserAccountModuleList[x].SysModuleID == 35){
                      $("#chkUserAccess35").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 36){
                      $("#chkUserAccess36").prop("checked", true);
                    }
                    if(data.UserAccountModuleList[x].SysModuleID == 37){
                      $("#chkUserAccess37").prop("checked", true);
                    }

                  }
                }

                $("#record-info-modal").modal();

              }else{
                showJSModalMessageJS("User Account Information",data.ResponseMessage,"OK");
              }
            },
            error: function(data){
              $("#divLoader").hide();
              buttonOneClick("btnSave", "Save", false);
              console.log(data.responseText);
            },
            beforeSend:function(vData){
                  $("#divLoader").show();
              buttonOneClick("btnSave", "", false);
            }
          });
        }
      }

      function SaveRecord(){

        if($('#Fullname').val() == "") {
          showJSMessage("User Account","Please enter user full name.","OK");
        }else if($('#Username').val() == "") {
          showJSMessage("User Account","Please enter username.","OK");
        }else if($("#UserAccountID").val() == "0" && $('#UserPassword').val() == "") {
          showJSMessage("User Account","Please enter password.","OK");
        }else{

          $.ajax({
            type: "post",
            data: {
              _token: '{{ $Token }}',
              UserAccountID: $("#UserAccountID").val(),
              Center: $("#Center").val(),
              Fullname: $("#Fullname").val(),
              Username: $("#Username").val(),
              UserPassword: $("#UserPassword").val(),
              IsSuperAdmin: $("#IsSuperAdmin").val(),
              Status: $("#Status").val(),

              UserAccess1: ($('#chkUserAccess1').is(':checked') ? 1 : 0),
              UserAccess2: ($("#chkUserAccess2").is(':checked') ? 2 : 0),
              UserAccess3: ($("#chkUserAccess3").is(':checked') ? 3 : 0),
              UserAccess4: ($("#chkUserAccess4").is(':checked') ? 4 : 0),
              UserAccess5: ($("#chkUserAccess5").is(':checked') ? 5 : 0),
              UserAccess6: ($("#chkUserAccess6").is(':checked') ? 6 : 0),
              UserAccess7: ($("#chkUserAccess7").is(':checked') ? 7 : 0),
              UserAccess8: ($("#chkUserAccess8").is(':checked') ? 8 : 0),
              UserAccess9: ($("#chkUserAccess9").is(':checked') ? 9 : 0),
              UserAccess10: ($("#chkUserAccess10").is(':checked') ? 10 : 0),

              UserAccess12: ($("#chkUserAccess12").is(':checked') ? 12 : 0),
              UserAccess13: ($("#chkUserAccess13").is(':checked') ? 13 : 0),
              UserAccess14: ($("#chkUserAccess14").is(':checked') ? 14 : 0),
              UserAccess15: ($("#chkUserAccess15").is(':checked') ? 15 : 0),
              UserAccess16: ($("#chkUserAccess16").is(':checked') ? 16 : 0),
              UserAccess17: ($("#chkUserAccess17").is(':checked') ? 17 : 0),
              UserAccess18: ($("#chkUserAccess18").is(':checked') ? 18 : 0),
              UserAccess19: ($("#chkUserAccess19").is(':checked') ? 19 : 0),

              UserAccess21: ($("#chkUserAccess21").is(':checked') ? 21 : 0),
              UserAccess22: ($("#chkUserAccess22").is(':checked') ? 22 : 0),
              UserAccess23: ($("#chkUserAccess23").is(':checked') ? 23 : 0),
              UserAccess24: ($("#chkUserAccess24").is(':checked') ? 24 : 0),
              UserAccess25: ($("#chkUserAccess25").is(':checked') ? 25 : 0),
              UserAccess26: ($("#chkUserAccess26").is(':checked') ? 26 : 0),
              UserAccess27: ($("#chkUserAccess27").is(':checked') ? 27 : 0),
              UserAccess28: ($("#chkUserAccess28").is(':checked') ? 28 : 0),
              UserAccess29: ($("#chkUserAccess29").is(':checked') ? 29 : 0),
              UserAccess30: ($("#chkUserAccess30").is(':checked') ? 30 : 0),
              UserAccess31: ($("#chkUserAccess31").is(':checked') ? 31 : 0),

              UserAccess35: ($("#chkUserAccess35").is(':checked') ? 35 : 0),
              UserAccess36: ($("#chkUserAccess36").is(':checked') ? 36 : 0),
              UserAccess37: ($("#chkUserAccess37").is(':checked') ? 37 : 0)

            },
            url: "{{ route('do-save-user-accounts') }}",
            dataType: "json",
            success: function(data){
              $("#divLoader").hide();
              buttonOneClick("btnSave", "Save", false);
              if(data.Response =='Success'){
                $("#record-info-modal").modal('hide');
                showMessage("Success",data.ResponseMessage);
                LoadRecordRow(data.UserAccountInfo);
              }else{
                showJSModalMessageJS("Save User Account",data.ResponseMessage,"OK");
              }
            },
            error: function(data){
              $("#divLoader").hide();
              buttonOneClick("btnSave", "Save", false);
              console.log(data.responseText);
            },
            beforeSend:function(vData){
              $("#divLoader").show();
              buttonOneClick("btnSave", "", true);
            }
          });
        }
      };

      $(window).scroll(function() {
        if(!isPageFirstLoad){
            if($(window).scrollTop() + $(window).height() >= ($(document).height() - 10) ){
                intCurrentPage = intCurrentPage + 1;
                getRecordList(intCurrentPage, $('.searchtext').val());
            }
        }
      });


  </script>




@endsection



