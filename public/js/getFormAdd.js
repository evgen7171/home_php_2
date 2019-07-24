/**
 * получение формы/полей для добавления записи в таблицу базы данных
 * @param tableName
 * @param properties
 * @returns {string}
 */
function getFormAdd(tableName, properties) {
    let textHTML = `
        <form action="index.php" method="post">`;
    for (let i = 0; i < properties.length; i++) {
        if (properties[i] === 'id') {
            continue;
        }
        textHTML += `
    <div>
        <p>${properties[i]}</p>
        <input class="editData" type="text" name="${properties[i]}">
    </div>`;
    }
    textHTML += `
            <input type="submit" id="saveAddedButton" value="Сохранить">
            <input type="reset" value="Сбросить">
            <input type="hidden" name="action" value="showAll">
            <input type="hidden" name="table" value="${tableName}">
        </form>`;
    return textHTML;
}