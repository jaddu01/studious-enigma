@extends('admin.layouts.app')

@section('title', 'Add Price with zone |')

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
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet" />
@endpush

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">

                            {!! Form::open(['route' => 'admin.product.zonepricesave','method'=>'post','class'=>'form-horizontal form-label-left validation']) !!}

                            {{csrf_field()}}
                            <span class="section">Add Price for Zone</span>
                            <div class="item form-group {{ $errors->has('zone_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Zone <span
                                            class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    <select name="zone_id"  class="form-control col-md-7 col-xs-12">
                                        <option value="">Select</option>
                                       @foreach($zones as $zone)
                                           <option value="{{$zone->id}}">{{$zone->name}}</option>
                                     @endforeach
                                    </select>
                                      {{ Form::filedError('zone_id') }}
                                </div>
                            </div>

                            <div class="item form-group {{ $errors->has('product_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Products<span
                                            class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    <select name="product_id"  class="form-control col-md-7 col-xs-12">
                                        <option value="">Select</option>
                                       @foreach($products as $key=>$product)
                                           <option value="{{$key}}">{{$product}}</option>
                                     @endforeach
                                    </select>
                                      {{ Form::filedError('product_id') }}
                                </div>
                            </div>

                         <div class="item form-group {{ $errors->has('price') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Price <span
                                            class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('price',null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'priceAmt')) !!}
                                    {{ Form::filedError('price') }}
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

    <!-- /page content -->
@endsection
@push('scripts')
    <script src="{{asset('public/js/select2.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.select2-multiple').select2();
    });
</script>
@endpush
