//Get the needed elements
const form = document.querySelector('.form');
const inputName = document.getElementById('name');
const errorsSpan = document.getElementsByClassName('error');
const tbody = document.querySelector('tbody');

//Listen form's submit
form.addEventListener('submit', (e) => {
    //Stop the submit
    e.preventDefault();
    //Call the create function
    createMembre();
    return;
})

/**
 * Send a request for membre backend insertion and display it
 */
function createMembre() {
    //Get the data from the form
    let data = new FormData();
    data.append("name", inputName.value);
    //Ajax request 
    fetch("/membres/create", {
        body: data,
        method: "POST"
    }).then(async (response) => {
        let jsonRes = await response.json();
        if(jsonRes.success && jsonRes.data.id) {
            addTr(jsonRes.data.id, inputName.value);
            inputName.value = '';
        } else if(jsonRes.errors) {
            displayErrors(jsonRes.errors);
        }
    });
}

let editId = null;
let editName = null;
/**
 * Change the tr for edition
 * @param {*} id 
 */
function editMembre(id) {
    if(editId != null && editName != null) {
        //Reset the unupdated tds if exists
        let nameTd = document.getElementById('name' + editId);
        let button = document.getElementById('editButton' + editId);
        if(nameTd && button) {
            nameTd.innerHTML = editName;
            button.innerText = "Modifier";
            button.setAttribute("onclick", `editMembre(${editId})`);
        }
    }
    let newNameTd = document.getElementById('name' + id);
    let newButton = document.getElementById('editButton' + id);
    editId = id;
    editName = newNameTd.innerHTML;
    newNameTd.innerHTML = `<input type="text" class="text-xs" name="name${id}" id="inputName${id}" value="${editName}">`;
    newButton.innerText = "Valider";
    //Change the action button
    newButton.setAttribute('onclick', `updateMembre(${id})`);
}

/**
 * Update the membre
 * @param {int} id id
 */
async function updateMembre(id) {
    const name = document.getElementById('inputName' + id);
    if(name) {
        //Get the data
        let formData = new FormData();
        formData.append("name", name.value);
        //Make the request
        let res = await fetch(`/membres/${id}/update`, {
            body: formData,
            method: "POST"
        });
        //Convert to json
        let data = await res.json();
        //If successfull
        if(data.success) {
            //Update the tr
            const nameTd = document.getElementById('name' + id);
            nameTd.innerHTML = name.value;
            editName = null;
            //Reset the button
            const button = document.getElementById('editButton' + id);
            button.innerText = "Modifier";
            button.setAttribute('onclick', `editMembre(${id})`);
        } else if(data.errors) {
            displayErrors(data.errors);
        }
    }
}

/**
 * Send a request and delete the membre with matching id
 * @param {int} id id
 */
function deleteMembre(id) {
    //Ajax request
    fetch(`/membres/${id}/delete`, {
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
 * @param {string} name membre's name
 */
function addTr(id, name) {
    //Create the tr
    const tr = document.createElement('tr');
    tr.setAttribute('id', `membre${id}`);
    //Append the id's td
    const num = document.createElement('td');
    num.appendChild(document.createTextNode(id));
    tr.appendChild(num);
    //Append the name's td
    const username = document.createElement('td');
    username.appendChild(document.createTextNode(name));
    username.setAttribute('id', `name${id}`);
    tr.appendChild(username);
    //Append the buttons td
    const actions = document.createElement('td');
    const div = document.createElement('div');
    //Create the update button
    const btnUpdate = document.createElement('button');
    btnUpdate.classList.add('btn-yellow');
    btnUpdate.classList.add('text-xs');
    btnUpdate.setAttribute('onclick', `editMembre(${id})`);
    btnUpdate.setAttribute('id', `editButton${id}`);
    btnUpdate.append(document.createTextNode('Modifier'));
    div.append(btnUpdate);
    //Create the delete button 
    const btnDelete = document.createElement('button');
    btnDelete.classList.add('btn-red');
    btnDelete.classList.add('text-xs');
    btnDelete.setAttribute('onclick', `deleteMembre(${id})`);
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
    const tr = document.getElementById(`membre${id}`);
    //Delete the tr
    tr.remove();
}