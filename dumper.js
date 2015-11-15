(function(){


    var parents = document.getElementsByClassName('parent');

    //iterateWithCallback(allChildren, toggleChildVisibility);


    iterateWithCallback(parents, function(parent){
        iterateWithCallback(parent.children, toggleChildVisibility);
        parent.addEventListener('click', function(event) {
            var children = event.target.children;
            iterateWithCallback(children, toggleChildVisibility);
            switchArrows(event.target);
            event.stopPropagation();
        }, false);
    });



    function iterateWithCallback(objects, callback) {
        Array.prototype.filter.call(objects, function(object){
            callback(object);
        });
    }

    function toggleChildVisibility(child) {
        if(child.style.display === 'none') {
            child.style.display = 'block';
        } else {
            child.style.display = 'none';
        }
    }

    //docket Toggling arrow
    function switchArrows(parent) {
        if(parent.innerHTML.indexOf('▼') === 19) {
            parent.innerHTML = parent.innerHTML.replaceAt(19, '▶');
        } else {
            parent.innerHTML = parent.innerHTML.replaceAt(19, '▼');
        }
    }

    String.prototype.replaceAt = function(index, character) {
        return this.substr(0, index) + character + this.substr(index+character.length);
    }

}(document, window));