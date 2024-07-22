//Get the needed elements
const form = document.querySelector('.form');
const inputName = document.getElementById('name');
const inputPassword = document.getElementById('password');
const errorsSpan = document.getElementsByClassName('error');
const tbody = document.querySelector('tbody');

//Listen form's submit
form.addEventListener('submit', (e) => {
    //Stop the submit
    e.preventDefault();
    //Call the create function
    createAdmin();
    return;
})

function createAdmin() {
    //Get the data from the form
    let data = new FormData();
    data.append("name", inputName.value);
    data.append("password", inputPassword.value);
    //Ajax request 
    fetch("/admins/create", {
        body: data,
        method: "POST"
    }).then(async (response) => {
        let jsonRes = await response.json();
        if(jsonRes.success && jsonRes.data.id) {
            addTr(jsonRes.data.id, inputName.value);
            inputName.value = '';
            inputPassword.value = '';
        } else if(jsonRes.errors) {
            for(let span of errorsSpan) {
                span.innerText = '';
            }
            for(let error in jsonRes.errors) {
                displayError(error, jsonRes.errors[error]);
            }
        }
    });
}

function editAdmin(id) {

}

function updateAdmin(id) {

}

function deleteAdmin(id) {

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
 * @param {string} name admin's name
 */
function addTr(id, name) {
    //Create the tr
    const tr = document.createElement('tr');
    tr.setAttribute('id', `admin${id}`);
    //Append the id's td
    const num = document.createElement('td');
    num.appendChild(document.createTextNode(id));
    tr.appendChild(num);
    //Append the name's td
    const username = document.createElement('td');
    username.appendChild(document.createTextNode(name));
    tr.appendChild(username);
    //Append the buttons td
    const actions = document.createElement('td');
    const div = document.createElement('div');
    //Create the update button
    const btnUpdate = document.createElement('button');
    btnUpdate.classList.add('btn-yellow');
    btnUpdate.classList.add('text-xs');
    btnUpdate.setAttribute('onclick', `editAdmin(${id})`);
    btnUpdate.append(document.createTextNode('Modifier'));
    div.append(btnUpdate);
    //Create the delete button 
    const btnDelete = document.createElement('button');
    btnDelete.classList.add('btn-red');
    btnDelete.classList.add('text-xs');
    btnDelete.setAttribute('onclick', `deleteAdmin(${id})`);
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