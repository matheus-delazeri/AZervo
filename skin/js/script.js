jQuery(document).ready(function () {
    jQuery(".msg-container").slideDown(100, function() {
        $(this).css('display', 'flex');
    })
    setTimeout(() => {
        jQuery(".msg-container").slideUp(100)
    }, 5000)
})