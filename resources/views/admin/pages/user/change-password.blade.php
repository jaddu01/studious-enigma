@extends('admin.layouts.app')

@section('title', 'Change Password')

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
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet" />
@endpush
@section('content')
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
                        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">


                        <div class="x_content">
                        {!! Form::model($user,['url' => array('admin/change-password',$user->id),'method'=>'post','class'=>'form-horizontal form-label-left ','id'=>'form-user-password']) !!}
                      {{csrf_field()}}
                      {{method_field('put')}}
                      <div class="row">

                        <div class="col-sm-6">
                          <div class="form-group">
                            <div class="form-group {{ $errors->has('user_password') ? ' has-error' : '' }}">
                              {!!  Form::password('user_password',  array('class' => 'form-control custom_input','placeholder'=>'Password')) !!}
                              @if( $errors->has('user_password'))
                                {{ Form::filedError('user_password') }}
                              @endif
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-6">

                          <div class="form-group {{ $errors->has('user_password_confirmation') ? ' has-error' : '' }}">
                            {!!  Form::password('user_password_confirmation',  array('class' => 'form-control custom_input','placeholder'=>'Password Confirm')) !!}
                            @if( $errors->has('user_password_confirmation'))
                              {{ Form::filedError('user_password_confirmation') }}
                            @endif
                          </div>

                        </div>

                        <div class="col-sm-12 text-center">
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
    <!-- /page content -->

@endsection
@push('scripts')

<script src="{{asset('public/assets/validator/validator.min.js')}}"></script>

<!-- validator -->
<script>


    $('#form-user-password').submit(function(e) {
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

</script>
@endpush

@push('scripts')
  
@endpush