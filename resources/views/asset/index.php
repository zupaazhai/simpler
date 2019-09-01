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
                <a href="/user/create" class="btn btn-primary" data-toggle="modal" data-target="#create-asset-modal" href="">New asset</a>
            </div>
        </div>

        <div id="asset-list" class="row">

            <?php foreach ($positions as $key => $title): ?>
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php __($title) ?></h3>
                    </div>
                    <div class="card-body">
                        <div ref="asset<?php echo ucfirst($key) ?>List" class="asset-list-wrapper">
                            <?php
                                $assetByPosition = $assets[$key];
                            ?>
                            <?php foreach ($assetByPosition as $asset) : ?>
                            <div id="<?php __($asset['id']) ?>" class="asset-item row mb-2  align-items-center">
                                <div class="col-10 col-xl-5">
                                    <span class="mr-3 text-gray"><i class="fas fa-arrows-alt"></i></span>
                                    <?php
                                        $id = 'asset/'  . $asset['id'];
                                    ?>
                                    <span>
                                        <?php if ($asset['source'] == $sources['file']): ?>
                                        <a href="<?php __($id) ?>"><?php __($asset['name']) ?></a>
                                        <?php else: ?>
                                        <a title="<?php __($asset['url']) ?>" href="<?php __($id) ?>"><?php __(url_truncate($asset['url'])) ?></a>
                                        <?php endif ?>
                                    </span>
                                </div>
                                <div class="col-2 d-none d-xl-flex font-bold"><?php __($asset['type']) ?></div>
                                <div class="col-3 d-none d-xl-block">
                                    <small><?php __(format_date($asset['updated_at'])) ?></small>
                                </div>
                                <div class="col-2 col-xl-2 text-right">
                                    <button @click="onClickDelete('<?php echo $asset['id'] ?>')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    <form ref="deleteForm<?php echo $asset['id'] ?>" action="/asset/<?php echo $asset['id'] ?>" method="post">
                                        <?php form_method('delete') ?>
                                    </form>
                                </div>
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>
    </div>
</section>

<div>
    <?php echo partial('asset.create-modal', array(
            'sources' => $sources,
            'positions' => $positions
        )) 
    ?>
</div>