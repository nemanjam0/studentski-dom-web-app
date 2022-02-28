@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Name') }}</th>
      <th scope="col">{{ __('Surname') }}</th>
      <th scope="col">{{ __('Dorm') }}</th>
      <th scope="col">{{ __('Room') }}</th>
      <th scope="col">{{ __('Action') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($dormstudents as $student)
    <tr>
      <th scope="row">{{$i++}}</th>
      <td>{{$student->student_name}}</td>
      <td>{{$student->student_surname}}</td>
      <td>{{$student->dorm_name}}</td>
      <td>{{$student->room_number}}</td>
      <td>
      <form method="POST" action="{{ route('dormstudents.destroy',['dormstudent'=> $student->student_id]) }}">
      <a href="{{ route('dormstudents.edit',['dormstudent'=> $student->student_id]) }}" class="btn btn-primary">Edit</a>
      @method('DELETE')
      @csrf
      <button type="submit" class="btn btn-danger">
                                    {{ __('Move out') }}
      </button>
      </form>
      </td>
    </tr>
    @endforeach
  </tbody>
  </table>
  <div class="d-flex justify-content-center"> {{ $dormstudents->links() }}</div>
  </div>
  @endsection