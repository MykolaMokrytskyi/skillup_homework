/**
 * Displays directory's content
 * @param element - event element
 * @param levelPath - path to directory to display content
 * @param levelNumber - content tree's level
 */
function showHideNextLevelContent(element, levelPath, levelNumber) {
    let parentElement = element.parentElement, lastChild = parentElement.lastChild;
    if (lastChild.className === 'dir-content') {
        parentElement.removeChild(lastChild);
    } else {
        let xmlHttp = new XMLHttpRequest(), requestData = new FormData();
        requestData.append('levelPath', levelPath);
        requestData.append('levelNumber', levelNumber);
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
                let folderContent = document.createElement('div');
                folderContent.classList.add('dir-content');
                parentElement.appendChild(folderContent);
                folderContent.innerHTML = xmlHttp.responseText;
            }
        }
        xmlHttp.open('post', 'inc/php/get-level-info.inc.php');
        xmlHttp.send(requestData);
    }
}

/**
 * Displays file content
 * @param contentPath - path to file to be displayed
 */
function getContent(contentPath) {
    let xmlHttp = new XMLHttpRequest(), requestData = new FormData();
    requestData.append('contentPath', contentPath);
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            let responseDiv = document.getElementById('response-div');
            responseDiv.innerHTML = '';
            let responseContent = document.createElement('div');
            responseContent.classList.add('response-content');
            responseDiv.prepend(responseContent);
            responseDiv.getElementsByClassName('response-content')[0].innerHTML = xmlHttp.responseText;
            let btn = document.createElement('button');
            btn.innerHTML = 'Clear content';
            btn.classList.add('btn', 'btn-secondary', 'btn-sm');
            btn.addEventListener('click', clearContent);
            responseDiv.prepend(btn);
        }
    }
    xmlHttp.open('post', 'inc/php/get-level-content.inc.php');
    xmlHttp.send(requestData);
}

/**
 * Clears html-tag with ajax-response data
 */
function clearContent() {
    document.getElementById('response-div').innerHTML = '';
}

/**
 * Allows to choose action (add or remove) and entity type
 * @param operationType - add or remove entity
 * @param parentDirectoryPath - parent directory's path where entity is placed/will be placed
 * @param element - event element
 */
function addRemoveEntity(operationType, parentDirectoryPath, element) {
    let chosenMode = true;
    if (operationType === 'add') {
        let message = (operationType === 'add') ? ' add new ' : ' delete existing ';
        chosenMode = confirm('Press "OK" if you want to' + message + 'directory (otherwise you\'ll work with files)');
    }
    if (chosenMode) {
        let entityName = prompt('Enter directory name (letters and numbers only)');
        const regexp = /^[A-Z\s0-9]+$/i;
        const matches = entityName.match(regexp);
        if (matches === null) {
            window.alert('Invalid name! Letters and numbers only...');
        } else {
            addRemoveDirectory(operationType, parentDirectoryPath, entityName, element);
        }
    } else {
        if (operationType === 'add') {
            addFile(parentDirectoryPath, element);
        }
    }
}

/**
 * Allows to add/remove directory
 * @param operationType - add or remove directory
 * @param parentDirectoryPath - parent directory's path where directory is placed/will be placed
 * @param directoryName - directory that will be processed
 * @param element - event element
 */
function addRemoveDirectory(operationType, parentDirectoryPath, directoryName, element) {
    let xmlHttp = new XMLHttpRequest(), requestData = new FormData();
    requestData.append('operationType', operationType);
    requestData.append('parentDirectoryPath', parentDirectoryPath);
    requestData.append('newDirectoryName', directoryName);
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            let childrenQuantity = element.parentElement.children.length;
            if (element.parentElement.children[childrenQuantity - 1].className === 'dir-content') {
                [0, 1].forEach(function() {
                    element.parentElement.children[0].click();
                });
            }
        }
    }
    xmlHttp.open('post', 'inc/php/create-delete-directory.inc.php');
    xmlHttp.send(requestData);
}

/**
 * Makes modal form that allows to choose file
 * @param parentDirectoryPath - parent directory's path where file will be placed
 * @param element - event element
 */
function addFile(parentDirectoryPath, element) {
    let modal = document.createElement('div');
    modal.classList.add('modal');
    document.body.prepend(modal);
    modal.innerHTML = '<div class="modal-content">' +
                            '<span class="warning">Notice: file\'s size can\'t be greater than 5 MB</span>' +
                            '<form method="post" enctype="multipart/form-data">' +
                                '<input name="attachment" type="file" id="file-form">' +
                            '</form>' +
                            '<div>' +
                                '<button class="btn btn-secondary btn-sm" id="load-file">' +
                                    'Load chosen file</button>' +
                                '<button id="close-modal" ' +
                                    'class="btn btn-secondary btn-sm">Close window</button>' +
                            '</div>' +
                        '</div>';
    document.getElementById('close-modal').addEventListener('click', function() {
        modal.remove();
    });
    document.getElementById('load-file').addEventListener('click', function() {
        loadFile(parentDirectoryPath, element);
    });
}

/**
 * Load file to chosen directory
 * @param parentDirectoryPath - parent directory's path where file will be placed
 * @param element - event element
 */
function loadFile(parentDirectoryPath, element) {
    let xmlHttp = new XMLHttpRequest(), formElements = document.forms[0], requestData = new FormData(formElements);
    requestData.append('parentDirectoryPath', parentDirectoryPath);
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            document.getElementsByClassName('modal')[0].remove();
            let childrenQuantity = element.parentElement.children.length;
            if (element.parentElement.children[childrenQuantity - 1].className === 'dir-content') {
                [0, 1].forEach(function() {
                    element.parentElement.children[0].click();
                });
            }
        }
    }
    xmlHttp.open('post', 'inc/php/load-file.inc.php');
    xmlHttp.send(requestData);
}

/**
 * Removes existing file
 * @param element - event element
 * @param entityPath - path to entity that will be removed
 */
function removeFile(element, entityPath) {
    let xmlHttp = new XMLHttpRequest(), requestData = new FormData();
    requestData.append('entityPath', entityPath);
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            element.previousElementSibling.remove();
            element.remove();
        }
    }
    xmlHttp.open('post', 'inc/php/delete-file.inc.php');
    xmlHttp.send(requestData);
}