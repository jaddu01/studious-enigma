@extends('admin.layouts.app')

@section('title', 'Add Recipe |')

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

                            {!! Form::open(['route' => 'recipe.store','method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}

                            {{csrf_field()}}
                            <span class="section">Add Recipe</span>
                            @foreach(config('translatable.locales') as $locale)
                             <?php if($locale=='en'){ $dir = 'ltr'; }
                                        else if($locale=='ar'){ $dir = 'rtl'; }?>
                                <div class="item form-group{{ $errors->has('name:'.$locale) ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Dish Name In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        {!!  Form::text('name:'.$locale, null, array('placeholder' => 'Dish Name','class' => 'form-control col-md-7 col-xs-12','lang'=>$locale ,'dir'=>$dir )) !!}
                                        @if ($errors->has('name:'.$locale))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name:'.$locale) }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="item form-group{{ $errors->has('description:'.$locale) ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Recipe In {{$locale}}<span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        {!!  Form::textarea('description:'.$locale, null, array('placeholder' => 'Recipe step by step','class' => 'form-control col-md-7 col-xs-12','rows'=>'3','lang'=>$locale ,'dir'=>$dir)) !!}
                                        @if ($errors->has('description:'.$locale))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('description:'.$locale) }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                    @endforeach

                                     <div class="item form-group ">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Excel For Ingredients</label>
                               <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" name="logo" id="xlsproduct" name="xlsproduct" class="form-control col-md-5 col-xs-12">
                                </div>
                                <a href="{{url('/public/sampleimport/Ingredientsxls.xlsx')}}">Sample file</a>
                        </div>
                             
                               <div class="item form-group {{ $errors->has('related_products') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Ingredients
                                          <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            {!!  Form::select('related_products[]', $related_products,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple','multiple'=>'true','id'=>'related_products')) !!}
                                           {{ Form::filedError('related_products') }}
                                           <span id="related_products[]-error" class="help-block error-help-block" style="display: none;"></span>
                                        </div>
                                    </div>

                                     <div class="item form-group {{ $errors->has('recipe_category') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Category
                                          <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            {!!  Form::select('recipe_category', $recipe_category,null, array('placeholder' => 'Recipe Category','class' => 'form-control col-md-7 col-xs-12 select2-multiple','id'=>'recipe_category')) !!}
                                           {{ Form::filedError('recipe_category') }}
                                           <span id="recipe_category-error" class="help-block error-help-block" style="display: none;"></span>
                                        </div>
                                    </div>


                       

                                    
                            <div class="item form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Image
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            <input type="file" id="image" name="image[]"
                                                   class="form-control col-md-7 col-xs-12" multiple>
                                            @if ($errors->has('image'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('image') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="item form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Status
                                            
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">

                                            {!!  Form::select('status', ['1'=>'Active','0'=>'Inactive'],null, array('class' => 'form-control col-md-7 col-xs-12')) !!}
                                            {{ Form::filedError('status') }}
                                        </div>
                                    </div>



                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            {{-- <button type="submit" class="btn btn-primary">Cancel</button>--}}
                                            {!!  Form::submit('Submit',array('class'=>'btn btn-success','id'=>'buttonDiv2')) !!}
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
    <script src="{{asset('public/js/select2.min.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
    <script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
    <script>
    $(document).ready(function() {
        $('.select2-multiple').select2();
});

$('#xlsproduct').change(function(){    
 alert('in  import xls');  
 //on change event  
    formdata = new FormData();
     if($(this).prop('files').length > 0)
    {
        file =$(this).prop('files')[0];
         formdata.append("import_file", file);
    }
  $.ajax({
   url:"{{ route('admin.recipe.importExcel') }}",
   method:"POST",
    data: formdata,
    processData: false,
    contentType: false,
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
           success: function( data ) {   
                   // console.log(data);  

$.each(data.valuess, function(k, v) {
    /// do stuff
// Set the value, creating a new option if necessary
if ($('#related_products').find("option[value='" + k + "']").length) {
    $('#related_products').val(k).trigger('change');
} else { 
    // Create a DOM Option and pre-select by default
     var newOption = new Option(v, k, true, true);
    // Append it to the select
    $('#related_products').append(newOption).trigger('change');
}   
});

     
                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        
                    },
                    error: function( data ) {
                        console.log(data);  
                        new PNotify({
                            title: 'Error',
                            text: data.responseJSON.message,
                            type: "error",
                            styling: 'bootstrap3'
                        });

                    }
  })    
 });

</script>
@endpush
