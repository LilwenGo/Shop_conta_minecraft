//Get the needed elements
const form = document.querySelector('.form');
const inputItem = document.getElementById('item');
const inputMembre = document.getElementById('membre');
const inputQuantity = document.getElementById('quantity');
const inputRefunded = document.getElementById('refunded');
const tbody = document.querySelector('tbody');

//Listen form's submit
form.addEventListener('submit', (e) => {
    //Stop the submit
    e.preventDefault();
    //Call the create function
    createSold();
    return;
})

/**
 * Send a request for sold backend insertion and display it
 */
function createSold() {
    //Get the data from the form
    let data = new FormData();
    data.append("item", inputItem.value);
    data.append("membre", inputMembre.value);
    data.append("quantity", inputQuantity.value);
    data.append("refunded", inputRefunded.value);
    //Ajax request 
    fetch("/solds/create", {
        body: data,
        method: "POST"
    }).then(async (response) => {
        let jsonRes = await response.json();
        if(jsonRes.success && jsonRes.data) {
            addTr(jsonRes.data.id_item, jsonRes.data.id_membre, jsonRes.data.item, jsonRes.data.membre, inputQuantity.value, inputRefunded.value);
            inputQuantity.value = '';
            inputRefunded.value = '';
        } else if(jsonRes.errors) {
            displayErrors(jsonRes.errors);
        }
    });
}

let editId = null;
let editQuantity = null;
let editRefunded = null;
/**
 * Change the tr for edition
 */
function editSold(id_item, id_membre) {
    let id = `${id_item},${id_membre}`;
    if(editId != null && editQuantity != null) {
        //Reset the unupdated tds if exists
        let quantityTd = document.getElementById('quantity' + editId);
        let refundedTd = document.getElementById('refunded' + editId);
        let button = document.getElementById('editButton' + editId);
        if(quantityTd && refundedTd && button) {
            quantityTd.innerHTML = editQuantity;
            refundedTd.innerHTML = editRefunded;
            button.innerText = "Modifier";
            button.setAttribute("onclick", `editSold(${editId})`);
        }
    }
    let newQuantityTd = document.getElementById('quantity' + id);
    let newRefundedTd = document.getElementById('refunded' + id);
    let newButton = document.getElementById('editButton' + id);
    editId = `${id_item},${id_membre}`;
    editQuantity = newQuantityTd.innerHTML;
    editRefunded = newRefundedTd.innerHTML;
    newQuantityTd.innerHTML = `<input type="text" class="text-xs" name="quantity${id_item},${id_membre}" id="inputQuantity${id_item},${id_membre}" value="${editQuantity}">`;
    newRefundedTd.innerHTML = `<input type="text" class="text-xs" name="refunded${id_item},${id_membre}" id="inputRefunded${id_item},${id_membre}" value="${editRefunded}">`;
    newButton.innerText = "Valider";
    //Change the action button
    newButton.setAttribute('onclick', `updateSold(${id_item},${id_membre})`);
}

/**
 * Update the sold
 */
async function updateSold(id_item, id_membre) {
    let id = `${id_item},${id_membre}`;
    const quantity = document.getElementById('inputQuantity' + id);
    const refunded = document.getElementById('inputRefunded' + id);
    if(quantity) {
        //Get the data
        let formData = new FormData();
        formData.append("quantity", quantity.value);
        formData.append("refunded", refunded.value);
        //Make the request
        let res = await fetch(`/solds/${id}/update`, {
            body: formData,
            method: "POST"
        });
        //Convert to json
        let data = await res.json();
        //If successfull
        if(data.success) {
            //Update the tr
            const quantityTd = document.getElementById('quantity' + id);
            quantityTd.innerHTML = quantity.value;
            editQuantity = null;
            const refundedTd = document.getElementById('refunded' + id);
            refundedTd.innerHTML = refunded.value;
            editRefunded = null;
            //Reset the button
            const button = document.getElementById('editButton' + id);
            button.innerText = "Modifier";
            button.setAttribute('onclick', `editSold(${id_item},${id_membre})`);
        } else if(data.errors) {
            displayErrors(data.errors);
        }
    }
}

/**
 * Send a request and delete the sold with matching id
 * @param {int} id id
 */
function deleteSold(id_item, id_membre) {
    //Ajax request
    fetch(`/solds/${id_item},${id_membre}/delete`, {
        method: "GET"
    }).then(async (response) => {
        let jsonRes = await response.json();
        if(jsonRes.success) {
            removeTr(id_item, id_membre);
        } else if(jsonRes.errors) {
            displayErrors(jsonRes.errors);
        }
    });
}

/**
 * Add a tr to the table
 * @param {string} id id
 * @param {string} name sold's name
 */
function addTr(id_item, id_membre, item, membre, quantity, refunded) {
    let tr = {
        type: 'tr',
        attributes: {
            id: `sold${id_item},${id_membre}`
        },
        childs: [
            {
                type: 'td',
                childs: [
                    {
                        type: 'text',
                        text: item
                    }
                ]
            },
            {
                type: 'td',
                childs: [
                    {
                        type: 'text',
                        text: membre
                    }
                ]
            },
            {
                type: 'td',
                attributes: {
                    id: `quantity${id_item},${id_membre}`
                },
                childs: [
                    {
                        type: 'text',
                        text: quantity
                    }
                ]
            },
            {
                type: 'td',
                attributes: {
                    id: `refunded${id_item},${id_membre}`
                },
                childs: [
                    {
                        type: 'text',
                        text: refunded
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
                                    id: `editButton${id_item},${id_membre}`,
                                    onclick: `editSold(${id_item},${id_membre})`
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
                                    onclick: `deleteSold(${id_item},${id_membre})`
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
 */
function removeTr(id_item, id_membre) {
    //Get the tr
    const tr = document.getElementById(`sold${id_item},${id_membre}`);
    //Delete the tr
    tr.remove();
}