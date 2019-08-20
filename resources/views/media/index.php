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
                            <li v-cloak v-for="(dir, index) in dirs()" class="nav-item p-2">
                                <a @click.prevent="onClickDir(dir.name, index)" :class="{'nav-link': true, 'active': dir.is_active}" href="">
                                    <i class="far fa-folder"></i> {{ dir.name }}
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Files</h3>
                    </div>
                    <div class="card-body media-editor-body">
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