
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
    <!-- END SIDEBAR SECTION -->

        <div class="account-layout">
            <div class="account-head">
                <h1 class="h3">Api Keys</h1>
            </div>
            <div class="account-items-list">
                <div class="account-table-content">
                    <div class="grid-container">
                        <form id="api-key-form">
                            <div class="row pb-5">
                                <div class="col-12 col-md-4">
                                    <label>Type</label>
                                    <select id="type" name="type" required class="form-control">
                                      @if(in_array('Ffl Pro',json_decode($packages,true)) || in_array('Ffl Advanced',json_decode($packages,true)) || in_array('Ffl Lite',json_decode($packages,true)) || in_array('Ffl Free',json_decode($packages,true)) )  <option value="ffl">Ffl</option> @endif
                                      <option value="guns">Guns</option>
                                      @if(in_array('Rsr',json_decode($packages,true)) || in_array('Zanders',json_decode($packages,true)) || in_array('Davidsons',json_decode($packages,true)))  <option value="distributor">Distributor</option> @endif
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label>Name</label>
                                    <input type="text" id="name" name="name" required class="form-control">
                                </div>
                                <div class="col-12 col-md-2">
                                    <br>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive-md">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th class="grid_head"><span>Name</span></th>
                                    <th class="grid_head"><span>Type</span></th>
                                    <th class="grid_head"><span>Key</span></th>
                                    <th class="grid_head actions">Created At</th>
                                    <th class="grid_head actions">Actions</th>
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
    $(document).on('submit','#api-key-form',function(e){
      e.preventDefault();
      const type=$('#type').val();
      const name=$('#name').val();
         $.ajaxSetup({
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
          $.ajax({
                 url: "/users/register-api-key",
                 method: "POST",
                 dataType: "json",
                 data:{type:type,name:name},
                 success: function (data) {
                            $('#api-key-form')[0].reset();
                            if(data.status==200){
                            fetch_data();
                            }
                }
            });
    });

    $(document).on('click','.delete-api-key',function(e){
            if(confirm('Are you sure you want to delete this api key')){
            const id=$(this).data('id');
              $.ajax({
                 url: "/users/delete-api-key/"+id,
                 method: "GET",
                 success: function (data) {
                        fetch_data();
                     }
            });
            }
    });
    function fetch_data(){
              $.ajax({
                 url: "/users/get-api-keys",
                 method: "GET",
                 dataType: "json",
                 success: function (data) {
                     if(data.status==200){
                     $('#table-body').html('');
                        for(i=0;i<data.api_keys.length;i++){
                          $('#table-body').append('<tr><td>'+data.api_keys[i]["name"]+'</td><td>'+data.api_keys[i]["type"]+'</td><td>'+data.api_keys[i]["api_key"]+'</td><td>'+data.api_keys[i]["created_at"].split('T')[0]+'</td><td class="actions"><div class="action"><span class="delete-api-key" data-id="'+data.api_keys[i]["id"]+'"><i class="far fa-trash-alt"></i></span></div></td></tr>');
                        }
                     }
                }
            });
    }

});

</script>

@endpush
@endsection

