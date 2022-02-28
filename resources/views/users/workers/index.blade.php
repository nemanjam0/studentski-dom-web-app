@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Name') }}</th>
      <th scope="col">{{ __('Workplace') }}</th>
      <th scope="col">{{ __('Job') }}</th>
      <th scope="col">{{ __('Action') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($dormworkers as $dormworker)
    <tr>
      <th scope="row">{{$i++}}</th>
      <td>{{$dormworker->userName.' '.$dormworker->userSurname}}</td>
      <td>{{$dormworker->dormName}}</td>
      <td>{{$dormworker->job}}</td>
      <td>
      <form method="POST" action="{{ route('workers.destroy',['worker'=> $dormworker->dorm_worker_id]) }}">
      <a href="{{ route('workers.edit',['worker'=>$dormworker->dorm_worker_id]) }}" class="btn btn-primary">Edit</a>
      @method('DELETE')
      @csrf
      <button type="submit" class="btn btn-danger">
                                    {{ __('Delete') }}
      </button>
      </form>
      </td>
    </tr>
    @endforeach
  </tbody>
  </table>
</div>
@endsection