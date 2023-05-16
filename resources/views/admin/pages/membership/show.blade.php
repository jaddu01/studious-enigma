@extends('admin.layouts.app')

@section('title', 'Show Product')

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

                            <span class="section">Show Membership</span>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Name: </label>
                                {{$membership->name or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Price: </label>
                                {{$membership->price or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Duration: </label>
                                {{$membership->duration or ''}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Offer: </label>
                                {{(empty($offerName))?'NO OFFER':$offerName }}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Offer Price: </label>
                                {{$membership->offer_price or ''}}
                                <hr>
                            </div>
                             <!-- <div class="item form-group">
                                <label class="control-label col-md-2 " >Offer Price: </label>
                                {{($membership->free_delivery=='1')?'Yes':'NO'}}
                                <hr>
                            </div> -->

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Minimum Order Price: </label>
                                {{$membership->min_order_price}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Image: </label>
                                   <img src="{{$membership->image}}" height="75" width="75">
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