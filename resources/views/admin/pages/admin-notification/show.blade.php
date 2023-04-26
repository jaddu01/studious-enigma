@extends('admin.layouts.app')

@section('title', ' order details |')
@push('css')
    <link href="{{asset('css/bootstrap-toggle.min.css')}}" rel="stylesheet">
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

                        <div class="x_content">

                            <span class="section"> Send Notification </span>
                             <div class="item form-group">
                                <label class="control-label col-md-2 " >User : </label>
                                 <?php
                                     $name= '';
                                 if($admin_notification->selection == null){
                                     $users  = \App\User::whereIn('id',$admin_notification->user_ids)->get();
                                     foreach ($users as $key=>$user){
                                         $name.=++$key.') '.$user->name.'<br>';
                                     }
                                 }else{
                                     $name = 'all';
                                 }
                                 ?>
                                {!! $name !!}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Message Heading: </label>
                                {{$admin_notification->message_heading}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Message Url: </label>
                                {{$admin_notification->message_url}}
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Image: </label>
                               <img src="{{$admin_notification->image}}" height="75px" width="75px  ">
                                <hr>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Message: </label>
                                {{$admin_notification->message}}
                                <hr>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-md-2 " >Created At: </label>
                                {{$admin_notification->created_at}}
                                <hr>
                            </div>

                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
			
            #print_div, #print_div * {
                visibility: visible;
            }
            #print_div {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
@endsection
@push('scripts')
    <script>
        function changeStatus(id,status){
            $.ajax({
				 url: "{!! route('admin.order.status') !!}",
            type: 'PATCH',
      // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
      data: {
        _method: 'PATCH',
        status : status,
        id : id,      
        _token: '{{ csrf_token() }}'
      },
                success: function( data ) {
                   
                    alertify.success("Success "+data.message);

                },
                error: function( data ) {
                    alertify.error("some thinng is wrong");

                }
            });
            
          
        }
        function myFunction() {
            var printContents = window.print();
            /* w=window.open();
             w.document.write(printContents);
             w.print();
             w.close();*/
        }
    </script>
@endpush
