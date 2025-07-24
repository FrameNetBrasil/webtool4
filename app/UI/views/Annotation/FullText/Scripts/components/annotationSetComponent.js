function annotationSetComponent(idAnnotationSet, token) {
    return {
        idAnnotationSet: null,
        selectionRaw: null,
        token: '',

        init() {
            this.idAnnotationSet = idAnnotationSet;
            this.token = token;
        },

        get selection() {
            let type = '',id = '',start = 0,end = 0;
            if (this.selectionRaw) {
                let { anchorNode, anchorOffset, focusNode, focusOffset } = this.selectionRaw;
                var startNode = anchorNode?.parentNode || null;
                var endNode = focusNode?.parentNode || null;
                if ((startNode !== null) && (endNode !== null)) {
                    if (startNode.dataset.type === "ni") {
                        let range = new Range();
                        range.setStart(startNode, 0);
                        range.setEnd(startNode, 1);
                        document.getSelection().removeAllRanges();
                        document.getSelection().addRange(range);
                        type = "ni";
                        id = startNode.dataset.id;
                    }
                    if (startNode.dataset.type === "word") {
                        type = "word";
                        if (startNode.dataset.startchar) {
                            start = startNode.dataset.startchar;
                        }
                        if (endNode.dataset.endchar) {
                            end = endNode.dataset.endchar;
                        }
                    }
                }
            }
            return {
                type,
                id,
                start,
                end
            };
        },

        onLabelAnnotate(idEntity, layerType) {
            let values = {
                idAnnotationSet: this.idAnnotationSet,
                token: this.token,
                idEntity,
                layerType,
                selection: this.selection
            };
            htmx.ajax('POST', '/annotation/fullText/annotate', {target:'.annotationSet', swap:'innerHTML',values: values });
        },

        onLabelDelete(idEntity, layerType) {
            let values = {
                idAnnotationSet: this.idAnnotationSet,
                token: this.token,
                idEntity
            };
            console.log(values);
            htmx.ajax('DELETE', '/annotation/fullText/label', {target:'.annotationSet', swap:'innerHTML',values: values });
        },

        onChangeLabelTab(e) {
            console.log(e);
            $(".tabs .item")
                .tab('change tab', e.detail)
            ;
        }
    };
}
