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
                                    @if(in_array('Menu Add',session()->get('permission')))
                                    <button type="button" class="btn add-btn" id="add_menu">
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
                                <div class="col-lg-10 col-sm-8 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Menu Name:</label>
                                    <input type="text" class="form-control" name="menu_name" id="menu_name" placeholder="Enter menu name" />
                                </div>

                                <div class="col-lg-2 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label></label>
                                    <div style="margin-top: 7px;">
                                        
                                        <button id="btn-reset" class="btn btn-danger  dim reset-btn pull-right" data-toggle="tooltip"
                                            data-placement="top" title="" data-original-title="Reset" type="button"><i
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
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="menuTable">
                            <thead>
                                <tr>
                                    <th>SR</th>
                                    <th>Menu Name</th>
                                    <th>Menu URL</th>
                                    <th>Icon</th>
                                    <th>Parent</th>
                                    <th>Sequence</th>
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

    <!-- BEGIN:: MENU ADD/EDIT MODAL FORM -->
    <div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <form method="POST" id="menu_form">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="menu_id" />
                        <div class="form-group">
                            <label for="menu_name" class="form-control-label">
                                Menu name:
                            </label>
                            <input type="text" name="menu_name" class="form-control" id="menu_name">
                            <div class="error error_menu_name text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label for="menu_url" class="form-control-label">
                                Menu Url:
                            </label>
                            <input type="text" name="menu_url" class="form-control" id="menu_url">
                            <div class="error error_menu_url text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label for="icon" class="form-control-label">
                                Icon:
                            </label>
                            <input type="text" name="icon" class="form-control" id="icon">
                            <div class="error error_icon text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label for="sequence" class="form-control-label">
                                Srquence:
                            </label>
                            <input type="text" name="sequence" class="form-control" id="sequence">
                            <div class="error error_sequence text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label for="parent_id" class="form-control-label">
                                Parent:
                            </label>
                            <select class="form-control chosen-select" name="parent_id" id="parent_id">
                                <option value="">Select Parent</option>
                                @if (!empty($menuList))
                                @foreach ($menuList as $menu)
                                <option value="{{$menu->id}}">{{$menu->menu_name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-close" data-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary" id="save-btn">
                            
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END:: MENU ADD/EDIT MODAL FORM -->
</div>

<!-- BEGIN:: JS SCRIPT SECTION -->
<script>
var table;
$(document).ready(function () {

    /** BEGIN:: DATATABLE SERVER SIDE CODE **/
    var table = $('#menuTable').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "dom": 'lTgBfrtip',
        "buttons": [
            'colvis',
            {
                "extend": 'csv',
                "title": 'Menu List',
                "exportOptions": {
                    "columns": [0, 1, 2, 3, 4,5]
                }
            },
            {
                "extend": 'excel',
                "title": 'Menu List',
                "exportOptions": {
                    "columns": [0, 1, 2, 3, 4,5]
                }
            },
            {
                "extend": 'pdf',
                "title": 'Menu List',
                "exportOptions": {
                    "columns": [0, 1, 2, 3, 4,5]
                }
            },

            {
                "extend": 'print',
                "title": 'Menu List',
                "exportOptions": {
                    "columns": [0, 1, 2, 3, 4,5]
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
            infoEmpty: '<strong class="text-danger">No Data Found</strong>',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>',
        },

        // Load data for the table's content from an Ajax source//
        "ajax": {
            "url": "{{url('/menu-list')}}",
            "type": "POST",
            "data": function (data) {
                data.menu_name = $('#menu_name').val();
                data._token = "{{csrf_token()}}";
            }
        },

        "fnDrawCallback": function () {
            //BEGIN: ON CLICK ADD MENU BUTTON & SHOW ADD MENU MODAL CODE
            $('#add_menu').click(function () {
                save_method = 'add';
                $('#menu_form')[0].reset();
                $("#menu_form #parent_id").val('').trigger("chosen:updated");
                $('#menu_id').val('');
                $(".error").each(function () {
                    $(this).empty();
                });

                $('#menuModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                })
                $('.modal-title').html('<i class="fas fa-plus-square"></i> <span>Add New Menu</span>');
                $('#save-btn').text('Save');
            });
            //END: ON CLICK ADD MENU BUTTON & SHOW ADD MENU MODAL CODE

            //BEGIN: ON CLICK EDIT MENU BUTTON FETCH MENU DATA AND SHOW IT IN EDIT MODAL FORM CODE
            $('.edit_menu').click(function () {
                var id = $(this).data('id');
                var url = "/menu/" + id + "/edit";
                save_method = 'update';
                $('#menu_form')[0].reset(); // reset form on modals

                $(".error").each(function () {
                    $(this).empty();
                });

                //Ajax Load data from ajax
                $.ajax({
                    url: "{{url('')}}" + url,
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) {

                        $('#menu_form #menu_id').val(data.menu.id);
                        $('#menu_form #menu_name').val(data.menu.menu_name);
                        $('#menu_form #menu_url').val(data.menu.menu_url);
                        $('#menu_form #icon').val(data.menu.icon);
                        $('#menu_form #sequence').val(data.menu.sequence);
                        $('#menu_form #parent_id').val(data.menu.parent_id);
                        $('#menu_form #parent_id').trigger("chosen:updated");
                        $('#menuModal').modal('show');
                        $('.modal-title').html('<i class="fas fa-edit"></i> <span>Edit Menu</span>');
                        $('#save-btn').text('Update');

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            });
            //END: ON CLICK EDIT MENU BUTTON FETCH MENU DATA AND SHOW IT IN EDIT MODAL FORM CODE
            
            //BEGIN: DELETE MENU DATA CODE
            $('.delete_menu').click(function () {
                var id = $(this).data('id');
                swal({
                    title: 'Are you sure?',
                    text: "It will be deleted permanently!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'btn',
                    cancelButtonColor: 'btn btn-danger',
                    confirmButtonText: 'Yes, delete it!',
                    showLoaderOnConfirm: true,

                    preConfirm: function () {
                        return new Promise(function (resolve) {

                            $.ajax({
                                    url: '{{ url("/menu/delete") }}/' +
                                        id,
                                    type: 'GET',
                                    dataType: 'json'
                                })
                                .done(function (response) {
                                    if (response.status ==
                                        'success') {
                                        swal({
                                            title: "Deleted!",
                                            text: response
                                                .message,
                                            type: "success"
                                        }).then(function () {
                                            table.ajax.reload();
                                        });
                                    } else if (response.status ==
                                        'error') {
                                        swal('Error deleting!',
                                            response.message,
                                            'error');
                                    }


                                })
                                .fail(function () {
                                    swal('Oops...',
                                        'Something went wrong with ajax !',
                                        'error');
                                });
                        });
                    },
                    allowOutsideClick: false
                });
            });
            //END: DELETE MENU DATA CODE
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [6], //first column / numbering column
                "orderable": false, //set not orderable
            }, 
            {
                "targets": 6, //TARGET ACTION COLUMN TO MAKE IT TEXT IN CENTER POSITION
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
        table.ajax.reload();
    });
    /** END:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/

    /** BEGIN:: MENU DATA ADD/UPDATE AJAX CODE **/
    $('#save-btn').click(function () {
        var url;
        if (save_method == 'add') {
            url = "{{url('/menu')}}";
        } else {
            url = "{{url('/menu/update')}}";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#menu_form').serialize(),
            dataType: "JSON",
            beforeSend: function () {
                $('.ajax_loading').show();
            },
            success: function (data) {
                if (data.success) {
                    console.log(data);
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
                    toastr.success(data.success, "SUCCESS");
                    table.ajax.reload();
                    $('#menuModal').modal('hide');
                    
                    $('.ajax_loading').hide();
                } else {

                    $("#menuModal").find('.error').text('');
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('.form-group').find('.error_' +
                            key).text(value);
                    });

                    $('.ajax_loading').hide();
                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    });
    /** END:: MENU DATA ADD/UPDATE AJAX CODE **/

});
</script>
<!-- END:: JS SCRIPT SECTION -->

@endsection
