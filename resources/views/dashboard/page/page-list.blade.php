@extends('dashboard.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('main_content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
	<!-- BEGIN:: SUBHEADER SECTION -->
	<div class="m-subheader ">
		<div class="d-flex align-items-center">
			<div class="mr-auto col-md-12">
				<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
					<li class="m-nav__item m-nav__item--home">
					<a href="{{url('/')}}" class="m-nav__link m-nav__link--icon">
							<i class="m-nav__link-icon la la-home"></i>
							<span class="m-nav__link-text">
								Dashboard
							</span>
						</a>
                    </li>
                    <li class="m-nav__item">
						<i class="la la-angle-double-right"></i>
                        <span class="m-nav__link-text">
                            <i class="fas {{$page_icon}}"></i> {{$page_title}}
                        </span>
					</li>
                </ul> 
			</div>
		</div>
	</div>
    <!-- END:: SUBHEADER SECTION -->

    <!-- BEGIN:: MAIN CONTENT SECTION -->
	<div class="m-content">
        <div class="row">
            <div class="col-xl-12">
                <div class="m-portlet m-portlet--creative m-portlet--bordered-semi">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                    <span><i class="fas {{$page_icon}}"></i> {{$page_title}}</span>
                                </h2>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                    @if(in_array('Page Add',session()->get('permission')))
									<button type="button" class="btn add-btn" id="add_page">
                                        <i class="fas fa-plus-square"></i>
                                        Add New
                                    </button>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <!-- BEGIN:: DATATABLE SEARCH DATA FORM SECTION -->
                        <form method="POST" id="form-filter" class="m-form m-form--fit m--margin-bottom-20" role="form">
							{{ csrf_field() }}
							<div class="row m--margin-bottom-20">
                                <div class="col-lg-5 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Page Name:</label>
                                    <input type="text" class="form-control" name="page_name" id="page_name" placeholder="Enter page name"/>
                                </div>
                            
                                <div class="col-lg-5 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Menu Name:</label>
                                    <input type="text" class="form-control" name="menu_name" id="menu_name" placeholder="Enter menu name"/>
                                </div>

                                <div class="col-lg-2 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label></label>
                                    <div style="margin-top: 7px;">
                                        <button  id="btn-reset" class="btn btn-danger dim reset-btn pull-right" data-toggle="tooltip" data-placement="top" title="" data-original-title="Reset" type="button"><i class="fas fa-refresh"></i></button>
                                        <button  id="btn-filter"  class="btn dim search-btn pull-right" data-toggle="tooltip" data-placement="top" title="" data-original-title="Search" type="button"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END:: DATATABLE SEARCH DATA FORM SECTION -->

                        <!-- BEGIN:: PAGE TABLE SECTION -->
                        <div class="table-responsive">
                            <table class="table table-striped- table-bordered table-hover table-checkable pageTable" id="pageTable">
                                <thead>
                                    <tr>
                                        <th>SR</th>
                                        <th>Page Name</th>
                                        <th>Menu Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- END:: PAGE TABLE SECTION -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END:: MAIN CONTENT SECTION -->

    <!-- BEGIN:: PAGE ADD/EDIT MODAL FORM -->
    <div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form method="POST" id="page_form">
                    <div class="modal-body">
                    
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="page_id" />
                        <div class="form-group">
                            <label for="page_name" class="form-control-label">
                                Page name:
                            </label>
                            <input type="text" name="page_name" class="form-control" id="page_name" placeholder="Enter page name">
                            <div class="error error_page_name text-danger"></div>
                        </div>   
                        <div class="form-group">
                            <label for="menu_id" class="form-control-label">
                                Menu:
                            </label>
                            <select class="form-control chosen-select" name="menu_id" id="menu_id">
                                <option value="">Please Select</option>
                                @if (!empty($menuList))
                                     @foreach ($menuList as $menu)
                                        <option value="{{$menu->id}}">{{$menu->menu_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="error error_menu_id text-danger"></div>
                        </div>                                                                   
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-close" data-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary" id="save-btn"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END:: PAGE ADD/EDIT MODAL FORM -->
</div>

<!-- BEGIN:: JS SCRIPT SECTION -->
<script>
var table;
var save_method;
$(document).ready(function() {

    /** BEGIN:: DATATABLE SERVER SIDE CODE **/
    var table = $('#pageTable').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "dom": 'lTgBfrtip',
        "buttons": [
            'colvis',
            {
                "extend": 'csv',
                "title": 'Page List',
                "exportOptions": {
                    "columns": [0,1,2]
                }
            },
            {
                "extend": 'excel', 
                "title": 'Page List',
                "exportOptions": {
                    "columns": [0,1,2]
                }
            },
            {
                "extend": 'pdf',
                "title": 'Page List',
                "exportOptions": {
                    "columns": [0,1,2]
                }
            },

            {
                "extend": 'print',
                "title": 'Page List',
                "exportOptions": {
                    "columns": [0,1,2]
                },
                customize: function (win) {
                //$(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '10px');

                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
                }
            }
        ],
        "responsive": false, //TO MAKE DATATABLE RESPONSIVE
        "bInfo": true, //WILL SHOW THE TOTAL COUNT NUMBER OF DATA
        "bFilter": false, //THIS FOR DATATABLE DEFAULT SEARCH BOX
        "lengthMenu": [
            [5, 10, 15, 25, 50, 100, -1],
            [5, 10, 15, 25, 50, 100, "All"]
        ],
        "pageLength": 25, //HOW MANY DATA SHOW PER PAGE
        "language": {
            processing: '<img class="loading-image" src="<?php echo asset("public/dashboard-assets/img/loading.svg"); ?>" />',
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '<strong class="text-danger">No Data Found</strong>',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>',
        },

        // Load data for the table's content from an Ajax source//
        "ajax": {
            "url": "{{url('/page-list')}}",
            "type": "POST",
            "data": function(data) {
                data.page_name = $('#page_name').val();
                data.menu_name = $('#menu_name').val();
                data._token    = "{{csrf_token()}}";
            }
        },

        "fnDrawCallback": function() {
			//BEGIN: ON CLICK ADD PAGE BUTTON & SHOW ADD MENU MODAL CODE
            $('#add_page').click(function(){
                save_method = 'add';
                $('#page_form')[0].reset();
                $("#page_form #menu_id").val('').trigger("chosen:updated");
                $('#page_id').val('');
                $(".error").each(function () {
                    $(this).empty();
                });

                $('#pageModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                });
                $('.modal-title').html('<i class="fas fa-plus-square"></i> <span>Add New page</span>');
                $('#save-btn').text('Save');
            });
            //END: ON CLICK ADD PAGE BUTTON & SHOW ADD MENU MODAL CODE

            //BEGIN: ON CLICK EDIT PAGE BUTTON FETCH PAGE DATA AND SHOW IT IN EDIT MODAL FORM CODE
            $('.edit_page').click(function(){
                var id = $(this).data('id');
                var url = "/page/"+id+"/edit";
                save_method = 'update';
                $('#page_form')[0].reset(); // reset form on modals

                $(".error").each(function () {
                    $(this).empty();
                });

                //Ajax Load data from ajax
                $.ajax({
                    url: "{{url('')}}" + url ,
                    type: "GET",
                    dataType: "JSON",
                    success: function (data)
                    {
                        console.log(data.page);
                        $('#page_form #page_id').val(data.page.id);
                        $('#page_form #page_name').val(data.page.page_name);
                        $('#page_form #menu_id').val(data.page.menu_id);
                        $('#page_form #menu_id').trigger("chosen:updated");                                     
                        
                        $('#pageModal').modal('show');
                        $('.modal-title').html('<i class="fas fa-edit"></i> <span>Edit Page</span>');
                        $('#save-btn').text('Update');
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error get data from ajax');
                    }
                });
            });
            //END: ON CLICK EDIT PAGE BUTTON FETCH PAGE DATA AND SHOW IT IN EDIT MODAL FORM CODE

            //BEGIN: DELETE PAGE DATA CODE
            $('.delete_page').click(function(){
                var id = $(this).data('id');
                swal({
                    title: 'Are you sure?',
                    text: "It will be deleted permanently!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'btn btn-success',
                    cancelButtonColor: 'btn btn-danger',
                    confirmButtonText: 'Yes, delete it!',
                    showLoaderOnConfirm: true,
                        
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            
                            $.ajax({
                            url: '{{ url("/page/delete") }}/'+id,
                            type: 'GET',
                            dataType: 'json'
                            })
                            .done(function(response){
                                if(response.status == 'success'){
                                    swal({title: "Deleted!", text: response.message,type: "success"}
                                    ).then(function(){ 
                                        table.ajax.reload();
                                        }
                                    );
                                    // setInterval( function () {
                                    //     location.reload();
                                    // }, 3000 );
                                }else if(response.status == 'error'){
                                    swal('Error deleting!', response.message, 'error');
                                }

                                
                            })
                            .fail(function(){
                                swal('Oops...', 'Something went wrong with ajax !', 'error');
                            });
                        });
                    },
                    allowOutsideClick: false			  
                });	
            });
             //END: DELETE PAGE DATA CODE
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [3], //first column / numbering column
                "orderable": false, //set not orderable
            },
            {
                "targets": 3,
                "className": "text-center",
            }
         ],

    });
    /** END:: DATATABLE SERVER SIDE CODE **/

    /** BEGIN:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/
    $("#form-filter input,#form-filter select").keyup(function(e) {
        if (e.which == 13) {
            $('button[name="search"]').trigger('click');
        }
    });

    $('#btn-filter').click(function() {
        table.ajax.reload();
    });

    $('#btn-reset').click(function() {
        $('#form-filter')[0].reset();
        table.ajax.reload();
    });

    /** END:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/

    /** BEGIN:: PAGE DATA ADD/UPDATE AJAX CODE **/
    $('#save-btn').click(function(){
        var url;
        if (save_method == 'add')
        {
            url = "{{url('/page')}}";
        } else
        {
            url = "{{url('/page/update')}}";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#page_form').serialize(),
            dataType: "JSON",
            beforeSend: function ()
            {
            $('.ajax_loading').show();
            },
            success: function (data)
            {
                if (data.success) {              
                    toastr.options = {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": true,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };
                    toastr.success(data.success,"SUCCESS");
                    
                    $('#pageModal').modal('hide');
                    $('.ajax_loading').hide();
                    table.ajax.reload();
                } else
                {
                
                    $("#pageModal").find('.error').text(''); 
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('.form-group').find('.error_'+key).text(value); 
                    });

                    $('.ajax_loading').hide();
                }


            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                console.log(jqXHR+' '+textStatus+' '+errorThrown);
                alert('Error adding / update data');
            }
        });
    });
    /** END:: PAGE DATA ADD/UPDATE AJAX CODE **/

    /** BEGIN:: MAKE PAGE PRIMARY CHECKBOX BEHAVIOUR AS RADIO BUTTON CODE **/
    $(".is_primary").change(function()
    {
        $(".is_primary").prop('checked',false);
        $(this).prop('checked',true);

    });
    /** END:: MAKE PAGE PRIMARY CHECKBOX BEHAVIOUR AS RADIO BUTTON CODE **/
});
</script>
<!-- END:: JS SCRIPT SECTION -->

@endsection

