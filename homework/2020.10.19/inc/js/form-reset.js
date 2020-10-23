window.onload = function () {
    ['username', 'post_message'].forEach(function(item) {
        document.getElementById(item).value = '';
    });
};