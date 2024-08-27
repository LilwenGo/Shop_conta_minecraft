//Get the needed elements
const form = document.querySelector('.form');
const inputLibelle = document.getElementById('libelle');
const inputCategory = document.getElementById('category');
const inputPrice = document.getElementById('price');
const inputTotal_selled = document.getElementById('total_selled');
const tbody = document.querySelector('tbody');

//Listen form's submit
form.addEventListener('submit', (e) => {
    //Stop the submit
    e.preventDefault();
    //Call the create function
    createItem();
    return;
})

/**
 * Send a request for item backend insertion and display it
 */
function createItem() {
    //Get the data from the form
    let data = new FormData();
    data.append("libelle", inputLibelle.value);
    data.append("category", inputCategory.value);
    data.append("price", inputPrice.value);
    data.append("total_selled", inputTotal_selled.value);
    //Ajax request 
    fetch("/items/create", {
        body: data,
        method: "POST"
    }).then(async (response) => {
        let jsonRes = await response.json();
        if(jsonRes.success && jsonRes.data.id) {
            addTr(jsonRes.data.id, inputLibelle.value, jsonRes.data.category, inputPrice.value, inputTotal_selled.value);
            inputLibelle.value = '';
            inputPrice.value = '';
            inputTotal_selled.value = '';
        }
        displayErrors(jsonRes.errors);
    });
}

let editId = null;
let editLibelle = null;
let editPrice = null;
let editTotal_selled = null;
/**
 * Change the tr for edition
 * @param {*} id 
 */
function editItem(id) {
    if(editId != null && editLibelle != null) {
        //Reset the unupdated tds if exists
        let libelleTd = document.getElementById('libelle' + editId);
        let priceTd = document.getElementById('price' + editId);
        let total_selledTd = document.getElementById('total_selled' + editId);
        let button = document.getElementById('editButton' + editId);
        if(libelleTd && button) {
            libelleTd.innerHTML = editLibelle;
            priceTd.innerHTML = editPrice;
            total_selledTd.innerHTML = editTotal_selled;
            button.innerText = "Modifier";
            button.setAttribute("onclick", `editItem(${editId})`);
        }
    }
    let newLibelleTd = document.getElementById('libelle' + id);
    let newPriceTd = document.getElementById('price' + id);
    let newTotal_selledTd = document.getElementById('total_selled' + id);
    let newButton = document.getElementById('editButton' + id);
    editId = id;
    editLibelle = newLibelleTd.innerHTML;
    editPrice = newPriceTd.innerHTML;
    editTotal_selled = newTotal_selledTd.innerHTML;
    newLibelleTd.innerHTML = `<input type="text" class="text-xs" name="libelle${id}" id="inputLibelle${id}" value="${editLibelle}">`;
    newPriceTd.innerHTML = `<input type="text" class="text-xs" name="price${id}" id="inputPrice${id}" value="${editPrice}">`;
    newTotal_selledTd.innerHTML = `<input type="text" class="text-xs" name="total_selled${id}" id="inputTotal_selled${id}" value="${editTotal_selled}">`;
    newButton.innerText = "Valider";
    //Change the action button
    newButton.setAttribute('onclick', `updateItem(${id})`);
}

/**
 * Update the item
 * @param {int} id id
 */
async function updateItem(id) {
    const libelle = document.getElementById('inputLibelle' + id);
    const price = document.getElementById('inputPrice' + id);
    const total_selled = document.getElementById('inputTotal_selled' + id);
    if(libelle) {
        //Get the data
        let formData = new FormData();
        formData.append("libelle", libelle.value);
        formData.append("price", price.value);
        formData.append("total_selled", total_selled.value);
        //Make the request
        let res = await fetch(`/items/${id}/update`, {
            body: formData,
            method: "POST"
        });
        //Convert to json
        let data = await res.json();
        //If successfull
        if(data.success) {
            //Update the tr
            const libelleTd = document.getElementById('libelle' + id);
            const priceTd = document.getElementById('price' + id);
            const total_selledTd = document.getElementById('total_selled' + id);
            libelleTd.innerHTML = libelle.value;
            priceTd.innerHTML = price.value;
            total_selledTd.innerHTML = total_selled.value;
            editLibelle = null;
            editPrice = null;
            editTotal_selled = null;
            //Reset the button
            const button = document.getElementById('editButton' + id);
            button.innerText = "Modifier";
            button.setAttribute('onclick', `editItem(${id})`);
        }
        displayErrors(data.errors);
    }
}

/**
 * Send a request and delete the item with matching id
 * @param {int} id id
 */
function deleteItem(id) {
    //Ajax request
    fetch(`/items/${id}/delete`, {
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
 * @param {string} libelle item's libelle
 */
function addTr(id, libelle, category, price, total_selled) {
    let tr = {
        type: 'tr',
        attributes: {
            id: `item${id}`
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
                    id: `libelle${id}`
                },
                childs: [
                    {
                        type: 'text',
                        text: libelle
                    }
                ]
            },
            {
                type: 'td',
                childs: [
                    {
                        type: 'text',
                        text: category
                    }
                ]
            },
            {
                type: 'td',
                attributes: {
                    id: `price${id}`
                },
                childs: [
                    {
                        type: 'text',
                        text: price
                    }
                ]
            },
            {
                type: 'td',
                attributes: {
                    id: `total_selled${id}`
                },
                childs: [
                    {
                        type: 'text',
                        text: total_selled
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
                                type: 'a',
                                classes: [
                                    'btn-purple',
                                    'text-xs'
                                ],
                                attributes: {
                                    href: `/items/${id}`
                                },
                                childs: [
                                    {
                                        type: 'text',
                                        text: 'Voir'
                                    }
                                ]
                            },
                            {
                                type: 'button',
                                classes: [
                                    'btn-yellow',
                                    'text-xs'
                                ],
                                attributes: {
                                    id: `editButton${id}`,
                                    onclick: `editItem(${id})`
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
                                    onclick: `deleteItem(${id})`
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
    const tr = document.getElementById(`item${id}`);
    //Delete the tr
    tr.remove();
}