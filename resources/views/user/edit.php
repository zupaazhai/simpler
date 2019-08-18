<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Edit user</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <form action="/user/<?php echo $user['id'] ?>" method="post">
                        <input name="_method" value="put" type="hidden">
                        <div class="card-header">
                            <h3 class="card-title">Edit user "<?php echo $user['username'] ?>"</h3>
                        </div> 
                        <div class="card-body">
                            <?php inc('user.form', array('user' => $user)) ?>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary">Save</button>
                            <a href="/user" class="btn btn-defaul">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>