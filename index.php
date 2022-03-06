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
/*
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
*/
Route::add('/([a-z0-9-]*)', function($slug) {
    global $s;
    //mamy url ze slugiem ...
    $page = getPageFromSlug($slug);
    
    if(count($page) == 0) {
        
        header("HTTP/1.0 404 Not Found");
        exit;
    
    }
       
    $s->assign('navMenu', getNavMenu());
    $s->assign('page', $page);
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
function getPageFromSlug(string $slug) : array {
    global $db;
    $q = $db->prepare("SELECT * FROM page WHERE slug = ? LIMIT 1");
    $q->bind_param("s", $slug);
    $q->execute();
    $result = $q->get_result();
    if($result->num_rows == 0)
    //jeżeli nie ma takiej strony to zwróc pustą tablicę
        return Array();
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
        $row['url'] = getSlug($row['title']);
        array_push($navMenu, $row);
    }
    return $navMenu;
}
function getSlug(string $text) : string {
    $slug = strtolower($text);
    $slug = str_replace(' ','-',$slug);
    $slug = str_replace('ó','o',$slug);
    $slug = str_replace('ł','l',$slug);
    $slug = str_replace('ą','a',$slug);
    return $slug;
}