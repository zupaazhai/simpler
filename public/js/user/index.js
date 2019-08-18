var userTable = new Vue({
    el: '#user-table',

    methods: {
        onClickDelete: function (id) {
            var self = this 
            bootbox.confirm('Are you sure to delete user?', function (res) {
                if (!res) {
                    return
                }
                
                var form = self.$refs['deleteForm' + id]
                form.submit()
            })
        }
    }
})