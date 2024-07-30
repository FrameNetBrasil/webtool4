<table id="semanticTypeGrid">
</table>
<script>
    $(function () {
        $('#semanticTypeGrid').treegrid({
            fit: true,
            url: "/semanticType/listForTree",
            queryParams: {{ Js::from(['_token' => $search->_token,'semanticType' => $search->semanticType,'domain' => $search->domain]) }},
            showHeader: false,
            rownumbers: false,
            idField: 'id',
            treeField: 'name',
            showFooter:false,
            border: false,
            columns: [[
                {
                    field: 'name',
                    width: '100%',
                    formatter: (value, rowData) => {
                        if (rowData.type === 'domain') {
                            return `<div class='color-domain'>${value}</div>`;
                        }
                        if (rowData.type === 'semanticType') {
                            return `<div class='color-semantictype'>${value[0]}</div>`;
                        }
                    }
                },
            ]],
            onClickRow: (row) => {
                if (row.type === 'semanticType') {
                    let idSemanticType = row.idSemanticType;
                    window.location.href = `/semanticType/${idSemanticType}`;
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

    /*.datagrid-body table tbody tr td div.datagrid-cell {*/
    /*    height: 40px !important;*/
    /*    padding-top: var(--wt-mini-unit);*/
    /*}*/

    .tree-indent {
        width:32px;
    }

    .relation, .domain {
        font-size: 13px;
    }


</style>
