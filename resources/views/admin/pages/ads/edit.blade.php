@extends('admin.layouts.app')

@section('title', 'Edit Ads')
@push('css')
    <link href="{{asset('public/css/multiple-select.css')}}" rel="stylesheet">
    <style type="text/css">
        .ms-parent {
    width: 100% !important;
    }
    .ms-choice {border: none; background-color: transparent;}
    </style>
@endpush
@section('sidebar')
    @parent
@endsection
@section('header')
    @parent
@endsection
@section('footer')
    @parent
@endsection


@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
                        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">
                                {!! Form::model($ads,['route' => ['ads.update',$ads->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','id'=>'slideredit_form']) !!}
                                {{csrf_field()}}
                                {{method_field('put')}}
                                <span class="section">Edit Ads</span>
                                 <div class="item form-group {{ $errors->has('zone_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Load Zone <span class="required">*</span>
                                </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        {!!  Form::select('zone_id[]',$zones,$selectedZone, array('class' => 'form-control multiselect-ui','id'=>'zone_id','multiple'=>'multiple')) !!}
                                        {{ Form::filedError('zone_id') }}
                                    </div>
                                </div>

                            @foreach(config('translatable.locales') as $locale)
                                <div class="item form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::text('title:'.$locale, null, array('placeholder' => 'Title','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="item form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Image In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <img src="{{$ads->{'image:'.$locale} }}" height="75" width="75"/>
                                        <input type="file" id="image" name="image:{{$locale}}" class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                  <div class="item form-group {{ $errors->has('link_type') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="link_type">Link Type <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::select('link_type', ['external'=>'External link','internal'=>'Internal Link'],null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'link_type',)) !!}
                                        {{ Form::filedError('link_type') }}
                                    </div>
                            </div>
                            <div  id="link_div" class="item form-group{{ $errors->has('link') ? ' has-error' : '' }}" style="<?php if($ads->link_type=='internal'){?>
                                display: none;
                                <?php }else{?>
                                display: block;
                                <?php } ?>">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Link <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('link', null, array('placeholder' => 'link','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('link'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('link') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                             <div id="internal_div" style="
                             <?php if($ads->link_type=='internal'){?>
                                display: block;
                                <?php }else{?>
                                display: none;
                                <?php } ?>">
                                <div class="item form-group {{ $errors->has('cat_id') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category">Category<span class="required">*</span>
                                    </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            {!!  Form::select('cat_id',$category,null, array('class' => 'form-control','placeholder'=>'','id'=>'cat_id')) !!}
                                            {{ Form::filedError('cat_id') }}
                                        </div>
                                </div>
                                <div id="sub_cat_div" class="item form-group {{ $errors->has('sub_cat_id') ? ' has-error' : '' }}" style=" <?php 
                                if(count($subCategory) > 0){?>
                                display: block;
                                <?php }else{?>
                                display: none;
                                <?php } ?>">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category">Sub Category
                                    </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            {!!  Form::select('sub_cat_id',$subCategory,null, array('class' => 'form-control','placeholder'=>'','id'=>'sub_cat_id')) !!}
                                            {{ Form::filedError('sub_cat_id') }}
                                        </div>
                                </div>
                               <!--  <div class="item form-group {{ $errors->has('product_id') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_id">Product</label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            {!!  Form::select('vendor_product_id',$products,null, array('class' => 'form-control','placeholder'=>'','id'=>'product_id')) !!}
                                            {{ Form::filedError('product_id') }}
                                        </div>
                                </div> -->
                            </div>
                                <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],$ads->status, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                        {{ Form::filedError('status') }}
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <button type="reset" class="btn btn-primary">Reset</button>
                                        <button id="send" type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator !!}
@endsection
@push('scripts')
 <script src="{{asset('public/js/multiple-select.js')}}"></script>
 <script>
        $("#zone_id").multipleSelect({
            filter: true,
            multiple: true,
            multipleWidth: 400,
            within: window
        });
          $("#slideredit_form").submit(function(){
           if($("#zone_id").val() == null || $("#zone_id").val() == ''){
                alert('Please select zone first');
                return false;
            }
        });
          $("#link_type").change(function(){

            if($(this).val()=='internal'){
                $("#internal_div").show();
                $("#cat_id").attr('required','required');
                $("#link_div").removeClass("has-error");
                $("#link").removeClass("has-error");
                $("#link_div").hide();
                
            }else{
                $("#link_div").addClass("has-error");
                $("#link").addClass("has-error");
                $("#link_div").show();
                $("#internal_div").hide();
            }
          });

        $("#cat_id").change(function(){
              
            if($(this).val() == ''){
                $('#sub_cat_id option:not(:first)').remove();
                $('#product_id option:not(:first)').remove();
            }

            $.ajax({
                data: {
                    id:$(this).val()
                },
                method:'post',
                url: "{!! route('offer-slider.sub-cat') !!}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( response ) {
                console.log(response);
                    if(response.status == 'true'){
                       $("#sub_cat_div").show();
                       //$('#sub_cat_id').children('option:not(:first)').remove();
                        $('#sub_cat_id option:not(:first)').remove();
                        $('#sub_cat_id').append($("<option selected value=''>Select Sub Category</option>"));
                       $.each(response.data, function(key, value) {
                             $('#sub_cat_id')
                                 .append($("<option></option>")
                                 .attr("value",key)
                                 .text(value));
                        });
                    
                    $('#product_id option:not(:first)').remove();
                    $('#product_id').append($("<option selected value=''>Select Product</option>"));
                    $.each(response.productData, function(key, value) {
                             $('#product_id')
                                .append($("<option></option>")
                                .attr("value",key)
                                .text(value));
                        });
                       if(response.data.length==0){
                         $("#sub_cat_div").hide();
                       }
                    }

                    if(response.status == 'false'){
                        console.log('here');
                        $("#sub_cat_div").hide();
                    }
                
            },
                error: function( response ) {
                   /* new PNotify({
                        title: 'Error',
                        text: 'something is wrong',
                        type: "error",
                        styling: 'bootstrap3'
                    });*/
                }
            });
        });
        $("#sub_cat_id").change(function(){
           if($(this).val()==''){
             $('#product_id').children('option:not(:first)').remove();
           }
            $.ajax({
                data: {
                    id:$(this).val()
                },
                method:'post',
                url: "{!! route('offer-slider.sub-cat') !!}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( response ) {
                console.log(response);
                    if(response.status == 'true'){
                    $('#product_id').children('option:not(:first)').remove();
                    $('#product_id').append($("<option selected value=''>Select Product</option>"));
                    $.each(response.productData, function(key, value) {
                             $('#product_id')
                                .append($("<option></option>")
                                .attr("value",key)
                                .text(value));
                        });
                       
                    }

                    if(response.status == 'false'){
                        console.log('here');
                       // $("#sub_cat_div").hide();
                    }
                
            },
                error: function( response ) {
                   /* new PNotify({
                        title: 'Error',
                        text: 'something is wrong',
                        type: "error",
                        styling: 'bootstrap3'
                    });*/
                }
            });
        });
        
    </script>

@endpush