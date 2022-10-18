jQuery(document).ready(function () {
    jQuery(".msg-container").slideDown(150, function() {
        $(this).css('display', 'flex');
    })
    setTimeout(() => {
        jQuery(".msg-container").slideUp(150)
    }, 4000)
})