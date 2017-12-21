<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><br>
    <h4 class="modal-title"> Option name : {{ $question_group->question_option_name }}</h4>
</div>
<form id="question-option-group-form" action="{{ url('admin/question/option/group/label/store/'.$question_group->id) }}"  role="form" class="form-horizontal" method="post">
    <div class="modal-body">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="table-responsive">
            <table class="table customFieldsName">
                <tr>
                    <td>
                        <input type="text" class="form-control"  name="o_name[]" placeholder="label name" />
                    </td>
                    <td>
                        <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option-name">
                            <i class="fa fa-plus"></i> Add</a>
                    </td>
                </tr>
            </table>
        </div>
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
        $(".add-more-option-name").click(function(){
            $(".customFieldsName").append(
                '<tr>' +
                '<td>' +
                '<input type="text" class="form-control"  name="o_name[]" placeholder="label name" />'+
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
    });
</script>