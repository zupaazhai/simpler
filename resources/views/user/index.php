<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-11">
                <h1>User</h1>
            </div>
            <div class="col-1">
                <a href="/user/create" class="btn btn-block btn-primary" href="">New user</a>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of user</h3>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 3%">#</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th style="width: 5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $index => $user): ?>
                                <tr>
                                    <td><?php echo ++$index ?></td>
                                    <td>
                                        <a href="/user/<?php echo $user['id'] ?>"><?php echo $user['username'] ?></a>
                                    </td>
                                    <td><?php echo $user['email'] ?></td>
                                    <td>
                                        <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="4">No user found</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>