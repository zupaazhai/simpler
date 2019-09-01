<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Dashboard</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php foreach ($topBlocks as $block): ?>
            <div class="col-lg-3">
                <?php partial('dashboard.top-block', $block) ?>
            </div>
            <?php endforeach ?>
        </div>
    </div>
</section>