<div class="login-box">

    <div class="login-logo">
        <a href=""><b>Simpler</a>
    </div>

    <div class="card">

        <?php
            $errors = bind('errors');
        ?>
        
        <div class="card-body login-card-body">
            <form action="/login" method="post">
                <div class="input-group mb-3">
                    <input 
                        type="text" 
                        name="username"
                        class="form-control <?php echo !empty($errors['username']) ? 'is-invalid' : '' ?>" 
                        placeholder="Username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?php echo !empty($errors['username']) ? $errors['username'] : '' ?></div>
                </div>

                <div class="input-group mb-3">
                    <input 
                        type="password" 
                        name="password"
                        class="form-control <?php echo !empty($errors['password']) ? 'is-invalid' : '' ?>" 
                        placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?php echo !empty($errors['password']) ? $errors['password'] : '' ?></div>
                </div>

                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>