{{--<option value="">Please choice a answer </option>--}}
@if(!empty($question_values) && count($question_values) > 0)
    @foreach($question_values as $key => $list)
        <option value="{{$list->question_option_value}}">{{$list->question_option_name}}</option>
    @endforeach
@else
    <option value="">Data not found.</option>
@endif