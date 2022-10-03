@extends("ffl::fflonboarding.layouts.master")

@section('content')
<div class="container py-5 px-0">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <p class="ffl-form-finish__confirmation">{{__('ffl::app.finish.thank_you')}}</p>
            <p>{{__('ffl::app.finish.reviewing')}}</p>
        </div>
    </div>
    
</div>
@endsection