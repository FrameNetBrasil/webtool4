<table id="networkFrameGrid">
</table>
<script>
    $(function() {
        $("#networkFrameGrid").treegrid({
            fit: true,
            url: "/network/listForTree",
            queryParams: {{ Js::from(['_token' => $search->_token,'idFramalDomain' => $search->idFramalDomain,'frame' => $search->frame]) }},
            showHeader: false,
            rownumbers: false,
            idField: "id",
            treeField: "name",
            showFooter: false,
            border: false,
            columns: [[
                {
                    field: "name",
                    width: "100%",
                    formatter: (value, rowData) => {
                        if (rowData.type === "domain") {
                            return `<div class='color-domain'>${value[0]}</div>`;
                        }
                        if (rowData.type === "frame") {
                            return `<div><span class='color_${value[3]} relation'>${value[2]}</span>&nbsp;<span class='color_frame'>${value[0]}</span>&nbsp;<span class="text-gray-500">${value[4]}</span></div>`;
                        }
                        if (rowData.type === "relation") {
                            return `<div><span class='color_frame'>${rowData.frame}</span>&nbsp;<span class='color_${value[3]} relation'>${value[2]}</span>&nbsp;<span class='color_frame'>${value[0]}</span>&nbsp;<span class="text-gray-500">${value[4]}</span></div>`;
                        }
                        if (rowData.type === "node") {
                            return `<div class='color-domain'>${value}</div>`;
                        }
                    }
                }
            ]],
            onClickRow: (row) => {
                $("#networkFrameGrid").treegrid("toggle", row.id);
            }
        });
    });
</script>
<style>
    .tree-indent {
        width: 32px;
    }

    .relation, .domain {
        font-size: 13px;
    }
</style>
</div>
