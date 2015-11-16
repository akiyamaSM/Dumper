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
        if(parent.innerHTML.indexOf('▼') > -1) {
            parent.innerHTML = parent.innerHTML.replace('▼', '▶');
        } else {
            parent.innerHTML = parent.innerHTML.replace('▶', '▼');
        }
    }

}(document, window));