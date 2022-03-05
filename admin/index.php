<?php
$db = new mysqli("localhost", "root", "", "cms");

if($_REQUEST['action'] == "pageList") {
    $q = $db->prepare("SELECT * FROM page");
    $q->execute();
    $result = $q->get_result();
    echo '<table>';
    foreach($result as $row) {
        echo '<tr>';
        echo '<td>'.$row['id'].'</td>';
        echo '<td>'.$row['title'].'</td>';
        echo '<td><a href="index.php?action=editPage&pageID='.$row['id'].'">
                <button>Edytuj</button></a></td>';
        echo '</tr>';
    }
    echo '<table>';
}

if ($_REQUEST['action'] == "savePage" && $_REQUEST['pageID'] != null) {
    $q = $db->prepare("UPDATE page SET title = ?, content = ? WHERE id = ?");
    $q->bind_param("ssi", $_REQUEST['title'], $_REQUEST['content'], $_REQUEST['pageID']);
    $q->execute();
    echo "Strona zapisana poprawnie!";
}
if ($_REQUEST['action'] == "editPage" && $_REQUEST['pageID'] != null) {

    $pageID = intval($_REQUEST['pageID']);
    $q = $db->prepare("SELECT * FROM page WHERE id = ? LIMIT 1");
    $q->bind_param("i", $pageID);
    $q->execute();
    $result = $q->get_result();
    $page = $result->fetch_assoc();
    $pageID = $page['id'];
    $title = $page['title'];
    $content = $page['content'];
?>

    <form action="index.php" method="post">
        <label for="titeInput">Tytuł strony:</label>
        <input type="text" name="title" id="titleInput" value="<?php echo $title; ?>"> <br>
        <label for="contentTextArea">Treść strony:</label>
        <textarea name="content" id="contentTextArea" cols="30" rows="10">
    <?php echo $content; ?>
    
    </textarea>
        <input type="hidden" name="action" value="savePage">
        <input type="hidden" name="pageID" value="<?php echo $pageID; ?>">
        <input type="submit" value="Zapisz">
    </form>
    <a href="index.php?action=pageList">Powrót</a>
<?php
}

?>