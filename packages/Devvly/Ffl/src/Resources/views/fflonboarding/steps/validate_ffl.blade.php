<div class="validate_ffl-wrapper" v-bind:class="{'d-none': currentStep !== 1}">
    <div class="row mt-5">
        <div class="col-sm-12 form-group d-flex flex-wrap">
            <label for="license-number" class="w-100">{{__('ffl::app.steps.license.ffl_number')}}</label>
            <input name="license_number_first" v-bind:class="[errors.has('license_number_first') ? 'has-error' : '']" v-model="form.license_number_parts.first" v-validate="'required|min:1|max:1|licenseRegion'" maxlength="1" placeholder="X" type="text" class="form-control col-sm-1 dash-after text-center" />
            <span class="input-delimiter"> - </span>
            <input name="license_number_second" v-bind:class="[errors.has('license_number_second') ? 'has-error' : '']" v-model="form.license_number_parts.second" v-validate="'required|min:2|max:2'" maxlength="2" placeholder="XX" type="text" class="form-control col-sm-1 dash-after text-center" />
            <span class="input-delimiter"> - </span>
            <input readonly name="license_number_third"  v-model="form.license_number_parts.third"  placeholder="XXX" type="text" class="form-control col-sm-1 dash-after text-center" />
            <span class="input-delimiter"> - </span>
            <input readonly name="license_number_fourth"  v-model="form.license_number_parts.fourth"  placeholder="XX" type="text" class="form-control col-sm-1 dash-after text-center" />
            <span class="input-delimiter"> - </span>
            <input readonly name="license_number_fifth" v-model="form.license_number_parts.fifth"  placeholder="XX" type="text" class="form-control col-sm-1 dash-after text-center" />
            <span class="input-delimiter"> - </span>
            <input name="license_number_sixth" v-bind:class="[errors.has('license_number_sixth') ? 'has-error' : '']" v-model="form.license_number_parts.sixth" v-validate="'required|min:3|max:6'" maxlength="6" placeholder="XXXXX" type="text" class="form-control col-sm-2 dash-after text-center" />
        </div>
    </div>
    <div class="form-actions d-flex">
        <button ref="cancel" data-url="{{route('ffl.onboarding.landing')}}" v-on:click="onCancel" class="btn btn-outline-dark">{{__('ffl::app.buttons.cancel')}}</button>
        <button v-on:click="onNextStep($event, true)" class="btn btn-primary ml-auto">{{__('ffl::app.buttons.continue')}}</button>
    </div>
</div>