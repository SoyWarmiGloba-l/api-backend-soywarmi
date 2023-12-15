@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Administración de personas</h1>
@stop
@section('plugins.Select2', true)
@section('plugins.Summernote', true)
@section('content')
@if(session()->has('success'))
<x-adminlte-alert theme="success" title="Success">
    {{ session()->get('success') }}
</x-adminlte-alert>
@endif
<x-adminlte-card id="cardSaveEdit" theme="maroon" title="Añadir Persona" theme-mode="outline" collapsible="collapsed">
    <form id="formSave" action="{{ route('people.save') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label for="eventType">Equipo</label>
                        <x-adminlte-select2 name="team" id="team">
                            <option selected disabled>Seleccione...</option>
                            @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                </div>
                <input type="hidden" name="save" id="saveType" value="true">
                <input type="hidden" name="id" id="id" value="0">
                <div class="col">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nombre">
                </div>
                <div class="col">
                    <label for="name">LastName</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="lastname">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="name">Segundo LastName</label>
                    <input type="text" class="form-control" id="secondlastname" name="secondlastname"
                        placeholder="secondlastname">
                </div>
                <div class="col">
                    <label for="name">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                </div>
                <div class="col">
                    <label for="name">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="name">Imagenes o videos (opcional)</label>
                        <x-adminlte-input-file-krajee id="kifPholder" name="kifPholder" igroup-size="sm"
                            data-msg-placeholder="Choose multiple files..." data-show-cancel="false"
                            data-show-close="false" preset-mode="minimalist" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="birthday">Nacimiento</label>
                    <x-adminlte-input name="birthday" id="birthday" type="date" />
                </div>
                <div class="col">
                    <label for="gender">Género</label>
                    <select name="gender" id="gender" class="form-control">
                        <option selected disabled>Seleccione...</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                </div>
                <div class="col">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="description">Descripción</label>
                    <x-adminlte-text-editor name="description" id="description" />
                </div>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row">
                        <h3>Imagenes subidas</h3>
                    </div>
                    <div class="row" id="dataImage">
                    </div>
                </div>
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
                <th>Nombre</th>
                <th>Equipo</th>
                <th>Rol</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($people as $person)
            <tr>
                <td>{{ $person->id }}</td>
                <td>{{ $person->name ." ". $person->lastname ." ". !empty($person->mother_lastname)}}</td>

                <td>{{ $person->team->name }}</td>
                <td>{{ $person->role->name }}</td>
                <td>{{ $person->email }}</td>
                <td colspan="2">
                    <x-adminlte-button label="Editar" onClick="changeData({{ $person->id }})" data-toggle="modal"
                        data-target="#modalCustom" class="bg-teal btn-outline-info" />
                    <form action="{{ route('people.delete', $person->id) }}" method="POST">
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
    function changeData(id)
    {
    $.ajax({
    url: "{{ route('people.get') }}",
    type: "POST",
    data: {
    "_token": $("meta[name='csrf-token']").attr("content"),
    id: id,
    },
    success: function (data) {
    var dataObject = data.data[0];

    $('#name').val(dataObject.name);
    $('#lastname').val(dataObject.lastname);
    $('#phone').val(dataObject.phone);
    $('#secondlastname').val(dataObject.mother_lastname ? dataObject.mother_lastname : '');
    $('#email').val(dataObject.email);
    $('#birthday').val(dataObject.birthday);
    $('#description').summernote('code', dataObject.description);
    $('#role').val(dataObject.role_id).trigger('change');
    $('#gender').val(dataObject.gender).trigger('change');
    $('#team').val(dataObject.team_id).trigger('change');
    $('#saveType').val(false);
    $('#id').val(dataObject.id);
    $('#dataEnd').val(dataObject.end_date);
$('#description').summernote('code', dataObject.description);
    let cardSaveEdit = document.getElementById('cardSaveEdit');
    cardSaveEdit.classList.remove('collapsed-card');

    //for js from inage
    $('#dataImage').empty();
    $('#dataImage').append( '<div class="col-4">' + '<div class="card">' + '<div class="card-body">' + '<img src="' +
                dataObject.photo + '" class="img-fluid" alt="Responsive image">' + '</div>' + '</div>' + '</div>' ); } }) }
function saveForm(){
let form = document.getElementById('formSave');
let saveType = document.getElementById('saveType');

form.submit();
}
    $('#example').DataTable({
    "responsive": true,
    "autoWidth": false,
    "language": {
    'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json'
    }
    });
</script>
@stop