var mediaEditor = new Vue({
    el: '#media-editor',

    data: function () {

        return {
            dirs: [],
            fileInDirs: {},
            hasLoading: false
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

            bootbox.confirm('Are you sure to delete this folder and all files?', function () {
                
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
        }
    }
})