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
            responseDiv.innerHTML = xmlHttp.responseText;
            addClearContentButton();
        }
    }
    xmlHttp.open('post', 'inc/php/get-level-content.inc.php');
    xmlHttp.send(requestData);
}

/**
 * Clears html-tag with ajax-response data
 */
function clearContent(elem) {
    document.getElementById('response-div').innerHTML = '';
    elem.remove();
    if (document.getElementsByClassName('resize-triggers').length > 0) {
        document.location.reload();
    }
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
        if (entityName === null) {
            window.alert('Invalid name! Letters and numbers only...');
            return;
        }
        const regexp = /^[A-Z\s0-9\-]+$/i;
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
                reloadContent(element);
            }
        } else if (xmlHttp.readyState === 4 && xmlHttp.status === 302) {
            ajaxErrorHandler(xmlHttp);
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
                                '<input name="attachment[]" ' +
                                        'type="file" id="file-form" multiple="multiple">' +
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
                reloadContent(element);
            } else if (xmlHttp.readyState === 4 && xmlHttp.status === 302) {
                ajaxErrorHandler(xmlHttp);
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
        } else if (xmlHttp.readyState === 4 && xmlHttp.status === 302) {
            ajaxErrorHandler(xmlHttp);
        }
    }
    xmlHttp.open('post', 'inc/php/delete-file.inc.php');
    xmlHttp.send(requestData);
}

/**
 * Asynchronous session deleting
 */
function userLogout() {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            document.location.reload();
        }
    }
    xmlHttp.open('post', 'inc/php/user-log-out.inc.php');
    xmlHttp.send();
}

/**
 * Displays error message to user
 */
function ajaxErrorHandler(ajaxObject) {
    window.alert(ajaxObject.responseText);
}

/**
 * Reloads subdirectory information
 * @param element - event element
 */
function reloadContent(element) {
    [0, 1].forEach(function() {
        element.parentElement.children[0].click();
    });
}

/**
 * Adds button to navbar
 */
function addClearContentButton() {
    let btn = document.getElementById('clear-content');
    if (btn) {
        return;
    }
    btn = document.createElement('button');
    btn.innerHTML = 'CLEAR CONTENT';
    btn.id = 'clear-content';
    btn.classList.add('btn', 'btn-secondary', 'btn-sm');
    btn.addEventListener('click', function() {
        clearContent(this);
    });
    document.getElementById('nav').append(btn);
}

/**
 * Takes json data and visualize within chart
 */
function showStatistic() {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            let responseDiv = document.getElementById('response-div');
            responseDiv.innerHTML = '';
            let chartData = JSON.parse(xmlHttp.responseText),
                statisticDatesArray = [],
                uniqueUsers = [],
                visitsAtAll = [],
                uniqueUsersQuantity = 0,
                visitsAtAllQuantity = 0;
            for (let statisticDate in chartData) {
                if (chartData.hasOwnProperty(statisticDate)) {
                    uniqueUsersQuantity = 0; visitsAtAllQuantity = 0;
                    statisticDatesArray.push(statisticDate);
                    for (const statisticUsername in chartData[statisticDate]) {
                        if (chartData[statisticDate].hasOwnProperty(statisticUsername)) {
                            uniqueUsersQuantity++;
                            visitsAtAllQuantity += chartData[statisticDate][statisticUsername];
                        }
                    }
                    uniqueUsers.push(uniqueUsersQuantity);
                    visitsAtAll.push(visitsAtAllQuantity);
                }
            }
            let options = {
                series: [
                    {
                        name: "VISITS AT ALL",
                        data: visitsAtAll
                    },
                    {
                        name: "UNIQUE VISITS",
                        data: uniqueUsers
                    }
                ],
                chart: {
                    height: 350,
                    type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    },
                },
                colors: ['#77B6EA', '#545454'],
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    curve: 'smooth'
                },
                title: {
                    text: 'DETAIL VISITS STATISTIC',
                    align: 'left'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0.5
                    },
                },
                markers: {
                    size: 1
                },
                xaxis: {
                    categories: statisticDatesArray,
                    title: {
                        text: 'VISITS DATE'
                    }
                },
                yaxis: {
                    title: {
                        text: 'VISITS QUANTITY'
                    },
                    min: 0,
                    max: Math.max.apply(null, visitsAtAll) + 1
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    floating: true,
                    offsetY: -25,
                    offsetX: -5
                }
            };
            /*** @var ApexCharts */
            let chart = new ApexCharts(responseDiv, options);

            /*** @var render */
            chart.render();
            addClearContentButton();
        }
    }
    xmlHttp.open('post', 'inc/php/show-statistic.inc.php');
    xmlHttp.send();
}

/**
 * Allows to rename directory or file
 * @param entityPath - path to entity to be renamed
 * @param element - event element
 * @param elementType - can be directory or file
 * @param level - content tree level
 */
function updateItem(entityPath, element, elementType, level) {
    let xmlHttp = new XMLHttpRequest(), requestData = new FormData();
    requestData.append('entityPath', entityPath);
    requestData.append('elementType', elementType);
    requestData.append('level', level);
    let entityName = prompt('Enter new name (letters and numbers only)');
    if (entityName === null) {
        window.alert('Invalid name! Letters and numbers only...');
        return;
    }
    const regexp = /^[A-Z\s0-9\-]+$/i;
    const matches = entityName.match(regexp);
    if (matches === null) {
        window.alert('Invalid name! Letters and numbers only...');
        return;
    } else {
        requestData.append('entityName', entityName);
    }
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            if (level === '0') {
                document.location.reload();
            } else {
                let neededElement = element.parentElement.parentElement.parentElement.parentElement.children[0];
                reloadContent(neededElement);
            }
        } else if (xmlHttp.readyState === 4 && xmlHttp.status === 302) {
            ajaxErrorHandler(xmlHttp);
        }
    }
    xmlHttp.open('post', 'inc/php/rename-entity.inc.php');
    xmlHttp.send(requestData);
}