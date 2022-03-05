{include file="head.tpl"}
    <header>
    <nav>
    {foreach $navMenu as $item}
        <a href="index.php?pageID={$item.id}">{$item.title}</a>
    {/foreach}
    </nav>
    </header>
    <main>
        <h1>{$page.title}</h1>
        <div>{$page.content}</div>
    </main>
{include file="foot.tpl"}