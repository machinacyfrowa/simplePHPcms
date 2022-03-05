<?php
require('./smarty/Smarty.class.php');

$s = new Smarty();

$s->setTemplateDir('./smarty/templates');
$s->setCompileDir('./smarty/templates_c');
$s->setCacheDir('./smarty/cache');
$s->setConfigDir('./smarty/configs');

$db = new mysqli("localhost", "root", "", "cms");


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
$s->assign('navMenu', $navMenu);


$pageID = intval($_REQUEST['pageID']);
$q = $db->prepare("SELECT * FROM page WHERE id = ? LIMIT 1");
$q->bind_param("i", $pageID);
$q->execute();
$result = $q->get_result();

//tablica asocjacyjna z pojedyńczą stroną
$page = $result->fetch_assoc();

$s->assign('page', $page);

$s->display('index.tpl');
?>
