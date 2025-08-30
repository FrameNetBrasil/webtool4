<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
                :sections="[['/','Home'],['/report','Reports'],['','LU']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content">
                <div class="ui container">
                    <div class="page-header">
                        @include('LU.Report.partials.lu-header')
                    </div>
                    <div class="page-content">
                        <div class="content-container">
                            {{-- LU Metadata Section --}}
                            <div class="lu-metadata-section">
                                @include('LU.Report.partials.lu-metadata')
                            </div>

                            {{-- INC Section --}}
                            @if(isset($incorporatedFE))
                                <div class="definition-section mb-8">
                                    @include('LU.Report.partials.inc-card')
                                </div>
                            @endif

                            {{-- Annotation Types Section --}}
                            <div class="annotation-types-section mb-8">
                                @include('LU.Report.partials.annotation-types-nav')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        {{--        <aside class="app-tools">--}}
        {{--            @include('LU.Report.partials.lu-sidebar-nav')--}}
        {{--        </aside>--}}
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>

<script>
    // $(function() {
    //
    //     // Initialize Fomantic UI tabs
    //     $(".ui.tabs.menu .item").tab({
    //         context: ".annotation-types-section",
    //         onFirstLoad: function(tabPath) {
    //             // Load content when tab is first accessed
    //             loadTabContent(tabPath);
    //         },
    //         onLoad: function(tabPath) {
    //             // Load content when tab is switched to
    //             if (!hasTabContent(tabPath)) {
    //                 loadTabContent(tabPath);
    //             }
    //         }
    //     });
    //
    //     // Load textual content by default
    //     loadTabContent("textual");
    // });

    function toggleSection(sectionId) {
        const section = document.getElementById(sectionId);
        let button;

        // Check if button is in the same parent container (new structure)
        const parent = section.parentElement;
        const headerButton = parent.querySelector(".section-header .section-toggle i");

        if (headerButton) {
            button = headerButton;
        } else {
            // Fallback for other structures
            button = document.querySelector(`[onclick*="${sectionId}"] i`);
        }

        if (section && button) {
            if (section.style.display === "none") {
                section.style.display = "block";
                button.className = "chevron up icon";
            } else {
                section.style.display = "none";
                button.className = "chevron down icon";
            }
        }
    }

    function scrollToSection(sectionId) {
        const element = document.getElementById(sectionId);
        if (element) {
            element.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    }

    // function switchToTab(tabType) {
    //     // Use Fomantic UI tab method to switch tabs
    //     $(".ui.tabs.menu .item[data-tab=\"" + tabType + "\"]").tab("change tab", tabType);
    //
    //     // Scroll to the annotation types section
    //     scrollToSection("annotation-types");
    // }
    //
    // function hasTabContent(tabType) {
    //     const contentDiv = document.getElementById(tabType + "-content");
    //     return contentDiv && contentDiv.children.length > 0;
    // }
    //
    // function loadTabContent(tabType) {
    //     const targetDiv = document.getElementById(tabType + "-content");
    //     const loadingDiv = document.querySelector("[data-tab=\"" + tabType + "\"] .tab-loading-indicator");
    //
    //     if (!targetDiv) return;
    //
    //     // Show loading indicator
    //     if (loadingDiv) {
    //         loadingDiv.style.display = "block";
    //     }
    //
    //     // Get LU ID from the URL or a data attribute
    //     const luId = document.querySelector("[data-lu-id]")?.dataset.luId ||
    //         window.location.pathname.match(/\/report\/lu\/(\d+)/)?.[1];
    //
    //     if (luId) {
    //         // Use HTMX to load content
    //         htmx.ajax("GET", `/report/lu/${luId}/${tabType}`, {
    //             target: "#" + tabType + "-content",
    //             swap: "innerHTML"
    //         }).then(() => {
    //             // Hide loading indicator
    //             if (loadingDiv) {
    //                 loadingDiv.style.display = "none";
    //             }
    //         }).catch(() => {
    //             // Hide loading indicator on error
    //             if (loadingDiv) {
    //                 loadingDiv.style.display = "none";
    //             }
    //             // Show error message
    //             targetDiv.innerHTML = "<div class=\"ui error message\">Failed to load " + tabType + " content. Please try again.</div>";
    //         });
    //     }
    // }
</script>
