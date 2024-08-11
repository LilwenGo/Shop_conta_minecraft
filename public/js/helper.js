//Get the needed elements
const errorsSpan = document.getElementsByClassName('error');

/**
 * Convert an object into html elements
 * @param {Element} parent Element where to append
 * @param {object} data the object to be converted
 */
function addChildElement(parent, data) {
    let el = null;
    if(data.type !== 'text') {
        el = document.createElement(data.type);
        if(data.classes) {
            for(let className of data.classes) {
                el.classList.add(className);
            }
        }
        if(data.attributes) {
            for(let attribute in data.attributes) {
                el.setAttribute(attribute, data.attributes[attribute]);
            }
        }
        if(data.childs) {
            for(let child of data.childs) {
                addChildElement(el, child);
            }
        }
    } else {
        el = document.createTextNode(data.text);
    }
    parent.append(el);
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