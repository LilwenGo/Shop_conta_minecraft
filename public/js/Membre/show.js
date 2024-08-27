//Get the needed elements
const form = document.querySelector('.form');
const inputCategory = document.getElementById('category');
const inputMembre = document.getElementById('membre');
const inputRole = document.getElementById('role');
const tbody = document.querySelectorAll('tbody')[1];

//Listen form's submit
form.addEventListener('submit', (e) => {
    //Stop the submit
    e.preventDefault();
    //Call the create function
    createCategory();
    return;
})

/**
 * Send a request for membre backend insertion and display it
 */
function createCategory() {
    //Get the data from the form
    let data = new FormData();
    data.append("role", inputRole.value);
    //Ajax request 
    fetch(`/categories/${inputCategory.value}/addMembre/${inputMembre.value}`, {
        body: data,
        method: "POST"
    }).then(async (response) => {
        let jsonRes = await response.json();
        if(jsonRes.success && jsonRes.data) {
            addTr(inputCategory.value, inputMembre.value, jsonRes.data.libelle, inputRole.value);
            inputRole.value = '';
        } else if(jsonRes.errors) {
            displayErrors(jsonRes.errors);
        }
    });
}

let editId = null;
let editRole = null;
/**
 * Change the tr for edition
 * @param {*} id 
 */
function editCategory(id_category, id_membre) {
    let id = `${id_category},${id_membre}`;
    if(editId != null && editRole != null) {
        //Reset the unupdated tds if exists
        let roleTd = document.getElementById('role' + editId);
        let button = document.getElementById('editButton' + editId);
        if(roleTd && button) {
            roleTd.innerHTML = editRole;
            button.innerText = "Modifier";
            button.setAttribute("onclick", `editCategory(${editId})`);
        }
    }
    let newRoleTd = document.getElementById('role' + id);
    let newButton = document.getElementById('editButton' + id);
    editId = id;
    editRole = newRoleTd.innerHTML;
    newRoleTd.innerHTML = `<input type="text" class="text-xs" name="role${id}" id="inputRole${id}" value="${editRole}">`;
    newButton.innerText = "Valider";
    //Change the action button
    newButton.setAttribute('onclick', `updateCategory(${id})`);
}

/**
 * Update the membre
 * @param {int} id id
 */
async function updateCategory(id_category, id_membre) {
    let id = `${id_category},${id_membre}`;
    const role = document.getElementById('inputRole' + id);
    if(role) {
        //Get the data
        let formData = new FormData();
        formData.append("role", role.value);
        //Make the request
        let res = await fetch(`/categories/${id_category}/setMembre/${id_membre}`, {
            body: formData,
            method: "POST"
        });
        //Convert to json
        let data = await res.json();
        //If successfull
        if(data.success) {
            //Update the tr
            const roleTd = document.getElementById('role' + id);
            roleTd.innerHTML = role.value;
            editRole = null;
            //Reset the button
            const button = document.getElementById('editButton' + id);
            button.innerText = "Modifier";
            button.setAttribute('onclick', `editCategory(${id})`);
        }
        displayErrors(data.errors);
    }
}

/**
 * Send a request and delete the membre with matching id
 * @param {int} id id
 */
function deleteCategory(id_category, id_membre) {
    let id = `${id_category},${id_membre}`;
    //Ajax request
    fetch(`/categories/${id_category}/deleteMembre/${id_membre}`, {
        method: "GET"
    }).then(async (response) => {
        let jsonRes = await response.json();
        if(jsonRes.success) {
            removeTr(id_category);
        }
        displayErrors(jsonRes.errors);
    });
}

/**
 * Add a tr to the table
 * @param {string} id id
 * @param {string} role membre's role
 */
function addTr(id_category, id_membre, libelle, role) {
    let id = `${id_category},${id_membre}`;
    let tr = {
        type: 'tr',
        attributes: {
            id: `category${id_category}`
        },
        childs: [
            {
                type: 'td',
                childs: [
                    {
                        type: 'text',
                        text: id_category
                    }
                ]
            },
            {
                type: 'td',
                childs: [
                    {
                        type: 'text',
                        text: libelle
                    }
                ]
            },
            {
                type: 'td',
                attributes: {
                    id: `role${id}`
                },
                childs: [
                    {
                        type: 'text',
                        text: role
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
                                    onclick: `editCategory(${id})`
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
                                    onclick: `deleteCategory(${id})`
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
function removeTr(id_category) {
    //Get the tr
    const tr = document.getElementById(`category${id_category}`);
    //Delete the tr
    tr.remove();
}