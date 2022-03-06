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
/*
Route::add('/strona/([0-9]*)', function($i) {
    global $s;
    $s->assign('navMenu', getNavMenu());
    $s->assign('page', getPage($i));
    $s->display('index.tpl');
});
*/
//ręcznie
Route::add('/strona-glowna', function() {
    global $s;
    $s->assign('navMenu', getNavMenu());
    $s->assign('page', getPage(1));
    $s->display('index.tpl');
});
Route::add('/o-nas', function() {
    global $s;
    $s->assign('navMenu', getNavMenu());
    $s->assign('page', getPage(2));
    $s->display('index.tpl');
});
Route::add('/galeria', function() {
    global $s;
    $s->assign('navMenu', getNavMenu());
    $s->assign('page', getPage(3));
    $s->display('index.tpl');
});
Route::add('/kontakt', function() {
    global $s;
    $s->assign('navMenu', getNavMenu());
    $s->assign('page', getPage(6));
    $s->display('index.tpl');
});


//$pages = getPages();



//var_dump($pages);
/* nie udało sie
foreach($pages as $page) {
    // to powinno nam dać url np. /o-nas albo /galeria
    $url = '/'.$page['title'];
    $pageId = $page['id'];
    //echo "tworze url: ". $url;
    //Route::add($url, displayPage($page['id']) );
    Route::add($url, function () {
        global $s, $pageId, $url;
        echo "Wyświetlam stronę $url";
        $s->assign('navMenu', getNavMenu());
        $s->assign('page', getPage($pageId));
        $s->display('index.tpl');
    } );
}
*/
/*
$routes = Route::getAll();
foreach($routes as $route) {
  echo $route['expression'].' ('.$route['method'].')';
}
echo "<pre>routes:";
var_dump($routes);
*/
// Run the router
Route::run('/cms');

/*
function displayPage(int $pageId) {
    global $s;
    $s->assign('navMenu', getNavMenu());
    $s->assign('page', getPage($pageId));
    $s->display('index.tpl');
}

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
*/
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
        $row['url'] = str_replace(' ','-',$row['title']);
        $row['url'] = str_replace('ó','o',$row['url']);
        $row['url'] = str_replace('ł','l',$row['url']);
        $row['url'] = strtolower($row['url']);
        array_push($navMenu, $row);
    }
    return $navMenu;
}
