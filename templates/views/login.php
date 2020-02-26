<div class="panel panel-login col-md-6 col-md-offset-3">
    <div class="panel-header">
        <h2>Login</h2>
    </div>

    <?php echo getMessage() ?>

    <div class="panel-body">
        <form id="form_login" class="form-signin" action="<?php echo getBaseURL().'adm/login' ?>" method="post">
            <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input class="form-control" id="exampleInputEmail1" type="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Senha</label>
                <input class="form-control" id="exampleInputPassword1" type="password" name="pass" placeholder="Password" required>
            </div>
            <!-- <div class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                    <input class="form-check-input" type="checkbox"> Remember Password</label>
                </div>
            </div> -->
            <input type="submit" class="btn btn-primary btn-block" value="Login">
        </form>
        <div class="text-center">
            <!-- <a class="d-block small mt-3" href="register.html">Register an Account</a> -->
            <!-- <a class="d-block small" href="forgot-password.html">Esqueci minha senha</a> -->
        </div>
    </div>
</div>
