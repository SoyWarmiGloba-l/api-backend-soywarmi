@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
@section('plugins.Select2', true)
@section('plugins.Summernote', true)
@if(session()->has('success'))
<x-adminlte-alert theme="success" title="Success">
    {{ session()->get('success') }}
</x-adminlte-alert>
@endif
<x-adminlte-card id="cardSaveEdit" theme="maroon" title="Añadir Actividad" theme-mode="outline" collapsible="collapsed">
    <form id="formSave" action="{{ route('teams.save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="eventType">Roles</label>
                        <x-adminlte-select2 name="role" id="role">
                            <option selected disabled>Seleccione...</option>
                            @foreach($roles as $rol)
                            <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                </div>
                <div class="col">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nombre">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="description">Descripción</label>
                    <x-adminlte-text-editor name="description" id="description" />
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <label for="inputSocial">Redes Sociales(registrar solo links)</label>
                    <input type="text" class="form-control" id="inputSocial" name="inputSocial"
                        placeholder="Pasos a realizar" autocomplete="off">
                </div>
                <div class="col">
                    <label>Redes Sociales(select)*</label>
                    <x-adminlte-select2 name="social[]" id="selectSocial" multiple>
                    </x-adminlte-select2>
                </div>
                <input type="hidden" name="save" id="saveType" value="true">
                <input type="hidden" name="id" id="id" value="0">
            </div>

            <div class="row">
                <button type="button" class="btn btn-outline-success btn-block" onclick="saveForm()">Guardar</button>
            </div>
        </div>
    </form>
</x-adminlte-card>
<x-adminlte-card theme="maroon" theme-mode="outline">
    <table id="example" class="table table-striped table-bordered dt-responsive" style="width:100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Rol</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teams as $team)
            <tr>
                <td>{{ $team->id }}</td>
                <td>{{ $team->role->name }}</td>
                <td>{{ $team->name }}</td>
                <td class="text-truncate" style="word-wrap: break-word;">{!! $team->description !!}</td>
                <td colspan="2">
                    <x-adminlte-button label="Editar" onClick="changeData({{ $team->id }})" data-toggle="modal"
                        data-target="#modalCustom" class="bg-teal btn-outline-info" />
                    <form action="{{ route('activity.delete', $team->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </form>
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
    $('#example').DataTable({
    "responsive": true,
    "autoWidth": false,
    "language": {
    'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json'
    }
    });
    function saveForm(){
    let form = document.getElementById('formSave');

    form.submit();
    }
    let inputSocial = document.getElementById('inputSocial');
    let selectSocial = document.getElementById('selectSocial');
    inputSocial.addEventListener('keypress', (e) => {
    if (e.keyCode === 13) {
    e.stopPropagation();
    e.preventDefault();
    let value = e.target.value;
    if (value === '') {
    return;
    }
    let option = document.createElement('option');
    option.value = value;
    option.text = value;
    selectSocial.appendChild(option);
    alert('Red Social Agregada');
    e.target.value = '';
    }
    });
</script>
<script>
    function changeData(id)
    {
    $.ajax({
    url: "{{ route('teams.get') }}",
    type: "POST",
    data: {
    "_token": $("meta[name='csrf-token']").attr("content"),
    id: id,
    },
    success: function (data) {

    var dataObject = data.data[0];

    $('#name').val(dataObject.name);
    $('#description').summernote('code', dataObject.description);
    $('#role').val(dataObject.role_id).trigger('change');
    $('#saveType').val(false);
    $('#id').val(dataObject.id);

    var $selectRequirements = $('#selectSocial');
    $selectRequirements.empty();

    $.each(dataObject.social_networks, function (key, value) {
    $selectRequirements.append('<option value="' + value + '">' + value + '</option>');
    })
    let cardSaveEdit = document.getElementById('cardSaveEdit');
    cardSaveEdit.classList.remove('collapsed-card');

        } }) }
</script>
@stop