@extends('admin.layouts.app')

@section('title', 'Add Category')

@section('sidebar')
    @parent
@endsection
@section('header')
    @parent
@endsection
@section('footer')
    @parent
@endsection
<style type="text/css">
    .optionGroup {
    font-weight: bold;
    font-style: italic;
}
    
.optionChild {
    padding-left: 15px;
}
</style>
@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
                        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">

                                {!! Form::open(['route' => 'category.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                                {{csrf_field()}}
                                <span class="section">Add Category</span>

                                @foreach(config('translatable.locales') as $locale)
                                    <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name In {{$locale}}<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('name:'.$locale, null, array('placeholder' => 'Name','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Image In {{$locale}}<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <input type="file" id="image" name="image:{{$locale}}" class="form-control col-md-7 col-xs-12">
                                            @if ($errors->has('image'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('image') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('banner_image') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Banner Image In {{$locale}}<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <input type="file" id="banner_image" name="banner_image:{{$locale}}" class="form-control col-md-7 col-xs-12">
                                            @if ($errors->has('banner_image'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('banner_image') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Category <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select name="parent_id" id="parent_id" class="form-control col-md-7 col-xs-12">
                                            <option value="0">Select</option>
                                            @foreach($categories as $key=>$value)
                                                <option value="{{$value->id}}" class="optionGroup">{{$value->name}}</option>
                                                @if(isset($value->sub_categories) && !empty($value->sub_categories))
                                                    @foreach($value->sub_categories as $key1=>$value1)
                                                        <option value="{{$value1->id}}" class="optionChild">{{$value1->name}}</option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>

                                        <!-- <select name="parent_id" class="form-control col-md-7 col-xs-12">
                                            <option value="0">Select</option>
                                            {{Helper::cat_list($categories)}}
                                        </select> -->


                                    </div>
                                </div>
                                 <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sort No
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            {!!  Form::text('sort_no', null, array('placeholder' => 'Sort no','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                            @if ($errors->has('sort_no'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('sort_no') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                </div>
                                <div class="item form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_show">Show Category
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <input name="is_show" type="checkbox" value="1">
                                            @if ($errors->has('is_show'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('is_show') }}</strong>
                                            </span>
                                            @endif
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
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
   {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
<!-- FastClick -->
<script src="{{asset('public/assets/fastclick/lib/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('public/assets/nprogress/nprogress.js')}}"></script>
{{--<!-- validator -->
<script src="{{asset('public/assets/validator/validator.min.js')}}"></--}}script>



@endpush
