function annotationSetComponent(idAnnotationSet, token) {
    return {
        idAnnotationSet: null,
        token: '',
        selection: {
            type: "",
            id: "",
            start: 0,
            end: 0
        },


        init() {
            this.idAnnotationSet = idAnnotationSet;
            this.token = token;
        },

        onSelectionChange(e) {
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
                    this.selection.type = "ni";
                    this.selection.id = startNode.dataset.id;
                }
                if (startNode.dataset.type === "word") {
                    this.selection.type = "word";
                    if (startNode.dataset.startchar) {
                        this.selection.start = startNode.dataset.startchar;
                    }
                    if (endNode.dataset.endchar) {
                        this.selection.end = endNode.dataset.endchar;
                    }
                }
            }
        },

        onLabelAnnotate(idFrameElement) {
            let values = {
                idAnnotationSet: this.idAnnotationSet,
                token: this.token,
                idFrameElement,
                selection: this.selection
            };
            htmx.ajax('POST', '/annotation/fe/annotate', {target:'.annotation-workarea', swap:'innerHTML',values: values });
        },

        onLabelDelete(idFrameElement) {
            let values = {
                idAnnotationSet: this.idAnnotationSet,
                token: this.token,
                idFrameElement
            };
            htmx.ajax('DELETE', '/annotation/fe/frameElement', {target:'.annotation-workarea', swap:'innerHTML',values: values });
        }
    };
}
