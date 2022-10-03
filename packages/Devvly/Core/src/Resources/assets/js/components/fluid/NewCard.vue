<template>
    <div class="credit-card-modal" id="newFluidCardPopup">
                <div class="">
                    <div class="errors" v-if="fluidErrors.length > 0">
                        <p class="text-danger" v-for="error in fluidErrors">{{error}}</p>
                    </div>
                    <div id="fluid-form" class="mb-3"></div>
                </div>
                <div class="">
                    <button id="add-card-button" type="button"class="btn btn-primary" @click="tokenizer.submit()">Subscribe</button>
                </div>
    </div>
</template>

<script>
import {API_ENDPOINTS} from "../../constant";

export default {
    name: "NewCard",
    data() {
        return {
          tokenizer: null,
          fluidErrors: [],
        }
    },
    async mounted() {
        const response = await this.$http.get(API_ENDPOINTS.fluid.getTokenizerinfo);
        if (response.data.status) {
            this.tokenizer = new Tokenizer({
                url: response.data.data.url,
                apikey: response.data.data.public_key,
                container: document.querySelector('#fluid-form'),
                settings: {
                    payment: {
                        showTitle: true,
                        placeholderCreditCard: '0000 0000 0000 0000',
                        showExpDate: true,
                        showCVV: true
                    },
                    user: {
                        showInline: true,
                        showName: true,
                        showEmail: true,
                        showTitle: true
                    },
                    billing: {
                        show: true,
                        showTitle: true
                    }
                },
                submission: (res) => {
                    if (res.status === 'success') {
                        this.$emit('submit', {
                            token: res.token,
                            billingInfo: {
                                ...res.user,
                                ...res.billing,
                            }

                        });
                    } else {
                        if(typeof res.msg != 'undefined'){
                            alert(res.msg);
                        }
                        if(typeof res.invalid != 'undefined'){
                             this.$toast.error('Enter the card info first', {
                              position: 'top-right',
                              duration: 5000,
                              });
                        }
                    }
                },
            })
        }
    },
}
</script>

<style scoped>

</style>