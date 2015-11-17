(function(){

    //Array of elements that have 'children'
    var parents = document.getElementsByClassName('parent');

    //On each parent
    iterateWithCallback(parents, function(parent) {
        //Add the right arrow
        switchArrows(parent.firstElementChild);
        //Hide its children
        iterateWithCallback(parent.children, toggleChildVisibility);
        //Ready the listener
        parent.addEventListener('click', function(event) {
            iterateWithCallback(this.children, toggleChildVisibility);
            switchArrows(this.firstElementChild);
            event.stopPropagation();
        }, false);
    });


    /**
     * Calls the given function passing in each object
     *
     * @param {object[]} objects
     * @param {function} callback
     */
    function iterateWithCallback(objects, callback) {
        Array.prototype.filter.call(objects, function(object){
            callback(object);
        });
    }

    /**
     * Toggles the visibility of the given child, if it is not an arrow
     *
     * @param {object} child
     */
    function toggleChildVisibility(child) {
        if(child.className !== 'arrow') {
            if (child.style.display === 'none') {
                child.style.display = 'block';
            } else {
                child.style.display = 'none';
            }
        }
    }

    /**
     * Switches the given arrow from down to right
     *
     * @param {object} arrow
     */
    function switchArrows(arrow) {
        if(arrow.innerHTML.indexOf('▼') > -1) {
            arrow.innerHTML = '▶';
        } else {
            arrow.innerHTML = '▼';
        }
    }

}(document, window));