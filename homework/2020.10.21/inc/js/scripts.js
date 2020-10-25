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

function addNewItem(newDirPath, itemType) {
    console.log(newDirPath + ' ' + itemType);
}