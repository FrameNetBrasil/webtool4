document.addEventListener("alpine:init", () => {
    window.ftStore = Alpine.store("ftStore", {
        idAnnotationSet: 0,
        asData: [],
        selection: {
            type: "",
            id: "",
            start: 0,
            end: 0
        },
        init() {
        },
        config() {
        },
        setSelection(type,id,start,end) {
            this.selection = {
                type,
                id,
                start,
                end
            };
        },
        setASData(asData) {
            this.asData = asData;
            console.log(asData);
        },
        async updateASData() {
            this.selection = {type:"",id:"",start:0,end:0};
            await annotationFullText.api.loadASData(this.idAnnotationSet);
        }
    });

});
