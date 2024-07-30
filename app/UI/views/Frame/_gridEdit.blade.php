<table id="mainGridTable">
</table>
<script>
    $(function() {
        $("#mainGridTable").treegrid({
            fit: true,
            url: "/frame/listForTree",
            queryParams: {{ Js::from($search) }},
            showHeader: false,
            rownumbers: false,
            idField: "id",
            treeField: "name",
            showFooter: true,
            border: false,
            columns: [[
                {
                    field: "name",
                    width: "100%",
                    formatter: (value, rowData) => {
                        if (rowData.type === "domain") {
                            return `<div class="noDefinition"><div class='color-domain'>${value[0]}</div></div>`;
                        }
                        if (rowData.type === "frame") {
                            return `<div class="hasDefinition"><div class='color-frame'>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === "fe") {
                            return `<div class="hasDefinition"><div><span class='fe-name color_${rowData.idColor}'>${value[0]}</span></div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === "lu") {
                            return `<div class="hasDefinition"><div class='color-lu'>${value[0]}</div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === "feFrame") {
                            return `<div class="hasDefinition"><div><span class='fe-name color_${rowData.idColor}'>${value[0]}</span>&nbsp;<span class='fe-name'>[${value[2]}]</span></div><div class='definition'>${value[1]}</div></div>`;
                        }
                        if (rowData.type === "luFrame") {
                            return `<div class="hasDefinition"><div class='color-lu'>${value[0]}&nbsp;<span class='fe-name'>[${value[2]}]</span></div><div class='definition'>${value[1]}</div></div>`;
                        }
                    },
                }
            ]],
            onClickRow: (row) => {
                if (row.type === "frame") {
                    let idFrame = row.id.substring(1);
                    window.location.href = `/frame/${idFrame}`;
                }
                if (row.type === "feFrame") {
                    let idFE = row.id.substring(1);
                    window.location.href = `/fe/${idFE}`;
                }
                if (row.type === "luFrame") {
                    let idLU = row.id.substring(1);
                    window.location.href = `/lu/${idLU}`;
                }
            }
        });
    });
</script>
<style>
    .definition {
        display: block;
        font-size: 12px;
    }

    .fe-name {
        display: inline-block;
        font-size: 12px;
    }

    .datagrid-body table tbody tr td div.datagrid-cell.tree-title.hasDefinition {
        box-sizing: border-box;
        height: 40px !important;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
    }


</style>
