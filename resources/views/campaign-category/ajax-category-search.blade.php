<table class="table table-hover table-bordered table-striped nopadding">
    <thead>
    <tr>
        <th>Category Name</th>
        <th style="text-align: center">Action</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($categories) && count($categories) > 0)
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>
                    <a class="btn btn-info btn-xs btn-squared campaign-category-show" href="javascript:void(0)"
                       data-campaign-category-id="{{$category->id}}">
                        <i class="fa fa-search"></i> Show
                    </a>
                    <a class="btn btn-primary btn-xs btn-squared" href="{{ url('admin/campaign/category/edit/'.$category->id) }}">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a class="btn btn-danger btn-xs btn-squared delete"  href="javascript:void(0)"
                       data-company-id="{{$category->id}}">
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
{{isset($category_pagination) ? $category_pagination:""}}