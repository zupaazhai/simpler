var mediaEditor = new Vue({
    el: '#media-editor',

    data: function () {

        return {
            files: window.files,
            fileInDirs: window.files['/'].children
        }
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

        onClickDir: function (dirname, index) {
            for (filename in this.files) {
                this.files[filename].is_active = false
            }

            this.files[dirname].is_active = true
            this.fileInDirs = []

            if (!this.files[dirname].children) {
                return
            }

            var files = this.files[dirname].children
                
            for (filename in files) {
                if (files[filename].type == 'file') {
                    this.fileInDirs.push(files[filename])
                }
            }
        },

        onClickNewFolder: function () {
            var self = this
            bootbox.prompt('Enter directory name', function (result) {
                var isExist = self.checkDirName(result)

                if (isExist) {
                    toastr.error('Directory name is already use, please change')
                    return false
                }
            })
        },

        checkDirName: function (newFolderName) {
            var names = []

            for (var dir in this.dirs()) {
                names.push(this.dirs()[dir].name)
            }

            return names.indexOf(newFolderName) > -1
        }
    }
})