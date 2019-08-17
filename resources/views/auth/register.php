<div class="register-box">
    <div class="login-logo">
        <a href=""><b>Simpler</a>
    </div>

    <div class="card">

        <?php
            $errors = bind('errors');
            $old = old();
            $errors = empty($errors) ? array() : $errors;
        ?>

        <div class="card-body register-card-body">
            <p class="register-box-msg">Register new user</p>
            <form action="/new-user" method="post">

                <div class="input-group mb-3">
                    <input 
                        type="text" 
                        class="form-control <?php echo !empty($errors['username']) ? 'is-invalid' : '' ?>" 
                        name="username" 
                        value="<?php echo !empty($old['username']) ? $old['username'] : '' ?>"
                        placeholder="Username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?php echo empty($errors['username']) ? '' : $errors['username'] ?></div>
                </div>

                <div class="input-group mb-3">
                    <input 
                        type="password" 
                        class="form-control <?php echo !empty($errors['password']) ? 'is-invalid' : '' ?>" 
                        name="password" 
                        placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-key"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?php echo empty($errors['password']) ? '' : $errors['password'] ?></div>
                </div>

                <div class="input-group mb-3">
                    <input 
                        type="password" 
                        class="form-control <?php echo !empty($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                        name="confirm_password" 
                        placeholder="Confirm Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-key"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?php echo empty($errors['confirm_password']) ? '' : $errors['confirm_password'] ?></div>
                </div>

                <div class="input-group mb-3">
                    <input 
                        type="email" 
                        class="form-control <?php echo !empty($errors['email']) ? 'is-invalid' : '' ?>" 
                        name="email" 
                        value="<?php echo !empty($old['email']) ? $old['email'] : '' ?>"
                        placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?php echo empty($errors['email']) ? '' : $errors['email'] ?></div>
                </div>

                <div class="input-group">
                    <button class="btn btn-primary btn-block">Register</button>
                </div>

            </form>
        </div>
    </div>
</div>