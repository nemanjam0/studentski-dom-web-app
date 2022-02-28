@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Report breakage') }}</div>

                <div class="card-body">
                    <form>
                        @method('PUT')
                        @csrf
                        <div class="form-group row">
                            <label for="reported_at" class="col-md-4 col-form-label text-md-right">{{ __('Reported at') }}</label>

                            <div class="col-md-6">
                                <input id="reported_at" type="text" class="form-control" name="reported_at" value="{{$breakage->created_at->format('d/m/Y H:i:s')}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dorm" class="col-md-4 col-form-label text-md-right">{{ __('Dorm') }}</label>

                            <div class="col-md-6">
                                <input id="dorm" type="text" class="form-control" name="dorm" value="{{$breakage->dorm->name}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dorm" class="col-md-4 col-form-label text-md-right">{{ __('Room') }}</label>

                            <div class="col-md-6">
                                <input id="room" type="text" class="form-control" name="room" value="{{$breakage->room->room_number}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="reported_by" class="col-md-4 col-form-label text-md-right">{{ __('Reported by') }}</label>

                            <div class="col-md-6">
                                <input id="reported_by" type="text" class="form-control" name="reported_by" value="{{$breakage->reported_by->name}} {{  $breakage->reported_by->surname}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" type="textarea" rows="5" cols="50" class="form-control" name="description" readonly>{{$breakage->description}} </textarea>
                            </div>
                        </div>
                        @if($breakage->status!='unanswered' && $breakage->answered_by_id!=null)
                        <div class="form-group row">
                            <label for="answered_by" class="col-md-4 col-form-label text-md-right">{{ __('Answered by') }}</label>

                            <div class="col-md-6">
                                <input id="answered_by" type="text" class="form-control" name="answered_by" value="{{$breakage->answered_by->name}} {{  $breakage->answered_by->surname}}" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="answer" class="col-md-4 col-form-label text-md-right">{{ __('Answer') }}</label>

                                <div class="col-md-6">
                                    <textarea id="answer" type="textardea" rows="5" cols="50" class="form-control @error('answer') is-invalid @enderror" name="answer" readonly>{{$breakage->answer}}</textarea>
                                </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="answered_at" class="col-md-4 col-form-label text-md-right">{{ __('Answered at') }}</label>

                            <div class="col-md-6">
                                <input id="answered_at" type="text" class="form-control" name="answered_at" value="{{$breakage->updated_at->format('d/m/Y H:i:s')}}" readonly>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>

                            <div class="col-md-6">
                                <input id="status" type="text" class="form-control" name="status" value="{{$breakage->status}}" readonly>
                            </div>
                        </div>


            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
