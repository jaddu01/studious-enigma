<div class="top_nav">

    <div class="nav_menu">
        <nav class="" role="navigation">
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
	
	<!--img src="{{asset('public/upload/bell.png')}}" alt="" style="width:30px;float:left;"-->
            <ul class="nav navbar-nav navbar-right"  id="admin-header-notification">
            
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                      <?php  if(Auth::guard('admin')->user()->image){$image = Auth::guard('admin')->user()->image;} ?>
                    @if(Auth::guard('admin')->user()->image)
                       <img class="" src="{{ url('storage/app/public/upload/'.$image) }}" />
                    @else
                    <img src="{{asset('public/images/img.jpg')}}" alt="">
                    @endif
                       
                        {{ Auth::guard('admin')->user()->first_name }}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="{{url('admin/profile')}}">  Profile</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out pull-right"></i> Log Out
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                
                
               <!--   <li><a href="{{route('admin.notification.unavailable')}}"><span class="badge bg-green" style="margin-left: -13px;

margin-top: -25px;">{{Helper::unavailableProducts()}}</span><img src="{{asset('public/upload/bell.png')}}" alt="" style="width:30px;float:left;"></a>
                        </li>
                 -->
                
                
                
                
                </li>
<!-- 
                <msg></msg> -->
                <?php $seg2 = Request::segment(2); 
                    $url  = $seg2;
                    $notify = '';
                    if($seg2 == 'notification'){
                        $seg3 = Request::segment(3);
                        
                            if($seg3 != ''){
                                $url  = $seg3;
                                $notification = $seg2;
                            }
                        
                    }
                    $data = Helper::getNotificationCount($url, $notify);
                 ?>
                 <!-- unavailable product notification -->
                  <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="badge bg-green" style="margin-left: -13px;

margin-top: -25px;">{{$data['uCount']}}</span><img src="{{asset('public/upload/bell.png')}}" alt="" style="width:30px;float:left;"> </a>
                          <ul class="dropdown-menu" style="width: 370px;">
                            <?php if(count($data['uNotification']) > 0){
                                $i = 1;
                                foreach($data['uNotification'] as $uValue){
                                $umessageData = json_decode($uValue->data);
                                $link = $data['link'];
                                ?>

                                 <li ><a href="{{$link}}" onclick="readNotification('<?php echo $uValue->id1; ?>')" >
                                    <?php if($data['message'] != ''){
                                        echo $data['message'];
                                         if(isset($umessageData->name)){echo '&nbsp;'.ucwords($messageData->name).'&nbsp;';}
                                          if(isset($umessageData->user_name)){echo '&nbsp;'.ucwords($messageData->user_name).'&nbsp;';}

                                    } else{
                                       
                                       /* if(isset($messageData->order_code)){echo '#'.$messageData->order_code.'&nbsp;&nbsp;&nbsp;';} */

                                         if(isset($messageData->message)){echo ucwords($messageData->message);}
                                         if(isset($umessageData->product_name)){echo '&nbsp;&nbsp;'.ucwords($umessageData->product_name).'&nbsp;&nbsp;&nbsp;';}
                                        
                                         echo "<br>";
                                         echo $uValue->created_at;

                                    } ?>
                                     </a>
                                 </li>
                                    <?php if(count($data['notification']) > 1 && $i != count($data['notification'])) {
                                        echo '<li role="separator" class="divider"></li>';
                                    } ?>

                          
                            <?php $i++; } }else{?>
                                 <li style="padding: 10px;"></li>
                                <li style="padding: 10px;">There is no unread notifications</li>
                                  <li style="padding: 10px;"></li>
                            <?php } ?>
                            
                          </ul><a style="padding: 0px 0px 15px 0px;line-height: 0px;" href="{{url('admin/notification/unavailable')}}">Unavailable</a>
                </li>
                <!-- end unavailable product notification -->
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="badge bg-green" style="margin-left: -13px;

margin-top: -25px;">{{$data['count']}}</span><img src="{{asset('public/upload/bell.png')}}" alt="" style="width:30px;float:left;"> </a>
                          <ul class="dropdown-menu" style="width: 370px;">
                            <?php if(count($data['notification']) > 0){
                                $i = 1;
                                 foreach($data['notification'] as $value){
                                $messageData = json_decode($value->data);
                                $link = $data['link'];
                                if($value->type == 'App\Notifications\AddressUpdate'){
                                    $link = url('admin/notification/address');
                                }
                                 if($value->type == 'App\Notifications\OrderStatus' || $value->type == 'App\Notifications\AllOrderStatus'){
                                    $link = url('admin/notification/order');
                                }
                                 if($value->type == 'App\Notifications\ProductUpdate' || $value->type == 'App\Notifications\ProductOutStockStatus' || $value->type == 'App\Notifications\NewProduct' || $value->type == 'App\Notifications\ManageProductUpdate'|| $value->type == 'App\Notifications\ManageOutStock'){
                                    $link = url('admin/notification/shopper');
                                }
                                 if($value->type == 'App\Notifications\ProductStatus'){
                                    $link = url('admin/notification/unavailable');
                                }
                                 if($value->type == 'App\Notifications\AddressUpdate'){
                                    $link = url('admin/notification/address');
                                }
                                ?>

                                 <li ><a href="{{$link}}" onclick="readNotification('<?php echo $value->id1; ?>')" >
                                    <?php if($data['message'] != ''){
                                        echo $data['message'];
                                         if(isset($messageData->name)){echo '&nbsp;'.ucwords($messageData->name).'&nbsp;';}
                                          if(isset($messageData->user_name)){echo '&nbsp;'.ucwords($messageData->user_name).'&nbsp;';}

                                    } else{
                                       
                                       /* if(isset($messageData->order_code)){echo '#'.$messageData->order_code.'&nbsp;&nbsp;&nbsp;';} */
                                        if(isset($messageData->message)){echo ucwords($messageData->message);}
                                         //echo $messageData->message;
                                         if(isset($messageData->product_name)){echo '&nbsp;&nbsp;'.ucwords($messageData->product_name).'&nbsp;&nbsp;&nbsp;';}
                                        
                                         echo "<br>";
                                         echo $value->created_at;

                                    } ?>
                                     </a>
                                 </li>
                                    <?php if(count($data['notification']) > 1 && $i != count($data['notification'])) {
                                        echo '<li role="separator" class="divider"></li>';
                                    } ?>

                          
                            <?php $i++; } }else{?>
                                 <li style="padding: 10px;"></li>
                                <li style="padding: 10px;">There is no unread notifications</li>
                                  <li style="padding: 10px;"></li>
                            <?php } ?>
                            
                          </ul><a style="padding: 0px 15px 15px 0px;line-height: 0px;" href="{{url('admin/notification/order')}}">Order Status</a>
                </li>


            </ul>
        </nav>
    </div>
    @include('flash-message')
</div>
