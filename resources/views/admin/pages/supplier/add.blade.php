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
    <link href="{{ asset('public/css/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="row">
                {!! Form::open([
                    'route' => 'supplier.store',
                    'method' => 'post',
                    'class' => 'form-horizontal form-label-left validation',
                    'enctype' => 'multipart/form-data',
                ]) !!}

                {{ csrf_field() }}
                @foreach (config('translatable.locales') as $locale)
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel mb-3">
                            <div class="x_content">
                                <span class="section">Add Supplier</span>


                                <div class="row mb-3">
                                    <div
                                        class="col-md-4 item form-group{{ $errors->has('gstin_number') ? ' has-error' : '' }}">
                                        <label class="control-label" for="gstin_number">GSTIN
                                        </label>
                                        {!! Form::text('gstin_number', null, [
                                            'placeholder' => 'GSTIN',
                                            'class' => 'form-control ',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('gstin_number'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('gstin_number') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div
                                        class="col-md-4 item form-group{{ $errors->has('pan_number') ? ' has-error' : '' }}">
                                        <label class="control-label" for="gstin_number">PAN No.
                                        </label>
                                        {!! Form::text('pan_number', null, [
                                            'placeholder' => 'PAN No.',
                                            'id' => 'gstin_number',
                                            'class' => 'form-control',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('pan_number'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('pan_number') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div
                                        class="col-md-4 item form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                                        <label class="control-label" for="company_name">Company Name
                                        </label>
                                        {!! Form::text('company_name', null, [
                                            'placeholder' => 'Company Name',
                                            'class' => 'form-control ',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('company_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('company_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="row mb-3">

                                    <div class="col-md-4 item form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                                        <label class="control-label" for="address">Address
                                        </label>

                                        {!! Form::text('address', null, [
                                            'placeholder' => 'Address',
                                            'class' => 'form-control ',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}

                                        @if ($errors->has('address'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('address') }}</strong>
                                            </span>
                                        @endif

                                    </div>

                                    <div class="col-md-4 item form-group {{ $errors->has('city') ? ' has-error' : '' }}">
                                        <label class="control-label" for="city">City
                                        </label>

                                        {!! Form::text('city', null, [
                                            'placeholder' => 'City',
                                            'class' => 'form-control ',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}

                                        @if ($errors->has('city'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('city') }}</strong>
                                            </span>
                                        @endif

                                    </div>

                                    <div class="col-md-4 item form-group {{ $errors->has('state') ? ' has-error' : '' }}">
                                        <label class="control-label" for="state">State
                                        </label>

                                        {!! Form::select('state', $states, null, [
                                            'placeholder' => 'State',
                                            'id' => 'state',
                                            'class' => 'form-control select2-tax-state',
                                            'id' => 'state',
                                        ]) !!}
                                        @if ($errors->has('state'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('state') }}</strong>
                                            </span>
                                        @endif

                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <div
                                        class="col-md-4 item form-group {{ $errors->has('pin_code') ? ' has-error' : '' }}">
                                        <label class="control-label" for="pin_code">Pin Code
                                        </label>

                                        {!! Form::text('pin_code', null, [
                                            'placeholder' => 'Pin Code',
                                            'class' => 'form-control ',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}

                                        @if ($errors->has('pin_code'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('pin_code') }}</strong>
                                            </span>
                                        @endif

                                    </div>

                                    <div
                                        class="col-md-4 item form-group {{ $errors->has('country') ? ' has-error' : '' }}">
                                        <label class="control-label" for="country">Country
                                        </label>

                                        {!! Form::text('country', null, [
                                            'placeholder' => 'Country',
                                            'class' => 'form-control ',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}

                                        @if ($errors->has('country'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('country') }}</strong>
                                            </span>
                                        @endif

                                    </div>

                                    <div class="col-md-4 item form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label class="control-label" for="email">Email
                                        </label>

                                        {!! Form::text('email', null, [
                                            'placeholder' => 'Email',
                                            'class' => 'form-control ',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div
                                        class="col-md-4 item form-group {{ $errors->has('phone_number') ? ' has-error' : '' }}">
                                        <label class="control-label" for="phone_number">Phone Number
                                        </label>

                                        {!! Form::text('phone_number', null, [
                                            'placeholder' => 'Phone Number',
                                            'class' => 'form-control ',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}

                                        @if ($errors->has('phone_number'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('phone_number') }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                    <div class="col-md-4 item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label class="control-label" for="status">Status
                                        </label>
                                        {!! Form::select('status', ['1' => 'Active', '0' => 'Inactive'], '1', [
                                            'class' => 'form-control col-md-7 col-xs-12',
                                            'id' => 'status',
                                        ]) !!}

                                        @if ($errors->has('status'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('status') }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                </div>




                            </div>

                            <div class="x_content">
                                <span class="section"><i class="fa fa-plus-circle btn-pill mt-3"
                                        id="add_general_details_btn"></i>Add General Details</span>
                                <div class="collapse" id="generalDetails">
                                    <div class="row mb-3">
                                        <div
                                            class="col-md-4 item form-group{{ $errors->has('contact_person') ? ' has-error' : '' }}">
                                            <label class="control-label" for="contact_person">Contact Person
                                            </label>
                                            {!! Form::text('contact_person', null, [
                                                'placeholder' => 'Contact Person',
                                                'class' => 'form-control ',
                                                'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                                'lang' => $locale,
                                            ]) !!}
                                            @if ($errors->has('contact_person'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('contact_person') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div
                                            class="col-md-4 item form-group{{ $errors->has('contact_number') ? ' has-error' : '' }}">
                                            <label class="control-label" for="contact_number">Contact Number
                                            </label>
                                            {!! Form::text('contact_number', null, [
                                                'placeholder' => 'Contact Number',
                                                'class' => 'form-control ',
                                                'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                                'lang' => $locale,
                                            ]) !!}
                                            @if ($errors->has('contact_number'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('contact_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div
                                            class="col-md-4 item form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label class="control-label" for="email">Email
                                            </label>
                                            {!! Form::email('email', null, [
                                                'placeholder' => 'Email',
                                                'class' => 'form-control ',
                                                'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                                'lang' => $locale,
                                            ]) !!}
                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div
                                            class="col-md-4 item form-group{{ $errors->has('tax_state') ? ' has-error' : '' }}">
                                            <label class="control-label" for="tax_state">State
                                            </label>
                                            {!! Form::select('tax_state', $states, null, [
                                                'id' => 'tax_state',
                                                'class' => 'form-control select2-tax_state',
                                                'id' => 'tax_state',
                                            ]) !!}
                                            @if ($errors->has('tax_state'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('tax_state') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div
                                            class="col-md-4 item form-group{{ $errors->has('opening_balance') ? ' has-error' : '' }}">
                                            <label class="control-label" for="opening_balance">Opening Balance
                                            </label>
                                            {!! Form::text('opening_balance', null, [
                                                'placeholder' => 'Opening Balance',
                                                'class' => 'form-control ',
                                                'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                                'lang' => $locale,
                                            ]) !!}
                                            @if ($errors->has('opening_balance'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('opening_balance') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div
                                            class="col-md-4 item form-group {{ $errors->has('account_type') ? ' has-error' : '' }}">
                                            <label class="control-label" for="account_type">Type
                                            </label>
                                            {!! Form::select('account_type', $types, 'debit', ['class' => 'form-control', 'id' => 'account_type']) !!}
                                            @if ($errors->has('account_type'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('account_type') }}</strong>
                                                </span>
                                            @endif

                                        </div>


                                    </div>
                                    <div class="row mb-3">
                                        <div
                                        class="col-md-4 item form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
                                        <label class="control-label" for="remark">Remark
                                        </label>
                                        {!! Form::text('remark', null, [
                                            'placeholder' => 'Remark',
                                            'class' => 'form-control ',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('remark'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('remark') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="x_content">
                                <span class="section"><i class="fa fa-plus-circle btn-pill mt-3"
                                        id="add_bank_detailsBtn"></i>Add Bank Details</span>
                                <div class="collapse" id="bankdDetails">
                                    <div class="row mb-3">
                                        <div
                                            class="col-md-4 item form-group{{ $errors->has('bank_name') ? ' has-error' : '' }}">
                                            <label class="control-label" for="bank_name">Bank Number
                                            </label>
                                            {!! Form::text('bank_name', null, [
                                                'placeholder' => 'Bank Number',
                                                'class' => 'form-control ',
                                                'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                                'lang' => $locale,
                                            ]) !!}
                                            @if ($errors->has('bank_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('bank_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div
                                            class="col-md-4 item form-group{{ $errors->has('bank_account_number') ? ' has-error' : '' }}">
                                            <label class="control-label" for="bank_account_number">Bank Account Number
                                            </label>
                                            {!! Form::text('bank_account_number', null, [
                                                'placeholder' => 'Bank Account Number',
                                                'class' => 'form-control ',
                                                'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                                'lang' => $locale,
                                            ]) !!}
                                            @if ($errors->has('bank_account_number'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('bank_account_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div
                                            class="col-md-4 item form-group{{ $errors->has('bank_ifsc_code') ? ' has-error' : '' }}">
                                            <label class="control-label" for="bank_ifsc_code">IFSC Code
                                            </label>
                                            {!! Form::text('bank_ifsc_code', null, [
                                                'placeholder' => 'IFSC Code',
                                                'class' => 'form-control ',
                                                'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                                'lang' => $locale,
                                            ]) !!}
                                            @if ($errors->has('bank_ifsc_code'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('bank_ifsc_code') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                         
                        </div>




                    </div>

            </div>
            @endforeach
            <div class="row">
                <div class="col-md-12 text-right">
                    {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}

                </div>
            </div>


            {!! Form::close() !!}
        </div>
    </div>
    </div>
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
    <script src="{{ asset('public/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
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
    <script src="{{ asset('public/assets/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('public/assets/nprogress/nprogress.js') }}"></script>
    {{-- <!-- validator -->
<script src="{{asset('public/assets/validator/validator.min.js')}}"></ --}}
    <script src="{{ asset('public/assets/suppliers/add_supplier.js') }}"></script>
@endpush
