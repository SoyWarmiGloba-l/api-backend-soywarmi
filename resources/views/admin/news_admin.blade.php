@extends('adminlte::page')

@section('title', 'News')

@section('content_header')
<h1>Administrar las Noticias</h1>
@stop
@section('plugins.Select2', true)
@section('plugins.Summernote', true)

@section('content')
@if(session()->has('success'))
<x-adminlte-alert theme="success" title="Success">
    {{ session()->get('success') }}
</x-adminlte-alert>
@endif
<x-adminlte-card theme="maroon" theme-mode="outline">
    <form action="{{ route('news.save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="name">Tipo de Noticia</label>
                        <x-adminlte-select2 name="eventType" id="eventType">
                            <option selected disabled>Seleccione...</option>
                            @foreach($eventTypes as $eventType)
                            <option value="{{ $eventType->id }}">{{ $eventType->title }}</option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="name">Titulo</label>
                        <input type="text" class="form-control" id="tittle" name="tittle" placeholder="Titulo" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="name">Subir imagenes o videos (opcional)</label>
                        <x-adminlte-input-file-krajee id="kifPholder" name="kifPholder[]" igroup-size="sm"
                            data-msg-placeholder="Choose multiple files..." data-show-cancel="false"
                            data-show-close="false" multiple preset-mode="minimalist" />
                    </div>
                </div>
                <input type="hidden" name="save" id="saveType" value="true">
                <input type="hidden" name="id" id="id" value="0">
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="name">Descripcion</label>
                        <x-adminlte-text-editor name="description" id="description" required />
                    </div>
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
                <div class="col-12">
                    <button type="submit" class="btn btn-outline-danger btn-block">Publicar Noticia</button>
                </div>
            </div>
        </div>
    </form>
</x-adminlte-card>

<x-adminlte-card theme="maroon" theme-mode="outline">
    <table id="newsTable" class="table table-striped table-bordered" style="width:100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Titulo</th>
                <th>Descripción</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($news as $new)
            <tr>
                <td>{{ $new->id }}</td>
                <td>{{ $new->title }}</td>
                <td class="text-truncate">{!! $new->description !!}</td>
                <td>{{ $new->eventType->title }}</td>
                <td colspan="2">
                    <x-adminlte-button label="Editar" onClick="changeData({{ $new->id }})" data-toggle="modal"
                        data-target="#modalCustom" class="bg-teal btn-outline-info" />
                    <form action="{{ route('news.delete', $new->id) }}" method="POST">
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
                url: "{{ route('news.get') }}",
                type: "POST",
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    id: id,
                },
                success: function (data) {
                    var dataObject = data.data[0];
                    $('#tittle').val(dataObject.title);
                    $('#description').summernote('code', dataObject.description);
                    $('#eventType').val(dataObject.event_type_id).trigger('change');
                    $('#saveType').val(false);
                    $('#id').val(dataObject.id);
                    //for js from inage
                    for (var i = 0; i < dataObject.images.length; i++) {
                        var image = dataObject.images[i];
                        $('#dataImage').append(
                            '<div class="col-4">' +
                            '<div class="card">' +
                            '<div class="card-body">' +
                            '<img src="' + image.url + '" class="img-fluid" alt="Responsive image">' + '</div>' +
                            '</div>' +
                            '</div>');
                    }
                }
            })
        }
</script>
<script>
    $('#newsTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json'
            }
        });
</script>

@stop