<html>

<head>

<title>Calendar Example 2</title>

<script language="javascript" src="js/calendar.js"></script>

<link rel="stylesheet" type="text/css" href="styles/calendar.css" />

</head>

<body>

<form method="POST">

<?

error_reporting(E_ALL);

include "includes/PHP4/calendar.php";

$calendar = new Calendar("example2");
$calendar->inForm = true;
echo $calendar->Output();
echo $calendar->Value();

?>

<input type="submit" />

</form>

</body>

</html>