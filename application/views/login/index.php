<form method="post" action="<?= \ItForFree\SimpleMVC\Url::link('login/login')?>" style="width: 50%;">

    <?php
    if (!empty($_GET['auth'])) {
        echo "Неверное имя пользователя или пароль";
    }
    ?>
    <ul>
        <li>
            <label for="userName">Имя</label>
            <input type="text" name="userName" id="userName" placeholder="Введите имя пользователя" required autofocus maxlength="20" />
        </li>
        <li>
            <label for="password">Пароль</label>
            <input type="password" name="password" id="userName" name="userName" placeholder="Введите пароль" required maxlength="20" />
        </li>
    </ul>
    <div class="buttons">
        <input type="submit" name="login" value="Войти" />
    </div>
</form>
