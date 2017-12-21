<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Survey Question Information</h4>
</div>
<div class="modal-body">
    <table class="table table-bordered table-striped" style="clear: both">
        <tbody>
        <tr>
            <td class="column-left">
                <strong>Campaign Category</strong>
            </td>
            <td class="column-right">
                <?php
                    $categoryName = \App\SurveyCampaign::getCampaignTitleById($question->campaign_id)
                ?>
                {{ $categoryName->name }}
            </td>
        </tr>
        <tr>
            <td class="column-left">
                <strong>Campaign Title</strong>
            </td>
            <td class="column-right">
                {{ $question->campaign_title }}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Question Title</strong></td>
            <td class="column-right">
                {{$question->question_title}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Question Help Text</strong></td>
            <td class="column-right">{{ $question->question_help_text }}</td>
        </tr>
        <tr>
            <td class="column-left"><strong>Question Input Type</strong></td>
            <td class="column-right">
                &nbsp;{{$question->input_type_name}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Question Answer Require</strong></td>
            <td class="column-right">
                @if($question->question_answer_require == 1)
                    <span class="label label-success">Yes</span>
                @else
                    <span class="label label-danger">No</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Masking Enable</strong></td>
            <td class="column-right">
                @if($question->masking_enable == 1)
                    <span class="label label-success">Yes</span>
                @else
                    <span class="label label-danger">No</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Branching Enable</strong></td>
            <td class="column-right">
                @if($question->branching_enable == 1)
                    <span class="label label-success">Yes</span>
                @else
                    <span class="label label-danger">No</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Question Page No</strong></td>
            <td class="column-right">
                &nbsp;{{$question->question_page_no}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Created At</strong></td>
            <td class="column-right">
                &nbsp;{{$created_user->name}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Updated At</strong></td>
            <td class="column-right">
                {{$updated_user->name}}
            </td>
        </tr>
        </tbody>
    </table>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
    </div>
</div>