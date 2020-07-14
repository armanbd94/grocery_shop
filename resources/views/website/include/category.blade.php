@php
    $helper = new Helper();
@endphp
@if (!empty($helper->show_category()))
    {!! $helper->show_category() !!}
@endif
