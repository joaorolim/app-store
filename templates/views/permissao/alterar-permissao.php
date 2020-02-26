<h2>Permissões</h2>

<?php
echo getMessage();
$ckb = 0;
foreach ($roles as $role) {
    $ckb++;
    $ckbClass = "ckb-{$ckb}";
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <div><b>Papel:</b> <?php echo $role["roleName"]; ?></div>
        <span style="font-size:10px;"><?php echo $role["roleObs"]; ?></span>
    </div>
    <div class="panel-body">
        <p><b>Permissões:</b><p>
        <div class="checkbox" style="margin-left: 5px">
            <label title="Selecionar Tudo">
                <input type="checkbox" id="<?php echo 'selectAll-'.$ckb ?>" name="selectAll"> Selecionar tudo
            </label>
        </div>
        <form name="form_perm" id="form_perm" action="<?php echo getBaseURL().'adm/permission/alterar' ?>" method="post" accept-charset="utf-8">
            <?php echo $hiddenFormInput; ?>
            <input type="hidden" name="fakeId" value="<?php echo $role["roleFakeId"]; ?>">
            <div class="checkbox">

                <?php
                $screen = 'init';
                foreach ($role["permissions"] as $perm) {
                    if ( $screen === 'init' OR $screen === $perm['permScreen'] ) {
                        $screen = $perm['permScreen'];
                        echo "<label class=\"check-perm\" title=\"{$perm['permObs']}\"><input type=\"checkbox\" class=\"{$ckbClass}\" value=\"{$perm['permFakeId']}\" name=\"permissions[]\" {$perm['checked']}>{$perm['permName']}</label>";
                    } else {
                        echo "<br/>";
                        $screen = $perm['permScreen'];
                        echo "<label class=\"check-perm\" title=\"{$perm['permObs']}\"><input type=\"checkbox\" class=\"{$ckbClass}\" value=\"{$perm['permFakeId']}\" name=\"permissions[]\" {$perm['checked']}>{$perm['permName']}</label>";
                    }

                }
                ?>

            </div>
            <button type="submit" class="btn btn-primary">Alterar</button>
        </form>
    </div>
</div>
<?php  } ?>

<div class="navegacao">
    <?php echo $paginacao; ?>
</div>
