
@extends('core::layouts.master')

@section('page_title')
    Register
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
                    <sign-up-form></sign-up-form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="text/x-template" id="sign-up-form-template">
            <div class="box-section">
                <div class="box-section__head heading">
                    <h3>Register</h3>
                </div>
                <div class="custom-form-container">
                    <form  id="register-form"  method="post" action="{{ route('core.register-action') }}" @submit.prevent="onSubmit">
                        @csrf
                        <div class="form-group margin_b_20" :class="[errors.has('first_name') ? 'has-error' : '']">
                            <label for="first_name" class="mandatory label-style form-labels">First Name</label>
                            <input type="text" name="first_name" value="" placeholder="First Name" class="form-control" v-validate="'required'"  data-vv-as="&quot;First Name&quot;">
                            <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                        </div>
                        <div class="form-group margin_b_20" :class="[errors.has('last_name') ? 'has-error' : '']">
                            <label for="last_name" class="mandatory label-style form-labels">Last Name</label>
                            <input type="text" name="last_name" value="" placeholder="Last Name" class="form-control" v-validate="'required'"  data-vv-as="&quot;Last Name&quot;">
                            <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                        </div>
                        <div class="form-group margin_b_20" :class="[errors.has('company_name') ? 'has-error' : '']">
                            <label for="company_name" class="mandatory label-style form-labels">Company Name</label>
                            <input type="text" name="company_name" value="" placeholder="Company Name" class="form-control" v-validate="'required'"  data-vv-as="&quot;Company Name&quot;">
                            <span class="control-error" v-if="errors.has('company_name')">@{{ errors.first('company_name') }}</span>
                        </div>
                        <div class="form-group margin_b_20" :class="[errors.has('phone') ? 'has-error' : '']">
                            <label for="phone" class="mandatory label-style form-labels">Phone</label>
                            <input type="text" name="phone" value="" placeholder="Phone" class="form-control" v-validate="'required'"  data-vv-as="&quot;Phone&quot;">
                            <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                        </div>
                        <div class="form-group margin_b_20" :class="[errors.has('email') ? 'has-error' : '']">
                            <label for="email" class="mandatory label-style form-labels">Email</label>
                            <input type="email" name="email" value="" placeholder="Email" class="form-control" v-validate="'required'"  data-vv-as="&quot;Email&quot;">
                            <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                        </div>
                        <div class="form-group"  :class="[errors.has('password') ? 'has-error' : '']">
                            <label for="password" class="mandatory label-style">Password</label>
                            <input type="password" name="password" value="" placeholder="Password" class="form-control" ref="password"  v-validate="'required'"  data-vv-as="&quot;Password&quot;">
                            <span class="control-error" v-if="errors.has('password')">@{{ errors.first('password') }}</span>
                        </div>
                        <div class="form-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                            <label for="password_confirmation" class="mandatory label-style">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" value="" placeholder="Confirm Password" class="form-control"    v-validate="'required|min:6|confirmed:password'"  data-vv-as="&quot;Password Confirmation&quot;">
                            <span class="control-error" v-if="errors.has('password_confirmation')">@{{ errors.first('password_confirmation') }}</span>
                        </div>
                        <div class="submit-container box-section__action">
                            <input id="custom-submit-button" type="submit" value="Sign Up" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </script>
        <script>
            Vue.component('sign-up-form', {
                template: "#sign-up-form-template",
                inject: ['$validator'],
                data: () => ({
                    termsAndCondsChecked: false,
                    submitLoading: false
                }),

                methods: {
                    acceptModalBtnClicked() {
                        this.termsAndCondsChecked = true;
                    }
                },
            });
        </script>
    @endpush
@endsection

