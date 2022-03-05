<?php
require('./../smarty/Smarty.class.php');

$s = new Smarty();

$s->setTemplateDir('./../smarty/templates');
$s->setCompileDir('./../smarty/templates_c');
$s->setCacheDir('./../smarty/cache');
$s->setConfigDir('./../smarty/configs');
$db = new mysqli("localhost", "root", "", "cms");

if (!isset($_REQUEST['action']) || $_REQUEST['action'] == "pageList") {
    $q = $db->prepare("SELECT * FROM page");
    $q->execute();
    $result = $q->get_result();
    echo '<table>';
    foreach ($result as $row) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['title'] . '</td>';
        echo '<td><a href="index.php?action=editPage&pageID=' . $row['id'] . '">
                <button>Edytuj</button></a></td>';
        echo '<td><a href="index.php?action=deletePage&pageID=' . $row['id'] . '">
                <button>Usuń</button></a></td>';
        echo '</tr>';
    }
    echo '<table>';
    echo '<a href="index.php?action=newPage">Nowa strona</a>';
}
if (isset($_REQUEST['action'])) {

    if ($_REQUEST['action'] == "deletePage" && $_REQUEST['pageID'] != null) {
        //usuwamy stronę
        $q = $db->prepare("DELETE FROM page WHERE id = ?");
        $q->bind_param("i", $_REQUEST['pageID']);
        $q->execute();
        echo "Strona usunięta";
    }

    if ($_REQUEST['action'] == "newPage") {
?>
        <form action="index.php" method="post">
            <label for="titeInput">Tytuł strony:</label>
            <input type="text" name="title" id="titleInput" value=""> <br>
            <label for="contentTextArea">Treść strony:</label>
            <textarea name="content" id="contentTextArea" cols="30" rows="10">
        </textarea>
            <input type="hidden" name="action" value="savePage">
            <input type="hidden" name="pageID" value="0">
            <input type="submit" value="Zapisz">
        </form>
        <a href="index.php?action=pageList">Powrót</a>
    <?php
    }

    if ($_REQUEST['action'] == "savePage" && $_REQUEST['pageID'] != null) {
        if ($_REQUEST['pageID'] == 0) {
            //nowa strona
            $q = $db->prepare("INSERT INTO page VALUES (NULL, ?, ?)");
            $q->bind_param("ss", $_REQUEST['title'], $_REQUEST['content']);
            $q->execute();
            echo "Strona utworzona poprawnie!";
        } else {
            $q = $db->prepare("UPDATE page SET title = ?, content = ? WHERE id = ?");
            $q->bind_param("ssi", $_REQUEST['title'], $_REQUEST['content'], $_REQUEST['pageID']);
            $q->execute();
            echo "Strona zapisana poprawnie!";
        }
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
}


?>