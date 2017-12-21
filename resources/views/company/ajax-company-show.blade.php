<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Company Information</h4>
</div>
<div class="modal-body">
    <table class="table table-bordered table-striped" style="clear: both">
        <tbody>
        <tr>
            <td class="column-left">
                <strong>Name</strong>
            </td>
            <td class="column-right">
                {{ $company->name }}
            </td>
        </tr>
        <tr>
            <td class="column-left">
                <strong>Address</strong>
            </td>
            <td class="column-right">
                {{ $company->address }}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>Mobile Number</strong></td>
            <td class="column-right">{{ $company->mobile }}</td>
        </tr>
        <tr>
            <td class="column-left"><strong>Web site address</strong></td>
            <td class="column-right">
                {{ $company->web_url }}
            </td>
        </tr>
        <tr>
            <td class="column-left"><strong>About Company</strong></td>
            <td class="column-right">
                &nbsp;{{$company->description}}
            </td>
        </tr>
        </tbody>
    </table>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
    </div>
</div>