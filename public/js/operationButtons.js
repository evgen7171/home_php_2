/**
 * класс кнопок для операций
 * buttonEdit - кнопка для редактирования, когда показана одна позиция
 * buttonCancel - кнопка для отмены редактирования, когда показана одна позиция
 * buttonSave - кнопка для сохранения, когда показана одна позиция
 * buttonAdd - кнопка для добавления, когда показаны все позиции
 */
class OperationButtons {
    constructor() {
        const tableNameElem = document.getElementById('tableName');
        this.tableName = tableNameElem.textContent;
        this.oldValues = [];
        this.data = [];

        this.buttonEditElem = document.getElementById('editButton');
        this.buttonCancelElem = document.getElementById('cancelButton');
        this.hideButtonsElems = document.querySelectorAll('.hideButtons');

        this.buttonSaveElem = document.getElementById('saveButton');
        this.buttonAddElem = document.getElementById('addButton');

        this.buttonEditHandler();
        this.buttonSaveHandler();
        this.buttonCancelHandler();
        this.buttonAddHandler();
    }

    buttonEditHandler() {
        if (this.buttonEditElem) {
            this.buttonEditElem.addEventListener('click', () => {
                const cellElems = document.querySelectorAll('.cellContentSpan');
                this.oldValues = insertInputsForEdit(cellElems);
                this.buttonEditElem.style.display = 'none';
                this.hideButtonsElems.forEach(elem => elem.style.display = 'block');
            });
        }
    }

    buttonCancelHandler() {
        if (this.buttonCancelElem) {
            this.buttonCancelElem.addEventListener('click', () => {
                const cellElems = document.querySelectorAll('.cellContentEdit');
                cancelInputsForEdits(cellElems, this.oldValues);
                this.buttonEditElem.style.display = 'block';
                this.hideButtonsElems.forEach(elem => elem.style.display = 'none');
            });
        }
    }

    buttonSaveHandler() {
        if (this.buttonSaveElem) {
            this.buttonSaveElem.addEventListener('click', () => {
                this.data = getDataFromTable();
                getHiddenForm(this.data, 'update', this.tableName).submit();
            });
        }
    }

    buttonAddHandler() {
        if (this.buttonAddElem) {
            this.buttonAddElem.addEventListener('click', () => {
                const formAddElem = document.getElementById('formAdd');
                this.buttonAddElem.style.display = 'none';
                const propertiesStr = this.buttonAddElem.dataset['properties'];
                let properties = propertiesStr.split(', ');
                formAddElem.innerHTML = getFormAdd(this.tableName, properties);
            });
        }
    }
}

const operationButtons = new OperationButtons();