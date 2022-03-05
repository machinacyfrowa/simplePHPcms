{include file="head.tpl"}
<div class="container">
    <header class="row">
        <nav class="navbar navbar-expand-md navbar-light bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        {foreach $navMenu as $item}
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?pageID={$item.id}">{$item.title}</a>
                        </li>
                        {/foreach}
                    </ul>
                </div>
            </div>

        </nav>
    </header>
    <main class="row">
        <h1>{$page.title}</h1>
        <div>{$page.content}</div>
    </main>
</div>
{include file="foot.tpl"}