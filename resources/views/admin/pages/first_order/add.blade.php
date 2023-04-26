@extends('admin.layouts.app')

@section('title', 'Add First Order Data |')

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

                                {!! Form::open(['route' => 'first_order.store','method'=>'post','class'=>'form-horizontal form-label-left validation']) !!}

                                {{csrf_field()}}
                                <span class="section">Add First Order Data</span>


                        

                            <div class="item form-group {{ $errors->has('free_product') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Free Products <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('free_product[]', $products,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple','multiple'=>'true')) !!}
                                    {{ Form::filedError('free_product') }}
                                </div>
                            </div>
                    
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                  
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
    $(document).ready(function() {
        $('.select2-multiple').select2();
    });
</script>
@endpush
