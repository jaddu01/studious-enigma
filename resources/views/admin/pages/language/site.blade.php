@extends('admin.layouts.app')

@section('title', ucwords($url).' Language' )

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
                        <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-12 ">
                        {!!  Form::select('lang', ['en'=>'En','ar'=>'Ar'],$lang, array('class' => 'form-control select2-multiple','placeholder'=>'Language','id'=>'language')) !!}
                         </div>
                         <div class="clear"></div>
                         <hr>

                            {!! Form::model($data,['url' => 'admin/language/'.$url.'/'.$lang,'method'=>'post','class'=>'form-horizontal form-label-left validation']) !!}
                          
                            <span class="section">{{ucwords($url)}} Language</span>

                            <div class="item form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
                               
                                <?php foreach ($data as $key => $value) {?>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label" for="name">{{$key}} <span class="required">*</span>
                                    </label>
                                    {!!  Form::text($key, $value, array('placeholder' => 'success','class' => 'form-control col-md-7 col-xs-12','required')) !!}
                                    @if ($errors->has('success'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first($key) }}</strong>
                                            </span>
                                    @endif
                                </div>
                                  
                                <?php } ?>
                                
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
@endsection
@push('scripts')
    <script type="text/javascript">
        $("#language").change(function(){
            var lang = $(this).val();
            var url = $(location).attr('href');
            parts = url.split("/");
            last_part = parts[parts.length-1];
            url = url.slice(0, url.lastIndexOf('/'));
            var newUrl = url+'/'+ lang;
            var rUrl = url+'/en';
            if(lang == null || lang== '' ){
                window.location.href =  rUrl;
                return false; 
            }
            window.location = newUrl; 
           
        })

    </script>
   
@endpush
