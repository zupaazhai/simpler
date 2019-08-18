(function($, bb) {

    $('.delete-btn').on('click', function (e) {
        var $btn = $(e.currentTarget)
        bb.confirm('Are you sure to delete?', function (result) {
            if (!result) {
                return
            }

            $($btn.data('target')).submit()
        })
    })

})(jQuery, bootbox)