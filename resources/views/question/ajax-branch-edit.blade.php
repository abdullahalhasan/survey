<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><br>
    <h4 class="modal-title">Edit branch filter</h4>
</div>
<form id="branchform" action="{{ url('admin/question/branch/condition/update/'.$question_id) }}"  role="form" class="form-horizontal" method="post">
    <div class="modal-body btn-squared">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="campaign_id" value="{{$campaign_id}}" class="campaign-id">
        <input type="hidden" name="page_number" value="{{$page_number+1}}" class="page-number">
        <table class="table table-bordered table-striped customTerm" style="clear: both">
            <thead>
            <tr>
                <th>Term</th>
                <th>Reference Question</th>
                <th>Answer</th>
                <th>Relation</th>
                <th>Compare Value</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            @if(!empty($b_questions) && count($b_questions) > 0)
                @foreach($b_questions as $b_question)
                    <tr>
                    <td>
                        <select  class="form-control input-sm" name="term[]">
                            <option {{(isset($b_question->term) && ($b_question->term=='')) ? 'selected' : ''}} value="">Choose a term</option>
                            <option {{(isset($b_question->term) && ($b_question->term=='and')) ? 'selected' : ''}} value="and">AND</option>
                            <option {{(isset($b_question->term) && ($b_question->term=='or')) ? 'selected' : ''}} value="or">OR</option>
                        </select>
                    </td>
                    <td>
                        <select  class="form-control question input-sm" name="reference_question_id[]">
                            <option value="">Please choice a reference question </option>
                            @if(!empty($questions) && count($questions) > 0)
                                @foreach($questions as $question)
                                    <option {{($question->id == $b_question->reference_question_id) ? 'selected' : ''}} value="{{ $question->id }}">
                                        {{$question->question_title}}
                                    </option>
                                @endforeach
                            @else
                                <option value="">Question not found.</option>
                            @endif
                        </select>
                    </td>
                    <td>
                        <select  class="form-control question_option input-sm" name="r_option_value[]">
                        </select>
                        Previous: {{ $b_question->r_option_name }}
                        <input type="hidden" class="r_option" name="r_option_value[]" value="{{$b_question->r_option_name}}">
                    </td>
                    <td>
                        <select  class="form-control input-sm" name="relation_symbol[]">
                            <option {{($b_question->relation_symbol=='answered') ? 'selected' : ''}} value="answered">is answered</option>
                            <option {{($b_question->relation_symbol=='not_answered') ? 'selected' : ''}} value="not_answered">is  not answered</option>
                            <option {{($b_question->relation_symbol=='is_equal') ? 'selected' : ''}} value="is_equal">is equal</option>
                            <option {{($b_question->relation_symbol=='is_less_than') ? 'selected' : ''}} value="is_less_than">is less than</option>
                            <option {{($b_question->relation_symbol=='is_greater_than') ? 'selected' : ''}} value="is_greater_than">is greater than</option>
                            <option {{($b_question->relation_symbol=='is_less_than_equal') ? 'selected' : ''}} value="is_less_than_equal">is less than equal</option>
                            <option {{($b_question->relation_symbol=='is_greater_than_equal') ? 'selected' : ''}} value="is_greater_than_equal">is greater than equal</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" value="{{$b_question->compare_value}}" name="compare_value[]">
                    </td>
                    <td>
                        <a href="javascript:void(0);"  data-branching-question-condition-id="{{$b_question->id}}"
                           class="btn btn-danger btn-squared btn-sm delete-branch-condition">
                            <i class="fa fa-minus"></i> Remove
                        </a>
                    </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6"> Data not found</td>
                </tr>
            @endif
            </tbody>
        </table>
        <button type="button" class="btn btn-primary btn-sm btn-squared add-term">Add term</button>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default btn-squared">Close</button>
        <button type="submit" class="btn btn-success btn-squared">Update</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        var site_url = $('.site_url').val();
        FormElements.init();
        $('.question').on('change', function () {
            var question_id = $(this).val();
            if(question_id != '') {
                $('.r_option').prop('disabled', 'disabled');
                $.ajax({
                    Type: "GET",
                    url: site_url + '/ajax/question/option/'+question_id,
                    success:function(data){
                        $('.question_option').html(data);
                    }
                });
            } else {
                $('.r_option').prop('disabled', false);
                alert('Question  answer not found.');

            }
        });
        $(".add-term").on('click',function(){
            var cam_id = $('.campaign-id').val();
            var page_number = $('.page-number').val();
            $.ajax({
                Type: "GET",
                url: site_url+'/admin/branch/question/ajax/json/'+cam_id+'/'+page_number,
                success:function(data){
                    data = $.parseJSON(data);
                    var questionOpts='';
                    var optionInput = '';
                    $.each(data.questions, function(key, value) {
                        questionOpts += "<option value='"+ value['id'] +"'>" + value['question_title'] + "</option>";
                    });
                    $.each(data.allInputOption, function(key, value) {
                        $.each(value, function(i, item) {
                            optionInput += "<option value='" + item['question_option_value'] + "'>" + item['question_option_name'] + "</option>";
                        });
                    });
                    $(".customTerm").append(
                        '<tr>' +
                        '<td>'+
                        '<select  class="form-control input-sm" name="term[]">'+
                        '<option value="and"> AND </option>'+
                        '<option value="or"> OR </option>'+
                        '</select>'+
                        '</td>' +
                        '<td>'+
                        '<select  class="append_question form-control input-sm" name="reference_question_id[]">'+
                        '<option value=""> Please choice a question</option>'+
                        questionOpts+
                        '</select>'+
                        '</td>'+
                        '<td>' +
                        '<select class="question_option_append form-control input-sm" name="r_option_value[]">'+
                        optionInput+
                        '</select>'+
                        '</td>' +
                        '<td>' +
                        '<select  class="form-control input-sm" name="relation_symbol[]">'+
                        '<option value="answered">is answered</option>'+
                        '<option value="not_answered">is  not answered</option>'+
                        '<option value="is_equal">is equal</option>'+
                        '<option value="is_less_than">is less than</option>'+
                        '<option value="is_greater_than">is greater than</option>'+
                        '<option value="is_less_than_equal">is less than equal</option>'+
                        '<option value="is_greater_than_equal">is greater than equal</option>'+
                        '</select>'+
                        '</td>' +
                        '<td>' +
                        '<input type="text" name="compare_value[]">'+
                        '</td>'+
                        '<td>'+
                        '<a href="javascript:void(0);" class="btn btn-danger btn-squared btn-sm remove-term"><i class="fa fa-minus"></i> Remove</a>'+
                        '</td>' +
                        '</tr>'
                    );
                    $(".remove-term").on('click',function(){
                        $(this).parent().parent().remove();
                    });
                },
            });
        });

        $('.delete-branch-condition').on('click', function () {
            var branching_question_condition_id = $(this).data('branching-question-condition-id');
            bootbox.dialog({
                message: "Are you sure you want to delete this item ?",
                title: "<i class='glyphicon glyphicon-trash'></i> Delete !",
                buttons: {
                    success: {
                        label: "No",
                        className: "btn-success btn-squared",
                        callback: function() {
                            $('.bootbox').modal('hide');
                        }
                    },
                    danger: {
                        label: "Delete!",
                        className: "btn-danger btn-squared",
                        callback: function() {
                            $.ajax({
                                type: 'GET',
                                url: site_url+'/ajax/delete/branch/condition/question/'+branching_question_condition_id,
                            }).done(function(response){
                                bootbox.alert(response);
                                /*parent.fadeOut(10,function () {
                                 location.reload(true);
                                 });*/
                                location.reload(true);
                            }).fail(function(response){
                                bootbox.alert(response);
                            })
                        }
                    }
                }
            });
        });

        $('#branch-form').validate({
            rules: {
                question_id: {
                    required: true
                },
                question_value: {
                    required: true
                },
                relation_symbol: {
                    required: true
                },
                question_id_after_true: {
                    required: true
                },
                question_id_after_false: {
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
    });
    function generateSelect2() {
        if($('.answer-select').length) {
            $('.answer-select').select2('destroy').select2({
                allowClear: true,
                width: '100%',
                placeholder: "Please choice answer(s)"
            });
        }
    }
</script>