function showHidePostsList(element) {
    let elementsList = ['current_posts_list_label', 'current_posts_list'];
    if (element.value === 'sub_post') {
        if (document.getElementById(elementsList[1]).querySelectorAll('option').length > 0) {
            document.getElementById('submit_btn').innerHTML = 'Додати коментар до існуючого допису';
            elementsList.forEach(function(item) {
                document.getElementById(item).style.display = 'block';
            });
        } else {
            window.alert('Перелік існуючих дописів не знайдено! Оберіть пункт "новий", додайте допис' +
                ' та повторіть спробу знову...');
            element.value = 'new_post';
        }
    } else {
        document.getElementById('submit_btn').innerHTML = 'Додати новий допис';
        elementsList.forEach(function(item) {
            document.getElementById(item).style.display = 'none';
        });
    }
}

function setValues(parentPostId) {
    document.getElementById('post_type').value = 'sub_post';
    let elementsList = ['current_posts_list_label', 'current_posts_list'];
    document.getElementById('submit_btn').innerHTML = 'Додати коментар до існуючого допису';
    elementsList.forEach(function(item) {
        document.getElementById(item).style.display = 'block';
    });
    document.getElementById('current_posts_list').value = parentPostId;
}