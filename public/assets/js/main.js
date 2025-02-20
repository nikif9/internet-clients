/**
 * Функция для переключения видимости потомков.
 * Использует AJAX для загрузки потомков, если контейнер пуст.
 *
 * @param {Event} event Событие клика.
 * @param {number|string} id Идентификатор родительского элемента.
 */
function toggleChildren(event, id) {
    event.stopPropagation();
    var li = event.target.parentNode;
    var childrenContainer = li.querySelector('ul.children');
    if (childrenContainer.style.display === 'none' || childrenContainer.style.display === '') {
        if (childrenContainer.innerHTML.trim() === '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            childrenContainer.innerHTML = response.html;
                        }
                    } catch(e) {
                        console.error(e);
                    }
                }
            };
            xhr.open('GET', '/api/get_children?parent_id=' + id, true);
            xhr.send();
        }
        childrenContainer.style.display = 'block';
        event.target.innerHTML = '[-]';
    } else {
        childrenContainer.style.display = 'none';
        event.target.innerHTML = '[+]';
    }
}

/**
 * Загружает описание выбранного элемента.
 *
 * @param {number|string} id Идентификатор элемента.
 */
function showDescription(id) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if(xhr.readyState === 4 && xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if(response.success) {
                    document.getElementById('descContent').innerHTML = response.description || 'Нет описания';
                }
            } catch(e) {
                console.error(e);
            }
        }
    };
    xhr.open('GET', '/api/get_description?id=' + id, true);
    xhr.send();
}
