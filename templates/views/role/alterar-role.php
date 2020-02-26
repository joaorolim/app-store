<h1>Papel</h1>
<?php
    echo getMessage()
?>
<div class="row">
    <div class='col-md-12'>
        <h3>Editar</h3>
        <form  name="form_alt_ocup" id="form_alt_ocup" action="<?php echo getBaseURL().'adm/role/alterar' ?>" method="post" accept-charset="utf-8">
            <?php echo $hiddenFormInput; ?>
            <?php echo $hiddenFormFakeId; ?>
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" id="papel" name="papel"  value="<?php echo $papel['nome']; ?>" Placeholder="Nome do papel..." title="Nome do papel..." style="width:50%;" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" id="observacao" name="observacao"  value="<?php echo $papel['obs']; ?>" Placeholder="Observação..." title="Observação..." required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <button type="submit" class="btn btn-primary">Alterar</button>
                </div>
            </div>
        </form>
    </div>
</div>
