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
    <form id="formSave" action="{{ route('activities.save') }}" method="POST" enctype="multipart/form-data">
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
                <div class="col">
                    <label for="dateEnd">fecha fin</label>
                    <x-adminlte-input name="dataEnd" id="dataEnd" type="date" />
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
                    <label for="inputAreas">Areas(registrar)</label>
                    <input type="text" class="form-control" id="inputAreas" name="inputAreas"
                        placeholder="Pasos a realizar" autocomplete="off">
                </div>
                <div class="col">
                    <label>Areas(select)*</label>
                    <x-adminlte-select2 name="areas[]" id="areas" multiple>
                    </x-adminlte-select2>
                </div>
                <input type="hidden" name="save" id="saveType" value="true">
                <input type="hidden" name="id" id="id" value="0">
            </div>
            <div class="row mb-2">
                <div class="col">
                    <label for="inputSteps">Pasos(registrar)</label>
                    <input type="text" class="form-control" id="inputSteps" name="inputSteps"
                        placeholder="Pasos a realizar">
                </div>
                <div class="col">
                    <label>Pasos(select)*</label>
                    <x-adminlte-select2 name="steps[]" id="steps" multiple>
                    </x-adminlte-select2>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="inputSteps">Requerimientos(registrar)</label>
                    <input type="text" class="form-control" id="inputReq" name="inputReq"
                        placeholder="Pasos a realizar">
                </div>
                <div class="col">
                    <label>Requerimientos(select)*</label>
                    <x-adminlte-select2 name="requirements[]" id="requirements" multiple>
                    </x-adminlte-select2>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="name">Imagenes o videos (opcional)</label>
                        <x-adminlte-input-file-krajee id="kifPholder" name="kifPholder[]" igroup-size="sm"
                            data-msg-placeholder="Choose multiple files..." data-show-cancel="false"
                            data-show-close="false" multiple preset-mode="minimalist" />
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
                <button type="button" class="btn btn-outline-success btn-block" onclick="saveForm()">Guardar</button>
            </div>
        </div>
    </form>
</x-adminlte-card>
@stop

@section('css')

@stop

@section('js')

@stop