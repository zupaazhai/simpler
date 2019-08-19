<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Edit asset</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form action="/asset/<?php __($asset['id']) ?>" method="post">
            <?php echo form_method('put') ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Content</div>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" name="content" id="" cols="30" rows="10"><?php __($asset['content']) ?></textarea>
                        </div>
                    </div>
                </div>   
                <div class="col-md-4">
                    <div class="card">
                        <input name="_method" value="put" type="hidden">
                        <div class="card-header">
                            <h3 class="card-title">File: <?php echo $asset['name'] ?></h3>
                        </div> 
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">File name</label>
                                <input class="form-control" name="name" value="<?php __($asset['name']) ?>" type="text">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <select class="form-control" name="type" id="type">
                                            <?php foreach ($types as $key => $value): ?>
                                            <option <?php echo $key == $asset['type'] ? 'selected' : '' ?> value="<?php __($key) ?>"><?php __($value) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="type">Placement position</label>
                                        <select class="form-control" name="position" id="type">
                                            <?php foreach ($positions as $key => $value): ?>
                                            <option <?php echo $key == $asset['position'] ? 'selected' : '' ?> value="<?php __($key) ?>"><?php __($value) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>