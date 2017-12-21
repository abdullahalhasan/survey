<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Survey Campaign Information</h4>
</div>
<div class="modal-body">
    <table class="table table-bordered table-striped" style="clear: both">
        <tbody>
        <tr>
            <td class="column-left">
                <strong>Campaign Category</strong>
            </td>
            <td class="column-right">
                {{ $surveyCampaign->name }}
            </td>
        </tr>
        <tr>
            <td class="column-left">
                <strong>Campaign Title</strong>
            </td>
            <td class="column-right">
                {{ $surveyCampaign->campaign_title }}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Campaign Owner</strong></td>
            <td class="column-right">
                {{$surveyCampaign->campaign_owner}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Active date</strong></td>
            <td class="column-right">{{ $surveyCampaign->active_date }}</td>
        </tr>
        <tr>
            <td class="column-left"><strong>Expire_date</strong></td>
            <td class="column-right">
                {{ $surveyCampaign->expire_date }}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Campaign Incentive Amount</strong></td>
            <td class="column-right">
                &nbsp;{{$surveyCampaign->campaign_incentive_amount}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Campaign Incentive Point</strong></td>
            <td class="column-right">
                &nbsp;{{$surveyCampaign->campaign_incentive_point}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Campaign Instructions</strong></td>
            <td class="column-right">
                &nbsp;{{$surveyCampaign->campaign_instructions}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Campaign Ending Text</strong></td>
            <td class="column-right">
                &nbsp;{{$surveyCampaign->campaign_ending_text}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Created At</strong></td>
            <td class="column-right">
                &nbsp;{{$surveyCampaign->created_at}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Updated At</strong></td>
            <td class="column-right">
                {{$surveyCampaign->updated_at}}
            </td>
        </tr>

        </tbody>
    </table>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
    </div>
</div>