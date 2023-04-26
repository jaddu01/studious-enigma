{{--<div class="borders">
    <div class="row">

        <div class="col-sm-4">
           Action
        </div>
        <div class="col-sm-4">
            <div class="switch">
                <input type="checkbox"  name="action_view" value="V">
                <label for="views">View</label>
            </div>
        </div>
        <div class="col-sm-4">
            <input type="checkbox" name="" value="A,E,D">
            <label for="views">add,edit,delete</label>
        </div>
    </div>

</div>--}}
<div class="borders">
    <input type="checkbox" onclick="toggle(this);" />Check all?<br />
<div class="row">
    @forelse($models as $key=>$model)

    <div class="col-sm-4">
        <input type="checkbox" name="permission_modal_id[{{$key}}]" value="{{$key}}" {{(in_array($key,$permissionAccess) ? 'checked':'fgdgdfg')}}>{{$model}}
    </div>
    @empty
        <p>no data found</p>
    @endforelse

</div>

</div>
<script>
    function toggle(source) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
    }
</script>