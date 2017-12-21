<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><br>
    <h4 class="modal-title"> Q.Title: {{ $question->question_title }}</h4>
</div>
<form id="question-option-group-form" action="{{ url('admin/question/option/group/store/'.$question->id) }}"  role="form" class="form-horizontal" method="post">
    <div class="modal-body">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="option_input_type_id" value="{{$question->question_input_type_id}}">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Please enter option name and value</legend>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="form-field-1">
                    <strong>Name</strong>
                </label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="question_option_name">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="form-field-1">
                    <strong>Value</strong>
                </label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="question_option_value">
                </div>
            </div>
        </fieldset>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default btn-squared">Close</button>
        <button type="submit" class="btn btn-success btn-squared">Save</button>
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