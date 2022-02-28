@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success">{{ __('Room') }} {{$data['room']->room_number}}</div>

                <div class="card-body">
                    <form>
                        <div class="form-group row">
                            <label for="dorm" class="col-md-4 col-form-label text-md-right">{{ __('Dorm') }}</label>

                            <div class="col-md-6">
                                <input id="dorm" type="text" class="form-control @error('dorm') is-invalid @enderror" name="dorm" value="{{$data['dorm']->name}}" disabled>

                                @error('dorm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        </div>

                        <div class="form-group row">
                            <label for="room_number" class="col-md-4 col-form-label text-md-right">{{ __('Room number') }}</label>


                            <div class="col-md-6">
                                <input id="room_number" type="text" class="form-control @error('room_number') is-invalid @enderror" name="room_number" value="{{ $data['room']->room_number}}" disabled>

                                @error('room_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label for="room_capacity" class="col-md-4 col-form-label text-md-right">{{ __('Room capacity') }}</label>


                            <div class="col-md-6">
                                <input id="room_capacity" type="text" class="form-control @error('room_capacity') is-invalid @enderror" name="room_capacity" value="{{ $data['room']->room_capacity}}" disabled>

                                @error('room_capacity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="floor" class="col-md-4 col-form-label text-md-right">{{ __('Floor') }}</label>


                            <div class="col-md-6">
                                <input id="floor" type="text" class="form-control @error('floor') is-invalid @enderror" name="floor" value="{{ $data['room']->floor }}" disabled>

                                @error('floor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="room_description" class="col-md-4 col-form-label text-md-right">{{ __('Room Description') }}</label>


                            <div class="col-md-6">
                                <input id="room_description" type="text" class="form-control @error('room_description') is-invalid @enderror" name="room_description" value="{{$data['room']->description}}" disabled>

                                @error('room_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="category" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                            <div class="col-md-6">
                                <input id="category" type="text" class="form-control @error('category') is-invalid @enderror" name="category" value="{{$data['room']->category}}" disabled>
                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        </br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success">{{ __('Students in room') }}</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Surname') }}</th>
                            <th scope="col">{{ __('College') }}</th>
                            <th scope="col">{{ __('Year') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
                        @foreach($data['students'] as $student)
                            <tr>
                                <th scope="row">{{$i++}}</th>
                                <td>{{$student->student_name}}</td>
                                <td>{{$student->student_surname}}</td>
                                <td>{{$student->college_name}}</td>
                                <td>{{$student->year}}</td>
                                <td>
                                <a href="{{ route('students.show',['student'=> $student->student_id]) }}" class="btn btn-warning">Show</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
