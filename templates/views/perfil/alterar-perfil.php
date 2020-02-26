<h1>Perfil do Usuário</h1>
<div class="row">
    <?php
        echo getMessage();
    ?>
    <div class='col-md-6 col-md-offset-3'>
        <h3>&nbsp</h3>
        <form  name="form_perfil" id="form_perfil" action="<?php echo getBaseURL().'adm/perfil/alterar' ?>" method="post" autocomplete="off" accept-charset="utf-8">
            <?php echo $hiddenFormInput; ?>
            <?php echo $hiddenFormFakeId; ?>
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" value="<?php echo $user['nome'] ?>" id="nome" title="Nome do usuário" readonly style="text-align:center;">
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" value="<?php echo $user['sobrenome'] ?>" id="sobrenome" title="Sobrenome do usuário" readonly style="text-align:center;">
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" value="<?php echo $user['sexo'] ?>" id="sexo" title="Sexo do usuário" readonly style="text-align:center;">
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" value="<?php echo $user['dt_nasc'] ?>" id="dt_nasc" title="Data de nascimento do usuário" readonly style="text-align:center;">
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="email" class="form-control" value="<?php echo $user['email'] ?>" id="email" readonly title="Email do usuário" style="text-align:center;">
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="status" class="form-control" value="<?php echo $user['status'] ?>" id="status" readonly title="Status do usuário" style="text-align:center;">
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="role" class="form-control" value="<?php echo $user['role'] ?>" id="role" title="Papel do usuário" readonly style="text-align:center;">
                </div>
            </div>

            <br/><hr/>

            <div class="form-group">
                <div>
                    <input type="password" class="form-control" id="senha" name="senha" Placeholder="Nova Senha..." title="Nova Senha" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="password" class="form-control" id="senha2" name="senha2" Placeholder="Confirmar Senha..." title="Confirmar Senha" required>
                </div>
                <span id="txt-senha2"></span>
            </div>

            <div class="form-group">
                <div>
                    <button id="btn-submit" type="submit" class="btn btn-primary">Alterar Senha</button>
                    <a href="<?php echo getBaseURL(); ?>adm/home" class="btn btn-primary">Voltar</a>
                </div>
            </div>
        </form>
    </div>
</div>
