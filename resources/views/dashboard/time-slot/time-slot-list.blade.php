@if(!empty($time_slot_list))
    @php
        $i=1;
    @endphp
    @foreach ($time_slot_list as $time)
        <tr>
            <td  class="text-center">{{$i++}}</td>
            <td  class="text-center">{{$time}}</td>
        </tr>
    @endforeach
@else 
    <tr><td class="text-center text-danger" colspan="2">No Data Available</td></tr>
@endif