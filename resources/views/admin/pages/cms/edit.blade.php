@extends('admin.layouts.app')

@section('title', 'CMS')

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

                                {!! Form::model($cms,['route' => ['cms.update',$cms->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                                {{csrf_field()}}
                                {{method_field('put')}}
                                <span class="section">Edit Cms</span>

                                <div class="item form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="hidden" name="name" value="{{$cms->name}}">
                                       <span> {{$cms->name}}</span>

                                    </div>
                                </div>

                            @foreach(config('translatable.locales') as $locale)

                                <div class="item form-group {{ $errors->has('description:'.$locale) ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Description In {{$locale}} <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::textarea('description:'.$locale,null, array('class' => 'form-control col-md-7 col-xs-12 editor','rows'=>'3')) !!}
                                        {{ Form::filedError('description:'.$locale) }}
                                    </div>
                                </div>
                            @endforeach

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                         <input name='reset' type="reset" value='Reset' class="btn btn-primary" /> 
                                       <!--  <button type="reset" class="btn btn-primary">Reset</button> -->
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
    <script src="{{ asset('/vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
    <script src="{{ asset('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js')}}"></script>
@endsection
@push('scripts')
    <script>
        $(function() {
    if (typeof CKEDITOR != 'undefined') {
        $('form').on('reset', function(e) {
            if ($(CKEDITOR.instances).length) {
                for (var key in CKEDITOR.instances) {
                    var instance = CKEDITOR.instances[key];
                    if ($(instance.element.$).closest('form').attr('name') == $(e.target).attr('name')) {
                        instance.setData(instance.element.$.defaultValue);
                    }
                }
            }
        });
    }
});
        var options = {
            filebrowserImageBrowseUrl: "{{url('/public/laravel-filemanager')}}?type=Images",
            filebrowserImageUploadUrl: "{{url('/public/laravel-filemanager/upload')}}?type=Images&_token="+$('meta[name="csrf-token"]').attr('content'),
            filebrowserBrowseUrl: "{{url('/public/laravel-filemanager')}}?type=Files",
            filebrowserUploadUrl: "{{url('/public/laravel-filemanager/upload')}}?type=Files&_token="+$('meta[name="csrf-token"]').attr('content'),
        };
        $('.editor').ckeditor(options);
    </script>

@endpush