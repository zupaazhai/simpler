
Vue.use(VeeValidate)

var createForm = new Vue({
    el: '#create-asset-modal',

    data: function () {
        return {
            isLoading: false,
            name: '',
            type: 'js',
            position: 'bottom'
        }
    },

    mounted: function () {

    },

    methods: {
        onSubmitAsset: function (e) {

            self = this

            this.$validator.validate().then(function (valid) {
                if (!valid) {
                    return
                }

                self.createAsset(e.target.getAttribute('action'), e.target.getAttribute('data-edit-url'))
            })
        },

        createAsset: function (url, editUrl) {

            var self = this
            self.isLoading = true

            axios.post(url, {
                name: this.name,
                type: this.type,
                position: this.position
            })
            .then(function (res) {

                var redirectUrl = editUrl.replace('@id', res.data.data.id)

                window.location.href = redirectUrl
                self.isLoading = false
            })
            .catch(function (err) {

                self.isLoading = false
                
                if (err.response.data.message == 'asset_file_exists') {
                    
                    var field = self.$validator.fields.find({
                        name: 'name', 
                        scope: self.$options.scope
                    })

                    self.$validator.errors.add({
                        id: field.id,
                        field: 'name',
                        msg: 'File name already exists',
                        scope: self.$options.scope,
                    })
                }    
            })
        }
    }
})

var assetTable = new Vue({
    el: '#asset-table',

    methods: {
        onClickDelete: function (id) {
            var self = this
            bootbox.confirm('Are you sure to delete this asset?', function (res) {
                if (!res) {
                    return
                }

                var form = self.$refs['deleteForm' + id]
                form.submit()
            })
        }
    }
})