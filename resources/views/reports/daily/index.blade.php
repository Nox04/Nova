@extends('layouts.admin')

@section('title', trans('reports.summary.daily'))

@section('new_button')
<span class="new-button"><a href="{{ url('reports/daily') }}?print=1&day={{ request('day', $this_day) }}" target="_blank" class="btn btn-success btn-sm"><span class="fa fa-print"></span> &nbsp;{{ trans('general.print') }}</a></span>
@endsection

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'reports/daily', 'role' => 'form', 'method' => 'GET']) !!}
        <div id="items" class="pull-left" style="margin-left: 5px">
            <input id="star_field" class="datepicker" name="start" />
            <input id="end_field" class="datepicker" name="end" />
            {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-sm btn-default btn-filter']) !!}
        </div>
        {!! Form::close() !!}
    </div>

    @include('reports.daily.body')
</div>
<!-- /.box -->
@endsection

@push('js')
<script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
@if (language()->getShortCode() != 'en')
<script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/locales/bootstrap-datepicker.' . language()->getShortCode() . '.js') }}"></script>
@endif
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
@endpush

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $.fn.datepicker.dates['en'] = {days:["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"],daysShort:["Dom","Lun","Mar","Mié","Jue","Vie","Sáb"],daysMin:["Do","Lu","Ma","Mi","Ju","Vi","Sa"],months:["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],monthsShort:["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],today:"Hoy",monthsTitle:"Meses",clear:"Borrar",weekStart:1,format:"dd/mm/yyyy"};
        $('#star_field').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        }).datepicker("setDate","{{ request('start', $this_day) }}");
        $('#end_field').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        }).datepicker("setDate","{{ request('end', $this_day) }}");
    });
</script>
@endpush