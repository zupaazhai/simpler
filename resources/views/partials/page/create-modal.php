<div class="modal fade" id="create-page-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form @submit.prevent="onCreatePage" action="/page/create" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Create new page</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input 
                            v-model="name" 
                            v-validate="'required'" 
                            :class="{'form-control': true, 'is-invalid': errors.has('name')}" 
                            name="name"
                            placeholder="Enter page name, allow only lower alphanumeric" 
                            type="text">
                        <span class="invalid-feedback">{{ errors.first('name') }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>