@extends('layouts.app')

@section('title', 'Formulário - Contato')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Formulário de cadastro
                </div>
                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-{{ session('class') }}" id="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="form-patient" action="/patients" method="post">
                        @csrf
                        @include('patients.partials.form-patient')

                        <button type="reset" class="btn btn-primary">Limpar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-pencil"></i> Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
