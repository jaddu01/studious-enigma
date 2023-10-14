@extends('admin.layouts.app')

@section('title', 'Send Push Notification |')

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

                                {!! Form::open(['route' => 'admin-notification.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                                {{csrf_field()}}
                                <span class="section">Send Push Notification</span>


                            <div class="item form-group{{ $errors->has('user_ids') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">User <span class="required">*</span>
                                </label>
                                <div class="col-md-4 col-sm-4 col-xs-12">

                                    {!!  Form::select('user_ids[]',$users, null, array('class' => 'form-control select2-multiple col-md-7 col-xs-12','multiple'=>'true','id'=>'user_ids' )) !!}
                                    @if ($errors->has('user_ids'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('user_ids') }}</strong>
                                        </span>
                                    @endif

                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!!  Form::checkbox('selection',null,false, array('placeholder' => 'link','id'=>'select_all' )) !!}All
                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('message_heading') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">message heading <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('message_heading', null, array('placeholder' => 'message heading','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('message_heading'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('message_heading') }}</strong>
                                        </span>
                                    @endif

                                </div>
                            </div>
                            <div class="item form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Image
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::file('image', array('placeholder' => 'Image','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('image'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                                    @endif

                                </div>
                            </div>
                            <div class="item form-group {{ $errors->has('link_type') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="link_type">Link Type <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::select('link_type', ['home'=>'Home link','external'=>'External link','internal'=>'Internal Link'],null, array('class' => 'form-control col-md-7 col-xs-12','id'=>'link_type',)) !!}
                                        @if ($errors->has('link_type'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('link_type') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                            </div>
                            <div id="link_div" class="item form-group{{ $errors->has('message_url') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Link <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('message_url', null, array('placeholder' => 'message url','class' => 'form-control col-md-7 col-xs-12','id'=>'link' )) !!}
                                    @if ($errors->has('message_url'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('message_url') }}</strong>
                                        </span>
                                    @endif

                                </div>
                            </div>
                            <div id="internal_div" style="display: none;">
                            <div class="item form-group {{ $errors->has('cat_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category">Category<span class="required">*</span>
                                </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        {!!  Form::select('cat_id',$category,null, array('class' => 'form-control','placeholder'=>'','id'=>'cat_id')) !!}
                                        {{ Form::filedError('cat_id') }}
                                    </div>
                            </div>
                            <div id="sub_cat_div" class="item form-group {{ $errors->has('sub_cat_id') ? ' has-error' : '' }}" style="display: none">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category">Sub Category
                                </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        {!!  Form::select('sub_cat_id',$subCategory,null, array('class' => 'form-control','placeholder'=>'','id'=>'sub_cat_id')) !!}
                                        {{ Form::filedError('sub_cat_id') }}
                                    </div>
                            </div>
                           <!--  <div class="item form-group {{ $errors->has('product_id') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_id">Product</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        {!!  Form::select('vendor_product_id',$product,null, array('class' => 'form-control','placeholder'=>'','id'=>'product_id')) !!}
                                        {{ Form::filedError('product_id') }}
                                    </div>
                            </div> -->
                        </div>
                           <!--  <div class="item form-group{{ $errors->has('message_url') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">message url <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::text('message_url', null, array('placeholder' => 'message url','class' => 'form-control col-md-7 col-xs-12' )) !!}
                                    @if ($errors->has('message_url'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('message_url') }}</strong>
                                        </span>
                                    @endif

                                </div>
                            </div> -->
                            <div class="item form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">message <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::textarea('message', null, array('placeholder' => 'message','class' => 'form-control col-md-7 col-xs-12','id'=>'editor' )) !!}
                                    @if ($errors->has('message'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('message') }}</strong>
                                        </span>
                                    @endif

                                </div>
                            </div>

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

   {!! $validator !!}
    <!-- /page content -->
@endsection
@push('scripts')
    <script src="{{asset('public/js/select2.min.js')}}"></script><!-- 
    <script src="{{ asset('public/ckeditor/ckeditor.js')}}"></script> -->
    <script>
        $(document).ready(function () {
             //console.log(users);

            $('.select2-multiple').select2({
                placeholder: "User",
                allowClear: true
            });

        });
        

        //CKEDITOR.replace( 'editor' );
        $('#select_all').click( function() {
            if($(this). prop("checked") == true){
                $('#user_ids option').prop('selected', true);
                 var myHtml = "All Selected";
                /*$("#user_ids option").trigger("change");
                $('#user_ids option :selected').each(function(i, selected) {
                var selectedValue = $(this).val();
                    $('.select2-selection__choice').eq(i).text(selectedValue);
                });
                var users = <?php echo json_encode($users) ?>;
                //console.log(users);
                var myHtml = "<span class='select2-selection__clear' data-select2-id='88'>×</span><li class='select2-search select2-search--inline'><input class='select2-search__field valid' type='search' tabindex='0' autocomplete='off' autocorrect='off' autocapitalize='none' spellcheck='false' role='textbox' aria-autocomplete='list' placeholder='' style='width: 0.75em;' aria-invalid='false'></li>";
                $.each( users, function( i, item ) {
                    myHtml += "<li class='select2-selection__choice' title='test test' data-select2-id="+i+"><span class='select2-selection__choice__remove' role='presentation'>×</span>" + item + "</li>";

                    //myHtml += "<li class='select2-selection__choice' title='test test' data-select2-id="+i+">" + item + "</li>";
                });*/
            }else{
                $('#user_ids option').prop('selected', false);
                myHtml = "";

            }
               
                $( ".select2-selection__rendered" ).html( myHtml );
        });

        
        //Home dropdown selected 
        $("#link_type").val('home').trigger('change');
        $("#link_div").hide();
        $("#internal_div").hide();

        $("#link_type").change(function() {
            if ($(this).val() == 'internal') {
                $("#link").val();
                $("#internal_div").show();
                $("#cat_id").attr('required', 'required');
                $("#link_div").removeClass("has-error");
                $("#link").removeClass("has-error");
                $("#link_div").hide();
                $("#link").val("");


            } else if ($(this).val() == 'home') {
                selectHomeLink();
            } else {
                $("#link_div").addClass("has-error");
                $("#link").addClass("has-error");
                $("#link_div").show();
                $("#internal_div").hide();
                $("#link").val("");


            }
        });

        function selectHomeLink() {
            $("#link_div").hide();
            $("#internal_div").hide();
            $("#link").val("homelink");

        }

        $("#cat_id").change(function(){
            if($(this).val() == ''){
                $('#sub_cat_id option:not(:first)').remove();
                $('#product_id option:not(:first)').remove();
            }

            $.ajax({
                data: {
                    id:$(this).val()
                },
                method:'post',
                url: "{!! route('offer-slider.sub-cat') !!}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( response ) {
                console.log(response);
                    if(response.status == 'true'){
                       $("#sub_cat_div").show();
                       //$('#sub_cat_id').children('option:not(:first)').remove();
                        $('#sub_cat_id option:not(:first)').remove();
                        $('#sub_cat_id').append($("<option selected value=''>Select Sub Category</option>"));
                       $.each(response.data, function(key, value) {
                             $('#sub_cat_id')
                                 .append($("<option></option>")
                                 .attr("value",key)
                                 .text(value));
                        });
                    
                    $('#product_id option:not(:first)').remove();
                    $('#product_id').append($("<option selected value=''>Select Product</option>"));
                    $.each(response.productData, function(key, value) {
                             $('#product_id')
                                .append($("<option></option>")
                                .attr("value",key)
                                .text(value));
                        });
                       if(response.data.length==0){
                         $("#sub_cat_div").hide();
                       }
                    }

                    if(response.status == 'false'){
                        console.log('here');
                        $("#sub_cat_div").hide();
                    }
                
            },
                error: function( response ) {
                   /* new PNotify({
                        title: 'Error',
                        text: 'something is wrong',
                        type: "error",
                        styling: 'bootstrap3'
                    });*/
                }
            });
        });
        $("#sub_cat_id").change(function(){

            if($(this).val()==''){
             $('#product_id').children('option:not(:first)').remove();
            }
            $.ajax({
                data: {
                    id:$(this).val()
                },
                method:'post',
                url: "{!! route('offer-slider.sub-cat') !!}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( response ) {
                console.log(response);
                    if(response.status == 'true'){
                    $('#product_id').children('option:not(:first)').remove();
                    $('#product_id').append($("<option selected value=''>Select Product</option>"));
                    $.each(response.productData, function(key, value) {
                             $('#product_id')
                                .append($("<option></option>")
                                .attr("value",key)
                                .text(value));
                        });
                       
                    }

                    if(response.status == 'false'){
                        console.log('here');
                       // $("#sub_cat_div").hide();
                    }
                
            },
                error: function( response ) {
                   /* new PNotify({
                        title: 'Error',
                        text: 'something is wrong',
                        type: "error",
                        styling: 'bootstrap3'
                    });*/
                }
            });
        });

    </script>
@endpush
