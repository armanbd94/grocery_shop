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
                                     @if(in_array('Category Add',session()->get('permission')))
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
                                <div class="<?php if(in_array('Category Edit',session()->get('permission'))){ ?> col-lg-3 <?php }else{ ?> col-lg-4 <?php } ?> col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Name:</label>
                                    <input type="text" class="form-control" name="cat_name" id="cat_name" placeholder="Enter name" />
                                </div>
                                <div class="<?php if(in_array('Category Edit',session()->get('permission'))){ ?> col-lg-3 <?php }else{ ?> col-lg-4 <?php } ?> col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Parent:</label>
                                    <select class="form-control chosen-select" name="parent" id="parent">
                                        
                                    </select>
                                </div>



                                @if(in_array('Category Edit',session()->get('permission')))
                                <div class="col-lg-3 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Status:</label>
                                    <select class="form-control chosen-select" name="status" id="status">
                                        <option value="">Select Please</option>
                                        <option value="1">Enable</option>
                                        <option value="2">Disable</option>
                                    </select>
                                </div>
                                @endif

                                <div class="<?php if(in_array('Category Edit',session()->get('permission'))){ ?>col-lg-3<?php }else{ ?> col-lg-4 <?php } ?> col-sm-4 m--margin-bottom-10-tablet-and-mobile">
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
                            <table class="table table-striped- table-bordered table-hover table-checkable" id="categoryTable">
                                <thead>
                                    <tr>
                                        <th width="5%">SR</th>
                                        <th>Name</th>
                                        <th>Parent</th>
                                        @if(in_array('Category Edit',session()->get('permission')))
                                        <th>Status</th>
                                        @endif
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
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <form method="POST" id="categoryForm">
                    <div class="modal-body">
                        <div class="form-group m-form__group row">
                            @csrf
                            <input type="hidden" name="id" id="category_id" />
                            <div class="col-lg-12 pb-15">
                                <label for="category_name" class="form-control-label">
                                   Category Name:
                                </label>
                                <input type="text" name="category_name" class="form-control" id="category_name">
                                <div class="error error_category_name text-danger"></div>
                            </div>
                            <div class="col-lg-12 pb-15">
                                <label for="category_slug" class="form-control-label">
                                    Category Slug:
                                </label>
                                <input type="text" name="category_slug" class="form-control" id="category_slug" readonly>
                                <div class="error error_category_slug text-danger"></div>
                            </div>
                            <div class="col-lg-12 pb-15">
                                <label for="parent_id" class="form-control-label">
                                    Menu:
                                </label>
                                <select class="form-control chosen-select" name="parent_id" id="parent_id">
 
                                </select>
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




<script>
var table;

$(document).ready(function () {
    category_list();
    $("#category_name").keyup(function(event) {
        var value = this.value.toLowerCase();
        var str = value.replace(/ +(?= )/g,'');
        var name =str.split(' ').join('-')
        $("#category_slug").val(name);
    });
    $("#category_name").focusout(function(event) {
        var value = this.value.toLowerCase();
        var str = value.replace(/ +(?= )/g,'');
        var name =str.split(' ').join('-')
        $("#category_slug").val(name);
    });

    /** BEGIN:: DATATABLE SERVER SIDE CODE **/
    var table = $('#categoryTable').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "dom": 'lTgBfrtip',
        "buttons": [
            'colvis',
            {
                "extend": 'csv',
                "title": 'Category List',
                "exportOptions": {
                    "columns": [0,1,2]
                    
                }
            },
            {
                "extend": 'excel',
                "title": 'Category List',
                "exportOptions": {
                   "columns": [0,1,2]
                }
            },
            {
                "extend": 'pdf',
                "title": 'Category List',
                "exportOptions": {
                    "columns": [0,1,2]
                }
            },

            {
                "extend": 'print',
                "title": 'Category List',
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
            "url": "{{url('/product-category-list')}}",
            "type": "POST",
            "data": function (data) {
                data.cat_name = $('#cat_name').val();
                data.parent   = $('#parent').val();
                data.status   = $('#status').val();
                data._token   = "{{csrf_token()}}";
            }
        },

        "fnDrawCallback": function () {
            
            //BEGIN:: USER STATUS CHANGE AJAX CODE
            $(".change_status").on("change", function() {
                if ($(this).is(":checked")) {
                    status = 1;
                    id = $(this).data("id");
                } else {
                    status = 2;
                    id = $(this).data("id");
                }
                var _token = "{{csrf_token()}}";
                if(status && id){
                    $.ajax({
                        url: "{{url('/change-product-category-status')}}",
                        type: "POST",
                        data: {id:id,status:status,_token:_token},
                        dataType: "JSON",
                        beforeSend: function () {
                            $('.ajax_loading').show();
                        },
                        success: function (data) {
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
                            if (data.success) {
                                console.log(data);
                                toastr.success(data.success, "SUCCESS");
                                $('.ajax_loading').hide();
                                table.ajax.reload();
                                
                            } else {
                                toastr.error(data.error, "ERROR");
                                $('.ajax_loading').hide();
                                
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert('Error in change status data');
                        }
                    });
                }
            });
            //END:: USER STATUS CHANGE AJAX CODE

            //BEGIN: ON CLICK ADD BUTTON & SHOW ADD MODAL CODE
            $('#add_data').click(function () {
                save_method = 'add';

                $('#categoryForm')[0].reset();
                $('#category_id').val('');
                $('#parent_id').val('').trigger('chosen:updated');
                $(".error").each(function () {
                    $(this).empty();
                });

                $('#categoryModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                })
                $('.modal-title').html('<i class="fas fa-plus-square"></i> <span>Add New Category</span>');
                $('#save-btn').text('Save');
            });
            //END: ON CLICK ADD BUTTON & SHOW ADD MODAL CODE

            //BEGIN: ON CLICK EDIT BUTTON FETCH DATA AND SHOW IT IN EDIT MODAL FORM CODE
            $('.edit_data').click(function () {
                var id = $(this).data('id');
                var url = "product-category/" + id + "/edit";
                $('#categoryForm')[0].reset(); // reset form on modals
                $(".error").each(function () {
                    $(this).empty();
                });

                //Ajax Load data from ajax
                $.ajax({
                    url: "{{url('')}}/"+url,
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) {

                        $('#categoryForm #category_id').val(data.category.id);
                        $('#categoryForm #category_name').val(data.category.category_name);
                        $('#categoryForm #category_slug').val(data.category.category_slug);
                        $('#categoryForm #parent_id').val(data.category.parent_id).trigger('chosen:updated');
                        $('#categoryModal').modal('show');
                        $('.modal-title').html('<i class="fas fa-edit"></i> <span>Edit Category Data</span>');
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
                            
                            $.ajax({
                            url: '{{ url("product-category/delete") }}/'+id,
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
                <?php if( in_array('Category Edit',session()->get('permission'))){ ?>
                "targets": [3,4], //first column / numbering column
                <?php }else{ ?>
                "targets": [3],
                <?php } ?>
                "orderable": false, //set not orderable
            }, 
            {
                <?php if( in_array('Category Edit',session()->get('permission'))){ ?>
                "targets": [3,4],
                <?php }else{ ?>
                "targets": 3,
                <?php } ?>
                "className": "text-center",
            }
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
        $('#parent').trigger("chosen:updated");
        $('#status').trigger("chosen:updated");
        table.ajax.reload();
    });
    /** END:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/
    
    /** BEGIN:: USER DATA EDIT AJAX CODE **/
    $('#categoryForm').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:"{{url('/product-category')}}",
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
                console.log(data);
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
                    // $('#categoryForm')[0].reset();
                    $('#categoryModal').modal('hide');
                    
                    table.ajax.reload();
                    category_list();
                } else
                {
                
                    $("#categoryModal").find('.error').text(''); 
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('.form-group').find('.error_' +
                            key).text(value);
                    });

                }
                $('.ajax_loading').hide();

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
            alert('Error add/update data');
            $('.ajax_loading').hide();
            }
        });
    });
    /** END:: USER DATA EDIT AJAX CODE **/

    function category_list(){
        $.ajax({
            url: "{{url('product-categories')}}/",
            type: "GET",
            dataType: "JSON",
            success: function (data) {

                $('#categoryForm #parent_id').html(data).trigger('chosen:updated');
                $('#parent').html(data).trigger('chosen:updated');

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

});


</script>

@endsection
