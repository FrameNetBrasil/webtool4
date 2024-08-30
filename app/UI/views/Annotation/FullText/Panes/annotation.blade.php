<script type="text/javascript">
    // let annotationFullText = {
    //     selection: {
    //         type: "",
    //         id: "",
    //         start: 0,
    //         end: 0
    //     }
    // };


    @include("Annotation.FullText.Scripts.api")
    @include("Annotation.FullText.Scripts.store")


    $(function() {
        document.onselectionchange = () => {
            let selection = document.getSelection();
            let { anchorNode, anchorOffset, focusNode, focusOffset } = selection;
            var startNode = anchorNode?.parentNode || null;
            var endNode = focusNode?.parentNode || null;
            if ((startNode !== null) && (endNode !== null)) {
                if (startNode.dataset.type === "ni") {
                    let range = new Range();
                    range.setStart(startNode, 0);
                    range.setEnd(startNode, 1);
                    document.getSelection().removeAllRanges();
                    document.getSelection().addRange(range);
                    annotationFullText.selection.type = "ni";
                    annotationFullText.selection.id = startNode.dataset.id;
                }
                if (startNode.dataset.type === "word") {
                    annotationFullText.selection.type = "word";
                    if (startNode.dataset.startchar) {
                        annotationFullText.selection.start = startNode.dataset.startchar;
                    }
                    if (endNode.dataset.endchar) {
                        annotationFullText.selection.end = endNode.dataset.endchar;
                    }
                }
            }
        };
    });

</script>
