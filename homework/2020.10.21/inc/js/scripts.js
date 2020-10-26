function getContent(contentPath, contentMimeTypeGroup) {
    let xmlHttp = new XMLHttpRequest();
    let requestData = new FormData();
    requestData.append('contentPath', contentPath);
    requestData.append('contentMimeTypeGroup', contentMimeTypeGroup);
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            document.getElementById('response_div').innerHTML = xmlHttp.responseText;
        }
    }
    xmlHttp.open('post', 'get-content.php');
    xmlHttp.send(requestData);
}

function processItem(newDirPath, itemType, operationType) {
    if (itemType === 'directory') {
        let message = (operationType === 'add') ? 'нової директорії' : 'директорії для видалення';
        let newItemName = prompt('Введіть назву ' + message + ' (лише латинські або кириличні літери)');
        if (newItemName !== null) {
            let xmlHttp = new XMLHttpRequest();
            let requestData = new FormData();
            requestData.append('newDirPath', newDirPath);
            requestData.append('newItemName', newItemName);
            requestData.append('operationType', operationType);
            xmlHttp.onreadystatechange = function() {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                    document.location.reload();
                }
            }
            xmlHttp.open('post', 'process-item.php');
            xmlHttp.send(requestData);
        }
    }
}