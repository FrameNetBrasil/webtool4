{{--
    LU Sidebar Navigation - Quick access to report sections

    Parameters:
    - $lu: LU object
--}}

<div class="tools-sidebar">
    <div class="tools-header">
        <h3 class="ui header">
            <i class="list icon"></i>
            Quick Navigation
        </h3>
    </div>
    <div class="tools-content">
        <div class="ui vertical menu fluid">
            <a class="item" href="#incfe" onclick="scrollToSection('incfe')">
                <i class="info circle icon"></i>
                Incorporated FE
            </a>
            <a class="item" href="#annotation-types" onclick="scrollToSection('annotation-types')">
                <i class="grid layout icon"></i>
                Annotation Types
            </a>
            <a class="item" href="#static-objects-so" onclick="scrollToSection('static-objects-so')">
                <i class="image icon"></i>
                Static Objects
            </a>
            <a class="item" href="#dynamic-objects-do" onclick="scrollToSection('dynamic-objects-do')">
                <i class="video icon"></i>
                Dynamic Objects
            </a>
            <div class="ui divider"></div>
            <div class="header">
                <i class="external alternate icon"></i>
                Report Types
            </div>
            <div class="item" onclick="switchToTab('textual')" style="cursor: pointer;">
                <i class="file text icon"></i>
                Textual Report
            </div>
            <div class="item" onclick="switchToTab('static')" style="cursor: pointer;">
                <i class="image icon"></i>
                Static Report
            </div>
            <div class="item" onclick="switchToTab('dynamic')" style="cursor: pointer;">
                <i class="video icon"></i>
                Dynamic Report
            </div>
            <div class="ui divider"></div>
            <div class="header">
                <i class="linkify icon"></i>
                Related
            </div>
            <a class="item" href="/report/frame/{{$lu->idFrame}}">
                <i class="sitemap icon"></i>
                Frame Report
            </a>
            @if(isset($isMaster) && $isMaster)
                <a class="item" href="/lu/{{$lu->idLU}}/edit">
                    <i class="edit icon"></i>
                    Edit LU
                </a>
            @endif
        </div>
    </div>
</div>

