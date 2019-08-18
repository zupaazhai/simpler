<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Asset</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <a href="/user/create" class="btn btn-primary" href="">New asset</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of asset</h3>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 3%">#</th>
                                    <th style="width: 20%">Name</th>
                                    <th>Type</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th style="width: 5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($assets)): ?>
                                <tr>
                                    <td colspan="6">No asset found</td>
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