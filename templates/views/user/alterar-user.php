<h1>Usuário</h1>
<?php
    echo getMessage();
?>
<div class="row">
    <div class='col-md-12'>
        <h3>Editar</h3>
        <form  name="form_alt_user" id="form_alt_user" action="<?php echo getBaseURL().'adm/user/alterar' ?>" method="post" accept-charset="utf-8">
            <?php echo $hiddenFormInput; ?>
            <?php echo $hiddenFormFakeId; ?>

            <div class="form-group col-md-6">
                <div>
                    <input type="text" class="form-control" value="<?php echo $user['nome'] ?>" id="nome" name="nome" Placeholder="Nome do usuário..." title="Nome" required>
                </div>
            </div>
            <div class="form-group col-md-6">
                <div>
                    <input type="text" class="form-control" value="<?php echo $user['sobrenome'] ?>" id="sobrenome" name="sobrenome" Placeholder="Sobrenome do usuário..." title="Sobrenome" required>
                </div>
            </div>
            <div class="form-group col-md-6">
                <select class="form-control" name="gender" id="gender">
                    <option value="0">Sexo</option>
                    <?php
                        $gen = $user['gender'];
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
            <div class="form-group col-md-6">
                <div>
                    <input type="text" class="form-control" value="<?php echo $user['dt_nasc'] ?>" id="dt_nasc" name="dt_nasc" Placeholder="Data de Nacimento dd/mm/aaaa" title="Data de Nacimento" required>
                </div>
            </div>
            <div class="form-group col-md-6">
                <div class="autocomplete">
                    <input id="myInput" type="text" class="form-control" value="<?php echo $user['strCidade'] ?>" name="cidade" Placeholder="Cidade" title="Cidade" required>
                </div>
            </div>
            <div class="form-group col-md-6">
                <div>
                    <input type="email" class="form-control" value="<?php echo $user['email'] ?>" id="email" name="email" Placeholder="E-mail do usuário..." title="E-mail" required>
                </div>
            </div>
            <div class="form-group col-md-6">
                <select class="form-control" name="status" id="status">
                    <option value="-1">Status</option>
                    <?php
                        $st = $user['status'];
                        foreach ($arrStatus as $key => $value) {
                            $selected = "";
                            if ( (int)$value ===  (int)$st ) {
                                $selected = "selected";
                            }
                            echo "<option value=\"{$value}\" title=\"{$key}\" $selected >{$key}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <select class="form-control" name="role" id="role">
                    <option value="0">Papel</option>
                    <?php
                        foreach ($arrRoles as $key => $role) {
                            $selected = "";
                            if ( $role['selected'] === true ) {
                                $selected = "selected";
                            }
                            echo "<option value=\"{$role['rol_id']}\" title=\"{$role['rol_obs']}\" $selected >{$role['rol_desc']}</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-12">
                <div>
                    <button type="submit" class="btn btn-primary">Alterar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<hr>
<div class="row">
    <div class="form-group col-md-6">
        <a href="<?php echo getBaseURL().'adm/user/reset/'.$user['id'] ?>" class="btn btn-primary">Resetar Senha do Usuário</a>
    </div>
</div>
