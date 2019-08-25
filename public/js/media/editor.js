var mediaEditor = new Vue({
    el: '#media-editor',

    data: function () {

        return {
            dirs: [],
            fileInDirs: {},
            hasLoading: false,
            isDraggingUpload: false,
            uploadFiles: [],

            currentSelectedDir: '',
            hideSuccessAfterMilSec: 3000
        }
    },

    mounted: function () {

        this.fetchDir()
    },

    methods: {

        dirs: function () {
            var dirs = [];

            for (var filename in this.files) {
                if (this.files[filename].type == 'dir') {
                    dirs.push(this.files[filename])
                }
            }

            return dirs
        },

        onClickDir: function (index) {

            this.dirs.forEach(function (dir, i) {
                this.dirs[i].is_active = false
            }.bind(this))

            this.dirs[index].is_active = true
            this.currentSelectedDir = this.dirs[index].name

            this.fetchFile(this.dirs[index].name)
        },

        setActiveDir: function (name) {
            
            this.dirs.forEach(function (dir, i) {
                this.dirs[i].is_active = false
            }.bind(this))

            var result = -1

            for (index in this.dirs) {

                if (this.dirs[index].name == name) {
                    result = index
                }
            }

            if (result > -1) {
                this.dirs[result].is_active = true
            }
        },

        onClickNewFolder: function () {
            var self = this
            bootbox.prompt('Enter directory name', function (result) {

                if (!result) {
                    return true
                }

                var isExist = self.checkDirName(result)

                if (isExist) {
                    toastr.error('Directory name is already use, please change')
                    return false
                }

                if (!result.length) {
                    toastr.error('Please enter new directory name')
                    return false
                }

                self.createNewFolder(result)
            })
        },

        onClickDeleteDir: function (index) {

            var self = this

            bootbox.confirm('Are you sure to delete this folder and all files?', function (result) {
                if (!result) {
                    return true
                }

                self.deleteDir(self.dirs[index].name)
            })
        },

        deleteDir: function (name) {

            var self = this

            axios.delete(window.url.dirs + '/' + name)
            .then(function (res) {
                self.fetchDir()
                toastr.success('Folder ' + name + ' delete sucess')
            })
            .catch(function (err) {
                console.log(err)
                toastr.error('Folder ' + name + ' delete fail')
            })
        },

        createNewFolder: function (name) {

            var self = this

            axios.post(window.url.dirs, {
                directory: name
            })
            .then(function (res) {
                toastr.success('Directory create success')
                self.fetchDir(function () {
                    self.fetchFile(name)
                    self.setActiveDir(name)
                })
            })
            .catch(function (err) {
                toastr.error(err.response.data.message)
            })
        },

        checkDirName: function (newFolderName) {
            var names = []

            for (var dir in this.dirs) {
                names.push(this.dirs[dir].name)
            }

            return names.indexOf(newFolderName) > -1
        },

        fetchDir: function (callback) {
            
            var self = this

            self.hasLoading = true

            axios.get(window.url.dirs)
                .then(function (res) {

                    self.hasLoading = false
                    self.dirs = res.data.data.files
                    self.fetchFile('')

                    if (typeof callback == 'function') {
                        callback()
                    }
                })
        },

        fetchFile: function (directoryName) {
            var self = this

            self.hasLoading = true

            axios.post(window.url.files, {
                directory: directoryName
            })
                .then(function (res) {

                    self.hasLoading = false
                    self.fileInDirs = res.data.data.files
                })
        },

        onDragEnter: function () {
            this.isDraggingUpload = true
        },

        onDragLeave: function () {
            this.isDraggingUpload = false            
        },

        onDragOver: function () {
            this.isDraggingUpload = true
        },

        onDrop: function (e) {
            this.isDraggingUpload = false

            var df = e.dataTransfer
            var files = df.files

            if (!files.length) {
                return
            }

            this.handleUploadFile(files)
        },

        handleUploadFile: function (files) {

            this.uploadFiles = []

            for (var i = 0; i < files.length; i++) {

                var file = {
                    name: files[i].name,
                    type: files[i].type,
                    size: files[i].size / 1024 / 1024,
                    valid: true,
                    message: '',
                    percent: 0,
                    status: false,
                    file: files[i]
                }

                if (window.allowedMimes.indexOf(file.type) == -1) {
                    file.valid = false
                    file.message = 'File type invalid'
                }

                var allowedFileSize = window.fileSize.indexOf('M') > -1 ? window.fileSize.replace(/[^0-9.]/, '') : 0
                allowedFileSize = parseInt(allowedFileSize)

                if (file.size > allowedFileSize) {
                    file.valid = false
                    file.message = 'File size invalid'
                }

                this.uploadFiles.push(file)
            }

            for (var i = 0; i < this.uploadFiles.length; i++) {

                if (this.uploadFiles[i].status === false) {
                    if (this.uploadFiles[i].valid) {
                        this.upload(this.uploadFiles[i], i)
                    }
                }
            }
        },

        onSelectFile: function (e) {

            var files = e.target.files

            if (!files.length) {
                return
            }

            this.handleUploadFile(files)
        },

        readyForUploadFiles: function () {

            var files = [] 

            for (var i = 0; i < this.uploadFiles.length; i++) {
                if (!this.uploadFiles[i].status) {
                    files.push(this.uploadFiles[i])
                }
            }

            return files
        },

        deleteUploadFile: function (index) {

            this.uploadFiles[index].status = true
        },

        upload: function (file, index) {

            var self = this,
                formData = new FormData

            formData.append('file', file.file)
            formData.append('directory', this.currentSelectedDir)

            axios.post(window.url.uploadFile, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },

                onUploadProgress: function(progressEvent) {
                    var percent = parseInt( Math.round((progressEvent.loaded * 100 ) / progressEvent.total))
                    self.uploadFiles[index].percent = percent
                }
            })
            .then(function () {
                
                self.handleWhenAllSuccess()

                setTimeout(function () {
                    self.uploadFiles[index].status = true
                }, self.hideSuccessAfterMilSec)
            })
            .catch(function (err) {

                self.uploadFiles[index].valid = false
                self.uploadFiles[index].message = err.response.data.message

                self.handleWhenAllSuccess()

                setTimeout(function () {
                    self.uploadFiles[index].status = true
                }, self.hideSuccessAfterMilSec)
            })
        },

        handleWhenAllSuccess: function () {
            var result = []

            for (var i = 0; i < this.uploadFiles.length; i++) {
                result.push(this.uploadFiles.status)
            }

            var isAllSuccess = result.indexOf(false) == -1

            if (isAllSuccess) {
                this.fetchFile(this.currentSelectedDir)
            }
        },

        onClickDeleteFile: function (filename) {

            var self = this

            bootbox.confirm('Are you sure to delete ' + filename + ' file?', function (result) {
                if (!result) {
                    return true
                }

                self.deleteFile(filename)
            })
        },

        deleteFile: function (filename) {

            var self = this

            axios.delete(window.url.files, {
                data: {
                    directory: this.currentSelectedDir,
                    filename: filename
                }
            })
            .then(function () {

                toastr.success('Delete file ' + filename + ' success')
                self.fetchFile(this.currentSelectedDir)
            })
            .catch(function () {
                toastr.error('Delete file ' + filename + ' fail')
                self.fetchFile(this.currentSelectedDir)
            })
        }
    }
})