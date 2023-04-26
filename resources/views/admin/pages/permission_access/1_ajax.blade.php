
<table class="table table-striped jambo_table bulk_action">
    <thead>
    <tr class="headings">
        <th class="column-title">S No.</th>
        <th class="column-title">Module</th>
        <th class="column-title">Add</th>
        <th class="column-title">Edit</th>
        <th class="column-title">View</th>
        <th class="column-title">Delete</th>
        <th class="column-title">No access</th>

    </tr>
    </thead>
    <tbody>

     <?php
     $coun=1;
    ?>
    @foreach($models as $key=>$model)
        <?php $searchArray = ($permissionAccess->where('permission_modal_id','=',$key)->where('access_level_id','=',$access_level_id))->first();

        ?>

        @if(count($searchArray))

            <tr>
                <td>{{$coun++}}</td>
                <td class=" ">{{$model}}</td>
                <input type="hidden" name="model[]" class="" value="{{$key}}">
                <td><input type="checkbox" name="add[{{$key}}]" class="check_box{{$key}}" value="A" {{(in_array('A',explode(',',$searchArray['type']))) ? 'checked':''}} {{(in_array('N',explode(',',$searchArray['type']))) ? 'disabled':''}}></td>
                <td><input type="checkbox" name="edit[{{$key}}]" class="check_box{{$key}}" value="E" {{(in_array('E',explode(',',$searchArray['type']))) ? 'checked':''}} {{(in_array('N',explode(',',$searchArray['type']))) ? 'disabled':''}}></td>
                <td><input type="checkbox" name="view[{{$key}}]" class="check_box{{$key}}" value="V" {{(in_array('V',explode(',',$searchArray['type']))) ? 'checked':''}} {{(in_array('N',explode(',',$searchArray['type']))) ? 'disabled':''}}></td>
                <td><input type="checkbox" name="delete[{{$key}}]" class="check_box{{$key}}" value="D" {{(in_array('D',explode(',',$searchArray['type']))) ? 'checked':''}} {{(in_array('N',explode(',',$searchArray['type']))) ? 'disabled':''}}></td>
                <td><input type="checkbox" name="no_access[{{$key}}]" class="get_change" value="N" id="{{$key}}" {{(in_array('N',explode(',',$searchArray['type']))) ? 'checked':''}} ></td>

            </tr>
        @else
            <tr>
                <td>{{$coun++}}</td>
                <td class=" ">{{$model}}</td>
                <input type="hidden" name="model[]" class="" value="{{$key}}">
                <td><input type="checkbox" name="add[{{$key}}]" class="check_box{{$key}}" value="A"></td>
                <td><input type="checkbox" name="edit[{{$key}}]" class="check_box{{$key}}" value="E"></td>
                <td><input type="checkbox" name="view[{{$key}}]" class="check_box{{$key}}" value="V"></td>
                <td><input type="checkbox" name="delete[{{$key}}]" class="check_box{{$key}}" value="D"></td>
                <td><input type="checkbox" name="no_access[{{$key}}]" class="get_change" value="N" id="{{$key}}"></td>

            </tr>
        @endif

     @endforeach



    </tbody>
</table>
<script>
    $(document).ready(function () {

        $('.get_change').change(function () {
            var id = $(this).attr("id");
            if($(this).prop('checked') == true){
                $('.check_box'+id).prop('checked', false);
                $('.check_box'+id).prop('disabled', true);
            }else{
                $('.check_box'+id).removeAttr('disabled');
            }
        });
    });

</script>

