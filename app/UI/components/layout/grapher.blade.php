<!DOCTYPE html>
<html id="fnbr-webtool" class="" lang="en">
<head>
    <meta name="Generator" content="Laravel 11.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>{!! config('webtool.pageTitle') !!}</title>
    <meta name="description" content="Framenet Brasil Webtool 3.8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    {{--    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Filled"--}}
    {{--          rel="stylesheet"--}}
    {{--          type="text/css">--}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Mono:wght@100..900&display=swap" rel="stylesheet">

    <script type="text/javascript" src="/scripts/htmx/htmx.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <!--
    <script type="text/javascript" src="/scripts/jquery-easyui-1.10.17/jquery.min.js"></script>
-->
    <script type="text/javascript" src="/scripts/maestro/manager.js"></script>

    <script type="text/javascript" src="/scripts/pdf/jspdf.debug.js"></script>
    <script type="text/javascript" src="/scripts/pdf/html2canvas.min.js"></script>
    <script type="text/javascript" src="/scripts/pdf/html2pdf.min.js"></script>
    <script defer src="/scripts/alpinejs/cdn.min.js"></script>

    <script type="text/javascript" src="/scripts/jquery-easyui-1.10.17/jquery.easyui.min.js"></script>
    <!--
    <script type="text/javascript" src="/scripts/maestro/_notify.js"></script>
    -->

    <link rel="stylesheet" type="text/css" href="/scripts/jointjs/dist/joint.css" />
    <script type="text/javascript" src="/scripts/video-js-8.11.5/video.min.js"></script>
    <link href="/scripts/video-js-8.11.5/video-js.css" rel="stylesheet" />

    <!--
    <script src="/scripts/helix-ui/webcomponents-loader.js"></script>
    -->


    <!--
    <link rel="stylesheet" type="text/css" href="/scripts/semantic-ui/semantic.min.css">
    -->
    <script src="/scripts/fomantic-ui/semantic.min.js"></script>

    @vite(['resources/js/app.js'])
</head>
<body
    class="hxVertical"
    hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
>
@include('Grapher.controls')

<div class="pusher">

@include('components.head')
@include('components.head-small')
@include('components.confirm')

<div id="content">
    <main role="main" class="mainFull">
        <header class="flex">
            <div class="col-8">
                {{$header}}
            </div>
        </header>
        <div id="graphPane"
             class="flex flex-column w-full h-full p-0 wt-layout-grapher">
            <div class="flex-none">
                <div class="options">
                    {{$menu}}
                </div>
            </div>
            <div
                id="graph"
            >
            </div>
            <div id="paper"></div>
        </div>
        <wt-go-top id="myButton" label="Top" offset="64"></wt-go-top>
    </main>
</div>
<footer id="foot">
    {!! config('webtool.footer') !!}
</footer>
</div>

<script>
    $(function () {
        $('.ui.flyout').flyout();
        window.Grapher = joint.mvc.View.extend({
            options: {
                nodes: [],
                links: [],
                cells: [],
                padding: 50,
                el: document.getElementById("layout-controls"),
                paper: null,
                panAndZoom: null,
                buildGraph: function () {
                    return [];
                },
                cellDblClick: function (cellView) {
                },
                linkEnter: function (linkView) {
                },
                elementEnter: function (elementView) {
                }
            },
            init: function () {
                let that = this;
                let options = this.options;

                this.paper = new joint.dia.Paper({
                    el: document.getElementById("paper"),
                    width: "calc(100% - 16px)",
                    height: "calc(100% - 16px)",
                    sorting: joint.dia.Paper.sorting.APPROX
                });

                this.paper.on("blank:pointerdown", (evt, x, y) => {
                    this.panAndZoom.enablePan();
                });

                this.paper.on("cell:pointerup blank:pointerup", (cellView, event) => {
                    this.panAndZoom.disablePan();
                });

                this.paper.on("cell:pointerdblclick", options.cellDblClick);

                this.paper.on("link:mouseenter", options.linkEnter);

                this.paper.on("link:mouseleave", function (linkView) {
                    linkView.removeTools();
                });

                this.paper.on("element:mouseenter", options.elementEnter);

                this.paper.on("element:mouseleave", function (elementView) {
                    elementView.removeTools();
                });

                $("#rankdir").combobox({onChange: () => that.onChange()});
                $("#ranker").combobox({onChange: () => that.onChange()});
                $("#align").combobox({onChange: () => that.onChange()});
                $("#vertices").on("change", () => this.onChange());
                $("#ranksep").on("change", () => this.onChange());
                $("#nodesep").on("change", () => this.onChange());
                $("#edgesep").on("change", () => this.onChange());
                $("#connector").combobox({
                    onChange: () => {
                        let links = this.paper.model.getLinks();
                        let connector = $("#connector").combobox("getValue");
                        for (var link of links) {
                            link.connector(connector);
                            this.paper.findViewByModel(link.id).render();
                        }
                    }
                });
                this.cells = options.buildGraph();
                console.log(this.cells);
            },
            onChange: function () {
                this.layout();
                this.trigger("layout");
            },
            layout: function () {
                let paper = this.paper;
                let graph = paper.model;
                let cells = this.cells;
                paper.freeze();
                var layoutOptions = this.getLayoutOptions();
                joint.layout.DirectedGraph.layout(cells, layoutOptions);
                if (graph.getCells().length === 0) {
                    // The graph could be empty at the beginning to avoid cells rendering
                    // and their subsequent update when elements are translated
                    graph.resetCells(cells);
                }
                // let x = paper.fitToContent({
                //     padding: 0,
                //     allowNewOrigin: 'any',
                //     useModelGeometry: true
                // });
                // console.log(x);
                paper.unfreeze();
                let h1 = $("#paper").innerHeight();
                let w1 = $("#paper").innerWidth();
                let b = paper.getContentBBox();
                let h2 = b.height;
                let w2 = b.width;
                if (w2 > w1) {
                    paper.scale((w1 / w2));
                } else if (h2 > h1) {
                    paper.scale((h1 / h2));
                }

                this.panAndZoom = window.svgPanZoom(this.paper.svg, {
                    zoomEnabled: true,
                    controlIconsEnabled: true,
                    dblClickZoomEnabled: false,
                    fit: false,
                    center: false
                });

                this.panAndZoom.enableControlIcons();
                this.panAndZoom.disablePan();
            },
            getLayoutOptions: function () {
                return {
                    dagre: dagre,
                    graphlib: dagre.graphlib,
                    setVertices: $("#vertices").is(":checked") ? true : function (link) {
                        link.set("vertices", []);
                    },
                    setLinkVertices: $("#vertices").is(":checked"),
                    setLabels: true,
                    ranker: $("#ranker").combobox("getValue"),
                    rankDir: $("#rankdir").combobox("getValue"),
                    align: $("#align").combobox("getValue"),
                    rankSep: parseInt($("#ranksep").val(), 10),
                    edgeSep: parseInt($("#edgesep").val(), 10),
                    nodeSep: parseInt($("#nodesep").val(), 10)
                };
            }
        });


    });
</script>

<!-- App Scripts Go Here -->
<script src="/scripts/lodash/lodash.js"></script>
<script src="/scripts/backbone/backbone.js"></script>
<script src="/scripts/jointjs/dist/joint.js"></script>
<script src="/scripts/dagre/dist/dagre.js"></script>
<script src="/scripts/utils/md5.min.js"></script>

<!--
<script type="module">
    import HelixUI from "/scripts/helix-ui/helix-ui.module.js";
    HelixUI.initialize();
</script>
-->

<script>
    // document.body.addEventListener("notify", function(evt) {
    //     console.log(evt.detail.type, evt.detail.message);
    //     $.toast({
    //         class: evt.detail.type,
    //         message: evt.detail.message,
    //         className: {
    //             content: 'content  wt-notify-' + evt.detail.type,
    //         },
    //     })
    //     ;
    // });
</script>
</body>
</html>
