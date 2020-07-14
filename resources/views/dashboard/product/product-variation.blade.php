
@if (!empty($weightwise_price))
@php  $i=0; @endphp
    @foreach($weightwise_price as $item)

        @if ($item->is_default == 2)
            @php $i++;@endphp
            <tr>
            @if(in_array('Product Edit',session()->get('permission')))
            <td>
            <input type="checkbox" id="{{$item->id}}" data-sr="{{$i}}" data-weight="{{$item->weight}}" data-unitname="{{$item->unitname}}" data-price="{{$item->price}}" data-delete="{{$item->id}}" class="check_box"  />
            </td>
            @endif
            <td>{{$i}}</td>
            <td>{{$item->weight}}</td>
            <td>{{$item->unitname}}</td>
            <td>QAR {{number_format($item->price,2)}}</td>
            @if(in_array('Product Delete',session()->get('permission')))
            <td>
                <button type="button" data-id="{{$item->id}}" class="btn btn-danger reset-btn btn-sm price_delete_btn"><i class="fas fa-trash"></i></button>
            </td>
            @endif
            </tr>
        @endif
    @endforeach
@else 
<tr><td class="text-danger text-center" colspan="6">No Data Available</td></tr>
@endif
