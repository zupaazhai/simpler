<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Setting</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12 col-md-5">
                <form action="/setting" method="post">
                    <?php form_method('put'); ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Basic</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="site_name">Site name</label>
                                <input class="form-control" value="<?php __($settings['site_name']) ?>" name="site_name" type="text">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary">Save Basic Setting</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
