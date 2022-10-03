
@extends('core::layouts.master')

@section('page_title')
    Products Price Change
@endsection

@section('head')

@endsection

@section('seo')
@stop

@section('content-wrapper')
    <div class="account-content">
        <!-- SIDEBAR SECTION -->
    @include('core::layouts.menu')
    <!-- END SIDEBAR SECTION -->

        <div class="account-layout">
            <div class="account-head">
                <h3 class="h3 pb-4">These notifications are price changes on products coming from your distributor feeds. Please make sure your payment settings on the feeds plugin are configured correctly</h3>
                <h4 class="h4">Products Price Change</h4>
            </div>
            <div class="account-items-list">
                <div class="account-table-content">
                    <div class="grid-container">
                        <div class="table-responsive-md">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th class="grid_head"><span>Title</span></th>
                                    <th class="grid_head"><span>Upc</span></th>
                                    <th class="grid_head"><span>Old Map</span></th>
                                    <th class="grid_head"><span>New Map</span></th>
                                    <th class="grid_head"><span>Old Msrp</span></th>
                                    <th class="grid_head"><span>New Msrp</span></th>
                                    <th class="grid_head actions">Updated At</th>
                                </tr>
                                </thead>
                                <tbody id="table-body">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
$( document ).ready(function() {
    fetch_data();
    function fetch_data(){
              $.ajax({
                 url: "/products/get-products-price-change",
                 method: "GET",
                 dataType: "json",
                 success: function (data) {

                     if(data.status==200){
                     $('#table-body').html('');
                        for(i=0;i<data.products.length;i++){
                          $('#table-body').append('<tr><td>'+data.products[i]["product"]['title']+'</td><td>'+data.products[i]["upc"]+'</td><td>'+data.products[i]["old_map"]+'</td><td>'+data.products[i]["map"]+'</td><td>'+data.products[i]["old_msrp"]+'</td><td>'+data.products[i]["msrp"]+'</td><td>'+data.products[i]["updated_at"].split('T')[0]+'</td><td class="actions"></td></tr>');
                        }
                     }
                }
            });
    }

});

</script>

    @endpush
@endsection

