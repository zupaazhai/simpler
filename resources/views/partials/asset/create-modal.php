<div class="modal fade" id="create-asset-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div :class="{'modal-content': true, 'has-loading': isLoading}">
            <form @submit.prevent="onSubmitAsset" 
                id="create-asset-form"
                data-edit-url="/asset/@id" 
                action="/asset/create" 
                data-vv-name="name"
                data-vv-as="name"
                method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Create new asset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="source">Source</label>
                        <select v-model="source" class="form-control" name="source" id="source">
                            <?php foreach ($data['sources'] as $source): ?>
                            <option value="<?php __($source) ?>"><?php __(ucfirst($source)) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div v-show="source == '<?php __($data['sources']['cdn']) ?>'" id="cdn-block">
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input 
                                v-model="url"
                                v-validate="{url: true, required: source == '<?php __($data['sources']['cdn']) ?>'}" 
                                placeholder="Enter url of cdn file" 
                                :class="{'form-control': true, 'is-invalid': errors.has('url') }" 
                                name="url" 
                                type="url">
                            <span class="invalid-feedback">{{ errors.first('url') }}</span>
                        </div>
                    </div>
                    <div v-show="source == '<?php __($data['sources']['file']) ?>'" id="file-block">
                        <div class="form-group">
                            <label  for="name">Name</label>
                            <input 
                                v-model="name"
                                v-validate="{required: source == '<?php __($data['sources']['file']) ?>'}" 
                                placeholder="Enter asset file name, ex app.js, style.css" 
                                :class="{'form-control': true, 'is-invalid': errors.has('name') }" 
                                name="name" 
                                type="text">
                            <span class="invalid-feedback">{{ errors.first('name') }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select v-model="type" class="form-control" name="type" id="type">
                                    <option value="js">JS</option>
                                    <option value="css">CSS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="type">Placement position</label>
                                <select v-modal="status" class="form-control" name="type" id="type">
                                    <option value="bottom">End of body</option>
                                    <option value="top">In header</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" data-button="Loading..." class="btn btn-primary">New asset</button>
                </div>
            </form>
        </div>
    </div>
</div>