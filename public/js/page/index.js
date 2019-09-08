Vue.use(VeeValidate)

var createPageModal = new Vue({
    el: '#create-page-modal',

    data: function () {

        return {
            name: ''
        }
    },

    watch: {

        name: function (val) {
            this.name = val.toLocaleLowerCase().replace(/[^0-9a-z]+/g, '')
        }
    },

    methods: {
        onCreatePage: function () {
            this.$validator.validate().then(function (result) {
                if (!result) {
                    return
                }                
            })
        }
    }
})