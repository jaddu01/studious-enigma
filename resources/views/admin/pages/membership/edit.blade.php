@extends('admin.layouts.app')

@section('title', 'Edit Membership |')

@section('sidebar')
    @parent
@endsection
@section('header')
    @parent
@endsection
@section('footer')
    @parent
@endsection

@push('css')
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
    <style type="text/css">
        .w-10-p{ width: 10%;  }
    </style>
@endpush

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">


                                {!! Form::model($membership,['route' => ['membership.update',$membership->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                                {{method_field('put')}}

                            {{csrf_field()}}
                            <span class="section">Edit Membership</span>

                            <div class="item form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Membership Name
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    
                                    {!!  Form::text('name',$membership->name, array('class' => 'form-control col-md-7 col-xs-12','id'=>'name')) !!}
                                    {{ Form::filedError('name') }}
                                </div>
                            </div>

                                <div class="item form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Image 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                           <div class="img-wrap">
                                            <span class="close" data-id="{{$membership->id}}" onclick="deleteImage({{$membership->image}},{{$membership->id}})">&times;</span>
                                            <img src="{{$membership->image}}" height="100" width="100"/>
                                        </div>    
                                    <input type="file" id="image" name="image"
                                           class="form-control col-md-7 col-xs-12" multiple>
                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong> 
                                            </span>
                                    @endif
                                </div>
                            </div>

                         <div class="item form-group {{ $errors->has('duration') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Duration
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    {!!  Form::number('duration_value',$membership->duration_value, array('class' => 'form-control col-md-6 col-xs-12','min'=>'1' ,'id'=>"duration_value")) !!}
                                      </div>
                               <div class="col-md-3 col-sm-3 col-xs-12">
                                    {!!  Form::select('duration_class', $durations,$membership->duration_class, array('class' => 'form-control col-md-6 col-xs-12','id'=>"duration_class")) !!}
                                    <input type="hidden" name="duration" id="duration" value="{{$membership->duration}}">
                                    {{ Form::filedError('duration') }}
                                </div>
                            </div>


                           
                            <div class="item form-group {{ $errors->has('price') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Price <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('price',$membership->price, array('class' => 'form-control col-md-7 col-xs-12','id'=>'priceAmt')) !!}
                                    {{ Form::filedError('price') }}
                                </div>
                            </div>
                         

                            <div class="item form-group {{ $errors->has('offer_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Offer
                                    <span class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                     <input type="hidden" name="offer_type" id="offer_type" value="">
                                     <input type="hidden" name="offer_value" id="offer_value" value="">
                                    {!!  Form::select('offer_id', $offres,$membership->offer_id, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple','placeholder'=>'Offer','empty' => false,'value'=>0,'id'=>'offer_id')) !!}
                                    {{ Form::filedError('offer_id') }}
                                </div>
                            </div>


                        <div class="item form-group {{ $errors->has('offer_price') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Offer Price <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('offer_price',$membership->offer_price, array('class' => 'form-control col-md-7 col-xs-12','id'=>'offer_price')) !!}
                                    {{ Form::filedError('price') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('min_order_price') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="min_order_price">Minimum Order Price 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('min_order_price',$membership->min_order_price, array('class' => 'form-control col-md-7 col-xs-12','id'=>'min_order_price')) !!}
                                    {{ Form::filedError('min_order_price') }}
                                </div>
                            </div>
                            <div style="display: none;" class="item form-group{{ $errors->has('free_delivery') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Free Delivery
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::checkbox('free_delivery',null, $membership->free_delivery, array('class' => 'form-control w-10-p' )) !!}
                                    @if ($errors->has('free_delivery'))
                                    <span class="help-block">
                                     <strong>{{ $errors->first('free_delivery') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                                <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],$membership->status, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                            {{ Form::filedError('status') }}
                                        </div>
                                    </div>


                       
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    {{-- <button type="submit" class="btn btn-primary">Cancel</button>--}}
                                    {!!  Form::submit('Submit',array('class'=>'btn btn-success')) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
    <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.select2-multiple').select2({
                placeholder: "No Offer",
                allowClear: true
            });
        });
        $("#offer_id").change(function(){
          
            if($(this).val() == ''){
                $('#offer_type').val('');
                $('#offer_value').val('');
            }
            $.ajax({
                data: {
                    id:$(this).val()
                },
                method:'post',
                url: "{!! route('vendor-product.get-offer') !!}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( response ) {
                console.log(response);
                    if(response.status == 'true'){
                        $('#offer_type').val(response.data.offer_type);
                        $('#offer_value').val(response.data.offer_value);
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
          $("#priceAmt").change(function(){
            var price = $(this).val();
            var offerType = $("#offer_type").val();
            var offerAmount =  $("#offer_value").val();
            if(offerType == 'amount'){
                if(parseFloat(offerAmount) >  parseFloat($(this).val())) {
                    alert('Price should be greater than offer amount');
                    $(this).val('');
                }else{
                            var offer_price_amount = price-offerAmount;
                            $('#offer_price').val(parseFloat(offer_price_amount).toFixed(2));
                }
            }else{

                 var offer_price_amount = price-((price*offerAmount) / 100 );
                             $('#offer_price').val(parseFloat(offer_price_amount).toFixed(2));
            }

            $('#priceAmt').val(offer_price_amount);
        });

           $("#offer_id").change(function(){
          
            if($(this).val() == ''){
                $('#offer_type').val('');
                $('#offer_value').val('');
            }
           // alert($(this).val());
            $.ajax({
                data: {
                    id:$(this).val()
                },
                method:'post',
                url: "{!! route('vendor-product.get-offer') !!}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( response ) {
                console.log(response);
                    if(response.status == 'true'){
                        $('#offer_type').val(response.data.offer_type);
                        $('#offer_value').val(response.data.offer_value);
                        var price = $('#priceAmt').val();
                        if(response.data.offer_type=='percentages'){
                            var offer_price_amount = price-((price*response.data.offer_value) / 100 );
                             $('#offer_price').val(parseFloat(offer_price_amount).toFixed(2));
                         }else if(response.data.offer_type=='amount'){
                            var offer_price_amount = price-response.data.offer_value;
                            $('#offer_price').val(parseFloat(offer_price_amount).toFixed(2));
                         }
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

           $('#duration_class').change(function(){
            var duration_value = $('#duration_value').val();
            var duration_class = $(this).val();
            var duration = duration_value+" "+duration_class;
            $('#duration').val(duration);
            });

            $('#duration_value').change(function(){
            var duration_class = $('#duration_class').val();
            var duration_value = $(this).val();
            var duration = duration_value+" "+duration_class;
            $('#duration').val(duration);
            });
     
    </script>
@endpush
