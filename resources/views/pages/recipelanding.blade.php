@extends('layouts.app')
@section('content')
@push('css')
  <style type="text/css">
  li.recipe-li{ min-height: 230px !important; }
  .recipe-box{ min-height: 180px !important; }
  .recipe-bottom-box{ min-height: 80px !important; }
  </style>
@endpush

<section class="inner-banner-area">
	<img src="public/images/inner-banner.png" alt="img">
</section>

<section class="section-area">
<div class="container">
<div class="must-have-product related-product mt">
<h2>All Recipes Categories</h2>
<div class="row">
<?php     $categories = Helper::RecipeCategory_arr(); ?>
@foreach($categories as $category)
<div class="col-xs-15">
<div class="col-five-box">
<a href="{{url('/list/recipe-listing/'.$category->slug)}}">
<div class="must-have-product-box recipe-box"><img src="{{$category->image}}" alt="img">  </div>
<div class="recipes-landding-box-hedding"> <h3>{{$category->name}}</h3> </div>
</a>
</div>	
</div>
@endforeach

</div>
</div>
</div>	
</section>
@endsection


  @push('script')
<script>
          $('#preloader').css('display','block');
      $(document).ready(function () {
        $('#preloader').css('display','none'); 
        })

    </script>
    @endpush
