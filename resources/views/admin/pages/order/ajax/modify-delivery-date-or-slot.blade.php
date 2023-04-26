<div class="item form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Load WorkDay<span class="required">:</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <p class="pname"><span>{{$day}}</span></p>
    </div>
</div>
<div class="item form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Select Slot<span class="required">:</span>
    </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <table class="table table-striped table-bordered" >
            <thead  class="success">
            <tr>
                <th>Action</th>
                <th>Slots</th>
                <th>Availability</th>
            </tr>
            </thead>
            <tbody>
            @forelse($delivaryDay->deliveryTime as $deliveryTime)
            <tr>
               {{-- <th>{!! (Helper::checkAvailabilityInTimeSlot($deliveryTime->id,$delivery_date) > 0 ? '<input type="radio" name="delivery_time_id" value="'.$deliveryTime->id.'">':'')!!}</th>--}}

                <th><input type="radio" name="delivery_time_id" value="{{$deliveryTime->id}}" {{(Helper::checkAvailabilityInTimeSlot($deliveryTime->id,$delivery_date) > 0 ? '':'disable')}} {{($deliveryTime->id == $order->delivery_time_id ? 'checked':'')}}></th>
                <th>{{$deliveryTime->to_time}}:{{$deliveryTime->from_time}}</th>
                <th>{{ (Helper::checkAvailabilityInTimeSlot($deliveryTime->id,$delivery_date) > 0 ? 'Availability('.Helper::checkAvailabilityInTimeSlot($deliveryTime->id,$delivery_date).')':'UnAvailability') }}</th>
            </tr>
            @empty
                <p>No users</p>
            @endforelse
            </tbody>
        </table>
    </div>
</div>