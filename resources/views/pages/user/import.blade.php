@extends('admin.layouts.app')

@section('title', ' Products |')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <style type="text/css">
        .dataTables_length {width: 20%;}
        .dt-buttons .btn {border-radius: 0px;padding: 4px 12px;}
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
                        <div class="x_title">
                            <h2>Products Import</h2>

                            <div class="clearfix"></div>
                        </div>
              <div class="x_content">
             {!! Form::open(['route' => 'front.importExcel','method'=>'post','class'=>'form-horizontal form-label-left validation importform','enctype'=>'multipart/form-data']) !!}
             {{csrf_field()}}
               

                <div class="item form-group {{ $errors->has('import_file') ? ' has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Import File
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="file" id="import_file" name="import_file[]"
                class="form-control col-md-7 col-xs-12" multiple>
                @if ($errors->has('import_file'))
                <span class="help-block">
                <strong>{{ $errors->first('import_file') }}</strong>
                </span>
                @endif
                </div>
                </div>

               
    <div class="form-group">
    <div class="col-md-6 col-md-offset-3">
    {{-- <button type="submit" class="btn btn-primary">Cancel</button>--}}

    {!!  Form::submit('Update Prodcuts ',array('class'=>'btn btn-success')) !!}
    </div>
    </div>
             {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
@endpush
