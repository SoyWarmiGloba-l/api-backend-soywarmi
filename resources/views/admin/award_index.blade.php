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
<x-adminlte-card id="cardSaveEdit" theme="maroon" title="Añadir Pregunta" theme-mode="outline" {{--
    collapsible="collapsed" --}}>
    <form id="formSave" action="{{ route('award.save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col">
                    <label for="question">Titulo</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Titulo">
                </div>
                <input type="hidden" name="save" id="saveType" value="true">
                <input type="hidden" name="id" id="id" value="0">
            </div>
            <div class="row">
                <div class="col">
                    <label for="answer">Descripcion</label>
                    <input type="text" class="form-control" id="description" name="description"
                        placeholder="Descripcion">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <x-adminlte-input-file name="fileIcon" igroup-size="sm" placeholder="Subir icono">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-lightblue">
                                <i class="fas fa-upload"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-file>
                </div>
                <div class="col">
                    <div class="row" id="dataImage">
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
                <th>Titulo</th>
                <th>Resumen</th>
                <th>Icono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($awards as $award)
            <tr>
                <td>{{ $award->id }}</td>
                <td>{{ $award->title }}</td>
                <td>{{ $award->description }}</td>
                <td>
                    <img class="img-fluid" src="{{ asset('/storage/' . $award->icon) }}">
                </td>
                <td colspan="2">
                    <div class="row">
                        <div class="col">
                            <x-adminlte-button label="Editar" onClick="changeData({{ $award->id }})" data-toggle="modal"
                                data-target="#modalCustom" class="bg-teal btn-outline-info" />
                        </div>
                        <div class="col">
                            <form action="{{ route('award.delete', $award->id) }}" method="POST">
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
    url: "{{ route('award.get') }}",
    type: "POST",
    data: {
    "_token": $("meta[name='csrf-token']").attr("content"),
    id: id,
    },
    success: function (data) {
    var dataObject = data.data;
    $('#title').val(dataObject.title);
    $('#description').val(dataObject.description);
    $('#saveType').val(false);
    $('#id').val(dataObject.id);
    let cardSaveEdit = document.getElementById('cardSaveEdit');
    cardSaveEdit.classList.remove('collapsed-card');
    $('#dataImage').empty();
    $('#dataImage').append( '<div class="col-4">' + '<div class="card">' + '<div class="card-body">' + '<img src="' +
                    'http://api-backend-soywarmi.test/storage/' + dataObject.icon + '" class="img-fluid" alt="Responsive image">' + '</div>' + '</div>' + '</div>' );
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