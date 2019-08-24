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
                <a @click.prevent="onClickNewFolder" class="btn btn-block btn-primary mt-3" class="nav-link" href="">
                    <i class="fas fa-folder-plus"></i> New folder
                </a>
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
                                            <div class="progress-bar" :style="{'width': uploadFile.file + '%'}"></div>
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
                            <button class="btn btn-primary">Upload</button>
                        </div>
                    </div>
                    <div 
                        @dragenter.prevent="onDragEnter"
                        @dragleave.prevent="onDragLeave"
                        @dragover.prevent="onDragOver"
                        @drop.prevent="onDrop"
                        :class="{'card-body media-editor-body': true, 'dragging': isDraggingUpload, 'has-loading': hasLoading}">
                        <ul v-if="fileInDirs" class="mailbox-attachments align-items-stretch clearfix">
                            <li v-for="file in fileInDirs">
                                <span class="mailbox-attachment-icon">
                                    <i class="far fa-file-pdf"></i>
                                </span>
                                <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name">
                                        <i class="fas fa-paperclip"></i> {{ file.name }}
                                    </a>
                                    <span class="mailbox-attachment-size clearfix mt-1">
                                        <span>1,245 KB</span>
                                        <a href="#" class="btn btn-default btn-sm float-right"><i class="fas fa-cloud-download-alt"></i></a>
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