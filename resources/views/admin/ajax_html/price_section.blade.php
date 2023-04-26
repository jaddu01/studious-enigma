<?php
$result = [];
?>


@foreach($productCoustomTags->ProductCoustomTagKey as $ProductCoustomTagKey )

    @foreach($ProductCoustomTagKey->ProductCoustomTagValue as $ProductCoustomTagValue)
        <?php
        $result[$ProductCoustomTagKey->name][] = [$ProductCoustomTagValue->name,$ProductCoustomTagValue->id] ;
       ?>
    @endforeach

@endforeach
<?php /*echo "<pre>"; print_r(Helper::get_combinations($result));*/?>
<div class="item form-group ">

    @foreach(Helper::get_combinations($result) as $index=>$data)
<div class="row col-md-12">
    <?php $variant = [];?>
        <div class="col-md-1 col-sm-1 col-xs-12">
            <input type="checkbox" name="tag_status[{{$index}}]" value="1" >
        </div>
        @foreach($data as $key=>$rec)
            <div class="col-md-2 col-sm-2 col-xs-12">
                {!!  Form::text('tag[]',$rec[0], array('class' => 'form-control col-md-7 col-xs-12','readonly'=>'')) !!}
            </div>
            <?php $variant[]=$rec[1]?>
        @endforeach
<input type="hidden" name="variant[]" value="{{implode('-',$variant)}}">
        <div class="col-md-2 col-sm-2 col-xs-12">
            {!!  Form::text('tag_price[]',null, array('class' => 'form-control col-md-7 col-xs-12','placeholder'=>'price','required'=>'')) !!}
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12">
            {!!  Form::text('tag_qty[]',null, array('class' => 'form-control col-md-7 col-xs-12','placeholder'=>'qty','required'=>'')) !!}
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12">
            {!!  Form::file('tag_image['.$index.']',null, array('class' => 'form-control col-md-7 col-xs-12','placeholder'=>'qty','required'=>'')) !!}
        </div>
            {!!  Form::hidden('tag_sku[]',uniqid('sku')) !!}
</div>
    <br>
<hr>

    @endforeach

</div>
