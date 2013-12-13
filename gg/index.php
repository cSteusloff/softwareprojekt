<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Style -->
    <link rel='stylesheet' type='text/css' href='css/menu_style.css' />
    <link rel='stylesheet' type='text/css' href='css/main_style.css' />

    <?php
    error_reporting (E_ALL | E_STRICT);
    ini_set ('display_errors', 'On');
    require_once 'init_autoloader.php';

    ?>

</head>
<body>
    <div id="body_wrapper">
        <div id="wrapper">
            <div id="header">
                <div id="logo"></div>
                <div id="title"><h2>Softwareprojekt</h2></div>
                <div id="login">LOGIN</div>
                <div class="cleaner"></div>
            </div>
            <div id='cssmenu'>
                <ul>
                    <li class='active'><a href='index.html'><span>Home</span></a></li>
                    <li><a href='#'><span>Products</span></a></li>
                    <li><a href='#'><span>About</span></a></li>
                    <li class='last'><a href='#'><span>Contact</span></a></li>
                </ul>
            </div>
            <div id="main">
                <div id="sidebar" class="float_l">
                    <div class="sidebar_box">
                        <ul class="sidebar_list">
                            <li class="first"><a href="index.php">Votierungsübersicht</a></li>
                            <li><a href="index.php?section=createtask">Aufgabe erstellen</a></li>
                            <li><a href="index.php?section=exampletask">Aufgabe lösen</a></li>
                            <li><a href="index.php">Testumgebung</a></li>
                            <li class="last"><a href="index.php">Übungsblätter</a></li>
                        </ul>
                    </div>
                </div>
                <div id="content" class="float_r">
                    <?php require_once("lib/allocator.inc.php");?>
                </div>
                <div class="cleaner"></div>
            </div>
            <div id="footer">
                <p>Copyright © 2013 Christian Steusloff & Jens Wiemann</p>
            </div>
        </div>
    </div>

    <!-- JavaScript -->

    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
</body>
</html>