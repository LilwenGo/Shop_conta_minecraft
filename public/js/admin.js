//Get the needed elements
const form = document.querySelector('.form');
const inputName = document.getElementById('name');
const inputPassword = document.getElementById('password');
const tbody = document.querySelector('tbody');

//Listen form's submit
form.addEventListener('submit', (e) => {
    //Stop the submit
    e.preventDefault();
    //Call the create function
    createAdmin();
    return;
})

/**
 * Send a request for admin backend insertion and display it
 */
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
        }
        displayErrors(jsonRes.errors);
    });
}

let editId = null;
let editName = null;
/**
 * Change the tr for edition
 * @param {*} id 
 */
function editAdmin(id) {
    if(editId != null && editName != null) {
        //Reset the unupdated tds if exists
        let nameTd = document.getElementById('name' + editId);
        let button = document.getElementById('editButton' + editId);
        if(nameTd && button) {
            nameTd.innerHTML = editName;
            button.innerText = "Modifier";
            button.setAttribute("onclick", `editAdmin(${editId})`);
        }
    }
    let newNameTd = document.getElementById('name' + id);
    let newButton = document.getElementById('editButton' + id);
    editId = id;
    editName = newNameTd.innerHTML;
    newNameTd.innerHTML = `<input type="text" class="text-xs" name="name${id}" id="inputName${id}" value="${editName}">`;
    newButton.innerText = "Valider";
    //Change the action button
    newButton.setAttribute('onclick', `updateAdmin(${id})`);
}

/**
 * Update the admin
 * @param {int} id id
 */
async function updateAdmin(id) {
    const name = document.getElementById('inputName' + id);
    if(name) {
        //Get the data
        let formData = new FormData();
        formData.append("name", name.value);
        //Make the request
        let res = await fetch(`/admins/${id}/update`, {
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
            button.setAttribute('onclick', `editAdmin(${id})`);
        }
        displayErrors(data.errors);
    }
}

/**
 * Send a request and delete the admin with matching id
 * @param {int} id id
 */
function deleteAdmin(id) {
    //Ajax request
    fetch(`/admins/${id}/delete`, {
        method: "GET"
    }).then(async (response) => {
        let jsonRes = await response.json();
        if(jsonRes.success) {
            removeTr(id);
        }
        displayErrors(jsonRes.errors);
    });
}

/**
 * Add a tr to the table
 * @param {string} id id
 * @param {string} name admin's name
 */
function addTr(id, name) {
    let tr = {
        type: 'tr',
        attributes: {
            id: `admin${id}`
        },
        childs: [
            {
                type: 'td',
                childs: [
                    {
                        type: 'text',
                        text: id
                    }
                ]
            },
            {
                type: 'td',
                attributes: {
                    id: `name${id}`
                },
                childs: [
                    {
                        type: 'text',
                        text: name
                    }
                ]
            },
            {
                type: 'td',
                childs: [
                    {
                        type: 'div',
                        childs: [
                            {
                                type: 'button',
                                classes: [
                                    'btn-yellow',
                                    'text-xs'
                                ],
                                attributes: {
                                    id: `editButton${id}`,
                                    onclick: `editAdmin(${id})`
                                },
                                childs: [
                                    {
                                        type: 'text',
                                        text: 'Modifier'
                                    }
                                ]
                            },
                            {
                                type: 'button',
                                classes: [
                                    'btn-red',
                                    'text-xs'
                                ],
                                attributes: {
                                    onclick: `deleteAdmin(${id})`
                                },
                                childs: [
                                    {
                                        type: 'img',
                                        classes: [
                                            'icon-img'
                                        ],
                                        attributes: {
                                            src: '/img/trash.png',
                                            alt: 'Icone poubelle'
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ]
    };
    addChildElement(tbody, tr);
}

/**
 * Remove a tr from the table
 * @param {int} id id
 */
function removeTr(id) {
    //Get the tr
    const tr = document.getElementById(`admin${id}`);
    //Delete the tr
    tr.remove();
}