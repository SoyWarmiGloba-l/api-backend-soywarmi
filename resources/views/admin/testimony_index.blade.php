@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Administrar los testimonios</h1>
@stop
@section('plugins.Select2', true)
@section('plugins.Summernote', true)
@section('content')
@if(session()->has('success'))
<x-adminlte-alert theme="success" title="Success">
    {{ session()->get('success') }}
</x-adminlte-alert>
@endif
<x-adminlte-card id="cardSaveEdit" theme="maroon" title="Añadir Testimonio" theme-mode="outline"
    collapsible="collapsed">
    <form id="formSave" action="{{ route('testimony.save') }}" method="POST">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="person">Personas registradas(Anónimo es vacío)</label>
                        <x-adminlte-select2 name="person" id="person">
                            <option selected disabled>Seleccione...</option>
                            @foreach($persons as $person)
                            <option value="{{ $person->id }}">{{ $person->name }}</option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                </div>
                <div class="col">
                    <label for="name">Titulo</label>
                    <input type="text" class="form-control" id="name" name="tittle" placeholder="Titulo">
                </div>
                <div class="col">
                    <label for="state">Estado(Check=Activo, Uncheck=Inactivo)</label>
                    <input type="checkbox" class="form-control" id="state" name="state" />
                </div>
                <input type="hidden" name="save" id="saveType" value="true">
                <input type="hidden" name="id" id="id" value="0">
            </div>
            <div class="row">
                <div class="col">
                    <label for="description">Descripción</label>
                    <x-adminlte-text-editor name="description" id="description" />
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
                <th>Titulo</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Persona</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($testimonies as $testimony)
            <tr>
                <td>{{ $testimony->id }}</td>
                <td>{{ $testimony->title }}</td>
                <td class="text-truncate" style="word-wrap: break-word;">{!! $testimony->description !!}</td>
                <td>{{ $testimony->status == 'active' ? 'Activo' : 'Inactivo' }}</td>
                <td>{{ $testimony->person->name }}</td>
                <td colspan="2">
                    <div class="row">
                        <div class="col">
                            <x-adminlte-button label="Editar" onClick="changeData({{ $testimony->id }})"
                                data-toggle="modal" data-target="#modalCustom" class="bg-teal btn-outline-info" />
                        </div>
                        <div class="col">
                            <form action="{{ route('testimony.delete', $testimony->id) }}" method="POST">
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
<script>
    function changeData(id)
    {
    $.ajax({
    url: "{{ route('testimony.get') }}",
    type: "POST",
    data: {
    "_token": $("meta[name='csrf-token']").attr("content"),
    id: id,
    },
    success: function (data) {
    var dataObject = data.data;
    $('#name').val(dataObject.title);
    $('#description').summernote('code', dataObject.description);
    $('#person').val(dataObject.person_id).trigger('change');
    $('#state').prop('checked', dataObject.status == 'active' ? true : false);
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