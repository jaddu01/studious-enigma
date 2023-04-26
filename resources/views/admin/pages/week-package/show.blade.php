@extends('admin.layouts.app')

@section('title', 'Show Week Details')

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

                            <span class="section">Show Week Details</span>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Name: </label>
                                {{$weekPackage->name or ''}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >saturday: </label>
                                {{$weekPackage->saturday->name or ''}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >sunday: </label>
                                {{$weekPackage->sunday->name or ''}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >monday: </label>
                                {{$weekPackage->monday->name or ''}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >tuesday: </label>
                                {{$weekPackage->tuesday->name or ''}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >wednesday: </label>
                                {{$weekPackage->wednesday->name or ''}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >thursday: </label>
                                {{$weekPackage->thursday->name or ''}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >friday: </label>
                                {{$weekPackage->friday->name or ''}}
                                <hr>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')


@endpush
