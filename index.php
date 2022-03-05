

<?php
$db = new mysqli("localhost", "root", "", "cms");

$q = $db->prepare("SELECT id, title FROM page");
$q->execute();
$result = $q->get_result();
foreach($result as $row) {
    $pageID = $row['id'];
    $title = $row['title'];
    echo '<a href="index.php?pageID='.$pageID.'">'.$title.'</a>';
}


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
