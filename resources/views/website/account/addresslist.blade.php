<tbody>
    @if (!empty($addressList))
       @foreach ($addressList as $address)
       
       <tr  @if($address->is_default=='1') {!! "style='background:#eee'"; !!} @endif >
          <td>{{$address->first_name}}&nbsp;{{$address->last_name}}<br>
          {{$address->address}}<br>
          {{$address->district}}<br> 
          {{$address->city}}<br>                                                 
          Mobile : {{$address->mobile_no}}
        </td>
          <td class="td-align" width="100">
           <button data-id="{{$address->id}}" class="btn btn-success edit_address"><i class="fa fa-edit"></i></button>
           @if($address->is_default=='2')
           <button data-id="{{$address->id}}" type="button" class="btn btn-danger delete_address"><i class="fa fa-trash"></i></button>
           @endif
          </td>
       </tr>
       @endforeach
    @else   
       <tr>
          <td><span class="text-danger">Address Not found</span></td>
       </tr>
    @endif
   
  
</tbody>