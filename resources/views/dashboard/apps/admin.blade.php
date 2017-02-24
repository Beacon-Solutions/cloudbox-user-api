<div class="row">
    <div class="col-sm-12 placeholder">
        <div class="panel panel-primary">
            <div class="panel-heading">Admin Apps</div>
            <div class="panel-body">
                <div class="list-group">
                    @foreach ($available_admin_apps as $admin_app)
                        <div class="list-group-item">
                            <h4 class="list-group-item-heading">{{ $admin_app->name }}</h4>
                            <p class="list-group-item-text">{{ $admin_app->description }}</p>
                            @if($admin_app-> launched == 1)
                                <a href="http://{{ $admin_app->ipv4 }}"
                                   class="btn btn-success app-action-btn" target="_blank">GoTo</a>
                            @elseif($admin_app-> launched == 0)
                                <a data-key="{{ $admin_app->id }}" href="#"
                                   class="btn btn-success app-action-btn">Launch</a>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    {{--<div class="col-sm-12 placeholder">--}}
        {{--<div class="panel panel-info">--}}
            {{--<div class="panel-heading">Install Apps</div>--}}
            {{--<div class="panel-body">--}}
                {{--<div class="list-group">--}}
                    {{--<div class="list-group-item">--}}
                        {{--<h4 class="list-group-item-heading">Business Bloggers</h4>--}}
                        {{--<p class="list-group-item-text">Keep update with your next challenge</p>--}}
                        {{--<a href="#" class="btn btn-info app-action-btn">Install</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    <script>
        $('#2').click(function () {
            $(this).text("Please Wait...");
            $.post("api/create_admin_instance.php",
                    {
                        username: 1
                    },
                    function (data, status) {
                        //alert("Data: " + data + "\nStatus: " + status);
                        $('#side-menu').find('ul li').eq(2).find('a').click();
                    });
        });
    </script>
</div>

