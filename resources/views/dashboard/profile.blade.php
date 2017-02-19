<div class="row">
    <div class="col-sm-12 placeholder">
        <div class="panel panel-primary">
            <div class="panel-heading">My Profile</div>
            <div class="panel-body">
                <div class="list-group">

                    <form class="form-horizontal" id="formManageUser">
                        <fieldset>
                            <div class="form-group">
                                <label for="inputFullName" class="col-lg-2 control-label">Full Name</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="inputFullNameUpdate" placeholder="Full Name"
                                           name="full_name" autocomplete="off" value="{{$userData['full_name']}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPosition" class="col-lg-2 control-label">Position</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="inputPositionUpdate" placeholder="Position"
                                           name="position" autocomplete="off" value="{{$userData['position']}}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputCurrentPassword" class="col-lg-2 control-label">Current Password</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="inputCurrentPassword" placeholder="Current Password"
                                           name="current_password" autocomplete="off" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputNewPassword" class="col-lg-2 control-label">New Password</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="inputNewPassword" placeholder="New Password"
                                           name="new_password" autocomplete="off" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputConfirmNewPassword" class="col-lg-2 control-label">Confirm New Password</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="inputConfirmNewPassword" placeholder="Confirm New Password"
                                           name="confirm_new_password" autocomplete="off" value="">
                                </div>
                            </div>

                            <input type="hidden" id="csrfToken" name="_token" value="{{ csrf_token() }}"/>
                            <input type="hidden" id="userID" name="user_id" value="{{$userData['user_id']}}"/>
                            <div class="form-group">
                                <div class="col-lg-10 col-lg-offset-2">
                                    <button id="update-user" type="button" class="btn btn-primary" name="_update">Update
                                    </button>
                                </div>
                            </div>
                            <div id="userManageError" class="alert alert-dismissible alert-danger text-center" hidden>
                                <strong id="userManageErrorMsg"></strong>
                            </div>
                        </fieldset>
                    </form>

                </div>
            </div>
        </div>
    </div>


</div>


<script>
    $('#update-user').click(function () {
        $.post("{{url('/users/update/profile')}}",
                $('#formManageUser').serialize(),
                function (data, status) {
                    if (data['error']) {
                        $('#userManageError').removeClass('alert-success');
                        $('#userManageError').addClass('alert-danger');
                        $('#userManageErrorMsg').text(data['msg']);
                        $('#userManageError').show();

                    } else {
                        $('#userManageError').removeClass('alert-danger');
                        $('#userManageError').addClass('alert-success');
                        $('#userManageErrorMsg').text(data['msg']);
                        $('#userManageError').show();
                        $('#userManageError').fadeOut(2000,function () {
                            location.reload();
                        });
                    }
                })
    });

</script>


