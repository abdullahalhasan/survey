@if(!empty($questions) && count($questions)>0)
    @foreach($questions as $key => $question)
        <tr>
            <td width="85%">
                <strong>Q - {{ $key+1 }} :  {{ isset($question->question_title) ? $question->question_title: '' }}</strong>
                <input type="hidden" value="{{ $question->id }}" name="answer_question_id[]">
                <input type="hidden" value="{{ $question->question_title }}" name="answer_question_title[]">
                <input type="hidden" value="{{ $question->question_input_type_id}}" name="answer_input_type_id[]">
                <span class="help-block">{{ isset($question->question_help_text) ? 'Help text: '.$question->question_help_text:'' }}</span>
                <?php
                $option_groups = \DB::table('question_option_group')
                    ->where('option_question_id',$question->id)
                    ->where('option_input_type_id',$question->question_input_type_id)
                    ->get();
                ?>
                @if(!empty($option_groups) && count($option_groups) > 0)
                    @foreach($option_groups as $key=>$group)
                        <div class="radio">
                            <label>
                                <input type="radio" value="{{$group->question_option_value}}" name="answer_option_group_value[]" class="grey">
                                {{$group->question_option_name}}
                            </label>
                        </div>
                    @endforeach
                @else
                    <p><strong>Option not found.</strong></p>
                @endif
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td>
            <strong>Question not found.</strong>
        </td>
    </tr>
@endif