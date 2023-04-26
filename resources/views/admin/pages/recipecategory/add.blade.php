@extends('admin.layouts.app')

@section('title', 'Add Recipe Category|')

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

                            {!! Form::open(['route' => 'recipecategory.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                            {{csrf_field()}}
                            <span class="section">Add Recipe Category</span>
                            @foreach(config('translatable.locales') as $locale)
                             <?php if($locale=='en'){ $dir = 'ltr'; }
                                        else if($locale=='ar'){ $dir = 'rtl'; }?>
                                <div class="item form-group{{ $errors->has('name:'.$locale) ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Category Name In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        {!!  Form::text('name:'.$locale, null, array('placeholder' => 'Category Name','class' => 'form-control col-md-7 col-xs-12','lang'=>$locale ,'dir'=>$dir )) !!}
                                        @if ($errors->has('name:'.$locale))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name:'.$locale) }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="item form-group{{ $errors->has('description:'.$locale) ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Recipe In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        {!!  Form::textarea('description:'.$locale, null, array('placeholder' => 'Recipe step by step','class' => 'form-control col-md-7 col-xs-12','rows'=>'3','lang'=>$locale ,'dir'=>$dir)) !!}
                                        @if ($errors->has('description:'.$locale))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('description:'.$locale) }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                  <div class="item form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Image:{{$locale}}
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input type="file" id="image" name="image:{{$locale}}"
                                                   class="form-control col-md-7 col-xs-12" multiple>
                                            @if ($errors->has('image:.$locale'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('image:.$locale') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                      @endforeach

                                    <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status
                                            
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                            {{ Form::filedError('status') }}
                                        </div>
                                    </div>



                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            {{-- <button type="submit" class="btn btn-primary">Cancel</button>--}}
                                            {!!  Form::submit('Submit',array('class'=>'btn btn-success','id'=>'buttonDiv2')) !!}
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
