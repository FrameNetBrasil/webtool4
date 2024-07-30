let UDTree = {
    element: "UDTreeCanvas",
    UDTreeCurrent: null
};

window.annotation = {
    data: annotationData,
    $dg: null,
    setSelection(rowIndex, value) {
        // setSelection deve ser chamado apenas quando a célula está vazia (status = 0)
        console.log(rowIndex, value);
        if (value.status === 0) { // empty cell
            let row = annotation.getRow(rowIndex);
            // verifica se pode estender para a esquerda
            // procura uma célula com status = 1 à esquerda, sem ocorrência de status = 2
            let field = "";
            let found = false;
            let cell = {};
            let currentStartChar = value.startChar - 1;
            while ((currentStartChar >= 0) && (!found)) {
                field = "c" + currentStartChar;
                console.log(currentStartChar, row[field]);
                if (row[field].status === 0) {
                    --currentStartChar;
                }
                if (row[field].status === 1) {
                    found = true;
                    cell = row[field];
                }
                if (row[field].status === 2) {
                    break;
                }
                if (row[field].status === 3) {
                    break;
                }
            }
            if (found) { // atualiza o startChar
                currentStartChar = cell.startChar;
            } else {
                currentStartChar = value.startChar;
            }
            annotation.markSelection({
                rowIndex: rowIndex,
                startChar: currentStartChar,
                endChar: value.endChar,
                status: 1
            });
        }
    },

    markSelection: (selection) => {
        let row = annotation.getRow(selection.rowIndex);
        let field;
        for (var i = selection.startChar; i <= selection.endChar; i++) {
            field = "c" + i;
            row[field].status = selection.status;
            row[field].startChar = selection.startChar;
            row[field].endChar = selection.endChar;
        }
        annotation.$dg.datagrid("beginEdit", selection.rowIndex);
        annotation.$dg.datagrid("updateRow", {
            index: selection.rowIndex,
            row: row
        });
        annotation.$dg.datagrid("endEdit", selection.rowIndex);
    },
    getRow: (index) => {
        let rows = annotation.$dg.datagrid("getRows");
        return rows[index];
    },
    getRowByIdLayer: (idLayer) => {
        let rowIndex = annotation.$dg.datagrid("getRowIndex", idLayer);
        return { row: annotation.getRow(rowIndex), rowIndex: rowIndex };
    },
    editRow: (rowIndex, row) => {
        annotation.$dg.datagrid("beginEdit", rowIndex);
        annotation.$dg.datagrid("updateRow", {
            index: rowIndex,
            row: row
        });
        annotation.$dg.datagrid("endEdit", rowIndex);
    },
    onNIClick: (rowIndex, instantiationType, action, idLabel, idLabelType) => {
        console.log(rowIndex, instantiationType, action, idLabel, idLabelType);
        let row = annotation.getRow(rowIndex);
        let type = "ni";
        let idPopover, colIndex, data;
        if (action === "create") {
            idPopover = "menu_ni_" + row.idAnnotationSet;
            colIndex = instantiationType;
            data = {
                action,
                instantiationType
            };
        } else if (action === "delete") {
            idPopover = "menu_ni_delete";
            colIndex = instantiationType + "_delete";
            data = {
                action,
                idLabel,
                idLabelType
            };
        }
        popover.open(idPopover, type, rowIndex, colIndex, data);
    },
    onLabelClick: (idLabelType) => {
        console.log(idLabelType);
        console.log(popover);
        let row = annotation.getRow(popover.rowIndex);
        if (popover.type === "ni") {
            if (popover.data.action === "create") {
                let idInstantiationType = annotation.data.idInstantiationType[popover.colIndex];
                let label = annotation.data.labelTypes[idLabelType];
                annotation.data.nis[row.idLayer][idLabelType] = {
                    fe: label.label,
                    idEntityFE: idLabelType,
                    idInstantiationType: idInstantiationType,
                    label: popover.colIndex,
                    idColor: label.idColor
                };
                window.ky.put(`/annotation/corpus/ni`, {
                    json: {
                        _token: csrf,
                        idLayer: row.idLayer,
                        idLabelType: idLabelType,
                        idInstantiationType: idInstantiationType,
                        startChar: -1,
                        endChar: -1
                    }
                }).json().then((data) => {
                    console.log(data);
                    annotation.data.nis[row.idLayer][idLabelType].idLabel = data.idLabel;
                });
            } else if (popover.data.action === "delete") {
                console.log(annotation.data.nis[row.idLayer]);
                delete (annotation.data.nis[row.idLayer][popover.data.idLabelType]);
                console.log(annotation.data.nis[row.idLayer]);
                window.ky.delete(`/annotation/corpus/label`, {
                    json: {
                        _token: csrf,
                        idLabel: popover.data.idLabel
                    }
                }).json().then((data) => {
                    if (data.notify.type === "error") {
                        manager.messager("error", data.notify.message);
                    }
                });
            }
        } else {
            let newStatus = 2;
            if (idLabelType === 0) { // clear
                newStatus = 0;
            }
            let col = row["c" + popover.colIndex];
            let currentStatus = col.status;
            console.log(col);
            let startChar = col.startChar;
            let endChar = col.endChar;

            let field, i;
            for (i = startChar; i <= endChar; i++) {
                field = "c" + i;
                row[field].idLabelType = idLabelType;
                row[field].status = newStatus;
            }
            for (i = startChar; i <= endChar; i++) {
                if (idLabelType === 0) {
                    row[field].startChar = row[field].endChar = -1;
                }
            }

            let idLayerGF, idLayerPT;
            if (row.layerTypeEntry === "lty_fe") {
                console.log("fe ===============");
                let annotationSet = annotation.data.annotationSets[row.idAnnotationSet];
                idLayerGF = annotationSet.idLayerGF;
                idLayerPT = annotationSet.idLayerPT;
                let rowGF = annotation.getRowByIdLayer(idLayerGF);
                let rowPT = annotation.getRowByIdLayer(idLayerPT);
                console.log(rowGF, rowPT);
                for (i = startChar; i <= endChar; i++) {
                    field = "c" + i;
                    rowGF.row[field].idLabelType = 0;
                    rowGF.row[field].status = (idLabelType === 0) ? 0 : 1;
                    rowGF.row[field].startChar = startChar;
                    rowGF.row[field].endChar = endChar;
                }
                for (i = startChar; i <= endChar; i++) {
                    if (idLabelType === 0) {
                        rowGF.row[field].startChar = rowGF.row[field].endChar = -1;
                    }
                }
                annotation.editRow(rowGF.rowIndex, rowGF.row);
                for (i = col.startChar; i <= col.endChar; i++) {
                    field = "c" + i;
                    rowPT.row[field].idLabelType = 0;
                    rowPT.row[field].status = (idLabelType === 0) ? 0 : 1;
                    rowPT.row[field].startChar = startChar;
                    rowPT.row[field].endChar = endChar;
                }
                for (i = startChar; i <= endChar; i++) {
                    if (idLabelType === 0) {
                        rowPT.row[field].startChar = rowPT.row[field].endChar = -1;
                    }
                }
                annotation.editRow(rowPT.rowIndex, rowPT.row);
                console.log("===============");
            }

            if (idLabelType === 0) { // clear
                if (currentStatus === 2) {
                    window.ky.delete(`/annotation/corpus/label`, {
                        json: {
                            _token: csrf,
                            idLayer: row.idLayer,
                            startChar: col.startChar
                        }
                    }).json().then((data) => {
                        if (data.notify.type === "error") {
                            manager.messager("error", data.notify.message);
                        }
                    });
                    if (row.layerTypeEntry === "lty_fe") {
                        window.ky.delete(`/annotation/corpus/label`, {
                            json: {
                                _token: csrf,
                                idLayer: idLayerGF,
                                startChar: col.startChar
                            }
                        }).json().then((data) => {
                            if (data.notify.type === "error") {
                                manager.messager("error", data.notify.message);
                            }
                        });
                        window.ky.delete(`/annotation/corpus/label`, {
                            json: {
                                _token: csrf,
                                idLayer: idLayerPT,
                                startChar: col.startChar
                            }
                        }).json().then((data) => {
                            if (data.notify.type === "error") {
                                manager.messager("error", data.notify.message);
                            }
                        });

                    }
                }
            } else {
                window.ky.put(`/annotation/corpus/label`, {
                    json: {
                        _token: csrf,
                        idLayer: row.idLayer,
                        idLabelType: idLabelType,
                        idInstantiationType: annotation.data.idInstantiationType.Normal,
                        startChar: col.startChar,
                        endChar: col.endChar
                    }
                }).json().then((data) => {
                    if (data.notify.type === "error") {
                        manager.messager("error", data.notify.message);
                    }
                });
            }
        }
        annotation.editRow(popover.rowIndex, row);
        popover.close();
    },
    onClickCell: (rowIndex, field, value) => {
        console.log(rowIndex, field, value);
        if (field === "actions") {
            return;
        }
        if (field === "layer") {
            return;
        }
        let row = annotation.getRow(rowIndex);
        let column = annotation.$dg.datagrid("getColumnOption", field);
        if (value.status === 0) { // empty
            if (value.char === " ") {
                return;
            }
            if (row.layerTypeEntry === "lty_gf") {
                return;
            }
            if (row.layerTypeEntry === "lty_pt") {
                return;
            }
            // o primeiro span é a word
            let word = annotation.data.words[column.word];
            value.startChar = word.startChar;
            value.endChar = word.endChar;
            annotation.setSelection(rowIndex, value);
        } else if ((value.status === 1) | (value.status === 2)) { // waiting/filled
            // update the selection to the current click and open menu
            let type = "other";
            let idPopover = "menu_" + row.layerTypeEntry;
            if (row.layerTypeEntry === "lty_fe") {
                idPopover = idPopover + "_" + row.idAnnotationSet;
                type = "fe";
            }
            popover.open(idPopover, type, rowIndex, value.index);
        }
    },

    rowStyler: (index, row) => {
        if (!row.show) {
            return "display:none";
        }
        if (row.idLayerType === 0) {
            return { class: "layerTarget" };
        } else if ((row.layerTypeEntry === "lty_gf") || (row.layerTypeEntry === "lty_pt")) {
            return { class: "layerGFPT" };
        } else {
            return { class: "layerSelected" };
        }
    },
    cellFormatter: (value, row, rowIndex) => {
        let label, offset;
        if (row.layerTypeEntry === "lty_target") {
            return value.char;
        } else {
            if ((value.idLabelType !== 0) || (value.status === 2)) {
                label = annotation.data.labelTypes[value.idLabelType];
                offset = value.index - value.startChar;
                let id = `l_${rowIndex}_${value.index}`;
                return `<span id="${id}" class="easyui-tooltip" title="${label.label}">${label.label.charAt(offset)}</span>`;
            } else if (value.status === 1) {
                let id = `l_${rowIndex}_${value.index}`;
                return `<span id="${id}">${value.char}</span>`;
            } else {
                return "";
            }
        }
    },
    cellStyler: (value, row, index) => {
        if (value.idLabelType !== 0) {
            if (row.layerTypeEntry === "lty_fe") {
                if (annotation.data.annotationSets[row.idAnnotationSet].annotatedFEs[value.idLabelType] === undefined) {
                    annotation.data.annotationSets[row.idAnnotationSet].annotatedFEs[value.idLabelType] = value.idLabelType;
                    window.dispatchEvent(new CustomEvent("add-fe-annotated-" + row.idAnnotationSet, {
                        detail: value.idLabelType
                    }));
                }
            }
            let idColor = annotation.data.labelTypes[value.idLabelType].idColor;
            return { class: "color_" + idColor + " cellFilled" };
        } else {
            if (row.layerTypeEntry === "lty_target") {
                return { class: "layerTarget" };
            } else if (value.status === 3) {
                return { class: "cellTarget" };
            } else if (value.status === 1) {
                return { class: "cellSelected" };
            } else if (value.status === 0) {
                if ((row.layerTypeEntry === "lty_gf") || (row.layerTypeEntry === "lty_pt")) {
                    return { class: "cellGFPT" };
                }
                return { class: "cellEmpty" };
            }
            return null;
        }
    },
    cellLayerFormatter: (value, row, rowIndex) => {
        var text = value;
        if (row.layerTypeEntry === "lty_target") {
            text = annotation.data.annotationSets[row.idAnnotationSet].name;
        }
        if (row.layerTypeEntry === "lty_fe") {
            var textNis = "";
            var nis = annotation.data.nis[row.idLayer];
            if (nis) {
                var i = 0;
                jQuery.each(nis, function(idLabelType, ni) {
                    var classColor = "color_" + ni.idColor;
                    textNis = textNis + `<span id="l_${rowIndex}_${ni.label}_delete" onclick="annotation.onNIClick(${rowIndex},'${ni.label}', 'delete', ${ni.idLabel},${ni.idEntityFE})" class="easyui-tooltip niDiv ${classColor}" title="${ni.fe}">${ni.label}</span>`;
                    i++;
                });
            }
            var textBtn = "";
            if (!row.extraFELayer) {
                textBtn = `<span id="l_${rowIndex}_CNI" onclick="annotation.onNIClick(${rowIndex},'CNI', 'create')" class="niBtn">CNI</span>`;
                textBtn = textBtn + `<span  id="l_${rowIndex}_DNI" onclick="annotation.onNIClick(${rowIndex},'DNI', 'create')" class="niBtn">DNI</span>`;
                textBtn = textBtn + `<span  id="l_${rowIndex}_INI" onclick="annotation.onNIClick(${rowIndex},'INI', 'create')" class="niBtn">INI</span>`;
                textBtn = textBtn + `<span  id="l_${rowIndex}_INC" onclick="annotation.onNIClick(${rowIndex},'INC', 'create')" class="niBtn">INC</span>`;
            }
            text = `<div class="flex justify-content-between"><div>${text}</div><div>${textNis}</div><div>${textBtn}</div></div>`;
        }
        if (row.layerTypeEntry === "lty_udrelation") {
            text = text + "&nbsp<a class='fa fa-sitemap fa16px' onclick='annotation.UDTree(" + rowIndex + ")'>&nbsp</a>";
        }
        return text;
    },
    cellActionsFormatter: (value, row, index) => {
        var text = "";
        if (row.idLayerType === 0) {
            text = text + `<div class="flex justify-content-between">`;
            text = text + `<div class="material-icons wt-icon-delete wt-anno-icon" onClick="annotation.onDeleteAS(${row.idAnnotationSet})"></div>`;
            text = text + `<div class="material-icons wt-icon-comment wt-anno-icon" onClick="annotation.ASComments(${row.idAnnotationSet})"></div>`;
            text = text + `<div class="material-icons wt-icon-collapse wt-anno-icon" onClick="annotation.collapseAS(${row.idAnnotationSet})"></div>`;
            text = text + `</div>`;
        }
        if (row.layerTypeEntry === "lty_fe") {
            if (!row.extraFELayer) {
                //text = text + `<span class="material-icons-outlined wt-anno-icon wt-icon-fe-ni" onClick="annotation.onNullInstantiationFE(${index})"></span>`;
                text = text + `<div class="material-icons wt-icon-add wt-anno-icon" onClick="annotation.onAddFELayer(${row.idAnnotationSet})"></div>`;
            } else {
                text = text + `<div class="material-icons wt-icon-delete wt-anno-icon" onClick="annotation.onDeleteLastFELayer(${row.idLayer})"></div>`;
            }
        }
        return text;
    },
    initDatagrid: () => {
        let frozenColumns = [
            {
                field: "actions",
                title: "Actions",
                width: 72,
                formatter: annotation.cellActionsFormatter
            }
        ];
        for (let c of annotation.data.frozenColumns) {
            frozenColumns.push({
                field: c.field,
                title: c.title,
                formatter: annotation.cellLayerFormatter
            });
        }
        let columns = [];
        for (let c of annotation.data.columns) {
            if (c.type === "data") {
                columns.push({
                    field: c.field,
                    hidden: true
                });
            }
            if (c.type === "char") {
                annotation.endChar = c.index;
                columns.push({
                    field: c.field,
                    title: c.title,
                    char: c.char,
                    index: c.index,
                    order: c.order,
                    word: c.word,
                    hasLU: c.hasLU,
                    hidden: false,
                    formatter: annotation.cellFormatter,
                    // hstyler: annotation.headerStyler,
                    styler: annotation.cellStyler,
                    resizable: false,
                    //width: c.width
                    width: (c.char === " ") ? 16 : "auto"
                });
            }
        }

        $("#dataGridLayers").datagrid({
            cls: "dataGridLayers",
            url: `/annotation/corpus/sentence/${annotation.data.idSentence}/data`,
            fit: true,
            idField: "idLayer",
            method: "get",
            collapsible: false,
            fitColumns: false,
            autoRowHeight: false,
            nowrap: true,
            rowStyler: annotation.rowStyler,
            showHeader: false,
            onBeforeSelect: function() {
                return false;
            },
            //onSelect: annotation.onSelect,
            onClickCell: annotation.onClickCell,
            //toolbar: "#toolbarLayers",
            frozenColumns: [frozenColumns],
            columns: [columns],
            emptyMsg: "<span style='color:red'>There is no AnnotationSet for this sentence yet.</span>"
        });
    },
    onDeleteAS: (idAnnotationSet) => {
        let frame = annotation.data.annotationSets[idAnnotationSet].name;
        $.notify.confirm('',`Removing AnnotationSet [${frame}].`, function(r) {
            if (r) {
                window.ky.delete(`/annotation/corpus/annotationSet`, {
                    json: {
                        _token: csrf,
                        idAnnotationSet: idAnnotationSet,
                    }
                }).json().then((data) => {
                    if (data.notify.type === 'error') {
                        manager.messager('error', data.notify.message);
                    } else {
                        window.location = "/annotation/corpus/sentence/" + annotation.data.idSentence;
                    }
                });
            }
        });
    },
    onDeleteLastFELayer: (idLayer) => {
        window.ky.delete(`/annotation/corpus/annotationSet/lastFELayer`, {
            json: {
                _token: csrf,
                idLayer
            }
        }).json().then((data) => {
            if (data.notify.type === "error") {
                manager.messager("error", data.notify.message);
            } else {
                window.location = "/annotation/corpus/sentence/" + annotation.data.idSentence;
            }
        });
    },
    onAddFELayer: (idAnnotationSet) => {
        window.ky.put(`/annotation/corpus/annotationSet/feLayer`, {
            json: {
                _token: csrf,
                idAnnotationSet: idAnnotationSet,
            }
        }).json().then((data) => {
            if (data.notify.type === 'error') {
                manager.messager('error', data.notify.message);
            } else {
                window.location = "/annotation/corpus/sentence/" + annotation.data.idSentence;
            }
        });
    },


};
