import Vue from 'vue'
import VeeValidate from 'vee-validate';
import {Validator} from 'vee-validate';

import './bootstrap';
import * as rule from './rules/license'
import geocoding from "./components/geocoding";
import {split} from "lodash";

Validator.extend('licenseRegion', rule.licenseRegion);
Validator.extend('licenseType', rule.licenseType);
Validator.extend('licenseExpire', rule.licenseExpire);

window.Vue = Vue;
window.VeeValidate = VeeValidate;

Vue.use(VeeValidate, {
    events: 'blur',
});
Vue.prototype.$http = axios

window.eventBus = new Vue();

$(document).ready(function () {
    new Vue({
        el: "#ffl-root",
        data() {
            return {
                ffl_exist: 0,
                addressFields: {
                    city: null,
                    street: null,
                    zipCode: null,
                    country: "US",
                },
                api_url: null,
                currentStep: 1,
                http_error: null,
                license_image: null,
                form: {
                    //First screen
                    company_name: null,
                    website: null,
                    website_link: null,
                    street_address: null,
                    city: null,
                    mailing_state: null,
                    zip_code: null,
                    phone: null,
                    email: null,
                    //Second screen
                    license_number_parts: {
                        first: null,
                        second: null,
                        third: null,
                        fourth: null,
                        fifth: null,
                        sixth: null
                    },
                    license_name: null,
                    license_number: null,
                    license_image: {
                        file: null,
                        name: null,
                        exist: false
                    },
                    //Third screen
                    position: {
                        lng: null,
                        lat: null,
                    }
                },
                mapStepToFields: [
                    {
                        step: 1, fields: [
                        ], custom: [
                            'license_number_first',
                            'license_number_second',
                            'license_number_sixth',
                        ]
                    },
                    {
                        step: 2, fields: [
                            "company_name",
                            "website",
                            "street_address",
                            "city",
                            "mailing_state",
                            "zip_code",
                            "phone",
                            "email",
                        ]
                    },
                    {
                        step: 3, fields: [
                            'license_image',
                        ], custom: [
                            'license_number_first',
                            'license_number_second',
                            'license_number_third',
                            'license_number_fourth',
                            'license_number_fifth',
                            'license_number_sixth',
                        ]
                    }
                ],
            };
        },
        methods: {
            onNextStep: async function (event, isCustom = false) {
                event.preventDefault();
                let isValid;
                if (isCustom) {
                    isValid = await this.validateScreenWithCustoms();
                } else {
                    isValid = await this.validateScreenInputs();
                }
                if (this.currentStep < 3 && isValid) {
                    if (this.currentStep ==1 && isValid) {
                        this.ffl_exist= await this.checkFflExist();
                    }
                    this.currentStep += 1
                }
            },
            checkFflExist: async function(){
                var url = "/check-ffl-exist/"+this.form.license_number_parts.first+'-'+this.form.license_number_parts.second+'-'+this.form.license_number_parts.sixth;
                var error = null;
                var response = await axios.get(url).catch(e => error = e);
                if (error) {
                    return 0;
                }
                if (response.status == 200) {
                    if(response.data.status==200){
                        this.fillFflData(response.data.ffl);
                        return 1;
                    }
                }
                return 0;
            },
            fillFflData: function(ffl){
                if(ffl){
                    console.log(ffl);
                    this.form.company_name=ffl.business_name;
                    this.form.city=ffl.city;
                    this.form.email=ffl.email;
                    this.form.position.lat=ffl.latitude;
                    this.form.position.longitude=ffl.longitude;
                    const license_number_array=split(ffl.license_number,'-');
                    this.form.license_number_parts.first=license_number_array[0];
                    this.form.license_number_parts.second=license_number_array[1];
                    this.form.license_number_parts.third=license_number_array[2];
                    this.form.license_number_parts.fourth=license_number_array[3];
                    this.form.license_number_parts.fifth=license_number_array[4];
                    this.form.license_number_parts.sixth=license_number_array[5];
                    this.form.license_name=ffl.license_name;
                    if(ffl.phone && ffl.phone!= 'null'){
                        this.form.phone=ffl.phone;
                    }
                    this.form.zip_code=ffl.zip_code;
                    this.form.street_address=ffl.street_address;
                    this.form.mailing_state=ffl.state_id;
                    this.addressFields.city=ffl.city;
                    this.addressFields.street=ffl.street_address;
                    this.addressFields.zipCode=ffl.zip_code;
                    if(ffl.website){
                        this.form.website='true';
                        this.form.website_link=ffl.website;
                    }
                    if(ffl.license_file){
                        this.form.license_image.exist=true;
                    }
                }
            },
            onPrevStep: function (event) {
                event.preventDefault();
                if (this.currentStep > 1) {
                    this.currentStep -= 1
                }
            },
            validateScreenWithCustoms: async function () {
                const [stepToFields] = this.mapStepToFields.filter(stepToFields => this.currentStep === stepToFields.step)
                console.log(stepToFields);
                const promisesCustom = Promise.all(stepToFields.custom.map(field => this.$validator.validate(field)));
                const promisesDefault = Promise.all(stepToFields.fields.map(field => {
                    if(field === 'license_image' && this.form.license_image.file){
                        return true;
                    }
                    return this.$validator.validate(field);
                }));
                return (await promisesDefault).every(isValid => isValid) && (await promisesCustom).every(isValid => isValid);
            },
            validateScreenInputs: async function () {
                const [stepToFields] = this.mapStepToFields.filter(stepToFields => this.currentStep === stepToFields.step)
                const promises = Promise.all(stepToFields.fields.map(field => this.$validator.validate(field)));
                console.log(promises);
                return (await promises).every(isValid => isValid);
            },
            onFileChange: async function (event) {
                const input = event.target.files || event.dataTransfer.files;
                if (!input.length) {
                    return;
                }
                const file = input[0];
                const toBase64 = file => new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => resolve(reader.result);
                    reader.onerror = error => reject(error);
                });
                const valid = await this.$validator.validate('license_image');
                if (valid) {
                    this.form.license_image.file = (await toBase64(file)).replace(/.*base64,/, "");
                    this.form.license_image.name = file.name;
                }
                else {
                    this.form.license_image.file = null;
                    this.form.license_image.name = null;
                }
            },
            onSubmit: async function (event) {
                event.preventDefault();
                this.composeLicenseNumber(event);
                const isValid = await this.validateScreenInputs();
                if (!isValid) {
                    return;
                }
                const data = this.castStringToBool();
                this.$http.post(this.api_url, data).then(() => {
                    localStorage.clear();
                    window.location.href = this.$refs.submit.getAttribute('data-url');
                }).catch(err => {
                    if(typeof err.response.data.message != 'undefined' ){
                        this.http_error = err.response.data.message;
                    }
                })
            },
            composeLicenseNumber: function (event) {
                event.preventDefault();
                this.form.license_number = Object.values(this.form.license_number_parts).join('-');
            },
            onCancel: function (event) {
                event.preventDefault();
                window.location.href = this.$refs.cancel.getAttribute('data-url');
            },
            castStringToBool: function () {
                const data = {};
                for (let field in this.form) {
                    if (this.form[field] === 'true') {
                        data[field] = true;
                    } else if (this.form[field] === 'false') {
                        data[field] = false;
                    } else {
                        data[field] = this.form[field];
                    }
                }
                return data;
            },
        },
        mounted() {
            this.api_url = this.$refs.form.getAttribute('data-url');
            if (localStorage.getItem('form')) {
                this.form = JSON.parse(localStorage.getItem('form'));
            }
        },
        watch: {
            form: {
                handler() {
                    localStorage.setItem('form', JSON.stringify(this.form));
                    if (this.form.street_address) {
                        this.addressFields.street = this.form.street_address;
                    }
                    if (this.form.city) {
                        this.addressFields.city = this.form.city;
                    }
                    if (this.form.zip_code) {
                        this.addressFields.zipCode = this.form.zip_code;
                    }
                },
                deep: true
            },
            addressFields: {
                handler(addressFields) {
                    for (let field in addressFields) {
                        if (!addressFields[field] || addressFields[field] === '') return
                    }
                    let addr = addressFields.street + ", " + addressFields.city + ", " + addressFields.zipCode + ", " + addressFields.country;
                    geocoding(addr)
                        .then(res => {
                            this.form.position = {
                                lat: res.data.results[0].geometry.location.lat,
                                lng: res.data.results[0].geometry.location.lng,
                            };
                        })
                        .catch(err => {
                            this.form.position = {
                                lat: 0,
                                lng: 0,
                            };
                        })
                },
                deep: true,
            }
        }
    });
    //Tooltip init
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
});
