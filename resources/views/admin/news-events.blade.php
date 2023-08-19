@extends('layout.adminweb')

@section('content')

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			News and Events
		</h1>
		<ol class="breadcrumb">
			<li><a href="{{ route('admin-dashboard') }}">Home</a></li>
			<li class="active">News and Events</li>
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
				                  <th>Sort</th>
				                  <th></th>
				                  <th>Title</th>
				                  <th>Created Date/Time</th>
				                  <th>PostedBy</th>
				                  <th>Posted Date</th>
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
		            <h4 class="modal-title"  style="text-align:center; color:white;"><span><b>News/Events Information</b></span></h4>
	          	</div>

				<form method="post" action="{{URL('do-save-news-events')}}" enctype="multipart/form-data">

	          	<div class="modal-body">

	                {!! csrf_field() !!}   <!--Token -->

	        		<input type="hidden" id="RecordID" name="RecordID" value="0" readonly>
		            <div class="row">
		            	<div class="col-md-9">
			                <label class="col-md-12" style="font-weight: normal;">Title</label>
			                <div class="col-md-12">
			                    <input id="Title" name="Title" type="text" class="form-control" value="" placeholder="Title" style="width:100%; font-weight:normal;" required>
			                </div>
		            	</div>
		            	<div class="col-md-3">
			                <label class="col-md-12" style="font-weight: normal;">Status <span style="color:red;">*</span></label>
			                <div class="col-md-12">
								<select id="Status" name="Status" class="form-control select2" style="width: 100%; font-weight: normal;" required>
									<option value="{{ config('app.STATUS_PUBLISHED') }}" selected>{{ config('app.STATUS_PUBLISHED') }}</option>
									<option value="{{ config('app.STATUS_UNPUBLISHED') }}">{{ config('app.STATUS_UNPUBLISHED') }}</option>
									<option value="{{ config('app.STATUS_CANCELLED') }}">{{ config('app.STATUS_CANCELLED') }}</option>
								</select>
			                </div>
		            	</div>
		            </div>
		            <div style="clear:both;"></div>
		            <br>
		            <div class="row">
		            	<div class="col-md-12">
			            	<div class="col-md-12">
		                       	<p class="help-block">
		                         	<br>Accepts jpg &amp; png image type with max [2048KB] file size,
		                        	<br><span style="color:red;"> <b>Best Quality fit for product image dimension is 500x500</b></span>
		                        </p>
		                        <input type="file" accept="image/*"  name="imgPhoto[]" onchange="loadFile(event)">
		                        <br>
		                        <div class="file-preview-frame">
		                            <!--Product Image Display Here  -->
		                            <img id="output" src="{{ asset(config('app.src_name') . 'img/newsevents/no-image-300x300.jpg') }}" style="max-width: 500px;" />
		                       	</div>
		                   	</div>
	                   	</div>
	            	</div>
		            <div style="clear:both;"></div>
		            <br>
		            <div class="row">
		            	<div class="col-md-12">
			                <label class="col-md-12" style="font-weight: normal;">Contents <span style="color:red;">*</span></label>
			                <div class="col-md-12" style="font-weight: normal;">
                				<textarea id="Contents" name="Contents" class="form-control wysiwyg" cols="40" rows="8"  placeholder="Contents" style="width:100%;"></textarea>
			                </div>
		            	</div>
		        	</div>
		            <div style="clear:both;"></div>
		            <br>                
		            <div class="row">
		            	<div class="col-md-8">
			                <label class="col-md-12" style="font-weight: normal;">Posted By</label>
			                <div class="col-md-12">
			                    <input id="PostedBy" name="PostedBy" type="text" class="form-control" value="" placeholder="Posted By" style="width:100%; font-weight:bold;" required>
			                </div>
		            	</div>
		            	<div class="col-md-4">
			                <label class="col-md-12" style="font-weight: normal;">Publish Date <span style="color:red;">*</span></label>
			            	<div class="col-md-12">
	                            <div class='input-group date' id='divPublishDate'>
	                                <input id="PublishDate" name="PublishDate" type='text' class="form-control" readonly />
	                                <span class="input-group-addon">
	                                    <span class="glyphicon glyphicon-calendar"></span>
	                                </span>
	                            </div>
                            </div>
		            	</div>
		            </div>

		            <div style="clear:both;"></div>
		            <br>    

		            <div class="modal-footer">
						<div class="input-group pull-right">
							<span class="input-group-btn">
								<button id="btnSave" type="submit" class="btn btn-info btn-flat">
									<i class="fa fa-save"></i> Save
								</button>
								<a href="#" class="btn btn-danger btn-flat" data-dismiss="modal" aria-label="Close"><i class="fa fa-remove"></i> Cancel</a>
							</span>
						</div>	
		            </div>
	          	</div>
				</form>

		    </div><!-- /.modal-content -->
  		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<script type="text/javascript">

	    var isNewRecord = 0;
	    var intCurrentPage = 1;

		var isPageFirstLoad = true;

	    $(document).ready(function() {

            $('#divPublishDate').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

	        $('#tblList').DataTable( {
				"columnDefs": [
				            {
				                "targets": [ 0 ],
				                "visible": false,
				                "searchable": false
				            },
				            {
				                "targets": [ 1 ],
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
	            "order": [[ 1, "asc" ], [ 5, "desc" ]]
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
				url: "{{ route('get-news-events-list') }}",
				dataType: "json",
				success: function(data){
					LoadRecordList(data.NewsEventsList);
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

	    	tdID = vData.RecordID;
	    	tdSortOption = vData.SortOption;
			tdOption = " <div class='input-group-btn' style='padding-left:10px; float:left; display: inline-block;'>" +
	                        " <button type='button' class='btn' data-toggle='dropdown' style='background-color:#dfb407;'> " +
	                        	" <span class='fa fa-caret-down'></span> " +
	                        "</button> " +
	                        "<ul class='dropdown-menu'> ";
		                  	tdOption += " <li style='text-align:left;'> " +
		                      	" <a href='#' onclick='EditRecord(" + vData.RecordID + "," + (vData.Status != '{{ config('app.STATUS_CANCELLED') }}' && vData.Status != '{{ config('app.STATUS_PUBLISHED') }}' ? true : false) + ")'>" + 
		                      		" <strong><i class='fa fa-info-circle font-size:15px;'></i> " + (vData.Status != '{{ config('app.STATUS_CANCELLED') }}' && vData.Status != '{{ config('app.STATUS_PUBLISHED') }}' ? "Edit Record" : "View Record") + "</strong>" +
		                      	" </a> " +
		                   	" </li> ";
            tdOption += " </ul> " +
	                    " </div> " ;

			tdTitle = "<span style='font-weight:normal;'>" + vData.Title + "</span>";
			tdDateTimeCreated = "<span style='font-weight:normal;'>" + vData.DateTimeCreated + "</span>";
			tdPostedBy = "<span style='font-weight:normal;'>" + vData.PostedBy + "</span>";
			tdPublishDate = "<span style='font-weight:normal;'>" + vData.PublishDate + "</span>";

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
			    if(rowData[0] == vData.RecordID){
					IsRecordExist = true;

			    	curData = tblList.row(rowIdx).data();
			    	curData[0] = tdID;
			    	curData[1] = tdSortOption;
			    	curData[2] = tdOption;
			    	curData[3] = tdTitle;
			    	curData[4] = tdDateTimeCreated;
			    	curData[5] = tdPostedBy;
			    	curData[6] = tdPublishDate;
			    	curData[7] = tdStatus;
			    	tblList.row(rowIdx).data(curData).invalidate().draw();
			    }

			});

			if(!IsRecordExist){
		    	//New Row
				var rowNode = tblList.row.add([
						tdID,
						tdSortOption,
						tdOption,
						tdTitle,
						tdDateTimeCreated, 
						tdPostedBy,
						tdPublishDate, 
						tdStatus
					]).draw().node();			
			}

	    }

	    function Clearfields(){

			$("#RecordID").val('0');
			$("#Title").val('');
			$("#Status").val('{{ config('app.STATUS_UNPUBLISHED') }}').change();
			window.parent.tinymce.get('Contents').setContent('');

			$("#output").attr("src","{{ asset(config('app.src_name') . 'img/newsevents/no-image-300x300.jpg') }}");

			$("#PostedBy").val('');
			$("#PublishDate").val('');

			$("#Status").prop('disabled', false);
			$("#Title").prop('disabled', false);
			$("#Contents").prop('disabled', false);

			$("#PostedBy").prop('disabled', false);
			$("#PublishDate").prop('disabled', false);
			
			$("#btnSave").show();

	    }

	    function NewRecord(){
	    	isNewRecord = 1;
			Clearfields();
			$("#record-info-modal").modal();
	    }

	    function EditRecord(vRecordID, vIsEditable){

	    	if(vRecordID > 0){
	    		isNewRecord = 0;
				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						RecordID: vRecordID
					},
					url: "{{ route('get-news-events-info') }}",
					dataType: "json",
					success: function(data){
				        $("#divLoader").hide();
						buttonOneClick("btnSave", "Save", false);

						if(data.Response =='Success' && data.NewsEventsInfo != undefined){
							
							Clearfields();
							
							$("#RecordID").val(data.NewsEventsInfo.RecordID);
							$("#Title").val(data.NewsEventsInfo.Title);
							$("#Status").val(data.NewsEventsInfo.Status).change();

							$("#output").attr("src","{{ asset(config('app.src_name') . 'img/newsevents') }}/" + data.NewsEventsInfo.RecordID + "-1-300x300.jpg");

							window.parent.tinymce.get('Contents').setContent(data.NewsEventsInfo.Contents);

							$("#PostedBy").val(data.NewsEventsInfo.PostedBy);
							$("#PublishDate").val(data.NewsEventsInfo.PublishDate);

							$("#Title").prop('disabled', !vIsEditable);
							$("#Status").prop('disabled', !vIsEditable);		
							$("#Contents").prop('disabled', !vIsEditable);
							$("#PostedBy").prop('disabled', !vIsEditable);
							$("#PublishDate").prop('disabled', !vIsEditable);

							if(vIsEditable){
								$("#btnSave").show();
							}else{
								$("#btnSave").hide();
							}

							$("#record-info-modal").modal();

						}else{
							showJSModalMessageJS("News/Events Information",data.ResponseMessage,"OK");
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

			if($('#Title').val() == "") {
				showJSMessage("Title","Please enter title of this article.","OK");
			}else if($('#Status').val() == "") {
				showJSMessage("Status","Please select status of this article.","OK");
			}else if(window.parent.tinymce.get('Contents').getContent() == "") {
				showJSMessage("Contents","Please enter contents of the article.","OK");
			}else if($('#PostedBy').val() == "") {
				showJSMessage("Posted By","Please set who posted this article.","OK");
			}else if($('#PublishDate').val() == "") {
				showJSMessage("Publish Date","Please set the publication date of this article.","OK");
			}else{

				$.ajax({
					type: "post",
					data: {
						_token: '{{ $Token }}',
						RecordID: $("#RecordID").val(),
						Title: $("#Title").val(),
						Contents: window.parent.tinymce.get('Contents').getContent(),
						PostedBy: $("#PostedBy").val(),
						PublishDate: $("#PublishDate").val(),
						Status: $("#Status").val()
					},
					url: "{{ route('do-save-news-events') }}",
					dataType: "json",
					success: function(data){

				        $("#divLoader").hide();
						buttonOneClick("btnSave", "Save", false);
						if(data.Response =='Success'){
							$("#record-info-modal").modal('hide');
							showMessage("Success",data.ResponseMessage);
							LoadRecordRow(data.NewsEventsInfo);
						}else{
							showJSModalMessageJS("Save News/Events",data.ResponseMessage,"OK");
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



