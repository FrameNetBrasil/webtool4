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
@include('components.head')
@include('components.head-small')
@include('components.confirm')


<div id="content">
    <main role="main" class="mainFull">
        {{$slot}}
        <wt-go-top id="myButton" label="Top" offset="64"></wt-go-top>
    </main>
</div>
<footer id="foot">
    {!! config('webtool.footer') !!}
</footer>


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
