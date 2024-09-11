@extends('publicLayout')

@section('main-content')
    <div class="page-height container mt-5 pt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Verifique seu endereço de e-mail</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                Um novo link de verificação foi enviado para seu endereço de e-mail.
                            </div>
                        @endif

                        Antes de continuar, por favor, verifique seu e-mail para o link de verificação.
                        Se você não recebeu o e-mail,
                        <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-success p-2 me-2 mt-3 align-baseline rounded-0 shadow-sm">
                                Clique aqui para solicitar outro.
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

