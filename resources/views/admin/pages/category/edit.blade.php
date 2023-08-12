@extends('admin.layouts.app')

@section('title', 'Edit Category')

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

                            {!! Form::model($category, [
                                'route' => ['category.update', $category->id],
                                'method' => 'post',
                                'class' => 'form-horizontal form-label-left validation',
                                'enctype' => 'multipart/form-data',
                            ]) !!}
                            {{ csrf_field() }}
                            {{ method_field('put') }}
                            <span class="section">Edit Category</span>

                            @foreach (config('translatable.locales') as $locale)
                                <div class="item form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name In
                                        {{ $locale }}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!! Form::text('name:' . $locale, null, [
                                            'placeholder' => 'Name',
                                            'class' => 'form-control col-md-7 col-xs-12',
                                            'dir' => $locale == 'ar' ? 'rtl' : 'ltr',
                                            'lang' => $locale,
                                        ]) !!}
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="item form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Image In
                                        {{ $locale }}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <img src="{{ $category->{'image:' . $locale} }}" height="75" width="75" />
                                        <input type="file" id="image" name="image:{{ $locale }}"
                                            class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="item form-group {{ $errors->has('banner_image') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="banner_image">Banner Image
                                        In {{ $locale }}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <img src="/storage/app/public/upload/{{ $category->{'banner_image:' . $locale} }}"
                                            height="75" width="75" />
                                        <input type="file" id="banner_image" name="banner_image:{{ $locale }}"
                                            class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('banner_image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('banner_image') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Category <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    <select name="parent_id" id="parent_id" class="form-control col-md-7 col-xs-12">
                                        <option value="0">Select</option>
                                        @foreach ($categories as $key => $value)
                                            <option value="{{ $value->id }}" class="optionGroup"
                                                {{ $category->parent_id == $value->id ? 'selected' : '' }}>
                                                {{ $value->name }}</option>
                                            @if (isset($value->sub_categories) && !empty($value->sub_categories))
                                                @foreach ($value->sub_categories as $key1 => $value1)
                                                    <option value="{{ $value1->id }}" class="optionChild"
                                                        {{ $category->parent_id == $value1->id ? 'selected' : '' }}>
                                                        {{ $value1->name }}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sort No
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!! Form::text('sort_no', null, ['placeholder' => 'Sort no', 'class' => 'form-control col-md-7 col-xs-12']) !!}
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
                                    <input name="is_show" type="checkbox" {{ $category->is_show == '1' ? 'checked' : '' }}
                                        value={{ $value->is_show }}>
                                    @if ($errors->has('is_show'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('is_show') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_home">Show Category In
                                    Home Page
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input name="is_home" type="checkbox" {{ $category->is_home == '1' ? 'checked' : '' }}
                                        value={{ $value->is_home }}>
                                    @if ($errors->has('is_home'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('is_home') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="is_checkout">Show Category In
                                    Checkout Page
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input name="is_checkout" type="checkbox" {{ $category->is_checkout == '1' ? 'checked' : '' }}
                                        value={{ $value->is_checkout }}>
                                    @if ($errors->has('is_checkout'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('is_checkout') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
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
    <script src="{{ asset('public/assets/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('public/assets/nprogress/nprogress.js') }}"></script>
    <!-- validator -->
    <script src="{{ asset('public/assets/validator/validator.min.js') }}"></script>



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
