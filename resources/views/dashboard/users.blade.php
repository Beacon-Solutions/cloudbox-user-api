<div class="modal fade" id="modelAddUser" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add User</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label for="inputUsername" class="col-lg-2 control-label">Username</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="inputUsername" placeholder="Username"
                                       name="username" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="col-lg-2 control-label">Password</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" id="inputPassword"
                                       placeholder="Password"
                                       name="password" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputFullName" class="col-lg-2 control-label">Full Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="inputFullName" placeholder="Full Name"
                                       name="full_name" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPosition" class="col-lg-2 control-label">Position</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="inputPosition" placeholder="Position"
                                       name="position" autocomplete="off">
                            </div>
                        </div>
                        <input type="hidden" id="csrfToken" name="_token" value="{{ csrf_token() }}"/>
                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button id="add-user" type="button" class="btn btn-primary" name="_add">Add</button>
                            </div>
                        </div>
                        <div id="userAddError" class="alert alert-dismissible alert-danger text-center" hidden>
                            <strong id="userAddErrorMsg"></strong>
                        </div>
                    </fieldset>
                </form>
            </div>
            {{--<div class="modal-footer">--}}
            {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
            {{--<button type="button" class="btn btn-primary">Save changes</button>--}}
            {{--</div>--}}
        </div>
    </div>
</div>

<div id="ajaxUserManage">

</div>

<div class="row">
    <div class="col-sm-12 placeholder">
        <div class="panel panel-primary">
            <div class="panel-heading">Users</div>
            <div class="panel-body">
                <div class="list-group">

                    @foreach ($all_users as $user)
                        <div class="list-group-item">
                            <h4 class="list-group-item-heading">{{ $user->full_name }}</h4>
                            <p class="list-group-item-text">{{ $user->position }}</p>
                            <a data-key="{{$user->id}}" href="#" class="btn btn-primary app-action-btn btn-manage-user">Manage</a>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 placeholder">
        <div class="panel panel-success">
            <div class="panel-heading">Add User</div>
            <div class="panel-body">
                <div class="bs-component">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelAddUser">
                        Add User
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        $('#add-user').click(function () {
            $.post("{{url('/users/add')}}",
                    {
                        username: $('#inputUsername').val(),
                        password: $('#inputPassword').val(),
                        full_name: $('#inputFullName').val(),
                        position: $('#inputPosition').val(),
                        wordpress: $('#default_wordpress_chk').val(),
                        _token: $('#csrfToken').val()
                    },
                    function (data, status) {
                        if (data['error']) {

                            $('#userAddErrorMsg').text(data['msg']);
                            $('#userAddError').show();

                        } else {
                            $('#modelAddUser').modal('hide').on('hidden.bs.modal', function () {
                                $('#side-menu').find('ul li').eq(3).find('a').click();
                            });
                            ;
                        }
                    })
        });

        $('.btn-manage-user').click(function () {
            var id = $(this).data('key');
            $('#ajaxUserManage').load('{{url('/users/get')}}' + '/' + id, function (response, status, xhr) {

                if (xhr.status == 200) {
                    $('#modelManageUser').modal('show');
                }
            });
        });

    </script>
</div>

