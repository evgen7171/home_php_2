/**
 * функция получения данных из полей
 * @returns {Array} массив данных
 */
function getDataFromTable() {
    const data = [];
    cellContentElems = document.querySelectorAll('.cellContent');
    cellContentElems.forEach(elem => {
        let property = elem.dataset['property'];
        let elementChild = elem.firstElementChild;
        if (elementChild.className === 'cellContentId') {
            data[property] = elementChild.textContent;
        } else {
            let inputElem = elementChild.firstElementChild;
            data[property] = inputElem.value;
        }
    });
    return data;
}