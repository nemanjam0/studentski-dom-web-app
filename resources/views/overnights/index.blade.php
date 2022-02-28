@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Host') }}</th>
      <th scope="col">{{ __('Dorm') }}</th>
      <th scope="col">{{ __('Room') }}</th>
      <th scope="col">{{ __('Person') }}</th>
      <th scope="col">{{ __('ID number') }}</th>
      <th scope="col">{{ __('Check-in') }}</th>
      <th scope="col">{{ __('Check-out') }}</th>
      <th scope="col">{{ __('Allowed by') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($overnights as $overnight)
    <tr>
      <th scope="row">{{$i++}}</th>
      <td>{{$overnight->host_name}} {{$overnight->host_surname}}</td>
      <td>{{$overnight->dorm_name}}</td>
      <td>{{$overnight->room_number}}</td>
      <td>{{$overnight->person_name}}</td>
      <td>{{$overnight->id_number}}</td>
      <td>{{$overnight->check_in}}</td>
      <td>{{$overnight->check_out}}</td>
      <td>{{$overnight->allowed_by_name}} {{$overnight->allowed_by_surname}}</td>
      <td>
      @if(!Auth::user()->isDoorman())
      <form method="POST" action="{{ route('overnights.destroy',['overnight'=> $overnight->id]) }}">
      @method('DELETE')
      @csrf
      <button type="submit" class="btn btn-danger">
                                    {{ __('Delete') }}
      </button>
      </form>
      @endif
      </td>
    </tr>
    @endforeach
  </tbody>
  </table>
</div>
@endsection