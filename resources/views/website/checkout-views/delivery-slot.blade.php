@if(!empty($customer_id) && !empty($billing_id) && !empty($shipping_id))
<form method="post" id="delivery_slot_form" class="py-20px">
    @csrf
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group required">
                <label class="control-label">Delivery Date</label>
                <input name="delivery_date" class="form-control border-form-control date" id="date" type="text" readonly
                    value="@if(!empty($delivery_slot['delivery_date'])) {{$delivery_slot['delivery_date']}} @endif">
                <span class="error error_delivery_date text-danger"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group required">
                <label class="control-label">Delivery Time</label>
                <select name="delivery_time" class="form-control">
                    <option value="">Select Please</option>
                    @if (!empty($time_slot))
                    @foreach ($time_slot as $time)
                    <option @if(!empty($delivery_slot['delivery_time'])) @if($delivery_slot['delivery_time']==$time)
                        {{'selected'}} @endif @endif value="{{$time}}">{{$time}}</option>
                    @endforeach
                    @endif

                </select>
                <span class="error error_delivery_time text-danger"></span>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-secondary mb-2 btn-lg pull-right" onclick="save_delivery_slot()">NEXT</button>
</form>
@else
<div></div>
@endif

<script>
    $(document).ready(function () {
        $("#date").datepicker({
            autoclose: true,
            todayBtn: "linked",
            todayHighlight: !0,
            orientation: "bottom left",
            format: "yyyy-mm-dd",
            templates: {
                leftArrow: '<i class="fa fa-angle-left"></i>',
                rightArrow: '<i class="fa fa-angle-right"></i>'
            }
        });
    });
</script>