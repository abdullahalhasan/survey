<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><br>
    <h4 class="modal-title"> Edit Option name</h4>
</div>
<form id="question-option-group-form" action="{{ url('admin/question/option/group/label/update/'.$question_option_group->id) }}"  role="form" class="form-horizontal" method="post">
    <div class="modal-body">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="form-field-1">
                <strong>Option name: </strong>
            </label>
            <div class="col-sm-8">
                <input type="text" class="form-control" value="{{$question_option_group->question_option_name}}" name="question_option_name">
            </div>
        </div>
        <fieldset class="scheduler-border">
            <legend class="scheduler-border"><strong>label name</strong></legend>
                <div class="table-responsive">
                    <table class="table customFieldsName">
                        @if(!empty($question_option_group_details) && count($question_option_group_details) > 0 )
                            @foreach($question_option_group_details as $question_option_group_detail)
                                <tr>
                                    <td>
                                        <input type="hidden" value="{{ $question_option_group_detail->id }}"
                                               name="question_option_group_detail_id[]">
                                        <input type="text" class="form-control" value="{{ $question_option_group_detail->o_name }}"
                                               name="o_name[]" />
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option-name">
                                        <i class="fa fa-plus"></i> Add label</a>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-success btn-squared add-more-option-name">
                                        <i class="fa fa-plus"></i> Add label</a>
                                </td>
                            </tr>
                        @endif
                    </table>
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
        $(".add-more-option-name").click(function(){
            $(".customFieldsName").append(
                '<tr>' +
                '<td>' +
                '<input type="hidden" value="" name="question_option_group_detail_id[]"/>'+
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