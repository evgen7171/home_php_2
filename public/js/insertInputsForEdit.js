/**
 * полуучение input-ов из span-ов для внесения изменений в данные
 * @param cellElems
 * @returns {Array}
 */
function insertInputsForEdit(cellElems) {
    const oldValues = [];
    cellElems.forEach(elem => {
        let property = elem.parentElement.dataset['property'];
        let text = elem.textContent;
        oldValues[property] = text;
        elem.innerHTML = `<input class="cellContentEdit" type="text" name="${property}" value="${text}">`;
    });
    return oldValues;
}