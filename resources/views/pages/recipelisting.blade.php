@extends('layouts.app')
@section('content')
@push('css')
  <style type="text/css">
  li.recipe-li{ min-height: 230px !important; }
  .recipe-box{ min-height: 180px !important; }
  .recipe-bottom-box{ min-height: 80px !important; }
  </style>
@endpush
    <section class="topnave-bar">
        <div class="container">
            <ul>
                <li><a href="{{url('/')}}">Home</a></li>
                <li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
                <li><a href="{{url('/recipe-landing')}}">Shop By Recipe</a></li>
                <li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
                <li><a href="{{url('/list/recipe-listing/'.$slug)}}">{{ucfirst($slug)}}</a></li>
            </ul>
        </div>
    </section>
<?php     $categories = Helper::RecipeCategory_arr();

   //echo "<pre>"; print_r($categories); die; ?>
    <section class="product-listing-body">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-md-3">
                    <div class="browse-categories">
                         <div class="nav">
                    <input type="checkbox" id="nav-check">
                    <div class="nav-header">
                    <div class="nav-title">
                    Filter
                    </div>
                    </div>
                    <div class="nav-btn">
                    <label for="nav-check">
                    <span></span>
                    <span></span>
                    <span></span>
                    </label>
                    </div>
                    <div class="nav-links">

                        <div class="left_navtab">
                            <h2>Browse Categories</h2>

                            <div class="left_navarea">
                                <!-- Navigation -->
                                <div class="mainNav">
                                    <ul>
                                        @foreach( $categories as  $category)
                                        <li><a href="{{url('/list/recipe-listing/'.$category->slug)}}"> {{$category->name}}</a> </li>
                                        @endforeach
                                       </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                     </div>
                    </div>

                    <div class="listing-add-area">
                        <img src="{{url('/')}}/public/images/listing-add.png" alt="img">
                    </div>
                </div>
                <div class="col-sm-8 col-md-9">
                    <div class="must-have-product listing-product mt">
                      @if(count($recipeies)>0)  <ul>
                            @foreach($recipeies as $key => $row)
                                <li class="recipe-li">
                                    <div class="must-have-product-box recipe-box">
                                       <?php  if(Auth::user()){ ?>
                                        <a href="{{ url('/recipe') }}/{{$row['translations'][0]['slug']}}">
                                              <?php } else { ?>
                                            <a data-target="#login_with_mobile" data-toggle="modal">
                                        <?php } ?>
                                            @if(!empty($row['image']))
                                                <img src="{{$row['image']['name']}}" alt="img">
                                              @else
                                               <img src="{{url('/storage/app/public/upload/404.jpeg')}}" alt="img">
                                            @endif
                                        </a>
                                       
                                    </div>
                                    <div class="savar-product-content recipe-bottom-box">
                                       
                                       <?php if(Auth::user()){ ?> 
                                       <a href="{{ url('/recipe') }}/{{$row['translations'][0]['slug']}}">
                                            <h4>{{$row['name']}}</h4>
                                        <button class="add-to-card-btn">View Details</button> </a>
                                        <?php } else { ?>
                                            <a data-target="#login_with_mobile" data-toggle="modal">
                                            <h4>{{$row['name']}}</h4>
                                        <button class="add-to-card-btn">View Details</button> </a>
                                        <?php } ?>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @else
                                  <h4 class"alert aler-info">Sorry!!!No Recipe Found</h4>
                        @endif
                    </div>
                    @include('pagination.default', ['paginator' => $recipeies])
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')

<script>
          $('#preloader').css('display','block');
      $(document).ready(function () {
        $('#preloader').css('display','none'); 
        })

    </script>
    @endpush