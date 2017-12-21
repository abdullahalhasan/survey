@if(!empty($options) && count($options) > 0)
    @foreach($options as $key => $list)
        <option value="{{$list->question_option_value}}">
            {{$list->question_option_name}}
        </option>
    @endforeach
@else
    <option value="">Data not found.</option>
@endif