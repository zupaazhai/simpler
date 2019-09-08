<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1>Media</h1>
            </div>
        </div>
    </div>
</div>

<section id="media-editor" class="content">
    <div class="container-fluid">
        <?php if (!$mediaIsWritable) : ?>
            <div class="row mb-2">
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Warning! please check folder permission</h5>
                        We just found your <strong>/media</strong> folder not has permission to upload a new file, please make sure you are change <i>chmod</i> to <strong>0775 or 0755</strong> or make <i>chown</i> to <strong>www-data</strong> or other user that has permission to upload new file.
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-lg-3">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Folder</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            <li v-cloak v-for="(dir, index) in dirs" class="nav-item p-2">
                                <a @click.prevent="onClickDir(index)" :class="{'nav-link d-flex': true, 'active': dir.is_active}" href="">
                                    <span class="flex-fill">
                                        <i class="far fa-folder"></i> {{ dir.name }}
                                    </span>
                                    <span v-if="dir.name !== '/'" class='flex-fill text-right'>
                                        <i @click="onClickDeleteDir(index)" class="fas fa-trash"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php if ($mediaIsWritable) : ?>
                    <a @click.prevent="onClickNewFolder" class="btn btn-block btn-primary mt-3" class="nav-link" href="">
                        <i class="fas fa-folder-plus"></i> New folder
                    </a>
                <?php endif ?>
            </div>
            <div class="col-lg-9">

                <div v-cloak v-if="readyForUploadFiles().length" class="card">
                    <div class="card-header">
                        <h3 class="card-title">Uploading</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Begin File Block -->
                            <div v-if="!uploadFile.status" v-for="(uploadFile, index) in uploadFiles" class="col-12 col-xs-6 col-lg-4">
                                <div :class="{'info-box': true, 'bg-primary': uploadFile.valid, 'bg-danger': !uploadFile.valid}">
                                    <span class="info-box-icon">
                                        <i :class="{'far fa-file': uploadFile.valid, 'fas fa-times-circle': !uploadFile.valid}"></i>
                                    </span>
                                    <div class="info-box-content" style="min-width: 80%;">
                                        <span class="info-box-text">{{ uploadFile.name }}</span>
                                        <div class="progress">
                                            <div class="progress-bar" :style="{'width': uploadFile.percent + '%'}"></div>
                                        </div>
                                        <div class="progress-description d-flex">
                                            <div class="flex-fill">
                                                <span v-if="!uploadFile.valid">{{ uploadFile.message }}</span>
                                                <span v-if="uploadFile.valid">{{ uploadFile.percent }}%</span>
                                            </div>
                                            <span v-if="!uploadFile.valid" class="flex-fill text-right">
                                                <i @click="deleteUploadFile(index)" class="fas fa-trash cursor-pointer"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End File Block -->
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex">
                        <h3 class="card-title flex-fill">Files</h3>
                        <div class="flex-fill text-right">
                            <small class="">Max file upload <?php __($maxFileUpload) ?></small>,
                            <small class="mr-2">Post max size <?php __($postMaxSize) ?></small>
                            <?php if ($mediaIsWritable) : ?>
                                <label>
                                    <span class="btn btn-primary">Upload</span>
                                    <input @change="onSelectFile" class="d-none" type="file" multiple>
                                </label>
                            <?php endif ?>
                        </div>
                    </div>
                    <div <?php if ($mediaIsWritable) : ?> @dragenter.prevent="onDragEnter" @dragleave.prevent="onDragLeave" @dragover.prevent="onDragOver" @drop.prevent="onDrop" <?php endif ?> :class="{'card-body media-editor-body': true, 'dragging': isDraggingUpload, 'has-loading': hasLoading}">
                        <ul v-if="fileInDirs" class="mailbox-attachments align-items-stretch clearfix">
                            <li v-for="file in fileInDirs">
                                <div class="mailbox-attachment-icon">
                                    <div v-if="file.is_image" class="preview-image" :style="{'background-image': 'url(' + file.url + ')' }">
                                    </div>
                                    <div v-if="!file.is_image" class="non-image">
                                        <i class="far fa-file"></i>
                                    </div>
                                </div>
                                <div class="mailbox-attachment-info">
                                    <a :dowload="file.name" target="_blank" :href="file.url" class="mailbox-attachment-name">{{ file.name }}</a>
                                    <span class="mailbox-attachment-size clearfix mt-1">
                                        <span>{{ file.size }}</span>
                                        <a @click.prevent="onClickDeleteFile(file.name)" class="btn btn-default btn-sm float-right">
                                            <i class="fas fa-trash cursor-pointer"></i>
                                        </a>
                                    </span>
                                </div>
                            </li>
                        </ul>
                        <div v-if="!Object.keys(fileInDirs).length">
                            <div class="media-editor-body-empty d-flex align-items-center justify-content-center">
                                <p class="text-lg text-gray">No file in this folder</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>