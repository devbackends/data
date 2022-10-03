
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

            <div class="">
                <h3>Change Your Password</h3>
            </div>
            <div class="custom-form-container" style="max-width: 400px;">
                @csrf
                <form id="change-password-form">
                    <div class="form-group margin_b_20">
                        <label for="old-password" class="mandatory label-style form-labels">Old Password</label>
                        <input type="password" id="old-password" name="old-password" minlength="6" placeholder="Old Password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="new-password" class="mandatory label-style">New Password</label>
                        <input type="password" id="new-password" name="new-password" minlength="6" placeholder="New Password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password" class="mandatory label-style">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" minlength="6" placeholder="Confirm Password" class="form-control" required>
                    </div>
                    <div class="submit-container box-section__action">
                        <input id="custom-submit-button" type="submit" value="Save" class="btn btn-primary">
                    </div>
                </form>
                <br>
                <div id="alert-message" class="alert">
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
$( document ).ready(function() {
    $(document).on('submit','#change-password-form',function(e){
      e.preventDefault();
      const old_password=$('#old-password').val();
      const new_password=$('#new-password').val();
      const confirm_password=$('#confirm-password').val();
      $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
          $.ajax({
                 url: "/users/change-password",
                 method: "POST",
                 dataType: "json",
                 data:{old_password:old_password,new_password:new_password,confirm_password:confirm_password},
                 success: function (data) {
                            if(data.status==200){
                            $('#change-password-form')[0].reset();
                                $('#alert-message').html('Password changed Succcessfully')
                                $('#alert-message').removeClass('alert-danger');
                                $('#alert-message').addClass('alert-success');
                            }else{
                                $('#alert-message').html(data.message);
                                $('#alert-message').removeClass('alert-success');
                                $('#alert-message').addClass('alert-danger');
                            }
                }
            });
    });
});
</script>
    @endpush
@endsection

