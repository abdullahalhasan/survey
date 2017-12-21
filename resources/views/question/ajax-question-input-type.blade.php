<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><br>
    <h4 class="modal-title">Choice Question Type</h4>
</div>
<div class="modal-body">
   <div class="row">
       @if(!empty($input_types) && count($input_types) > 0)
           <?php $i=0; ?>
           @foreach($input_types as $input_type)
               <?php $i++ ?>
               @if($i % 2 == 0)
                   <div class="col-sm-6">
                       <div class="radio">
                           <label>
                               <input type="radio" value="{{ $input_type->input_type_value }}" name="optionsRadios2" class="red">
                               {{ $input_type->input_type_name }}
                           </label>
                       </div>
                   </div>
               @else
                   <div class="col-sm-6">
                       <div class="radio">
                           <label>
                               <input type="radio" value="{{ $input_type->input_type_value }}" name="optionsRadios2" class="red">
                               {{ $input_type->input_type_name }}
                           </label>
                       </div>
                   </div>
               @endif
           @endforeach
       @else
           <div class="col-sm-12">
               <p>Question input type data not found</p>
           </div>
       @endif
   </div>
</div>
<div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
</div>