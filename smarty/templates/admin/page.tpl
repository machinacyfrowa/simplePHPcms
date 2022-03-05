{include file="head.tpl"}
<form action="index.php" method="post">
    <label for="titeInput">Tytuł strony:</label>
    <input type="text" name="title" id="titleInput" 
        value="{$page.title|default:""}"> <br>
    <label for="contentTextArea">Treść strony:</label>
    <textarea name="content" id="contentTextArea">
    {$page.content|default:""}
    </textarea>
    <input type="hidden" name="action" value="savePage">
    <input type="hidden" name="pageID" value="{$page.id|default:"0"}">
    <input type="submit" value="Zapisz">
</form>
<a class="btn btn-primary" href="index.php">Powrót</a>

{include file="foot.tpl"}