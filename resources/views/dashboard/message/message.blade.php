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
                        <a href="{{url('/')}}" class="m-nav__link m-nav__link--post_image">
                            <i class="m-nav__link-post_image la la-home"></i>
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

                    </div>
                    <div class="m-portlet__body">
                        <!-- BEGIN:: DATATABLE SEARCH DATA FORM SECTION -->
                        <form method="POST" id="form-filter" class="m-form m-form--fit m--margin-bottom-20" role="form">
                            {{ csrf_field() }}
                            <div class="row m--margin-bottom-20">
                                <div class="col-lg-4 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>From Date:</label>
                                    <input type="text" class="form-control date" name="from_date" id="from_date" placeholder="Enter post title" />
                                </div>

                                <div class="col-lg-4 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>To Date:</label>
                                    <input type="text" class="form-control date" name="to_date" id="to_date" placeholder="Enter post title" />
                                </div>

                                <div class="col-lg-4 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
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
                            <table class="table table-striped table-bordered table-hover" id="messageTable">
                                <thead>
                                    <tr>
                                        <th>SR</th>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Email</th>
                                        <th width="20%">Message</th>
                                        @if (Auth::user()->role_id == 1)
                                            <th width="8%">Status</th>
                                            <th width="15%">Seen By</th>
                                        @endif
                                        <th>Date</th>
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


</div>


<script>
$(".date").datepicker({
    todayHighlight: !0,
    format: 'yyyy-mm-dd',
    orientation: "bottom left",
    templates: {
        leftArrow: '<i class="la la-angle-left"></i>',
        rightArrow: '<i class="la la-angle-right"></i>'
    }
})
</script>
<script>
var table;

$(document).ready(function () {

    /** BEGIN:: DATATABLE SERVER SIDE CODE **/
    var table = $('#messageTable').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "dom": 'lTgBfrtip',
        "buttons": [
            'colvis',
            {
                "extend": 'csv',
                "title": 'Message List',
                "exportOptions": {
                    <?php
                        if(Auth::user()->role_id == 1){
                    ?>
                        "columns": [0,1,2,3,4,5,6,7,8],    
                    <?php
                        }else{
                    ?>
                        "columns": [0,1,2,3,4,5,6],
                    <?php
                        }
                    ?>
                    
                    
                },
            },
            {
                "extend": 'excel',
                "title": 'Message List',
                "exportOptions": {
                    <?php
                        if(Auth::user()->role_id == 1){
                    ?>
                        "columns": [0,1,2,3,4,5,6,7,8],    
                    <?php
                        }else{
                    ?>
                        "columns": [0,1,2,3,4,5,6],
                    <?php
                        }
                    ?>
                    
                },
            },
            {
                "extend": 'pdf',
                "title": 'Message List',
                "exportOptions": {
                    <?php
                        if(Auth::user()->role_id == 1){
                    ?>
                        "columns": [0,1,2,3,4,5,6,7,8], 
                    <?php
                        }else{
                    ?>
                        "columns": [0,1,2,3,4,5,6],
                    <?php
                        }
                    ?>
                    
                },
            },

            {
                "extend": 'print',
                "title": 'Message List',
                "exportOptions": {
                    <?php
                        if(Auth::user()->role_id == 1){
                    ?>
                        "columns": [0,1,2,3,4,5,6,7,8],    
                    <?php
                        }else{
                    ?>
                        "columns": [0,1,2,3,4,5,6],
                    <?php
                        }
                    ?>
                    
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
            "url": "{{url('/message-list')}}",
            "type": "POST",
            "data": function (data) {
                data.from_date  = $('#from_date').val();
                data.to_date    = $('#to_date').val();
                data._token     = "{{csrf_token()}}";
            }
        },

        "fnDrawCallback": function () {
            
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
                                url: '{{ url("message-delete") }}',
                                type: 'POST',
                                data:{id:id},
                                dataType: 'json'
                            })
                            .done(function(response){
                                if(response.status == 'success'){
                                    swal({title: "Deleted!", text: response.message,type: "success"}
                                    ).then(function(){ 
                                        table.ajax.reload();
                                        get_notification(1);
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
                <?php
                    if(Auth::user()->role_id == 1){
                ?>
                    "targets": 8, 
                <?php
                    }else{
                ?>
                    "targets": 6,
                <?php
                    }
                ?>
                "orderable": false, //set not orderable
            }, 
            {
                <?php
                    if(Auth::user()->role_id == 1){
                ?>
                    "targets": 8,    
                <?php
                    }else{
                ?>
                    "targets": 6,
                <?php
                    }
                ?>
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

});
</script>

@endsection
