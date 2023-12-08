@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Administrar las Preguntas Frecuentes</h1>
@stop
@section('plugins.Select2', true)
@section('content')
@if(session()->has('success'))
<x-adminlte-alert theme="success" title="Success">
    {{ session()->get('success') }}
</x-adminlte-alert>
@endif
<x-adminlte-card id="cardSaveEdit" theme="maroon" title="Añadir Pregunta" theme-mode="outline" collapsible="collapsed">
    <form id="formSave" action="{{ route('faq.save') }}" method="POST">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col">
                    <label for="question">Pregunta</label>
                    <input type="text" class="form-control" id="question" name="question" placeholder="Pregunta">
                </div>
                <input type="hidden" name="save" id="saveType" value="true">
                <input type="hidden" name="id" id="id" value="0">
            </div>
            <div class="row">
                <div class="col">
                    <label for="answer">Respuesta</label>
                    <input type="text" class="form-control" id="answer" name="answer" placeholder="Respuesta">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="state">Estado</label>
                        <x-adminlte-select2 name="state" id="state">
                            <option selected disabled>Seleccione...</option>
                            <option value="active">Activo</option>
                            <option value="pending">Pendiente</option>
                            <option value="expired">Expirado</option>
                            <option value="deleted">Borrado</option>
                            <option value="open">Abierto</option>
                            <option value="closed">Cerrado</option>
                            <option value="inactive">Inactivo</option>
                        </x-adminlte-select2>
                    </div>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn btn-outline-success btn-block">Guardar</button>
            </div>
        </div>
    </form>
</x-adminlte-card>
<x-adminlte-card theme="maroon" theme-mode="outline">
    <table id="example" class="table table-striped table-bordered dt-responsive" style="width:100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Pregunta</th>
                <th>Respuesta</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($faqs as $faq)
            <tr>
                <td>{{ $faq->id }}</td>
                <td>{{ $faq->question }}</td>
                <td>{{ $faq->answer }}</td>
                <td>{{ $faq->status == 'active' ? 'Activo' : 'Inactivo' }}</td>
                <td colspan="2">
                    <div class="row">
                        <div class="col">
                            <x-adminlte-button label="Editar" onClick="changeData({{ $faq->id }})" data-toggle="modal"
                                data-target="#modalCustom" class="bg-teal btn-outline-info" />
                        </div>
                        <div class="col">
                            <form action="{{ route('faq.delete', $faq->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-adminlte-card>
@stop

@section('css')

@stop

@section('js')
<script src="{{ asset('assets/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<script>
    function changeData(id)
    {
    $.ajax({
    url: "{{ route('faq.get') }}",
    type: "POST",
    data: {
    "_token": $("meta[name='csrf-token']").attr("content"),
    id: id,
    },
    success: function (data) {
    var dataObject = data.data;
    $('#question').val(dataObject.question);
    $('#answer').val(dataObject.answer);
    $('#state').val(dataObject.status).trigger('change');
    $('#saveType').val(false);
    $('#id').val(dataObject.id);
    let cardSaveEdit = document.getElementById('cardSaveEdit');
    cardSaveEdit.classList.remove('collapsed-card');
    }});}
</script>
<script>
    $('#example').DataTable({
    "responsive": true,
    "autoWidth": false,
    "language": {
    'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json'
    }});
</script>
@stop