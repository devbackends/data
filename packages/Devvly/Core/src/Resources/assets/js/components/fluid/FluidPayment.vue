<template>
    <div>
        <NewCard @submit="submit"/>
    </div>
</template>

<script>
import {API_ENDPOINTS} from "../../constant";
import NewCard from "./NewCard";

export default {
    name: 'FluidPayment',
    components: {NewCard},
    data() {
        return {
            token: '',
            billingInfo: ''
        }
    },
    async mounted() {
    },
    methods: {
        submit(additional) {
            if(typeof additional.token != 'undefined'){
                    if (additional.token) {
                        this.token=additional.token;
                        this.billingInfo=additional.billingInfo;
                        this.$emit('submit', {
                        token: additional.token,
                        billingInfo: additional.billingInfo
                        });
                    }
            }
        },
    },
    watch: {

    }
}
</script>
