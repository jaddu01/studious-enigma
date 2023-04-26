@extends('admin.layouts.app')
@section('title', 'Add product |')
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
@endpush
@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_content">
                            {!! Form::open(['route' => 'vendor-product.addvendorproductbyzone','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data','id'=>'product_form']) !!}
                            {{csrf_field()}}
                            <span class="section">Add Product</span>
                            <div class="item form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">From  Vendor
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('user_id_from', $users,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple')) !!}
                                    {{ Form::filedError('user_id') }}
                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">To Vendor
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('user_id_to', $users,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple')) !!}
                                    {{ Form::filedError('user_id') }}
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
                    new PNotify({
                        title: 'Error',
                        text: 'something is wrong',
                        type: "error",
                        styling: 'bootstrap3'
                    });
                }
            });
        });
    </script>
@endpush
