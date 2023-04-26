<div id="registerModal"  class="modal  fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <?php echo "fdgdh"; die; ?>
    <div class="modal-content">
      <div class="modal-header custom_popup ">
        <div class="col-md-8"> <img src="{{url('public/images/logo.png')}}" height="30px" alt="img"></div> 
        <div class="col-md-4"><button type="button" class="close" data-dismiss="modal">X</button></div>
      </div>
      <div class="modal-body clearfix">
       
        <div class="modal-body-rightcol">  
         <h2  class="modal-title" id="registerModal">{{ __('Register') }}</h2>
         <p>Please provide your details to Signup on Zadcart</p>
         
         
                <form method="POST" id="registerForm">
                    @csrf

                    <div class="form-group row">
                        <label for="nameInput" class="col-md-12 col-form-label text-md-right">{{ __('Name') }}</label>

                        <div class="col-md-12">
                            <input id="nameInput" type="text" class="form-control" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus>

                            <span class="invalid-feedback" role="alert" id="nameError">
                                <strong></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="emailInput" class="col-md-12 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-12">
                            <input id="emailInput" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email">

                            <span class="invalid-feedback" role="alert" id="emailError">
                                <strong></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="passwordInput" class="col-md-12 col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="col-md-12">
                            <input id="passwordInput" type="password" class="form-control" name="password" required autocomplete="new-password">

                            <span class="invalid-feedback" role="alert" id="passwordError">
                                <strong></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-12 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                        <div class="col-md-12">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator !!}
    <!-- /page content -->
@section('scripts')
@parent

<script>
$(function () {
    $('#registerForm').submit(function (e) {
        e.preventDefault();
        let formData = $(this).serializeArray();
        $(".invalid-feedback").children("strong").text("");
        $("#registerForm input").removeClass("is-invalid");
        $.ajax({
            method: "POST",
            headers: {
                Accept: "application/json"
            },
            url: "{{ route('register') }}",
            data: formData,
            success: () => window.location.assign("{{ route('home') }}"),
            error: (response) => {
                if(response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function (key) {
                        $("#" + key + "Input").addClass("is-invalid");
                        $("#" + key + "Error").children("strong").text(errors[key][0]);
                    });
                } else {
                    window.location.reload();
                }
            }
        })
    });
})
</script>
@endsection