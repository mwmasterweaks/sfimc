@extends('layout.adminweb')

@section('content')

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Frequently Ask Question
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li class="active">FAQ</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
	          	<div class="box box-theme">
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
				                  <th>Sort Order</th>
				                  <th>FAQ</th>
				                  <th>Answer</th>
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
		            <h4 class="modal-title"  style="text-align:center; color:white;"><span><b>FAQ Information</b></span></h4>
	          	</div>
	          	<div class="modal-body">
	        		<input type="hidden" id="FAQID" value="0" readonly>
		            <div class="row">
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Sort Order</label>
			                <div class="col-md-12">
			                    <input id="SortOrder" type="text" class="form-control numberonly" value="" placeholder="Sort Order" style="width:100%; font-weight:bold;" required>
			                </div>
		            	</div>
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
			                <div class="col-md-12">
								<select id="Status" class="form-control select2" style="width: 100%; font-weight: normal;">
									<option value="{{ config('app.STATUS_ACTIVE') }}" selected>{{ config('app.STATUS_ACTIVE') }}</option>
									<option value="{{ config('app.STATUS_INACTIVE') }}">{{ config('app.STATUS_INACTIVE') }}</option>
								</select>
			                </div>
		            	</div>
		            </div>
		            <div style="clear:both;"></div>
		            <br>
		            <div class="row">
		            	<div class="col-md-12">
			                <label class="col-md-12" style="font-weight: normal;">FAQ <span style="color:red;">*</span></label>
			                <div class="col-md-12" style="font-weight: normal;">
                				<textarea id="FAQ" class="form-control wysiwyg" cols="40" rows="8"  placeholder="FAQ" style="width:100%;" required></textarea>
			                </div>
		            	</div>
		        	</div>
		            <div style="clear:both;"></div>
		            <br>
		            <div class="row">
		            	<div class="col-md-12">
			                <label class="col-md-12" style="font-weight: normal;">FAQ Answer<span style="color:red;">*</span></label>
			                <div class="col-md-12" style="font-weight: normal;">
                				<textarea id="FAQAnswer" class="form-control wysiwyg" cols="40" rows="8"  placeholder="Answer" style="width:100%;" required></textarea>
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
	            "order": [[ 2, "asc" ], [ 3, "desc" ]]
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
				url: "{{ route('get-faq-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.FAQList);
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

	    	tdID = vData.FAQID;
			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> ";
		                  	tdOption += " <li style='text-align:left;'> " +
		                      	" <a href='#' onclick='EditRecord(" + vData.FAQID + "," + true + ")'>" + 
		                      		" <strong><i class='fa fa-info-circle font-size:15px;'></i> Edit Record</strong>" +
		                      	" </a> " +
		                   	" </li> ";
            tdOption += " </ul> " +
	                    " </div> " ;
	    	tdSortOrder = "<span style='font-weight:normal;'>" + vData.SortOrder + "</span>";
			tdFAQ = "<span style='font-weight:normal;'>" + vData.FAQ + "</span>";
			tdFAQAnswer = "<span style='font-weight:normal;'>" + vData.FAQAnswer + "</span>";

			tdStatus = "";
			if(vData.Status == "{{ config('app.STATUS_UNPUBLISHED') }}"){
				tdStatus += "<span class='label label-warning' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}else if(vData.Status == "{{ config('app.STATUS_PUBLISHED') }}"){
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#f308e0;'>" + vData.Status + "</span>";
			}else if(vData.Status == "{{ config('app.STATUS_CANCELLED') }}"){
				tdStatus += "<span class='label' style='font-weight:normal; text-align:center; background-color:#ff0606;'>" + vData.Status + "</span>";
			}else{
				tdStatus += "<span class='label label-success' style='font-weight:normal; text-align:center;'>" + vData.Status + "</span>";
			}

			//Check if record already listed
			var IsRecordExist = false;
			tblList.rows().every(function ( rowIdx, tableLoop, rowLoop ) {

			    var rowData = this.data();
			    if(rowData[0] == vData.FAQID){
					IsRecordExist = true;

			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdOption;
			    	curData[2] = tdSortOrder;
			    	curData[3] = tdFAQ;
			    	curData[4] = tdFAQAnswer;
			    	curData[5] = tdStatus;
			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				var rowNode = tblList.row.add([
						tdID,
						tdOption,
						tdSortOrder,
						tdFAQ,
						tdFAQAnswer, 
						tdStatus
					]).draw().node();			
			}

	    }

	    function Clearfields(){

			$("#FAQID").val('0');
			$("#SortOrder").val('0');
			$("#Status").val('{{ config('app.STATUS_ACTIVE') }}').change();

			window.parent.tinymce.get('FAQ').setContent('');
			window.parent.tinymce.get('FAQAnswer').setContent('');

			$("#SortOrder").prop('disabled', false);
			$("#Status").prop('disabled', false);
			$("#FAQ").prop('disabled', false);
			$("#FAQAnswer").prop('disabled', false);
		
			$("#btnSave").show();

	    }

	    function NewRecord(){
	    	isNewRecord = 1;
			Clearfields();
			$("#record-info-modal").modal();
	    }

	    function EditRecord(vFAQID, vIsEditable){

	    	if(vFAQID > 0){
	    		isNewRecord = 0;
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						FAQID: vFAQID
					},
					url: "{{ route('get-faq-info') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnSave", "Save", false);

						if(data.Response =='Success' && data.FAQInfo != undefined){
							
							Clearfields();
							
							$("#FAQID").val(data.FAQInfo.FAQID);

							$("#SortOrder").val(data.FAQInfo.SortOrder);
							$("#Status").val(data.FAQInfo.Status).change();

							window.parent.tinymce.get('FAQ').setContent(data.FAQInfo.FAQ);
							window.parent.tinymce.get('FAQAnswer').setContent(data.FAQInfo.FAQAnswer);

							$("#SortOrder").prop('disabled', !vIsEditable);
							$("#Status").prop('disabled', !vIsEditable);		
							$("#FAQ").prop('disabled', !vIsEditable);
							$("#FAQAnswer").prop('disabled', !vIsEditable);

							if(vIsEditable){
								$("#btnSave").show();
							}else{
								$("#btnSave").hide();
							}

							$("#record-info-modal").modal();

						}else{
							showJSModalMessageJS("FAQ Information",data.ResponseMessage,"OK");
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

	    	var SortOrder = 0;
    		if($('#SortOrder').length){
    			if($("#SortOrder").val() != ""){
		            var strSortOrder = $("#SortOrder").val();
		            SortOrder = parseFloat(strSortOrder.replace(",",""));
    			}
			}

			if(SortOrder == 0) {
				showJSMessage("Sort Order","Please set sort order of this FAQ.","OK");
			}else if(window.parent.tinymce.get('FAQ').getContent() == "") {
				showJSMessage("FAQ","Please enter FAQ content.","OK");
			}else if(window.parent.tinymce.get('FAQAnswer').getContent() == "") {
				showJSMessage("FAQ Answer","Please enter FAQ answer.","OK");
			}else if($('#Status').val() == "") {
				showJSMessage("Status","Please select status of this article.","OK");
			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						FAQID: $("#FAQID").val(),
						SortOrder: SortOrder,
						FAQ: window.parent.tinymce.get('FAQ').getContent(),
						FAQAnswer: window.parent.tinymce.get('FAQAnswer').getContent(),
						Status: $("#Status").val()
					},
					url: "{{ route('do-save-faq') }}",
					dataType: "json",
					success: function(data){

				        $("#divLoader").hide();
						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.FAQInfo);
						}else{
							showJSModalMessageJS("Save FAQ",data.ResponseMessage,"OK");
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



