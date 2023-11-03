@extends('adminlte::page')

@section('title', 'News')

@section('content_header')
    <h1>Administrar las Noticias</h1>
@stop
@section('plugins.Select2', true)

@section('content')
    @if(session()->has('success'))
        <x-adminlte-alert theme="success" title="Success">
            {{ session()->get('success') }}
        </x-adminlte-alert>
    @endif
    <x-adminlte-card theme="maroon" theme-mode="outline">
        <form action="{{ route('news.save') }}" method="POST">
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
                    <input type="hidden" name="save" id="saveType" value="true">
                    <input type="hidden" name="id" id="id" value="0">
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="name">Descripcion</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Descripcion...." required></textarea>
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
        <table id="example" class="table table-striped table-bordered dt-responsive" style="width:100%;">
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
                        <td class="text-truncate" style="word-wrap: break-word;">{{ $new->description }}</td>
                        <td>{{ $new->eventType->title }}</td>
                        <td colspan="2">
                            <x-adminlte-button label="Editar" onClick="changeData({{ $new->id }})" data-toggle="modal" data-target="#modalCustom" class="bg-teal btn-outline-info"/>
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
    <link rel="stylesheet" href="{{ asset('assets/datatable/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatable/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatable/css/responsive.bootstrap4.min.css') }}">
@stop

@section('js')
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
                    $('#description').val(dataObject.description);
                    $('#eventType').val(dataObject.event_type_id).trigger('change');
                    $('#saveType').val(false);
                    $('#id').val(dataObject.id);
                }
            })
        }
    </script>
    <script>
        $('#example').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json'
            }
        });
    </script>
    <script src="{{ asset('assets/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/datatable/js/responsive.bootstrap4.min.js') }}"></script>
@stop
