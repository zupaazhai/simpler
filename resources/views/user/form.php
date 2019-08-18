<div class="form">
    <?php
        $errors = bind('errors');
        $old = old();
        $user = !empty($data['user']) ? $data['user'] : array();

        $username = !empty($old['username']) ? $old['username'] : '';
        $username = !empty($user['username']) ? $user['username'] : $username;

        $email = !empty($old['email']) ? $old['email'] : ''; 
        $email = !empty($user['email']) ? $user['email'] : $email; 
    ?>
    <div class="form-group">
        <label for="username">Username</label>
        <input 
            class="form-control <?php echo !empty($errors['username']) ? 'is-invalid' : '' ?>" 
            value="<?php echo $username ?>"
            type="text" 
            name="username">
        <span class="invalid-feedback"><?php echo !empty($errors['username']) ? $errors['username'] : '' ?></span>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input 
            class="form-control <?php echo !empty($errors['password']) ? 'is-invalid' : '' ?>" 
            type="password" 
            name="password">
        <span class="invalid-feedback"><?php echo !empty($errors['password']) ? $errors['password'] : '' ?></span>        
    </div>
    <div class="form-group">
        <label for="password">Confirm Password</label>
        <input 
            class="form-control <?php echo !empty($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
            type="password" 
            name="confirm_password">
        <span class="invalid-feedback"><?php echo !empty($errors['confirm_password']) ? $errors['confirm_password'] : '' ?></span>                
    </div>
    <div class="form-group">
        <label for="password">Email</label>
        <input 
            class="form-control <?php echo !empty($errors['email']) ? 'is-invalid' : '' ?>" 
            value="<?php echo $email ?>"
            type="email" 
            name="email">
        <span class="invalid-feedback"><?php echo !empty($errors['email']) ? $errors['email'] : '' ?></span>        
    </div>
</div>
