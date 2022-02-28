@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Name') }}</th>
      <th scope="col">{{ __('Address') }}</th>
      <th scope="col">{{ __('Category') }}</th>
      <th scope="col">{{ __('Action') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($dorms as $dorm)
    <tr>
      <th scope="row">{{$i++}}</th>
      <td>{{$dorm->name}}</td>
      <td>{{$dorm->address}}</td>
      <td>{{$dorm->category}}</td>
      <td>
      <form method="POST" action="{{ route('dorms.destroy',['dorm'=> $dorm->id]) }}">
      <a href="{{ route('dorms.edit',['dorm'=>$dorm->id]) }}" class="btn btn-primary">Edit</a>
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