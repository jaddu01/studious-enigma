@extends('admin.layouts.app')

@section('title', 'Permission Access |')

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

                                {!! Form::open(['route' => 'permission_access.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                                {{csrf_field()}}
                                <span class="section">Permission Access</span>



                                <div class="item form-group {{ $errors->has('access_level_id') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        {!!  Form::select('access_level_id', $accessLevels,null, ['class' => 'form-control col-md-7 col-xs-12','placeholder'=>'Please select']) !!}
                                        {{ Form::filedError('access_level_id') }}
                                    </div>
                                </div>

                            <div id="data_continner"></div>




                                <div class="ln_solid"></div>
                                <div class="row">
                                <div class="form-group" id="access_submit_div" style="display: none">
                                    <div class="col-md-offset-6">
                                       {{-- <button type="submit" class="btn btn-primary">Cancel</button>--}}
                                        {!!  Form::submit('Submit',array('class'=>'btn btn-success','id'=>'access_submit')) !!}
                                    </div>
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
    <script>
        $(document).ready(function(){
            $("#access_submit").attr('disabled','disabled');
        });
        $("[name=access_level_id]").change(function () {
            if($(this).val()== ''){
                $("#access_submit_div").hide();
                $("#data_continner").html('');
                $("#access_submit").attr('disabled','disabled');
                return false;
            }else{
                $("#access_submit_div").show();
                
                 $("#access_submit").removeAttr('disabled');
            }
            $.ajax({
                data: {
                    access_level_id:$(this).val(),
                },
                type: "PATCH",
                url: "{!! route('permission_access.ajax') !!}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( data ){
					//alert(data.data.html);
                    $("#data_continner").html(data.data.html);

                },
                error: function(data) {

                }
            });
        });


    </script>
@endpush
