document.body.addEventListener("updateObjectAnnotationEvent", function(evt) {
    annotation.objects.updateObjectAnnotationEvent();
});

document.body.addEventListener("htmx:afterSwap", function(elt) {
    if (elt.target.id === "formObject"){
        if (document.getElementById("btnCreateObject")) {
            Alpine.store("doStore").uiEditingObject();
        }
    }
});

document.body.addEventListener("update-current-frame", function(e) {
    console.log(e);
    if (e.detail.frame !== undefined) {
        annotation.video.gotoFrame(e.detail.frame);
    }
});
