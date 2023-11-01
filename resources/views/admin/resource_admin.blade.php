@extends('adminlte::page')

@section('title', 'Recursos')

@section('content_header')
<h1>Ver y subir recursos</h1>
@stop
@section('plugins.KrajeeFileinput', true)
@section('content')
    @if(session()->has('success'))
        <x-adminlte-alert theme="success" title="Success">
            {{ session()->get('success') }}
        </x-adminlte-alert>
    @endif
<x-adminlte-card theme="maroon" theme-mode="outline">
    <form action="{{ route('resource.save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="name">Tipo (word, power point, etc.</label>
                        <input type="text" class="form-control" id="name" name="type" placeholder="Tipo documento" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="name">Descripcion</label>
                        <textarea class="form-control" id="name" name="description" placeholder="Descripcion...." required></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    {{-- With a label, some plugin config, and error feedback disabled --}}
                    {{-- ['image', 'html', 'text', 'video', 'audio', 'flash', 'object'] --}}
                    @php
                        $config = [
                        'allowedFileTypes' => ['text', 'office', 'pdf', 'doc', 'docx', 'image', 'video'],
                        'browseOnZoneClick' => true,
                        'theme' => 'explorer-fa5',
                        ];
                    @endphp
                    <x-adminlte-input-file-krajee name="file" label="Subir elemento"
                                                  data-msg-placeholder="Choose a text, office or pdf file..." label-class="text-primary"
                                                  :config="$config" />
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-outline-danger btn-block">Subir</button>
                </div>
            </div>
        </div>
    </form>
</x-adminlte-card>
<x-adminlte-card theme="maroon" theme-mode="outline">
    {{-- Setup data for datatables --}}
    @php
    $heads = [
    'ID',
    'Nombre',
    ['label' => 'Descripción', 'width' => 40],
    ['label' => 'Tipo', 'no-export' => true, 'width' => 5],
    ['label' => 'Acciones', 'no-export' => true, 'width' => 5, 'orderable' => false],
    ];

    $config = [
    'data' => $resources,
    'order' => [[1, 'asc']],
    'columns' => [null, null, null, ['orderable' => false]],
    ];
    @endphp

    {{-- Minimal example / fill data using the component slot --}}
    <x-adminlte-datatable id="table1" :heads="$heads">
        @foreach($config['data'] as $row)
        <tr>
            @foreach($row as $cell)
                @if(!$loop->last)
                    <td>{!! $cell !!}</td>
                @endif
            @endforeach
            <td colspan="2">
                <a href="{{ asset( $row['url']) }}" target="_blank">
                    <button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                        <i class="fa fa-lg fa-fw fa-eye"></i>
                    </button>
                </a>
                <a href="{{ route('resource.delete', $row['id']) }}">
                    <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                        <i class="fa fa-lg fa-fw fa-trash"></i>
                    </button>
                </a>
            </td>
        </tr>
        @endforeach
    </x-adminlte-datatable>
</x-adminlte-card>

@stop

@section('css')
    <link href="{{ asset('assets/toast/css/toastr.min.css') }}" rel="stylesheet">
@stop

@section('js')
    <script src="{{ asset('assets/toast/js/toastr.min.js') }}"></script>
@stop
