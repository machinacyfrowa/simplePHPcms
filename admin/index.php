<?php
require('./../smarty/Smarty.class.php');
require('Route.php');

use Steampixel\Route; 

$s = new Smarty();

$s->setTemplateDir('./../smarty/templates/admin');
$s->setCompileDir('./../smarty/templates_c');
$s->setCacheDir('./../smarty/cache');
$s->setConfigDir('./../smarty/configs');
$db = new mysqli("localhost", "root", "", "cms");
Route::add('/',function(){
    global $db, $s;
    $q = $db->prepare("SELECT * FROM page");
    $q->execute();
    $result = $q->get_result();
    $pages = array();
    foreach($result as $page) {
        array_push($pages, $page);
    }
    $s->assign('pages', $pages);
    $s->display('pageList.tpl');
});
Route::add('/page-list',function(){
    global $db, $s;
    $q = $db->prepare("SELECT * FROM page");
    $q->execute();
    $result = $q->get_result();
    $pages = array();
    foreach($result as $page) {
        array_push($pages, $page);
    }
    $s->assign('pages', $pages);
    $s->display('pageList.tpl');
});
Route::add('/delete-page/([0-9]*)',function($id){
    global $db, $s;
    //usuwamy stronę
    $q = $db->prepare("DELETE FROM page WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $s->assign("message", "Strona usunięta");
    $s->display("message.tpl");
});
Route::add('/new-page',function(){
    global $s;
    $s->display("page.tpl");
});
Route::add('/edit-page/([0-9]*)',function($id){
    global $db, $s;
    $q = $db->prepare("SELECT * FROM page WHERE id = ? LIMIT 1");
    $q->bind_param("i", $id);
    $q->execute();
    $result = $q->get_result();
    $page = $result->fetch_assoc();
    $s->assign("page", $page);
    $s->display("page.tpl");
});
Route::add('/save-page',function(){
    global $db, $s;
    if ($_REQUEST['pageID'] == 0) {
        //nowa strona
        $q = $db->prepare("INSERT INTO page VALUES (NULL, ?, ?, ?)");
        $slug = getSlug($_REQUEST['title']);
        $q->bind_param("sss", $_REQUEST['title'], $_REQUEST['content'], $slug);
        //TODO: check for uqnique slug
        $q->execute();
        $s->assign("message", "Strona utworzona");
        $s->display("message.tpl");
    } else {
        $q = $db->prepare("UPDATE page SET title = ?, content = ?, slug = ? WHERE id = ?");
        $slug = getSlug($_REQUEST['title']);
        $q->bind_param("sssi", $_REQUEST['title'], $_REQUEST['content'], $slug, $_REQUEST['pageID']);
        $q->execute();
        $s->assign("message", "Strona zaktualizowana");
        $s->display("message.tpl");
    }
}, 'post');

/*

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
    default:
        $s->assign("message", "Nieprawidłowy parametr action");
        $s->display("message.tpl"); 
    break;
}
*/
Route::run('/cms/admin');


function getSlug(string $text) : string {
    $slug = strtolower($text);
    $slug = str_replace(' ','-',$slug);
    $slug = str_replace('ó','o',$slug);
    $slug = str_replace('ł','l',$slug);
    $slug = str_replace('ą','a',$slug);
    return $slug;
}
?>