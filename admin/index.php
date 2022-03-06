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

Route::add('/login', function() {
    global $s;
    $s->display('login.tpl');
});

Route::add('/login', function() {
    global $db, $s;
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $q = $db->prepare("SELECT id, passwordHash FROM user WHERE email = ?");
    $q->bind_param("s", $email);
    $q->execute();
    $result = $q->get_result();
    
    if($result->num_rows > 0) {
        //znaleziono użytkownika
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['passwordHash'])) {
            //zalogowano poprawnie
            session_start();
            $_SESSION['userId'] = $user['id'];
            $s->assign("message", "Zalogowano poprawnie");
            $s->display("message.tpl");
        } else {
            //błędny login lub hasło
            $s->assign("message", "Błedny email lub hasło!");
            $s->display("login.tpl");
        }
    } else {
        //nie ma użytkownika o tkaim adresie
        $s->assign("message", "Błedny email lub hasło!");
        $s->display("login.tpl");
    }
    
}, 'post');

Route::add('/register', function() {
    global $s;
    $s->display('register.tpl');
});

Route::add('/register', function() {
    global $db, $s;
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $passwordRepeat = $_REQUEST['passwordRepeat'];
    if($password != $passwordRepeat) {
        //hasła niezgodne
        $s->assign("message", "Hasła nie są takie same");
        $s->display("register.tpl");
    } else {
        //hasła były zgodne
        $q = $db->prepare("INSERT INTO user VALUES (NULL, ?, ?)");
        $passwordHash = password_hash($password, PASSWORD_ARGON2I);
        $q->bind_param("ss", $email, $passwordHash);
        $q->execute();
        $s->assign("message", "Konto utworzone, zaloguj się");
        $s->display("register.tpl");
    }

}, 'post');

Route::add('/',function(){
    checkAuth();
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
    checkAuth();
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
    checkAuth();
    global $db, $s;
    //usuwamy stronę
    $q = $db->prepare("DELETE FROM page WHERE id = ?");
    $q->bind_param("i", $id);
    $q->execute();
    $s->assign("message", "Strona usunięta");
    $s->display("message.tpl");
});
Route::add('/new-page',function(){
    checkAuth();
    global $s;
    $s->display("page.tpl");
});
Route::add('/edit-page/([0-9]*)',function($id){
    checkAuth();
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
    checkAuth();
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

Route::add('/logout', function() {
    session_start();
    session_destroy();
    echo "Wylogowano poprawnie";
});

Route::run('/cms/admin');


function getSlug(string $text) : string {
    $slug = strtolower($text);
    $slug = str_replace(' ','-',$slug);
    $slug = str_replace('ó','o',$slug);
    $slug = str_replace('ł','l',$slug);
    $slug = str_replace('ą','a',$slug);
    return $slug;
}
function checkAuth() {
    session_start();
    if(!isset($_SESSION['userId'])) {
        die("Nie masz dostęu do tej strony");
    }
}
?>