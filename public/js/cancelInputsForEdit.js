/**
 * возврат от input-ов обратно к неизменяемым span-ам
 * @param cellElems
 * @param oldValues
 */
function cancelInputsForEdits(cellElems, oldValues) {
    cellElems.forEach(elem=>{
        let key = elem.name;
        let value = oldValues[key];
        elem.parentElement.textContent = value;
    });
}