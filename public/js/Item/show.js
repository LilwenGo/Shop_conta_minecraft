//Get the needed element
const select = document.getElementById('category');
const input = document.getElementById('total_selled');

/**
 * Update the item's category
 */
async function updateCategory(id) {
    //Get the data
    let formData = new FormData();
    formData.append("category", select.value);
    //Make the request
    let res = await fetch(`/items/${id}/updateCategory`, {
        body: formData,
        method: "POST"
    });
    //Convert to json
    let data = await res.json();
    //If successfull
    if(data.success) {
        alert('La catégorie a bien été modifiée !');
    }
    displayErrors(data.errors);
    
}

/**
 * Update the item's total_selled
 */
async function updateTotal_selled(id) {
    //Get the data
    let formData = new FormData();
    formData.append("total_selled", input.value);
    //Make the request
    let res = await fetch(`/items/${id}/updateTS`, {
        body: formData,
        method: "POST"
    });
    //Convert to json
    let data = await res.json();
    //If successfull
    if(data.success) {
        alert('Le total vendu a bien été modifié !');
    }
    displayErrors(data.errors);
    
}