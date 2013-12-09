<?php
function html_kopf($title)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<title><?php print($title); ?></title>
</head>
<body>
<?php
}

function html_fuss()
{
?>
</body>
</html>
<?php
}

function zeige_navigation($current)
{
    $nav_ary = array(
        array("�bersicht", "index.php", 0),
        array("Tipps und Tricks", "tipps.php", 0),
        array("Tipp 1", "tipp1.php", 1),
        array("zu Tipp1", "zusatz1.php", 1),
        array("Tipp 2", "tipp2.php", 1),
        array("Tipp 3", "tipp3.php", 1),
        array("Kontakt", "kontakt.php", 0)
        );

    foreach ($nav_ary as $key => $value)
    {
        echo insert_link($value[0], $value[1], $current, $value[2]);
    }
}

function insert_link($key, $value, $current, $ebene=0)
{
  $menuclass = "menu";
  if ($ebene > 0)
  {
      $menuclass = "submenu";
  }

  $result = '<div class="'.$menuclass;
  if ($ebene > 0)
  {
    $result .= ', indent'.$ebene.'">';
  } else {
    $result .= '">';
  }

  if ($key == $current)
	{
	  $result .= $key;
	} else {
	  $result .= '<a href="' . $value.
        '" class="'.$menuclass.'">' .
        $key .'</a>';
	}
    $result .= '</div>';

	return $result;
}

function zeige_navigation2()
{
    $nav_ary = array(
        array("�bersicht", "index.php", 0),
        array("Tipps und Tricks", "tipps.php", 0),
        array("Tipp 1", "tipp1.php", 1),
        array("zu Tipp1", "zusatz1.php", 2),
        array("Tipp 2", "tipp2.php", 1),
        array("Tipp 3", "tipp3.php", 1),
        array("Kontakt", "kontakt.php", 0)
        );

    foreach ($nav_ary as $key => $value)
    {
        echo insert_link2($value[0], $value[1], $value[2]);
    }
}


function insert_link2($key, $value, $ebene=0)
{
  $menuclass = "menu";
  if ($ebene > 0)
  {
      $menuclass = "submenu";
  }

  $result = '<div class="'.$menuclass;
  if ($ebene > 0)
  {
    $result .= ', indent'.$ebene.'">';
  } else {
    $result .= '">';
  }

  if (basename($_SERVER['PHP_SELF']) == $value)
	{
	  $result .= $key;
	} else {
	  $result .= '<a href="' . $value. '" class="'.$menuclass.'">' . $key .'</a>';
	}
    $result .= '</div>';

	return $result;
}

