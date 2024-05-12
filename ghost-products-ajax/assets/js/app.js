jQuery(document).ready(function($) {
    $('#ghost-search-input').on('keyup',function() {
        var searchKeyword = $(this).val();
        if(searchKeyword.length < 1){
            $('#ghost-products-container').hide()
        }
        if(searchKeyword.length > 2){
            $('#ghost-products-container').show()
            $.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: {
                    action: 'ghost_product',
                    keyword: searchKeyword
                },
                success: function(response) {
                    $("#ghost-products-container").html(response)
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", error);
                    console.error("Status:", status);
                }
            });
        }
    });
});