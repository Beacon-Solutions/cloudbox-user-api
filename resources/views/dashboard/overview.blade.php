<div class="row">
    <div class="col-sm-12 placeholder">
        <div class="panel panel-success" id="overview-pannel">
            <div class="panel-heading">Overview</div>
            <div class="panel-body">
                <div class="list-group">
                    @foreach ($all_running_apps as $running_app)
                        <a href="http://{{ $running_app->ipv4 }}" class="list-group-item" target="_blank">
                            <h4 class="list-group-item-heading">{{ $running_app->name }}</h4>
                            <p class="list-group-item-text">{{ $running_app->description }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 placeholder">
        <div class="panel panel-info">
            <div class="panel-heading">CloudBox News</div>
            <div id="messageGlobal" class="panel-body">
                Failed to connect with CloudBox server.
            </div>
        </div>
    </div>
    <div class="col-sm-6 placeholder">
        <div class="panel panel-warning">
            <div class="panel-heading">Messages</div>
            <div id="messageProvider" class="panel-body">
                No new messages.
            </div>
        </div>
    </div>
</div>


<script>

    $("#messageGlobal").load("{{url('/dashboard/global/news')}}");
    $("#messageProvider").load("{{url('/dashboard/message')}}");

</script>
