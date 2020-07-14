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
                        <a href="{{url('/')}}" class="m-nav__link m-nav__link--cover_image">
                            <i class="m-nav__link-cover_image la la-home"></i>
                            <span class="m-nav__link-text">
                                Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="m-nav__item">
                        <i class="la la-angle-double-right"></i>
                        <span class="m-nav__link-text">
                        <i class="{{$page_icon}}"></i> {{$page_title}}
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
                                    <span><i class="{{$page_icon}}"></i> {{$page_title}}</span>
                                </h2>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                     @if(in_array('Website Page Add',session()->get('permission')))
                                    <button type="button" class="btn add-btn" id="add_data">
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
                                <div class="col-lg-6 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Page Name:</label>
                                    <input type="text" class="form-control" name="pg_name" id="pg_name" placeholder="Enter page name" />
                                </div>

                                <div class="col-lg-6 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label></label>
                                    <div style="margin-top: 7px;">
                                        
                                        <button id="btn-reset" class="btn btn-danger  dim reset-btn pull-right" data-toggle="tooltip"
                                            data-placement="top" title="" data-original-title="Reset" type="reset"><i
                                                class="fas fa-refresh"></i></button>
                                                
                                        <button id="btn-filter" class="btn dim search-btn pull-right" data-toggle="tooltip"
                                            data-placement="top" title="" data-original-title="Search" type="button"><i
                                                class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END:: DATATABLE SEARCH DATA FORM SECTION -->

                        <!-- BEGIN:: MENU TABLE SECTION -->
                        <div class="table-responsive">
                            <table class="table table-striped- table-bordered table-hover table-checkable" id="websitePageTable">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Page Name</th>
                                        <th>Page Url</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- END:: MENU TABLE SECTION -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END:: MAIN CONTENT SECTION -->

    <!-- BEGIN:: ADD AND EDIT MODAL FORM -->
    <div class="modal fade" id="websitePageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form method="POST" id="websitePageForm">
                    <div class="modal-body">
                        <div class="form-group m-form__group row">
                            @csrf
                            <input type="hidden" name="id" id="page_id" />
                            <div class="col-lg-12 pb-15">
                                <label for="page_name" class="form-control-label">
                                   Page Name:
                                </label>
                                <input type="text" name="page_name" class="form-control" id="page_name">
                                <span class="error error_page_name text-danger"></span>
                            </div>
                            <div class="col-lg-12 pb-15">
                                <label for="page_url" class="form-control-label">
                                    Page URL:
                                </label>
                                <input type="text" name="page_url" class="form-control" id="page_url" readonly>
                                <span class="error error_page_url text-danger"></span>
                            </div>
                            <div class="col-lg-12 pb-15">
                                <label for="page_content" class="form-control-label">
                                    Page Content:
                                </label>
                                <textarea class="form-control m-input col-md-7" name="page_content" id="page_content"></textarea>                                   
                                <span class="error error_page_content text-danger"></span>
                            </div> 
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-close" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="save-btn">
                            
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END:: ADD AND EDIT MODAL FORM -->

</div>



<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>
var table;

$(document).ready(function () {
    CKEDITOR.replace('page_content');
    $("#page_name").keyup(function(event) {
        var value = this.value.toLowerCase();
        var str = value.replace(/ +(?= )/g,'');
        var name =str.split(' ').join('-')
        $("#page_url").val(name);
    });
    $("#page_name").focusout(function(event) {
        var value = this.value.toLowerCase();
        var str = value.replace(/ +(?= )/g,'');
        var name =str.split(' ').join('-')
        $("#page_url").val(name);
    });

    /** BEGIN:: DATATABLE SERVER SIDE CODE **/
    var table = $('#websitePageTable').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "dom": 'lTgBfrtip',
        "buttons": [
            'colvis',
            {
                "extend": 'csv',
                "title": '{{$page_title}}',
                "exportOptions": {
                    "columns": [0,1,2]
                    
                }
            },
            {
                "extend": 'excel',
                "title": '{{$page_title}}',
                "exportOptions": {
                   "columns": [0,1,2]
                }
            },
            {
                "extend": 'pdf',
                "title": '{{$page_title}}',
                "exportOptions": {
                    "columns": [0,1,2]
                }
            },

            {
                "extend": 'print',
                "title": '{{$page_title}}',
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
        "responsive": false,
        "bInfo": true,
        "bFilter": false,
        "lengthMenu": [
            [5, 10, 15, 25, 50, 100, -1],
            [5, 10, 15, 25, 50, 100, "All"]
        ],
        "pageLength": 25,
        "language": {
            processing: '<img class="loading-image" src="<?php echo asset("public/dashboard-assets/img/loading.svg"); ?>" />',
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>',
        },

        // Load data for the table's content from an Ajax source//
        "ajax": {
            "url": "{{url('/website-page-list')}}",
            "type": "POST",
            "data": function (data) {
                data.pg_name = $('#pg_name').val();
                data._token   = "{{csrf_token()}}";
            }
        },

        "fnDrawCallback": function () {
            

            //BEGIN: ON CLICK ADD BUTTON & SHOW ADD MODAL CODE
            $('#add_data').click(function () {
                save_method = 'add';

                $('#websitePageForm')[0].reset();
                $('#page_id').val('');
                CKEDITOR.instances.page_content.setData('');
                $(".error").each(function () {
                    $(this).empty();
                });

                $('#websitePageModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                })
                $('.modal-title').html('<i class="fas fa-plus-square"></i> <span>Add New Page</span>');
                $('#save-btn').text('Save');
            });
            //END: ON CLICK ADD BUTTON & SHOW ADD MODAL CODE

            //BEGIN: ON CLICK EDIT BUTTON FETCH DATA AND SHOW IT IN EDIT MODAL FORM CODE
            $('.edit_data').click(function () {
                var id = $(this).data('id');
                var url = "website-page/" + id + "/edit";
                $('#websitePageForm')[0].reset(); // reset form on modals
                $(".error").each(function () {
                    $(this).empty();
                });

                //Ajax Load data from ajax
                $.ajax({
                    url: "{{url('')}}/"+url,
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) {

                        $('#websitePageForm #page_id').val(data.page.id);
                        $('#websitePageForm #page_name').val(data.page.page_name);
                        $('#websitePageForm #page_url').val(data.page.page_url);
                        if(data.page.page_content){
                            // tinyMCE.get('description').setContent(data.post.description);
                            CKEDITOR.instances.page_content.setData(data.page.page_content);
                        }else{
                            CKEDITOR.instances.page_content.setData('');
                        }
                        $('#websitePageModal').modal('show');
                        $('.modal-title').html('<i class="fas fa-edit"></i> <span>Edit Page Data</span>');
                        $('#save-btn').text('Update');

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            });
            //END: ON CLICK EDIT USER BUTTON FETCH USER DATA AND SHOW IT IN EDIT MODAL FORM CODE       

            //BEGIN: DELETE USER DATA AJAX CODE
            $('.delete_data').on('click', function(){
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
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                            url: '{{ url("website-page/delete") }}',
                            type: 'POST',
                            data:{id:id},
                            dataType: 'json'
                            })
                            .done(function(response){
                                if(response.status == 'success'){
                                    swal({title: "Deleted!", text: response.message,type: "success"}
                                    ).then(function(){ 
                                        table.ajax.reload();
                                        }
                                    );

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
            //END: DELETE USER DATA AJAX CODE
            
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {

                "targets": [3], //first column / numbering column
                "orderable": false, //set not orderable
                 "className": "text-center",
            }, 
            

        ],
    });
    /** END:: DATATABLE SERVER SIDE CODE **/

    /** BEGIN:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/
    $("#form-filter input,#form-filter select").keyup(function (e) {
        if (e.which == 13) {
            $('button[name="search"]').trigger('click');
        }
    });

    $('#btn-filter').click(function () {
        table.ajax.reload();
    });

    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        table.ajax.reload();
    });
    /** END:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/
    
    /** BEGIN:: USER DATA EDIT AJAX CODE **/
    $('#websitePageForm').on('submit', function(event){
        event.preventDefault();
        CKEDITOR.instances.page_content.updateElement();
        $.ajax({
            url:"{{url('/website-page')}}",
            type: "POST",
            data: new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            beforeSend: function () {
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
                        "batch_noClass": "toast-top-right",
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
                    // $('#websitePageForm')[0].reset();
                    $('#websitePageModal').modal('hide');
                    $('.ajax_loading').hide();
                    table.ajax.reload();
                    category_list();
                } else
                {
                
                    $("#websitePageModal").find('.error').text(''); 
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('.form-group').find('.error_' +
                            key).text(value);
                    });
                    $('.ajax_loading').hide();

                }
                

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
            alert('Error add/update data');
            $('.ajax_loading').hide();
            }
        });
    });
    /** END:: USER DATA EDIT AJAX CODE **/

});


</script>

@endsection
