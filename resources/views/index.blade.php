<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CloudBox</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">CloudBox</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#" id="navbar-user"> {{$session_data['full_name']}}</a></li>
                <li><a href="help.pdf" target="_blank">Help</a></li>
                <li><a id="view-profile" href="{{url('/users/profile')}}">Profile</a></li>
                <li><a href="{{url('logout')}}">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div id="side-menu" class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                @for ($i = 0; $i < count($session_data['menu_items']); $i++)
                    @if ($i == 0)
                        <li class="active"><a
                                    href="{{url($session_data['menu_items'][$i]['url'])}}">{{$session_data['menu_items'][$i]['name']}}</a>
                        </li>
                    @else
                        <li>
                            <a href="{{url($session_data['menu_items'][$i]['url'])}}">{{$session_data['menu_items'][$i]['name']}}</a>
                        </li>
                    @endif
                @endfor
            </ul>
        </div>
        <div id="ajax-container" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>


</body>
</html>