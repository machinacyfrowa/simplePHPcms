{include file="head.tpl"}
    <header>

    </header>
    <main>
    <table class="table table-bordered">
        {foreach from=$pages item=$page}
           <tr>
            <td>{$page.id}</td>
            <td>{$page.title}</td>
            <td>
                <a class="btn btn-primary" href="index.php?action=editPage&pageID={$page.id}">
                Edytuj
                </a>
            </td>
            <td>
                <a class="btn btn-primary" href="index.php?action=deletePage&pageID={$page.id}">
                Usuń
                </a>
            </td>
           </tr> 
        {/foreach}
    <table>
    <a class="btn btn-primary" href="index.php?action=newPage">Nowa strona</a>
    </main>
{include file="foot.tpl"}