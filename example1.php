<html>

<head>

<title>Calendar Example 1</title>

<link rel="stylesheet" type="text/css" href="styles/calendar.css" />

</head>

<body>

<?

include "includes/PHP4/calendar.php";
$calendar = new Calendar("example1");
echo $calendar->Output();

?>

</body>

</html>