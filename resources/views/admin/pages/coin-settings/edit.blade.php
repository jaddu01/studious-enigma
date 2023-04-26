@extends('admin.layouts.app')

@section('title', 'Edit Coin Settings')

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

                            {!! Form::model($coinSetting,['route' => ['coin-settings.update',$coinSetting->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                                {{csrf_field()}}
                                {{method_field('put')}}
                                <span class="section">Edit Brand</span>

                                @foreach(config('translatable.locales') as $locale)
                                    <div class="item form-group{{ $errors->has('from_amount') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="from_amount">From Amount<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('from_amount', null, array('placeholder' => 'From Amount','class' => 'form-control col-md-7 col-xs-12') ) !!}
                                            @if ($errors->has('from_amount'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('from_amount') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('to_amount') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="to_amount">To Amount<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('to_amount', null, array('placeholder' => 'To Amount','class' => 'form-control col-md-7 col-xs-12') ) !!}
                                            @if ($errors->has('to_amount'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('to_amount') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('coin') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="coin">Coin<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('coin', null, array('placeholder' => 'Coin','class' => 'form-control col-md-7 col-xs-12') ) !!}
                                            @if ($errors->has('coin'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('coin') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                       <button type="reset" class="btn btn-primary">Reset</button>
                                        <button id="send" type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection
@push('scripts')
<!-- FastClick -->
<script src="{{asset('public/assets/fastclick/lib/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('public/assets/nprogress/nprogress.js')}}"></script>
<!-- validator -->
<script src="{{asset('public/assets/validator/validator.min.js')}}"></script>



<!-- validator -->
<script>
    // initialize the validator function
    validator.message.date = 'not a real date';

    // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
    $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

    $('.multi.required').on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
    });

    $('form').submit(function(e) {
        e.preventDefault();
        var submit = true;

        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
            submit = false;
        }

        if (submit)
            this.submit();

        return false;
    });
@endpush