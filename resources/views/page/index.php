<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Page</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2">
                <div class="card page-card">
                    <div class="card-header pb-4">
                        <div class="card-tools">
                            <i class="fas fa-trash"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <span>Home</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="new-page-card d-flex justify-content-center align-items-center">
                    <a class="text-navy" href="" data-toggle="modal" data-target="#create-page-modal">
                        <span>
                            <i class="fas fa-plus mr-2"></i> New Page
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<div>
    <?php echo partial('page.create-modal') ?>
</div>