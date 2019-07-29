class Buttons {
    constructor() {
        this.buttonEmpty = document.getElementById('data_button__empty');
        this.buttonEdit = document.getElementById('data_button__edit');
        this.buttonCancel = document.getElementById('data_button__cancel');
        this.buttonSave = document.getElementById('data_button__save');
        this.valueIdElem = document.querySelector('.data_key-value .value_id');
        this.valueElems = document.querySelectorAll('.data_key-value .value');
        this.oldValues = {};

        this.formAddElem = document.getElementById('form_add');
        this.buttonAddElem = document.getElementById('form_add__button');

        this.buttonEditHandle();
        this.buttonCancelHandle();
        this.buttonSaveHandle();
        this.buttonAddHandle();
    }

    isVerifyFormAdd = formElem => {
        const childElems = document.querySelectorAll('.data_add__item');
        const elemArray = [];
        for (let i = 0; i < childElems.length; i++) {
            if (!childElems[i].value.length) {
                elemArray.push(childElems[i].name);
            }
        }
        return elemArray;
    };

    messageErrorFormAdd = elemNames => {
        const elems = document.querySelectorAll('.data_add__item');
        elems.forEach(elem => {
            elem.classList.remove('form_add__error');
        });
        for (let elem of elems) {
            if (elemNames.indexOf(elem.name) !== -1) {
                elem.classList.add('form_add__error');
            }
        }
    };

    buttonAddHandle() {
        if (this.buttonAddElem) {
            this.buttonAddElem.addEventListener('click', () => {
                event.preventDefault();
                const notEmptyElemNames = this.isVerifyFormAdd(this.formAddElem);
                if (!notEmptyElemNames.length) {
                    this.formAddElem.submit();
                } else {
                    this.messageErrorFormAdd(notEmptyElemNames);
                }
            });
        }


    }

    buttonEditHandle() {
        if (this.buttonEdit) {
            this.buttonEdit.addEventListener('click', () => {
                this.valueElems.forEach(elem => {
                    let value = elem.textContent;
                    let width = elem.offsetWidth - 14;
                    let height = elem.offsetHeight - 18;
                    elem.innerHTML =
                        `<input type="text" 
                                value="${value}"
                                style = "width: ${width}px; height: ${height}px">`;
                    this.oldValues[elem.dataset['key']] = value;
                });
                this.buttonCancel.style.display = 'block';
                this.buttonSave.style.display = 'block';
                this.buttonEdit.style.display = 'none';
                this.buttonEmpty.style.display = 'none';
            });
        }
    }

    isEmptyObject = object => Object.keys(object).length === 0;

    buttonCancelHandle() {
        if (this.buttonCancel) {
            this.buttonCancel.addEventListener('click', () => {
                if (!this.isEmptyObject(this.oldValues)) {
                    this.valueElems.forEach(elem => {
                        elem.textContent = this.oldValues[elem.dataset['key']];
                    });
                }
                this.buttonCancel.style.display = 'none';
                this.buttonSave.style.display = 'none';
                this.buttonEdit.style.display = 'block';
                this.buttonEmpty.style.display = 'block';
            });
        }
    }

    addHideFormAdd = (controller, data) => {
        let textHTML = `<form action="/${controller}/update?id=${data.id}" id="hideFormAdd" method="post">`;
        for (let item in data) {
            if (item === 'id') {
                continue;
            }
            textHTML += `<input type="hidden" name="${item}" value="${data[item]}">`;
        }
        textHTML += '</form>';
        document.querySelector('.content').insertAdjacentHTML('beforeend', textHTML);
        return document.getElementById('hideFormAdd');
    };

    buttonSaveHandle() {
        if (this.buttonSave) {
            this.buttonSave.addEventListener('click', () => {
                const data = {
                    id: this.valueIdElem.textContent
                };
                this.valueElems.forEach(elem => {
                    data[elem.dataset['key']] = elem.firstElementChild.value;
                });
                const hideFormElem = this.addHideFormAdd(this.buttonSave.dataset['controller'], data);
                console.log(this);
                hideFormElem.submit();
            });
        }
    }

}


const buttons = new Buttons();
