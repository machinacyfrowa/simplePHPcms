{include file="head.tpl"}
<form action="" method="post">
    <div class="form-group">
        <label for="emailInput">E-mail:</label>
        <input type="email" name="email" id="emailInput">
    </div>
    <div class="form-group">
        <label for="passwordInput">Hasło</label>
        <input type="password" name="password" id="passwordInput">
    </div>
    <button type="submit" class="btn btn-primary">Zaloguj</button>
</form>
{$message|default:""}
{include file="foot.tpl"}