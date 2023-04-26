@if(count($productCoustomTags))
    @foreach($productCoustomTags->ProductCoustomTagKey as $ProductCoustomTagKey )
    <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">{{$ProductCoustomTagKey->name}} <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="" multiple>
                <option value="all">all</option>
                @foreach($ProductCoustomTagKey->ProductCoustomTagValue as $ProductCoustomTagValue)
                    <option value="{{$ProductCoustomTagValue->name}}">{{$ProductCoustomTagValue->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endforeach
@endif