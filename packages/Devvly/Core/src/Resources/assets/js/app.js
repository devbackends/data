import Vue from 'vue';

import 'vue-toast-notification/dist/index.css';
import VueToast from 'vue-toast-notification';


window.axios = require("axios");
window.VeeValidate = require("vee-validate");
window.jQuery = window.$ = require("jquery");
window.eventBus = new Vue();

Vue.component('FluidPayment', require('./components/fluid/FluidPayment'));

Vue.use(VueToast);
Vue.use(VeeValidate);

Vue.prototype.$http = axios;


window.Vue = Vue;


Vue.config.devtools = true;

// for compilation of html coming from server
Vue.component('vnode-injector', {
    functional: true,
    props: ['nodes'],
    render(h, {props}) {
        return props.nodes;
    }
});


$(document).ready(function () {

    Vue.mixin({

        data: function () {
            return {
                'baseUrl': document.querySelector("script[src$='core.js']").getAttribute('baseUrl'),
            }
        },
        mounted(){

        },
        methods: {
            redirect: function (route) {
                route ? window.location.href = route : '';
            },


            onSubmit: function (event) {
                event.target.preventDefault;
                this.$validator.validateAll().then(result => {
                if (result) {
                            event.target.submit();
                } else {
                this.toggleButtonDisability({event, actionType: false});
                eventBus.$emit('onFormError');
                }
                });
            },

            isMobile: function () {
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    return true
                } else {
                    return false
                }
            }
        }
    });

    new Vue({
        el: "#app",
        data: function () {
            return {
                modalIds: {},
                miniCartKey: 0,
                quickView: false,
                productDetails: [],
            }
        },

        created: function () {
            window.addEventListener('click', () => {
                let modals = document.getElementsByClassName('sensitive-modal');

                Array.from(modals).forEach(modal => {
                    modal.classList.add('hide');
                });
            });
        },

        mounted: function () {
            setTimeout(() => {

            }, 0);

            document.body.style.display = "block";
            this.$validator.localize(document.documentElement.lang);

        },
        methods: {
            onSubmit: function (event) {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        event.target.submit();
                    } else {
                        eventBus.$emit('onFormError')
                    }
                });
            },
            addFlashMessages: function () {
                if (window.flashMessages.alertMessage)
                    window.alert(window.flashMessages.alertMessage);
            },
        }
    });

});


