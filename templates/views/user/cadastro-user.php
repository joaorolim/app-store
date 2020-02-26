<h1>Usuários</h1>
<div class="row">
    <?php
        echo getMessage();
    ?>
    <div class='col-md-3'>
        <h3>Cadastrar</h3>
        <form  name="form_cad" id="form_cad" action="<?php echo getBaseURL().'adm/user/cadastrar' ?>" method="post" autocomplete="off" accept-charset="utf-8">
            <?php echo $hiddenFormInput; ?>
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" value="<?php echo getOld('nome') ?>" id="nome" name="nome" Placeholder="Nome do usuário..." title="Nome" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" value="<?php echo getOld('sobrenome') ?>" id="sobrenome" name="sobrenome" Placeholder="Sobrenome do usuário..." title="Sobrenome" required>
                </div>
            </div>
            <div class="form-group">
                <select class="form-control" name="gender" id="gender">
                    <option value="0">Sexo</option>
                    <?php
                        $gen = getOld('gender');
                        foreach ($arrGender as $key => $value) {
                            $selected = "";
                            if ( $value ==  $gen ) {
                                $selected = "selected";
                            }
                            echo "<option value=\"{$value}\" title=\"{$key}\" $selected >{$key}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" value="<?php echo getOld('dt_nasc') ?>" id="dt_nasc" name="dt_nasc" Placeholder="Data de Nacimento dd/mm/aaaa" title="Data de Nacimento" required>
                </div>
            </div>
            <div class="form-group">
                <div class="autocomplete">
                    <input id="myInput" type="text" class="form-control" value="<?php echo getOld('cidade') ?>" name="cidade" Placeholder="Cidade" title="Cidade" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="email" class="form-control" value="<?php echo getOld('email') ?>" id="email" name="email" Placeholder="E-mail do usuário..." title="E-mail" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="password" class="form-control" id="senha" name="senha" Placeholder="Senha do usuário..." title="Senha" required>
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
                    <button id="btn-submit" type="submit" class="btn btn-primary">Cadastrar</button>
                </div>
            </div>
        </form>
    </div>

    <div class='col-md-9'>
        <h3>Lista</h3>
        <div class="table-responsive">
            <?php echo $table; ?>
        </div>
        <div class="navegacao">
            <?php echo $paginacao; ?>
        </div>
    </div>
</div>
