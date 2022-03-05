<a href="index.php?pageID=1">Pierwsza strona</a>
<a href="index.php?pageID=2">druga strona</a>
<a href="index.php?pageID=3">Trzecia strona</a>



<?php
$db = new mysqli("localhost", "root", "", "cms");
$pageID = intval($_REQUEST['pageID']);
$q = $db->prepare("SELECT * FROM page WHERE id = ? LIMIT 1");
$q->bind_param("i", $pageID);
$q->execute();
$result = $q->get_result();

//tablica asocjacyjna z pojedyńczą stroną
$page = $result->fetch_assoc();

echo "<h1>" . $page['title'] . "</h1>";
echo "<div>" . $page['content'] . "<div>";

//var_dump($page);
?>
