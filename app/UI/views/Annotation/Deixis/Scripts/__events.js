// document.body.addEventListener("updateObjectAnnotationEvent", function(evt) {
//     annotation.objects.updateObjectAnnotationEvent();
// });

document.body.addEventListener("htmx:afterSwap", function(elt) {

    console.log('afterSwap',elt.target.id);
    // if (elt.target.id === "formObject"){
    //     if (document.getElementById("btnCreateObject")) {
    //         Alpine.store("doStore").uiEditingObject();
    //     }
    // }
});

// document.addEventListener("update-current-frame", function(e) {
//     console.log("update-current-frame",e);
//     // if (e.detail.frame !== undefined) {
//     //     annotation.video.gotoFrame(e.detail.frame);
//     // }
// });
