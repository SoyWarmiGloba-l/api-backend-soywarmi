@extends('adminlte::page')

@section('title', 'Eventos')

@section('content_header')
    <h1>Administrar eventos o Actividades</h1>
@stop
@section('plugins.Select2', true)
@section('plugins.Summernote', true)

@section('content')
    @if(session()->has('success'))
        <x-adminlte-alert theme="success" title="Success">
            {{ session()->get('success') }}
        </x-adminlte-alert>
    @endif
    <x-adminlte-card theme="maroon" title="Añadir Actividad" theme-mode="outline" collapsible="collapsed">
        <form id="formSave" action="{{ route('activities.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="eventType">Tipo de Evento</label>
                            <x-adminlte-select2 name="eventType" id="eventType">
                                <option selected disabled>Seleccione...</option>
                                @foreach($eventTypes as $eventType)
                                    <option value="{{ $eventType->id }}">{{ $eventType->title }}</option>
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
                        <x-adminlte-input name="dataEnd" id="dataEnd" type="date"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="description">Descripción</label>
                        <x-adminlte-text-editor name="description" id="description"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label for="inputAreas">Areas(registrar)</label>
                        <input type="text" class="form-control" id="inputAreas" name="inputAreas" placeholder="Pasos a realizar" autocomplete="off">
                    </div>
                    <div class="col">
                        <label>Areas(select)*</label>
                        <x-adminlte-select2 name="areas[]" id="areas" multiple>
                        </x-adminlte-select2>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label for="inputSteps">Pasos(registrar)</label>
                        <input type="text" class="form-control" id="inputSteps" name="inputSteps" placeholder="Pasos a realizar">
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
                        <input type="text" class="form-control" id="inputReq" name="inputReq" placeholder="Pasos a realizar">
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
                            <x-adminlte-input-file-krajee id="kifPholder" name="kifPholder[]"
                                                          igroup-size="sm" data-msg-placeholder="Choose multiple files..."
                                                          data-show-cancel="false" data-show-close="false" multiple preset-mode="minimalist"/>
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
    <script>
        let inputArea = document.getElementById('inputAreas');
        let selectArea = document.getElementById('areas');
        inputArea.addEventListener('keypress', (e) => {
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
                selectArea.appendChild(option);
                alert('Area agregada');
                e.target.value = '';
            }
        });
        let inputStep = document.getElementById('inputSteps');
        let selectStep = document.getElementById('steps');
        inputStep.addEventListener('keypress', (e) => {
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
                selectStep.appendChild(option);
                alert('Pasos agregados');
                e.target.value = '';
            }
        })
        let inputReq = document.getElementById('inputReq');
        let selectReq = document.getElementById('requirements');
        inputReq.addEventListener('keypress', (e) => {
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
                selectReq.appendChild(option);
                alert('Requerimientos agregados');
                e.target.value = '';
            }
        })
        function saveForm(){
            let form = document.getElementById('formSave');
            if (selectArea.value === '' || selectStep.value === '' || selectReq.value === '') {
                alert('Debe seleccionar al menos un area, un pasos y un requerico');
                return;
            }
            form.submit();
        }
    </script>
@stop
