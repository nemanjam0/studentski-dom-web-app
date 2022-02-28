@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Name') }}</th>
      <th scope="col">{{ __('Birthday date') }}</th>
      <th scope="col">{{ __('Job') }}</th>
      <th scope="col">{{ __('Temp pass') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($users as $user)
    <tr>
      <th scope="row">{{$i++}}</th>
      <td>{{$user->name.' '.$user->surname}}</td>
      <td>{{$user->birthday_date}}</td>
      <td>{{$user->user_type}}</td>
      <td>{{$user->temp_pass}}</td>
    </tr>
    @endforeach
  </tbody>
  </table>
</div>
@endsection