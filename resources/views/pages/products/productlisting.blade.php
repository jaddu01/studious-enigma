@extends('layouts.app')
@section('content')
<section class="topnave-bar">
	<div class="container">
	<ul>
	<li><a href="">Home</a> </li>
	<li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
	<li>Shop</li>	
	</ul>
	</div>	
</section>

<section class="product-listing-body">
	<div class="container">
		<div class="row">
		<div class="col-sm-4 col-md-3">
		<div class="browse-categories">
		
		<div class="left_navtab">
		<h2>Browse Categories</h2>

      <div class="left_navarea"> 
        <!-- Navigation -->
        <div class="mainNav">
          <ul>
          	
            <li><a href=""> Snackes</a>
			  <ul>
			<li><a href=""> Sub Categories</a></li>
            <li><a href=""> Sub Categories</a></li>
            <li><a href=""> Sub Categories</a></li>
            <li><a href=""> Sub Categories</a></li>
			</ul>
			  
			  </li>
            <li><a href=""> Beauty & Hygenic</a>
			 <ul>
			<li><a href=""> Sub Categories</a></li>
            <li><a href=""> Sub Categories</a></li>
            <li><a href=""> Sub Categories</a></li>
            <li><a href=""> Sub Categories</a></li>
			</ul>
			  </li>
			  
            <li><a href=""> Hair Care</a></li>
            <li><a href=""> Berverages</a>
			  <ul>
			<li><a href=""> Sub Categories</a></li>
            <li><a href=""> Sub Categories</a></li>
            <li><a href=""> Sub Categories</a></li>
            <li><a href=""> Sub Categories</a></li>
			</ul>
			  
			  </li>
            <li><a href=""> Skin Care</a>
			  
			  <ul>
			<li><a href=""> Sub Categories</a></li>
            <li><a href=""> Sub Categories</a></li>
            <li><a href=""> Sub Categories</a></li>
            <li><a href=""> Sub Categories</a></li>
			</ul>
			  </li>
            <li><a href=""> Cleaning & Household</a></li>
            <li><a href=""> Fabric Conditioner</a></li>			  
            <li><a href=""> Dishwash & Cleaner</a></li>			  
            <li><a href=""> Fruits</a></li>
            <li><a href=""> Fresh Vegetable</a></li>
  
          </ul>

			
        </div>
      </div>
				
    </div>	
		
		<div class="filter-area">
		<h2>Filter By</h2>
			
		<h3> Filter by price </h3>	
		<img src="public/images/filter.png" alt="logo">
			
		<div class="filter-btn"> <button type="button" class="common-btn">Filter</button> </div>	
		</div>	
		
		<div class="brand-color-area">
		<h3>Brands</h3>
			
		<ul class="brand-detail">
			
		<li>
		<span>
          <input type="checkbox"  name="radio-group" id="test1">
          <label for="test1">The Solas</label>
         </span> 
		</li>	
			
		<li>
		<span>
          <input type="checkbox"  name="radio-group" id="test2">
          <label for="test2">Dave’s Killer Bresad</label>
         </span> 
		</li>
			
		<li>
		<span>
          <input type="checkbox"  name="radio-group" id="test3">
          <label for="test3">Sunbeam Bread</label>
         </span> 
		</li>	
			
		<li>
		<span>
          <input type="checkbox"  name="radio-group" id="test4">
          <label for="test4">Kit Kat</label>
         </span> 
		</li>			
			
		<li>
		<span>
          <input type="checkbox"  name="radio-group" id="test5">
          <label for="test5">Oreo</label>
         </span> 
		</li>	

			
		</ul>
		
			
		<ul class="brand-detail color-detail">
		<h3>Brands</h3>	
		<li>
		<span>
          <input type="checkbox"  name="radio-group" id="test6">
          <label for="test6">All</label>
         </span> 
		</li>	
			
		<li>
		<span>
          <input type="checkbox"  name="radio-group" id="test7">
          <label for="test7">Red</label>
         </span> 
		</li>
			
		<li>
		<span>
          <input type="checkbox"  name="radio-group" id="test8">
          <label for="test8">Green</label>
         </span> 
		</li>	
			
		<li>
		<span>
          <input type="checkbox"  name="radio-group" id="test9">
          <label for="test9">Blue</label>
         </span> 
		</li>			
			
		

			
		</ul>	
			
			
		</div>	
			
			
		</div>
		
		<div class="listing-add-area">
		<img src="public/images/listing-add.png" alt="img">	
		
		</div>	
			
		</div>
		
		<div class="col-sm-8 col-md-9">
		<div class="must-have-product listing-product">
<ul>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>


<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>

<li>
<div class="must-have-product-box"><img src="public/images/mostsearched-product01.jpg" alt="img"> <span class="heart-icon"><i class="fa fa-heart" aria-hidden="true"></i>
</span> </div>
<div class="savar-product-content">
<p>$110.15  <span class="discount-price">$149.15</span>  </p>
<h4>Tata Tea Gold <span>250 Gm (Super saver pack)</span>  </h4>
<button class="add-to-card-btn">Add To Cart</button>
</div>
</li>
	

</ul>
</div>
			
		<div class="bottom-pagination">
			<ul>
			<li> <a href=""><i class="fa fa-arrow-left" aria-hidden="true"></i></a> </li>
			<li class="active"> <a href="">1</a> </li>
			<li> <a href="">2</a> </li>
			<li> <a href="">3</a> </li>	
			<li> <a href="">4</a> </li>
			<li> <a href=""><i class="fa fa-arrow-right" aria-hidden="true"></i></a> </li>	
			</ul>
		
		</div>	
			
		</div>
		</div>
	</div>	
</section>

@endsection