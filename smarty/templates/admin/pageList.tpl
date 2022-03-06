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
                <a class="btn btn-primary" href="edit-page/{$page.id}">
                Edytuj
                </a>
            </td>
            <td>
                <a class="btn btn-primary" href="delete-page/{$page.id}">
                Usuń
                </a>
            </td>
           </tr> 
        {/foreach}
    <table>
    <a class="btn btn-primary" href="new-page/">Nowa strona</a>
    </main>
{include file="foot.tpl"}