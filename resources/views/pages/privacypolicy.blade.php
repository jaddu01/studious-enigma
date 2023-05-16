@extends('layouts.app')
@section('content')


<section class="topnave-bar">
    <div class="container">
    <ul>
    <li><a href="">Home</a> </li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
    <li>{{$pageName}}</li>   
    </ul>
    </div>  
</section>

<section class="product-listing-body">
    <div class="container">
        <div class="row">
        <div class="col-md-12">
            <h3>{{$pageName}}}</h3>   
            <hr/>
             <?php echo $data; ?>
         </div>
        </div>
        </div>
</section>

@endsection