@extends('admin.layouts.app')

@section('title', ' Driver Assignment |')
@push('css')
    <link href="{{asset('public/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('public/css/bootstrap-datepicker.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/model.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet">
   <style type="text/css">
        .modal-content{width:30%!important;}
        .modal-content select {border-radius: 0;width: 74%;display: inline!important;height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;}
    </style>
@endpush
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
                        <div class="x_title">
                            <h2>Driver Assignment</h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="panel-body">
						
							
						{!! Form::open (array('route' => 'vendor-product.driverassignment', 'class' => 'form-inline','method' => 'post', 'novalidate' => 'novalidate','autocomplete'=>'off' ) ) !!}	
							
							
                        <input type='text' class='datepicker' name="date">
                        <button class="btn btn-primary" type="submit">Apply filter</button>
                         <a class="btn btn-primary" href="{!! route('vendor-product.driverassignment') !!}">Cancel filter</a>
                        {!! Form::close () !!}
                        
                        
                        
                            </div>
                            
                            <?php //echo "<pre>";print_r($newArray); 
                             if(!empty($newArray)){
                            foreach($newArray as $dataorderlist){
								
								?>
								
								
								
								
								<p><?php echo $dataorderlist['name'];?></p>
								
								
								
                            
                            <table class="table table-striped table-bordered" id="users-table">
                                <thead>
                                    <tr>
                                        <th>Slot</th>
                                        <th>Order ID</th>
                                        <th>Total</th>
                                        <th>Items</th>
                                        <th>Shopper</th>
                                        <th>Order Status</th>
                                        <th>Assignment Status</th>
                                      
                                        <th>Action</th>
                                    </tr>
                                    
                                    <?php foreach($dataorderlist['driver'] as $datadriver){?>
                                    
                                    
                                    
                                    
                                     <tr id="<?php echo 'tr_'.$datadriver['order_id'];?>">
                                        <td><?php echo $datadriver['delivery_time'];?></td>
                                        <td><?php echo $datadriver['order_code'];?></td>
                                        <td><?php echo $datadriver['total_amount'];?></td>
                                        <td><?php echo $datadriver['total_order'];?></td>
                                        <td><?php echo $datadriver['name'];?></td>
                                        <td><?php echo Helper::$order_status[$datadriver['order_status']];?></td>
                                        <td><?php echo Helper::$assigned_status[$datadriver['assigned_status']];?></td>
                                        <td><?php echo $datadriver['action'];?></td>
                                       
                                    </tr>
                                    
                                    
                                    <?php }?>
                                    
                                    
                                    
                                </thead>
                            </table>
                            
                            								<?php } }else{
                                                                echo "<p>No results found</p>";
                                                            }?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    
    <div id="myModal" class="modal">

  <!-- Modal content -->
  
  <div class="modal-content">
    <span class="close" data-dismiss="modal">&times;</span>
    
    {!! Form::open(array('route' => 'vendor-product.changeShopperAndDriver', 'class' => 'form','method' => 'post','id'=>'changeShopper_form', 'novalidate' => 'novalidate' )) !!}
     <label class="control-label col-md-12 col-sm-12  col-xs-12" for="name">Select Shopper</label>
    <div class="row item form-group">
    {!!  Form::select('shoper_id', $shopperList, array('class' => 'form-control select2-multiple','placeholder'=>'Shopper','id'=>"shoper_id")) !!}
    </div>
     <label class="control-label col-md-12 col-sm-12  col-xs-12" for="name">Select Driver
                        </label>
    <div class="row item form-group">
    {!!  Form::select('driver_id', $driverList, array('class' => 'form-control ','placeholder'=>'Drivers','id'=>"driver_id")) !!}
    </div>
					<input type="hidden" value="" name="order_id">
					<input type="hidden" value="driver" name="type">
 					<div class="row item form-group"><button  class="btn btn-success" id="assign_button"  >Save</button></div>
					{!! Form::close() !!}
  </div>

</div>   
    <!-- /page content -->
@endsection
@push('scripts')
<!-- FastClick -->
<script src="{{asset('public/assets/fastclick/lib/fastclick.js')}}"></script>

<!-- Datatables -->
<script src="{{asset('public/assets/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('public/assets/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
{{--<script src="{{asset('public/assets/datatables.net-scroller/js/datatables.scroller.min.js')}}"></script>--}}
<script src="{{asset('public/assets/pnotify/dist/pnotify.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.buttons.js')}}"></script>
<script src="{{asset('public/assets/pnotify/dist/pnotify.nonblock.js')}}"></script>
<script src="{{asset('public/js/bootstrap-toggle.min.js')}}"></script>
<script src="{{asset('public/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('public/js/select2.min.js')}}"></script>
<script>

$(window).load(function(){
   setTimeout(function(){ $('.alert-success').fadeOut() }, 2000);
});


  $('.datepicker' ).datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
   
    })


var modal = document.getElementById('myModal');

// Get the button that opens the modal


// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
} 


$("#assign_button").click(function(){
   $('#changeShopper_form').submit();
    modal.style.display = "none";
    setTimeout(location.reload.bind(location), 20000);

});


function changeShoper(id){
	
	$("input[name=order_id]").val(id);
    $("select[name=driver_id]").attr('id','driver_id_'+id);
    $("select[name=shoper_id]").attr('id','shopper_id_'+id);
    //var tr = "#tr_"+id;
    //var driver = $(tr).find("td:eq(4)").text();
    $.ajax({
                data: {
                    id:id
                },
                method:'post',
                url: "{!! route('vendor-product.get-driver-shopper') !!}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function( response ) {
                    console.log(response.data.shopper_id);
                    if(response.status == 'true'){
                        //$('#shopper_id_'+id+' option:selected').removeAttr('selected');
                        $('#shopper_id_'+id+' option[value='+response.data.shopper_id+']').prop('selected', true);
                         //$('#driver_id_'+id+' option:selected').removeAttr('selected');
                        $('#driver_id_'+id+' option[value='+response.data.driver_id+']').prop('selected', true);
                        modal.style.display = "block";
                    }

                },
                error: function( response ) {
                  modal.style.display = "block";
                }
            });
    
}
	

</script>











@endpush
