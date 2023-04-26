
<div class="item form-group">
    @forelse($dataArray as $data)
    <div class="col-md-12 col-sm-12 col-xs-12">
        <p class="pname"><span> {{$data['day']}} </span> <span> {{ $data['date'] }} </span></p>
        <table class="table table-striped table-bordered" >
            <thead  class="success">
            <tr>
                <th>Slots</th>
                <th>Availability</th>
            </tr>
            </thead>
            <tbody>
            @forelse($data['data'] as $deliveryTime)
            <tr>
                <th>{{$deliveryTime->to_time}}-{{$deliveryTime->from_time}}</th>
                <th>{{ (Helper::checkAvailabilityInTimeSlot($deliveryTime->id,$data['date']) > 0 ? 'Availability('.Helper::checkAvailabilityInTimeSlot($deliveryTime->id,$data['date']).')':'UnAvailability') }}</th>
            </tr>
            @empty
                <p>No Data</p>
            @endforelse
            </tbody>
        </table>
    </div>
    @empty

    @endforelse
</div>
