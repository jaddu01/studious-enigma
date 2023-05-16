@extends('admin.layouts.app')

@section('title', 'Edit recipe')

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
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <style type="text/css">
        .img-wrap{
          position: relative;
          float: left;
          width: 20%;
          margin-right: 10px;
        }
        .close{
          font-size: 21px;
          font-weight: 700;
          line-height: 1;
          color: #f00;
          text-shadow: 0 1px 0 #fff;
          filter: alpha (opacity=20) ;
          opacity: .8;
          position: absolute;
          right: 0;
        }
    </style>
@endpush

@section('content')
    
    <!-- page content -->
    <div class="right_col" role="main">

        <div class="">
                        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">
                                {!! Form::model($recipe,['route' => ['recipe.update',$recipe->id],'method'=>'post','class'=>'form-horizontal form-label-left validation','enctype'=>'multipart/form-data']) !!}
                                {{csrf_field()}}
                                {{method_field('put')}}
                                <span class="section">Edit recipe</span>

                            

                            @foreach(config('translatable.locales') as $locale)
                            <?php if($locale=='en'){ $dir = 'ltr'; }
                                        else if($locale=='ar'){ $dir = 'rtl'; }?>
                                <div class="item form-group{{ $errors->has('name:'.$locale) ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Dish Name
                                        In {{$locale}}<span class="required">*</span>
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

                                        {!!  Form::textarea('description:'.$locale, null, array('placeholder' => 'Recipe step by step','class' => 'form-control col-md-7 col-xs-12','rows'=>'3','lang'=>$locale ,'dir'=>$dir )) !!}
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
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    {!!  Form::select('related_products[]', $related_products,null, array('class' => 'form-control col-md-7 col-xs-12 select2-multiple','multiple'=>'true')) !!}
                                    {{ Form::filedError('related_products') }}
                                </div>
                            </div>
                        
                             <div class="item form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Image 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">

                                    @foreach($recipe->images as $image)
                                        <div class="img-wrap">
                                            <span class="close" data-id="{{$image->id}}" onclick="deleteImage({{$image->id}},{{$recipe->id}})">&times;</span>
                                            <img src="{{$image->name}}" height="100" width="100"/>
                                        </div>                                        
                                    @endforeach

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
    <script type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! $validator !!}
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
   function deleteImage(imageId,productId){
            var r = confirm("Are you want to delete this image?");
            if (r == true) {
                //var object=$(data);
                //var id=object.data('id');  
                var id = imageId;  
                var productId = productId;           
                $.ajax({
                    data: {
                        id:id,
                        product_id : productId,                     
                        _method:'PATCH'
                    },
                    type: "PATCH",
                    url: "{!! route('admin.recipe.image') !!}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function( data ) {   
                    console.log(data);              
                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        $("span[data-id='" + id + "']").parent().remove();
                        //object.parent().remove();
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
                });
            }             
        }

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