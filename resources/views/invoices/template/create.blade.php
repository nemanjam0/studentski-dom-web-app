@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create new item') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('template.store') }}">
                        @csrf

                        <div class="form-group row">

                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="recurring" class="col-md-4 col-form-label text-md-right">{{ __('Reccuring') }}</label>

                            <div class="col-md-6">
                                <select id="recurring" class="form-control @error('recurring') is-invalid @enderror" name="recurring" value="{{ old('recurring') }}" onchange="reccuringDisplayFunction()">
                                <option value="no">No</option>
                                <option value="template"  @if(old('recurring')=='template') selected @endif>Template</option>
                                <option value="daily" @if(old('recurring')=='daily') selected @endif>Daily</option>
                                <option value="weekly" @if(old('recurring')=='weekly') selected @endif>Weekly</option>
                                <option value="monthly" @if(old('recurring')=='monthly') selected @endif>Monthly</option>
                                <option value="yearly" @if(old('recurring')=='yearly') selected @endif>Yearly</option>
                                </select>
                                @error('reccuring')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row" id="day_of_the_week"style=@if(old('recurring')!='weekly')"display:none"@endif>
                            <label for="day_of_the_week" class="col-md-4 col-form-label text-md-right">{{ __('Day of the week') }}</label>

                            <div class="col-md-6">
                                <input id="day_of_the_week" type="text" class="form-control @error('day_of_the_week') is-invalid @enderror" name="day_of_the_week" value="{{ old('day_of_the_week') }}">

                                @error('day_of_the_week')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row" id="day" style=@if(old('recurring')!='monthly' && old('recurring')!='yearly')"display:none"@endif>
                            <label for="day" class="col-md-4 col-form-label text-md-right">{{ __('Day in month') }}</label>

                            <div class="col-md-6">
                                <input id="day" type="text" class="form-control @error('day') is-invalid @enderror" name="day" value="{{ old('day') }}">

                                @error('day')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row" id="month" style=@if(old('recurring')!='yearly')"display:none"@endif>
                            <label for="month" class="col-md-4 col-form-label text-md-right">{{ __('Month in year') }}</label>

                            <div class="col-md-6">
                                <input id="month" type="text" class="form-control @error('month') is-invalid @enderror" name="month" value="{{ old('month') }}">

                                @error('month')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="amount_of_money" class="col-md-4 col-form-label text-md-right">{{ __('Amount of money') }}</label>

                            <div class="col-md-6">
                                <input id="amount_of_money" type="text" class="form-control @error('amount_of_money') is-invalid @enderror" name="amount_of_money" value="{{ old('amount_of_money') }}">

                                @error('amount_of_money')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="days_to_pay" class="col-md-4 col-form-label text-md-right">{{ __('Days to pay') }}</label>

                            <div class="col-md-6">
                                <input id="days_to_pay" type="text" class="form-control @error('days_to_pay') is-invalid @enderror" name="days_to_pay" value="{{ old('days_to_pay') }}">

                                @error('days_to_pay')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
function reccuringDisplayFunction() 
{

  var x = document.getElementById("recurring").value;
    if(x==='daily' || x==='template' || x==="no")
    {
        document.getElementById("day").style.display = "none";
        document.getElementById("month").style.display = "none";
        document.getElementById("day_of_the_week").style.display = "none;";
    }
    else if(x==='weekly')
    {
        document.getElementById("day").style.display = "none";
        document.getElementById("month").style.display = "none";
        document.getElementById("day_of_the_week").style.display = "";
    }
    else if(x==='monthly')
    {
        document.getElementById("day").style.display = "";
        document.getElementById("month").style.display = "none";
        document.getElementById("day_of_the_week").style.display = "none";
    }
    else if(x==='yearly')
    {
        document.getElementById("day").style.display = "";
        document.getElementById("month").style.display = "";
        document.getElementById("day_of_the_week").style.display = "none";
    }
}
</script>

