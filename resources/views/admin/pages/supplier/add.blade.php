@extends('admin.layouts.app')

@section('title', 'Add Supplier')

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
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
@endpush
@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
                        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">

                                {!! Form::open(['route' => 'supplier.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                                {{csrf_field()}}
                                <span class="section">Add Supplier</span>

                                @foreach(config('translatable.locales') as $locale)
                                    
                                    <div class="item form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="company_name">Company Name<span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('company_name', null, array('placeholder' => 'Company Name','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('company_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('company_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Address
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::textarea('address', null, array('placeholder' => 'Address','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('address'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('address') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="city">City
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('city', null, array('placeholder' => 'City','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('city'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('city') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('state') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="state">State 
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::select('state', $states,null, array('placeholder' => 'State','class' => 'form-control col-md-7 col-xs-12 select2-state','id'=>'state' )) !!}
                                        @if ($errors->has('state'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('state') }}</strong>
                                            </span>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('pin_code') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pin_code">Pin Code
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('pin_code', null, array('placeholder' => 'Pin Code','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('pin_code'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('pin_code') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="country">Country
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('country', "India", array('placeholder' => 'Country','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('country'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('country') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone_number">Phone Number
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('phone_number', null, array('placeholder' => 'Phone Number','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('phone_number'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('phone_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ln_solid"></div>
                                    <div class="item form-group{{ $errors->has('bank_name') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name">Bank Name
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('bank_name', null, array('placeholder' => 'Bank Name','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('bank_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('bank_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('bank_account_number') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_account_number">Bank A/c No.
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('bank_account_number', null, array('placeholder' => 'Bank A/c No.','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('bank_account_number'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('bank_account_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('bank_ifsc_code') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_ifsc_code">IFSC Code
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('bank_ifsc_code', null, array('placeholder' => 'IFSC Code','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('bank_ifsc_code'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('bank_ifsc_code') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ln_solid"></div>
                                    <div class="item form-group{{ $errors->has('pan_number') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pan_number">PAN No.
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('pan_number', null, array('placeholder' => 'PAN No.','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('pan_number'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('pan_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('gstin_number') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gstin_number">GSTIN
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('gstin_number', null, array('placeholder' => 'GSTIN','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('gstin_number'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('gstin_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('tax_state') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tax_state">State 
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::select('tax_state', $states,null, array('placeholder' => 'State','class' => 'form-control col-md-7 col-xs-12 select2-tax-state','id'=>'tax_state' )) !!}
                                        @if ($errors->has('tax_state'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('tax_state') }}</strong>
                                            </span>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('opening_balance') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="opening_balance">Opening Balance
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::number('opening_balance', null, array('step'=>'0.01','placeholder' => 'Opening Balance','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('opening_balance'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('opening_balance') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('account_type') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_type">Type
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::select('account_type', $types,'debit', array('placeholder' => 'Type','class' => 'form-control col-md-7 col-xs-12','id'=>'account_type' )) !!}
                                        @if ($errors->has('account_type'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('account_type') }}</strong>
                                            </span>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="control-label"></div>
                                    <div class="item form-group{{ $errors->has('contact_person') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="contact_person">Contact Person
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('contact_person', null, array('placeholder' => 'Contact Person','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('contact_person'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('contact_person') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('contact_number') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="contact_number">Contact No.
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::text('contact_number', null, array('placeholder' => 'Contact No.','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('contact_person'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('contact_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">Remark
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::textarea('remark', null, array('placeholder' => 'Remark','class' => 'form-control col-md-7 col-xs-12' , 'dir'=>($locale=="ar" ? 'rtl':'ltr'), 'lang'=>$locale ) ) !!}
                                            @if ($errors->has('remark'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('remark') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],'1', array('placeholder' => 'Status','class' => 'form-control col-md-7 col-xs-12','id'=>'status' )) !!}
                                        @if ($errors->has('status'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('status') }}</strong>
                                            </span>
                                        @endif
                                        </div>
                                    </div>
                                @endforeach
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
    <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.select2-state').select2({
                placeholder: "Select State",
                allowClear: true
            });
            $('.select2-tax-state').select2({
                placeholder: "Select State",
                allowClear: true
            });
        });
    </script>
<!-- FastClick -->
<script src="{{asset('public/assets/fastclick/lib/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('public/assets/nprogress/nprogress.js')}}"></script>
{{--<!-- validator -->
<script src="{{asset('public/assets/validator/validator.min.js')}}"></--}}script>



@endpush
