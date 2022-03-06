<?php
require('./smarty/Smarty.class.php');
require('./Route.php');

use Steampixel\Route; 

$s = new Smarty();

$s->setTemplateDir('./smarty/templates');
$s->setCompileDir('./smarty/templates_c');
$s->setCacheDir('./smarty/cache');
$s->setConfigDir('./smarty/configs');

$db = new mysqli("localhost", "root", "", "cms");

Route::add('/', function() {
    global $s;
    $s->assign('navMenu', getNavMenu());
    $s->assign('page', getPage(1));
    $s->display('index.tpl');
});

Route::add('/strona/([0-9]*)', function($i) {
    global $s;
    $s->assign('navMenu', getNavMenu());
    $s->assign('page', getPage($i));
    $s->display('index.tpl');
});
//ręcznie
Route::add('/kontakt-recznie', function() {
    global $s;
    $s->assign('navMenu', getNavMenu());
    $s->assign('page', getPage(6));
    $s->display('index.tpl');
});

$pages = getPages();
//echo "<pre>";
//var_dump($pages);


// Run the router
Route::run('/cms');

function getPages() : array {
    $pages = getNavMenu();
    foreach($pages as &$page) {
        $page['title'] = str_replace(' ','-',$page['title']);
        $page['title'] = str_replace('ó','o',$page['title']);
        $page['title'] = str_replace('ł','l',$page['title']);
        $page['title'] = strtolower($page['title']);
    }
    return $pages;
}

function getPage(int $pageId) : array {
    global $db;
    $q = $db->prepare("SELECT * FROM page WHERE id = ? LIMIT 1");
    $q->bind_param("i", $pageId);
    $q->execute();
    $result = $q->get_result();
    
    //tablica asocjacyjna z pojedyńczą stroną
    return $result->fetch_assoc();
}
function getNavMenu() : array {
    global $db;
    $q = $db->prepare("SELECT id, title FROM page");
    $q->execute();
    $result = $q->get_result();
    $navMenu = array();
    foreach($result as $row) {
        //$pageID = $row['id'];
        //$title = $row['title'];
        //echo '<a href="index.php?pageID='.$pageID.'">'.$title.'</a>';
        array_push($navMenu, $row);
    }
    return $navMenu;
}




?>
