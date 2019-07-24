/**
 * получить форму, которую не будет видно, для отправки данных, для изменения
 */
function getHiddenForm(data, action, table) {
    let textHTML = `<form id="hiddenForm" action="index.php?action=${action}&table=${table}" method="post">`;
    for (item in data) {
        textHTML += `<input type="hidden" name="${item}" value="${data[item]}">`;
    }
    textHTML += `</form>`;
    document.querySelector('.content').insertAdjacentHTML('beforeend', textHTML);
    return document.getElementById('hiddenForm');
}