//Get the needed elements
const form = document.querySelector('.form');
const inputLibelle = document.getElementById('libelle');
const errorsSpan = document.getElementsByClassName('error');
const tbody = document.querySelector('tbody');

//Listen form's submit
form.addEventListener('submit', (e) => {
    //Stop the submit
    e.preventDefault();
    //Call the create function
    createCategory();
    return;
})

/**
 * Send a request for category backend insertion and display it
 */
function createCategory() {
    //Get the data from the form
    let data = new FormData();
    data.append("libelle", inputLibelle.value);
    //Ajax request 
    fetch("/categories/create", {
        body: data,
        method: "POST"
    }).then(async (response) => {
        let jsonRes = await response.json();
        if(jsonRes.success && jsonRes.data.id) {
            addTr(jsonRes.data.id, jsonRes.data.libelle);
            inputLibelle.value = '';
        } else if(jsonRes.errors) {
            displayErrors(jsonRes.errors);
        }
    });
}

let editId = null;
let editLibelle = null;
/**
 * Change the tr for edition
 * @param {*} id 
 */
function editCategory(id) {
    if(editId != null && editLibelle != null) {
        //Reset the unupdated tds if exists
        let libelleTd = document.getElementById('libelle' + editId);
        let button = document.getElementById('editButton' + editId);
        if(libelleTd && button) {
            libelleTd.innerHTML = editLibelle;
            button.innerText = "Modifier";
            button.setAttribute("onclick", `editCategory(${editId})`);
        }
    }
    let newLibelleTd = document.getElementById('libelle' + id);
    let newButton = document.getElementById('editButton' + id);
    editId = id;
    editLibelle = newLibelleTd.innerHTML;
    newLibelleTd.innerHTML = `<input type="text" class="text-xs" name="libelle${id}" id="inputLibelle${id}" value="${editLibelle}">`;
    newButton.innerText = "Valider";
    //Change the action button
    newButton.setAttribute('onclick', `updateCategory(${id})`);
}

/**
 * Update the category
 * @param {int} id id
 */
async function updateCategory(id) {
    const libelle = document.getElementById('inputLibelle' + id);
    if(libelle) {
        //Get the data
        let formData = new FormData();
        formData.append("libelle", libelle.value);
        //Make the request
        let res = await fetch(`/categories/${id}/update`, {
            body: formData,
            method: "POST"
        });
        //Convert to json
        let data = await res.json();
        //If successfull
        if(data.success) {
            //Update the tr
            const libelleTd = document.getElementById('libelle' + id);
            libelleTd.innerHTML = data.data.libelle;
            editLibelle = null;
            //Reset the button
            const button = document.getElementById('editButton' + id);
            button.innerText = "Modifier";
            button.setAttribute('onclick', `editCategory(${id})`);
        } else if(data.errors) {
            displayErrors(data.errors);
        }
    }
}

/**
 * Send a request and delete the category with matching id
 * @param {int} id id
 */
function deleteCategory(id) {
    //Ajax request
    fetch(`/categories/${id}/delete`, {
        method: "GET"
    }).then(async (response) => {
        let jsonRes = await response.json();
        if(jsonRes.success) {
            removeTr(id);
        } else if(jsonRes.errors) {
            displayErrors(jsonRes.errors);
        }
    });
}

/**
 * Display all the gived errors
 */
function displayErrors(errors) {
    for(let span of errorsSpan) {
        span.innerText = '';
    }
    for(let error in errors) {
        if(error === 'message') {
            alert(errors[error]);
        } else {
            displayError(error, errors[error]);
        }
    }
}

/**
 * Display the error with gived message
 * @param {string} error field
 * @param {string} message message
 */
function displayError(error, message) {
    const field = document.getElementsByName(error)[0];
    const span = field.nextSibling.nextSibling;
    span.innerText = message;
}

/**
 * Add a tr to the table
 * @param {string} id id
 * @param {string} libelle category's libelle
 */
function addTr(id, libelle) {
    //Create the tr
    const tr = document.createElement('tr');
    tr.setAttribute('id', `category${id}`);
    //Append the id's td
    const num = document.createElement('td');
    num.appendChild(document.createTextNode(id));
    tr.appendChild(num);
    //Append the libelle's td
    const libelleTd = document.createElement('td');
    libelleTd.innerHTML = libelle;
    libelleTd.setAttribute('id', `libelle${id}`);
    tr.appendChild(libelleTd);
    //Append the buttons td
    const actions = document.createElement('td');
    const div = document.createElement('div');
    //Create the update button
    const btnUpdate = document.createElement('button');
    btnUpdate.classList.add('btn-yellow');
    btnUpdate.classList.add('text-xs');
    btnUpdate.setAttribute('onclick', `editCategory(${id})`);
    btnUpdate.setAttribute('id', `editButton${id}`);
    btnUpdate.append(document.createTextNode('Modifier'));
    div.append(btnUpdate);
    //Create the delete button 
    const btnDelete = document.createElement('button');
    btnDelete.classList.add('btn-red');
    btnDelete.classList.add('text-xs');
    btnDelete.setAttribute('onclick', `deleteCategory(${id})`);
    const img = document.createElement('img');
    img.classList.add('icon-img');
    img.setAttribute('src', '/img/trash.png');
    img.setAttribute('alt', 'Icone poubelle');
    btnDelete.append(img);
    div.append(btnDelete);
    actions.append(div);
    tr.append(actions);
    //Append the tr
    tbody.append(tr);
}

/**
 * Remove a tr from the table
 * @param {int} id id
 */
function removeTr(id) {
    //Get the tr
    const tr = document.getElementById(`category${id}`);
    //Delete the tr
    tr.remove();
}