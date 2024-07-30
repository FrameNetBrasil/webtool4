<table id="mainGridTable">
</table>
<script>
    $(function() {
        $("#mainGridTable").treegrid({
            fit: true,
            url: "/corpus/listForTree",
            method: "GET",
            queryParams: {{ Js::from($search) }},
            showHeader: true,
            rownumbers: false,
            idField: "id",
            treeField: "name",
            border: false,
            striped: true,
            columns: [[
                {
                    field: "name",
                    width: "90%",
                    formatter: (value, rowData) => {
                        if (rowData.type === "corpus") {
                            return `<div><div class='color-corpus'>${value[0]}</div></div>`;
                        }
                        if (rowData.type === "document") {
                            return `<div><div class='color-document'>${value}</div></div>`;
                        }
                        if (rowData.type === "sentence") {
                            return `<div><div class='color-document'>${value}</div></div>`;
                        }
                    }
                },
                {
                    field: "id",
                    width: "10%",
                    align:"right",
                    formatter: (value, rowData) => {
                        return '#' + value.substring(1);
                    }
                },
            ]],
            onClickRow: (row) => {
                let id = row.id.substring(1);
                if (row.type === "corpus") {
                    window.location.href = `/corpus/${id}`;
                }
                if (row.type === "document") {
                    window.location.href = `/document/${id}`;
                }
                if (row.type === "sentence") {
                    window.location.href = `/sentence/${id}`;
                }
            }
        });
    });
</script>
<style>
    .definition {
        display: inline-block;
        font-size: 12px;
    }

    .fe-name {
        display: inline-block;
        font-size: 12px;
    }

    .datagrid-body table tbody tr td div.datagrid-cell {
        height: 24px !important;
        padding-top: 4px;
    }
</style>
