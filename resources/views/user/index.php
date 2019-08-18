<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>User</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <a href="/user/create" class="btn btn-primary" href="">New user</a>
            </div>
        </div>
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
                                    <th style="width: 20%">Username</th>
                                    <th>Email</th>
                                    <th>Created At</th>
                                    <th style="width: 5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $index => $user): ?>
                                <tr>
                                    <td><?php echo ++$index ?></td>
                                    <td>
                                        <a href="/user/<?php __($user['id']) ?>"><?php __($user['username']) ?></a>
                                    </td>
                                    <td><?php echo $user['email'] ?></td>
                                    <td><?php __(format_date($user['created_at'])) ?></td>
                                    <td>
                                        <button data-target="#delete-form-<?php __($user['id']) ?>" class="delete-btn btn btn-danger"><i class="fas fa-trash"></i></button>
                                        <form id="delete-form-<?php __($user['id']) ?>" action="/user/<?php __($user['id']) ?>" method="post">
                                            <?php form_method('delete') ?>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="5">No user found</td>
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