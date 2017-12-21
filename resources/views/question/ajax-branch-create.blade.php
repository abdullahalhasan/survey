<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><br>
    <h4 class="modal-title">Edit branch filter</h4>
</div>
<form id="branchform" action="{{ url('admin/question/branch/store/'.$campaign_id) }}"  role="form" class="form-horizontal" method="post">
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
            <tr>
                <td>
                    <select  class="form-control input-sm" name="term[]">
                        <option value="">Choose a term</option>
                        <option value="and">AND</option>
                        <option value="or">OR</option>
                    </select>
                </td>
                <td>
                    <select  class="form-control question input-sm" name="reference_question_id[]">
                        <option value="">Please choice a reference question </option>
                        @if(!empty($questions) && count($questions) > 0)
                            @foreach($questions as $question)
                                <option value="{{ $question->id }}">
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
                        <option value="">Please choice a answer </option>
                    </select>
                </td>
                <td>
                    <select  class="form-control input-sm" name="relation_symbol[]">
                        <option value="answered">is answered</option>
                        <option value="not_answered">is  not answered</option>
                        <option value="is_equal">is equal</option>
                        <option value="is_less_than">is less than</option>
                        <option value="is_greater_than">is greater than</option>
                        <option value="is_less_than_equal">is less than equal</option>
                        <option value="is_greater_than_equal">is greater than equal</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="compare_value[]">
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-primary btn-sm btn-squared add-term disabled">Add term</button>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default btn-squared">Close</button>
        <button type="submit" class="btn btn-success btn-squared">Create</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        var site_url = $('.site_url').val();
        FormElements.init();
        $('.question_option').prop('disabled', 'disabled');
        $(".add-term").off('click');
        $('.question').on('change', function () {
            var question_id = $(this).val();
            if(question_id != '') {
                $.ajax({
                    Type: "GET",
                    url: site_url + '/ajax/question/option/'+question_id,
                    success:function(data){
                        $('.question_option').prop('disabled', false);
                        $('.add-term').removeClass('disabled')
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
                        $('.question_option').html(data);
                    }
                });
            } else {
                $('.question_option').prop('disabled', 'disabled');
                $('.add-term').addClass('disabled');
                $(".add-term").off('click');
            }
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