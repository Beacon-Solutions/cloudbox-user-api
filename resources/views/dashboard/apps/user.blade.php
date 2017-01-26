<div class="row">
    <div class="col-sm-12 placeholder">
        <div class="panel panel-success">
            <div class="panel-heading">My Apps</div>
            <div class="panel-body">
                <div class="list-group">

                    @foreach ($available_user_apps as $user_app)
                        <div class="list-group-item">
                            <h4 class="list-group-item-heading">{{ $user_app->name }}</h4>
                            <p class="list-group-item-text">{{ $user_app->description }}</p>
                            @if($user_app-> launched == 1)
                                <a href="http://{{ $user_app->ipv4 }}"
                                   class="btn btn-success app-action-btn" target="_blank">GoTo</a>
                            @elseif($user_app-> launched == 0)
                                <a data-key="{{ $user_app->id }}" href="#"
                                   class="btn btn-success app-action-btn btn-app-launch">Launch</a>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 placeholder">
        <div class="panel panel-danger">
            <div class="panel-heading">Danger Zone</div>
            <div class="panel-body">
                <div class="list-group">
                    <div class="list-group-item">
                        <h4 class="list-group-item-heading">Business Bloggers</h4>
                        <p class="list-group-item-text">Keep update with your next challenge</p>
                        <a href="#" class="btn btn-danger app-action-btn">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('a.btn-app-launch').click(function () {
            $(this).text("Please Wait...");

            $.post("{{url('instance/create')}}",
                    {
                        app_id: $(this).data('key'),
                        _token: "{{ csrf_token() }}"
                    },
                    function (data, status) {
                        //alert("Data: " + data + "\nStatus: " + status);
                        $('#side-menu').find('ul li').eq(1).find('a').click();
                    });
        });
    </script>

</div>

