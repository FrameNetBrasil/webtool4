<div class="ui container page-report">
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-main">
                <div class="page-title-section">
                    <div class="page-title">
                        <x-ui::element.semantictype name="{{$class->name}}"></x-ui::element.semantictype>
                    </div>
                    <div class="page-subtitle">{!! str_replace('ex>','code>',nl2br($class->description)) !!}</div>
                </div>
                @if($isHtmx)
                    <div class="page-actions">
                        <button
                            class="ui basic left labeled icon button"
                            @click="$.tab('change tab','browse')"
                        >
                            <i class="left arrow icon"></i>
                            Back to Classes
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="page-content">
        {{-- Class Overview Section --}}
        <div class="class-metadata-section mb-8">
            @include('Class.Report.partials.class-overview')
        </div>

        {{-- Stats Section --}}
        <div class="stats-section mb-8">
            @include('Class.Report.partials.stats-card')
        </div>

        {{-- Frame Elements Using This Class Section --}}
        <div class="frame-elements-section mb-8">
            @include('Class.Report.partials.frame-elements-cards')
        </div>

        {{-- Relations Section (if any exist) --}}
        @if(!empty($relations))
            <div class="relations-section mb-8">
                <div class="section-header">
                    <h2 class="ui header section-title" id="relations">
                        <a href="#relations">Relations</a>
                    </h2>
                    <button class="ui button basic icon section-toggle"
                            onclick="toggleSection('relations-content')"
                            aria-expanded="true">
                        <i class="chevron up icon"></i>
                    </button>
                </div>
                <div class="section-content" id="relations-content">
                    @include('Class.Report.partials.relations-card')
                </div>
            </div>
        @endif

    </div>
</div>
<script>
    function toggleSection(sectionId) {
        const section = document.getElementById(sectionId);
        let button;

        // Check if button is in the same parent container (new structure)
        const parent = section.parentElement;
        const headerButton = parent.querySelector(".section-header .section-toggle i");

        if (headerButton) {
            button = headerButton;
        } else {
            // Fallback for Frame Elements structure (button in previous sibling)
            button = section.previousElementSibling.querySelector(".section-toggle i");
        }

        if (section.style.display === "none") {
            section.style.display = "block";
            button.className = "chevron up icon";
        } else {
            section.style.display = "none";
            button.className = "chevron down icon";
        }
    }

    function toggleFeDetails(button) {
        const targetId = button.getAttribute("data-target");
        const target = document.querySelector(targetId);
        const icon = button.querySelector("i");

        if (target.style.display === "none") {
            target.style.display = "block";
            icon.className = "chevron up icon";
        } else {
            target.style.display = "none";
            icon.className = "chevron down icon";
        }
    }
</script>
