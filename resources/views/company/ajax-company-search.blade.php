<table class="table table-hover table-bordered table-striped nopadding">
    <thead>
    <tr>
        <th>Company Name</th>
        <th>Address</th>
        <th>Mobile</th>
        <th>Web site</th>
        <th style="text-align: center">Action</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($companies) && count($companies) > 0)
        @foreach($companies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->address }}</td>
                <td>{{ $company->mobile }}</td>
                <td><a href="{{ $company->web_url }}" target="_blank"> {{ $company->web_url }}</a> </td>
                <td>
                    <a class="btn btn-info btn-xs btn-squared company-show" href="javascript:void(0)"
                       data-company-id="{{$company->id}}">
                        <i class="fa fa-search"></i> Show
                    </a>
                    <a class="btn btn-primary btn-xs btn-squared" href="{{ url('admin/company/edit/'.$company->id) }}">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a class="btn btn-danger btn-xs btn-squared delete"  href="javascript:void(0)"
                       data-company-id="{{$company->id}}">
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
{{isset($company_pagination) ? $company_pagination:""}}