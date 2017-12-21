<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><br>
    <h4 class="modal-title">Create Question</h4>
</div>
<form id="question-form" action="{{ url('admin/question/store') }}" role="form" class="form-horizontal" method="post">
    <div class="modal-body">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="campaign_id" value="{{$campaign_id}}">
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
                    <select  class="form-control search-select masking-question-change" name="mask_question_id">
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
                    <select  class="form-control search-select re-masking-question-change" name="re_mask_question_id">
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
                <select  class="input-type form-control search-select question-type" name="question_input_type_id">
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
                        <legend class="scheduler-border"><strong>Please enter label name</strong></legend>
                        <div class="table-responsive">
                            <table class="table customFieldsName">
                                <tr>
                                    <td><input type="text" class="form-control only_name_name"  name="question_option_name[]" placeholder="Name" /></td>
                                    {{--<td><input type="text" class="form-control"   name="question_option_value[]" placeholder="Value" /></td>--}}
                                    <td>
                                        <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option-name">
                                            <i class="fa fa-plus"></i> Add</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </fieldset>
                </div>
                <div class="name_value">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><strong>Please enter label name and value</strong></legend>
                        <div class="table-responsive">
                            <table class="table customFields">
                                <tr>
                                    <td><input type="text" class="form-control name_value_name"  name="question_option_name[]" placeholder="Name" /></td>
                                    <td><input type="text" class="form-control name_value_value"   name="question_option_value[]" placeholder="Value" /></td>
                                    <td><a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option"><i class="fa fa-plus"></i> Add</a></td>
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
            <div class="col-sm-1">
                <input type="checkbox" value="1" name="question_answer_require">
                Yes
            </div>
            <div class="col-sm-2">
                <strong style="text-align: right; padding-top: 8px;" class="pull-right">
                    Page Number
                </strong>
            </div>
            <div class="col-sm-3">
                <input type="text" class="form-control page-number" name="question_page_no" value="1">
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
        FormElements.init();
        //enable-masking
        $('.show-masking').hide();
        $('.show-re-masking').hide();
        $('.name_value').hide();
        // enable branching
        $('.enable-branching').hide();
        $('.masking-enable').on('change', function () {
            if ($(this).is(':checked')) {
                $('.show-masking').show();
                $('.re-masking-enable').attr("disabled", true);
                $('.only_name').hide();
                $('.name_value').hide();
                $('.question-type').off('change')
                $('.masking-question-change').on('change',function () {
                    var question_id = $(this).val();
                    var page_number = $('.page-number').val();
                    $.ajax({
                        type:"GET",
                        url:site_url + '/question/page/number/check/'+question_id,
                        success: function (data) {
                            if((data.ex_page_number == page_number)|| (data.ex_page_number > page_number)){
                                alert('Please use different page number');
                                $('.page-number').val('');
                            }
                            $('.page-number').on('keyup', function () {
                                var page_number1 = $('.page-number').val();
                                if((data.ex_page_number == page_number1) ||(data.ex_page_number > page_number1)) {
                                    alert('Please use different page number');
                                    $('.page-number').val('');
                                }
                            })

                            //alert(data.ex_page_number);
                        },
                        dataType: "json"
                    })
                })
            } else {
                $('.show-masking').hide();
                $('.page-number').val('');
                $('.re-masking-enable').removeAttr("disabled");
                var question_type_id = $(this).val();
                if(question_type_id != '') {
                    var input_value = ['1', '2','3','4','5'];
                    if ($.inArray(question_type_id, input_value)!='-1') {
                        $('.only_name').show();
                        $('.name_value').hide();
                        $('.only_name_name').removeAttr('disabled', true);
                        $('.name_value_name').prop('disabled', true);
                        $('.name_value_value').prop('disabled', true);
                    } else {
                        $('.name_value').show();
                        $('.only_name_name').prop('disabled', true);
                        $('.name_value_name').removeAttr("disabled");
                        $('.name_value_value').removeAttr("disabled");
                        $('.only_name').hide();
                    }
                }
                $('.question-type').on('change', function () {
                    var question_type_id = $(this).val();
                    if(question_type_id != '') {
                        var input_value = ['1', '2','3','4','5'];
                        if ($.inArray(question_type_id, input_value)!='-1') {
                            $('.only_name').show();
                            $('.name_value').hide();
                            $('.only_name_name').removeAttr('disabled', true);
                            $('.name_value_name').prop('disabled', true);
                            $('.name_value_value').prop('disabled', true);
                        } else {
                            $('.name_value').show();
                            $('.only_name_name').prop('disabled', true);
                            $('.name_value_name').removeAttr("disabled");
                            $('.name_value_value').removeAttr("disabled");
                            $('.only_name').hide();
                        }
                    }
                });
            }
        });

        $('.re-masking-enable').on('change', function () {
            if ($(this).is(':checked')) {
                $('.show-re-masking').show();
                $('.masking-enable').attr("disabled", true);
                $('.only_name').hide();
                $('.name_value').hide();
                $('.question-type').off('change');
                $('.re-masking-question-change').on('change',function () {
                    var question_id = $(this).val();
                    var page_number = $('.page-number').val();
                    $.ajax({
                        type:"GET",
                        url:site_url + '/question/page/number/check/'+question_id,
                        success: function (data) {
                            if((data.ex_page_number == page_number)|| (data.ex_page_number > page_number)){
                                alert('Please use different page number');
                                $('.page-number').val('');
                            }
                            $('.page-number').on('keyup', function () {
                                var page_number1 = $('.page-number').val();
                                if((data.ex_page_number == page_number1) ||(data.ex_page_number > page_number1)) {
                                    alert('Please use different page number');
                                    $('.page-number').val('');
                                }
                            })

                            //alert(data.ex_page_number);
                        },
                        dataType: "json"
                    })
                })
            } else {
                $('.show-re-masking').hide();
                $('.page-number').val('');
                $('.masking-enable').removeAttr("disabled");
                var question_type_id = $(this).val();
                if(question_type_id != '') {
                    var input_value = ['1', '2','3','4','5'];
                    if ($.inArray(question_type_id, input_value)!='-1') {
                        $('.only_name').show();
                        $('.name_value').hide();
                        $('.only_name_name').removeAttr('disabled', true);
                        $('.name_value_name').prop('disabled', true);
                        $('.name_value_value').prop('disabled', true);
                    } else {
                        $('.name_value').show();
                        $('.only_name_name').prop('disabled', true);
                        $('.name_value_name').removeAttr("disabled");
                        $('.name_value_value').removeAttr("disabled");
                        $('.only_name').hide();
                    }
                }
                $('.question-type').on('change', function () {
                    var question_type_id = $(this).val();
                    if(question_type_id != '') {
                        var input_value = ['1', '2','3','4','5'];
                        if ($.inArray(question_type_id, input_value)!='-1') {
                            $('.only_name').show();
                            $('.name_value').hide();
                            $('.only_name_name').removeAttr('disabled', true);
                            $('.name_value_name').prop('disabled', true);
                            $('.name_value_value').prop('disabled', true);
                        } else {
                            $('.name_value').show();
                            $('.only_name_name').prop('disabled', true);
                            $('.name_value_name').removeAttr("disabled");
                            $('.name_value_value').removeAttr("disabled");
                            $('.only_name').hide();
                        }
                    }
                });
            }
        });

        $('.branching').on('change', function () {
            if ($(this).is(':checked'))
                $('.enable-branching').show();
            else
                $('.enable-branching').hide();
        });
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
                },
                meta_question_field_value_mask: {
                    required: true
                },
                meta_question_field_value_branch: {
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

        $('.question-type').on('change', function () {
            var question_type_id = $(this).val();
            if(question_type_id != '') {
                var input_value = ['1', '2','3','4','5'];
                if ($.inArray(question_type_id, input_value)!='-1') {
                    $('.only_name').show();
                    $('.name_value').hide();
                    $('.only_name_name').removeAttr('disabled', true);
                    $('.name_value_name').prop('disabled', true);
                    $('.name_value_value').prop('disabled', true);
                } else {
                    $('.name_value').show();
                    $('.only_name_name').prop('disabled', true);
                    $('.name_value_name').removeAttr("disabled");
                    $('.name_value_value').removeAttr("disabled");
                    $('.only_name').hide();
                }
            }
        })

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


        $(".add-more-option").click(function(){
            $(".customFields").append(
                '<tr>' +
                '<td>' +
                '<input type="text" class="form-control"  name="question_option_name[]" placeholder="Name" />'+
                '</td>'+
                '<td>' +
                '<input type="text" class="form-control"  name="question_option_value[]" placeholder="Value" />'+
                '</td>'+
                '<td>'+
                '<a href="javascript:void(0);" class="btn btn-danger btn-squared remove-option"><i class="fa fa-minus"></i> Remove</a>'+
                '</td>' +
                '</tr>'
            );
            $(".remove-option").on('click',function(){
                $(this).parent().parent().remove();
            });
        });
    });
</script>
