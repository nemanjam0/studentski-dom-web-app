@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Name') }}</th>
      <th scope="col">{{ __('Address') }}</th>
      <th scope="col">{{ __('Action') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($colleges as $college)
    <tr>
      <th scope="row">{{$i++}}</th>
      <td>{{$college->name}}</td>
      <td>{{$college->address}}</td>
      <td>
      <form method="POST" action="{{ route('colleges.destroy',['college'=> $college->id]) }}">
      <a href="{{ route('colleges.edit',['college'=>$college->id]) }}" class="btn btn-primary">Edit</a>
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