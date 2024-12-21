@extends('admin.includes.Template')
@section('content')
    <style type="text/css">
        ul li {
            list-style: inherit;
        }
    </style>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Costing</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/followup') }}">Enquiry</a></li>
                        <li class="breadcrumb-item active">Add Costing</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div id="validate" class="alert alert-danger alert-dismissible fade show" style="display: none;">
            <span id="login_error"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <h4 class="card-title">Basic Info</h4> -->
                        <form id="survey_form" action="{{ route('costing_information') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @php
                                  $followup_data = DB::table('followups')->where('id',$inquiry_id)->first(); 

                                  $survey_data = DB::table('survey_assign')->where('enquiry_id', $inquiry_id)->first();

                                  $branch = DB::table('branch')->where('id',$followup_data->branch)->pluck('branch')->first();
                                  
                                //    echo"<pre>";print_r($branch);echo"</pre>";exit;
                            @endphp
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Enquiry Id:</label>
                                        <input id="inquiry_id" name="inquiry_id" type="text" class="form-control"
                                            value="{{ $inquiry_id }}" readonly />
                                        <p class="form-error-text" id="name_error" style="color: red;"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Costing Date:</label>
                                        <input id="costing_date" name="costing_date" type="text" class="form-control"/>
                                        <p class="form-error-text" id="costing_date_error" style="color: red;"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Branch Code:</label>
                                        <input id="branch_code" name="branch_code" type="text" value="{{$branch}}" class="form-control" readonly/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Survey Id:</label>
                                        <input id="branch_code" name="branch_code" type="text" value="{{$survey_data->survey_id}}" class="form-control" readonly/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Costing Currency:</label>
                                       <select id="costing_currency" name="costing_currency" class="form-control form-select">
                                           <option value="">Select Costing Currency</option>
                                       </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="base_currency">Base Currency:</label>
                                        <lable for="aed">AED</lable>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="text-end mt-4">
                                    <a class="btn btn-primary" href="{{ route('followup.index') }}"> Cancel</a>
                                    <button class="btn btn-primary mb-1" type="button" disabled id="spinner_button"
                                        style="display: none;">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="javascript:costing_validation()" id="submit_button">Submit</button>
                                    <!-- <input type="submit" name="submit" value="Submit" class="btn btn-primary"> -->
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('footer_js')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#price_include'))
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#price_exclude'))
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#price_note'))
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#insurances'))
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#payment_terms'))
            .catch(error => {
                console.error(error);
            });
        ClassicEditor
            .create(document.querySelector('#payment_options'))
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        function costing_validation() {

            $('#spinner_button').show();
            $('#submit_button').hide();
            $('#survey_form').submit();
        }
        function setTodayDate() {
            const today = new Date();
            const formattedDate = today.toISOString().split('T')[0];
            document.getElementById('costing_date').value = formattedDate;
        }
        window.onload = setTodayDate;
    </script>
    <script type="text/javascript">
        $(function() {

            $('#moving_date').datepicker({
                format: 'yyyy-mm-dd', // Set the desired date format yyyy-mm-dd
                // autoclose: true,
                todayHighlight: true
            });
        });
        $(function() {
            $('#survey_date').datepicker({
                format: 'yyyy-mm-dd', // Set the desired date format yyyy-mm-dd
                // autoclose: true,
                todayHighlight: true
            });
        });
        $(function() {
            $('#quotetion_date').datepicker({
                format: 'yyyy-mm-dd', // Set the desired date format yyyy-mm-dd
                // autoclose: true,
                todayHighlight: true
            });
        });
    </script>
    <script type="text/javascript" language="javascript">
        function singledelete(url) {
            var t = confirm('Are You Sure To Delete The Attribute ?');
            if (t) {
                window.location.href = url;
            } else {
                return false;
            }
        }
        $(document).ready(function() {
            var max_fields = 50;
            var wrapper = $(".input_fields_wrap12");
            var add_button = $("#add_field_button12");
            var b = 0;
            $(add_button).click(function(e) { //alert('ok');
                e.preventDefault();
                if (b < max_fields) {
                    b++;
                    $(wrapper).append(
                        '<div class="row"><div class="col-md-4"><div class="form-group"><label for="poc">Particulars</label><input type="text" id="particulars" name="particulars[]" class="form-control" placeholder="Enter Particulars"></div></div><div class="col-md-4"><div class="form-group"><label for="poc">Amount(AED)</label><input type="text" id="amount" name="amount[]" class="form-control" placeholder="Enter Amount(AED)" onkeypress="return validateNumber(event)"></div></div><a href="#" class="btn btn-danger pull-right remove_field1" style="margin-right: 0;margin-top: 23px;width: 10%;float: right;height: 38px;margin-left: 127px;">Remove</a></div>'
                    );
                }
            });
            $(wrapper).on("click", ".remove_field1", function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                b--;
            })
        });

        function reject(id) {
            $('#reject_inquiry_id_hidden').val(id);
            $('#get_quote_reject_model').modal('show');
        }

        function accept(id) {
            $('#inquiry_id_hidden').val(id);
            $('#get_quote_accept_model').modal('show');
        }

        function quote_accept_form() {
            $('#quote_accept_form').submit();
        }

        function quote_reject_form() {
            var reject_reason = jQuery("#reject_reason").val();
            if (reject_reason == '') {
                jQuery('#reject_reason_error').html("Please Enter Reject Reason");
                jQuery('#reject_reason_error').show().delay(0).fadeIn('show');
                jQuery('#reject_reason_error').show().delay(2000).fadeOut('show');
                return false;
            }
            $('#quote_reject_form').submit();
        }

        function validateNumber(event) {
            var key = window.event ? event.keyCode : event.which;
            if (event.keyCode === 8 || event.keyCode === 46) {
                return true;
            } else if (key < 48 || key > 57) {
                return false;
            } else {
                return true;
            }
        }
    </script>
    	
      
        
@stop
