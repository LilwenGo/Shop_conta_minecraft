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