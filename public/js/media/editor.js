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

            console.log(this.fileInDirs)
        }
    }
})