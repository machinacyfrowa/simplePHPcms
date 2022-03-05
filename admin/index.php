<?php
require('./../smarty/Smarty.class.php');

$s = new Smarty();

$s->setTemplateDir('./../smarty/templates/admin');
$s->setCompileDir('./../smarty/templates_c');
$s->setCacheDir('./../smarty/cache');
$s->setConfigDir('./../smarty/configs');
$db = new mysqli("localhost", "root", "", "cms");

$action = "pageList";
if(isset($_REQUEST['action'])) {
 $action = $_REQUEST['action'];
}

switch($action) {
    case 'pageList':
        $q = $db->prepare("SELECT * FROM page");
        $q->execute();
        $result = $q->get_result();
        $pages = array();
        foreach($result as $page) {
            array_push($pages, $page);
        }
        $s->assign('pages', $pages);
        $s->display('pageList.tpl');
    break;
    case 'deletePage':
        //usuwamy stronę
        $q = $db->prepare("DELETE FROM page WHERE id = ?");
        $q->bind_param("i", $_REQUEST['pageID']);
        $q->execute();
        $s->assign("message", "Strona usunięta");
        $s->display("message.tpl");
    break;
    case 'newPage':
        $s->display("page.tpl");
    break;
    case 'editPage':
        $pageID = intval($_REQUEST['pageID']);
        $q = $db->prepare("SELECT * FROM page WHERE id = ? LIMIT 1");
        $q->bind_param("i", $pageID);
        $q->execute();
        $result = $q->get_result();
        $page = $result->fetch_assoc();
        $s->assign("page", $page);
        $s->display("page.tpl");
    break;
    case 'savePage':
        if ($_REQUEST['pageID'] == 0) {
            //nowa strona
            $q = $db->prepare("INSERT INTO page VALUES (NULL, ?, ?)");
            $q->bind_param("ss", $_REQUEST['title'], $_REQUEST['content']);
            $q->execute();
            $s->assign("message", "Strona utworzona");
            $s->display("message.tpl");
        } else {
            $q = $db->prepare("UPDATE page SET title = ?, content = ? WHERE id = ?");
            $q->bind_param("ssi", $_REQUEST['title'], $_REQUEST['content'], $_REQUEST['pageID']);
            $q->execute();
            $s->assign("message", "Strona zaktualizowana");
            $s->display("message.tpl");
        }
    break;
}

/*
if (isset($_REQUEST['action'])) {


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

}

*/
?>