<?php include __DIR__ . '/../inicio-html.php'; ?>

    <form action="/realiza-login" method="post">
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="text" id="email" name="email" class="form-control"
                   value="" required>
        </div>

        <div class="form-group">
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" class="form-control"
                   value="" required>
        </div>
        <button class="btn btn-primary">Entrar</button>
    </form>

<?php include __DIR__ . '/../fim-html.php'; ?>