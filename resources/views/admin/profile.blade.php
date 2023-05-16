@extends('admin.layouts.app')

@section('title', 'Dashboard')

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
      <div class="page-title">
        <div class="title_left">
          <h3>User Profile</h3>
        </div>
      </div>
      <div class="clearfix"></div>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">

            <div class="x_content">
               {!! Form::model($user,['url' => array('admin/update-profile',$user->id),'method'=>'post','class'=>'form-horizontal form-label-left ','enctype'=>'multipart/form-data','id'=>'form-admin-profile']) !!}
              <div class="col-md-3 col-sm-3 col-xs-12 profile_left">

                <div class="profile_img" >

                <input type="file" name="image" id="profile_img"/>

                  <!-- end of image cropping -->
                  <div id="crop-avatar">
                     @if( $errors->has('image'))
                              {{ Form::filedError('image') }}
                            @endif
                    <!-- Current avatar -->
                    @if($user->image)
                     <img id="image_upload_preview" class="profile_img mg-responsive avatar-view" src="{{$user->image}}" style="max-height: 225px;max-width: 225px;" />
                    @else
                    <img id="image_upload_preview" class="profile_img mg-responsive avatar-view" src="{{ asset('public/images/picture.jpg') }}" alt="Change the avatar" style="max-height: 225px;max-width: 225px;" /> 
                    @endif
                   <!--  <img class="img-responsive avatar-view" src="{{ asset('public/images/picture.jpg') }}" alt="Avatar" title="Change the avatar"> -->

                    <!-- /.modal -->

                    <!-- Loading state -->
                    <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                  </div>
                  <!-- end of image cropping -->

                </div>
                <h3>{{ Auth::guard('admin')->user()->name}} {{Auth::guard('admin')->user()->last_name}}({{Auth::guard('admin')->user()->role=='admin' ? Auth::guard('admin')->user()->role :Auth::guard('admin')->user()->user_type }})</h3>

                <ul class="list-unstyled user_data">
                  <li><i class="fa fa-envelope-o user-profile-icon"></i> {{Auth::guard('admin')->user()->email}}
                  </li>

                  <li>
                    <i class="fa fa-male user-profile-icon"></i> {{Auth::guard('admin')->user()->gender}}
                  </li>

                  <li class="m-top-xs">
                    <i class="fa fa-phone user-profile-icon"></i> {{Auth::guard('admin')->user()->phone_number}}
                  </li>
                  <li><i class="fa fa-location-arrow user-profile-icon"></i> {{Auth::guard('admin')->user()->address}}
                  </li>
                </ul>



              </div>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Profile</a>
                    </li>
                    <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Change Password</a>
                    </li>

                  </ul>
                  <div id="myTabContent" class="tab-content">

                    <div role="tabpanel" class="tab-pane fade active in " id="tab_content3" aria-labelledby="profile-tab">
                     
                      {{csrf_field()}}
                      {{method_field('put')}}
                      <div class="row">

                        <div class="col-sm-6">
                          <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                            {!!  Form::text('name', null, array('class' => 'form-control custom_input','placeholder'=>'Name')) !!}
                            @if( $errors->has('name'))
                              {{ Form::filedError('name') }}
                            @endif

                          </div>
                        </div>

                        <div class="col-sm-6">
                          <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            {!!  Form::text('email', null, array('class' => 'form-control custom_input','placeholder'=>'Email')) !!}
                            @if( $errors->has('email'))
                              {{ Form::filedError('email') }}
                            @endif

                          </div>
                        </div>

                       
                        <div class="col-sm-6">
                          <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }}">
                            {!!  Form::select('gender', Helper::$gender,null, array('class' => 'form-control custom_input','placeholder'=>'Gender')) !!}
                            @if( $errors->has('gender'))
                              {{ Form::filedError('gender') }}
                            @endif

                          </div>
                        </div>

                        <div class="col-sm-6">
                          <div class="form-group {{ $errors->has('phone_number') ? ' has-error' : '' }}">
                            {!!  Form::text('phone_number', null, array('class' => 'form-control custom_input','placeholder'=>'Phone Number')) !!}
                            @if( $errors->has('phone_number'))
                              {{ Form::filedError('phone_number') }}
                            @endif

                          </div>
                        </div>

                        <div class="col-sm-6">
                          <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                            {!!  Form::text('address', null, array('class' => 'form-control custom_input','placeholder'=>'Address')) !!}
                            @if( $errors->has('address'))
                              {{ Form::filedError('address') }}
                            @endif

                          </div>
                        </div>

                        <div class="col-sm-12 text-center">
                          {!!  Form::submit('Submit',array('class'=>'btn btn-success')) !!}
                        </div>
                      </div>

                      {!! Form::close() !!}

                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                      {!! Form::model($user,['url' => array('admin/update-profile',$user->id),'method'=>'post','class'=>'form-horizontal form-label-left ','enctype'=>'multipart/form-data','id'=>'form-admin-password']) !!}
                      {{csrf_field()}}
                      {{method_field('put')}}
                      <div class="row">

                        <div class="col-sm-6">
                          <div class="form-group">
                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                              {!!  Form::password('password',  array('class' => 'form-control custom_input','placeholder'=>'Password')) !!}
                              @if( $errors->has('password'))
                                {{ Form::filedError('password') }}
                              @endif
                            </div>
                          </div>
                        </div>

                        <div class="col-sm-6">

                          <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            {!!  Form::password('password_confirmation',  array('class' => 'form-control custom_input','placeholder'=>'Confirm Password')) !!}
                            @if( $errors->has('password_confirmation'))
                              {{ Form::filedError('password_confirmation') }}
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
        </div>
      </div>
    </div>
  </div>


  {!! $validator->selector('#form-admin-profile') !!}
  {!! $validator->selector('#form-admin-password') !!}
@endsection
@push('scripts')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image_upload_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0])
        }
    }

    $("#profile_img").change(function () {
        readURL(this);
    });
</script>
@endpush
