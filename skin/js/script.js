$(document).ready(function () {
    $(".msg-container").slideDown(150, function() {
        $(this).css('display', 'flex');
    })
    setTimeout(() => {
        $(".msg-container").slideUp(150)
    }, 2000)
})