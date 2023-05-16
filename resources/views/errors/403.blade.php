@extends('admin.layouts.app')
@section('title', 'Add city |')

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
 <div class="right_col" role="main">

        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">
                            <div class="error-page">
                                <h2 class="headline text-info"> 403</h2>
                                <div class="error-content">
                                    <h3><i class="fa fa-warning text-yellow"></i> Oops! You can not access this page.</h3>

                                    <!-- <p>
                                        We could not find the page you were looking for.
                                        Meanwhile, you may <a href="">return to dashboard</a> or try using the search form.
                                    </p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

  