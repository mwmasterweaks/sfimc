@extends('layout.adminweb')

@section('content')
   
   <?php
   
   
  $lvls =  $_GET['level'];
   $mid =  $_GET['MemberEntryID'];
   ?>
   
    <?php 
        $PicPath = "/public/img/members/";

        $ParentID = 1;
        $ParentEntryID = 0;
        $MemberFullname = "";
        if(isset($TOP)){
            $MemberFullname = $TOP->MemberName;
            $ParentEntryID = $TOP->ParentEntryID;
        }

    ?>
        	@php($MatchingLeft = 0)
	@php($MatchingRight = 0)
	@php($AccumulatedPersonalPurchase = 0)
	@php($AccumulatedGroupPurchase = 0)
	@php($TotalEntryShare = 0)
	@php($TotalRewards = 0)
	@php($TotalEncashment = 0)
	@php($AvailableEwallet = 0)
	
	@if(isset($DashboardFigures))
		@foreach($DashboardFigures as $fig)
			@php($MatchingLeft = $fig->LRunningBalance)
			@php($MatchingRight = $fig->RRunningBalance)
			@php($AccumulatedPersonalPurchase = $fig->PersonalRunningBalance)
			@php($AccumulatedGroupPurchase = $fig->GroupRunningBalance)

			@php($TotalEntryShare = $fig->TotalEntryShare)
			@php($TotalRewards = $fig->TotalRewards)
			@php($TotalEncashment = $fig->TotalEncashment)
			@php($AvailableEwallet = $fig->AvailableEwallet)

		@endforeach
	@endif
	

    <div class="box">
        <div class="box-header" style="text-align:center; background-color:#222d32; color:white; margin-left:2px; margin-right:2px;">
            <h3 class="box-title" style="margin-top: 8px;"><strong>{{ $MemberFullname }} - Genealogy</strong></h3>
        </div><!-- /.box-header -->
        
        <style>
.square {
  height: 17px;
  width: 17px;
  background-color: #96856e;
  color:#fff;
   border-radius: 50%;
}
.square2 {
  height: 17px;
  width: 17px;
  background-color: #a2a2a1;
  color:#fff;
   border-radius: 50%;
}
.square3 {
  height: 17px;
  width: 17px;
  background-color: #dfb407;
  color:#fff;
   border-radius: 50%;
}
.square4 {
  height: 17px;
  width: 17px;
  background-color: green;
  color:#fff;
   border-radius: 50%;
}
</style>
      

        <div class="box-body table-responsive">
           nth Level <select name="cars" id="cars" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                <option value="">Select</option>
                  <option value="{{ route('admin-member-genealogy') }}?MemberEntryID={{ $mid }}&MaxLevel=100&level=3">3rd level</option>
                    <option value="{{ route('admin-member-genealogy') }}?MemberEntryID={{ $mid }}&MaxLevel=100&level=4">4th level</option>
  <option value="{{ route('admin-member-genealogy') }}?MemberEntryID={{ $mid }}&MaxLevel=100&level=5">5th level</option>
  <option value="{{ route('admin-member-genealogy') }}?MemberEntryID={{ $mid }}&MaxLevel=100&level=6">6th level</option>
  <option value="{{ route('admin-member-genealogy') }}?MemberEntryID={{ $mid }}&MaxLevel=100&level=7">7th level</option>
  <option value="{{ route('admin-member-genealogy') }}?MemberEntryID={{ $mid }}&MaxLevel=100&level=8">8th level</option>
  <option value="{{ route('admin-member-genealogy') }}?MemberEntryID={{ $mid }}&MaxLevel=100&level=9">9th level</option>
  <option value="{{ route('admin-member-genealogy') }}?MemberEntryID={{ $mid }}&MaxLevel=100&level=10">10th level</option>
</select>


            
            <div style = "display:flex;float:right">
              <div class="square"> </div> Bronze
              <div class="square2"> </div> Silver
              <div class="square3"> </div> Gold
              <div class="square4"> </div> NONE
      
              </div>
              <br>
             <span style = "color:green">Showing level <?php  echo $lvls; ?></span> 
               <br>
{{-- 
	<span class="input-group-addon">Show </span>
                <select id="selLevelNo" class="form-control select2" style="width:100px;">
                        <option value="2" {{ ($MaxLevel == 2 ? 'selected' : '') }}>Level 2</option>
                        <option value="3" {{ ($MaxLevel == 3 ? 'selected' : '') }}>Level 3</option>
                        <option value="4" {{ ($MaxLevel == 4 ? 'selected' : '') }}>Level 4</option>
                </select>
                <a href="#" class="btn btn-success btn-flat pull-right"  onclick="GoToUpline({{ $ParentEntryID }})"> Go To Upline</a>
            </div>
            <br>
            <div style="clear:both;"></div>
 --}}

   <div id="memberentry" style="width:100%; height:700px;"></div>
   <script type="text/ecmascript">

                var nodes = [
                        <?php
                            $CHILDNAME = '';
                            $PARENTMID = '';
                        ?>                    
                        @if(isset($TOP))

                            <?php
                                $PARENTMID = $TOP->EntryID;
                                $ProfilePic = $PicPath.$PARENTMID.".jpg";
                            ?>

                            @if(!File::exists('public/img/members/'.$TOP->EntryID.'.jpg'))
                                <?php 
                                    $ProfilePic = $PicPath."member-no-image.png"; 
                                ?>
                            @endif

                            <?php 
                                $ProfilePic = URL::to('/').$ProfilePic;
                                $EntryCode = "IBO Number : ".$TOP->EntryCode;
                                $MemberName = $TOP->MemberName;
                                $LevelNo = "";
                                $Package = $TOP->Package;
                                $TreeSponsorID = 0;
                                $TreeSponsorEntryCode = "";
                                $TreeSponsorFullName = "";
                                $MatrixPosition = $TOP->ParentPosition;
                                $Status = "Status : ".$TOP->Status;
                            ?>

                            {!! "{ ".
                                "id: ".$ParentID.",".
                                "parentId: 0, ".
                                "entryid: ".$PARENTMID.",".
                                "name: \"".$MemberName."\", ".
                                "entrycode: \"".$EntryCode."\", ".
                                "status: \"".$Status."\", ".
                                "package: \"".$Package."\", ".
                                "level: \"".$LevelNo."\", ".
                                "treesponsorid: \"".$TreeSponsorID."\", ".
                                "treesponsorentrycode: \"".$TreeSponsorEntryCode."\", ".
                                "treesponsorfullname: \"".$TreeSponsorFullName."\", ".
                                "position: \"".$MatrixPosition."\", ".
                                "image: \"".$ProfilePic."\" ".
                                "}\n"
                            !!}
                                
                        @endif
                    
                        @foreach($TREE as $child)

                            <?php
                            $ProfilePic = $PicPath.$child->EntryID.'.jpg';
                            ?>
                            @if(!File::exists('public/img/members/'.$child->EntryID.'.jpg'))
                                <?php
                                    $ProfilePic = $PicPath."member-no-image.png"; 
                                ?>
                            @endif

                            <?php 
                                if($child->LevelNo == 1){
                                    $ParentID = 1;
                                }else{
                                    $ParentID = $MiscModel->getEntryCounter($TREE, $child->TreeSponsorID);
                                }

                                if($child->EntryID > 0){
                                    $ProfilePic = URL::to('/').$ProfilePic;
                                    $EntryCode = "IBO Number : ".$child->EntryCode;
                                    $ChildName = $child->MemberFullName;
                                    $LevelNo = "Level ".$child->LevelNo;
                                    $TreeSponsorID = $child->TreeSponsorID;
                                    $TreeSponsorEntryCode = $child->TreeSponsorEntryCode;
                                    $TreeSponsorFullName = $child->TreeSponsorFullName;
                                    $MatrixPosition = $child->MatrixPosition;
                                    $Package = $child->Package;
                                    $Status = "Status : ".$child->Status;
                                }else{
                                    $ProfilePic = URL::to('/').$ProfilePic;
                                    $EntryCode = "";
                                    $ChildName = "";
                                    $LevelNo = "";
                                    $TreeSponsorID = $child->TreeSponsorID;
                                    $TreeSponsorEntryCode = $child->TreeSponsorEntryCode;
                                    $TreeSponsorFullName = $child->TreeSponsorFullName;
                                    $MatrixPosition = $child->MatrixPosition;
                                    $Package = "";
                                    $Status = "";
                                }
                            ?>


    

                            {!! ",{ ".
                                "id: ".$child->Cntr.",".
                                "parentId: ".$ParentID.", ".
                                "entryid: ".$child->EntryID.",".
                                "name: \"".$ChildName."\", ".
                                "entrycode: \"".$EntryCode."\", ".
                                "status: \"".$Status."\", ".
                                "package: \"".$Package."\", ".
                                "level: \"".$LevelNo."\", ".
                                "treesponsorid: \"".$TreeSponsorID."\", ".
                                "treesponsorentrycode: \"".$TreeSponsorEntryCode."\", ".
                                "treesponsorfullname: \"".$TreeSponsorFullName."\", ".
                                "position: \"".$MatrixPosition."\", ".
                                "image: \"".$ProfilePic."\" ".
                                "}\n"
                            !!}

                        @endforeach

                    ];


                var chart = new getOrgChart(document.getElementById("memberentry"), {
                    //theme: "annabel",
                    //theme: "belinda",
                   // theme: "cassandra",
                    //theme: "deborah",
                    //theme: "lena",
                    theme: "monica",
                    //theme: "eve",
                    //theme: "vivian",
                    //theme: "helen",
                    gridView: true,
                    color: "green",
                    enableEdit: false,
                    enableDetailsView: false,       
                    expandToLevel: <?php echo $lvls; ?>,
                    mode: 'dark',
                    orientation: getOrgChart.RO_TOP,
                    template: "olivia",
                    photoFields: ["image"],
                    primaryFields: ["name", "entrycode", "status", "package", "level"],
                    clickNodeEvent: function( sender, args ) { 
                        if(args.node.data.package != ""){
                            $('#MemberEntryID').val(args.node.data.entryid);
                            $('#MemberName').val(args.node.data.name);
                             $('#PARENTMID').val(args.node.data.entrycode);
                                 $('#Status').val(args.node.data.status);
                                  $('#Package').val(args.node.data.package);
                                  $('#LevelNo').val(args.node.data.level);
                                   $('#TreeSponsorEntryCode').val(args.node.data.treesponsorentrycode);
                                    $('#TreeSponsorFullName').val(args.node.data.treesponsorfullname);
                                     $('#MatrixPosition').val(args.node.data.position);
                            $('#myModal').modal('show');
                        }else{
                            NewRecord(args.node.data.treesponsorid, args.node.data.treesponsorentrycode, args.node.data.treesponsorfullname, args.node.data.position);
                        }
                        return false; 
                    },
                    dataSource: nodes
                });

            </script>   

            <style type="text/css">
                
                @if(isset($TOP))
                    @if($TOP->PackageID == 1)
                        g[data-node-id='1'] > path {
                            fill: #9a6941 !important;
                            stroke: #d7b1a0 !important;
                        }
                    @elseif($TOP->PackageID == 2)
                        g[data-node-id='1'] > path {
                            fill: #a2a2a1 !important;
                            stroke: #dfddd6 !important;
                        }
                    @elseif($TOP->PackageID == 3)
                        g[data-node-id='1'] > path {
                            fill: #dfb407 !important;
                            stroke: #f8e692 !important;
                        }
                    @else
                        g[data-node-id='1'] > path {
                            fill: #96856e !important;
                            stroke: #f8bbc3 !important;
                        }
                    @endif
                @endif

                @foreach($TREE as $child)
                    @if($child->PackageID == 1)
                        g[data-node-id='{{ $child->Cntr }}'] > path {
                            fill: #9a6941 !important;
                            stroke: #d7b1a0 !important;
                        }
                    @elseif($child->PackageID == 2)
                        g[data-node-id='{{ $child->Cntr }}'] > path {
                            fill: #a2a2a1 !important;
                            stroke: #dfddd6 !important;
                        }
                    @elseif($child->PackageID == 3)
                        g[data-node-id='{{ $child->Cntr }}'] > path {
                            fill: #dfb407 !important;
                            stroke: #f8e692 !important;
                        }
                    @else
                        g[data-node-id='{{ $child->Cntr }}'] > path {
                            fill: green !important;
                            stroke: #6e9679 !important;
                        }
                    @endif
                @endforeach                

            </style>
 
         

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style = "font-size:22px;float:center">
                <span>Member Details:</span>
            </div>
            <div class="modal-body">
                <form id="frmSubmit"  name="frmSubmit" method="get" action="{{ route('admin-member-genealogy') }}">
                <input type="hidden" id="MemberEntryID" name="MemberEntryID" value="" />
                <input type="hidden" id="MaxLevel" name="MaxLevel" value="100" />
                <input type="hidden" id="MaxLevel" name="level" value="5" />
<table>
 <tr>
    <td><input type="text" id="PARENTMID" style = "color:#121212;border:none" name="" readonly>  </td>
  </tr>
  <tr>
    <td> Full Name: <input type="text" id="MemberName" style = "color:red;border:none;font-size:22px" name="" readonly>  </td>
  </tr>
  <tr>
    <td> <input type="text" id="Status" style = "color:#121212;border:none" name="" readonly>  </td>
  </tr>
  <tr>
    <td> <input type="text" id="Package" style = "color:#121212;border:none" name="" readonly>  </td>
  </tr>
  <tr>
    <td> <input type="text" id="LevelNo" style = "color:#121212;border:none" name="" readonly>  </td>
  </tr>
    <tr>
    <td>   Position:   <input type="text" id="MatrixPosition" style = "color:#121212;border:none" name="" readonly>  </td>
	              
  </tr>
  <tr>
    <td>Sponsor Details:<br> IBO #: <input type="text" id="TreeSponsorEntryCode" style = "color:green;border:none" name="" readonly>  <br>
        Name: <input type="text" id="TreeSponsorFullName" style = "color:green;border:none" name="" readonly> 
    </td>
  </tr>
  
    <tr>
    <td>     <span class="info-box-text">Matching Left (Waiting)</span>
	              <span class="info-box-number">{{ number_format($MatchingLeft,0) }}</span>  </td>
  </tr>

 
  <tr>
    <td>      <span class="info-box-text">Matching Right (Waiting)</span>
	              <span class="info-box-number">{{ number_format($MatchingRight,0) }}</span> </td>
	              
  </tr>

 
	              
	            
<style>
table {
  font-family: arial, sans-serif;

  width: 100%;
  
}

td, th {
  border: 1px solid #91ba9c;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #fff;
}
</style>
 
</table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <input type = "submit" class="btn btn-success" value = "View Genealogy">
            </div>
                  </form>
        </div>
    </div>
</div>



            <div id="record-info-modal" class="modal fade bs-example-modal-lg" style="display:none;"> 
              <div class="modal-dialog modal-lg" style="width: 90%;">
                <div class="modal-content">

                    <div class="modal-header" style="background-color: #3c8dbc;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"  style="text-align:center; color:white;"><b>Member Entry Information</b></h4>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" id="EntryID" value="0" readonly>
                        <input type="hidden" id="MemberID" value="0" readonly>

                        <div class="row">
                            <div class="col-md-3">
                                <label class="col-md-12" style="font-weight: normal;">IBO Number</label>
                                <div class="col-md-12">
                                    <input id="EntryCode" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-md-12" style="font-weight: normal;">Date/Time</label>
                                <div class="col-md-12">
                                    <input id="EntryDateTime" type="text" class="form-control" value="" placeholder="Auto Generated" style="width:100%; font-weight: normal;" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
                                <div class="col-md-12" style="font-weight: normal;">
                                    <input id="Status" type="text" class="form-control" value=""style="width:100%; font-weight: normal;" readonly>
                                </div>
                            </div>
                        </div>

                        <div style="clear:both;"></div>
                        <br>                
                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Member Information</b></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="col-md-12" style="font-weight: normal;">Member No. <span style="color:red;">*</span></label>
                                <div class="col-md-12">
                                    <input id="MemberNo" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-md-12" style="font-weight: normal;">First Name <span style="color:red;">*</span></label>
                                <div class="col-md-12">
                                    <input id="FirstName" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-md-12" style="font-weight: normal;">Last Name <span style="color:red;">*</span></label>
                                <div class="col-md-12">
                                    <input id="LastName" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-md-12" style="font-weight: normal;">Middle Name <span style="color:red;">*</span></label>
                                <div class="col-md-12">
                                    <input id="MiddleName" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-md-12" style="font-weight: normal;">Telephone No.</label>
                                <div class="col-md-12">
                                    <input id="TelNo" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md-12" style="font-weight: normal;">Mobile No. <span style="color:red;">*</span></label>
                                <div class="col-md-12">
                                    <input id="MobileNo" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-md-12" style="font-weight: normal;">Email Address </label>
                                <div class="col-md-12">
                                    <input id="EmailAddress" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md-12" style="font-weight: normal;">Password <span style="font-size:10px; color:red;">(Please remember your password to gain access of your back office)</span></label>
                                <div class="col-md-12">
                                    <input id="Password" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                        </div>

                        <div style="clear:both;"></div>
                        <br>                
                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Address Information</b></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-md-12" style="font-weight: normal;">Address</label>
                                <div class="col-md-12">
                                    <input id="Address" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12" style="font-weight: normal;">City <span style="color:red;">*</span></label>
                                <div class="col-md-12" style="font-weight: normal;">
                                    <select id="City" class="form-control select2" style="width: 100%; height: 100px; font-weight: normal;">
                                        <option value="">Please Select</option>
                                        @foreach ($CountryCityList as $ckey)
                                            <option value="{{ $ckey->CityID }}"
                                                data-cityid="{{$ckey->CityID}}"
                                                data-cityprovince="{{$ckey->Province}}"
                                                data-cityzipcode="{{$ckey->ZipCode}}"
                                                >{{ $ckey->City }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-12" style="font-weight: normal;">State/Province</label>
                                <div class="col-md-12">
                                    <input id="StateProvince" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label class="col-md-12" style="font-weight: normal;">Zip Code</label>
                                <div class="col-md-12">
                                    <input id="ZipCode" type="text" class="form-control" value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <label class="col-md-12" style="font-weight: normal;">Country <span style="color:red;">*</span></label>
                                <div class="col-md-12" style="font-weight: normal;">
                                    <select id="Country" class="form-control select2" style="width: 100%; font-weight: normal;">
                                        <option value="174" selected>Philippines</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                        <br>                
                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-md-12" style="font-size: 15px;"><i class="fa fa-map-marker margin-r-5"></i><b>Code Information</b></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-md-12" style="font-weight: normal;">Code <span style="font-size: 10px; color:red;">*</span></label>
                                <div class="col-md-12">
                                    <input id="Code" type="text" class="form-control"  value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md-12" style="font-weight: normal;">Package <span style="color:red;">*</span></label>
                                <div class="col-md-12" style="font-weight: normal;">
                                    <input id="PackageID" type="hidden" class="form-control" value="" readonly>
                                    <input id="Package" type="test" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md-12" style="font-weight: normal;">Type <span style="color:red;">*</span></label>
                                <div class="col-md-12" style="font-weight: normal;">
                                    <input id="IsFreeCode" type="test" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="col-md-12" style="font-weight: normal;">Sponsor/Owner <span style="color:red;">*</span></label>
                                <div class="col-md-12" style="font-weight: normal;">
                                    <input id="SponsorEntryID" type="hidden" class="form-control" value="" readonly>
                                    <input id="Sponsor" type="text" data-type="SponsorEntryCode" class="form-control autocomplete_txt"  autocomplete="off" value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md-12" style="font-weight: normal;">Please enter the IBO Number of the upline.<span style="font-size: 10px; color:red;">(Please type atleast 3 characters of upline IBO Number)</span></label>
                                <div class="col-md-12">
                                    <input id="ParentEntryID" type="hidden" class="form-control" value="" style="width:100%; font-weight: normal;" readonly>
                                    <input id="ParentEntry" type="text" data-type="ParentEntryCode" class="form-control autocomplete_txt"  autocomplete="off" value="" style="width:100%; font-weight: normal;" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-md-12" style="font-weight: normal;">Position</span></label>
                                <div class="col-md-12 form-group">
                                    <label>
                                      <input type="radio" id="rdoL" name="rdoPosition" value="L" class="flat-green">
                                      <span id="spnRDOLeft">LEFT</span>
                                    </label>
                                    &nbsp&nbsp&nbsp&nbsp&nbsp
                                    <label>
                                      <input type="radio" id="rdoR" name="rdoPosition" value="R" class="flat-green">
                                      <span id="spnRDORight">RIGHT</span>
                                    </label>
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


        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <script type="text/javascript">

        var IsFreeCode = 0;
        
        $("#selLevelNo").change(function(){
            GoToUpline({{ $PARENTMID }});
        });

        function GoToUpline(vParentEntryID){
            $("#MemberEntryID").val(vParentEntryID);
            $("#MaxLevel").val($("#selLevelNo").val());
            $('#frmSubmit').submit();
        };

        function Clearfields(){ 

            IsFreeCode = 0;

            $("#EntryID").val('0');

            $("#EntryCode").val('');
            $("#EntryDateTime").val('');
            $("#Status").val('Active');
            
            $("#MemberID").val('');
            $("#MemberNo").val('');
            $("#FirstName").val('');
            $("#LastName").val('');
            $("#MiddleName").val('');

            $("#TelNo").val('');
            $("#MobileNo").val('');
            $("#EmailAddress").val('');
            $("#Password").val('');

            $("#Address").val('');
            $("#City").val('').change();
            $("#StateProvince").val('');
            $("#ZipCode").val('');
            $("#Country").val(174).change();

            $("#Code").val('');
            $("#PackageID").val('0');
            $("#Package").val('');
            $("#IsFreeCode").val('');
            $("#SponsorEntryID").val('0');
            $("#Sponsor").val('');

            $("#ParentEntryID").val('0');
            $("#ParentEntry").val('');

            $("#spnRDOLeft").text('Left');
            $("#rdoL").prop("checked", false);

            $("#spnRDORight").text('Right');
            $("#rdoR").prop("checked", false);

            $("#FirstName").prop('disabled', false);
            $("#LastName").prop('disabled', false);
            $("#MiddleName").prop('disabled', false);

            $("#TelNo").prop('disabled', false);
            $("#MobileNo").prop('disabled', false);
            $("#EmailAddress").prop('disabled', false);
            $("#Password").prop('disabled', false);

            $("#Address").prop('disabled', false);
            $("#City").prop('disabled', false);
            $("#StateProvince").prop('disabled', false);
            $("#ZipCode").prop('disabled', false);
            $("#Country").prop('disabled', false);

            $("#Code").prop('disabled', false);

            $("#ParentEntry").prop('disabled', true);
            $("#rdoL").prop('disabled', true);
            $("#rdoR").prop('disabled', true);

            $("#divPackage").hide();
            $("#divType").hide();

            $("#btnSave").show();

        }

        function NewRecord(vParentEntryID, vParentEntryCode, vParentEntryFullName, vPosition){
            $.ajax({
                type: "post",
                data: {
                    _token: '{{ $Token }}'
                },
                url: "{{ route('get-member-temp-password') }}",
                dataType: "json",
                success: function(data){
                    Clearfields();
                    $("#Password").val(data.TempPassword);

                    $("#ParentEntryID").val(vParentEntryID);
                    $("#ParentEntry").val(vParentEntryCode + " - " + vParentEntryFullName);

                    if(vPosition == "L"){
                        $("#rdoL").prop("checked", true);
                    }else{
                        $("#rdoR").prop("checked", true);
                    }

                    $("#record-info-modal").modal();
                },
                error: function(data){
                    console.log(data.responseText);
                },
                beforeSend:function(vData){
                }
            });
        }

        $("#City").change(function(){

          if($("#City").find('option:selected').data('cityprovince') != undefined){
            $("#StateProvince").val($("#City").find('option:selected').data('cityprovince'));
          }

          if($("#City").find('option:selected').data('cityzipcode') != undefined){
            $("#ZipCode").val($("#City").find('option:selected').data('cityzipcode'));
          }
     
        });

        function SaveRecord(){

            var strPosition = "";
            if($("#rdoL").is(":checked")){
                strPosition = "L";
            }else if($("#rdoR").is(":checked")){
                strPosition = "R";
            }

            if($('#FirstName').val() == "") {
                showJSMessage("Member Entry","Please enter member first name.","OK");
            }else if($('#LastName').val() == "") {
                showJSMessage("Member Entry","Please enter member last name.","OK");
            }else if($('#FirstName').val() == "") {
                showJSMessage("Member Entry","Please enter member firstname.","OK");
            
            }else if($('#MobileNo').val() == "") {
                showJSMessage("Member Entry","Please enter member active mobile number.","OK");

            }else if($('#City').val() == "") {
                showJSMessage("Member Entry","Please select member city address.","OK");
            }else if($('#Country').val() == "") {
                showJSMessage("Member Entry","Please select member country address.","OK");

            }else if($('#EntryID').val()=="0" && $('#Code').val() == "") {
                showJSMessage("Member Entry","Please enter code.","OK");
            }else if($('#EntryID').val()=="0" &&$('#ParentEntryID').val() == "") {
                showJSMessage("Member Entry","Please select member upline.","OK");
            }else if($('#EntryID').val()=="0" && strPosition == "") {
                showJSMessage("Member Entry","Please select position.","OK");

            }else{

                $.ajax({
                    type: "post",
                    data: {
                        _token: '{{ $Token }}',
                        EntryID: $("#EntryID").val(),

                        MemberID: $("#MemberID").val(),
                        MemberNo: $("#MemberNo").val(),
                        FirstName: $("#FirstName").val(),
                        LastName: $("#LastName").val(),
                        MiddleName: $("#MiddleName").val(),

                        TelNo: $("#TelNo").val(),
                        MobileNo: $("#MobileNo").val(),
                        EmailAddress: $("#EmailAddress").val(),
                        Password: $("#Password").val(),

                        Address: $("#Address").val(),
                        City: $("#City").val(),
                        StateProvince: $("#StateProvince").val(),
                        ZipCode: $("#ZipCode").val(),
                        Country: $("#Country").val(),

                        Status: $("#Status").val(),

                        Code: $("#Code").val(),
                        PackageID: $("#PackageID").val(),
                        IsFreeCode : IsFreeCode,
                        SponsorEntryID: $("#SponsorEntryID").val(),
                        ParentEntryID: $("#ParentEntryID").val(),
                        ParentPosition: strPosition

                    },
                    url: "{{ route('do-save-member-entry') }}",
                    dataType: "json",
                    success: function(data){

                        buttonOneClick("btnSave", "Save", false);
                        if(data.Response =='Success'){
                            $("#record-info-modal").modal('hide');
                            showMessage("Success",data.ResponseMessage);
                            location.reload();
                        }else{
                            showJSModalMessageJS("Save Member Entry",data.ResponseMessage,"OK");
                        }

                    },
                    error: function(data){
                        buttonOneClick("btnSave", "Save", false);
                        console.log(data.responseText);
                    },
                    beforeSend:function(vData){
                        buttonOneClick("btnSave", "", true);
                    }
                });
            }
        };

        //autocomplete script
        $(document).on('focus','.autocomplete_txt',function(){

            if($(this).data('type') == "SponsorEntryCode"){

                $(this).autocomplete({

                    source: function( request, response ) {

                        if(request.term.length >= 3){
                            $.ajax({
                                url: "{{ route('get-member-search-list') }}",
                                dataType: "json",
                                method: 'get',
                                data: {
                                    SearchText: request.term,
                                    PageNo : 1,
                                    Status : "",
                                    IsWithEwallet : 0
                                },
                                success: function( data ) {
                                    response( $.map( data, function( item ) {
                                        var code = item.split("|");
                                        console.log(code);
                                        return {
                                            label: code[1] + " - " + code[2],
                                            value: code[1] + " - " + code[2],
                                            data : item
                                        }
                                    }));
                                },
                                error: function(data){
                                    console.log(data.responseText);
                                }
                            });
                        }
                    },
                    autoFocus: true,
                    minLength: 0,

                    select: function( event, ui ) {

                        var data = ui.item.data.split("|");

                        $('#SponsorEntryID').val(data[0]);
                        $('#Sponsor').val(data[1] + " - " + data[2]);
                    }
                });

            }else if($(this).data('type') == "ParentEntryCode"){

                $(this).autocomplete({

                    source: function( request, response ) {

                        if(request.term.length >= 3){
                            $.ajax({
                                url: "{{ route('get-member-search-list') }}",
                                dataType: "json",
                                method: 'get',
                                data: {
                                    SearchText: request.term,
                                    PageNo : 1,
                                    Status : "",
                                    IsWithEwallet : 0
                                },
                                success: function( data ) {
                                    response( $.map( data, function( item ) {
                                        var code = item.split("|");
                                        console.log(code);
                                        return {
                                            label: code[1] + " - " + code[2],
                                            value: code[1] + " - " + code[2],
                                            data : item
                                        }
                                    }));
                                },
                                error: function(data){
                                    console.log(data.responseText);
                                }
                            });
                        }
                    },
                    autoFocus: true,
                    minLength: 0,

                    select: function( event, ui ) {

                        var data = ui.item.data.split("|");

                        $('#ParentEntryID').val(data[0]);
                        $('#ParentEntry').val(data[1] + " - " + data[2]);

                        var intLeftEntryID = data[6]
                        var intRightEntryID = data[7]

                        if(intLeftEntryID > 0){
                            $("#spnRDOLeft").text('Left (Occupied)');
                            $("#rdoL").prop('disabled', true);
                        }else{
                            $("#spnRDOLeft").text('Left - Available');
                            $("#rdoL").prop('disabled', false);
                        }

                        if(intRightEntryID > 0){
                            $("#spnRDORight").text('Right (Occupied)');
                            $("#rdoR").prop('disabled', true);
                        }else{
                            $("#spnRDORight").text('Right - Available');
                            $("#rdoR").prop('disabled', false);
                        }

                    }
                });

            }

        });

    </script>
    

    
@endsection




