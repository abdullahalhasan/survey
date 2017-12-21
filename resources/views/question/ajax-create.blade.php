<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><br>
    <h4 class="modal-title">Create Question</h4>
</div>
<form id="question-form" action="{{ url('admin/question/store') }}" role="form" class="form-horizontal" method="post">
    <div class="modal-body">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="campaign_id" value="{{$campaign_id}}" class="campaign-id">
        <input type="hidden" name="page_number" value="{{$page_number}}" class="page-number">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>
                    Question Title
                    <span class="symbol required" aria-required="true"></span>
                </strong>
            </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="question_title">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>Help Text</strong>
            </label>
            <div class="col-sm-8">
                <input class="form-control" name="question_help_text"  type="text" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>
                    Enable Masking ?
                </strong>
            </label>
            <div class="col-sm-1">
                <label class="checkbox-inline">
                    <input type="checkbox" value="1" class="masking-enable" name="masking_enable">
                    Yes
                </label>
            </div>
            <div class="col-sm-7">
                <div class="show-masking">
                    <select  class="form-control search-select masking-question" name="mask_question_id">
                        <option value="">&nbsp;Choice Question Name</option>
                        @if(!empty($questions) && count($questions) > 0)
                            @foreach($questions as $question)
                                <option value="{{ $question->id }}">{{$question->question_title}}</option>
                            @endforeach
                        @else
                            <option value="">Question Not Found.</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>
                    Enable Reverse Masking ?
                </strong>
            </label>
            <div class="col-sm-1">
                <label class="checkbox-inline">
                    <input type="checkbox" value="1" class="re-masking-enable" name="remasking_enable">
                    Yes
                </label>
            </div>
            <div class="col-sm-7">
                <div class="show-re-masking">
                    <select  class="form-control search-select re-masking-question" name="re_mask_question_id">
                        <option value="">&nbsp;Choice Question Name</option>
                        @if(!empty($questions) && count($questions) > 0)
                            @foreach($questions as $question)
                                <option value="{{ $question->id }}">{{$question->question_title}}</option>
                            @endforeach
                        @else
                            <option value="">Question Not Found.</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>
                    Input type
                    <span class="symbol required" aria-required="true"></span>
                </strong>
            </label>
            <div class="col-sm-8">
                <select  class="form-control search-select question-type" name="question_input_type_id">
                    <option value="">&nbsp;Choice Question Input Type </option>
                    @if(!empty($input_types) && count($input_types) > 0)
                        @foreach($input_types as $input_type)
                            <option value="{{ $input_type->id }}">{{$input_type->input_type_name}}</option>
                        @endforeach
                    @else
                        <option value="">Data not found.</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">&nbsp;</div>
            <div class="col-sm-8">
                <div class="only_name">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><strong>Please enter label/option name</strong></legend>
                        <div class="table-responsive">
                            <table class="table customFieldsName">
                                <tr>
                                    <td><input type="text" class="form-control only_name_input_disable"  name="question_option_name[]" placeholder="Name" /></td>
                                    <td>
                                        <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option-name">
                                            <i class="fa fa-plus"></i> Add</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </fieldset>
                </div>
                <div class="number_percentage">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td>
                                    <input type="text" class="form-control"  name="min" placeholder="min value" />
                                </td>
                                <td>
                                    <input type="text" class="form-control"   name="max" placeholder="max value" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="start_rating">
                    <input style="width: 30%" type="text" class="form-control input-sm"  name="question_option_name_value" placeholder="value" />
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><strong>Please enter label/option name</strong></legend>
                        <div class="table-responsive">
                            <table class="table customFieldsStart">
                                <tr>
                                    <td><input type="text" class="form-control only_name_input_disable_for_rating"  name="question_option_name[]" placeholder="Name" /></td>
                                    <td><a href="javascript:void(0);" class="btn btn-success btn-squared add-more-start-option-name"><i class="fa fa-plus"></i> Add</a></td>
                                </tr>
                            </table>
                        </div>
                    </fieldset>
                </div>

                <div class="scale_static">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><strong>Option name</strong></legend>
                        <div class="table-responsive">
                            <table class="table customFieldsScaleStaticOptionName">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control only_name_input_disable_for_static_option_name"
                                               name="question_option_name_static[]" placeholder="Name" />
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);"
                                           class="btn btn-success btn-squared
                                           add-more-scale-static-option-name">
                                            <i class="fa fa-plus"></i>
                                            Add
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </fieldset>
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><strong>Value</strong></legend>
                        <div class="table-responsive">
                            <table class="table customFieldsScaleStaticOptionValue">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control only_name_input_disable_for_static_option_value"
                                               name="question_option_value_static[]" placeholder="Name" />
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" class="btn btn-success btn-squared
                                        add-more-scale-static-option-value">
                                            <i class="fa fa-plus"></i> Add
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </fieldset>
                </div>

                <div class="name_value">
                    <fieldset class="scheduler-border">
                        <div class="table-responsive">
                            <table class="table customFieldsNameValue">
                                <tr>
                                    <td><input type="text" class="form-control name_value_name_input_disable"  name="question_option_name[]" placeholder="Name" /></td>
                                    <td><input type="text" class="form-control name_value_value_input_disable"   name="question_option_value[]" placeholder="Value" /></td>
                                    <td><a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option-name-value"><i class="fa fa-plus"></i> Add</a></td>
                                </tr>
                            </table>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>
                    Is answer require ?
                </strong>
            </label>
            <div class="col-sm-1 control-label">
                <input type="checkbox" value="1" name="question_answer_require">
                Yes
            </div>
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>
                    Is option random ?
                </strong>
            </label>
            <div class="col-sm-1 control-label">
                <input type="checkbox" value="1" name="option_random">
                Yes
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default btn-squared">Close</button>
        <button type="submit" class="btn btn-success btn-squared">Create</button>
    </div>
</form>

<script type="text/javascript">
    jQuery(document).ready(function() {
        var site_url = $('.site_url').val();
        $('.name_value').hide();
        $('.number_percentage').hide();
        $('.start_rating').hide();
        $('.show-masking').hide();
        $('.show-re-masking').hide();
        $('.scale_static').hide();
        FormElements.init();
        $('#question-form').validate({
            rules: {
                question_input_type_id: {
                    required: true
                },
                campaign_id: {
                    required: true
                },
                question_title: {
                    required: true
                },
                question_page_no: {
                    required: true
                },
                question_option_name: {
                    required: true
                },
                question_option_value: {
                    required: true
                }
            },
            highlight: function (element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                    element.attr("placeholder",error.text());
                }
            }
        });
        $('.masking-enable').on('change', function () {
            if ($(this).is(':checked')) {
                var page_number = $('.page-number').val();
                var campaign_id = $('.campaign-id').val();
                $('.re-masking-enable').attr("disabled", true);
                $('.only_name').hide();
                $('.name_value').hide();
                $('.number_percentage').hide();
                $('.start_rating').hide();
                $('.question-type').off('change')
                if (page_number == '' || campaign_id == '') {
                    alert('Something is wrong');
                    $('.masking-enable').prop('checked', false);
                } else {
                    $.ajax({
                        type:"GET",
                        url:site_url + '/admin/question/ajax/check/masking/remasking/'+campaign_id+'/'+page_number,
                        success: function (data) {
                            if(data.status == 1) {
                                $('.show-masking').show();
                                $('.masking-question').on('change',function () {
                                    var question_id = $(this).val();
                                    console.log(question_id);
                                    if(question_id != '') {
                                        $.ajax({
                                            type:"GET",
                                            url:site_url + '/question/page/number/check/'+campaign_id+'/'+page_number+'/'+question_id,
                                            success: function (data) {
                                                if(data.status == 0) {
                                                    //$(".masking-question option[value='']").prop('selected', true);
                                                    $('.masking-question').val('').trigger('change.select2');
                                                    alert('Please select different question');
                                                } else if (data.status == 1) {
                                                    $.ajax({
                                                        type:"GET",
                                                        url:site_url + '/mask/question/input/option/value/'+question_id,
                                                        success: function (data) {
                                                            if(data.status == 0) {
                                                                //$(".masking-question option[value='']").prop('selected', true);
                                                                $('.masking-question').val('').trigger('change.select2');
                                                                alert('Please select different question');
                                                            } else {
                                                                /*var htmlText = '';
                                                                for (var i in data) {
                                                                    htmlText += '<tr>';
                                                                    htmlText += '<td>';
                                                                    htmlText += '<input type="text" class="form-control" value="'+data[i].question_option_value +'"   name="question_option_value[]"/>';
                                                                    htmlText += '</td>';
                                                                    htmlText += '<td>';
                                                                    htmlText += '<input type="text" class="form-control" value="'+data[i].question_option_name+'"   name="question_option_value[]"/>';
                                                                    htmlText += '</td>';
                                                                    htmlText += '</tr>';
                                                                }
                                                                $('.masked_option').append(htmlText);
                                                                $('.masked_option').show();*/
                                                            }

                                                        },
                                                        dataType: 'json'
                                                    });
                                                }
                                            },
                                            dataType: "json"
                                        });
                                    } else {
                                        alert('Please choice right question')
                                    }
                                })
                            } else {
                                alert('This page already have masked or reverse masked question.')
                                $('.masking-enable').prop('checked', false);
                            }
                        },
                        dataType: "json"
                    })
                }
            } else {
                $('.re-masking-enable').removeAttr("disabled");
                $('.show-masking').hide();
                //$('.masked_option').hide();
                $('.masking-question').val('').trigger('change.select2');
                $('.question-type').val('').trigger('change.select2');
                var question_type_id = $(this).val();
                if(question_type_id != '') {
                    var input_value = ['1', '2', '3','4','10', '7', '12'];
                    var inputValueForSLmL = ['5','6','13','14'];
                    var inputValueForNumPer = ['8', '9'];
                    var inputValueForStarRating = ['11']
                    if ($.inArray(question_type_id, input_value)!='-1') {
                        $('.only_name').show();
                        $('.name_value').hide();
                        $('.number_percentage').hide();
                        $('.start_rating').hide();
                        $('.only_name_input_disable').removeAttr('disabled', true);
                        $('.only_name_input_disable_for_rating').prop('disabled', true);
                        $('.name_value_name_input_disable').prop('disabled', true);
                        $('.name_value_value_input_disable').prop('disabled', true);
                        $('.question_option_name_disable').prop('disabled', true)
                    } else if ($.inArray(question_type_id, inputValueForSLmL)!='-1'){
                        $('.number_percentage').hide();
                        $('.start_rating').hide();
                        $('.only_name').hide();
                        $('.name_value').hide();
                        $('.only_name_input_disable').prop('disabled', true);
                        $('.only_name_input_disable_for_rating').prop('disabled', true);
                        $('.name_value_name_input_disable').prop('disabled', true);
                        $('.name_value_value_input_disable').prop('disabled', true);
                        $('.question_option_name_disable').prop('disabled', true)
                    } else if ($.inArray(question_type_id, inputValueForNumPer)!='-1') {
                        $('.only_name').hide();
                        $('.name_value').hide();
                        $('.number_percentage').show();
                        $('.start_rating').hide();
                        $('.only_name_input_disable').prop('disabled', true);
                        $('.only_name_input_disable_for_rating').prop('disabled', true);
                        $('.name_value_name_input_disable').prop('disabled', true);
                        $('.name_value_value_input_disable').prop('disabled', true);
                        $('.question_option_name_disable').prop('disabled', true)
                    } else if ($.inArray(question_type_id, inputValueForStarRating)!='-1') {
                        $('.only_name').hide();
                        $('.name_value').hide();
                        $('.number_percentage').hide();
                        $('.start_rating').show();
                        $('.only_name_input_disable_for_rating').removeAttr('disabled', true);
                        $('.only_name_input_disable').prop('disabled', true);
                        $('.name_value_name_input_disable').prop('disabled', true);
                        $('.name_value_value_input_disable').prop('disabled', true);
                        $('.question_option_name_disable').prop('disabled', true)
                    } else {
                        $('.name_value').show();
                        $('.start_rating').hide();
                        $('.only_name_input_disable').prop('disabled', true);
                        $('.only_name_input_disable_for_rating').prop('disabled', true);
                        $('.name_value_name_input_disable').removeAttr("disabled");
                        $('.name_value_value_input_disable').removeAttr("disabled");
                        $('.question_option_name_disable').removeAttr("disabled");
                        $('.only_name').hide();
                        $('.number_percentage').hide();
                    }
                }
            }
        });
        $('.re-masking-enable').on('change', function () {
            if ($(this).is(':checked')) {
                var page_number = $('.page-number').val();
                var campaign_id = $('.campaign-id').val();
                $('.masking-enable').attr("disabled", true);
                $('.only_name').hide();
                $('.name_value').hide();
                $('.number_percentage').hide();
                $('.start_rating').hide();
                $('.question-type').off('change')
                if (page_number == '' || campaign_id == '') {
                    alert('Something is wrong');
                    $('.re-masking-enable').prop('checked', false);
                } else {
                    $.ajax({
                        type:"GET",
                        url:site_url + '/admin/question/ajax/check/masking/remasking/'+campaign_id+'/'+page_number,
                        success: function (data) {
                            if(data.status == 1) {
                                $('.show-re-masking').show();
                                $('.re-masking-question').on('change',function () {
                                    var question_id = $(this).val();
                                    console.log(question_id);
                                    if(question_id != '') {
                                        $.ajax({
                                            type:"GET",
                                            url:site_url + '/question/page/number/check/'+campaign_id+'/'+page_number+'/'+question_id,
                                            success: function (data) {
                                                if(data.status == 0) {
                                                    //$(".masking-question option[value='']").prop('selected', true);
                                                    $('.re-masking-question').val('').trigger('change.select2');
                                                    alert('Please select different question');
                                                } else if (data.status == 1) {
                                                    $.ajax({
                                                        type:"GET",
                                                        url:site_url + '/remask/question/input/option/value/'+question_id,
                                                        success: function (data) {
                                                            if(data.status == 0) {
                                                                //$(".masking-question option[value='']").prop('selected', true);
                                                                $('.re-masking-question').val('').trigger('change.select2');
                                                                alert('Please select different question');
                                                            } else {
                                                                /*var htmlText = '';
                                                                 for (var i in data) {
                                                                 htmlText += '<tr>';
                                                                 htmlText += '<td>';
                                                                 htmlText += '<input type="text" class="form-control" value="'+data[i].question_option_value +'"   name="question_option_value[]"/>';
                                                                 htmlText += '</td>';
                                                                 htmlText += '<td>';
                                                                 htmlText += '<input type="text" class="form-control" value="'+data[i].question_option_name+'"   name="question_option_value[]"/>';
                                                                 htmlText += '</td>';
                                                                 htmlText += '</tr>';
                                                                 }
                                                                 $('.masked_option').append(htmlText);
                                                                 $('.masked_option').show();*/
                                                            }

                                                        },
                                                        dataType: 'json'
                                                    });
                                                }
                                            },
                                            dataType: "json"
                                        });
                                    } else {
                                        alert('Please choice right question')
                                    }
                                })
                            } else {
                                alert('This page already have masked or reverse masked question.')
                                $('.masking-enable').prop('checked', false);
                            }
                        },
                        dataType: "json"
                    })
                }
            } else {
                $('.masking-enable').removeAttr("disabled");
                $('.show-re-masking').hide();
                $('.re-masking-question').val('').trigger('change.select2');
                $('.question-type').val('').trigger('change.select2');
                var question_type_id = $(this).val();
                if(question_type_id != '') {
                    var input_value = ['1', '2', '3','4','10', '7', '12'];
                    var inputValueForSLmL = ['5','6','13','14'];
                    var inputValueForNumPer = ['8', '9'];
                    var inputValueForStarRating = ['11']
                    if ($.inArray(question_type_id, input_value)!='-1') {
                        $('.only_name').show();
                        $('.name_value').hide();
                        $('.number_percentage').hide();
                        $('.start_rating').hide();
                        $('.only_name_input_disable').removeAttr('disabled', true);
                        $('.only_name_input_disable_for_rating').prop('disabled', true);
                        $('.name_value_name_input_disable').prop('disabled', true);
                        $('.name_value_value_input_disable').prop('disabled', true);
                        $('.question_option_name_disable').prop('disabled', true)
                    } else if ($.inArray(question_type_id, inputValueForSLmL)!='-1'){
                        $('.number_percentage').hide();
                        $('.start_rating').hide();
                        $('.only_name').hide();
                        $('.name_value').hide();
                        $('.only_name_input_disable').prop('disabled', true);
                        $('.only_name_input_disable_for_rating').prop('disabled', true);
                        $('.name_value_name_input_disable').prop('disabled', true);
                        $('.name_value_value_input_disable').prop('disabled', true);
                        $('.question_option_name_disable').prop('disabled', true)
                    } else if ($.inArray(question_type_id, inputValueForNumPer)!='-1') {
                        $('.only_name').hide();
                        $('.name_value').hide();
                        $('.number_percentage').show();
                        $('.start_rating').hide();
                        $('.only_name_input_disable').prop('disabled', true);
                        $('.only_name_input_disable_for_rating').prop('disabled', true);
                        $('.name_value_name_input_disable').prop('disabled', true);
                        $('.name_value_value_input_disable').prop('disabled', true);
                        $('.question_option_name_disable').prop('disabled', true)
                    } else if ($.inArray(question_type_id, inputValueForStarRating)!='-1') {
                        $('.only_name').hide();
                        $('.name_value').hide();
                        $('.number_percentage').hide();
                        $('.start_rating').show();
                        $('.only_name_input_disable_for_rating').removeAttr('disabled', true);
                        $('.only_name_input_disable').prop('disabled', true);
                        $('.name_value_name_input_disable').prop('disabled', true);
                        $('.name_value_value_input_disable').prop('disabled', true);
                        $('.question_option_name_disable').prop('disabled', true)
                    } else {
                        $('.name_value').show();
                        $('.start_rating').hide();
                        $('.only_name_input_disable').prop('disabled', true);
                        $('.only_name_input_disable_for_rating').prop('disabled', true);
                        $('.name_value_name_input_disable').removeAttr("disabled");
                        $('.name_value_value_input_disable').removeAttr("disabled");
                        $('.question_option_name_disable').removeAttr("disabled");
                        $('.only_name').hide();
                        $('.number_percentage').hide();
                    }
                }
            }
        });
        $('.question-type').on('change', function () {
            var question_type_id = $(this).val();
            if(question_type_id != '') {
                var input_value = ['1', '2', '3','4','10', '7', '12'];
                var inputValueForSLmL = ['5','6','13','14'];
                var inputValueForNumPer = ['8', '9'];
                var inputValueForStarRating = ['11'];
                var inputValueForScaleStatic = ['15'];
                if ($.inArray(question_type_id, input_value)!='-1') {
                    $('.only_name').show();
                    $('.name_value').hide();
                    $('.number_percentage').hide();
                    $('.start_rating').hide();
                    $('.scale_static').hide();
                    $('.only_name_input_disable').removeAttr('disabled', true);
                    $('.only_name_input_disable_for_rating').prop('disabled', true);
                    $('.name_value_name_input_disable').prop('disabled', true);
                    $('.name_value_value_input_disable').prop('disabled', true);
                    $('.question_option_name_disable').prop('disabled', true)
                    $('.only_name_input_disable_for_static_option_name').prop('disabled', true);
                    $('.only_name_input_disable_for_static_option_value').prop('disabled', true);
                } else if ($.inArray(question_type_id, inputValueForSLmL)!='-1'){
                    $('.number_percentage').hide();
                    $('.start_rating').hide();
                    $('.only_name').hide();
                    $('.name_value').hide();
                    $('.scale_static').hide();
                    $('.only_name_input_disable').prop('disabled', true);
                    $('.only_name_input_disable_for_rating').prop('disabled', true);
                    $('.name_value_name_input_disable').prop('disabled', true);
                    $('.name_value_value_input_disable').prop('disabled', true);
                    $('.question_option_name_disable').prop('disabled', true)
                    $('.only_name_input_disable_for_static_option_name').prop('disabled', true);
                    $('.only_name_input_disable_for_static_option_value').prop('disabled', true);
                } else if ($.inArray(question_type_id, inputValueForNumPer)!='-1') {
                    $('.only_name').hide();
                    $('.name_value').hide();
                    $('.scale_static').hide();
                    $('.number_percentage').show();
                    $('.start_rating').hide();
                    $('.only_name_input_disable').prop('disabled', true);
                    $('.only_name_input_disable_for_rating').prop('disabled', true);
                    $('.name_value_name_input_disable').prop('disabled', true);
                    $('.name_value_value_input_disable').prop('disabled', true);
                    $('.question_option_name_disable').prop('disabled', true)
                    $('.only_name_input_disable_for_static_option_name').prop('disabled', true);
                    $('.only_name_input_disable_for_static_option_value').prop('disabled', true);
                } else if ($.inArray(question_type_id, inputValueForStarRating)!='-1') {
                    $('.only_name').hide();
                    $('.name_value').hide();
                    $('.number_percentage').hide();
                    $('.scale_static').hide();
                    $('.start_rating').show();
                    $('.only_name_input_disable_for_rating').removeAttr('disabled', true);
                    $('.only_name_input_disable').prop('disabled', true);
                    $('.name_value_name_input_disable').prop('disabled', true);
                    $('.name_value_value_input_disable').prop('disabled', true);
                    $('.question_option_name_disable').prop('disabled', true);
                    $('.only_name_input_disable_for_static_option_name').prop('disabled', true);
                    $('.only_name_input_disable_for_static_option_value').prop('disabled', true);
                } else if ($.inArray(question_type_id, inputValueForScaleStatic)!='-1') {
                    $('.name_value').hide();
                    $('.start_rating').hide();
                    $('.only_name_input_disable').prop('disabled', true);
                    $('.only_name_input_disable_for_rating').prop('disabled', true);
                    $('.name_value_name_input_disable').removeAttr("disabled");
                    $('.name_value_value_input_disable').removeAttr("disabled");
                    $('.question_option_name_disable').removeAttr("disabled");
                    $('.only_name_input_disable_for_static_option_name').removeAttr('disabled');
                    $('.only_name_input_disable_for_static_option_value').removeAttr('disabled');
                    $('.only_name').hide();
                    $('.number_percentage').hide();
                    $('.scale_static').show();
                } else {
                    $('.name_value').show();
                    $('.start_rating').hide();
                    $('.only_name_input_disable').prop('disabled', true);
                    $('.only_name_input_disable_for_rating').prop('disabled', true);
                    $('.name_value_name_input_disable').removeAttr("disabled");
                    $('.name_value_value_input_disable').removeAttr("disabled");
                    $('.question_option_name_disable').removeAttr("disabled");
                    $('.only_name_input_disable_for_static_option_name').prop('disabled', true);
                    $('.only_name_input_disable_for_static_option_value').prop('disabled', true);
                    $('.only_name').hide();
                    $('.number_percentage').hide();
                    $('.scale_static').hide();
                }
            }
        });

        $(".add-more-option-name").click(function(){
            $(".customFieldsName").append(
                '<tr>' +
                '<td>' +
                '<input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />'+
                '</td>'+
                '<td>'+
                '<a href="javascript:void(0);" class="btn btn-danger btn-squared remove-option-name"><i class="fa fa-minus"></i> Remove</a>'+
                '</td>' +
                '</tr>'
            );
            $(".remove-option-name").on('click',function(){
                $(this).parent().parent().remove();
            });
        });
        $(".add-more-option-name-value").click(function(){
            $(".customFieldsNameValue").append(
                '<tr>' +
                '<td>'+
                '<input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />'+
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control"  name="question_option_value[]" placeholder="Value" />'+
                '</td>'+
                '<td>'+
                '<a href="javascript:void(0);" class="btn btn-danger btn-squared remove-option-name-value"><i class="fa fa-minus"></i> Remove</a>'+
                '</td>'+
                '</tr>'
            );
            $(".remove-option-name-value").on('click',function(){
                $(this).parent().parent().remove();
            });
        });

        $(".add-more-start-option-name").click(function(){
            $(".customFieldsStart").append(
                '<tr>' +
                '<td>'+
                '<input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />'+
                '</td>' +
                '<td>'+
                '<a href="javascript:void(0);" class="btn btn-danger btn-squared remove-option-name-star"><i class="fa fa-minus"></i> Remove</a>'+
                '</td>'+
                '</tr>'
            );
            $(".remove-option-name-star").on('click',function(){
                $(this).parent().parent().remove();
            });
        });


        $(".add-more-scale-static-option-name").click(function(){
            $(".customFieldsScaleStaticOptionName").append(
                '<tr>' +
                '<td>'+
                '<input type="text" class="form-control"  name="question_option_name_static[]" placeholder="Name" />'+
                '</td>' +
                '<td>'+
                '<a href="javascript:void(0);" class="btn btn-danger btn-squared remove-option-name-scale-static"><i class="fa fa-minus"></i> Remove</a>'+
                '</td>'+
                '</tr>'
            );
            $(".remove-option-name-scale-static").on('click',function(){
                $(this).parent().parent().remove();
            });
        });

        $(".add-more-scale-static-option-value").click(function(){
            $(".customFieldsScaleStaticOptionValue").append(
                '<tr>' +
                '<td>'+
                '<input type="text" class="form-control"  name="question_option_value_static[]" placeholder="Name" />'+
                '</td>' +
                '<td>'+
                '<a href="javascript:void(0);" class="btn btn-danger btn-squared remove-option-value-scale-static"><i class="fa fa-minus"></i> Remove</a>'+
                '</td>'+
                '</tr>'
            );
            $(".remove-option-value-scale-static").on('click',function(){
                $(this).parent().parent().remove();
            });
        });


    });
</script>
