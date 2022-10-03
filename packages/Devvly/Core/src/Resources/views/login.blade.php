
@extends('core::layouts.master')

@section('page_title')
    Login
@endsection

@section('head')

@endsection

@section('seo')
@stop

@section('content-wrapper')
    @include('core::flash-message')
    <div class="main-content-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-6 box-section-wrapper">
                    <div class="box-section">
                        <div class="box-section__head heading">
                            <h3>Sign into your 2A Data Admin Portal</h3>
                        </div>
                        <div class="custom-form-container">
                            <form   method="POST"
                                    action="{{ route('core.login-action') }}"
                                    @submit.prevent="onSubmit">
                                @csrf
                                <div class="form-group margin_b_20" :class="[errors.has('email') ? 'has-error' : '']">
                                    <label for="email" class="mandatory label-style form-labels">Email</label>
                                    <input type="email" name="email" value="" placeholder="Email" class="form-control" v-validate="'required|email'" data-vv-as="&quot;Email&quot;">
                                    <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                                </div>
                                <div class="form-group"  :class="[errors.has('password') ? 'has-error' : '']">
                                    <label for="password" class="mandatory label-style">Password</label>
                                    <input type="password" name="password" value="" placeholder="Password" class="form-control"  v-validate="'required'" data-vv-as="&quot;Password&quot;">
                                    <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                                </div>
                                <div class="submit-container box-section__action">
                                    <input id="custom-submit-button" type="submit" value="Sign In" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

