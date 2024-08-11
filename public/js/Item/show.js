//Get the needed element
const select = document.getElementById('category');

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
    } else if(data.errors) {
        displayErrors(data.errors);
    }
    
}