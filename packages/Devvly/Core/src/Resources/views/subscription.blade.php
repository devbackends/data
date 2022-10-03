@extends('core::layouts.master')

@section('page_title')
    Subscription
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
                <div class="col-12 box-section-wrapper">
                    <subscription></subscription>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="text/x-template" id="subscription-template">
            <div class="box-section" style="max-width: unset;">
                <div class="custom-form-container">
                    <form id="subscribe-form" method="post" action="{{ route('core.subscription-action') }}">
                        @csrf
                    <div class="row">
                            <div class="col-md-6">
                                <h3>Choose the Plan that's right for you</h3>

                                <div class="form-group p-4 package-block" style="display: inline-block;">
                                    <input type="checkbox" v-on:click="stopOthers('free')" v-model="fflFree" style="float: left;" >
                                    <ul style="float: left;">
                                        <li>Free</li>
                                        <li>Up to 100 Requests per day</li>
                                    </ul>
                                </div>
                                <div class="form-group p-4 package-block @if($data['ffl_lite_expired']) package-expired @endif" style="display: inline-block;">
                                    @if($data['ffl_lite_expired'])
                                        <span class="form-group-badge">Expired</span>
                                    @else
                                        @if($data['ffl_lite_expire_on'])
                                            @php
                                                $now = time(); // or your date as well
                                                $your_date = strtotime($data['ffl_lite_expire_on']);
                                                $datediff= $your_date - $now;
                                            @endphp
                                            <span class="form-group-badge">Expire in {{ round($datediff / (60 * 60 * 24))  }} days</span>
                                        @endif
                                    @endif
                                    <input type="checkbox" v-on:click="stopOthers('lite')" v-model="fflLite" style="float: left;" >
                                    <ul style="float: left;">
                                        <li>Lite</li>
                                        <li>$19/month</li>
                                        <li>CPR $0.013</li>
                                        <li>Up to 6000 Requests per day</li>
                                        <li>Fee for exceeding 6000 requests - $80</li>
                                    </ul>
                                </div>
                                <div class="form-group p-4 package-block @if($data['ffl_advanced_expired']) package-expired @endif"  style="display: inline-block;">
                                    @if($data['ffl_advanced_expired'])
                                        <span class="form-group-badge">Expired</span>
                                    @else
                                        @if($data['ffl_advanced_expire_on'])
                                            @php
                                                $now = time(); // or your date as well
                                                $your_date = strtotime($data['ffl_advanced_expire_on']);
                                                $datediff= $your_date - $now;
                                            @endphp
                                            <span class="form-group-badge">Expire in {{ round($datediff / (60 * 60 * 24))  }} days</span>
                                        @endif
                                    @endif
                                    <input type="checkbox" v-on:click="stopOthers('advanced')" v-model="fflAdvanced" style="float: left;" >
                                    <ul style="float: left;">
                                        <li>Advanced</li>
                                        <li>$99/month</li>
                                        <li>CPR  $0.0017</li>
                                        <li>Up to 120,000 Requests Per Day</li>
                                        <li>Fee for exceeding 120000 requests - $100</li>
                                    </ul>
                                </div>
                                <div class="form-group  p-4 package-block @if($data['ffl_pro_expired']) package-expired @endif"  style="display: inline-block;">
                                    @if($data['ffl_pro_expired'])
                                        <span class="form-group-badge">Expired</span>
                                    @else
                                        @if($data['ffl_pro_expire_on'])
                                            @php
                                                $now = time(); // or your date as well
                                                $your_date = strtotime($data['ffl_pro_expire_on']);
                                                $datediff= $your_date - $now;
                                            @endphp
                                            <span class="form-group-badge">Expire in {{ round($datediff / (60 * 60 * 24))  }} days</span>
                                        @endif
                                    @endif
                                    <input type="checkbox" v-on:click="stopOthers('pro')" v-model="fflPro" style="float: left;" >
                                    <ul style="float: left;">
                                        <li>Pro</li>
                                        <li>$199/month</li>
                                        <li>CPR $0.0013</li>
                                        <li>Up to 600,000 requests Per Day</li>
                                    </ul>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <h3>Distributors API Plans</h3>
                                <div class="form-group  p-4 package-block @if($data['distributor_rsr_expired']) package-expired @endif">
                                    @if($data['distributor_rsr_expired'])
                                        <span class="form-group-badge">Expired</span>
                                    @else
                                        @if($data['distributor_rsr_expire_on'])
                                            @php
                                                $now = time(); // or your date as well
                                                $your_date = strtotime($data['distributor_rsr_expire_on']);
                                                $datediff= $your_date - $now;
                                            @endphp
                                            <span class="form-group-badge">Expire in {{ round($datediff / (60 * 60 * 24))  }} days</span>
                                        @endif
                                    @endif
                                    <input type="checkbox" v-model="rsr"> <label>RSR Distributor - $25/mo</label>
                                </div>
                                <div class="form-group  p-4 package-block @if($data['distributor_zanders_expired']) package-expired @endif">
                                    @if($data['distributor_zanders_expired'])
                                        <span class="form-group-badge">Expired</span>
                                    @else
                                        @if($data['distributor_zanders_expire_on'])
                                            @php
                                                $now = time(); // or your date as well
                                                $your_date = strtotime($data['distributor_zanders_expire_on']);
                                                $datediff= $your_date - $now;
                                            @endphp
                                            <span class="form-group-badge">Expire in {{ round($datediff / (60 * 60 * 24))  }} days</span>
                                        @endif
                                    @endif
                                    <input type="checkbox" value="" name="" id="" v-model="zanders"> <label>Zanders Distributor
                                        - $25/mo</label>
                                </div>
                                <div class="form-group  p-4 package-block @if($data['distributor_davidsons_expired']) package-expired @endif">
                                    @if($data['distributor_davidsons_expired'])
                                        <span class="form-group-badge">Expired</span>
                                    @else
                                        @if($data['distributor_davidsons_expire_on'])
                                            @php
                                                $now = time(); // or your date as well
                                                $your_date = strtotime($data['distributor_davidsons_expire_on']);
                                                $datediff= $your_date - $now;
                                            @endphp
                                            <span class="form-group-badge">Expire in {{ round($datediff / (60 * 60 * 24))  }} days</span>
                                        @endif
                                    @endif
                                    <input type="checkbox" value="" name="" id="" disabled v-model="davidsons"> <label>Davidsons
                                        <span style="font-weight: bold; ">Coming Soon</span></label>
                                </div>
<!--                                <div class="form-group   p-4 package-block">
                                    <input type="checkbox" v-on:click="checkAllDistributors" value="" name="" id=""
                                           v-model="allDistributors"> <label>All Three - $60/mo</label>
                                </div>-->
                            </div>
                    </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h2>Total</h2>
                                <div>
                                    <p v-if="click_request_amounts > 0">Click amounts: <span v-html="click_request_amounts+' USD'"></span></p>
                                    <p >Total: <span v-html="getTotal+' USD'"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12  text-center" v-if="getTotal > 0">
                                <FluidPayment @submit="submit" ref="fluidPayment" />
                            </div>
                            <div class="col-md-12  text-center" v-if="getTotal==0 && fflFree">
                                <button  type="button" class="btn btn-primary" v-on:click="submit({token:'hesfguy',billingInfo:''})" >Subscribe</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </script>
        <script>
            Vue.component('subscription', {
                template: "#subscription-template",
                inject: ['$validator'],
                data() {
                    return {
                        fflPro: false,
                        fflAdvanced: false,
                        fflLite: false,
                        fflFree: false,
                        rsr: false,
                        zanders: false,
                        davidsons: false,
                        allDistributors: false,
                        token: '',
                        billingInfo: '',
                        click_request_amounts:{{ $data['click_request_amounts']}}
                    }
                },
                mounted() {

                },
                computed: {
                    getTotal() {
                        let total = this.click_request_amounts;
                        if (this.fflPro) {
                            total += 199;
                        }
                        if (this.fflAdvanced) {
                            total += 99;
                        }
                        if (this.fflLite) {
                            total += 19;
                        }
                        if (this.allDistributors || (this.rsr && this.zanders && this.davidsons)) {
                            total += 60;
                        } else {
                            this.allDistributors = false;
                            if (this.rsr) {
                                total += 25;
                            }
                            if (this.zanders) {
                                total += 25;
                            }
                            if (this.davidsons) {
                                total += 25;
                            }
                        }
                        return total;
                    },
                    getPackages() {
                        let packages = [];

                        if (this.fflPro) {
                            packages.push("Ffl Pro");
                        }
                        if (this.fflAdvanced) {
                            packages.push("Ffl Advanced");
                        }
                        if (this.fflLite) {
                            packages.push("Ffl Lite");
                        }

                        if (this.fflFree) {
                            packages.push("Ffl Free");
                        }
                        if (this.allDistributors || (this.rsr && this.zanders && this.davidsons)) {
                            packages.push("Rsr");
                            packages.push("Zanders");
                            packages.push("Davidsons");
                        } else {
                            let rsrIndex = packages.indexOf("rsr");
                            if (rsrIndex > -1) {
                                packages.splice(rsrIndex, 1);
                            }
                            let zandersIndex = packages.indexOf("zanders");
                            if (zandersIndex > -1) {
                                packages.splice(zandersIndex, 1);
                            }
                            let davidsonsIndex = packages.indexOf("Davidsons");
                            if (davidsonsIndex > -1) {
                                packages.splice(davidsonsIndex, 1);
                            }
                            if (this.rsr) {
                                packages.push("Rsr");
                            }
                            if (this.zanders) {
                                packages.push("Zanders");
                            }
                            if (this.davidsons) {
                                packages.push("Davidsons");
                            }
                        }
                        return packages;
                    }

                },
                methods: {
                    async submit(additional) {
                        alert('test');
                        if (typeof additional.token != 'undefined') {
                            if (additional.token) {
                                this.token = additional.token;
                                this.billingInfo = additional.billingInfo;

                                /*if (this.getTotal > 0) {*/
                                if(this.getPackages.length > 0){
                                    if (this.token) {
                                        let res = await this.$http.post('/users/subscription', {
                                            token: this.token,
                                            total: this.getTotal,
                                            billingInfo: this.billingInfo,
                                            packages: this.getPackages.toString().replace('"','')
                                        }).catch(err => {
                                            console.log(err);
                                        });
                                        if (res.status == 200) {
                                            if (res.data.status == 'success') {
                                                this.$toast.success(res.data.message, {
                                                    position: 'top-right',
                                                    duration: 5000,
                                                });
                                                window.location = res.data.redirect;
                                            } else {
                                                this.$toast.error(res.data.message, {
                                                    position: 'top-right',
                                                    duration: 5000,
                                                });

                                            }
                                        }
                                    } else {
                                        this.$toast.error('You need to add a card first', {
                                            position: 'top-right',
                                            duration: 5000,
                                        });
                                    }
                                } else {
                                    this.$toast.error('you need to choose a package first', {
                                        position: 'top-right',
                                        duration: 5000,
                                    });
                                }

                            }
                        }
                    },
                    stopOthers: function (value) {
                        if (value == 'free') {
                            this.fflLite = false;
                            this.fflPro = false;
                            this.fflAdvanced = false;
                        }
                        if (value == 'lite') {
                            this.fflFree = false;
                            this.fflPro = false;
                            this.fflAdvanced = false;
                        }
                        if (value == 'advanced') {
                            this.fflFree = false;
                            this.fflLite = false;
                            this.fflPro = false;
                        }
                        if (value == 'pro') {
                            this.fflFree = false;
                            this.fflLite = false;
                            this.fflAdvanced = false;
                        }
                    },
                    checkAllDistributors: function () {
                        if (this.allDistributors) {
                            this.rsr = false;
                            this.zanders = false;
                            this.davidsons = false;
                        } else {
                            this.rsr = true;
                            this.zanders = true;
                            this.davidsons = true;
                        }
                    }
                },
            });


        </script>
        <script src="{{config('services.2adata.gateway_url') . '/tokenizer/tokenizer.js'}}"></script>
    @endpush

@endsection

