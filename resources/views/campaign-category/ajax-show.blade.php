<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Campaign Category Information</h4>
</div>
<div class="modal-body">
    <table class="table table-bordered table-striped" style="clear: both">
        <tbody>
        <tr>
            <td class="column-left">
                <strong>Name</strong>
            </td>
            <td class="column-right">
                {{ $category->name }}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Name Slug</strong></td>
            <td class="column-right">
                &nbsp;{{$category->name_slug}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Created At</strong></td>
            <td class="column-right">
                &nbsp;{{$category->created_at}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Updated At</strong></td>
            <td class="column-right">
                &nbsp;{{$category->updated_at}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Created By</strong></td>

            <td class="column-right">
                &nbsp;{{$created_by->name}}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Updated By</strong></td>
            <td class="column-right">
                {{$updated_by->name}}
            </td>
        </tr>
        </tbody>
    </table>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
    </div>
</div>