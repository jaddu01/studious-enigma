
<div class="item form-group ">

    @foreach($ProductVarient as $index=>$data)
<div class="row col-md-12">
    <div class="col-md-1 col-sm-1 col-xs-12">
        <input type="checkbox" name="tag_status[{{$index}}]" value="1" {{ ($data->status==1) ? 'checked':'' }} >
    </div>
        @foreach(explode('-',$data->product_coustom_tag_value_translation_ids) as $key=>$rec)

           {{-- {{dd($rec)}}--}}
            <div class="col-md-2 col-sm-2 col-xs-12">
                <?php $TagValue= \App\ProductCoustomTagValue::where('id',$rec)->first();?>

                {!!  Form::text('tag[]',$TagValue->name, array('class' => 'form-control col-md-7 col-xs-12','readonly'=>'true')) !!}
            </div>

        @endforeach
        <input type="hidden" name="variant[]" value="{{$data->product_coustom_tag_value_translation_ids}}">
        <div class="col-md-2 col-sm-2 col-xs-12">
            {!!  Form::text('tag_price[]',$data->price, array('class' => 'form-control col-md-7 col-xs-12','placeholder'=>'price','required'=>'true')) !!}
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12">
            {!!  Form::text('tag_qty[]',$data->qty, array('class' => 'form-control col-md-7 col-xs-12','placeholder'=>'qty','required'=>'true')) !!}
        </div>
    <div class="col-md-2 col-sm-2 col-xs-12">
        {!!  Form::file('tag_image['.$index.']',null, array('class' => 'form-control col-md-7 col-xs-12','placeholder'=>'qty','required'=>'')) !!}
        @if(!empty($data->image))
            <img src="{{$data->image}}" height="75" width="75">
         @endif
    </div>
            {!!  Form::hidden('tag_sku[]',$data->sku) !!}
</div>
    <br>
<hr>

    @endforeach

</div>
