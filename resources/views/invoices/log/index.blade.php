@extends('layouts.app')
@section('content')
<div class="containter">
    <div class="row justify-content-end" style="margin: auto">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">{{ __('Start invoice execution') }}</div>
                <form method="POST" action="{{ route('invoice_log.new') }}">
                @csrf
                    <div class="form-group row justify-content-left">
                        <label for="date" class="col-md-2 offset-1 col-form-label text-md-left">{{ __('Date') }}</label>

                        <div class="col-md-6">
                            <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date" value={{\Carbon\Carbon::now()->format('Y-m-d')}}>

                            @error('date')
                                <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                </span>
                             @enderror
                        </div>
                    </div>
                        <div class="form-group row mb-1">
                            <div class="col-md-2 offset-md-3">
                                <button type="submit" class="btn btn-danger">
                                    {{ __('Run') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <table class="table table-sm">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('ID') }}</th>
            <th scope="col">{{ __('Started') }}</th>
            <th scope="col">{{ __('Query log') }}</th>
            <th scope="col">{{ __('Status') }}</th>
            <th scope="col">{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
        @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
        @foreach($logs as $log)
            <tr>   
            <th>{{$i++}}</th>
            <td>{{$log->id}}</td>
            <td>{{$log->started}}</td>
            <td>{{$log->query_log}}</td>
            <td>
                @if($log->successful==0)
                <span class="badge badge-danger">Unsuccessful</span>
                    @else
                    <span class="badge badge-success">Successful</span>
                    @endif
            <td>
                @if($log->successful==0)
                <form method="POST" action="{{ route('invoice_log.retry')}}">
                <input name="id" type="hidden" value="{{$log->id}}">
            @csrf
            <button type="submit" class="btn btn-danger">
                                            {{ __('Retry') }}
            </button>
            </form>
                @endif
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
  <div class="d-flex justify-content-center"> {{ $logs->links() }}</div>
</div>
@endsection