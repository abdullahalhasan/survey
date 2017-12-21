<table class="table table-hover table-bordered table-striped nopadding">
    <thead>
    <tr>
        <th>Campaign Category</th>
        <th>Campaign Title</th>
        <th>Active date</th>
        <th>Expire date</th>
        <th>Incentive amount(BDT)</th>
        <th>Incentive point</th>
        <th style="text-align: center">Action</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($surveyCampaigns) && count($surveyCampaigns) > 0)
        @foreach($surveyCampaigns as $surveyCampaign)
            <tr>
                <td>{{ $surveyCampaign->name }}</td>
                <td>{{ $surveyCampaign->campaign_title }}</td>
                <td>{{ $surveyCampaign->active_date }}</td>
                <td>{{ $surveyCampaign->expire_date}}</td>
                <td>{{ $surveyCampaign->campaign_incentive_amount}}</td>
                <td>{{ $surveyCampaign->campaign_incentive_point}}</td>
                <td>
                    <a class="btn btn-info btn-xs btn-squared survey-campaign-show" href="javascript:void(0)"
                       data-survey-campaign-id="{{$surveyCampaign->id}}">
                        <i class="fa fa-search"></i> Show
                    </a>
                    <a class="btn btn-primary btn-xs btn-squared" href="{{ url('admin/survey/campaign/edit/'.$surveyCampaign->id) }}">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a class="btn btn-danger btn-xs btn-squared delete"  href="javascript:void(0)"
                       data-company-id="{{$surveyCampaign->id}}">
                        <i class="fa fa-trash-o"></i> Delete
                    </a>
                </td>
            </tr>
        @endforeach
    @else
        <tr class="text-center">
            <td colspan="11">No Data available</td>
        </tr>
    @endif
    </tbody>
</table>
{{isset($surveyCampaigns) ? $surveyCampaigns:""}}