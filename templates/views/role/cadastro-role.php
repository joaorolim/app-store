<h1>Papéis</h1>
<div class="row">
    <?php
        echo getMessage()
    ?>
    <div class='col-md-3'>
        <h3>Cadastrar</h3>
        <form  name="form_alt" id="form_alt" action="<?php echo getBaseURL().'adm/role/cadastrar' ?>" method="post" accept-charset="utf-8">
            <?php echo $hiddenFormInput; ?>
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" id="papel" name="papel" value="<?php echo getOld('role') ?>" Placeholder="Nome do papel..." title="Papel" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" id="observacao" name="observacao" value="<?php echo getOld('observacao') ?>" Placeholder="Observação..." title="Observação" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
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
