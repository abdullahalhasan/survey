<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><br>
    <h4 class="modal-title">Edit Question option value</h4>
</div>
<form id="question-option-group-form" action="{{ url('admin/question/option/group/update/'.$question_option_group->id) }}"  role="form" class="form-horizontal" method="post">
    <div class="modal-body">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="option_question_id" value="{{$question_option_group->option_question_id}}">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>Option input name</strong>
            </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="question_option_name"
                       value="{{ isset($question_option_group->question_option_name)? $question_option_group->question_option_name:''}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>Option input value</strong>
            </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="question_option_value"
                       value="{{ isset($question_option_group->question_option_value)? $question_option_group->question_option_value:''}}">
            </div>
        </div>
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
        $('#question-option-group-form').validate({
            rules: {
                question_option_name: {
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
</script>