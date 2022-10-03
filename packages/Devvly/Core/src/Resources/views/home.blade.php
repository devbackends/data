@extends('core::layouts.master')

@section('page_title')
    Home
@endsection

@section('head')

@endsection

@section('seo')
@stop

@section('content-wrapper')
    <div class="account-content">
        <!-- SIDEBAR SECTION -->
        @include('core::layouts.menu')
        <dashboard></dashboard>
    </div>
    @push('scripts')
        <script type="text/x-template" id="dashboard-template">
            <!-- END SIDEBAR SECTION -->
            <div class="account-layout">
                <div class="account-head">
                    <h1 class="h3">Dashboard</h1>
                    <div class="account-action">

                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="dashboard-box dashboard-box--number">
                            <div class="title">Current Plan</div>
                            <div class="sum">
                                @foreach($data['packages'] as $package )
                                    <span class="h4">{{$package}}</span>
                                    <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @if(isset($data['calls_number']))
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="dashboard-box dashboard-box--number">
                                <div class="title">API Requests Number</div>
                                <div class="data">
                                    <span class="sum">{{$data['calls_number']}}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="dashboard-box dashboard-box--number">
                            <div class="title">Distributors</div>
                            <div class="sum">
                                @foreach($data["distributors"] as $distributor )
                                    <span class="h4">{{ucfirst($distributor->name)}}</span>
                                    @if(in_array(ucfirst($distributor->name),$data['packages']))
                                        <span class="h6">
                                            @if($distributor->active==1)
                                                Verified
                                            @else
                                                Not Verified
                                                <a href="#" class="ftp_modal" v-on:click="openFtpModal('{{$distributor->host}}','{{$distributor->name}}')" data-host="{{$distributor->host}}"  data-toggle="modal" data-target="#distributor-verification">Verify</a>
                                            @endif
                                        </span> <br>
                                    @else
                                        <a href="#" class="form-file-remove" data-toggle="modal"
                                           v-on:click="setPackages('{{ucfirst($distributor->name)}}')"
                                           data-target="#subscribe">
                                            Subscribe
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-center pt-4 pb-4">
                        <h2>Choose the FFL Plan that right for you</h2>
                    </div>

                    <div class="col-12 col-md pr-md-0">
                        <div id="basic-seller-plan" class="seller-plan-card @if(in_array('Ffl Free',$data['packages'])) seller-plan-card--selected @endif  ">
                            <h3 class="seller-plan-card__title">Free</h3>
                            <div class="seller-plan-card__link">
                                @if(in_array('Ffl Lite',$data['packages']) || in_array('Ffl Advanced',$data['packages']) || in_array('Ffl Pro',$data['packages']))
                                    <button type="button" class="btn btn-primary btn-block"  data-toggle="modal" data-target="#subscribe-free" v-on:click="setPackages('Ffl Free')">
                                        Downgrade your account to this plan
                                    </button>
                                @elseif(in_array('Ffl Free',$data['packages']))
                                    <button type="button" class="btn btn-primary btn-block">
                                        This is your current plan
                                    </button>
                                @else
                                    <button type="button" class="btn btn-primary btn-block"  data-toggle="modal" data-target="#subscribe-free" v-on:click="setPackages('Ffl Free')">
                                        Subscribe
                                    </button>
                                @endif
                            </div>
                            <ul class="seller-plan-card__features">
                                <li>Free</li>
                                <li>Up to 100 Requests per day</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-12 col-md pr-md-0">
                        <div id="basic-seller-plan" class="seller-plan-card @if(in_array('Ffl Lite',$data['packages'])) seller-plan-card--selected @endif  ">
                            <h3 class="seller-plan-card__title">Lite</h3>
                            <div class="seller-plan-card__link">
                                @if(in_array('Ffl Advanced',$data['packages']) || in_array('Ffl Pro',$data['packages']))
                                <button type="button" class="btn btn-primary btn-block"  data-toggle="modal" data-target="#subscribe" v-on:click="setPackages('Ffl Lite')">
                                    Downgrade your account to this plan
                                </button>
                                @elseif(in_array('Ffl Lite',$data['packages']))
                                    <button type="button" class="btn btn-primary btn-block">
                                        This is your current plan
                                    </button>
                                @elseif(in_array('Ffl Free',$data['packages']))
                                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal"   data-target="#subscribe" v-on:click="setPackages('Ffl Lite')">
                                        Upgrade
                                    </button>
                                @else
                                    <button type="button" class="btn btn-primary btn-block"  data-toggle="modal" data-target="#subscribe" v-on:click="setPackages('Ffl Lite')">
                                        Subscribe
                                    </button>
                                @endif
                            </div>
                            <ul class="seller-plan-card__features">
                                <li>$19/month</li>
                                <li>CPR $0.013</li>
                                <li>Up to 6000 Requests per day</li>
                                <li>Fee for exceeding 6000 requests - $80</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div id="plus-seller-plan" class="seller-plan-card seller-plan-card--recommended @if(in_array('Ffl Advanced',$data['packages'])) seller-plan-card--selected @endif">
                            <img src="{{ asset('images/seller-plan-recommended.svg') }}" alt=""
                                    class="seller-plan-card--recommended-badge">
                            <h2 class="seller-plan-card__title"><span>Advanced</span></h2>
                            <div class="seller-plan-card__link">
                                @if(in_array('Ffl Pro',$data['packages']))
                                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal"  data-target="#subscribe" v-on:click="setPackages('Ffl Advanced')">
                                        Downgrade your account to this plan
                                    </button>
                                @elseif(in_array('Ffl Advanced',$data['packages']))
                                    <button type="button" class="btn btn-primary btn-block">
                                        This is your current plan
                                    </button>
                                @elseif(in_array('Ffl Lite',$data['packages']) || in_array('Ffl Free',$data['packages']))
                                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal"   data-target="#subscribe" v-on:click="setPackages('Ffl Advanced')">
                                        Upgrade
                                    </button>
                                @else
                                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal"  data-target="#subscribe" v-on:click="setPackages('Ffl Advanced')">
                                        Subscribe
                                    </button>
                                @endif
                            </div>
                            <ul class="seller-plan-card__features">
                                <li><p>Accept Cash and <strong>Credit Card</strong> Payments</p>
                                    <div><p class="mb-0 font-weight-bold">Powered by:</p></div>
                                    <div class="mb-3">
                                        <img
                                                src="https://www.2acommerce.com/themes/commerce/public/images/site-logo.svg"
                                                alt="" width="150px"></div>
                                    <div class="mb-2">
                                    </div>
                                </li>
                                <li>$99/month</li>
                                <li>CPR $0.0017</li>
                                <li>Up to 120,000 Requests Per Day</li>
                                <li>Fee for exceeding 120000 requests - $100</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-md pl-md-0">
                        <div id="pro-seller-plan" class="seller-plan-card @if(in_array('Ffl Pro',$data['packages'])) seller-plan-card--selected @endif">
                            <h3 class="seller-plan-card__title">Pro</h3>
                            <div class="seller-plan-card__link">
                                @if(in_array('Ffl Free',$data['packages']) || in_array('Ffl Lite',$data['packages']) || in_array('Ffl Advanced',$data['packages']))
                                    <button type="button" class="btn btn-primary btn-block"  data-toggle="modal" data-target="#subscribe" v-on:click="setPackages('Ffl Pro')">
                                        Upgrade
                                    </button>
                                @elseif(in_array('Ffl Pro',$data['packages']))
                                    <button type="button" class="btn btn-primary btn-block">
                                        This is your current plan
                                    </button>
                                @else
                                    <button type="button" class="btn btn-primary btn-block"  data-toggle="modal" data-target="#subscribe" v-on:click="setPackages('Ffl Pro')">
                                        Subscribe
                                    </button>
                                @endif
                            </div>
                            <ul class="seller-plan-card__features">
                                <li>Pro</li>
                                <li>$199/month</li>
                                <li>CPR $0.0013</li>
                                <li>Up to 600,000 requests per day</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- SUBSCRIPTION MODAL -->
                <div class="modal normal fade" id="subscribe" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="modal-head">
                                    <h3>Subscribe</h3>
                                    <div class="box-section">
                                        <div class="custom-form-container">
                                            <form id="subscribe-form" method="post"
                                                  action="{{ route('core.subscription-action') }}">
                                                <div>
                                                    <FluidPayment @submit="submit" ref="fluidPayment"/>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a href="#" class="btn btn-outline-gray" data-dismiss="modal">cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SUBSCRIPTION MODAL -->

                <!--Free SUBSCRIPTION MODAL -->
                <div class="modal normal fade" id="subscribe-free" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="modal-head">
                                    <h3>Are You sure you want to a free subscription</h3>
                                    <div class="box-section">
                                        <div class="custom-form-container">
                                            <form id="subscribe-form" method="post">
                                                @csrf
                                                <button type="button" v-on:click="submit({})" class="btn btn-primary">Subscribe</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a href="#" class="btn btn-outline-gray" data-dismiss="modal">cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SUBSCRIPTION MODAL -->

                <!-- DISTRIBUTOR VERIFICATION MODAL -->
                <div class="modal normal fade" id="distributor-verification" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="modal-head">
                                    <h3>Login</h3>
                                    <div class="box-section">
                                        <div class="custom-form-container">
                                            <form   method="POST"
                                                    action="{{ route('core.verify-distributor') }}"
                                                    @submit.prevent="onSubmit">
                                                @csrf
                                                <input type="hidden" name="host" id="host"/>
                                                <input type="hidden" name="distributor" id="distributor"/>
                                                <div class="form-group margin_b_20" :class="[errors.has('user') ? 'has-error' : '']">
                                                    <label for="user" class="mandatory label-style form-labels">User</label>
                                                    <input type="text" name="user" value="" placeholder="User" class="form-control" v-validate="'required'" data-vv-as="&quot;User&quot;">
                                                    <span class="control-error" v-if="errors.has('user')">@{{ errors.first('user') }}</span>
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
                                <div class="d-flex justify-content-between">
                                    <a href="#" class="btn btn-outline-gray" data-dismiss="modal">cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END DISTRIBUTOR VERIFICATION MODAL -->
            </div>

        </script>
        <script>
            Vue.component('dashboard', {
                template: "#dashboard-template",
                inject: ['$validator'],
                data() {
                    return {
                        token: '',
                        billingInfo: '',
                        packages: [],
                        showUpgrade: 0,
                        total: 0
                    }
                },
                mounted() {

                },
                computed: {},
                methods: {
                    openFtpModal(host,distributor){
                        $("#host").val(host);
                        $("#distributor").val(distributor);
                    },
                    setTotal(package) {
                        if (package == 'Ffl Free') {
                            this.total = 0;
                        }
                        if (package == 'Ffl Lite') {
                            this.total = 19;
                        }
                        if (package == 'Ffl Advanced') {
                            this.total = 99;
                        }
                        if (package == 'Ffl Pro') {
                            this.total = 199;
                        }
                        if (package == 'Rsr') {
                            this.total = 19;
                        }
                        if (package == 'Zanders') {
                            this.total = 19;
                        }
                        if (package == 'Davidsons') {
                            this.total = 19;
                        }
                    },
                    setPackages(package) {
                        if (!this.packages.includes(package)) {
                            this.packages = [];
                            this.packages.push(package);
                            this.setTotal(package);
                        }
                    },
                    async submit(additional) {
                        if(this.total==0){
                            additional.token='free';
                            additional.billingInfo='free';
                        }
                        if (typeof additional.token != 'undefined') {
                            if (additional.token) {
                                this.token = additional.token;
                                this.billingInfo = additional.billingInfo;
                                    if (this.token) {
                                        let res = await this.$http.post('/users/subscription', {
                                            token: this.token,
                                            total: this.total,
                                            billingInfo: this.billingInfo,
                                            packages: this.packages.toString().replace('"', '')
                                        }).catch(err => {
                                            console.log(err);
                                        });
                                        if (res.status == 200) {
                                            if (res.data.status == 'success') {
                                                this.$toast.success(res.data.message, {
                                                    position: 'top-right',
                                                    duration: 5000,
                                                });
                                                window.location = '/users/home';
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

                            }
                        }
                    }
                }

            });


        </script>
        <script src="{{config('services.2adata.gateway_url') . '/tokenizer/tokenizer.js'}}"></script>
    @endpush

@endsection

