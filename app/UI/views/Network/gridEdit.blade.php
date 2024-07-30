<table id="mainGridTable">
</table>
<script>
    $(function () {
        $('#mainGridTable').treegrid({
            fit: true,
            url: "/network/listForTree",
            queryParams: {{ Js::from(['_token' => $search->_token,'idFramalDomain' => $search->idFramalDomain,'frame' => $search->frame]) }},
            showHeader: false,
            rownumbers: false,
            idField: 'id',
            treeField: 'name',
            showFooter:true,
            border: false,
            columns: [[
                {
                    field: 'name',
                    width: '100%',
                    formatter: (value, rowData) => {
                        if (rowData.type === 'domain') {
                            return `<div class='color-domain'>${value[0]}</div>`;
                        }
                        if (rowData.type === 'frame') {
                            return `<div><span class='color_${value[3]} relation'>${value[2]}</span>&nbsp;<span class='color-frame'>${value[0]}</span>&nbsp;<span class="domain">${value[4]}</span></div><div class='definition'>${value[1]}</div>`;
                        }
                        if (rowData.type === 'node') {
                            return `<div class='color-domain'>${value}</div>`;
                        }
                    }
                },
            ]],
            onClickRow: (row) => {
                if (row.type === 'frame') {
                    let idFrame = row.id;
                    window.location.href = `/frame/${idFrame}`;
                }
            },
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
        height: 40px !important;
        padding-top: var(--wt-mini-unit);
    }

    .tree-indent {
        width:32px;
    }

    .relation, .domain {
        font-size: 13px;
    }


</style>
