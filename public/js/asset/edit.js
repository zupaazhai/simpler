var assetForm = new Vue({

    el: '#asset-form',

    data: function () {

        return {
            editor: null
        }
    },

    mounted: function () {

        var self = this

        this.$nextTick(function () {

            if (!this.$refs.contentEditor) {
                return
            }

            self.editor = CodeMirror.fromTextArea(this.$refs.contentEditor, {
                theme: 'mdn-like',
                lineNumbers: true,
                matchBrackets: true,
                mode: window.type == 'js' ? 'javascript' : 'css'
            })

            self.editor.setSize(null, 600)
        })
    }
})