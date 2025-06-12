<!-- Results Container -->
<div class="results-container view-cards">
    <div class="results-header">
        <div class="results-info">
            <div class="results-count" id="resultsCount">{!! count($frames) !!} results</div>
            <div class="search-query-display" id="queryDisplay"></div>
        </div>
        <div class="results-actions">
            <div class="view-toggle">
                <button class="view-btn active" data-view="cards">
                    <i class="th large icon"></i>
                    Cards
                </button>
                <button class="view-btn" data-view="table">
                    <i class="table icon"></i>
                    Table
                </button>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    @if(count($frames) == 0)
        <div class="empty-state" id="emptyState">
            <i class="search icon empty-icon"></i>
            <h3 class="empty-title">Ready to search</h3>
            <p class="empty-description">
                Enter your search term above to find frames.
            </p>
        </div>
    @endif

    <!-- Loading State -->
    <div class="loading-state" id="loadingState" style="display: none;">
        <div class="loading-spinner"></div>
        <h3 class="loading-title">Searching...</h3>
        <p class="loading-description">

        </p>
    </div>
    @if(count($frames) > 0)
        <!-- Card View -->
        <div class="card-view">
            <div class="search-results-grid">
                @foreach($frames as $frame)
                    <div class="result-card" data-id="{{$frame->idFrame}}">
                        <div class="card-header">
                            {{--                            <div class="result-type sentence">Sentence</div>--}}
                            <div class="result-info">
                                <div class="result-title">
                                    <x-ui::element.frame name="{{$frame->name}}"></x-ui::element.frame>
                                </div>
                                {{--                                <div class="result-path">Medical Research → Neuroscience → Brain Studies → Doc12</div>--}}
                            </div>
                        </div>
                        <div class="result-content">
                            {{$frame->description}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Table View -->
        <div class="table-view">
            <div class="results-table-container">
                <table class="ui table results-table">
                    <thead>
                    <tr>
                        <th class="four wide">Name</th>
                        <th class="twelve wide">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($frames as $frame)
                        <tr data-id="{{$frame->idFrame}}">
                            <td>
                                <div class="table-title">
                                    <x-ui::element.frame name="{{$frame->name}}"></x-ui::element.frame>
                                </div>
                            </td>
                            <td>
                                <div class="table-content">
                                    {{$frame->description}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
<script>
    // View toggle functionality
    document.querySelectorAll(".view-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            // Update active button
            document.querySelectorAll(".view-btn").forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
            // Update container class
            const container = document.querySelector(".results-container");
            const view = btn.dataset.view;
            console.log(view);
            container.className = `results-container view-${view}`;
        });
    });
    function updateViewForScreen() {
        const isMobile = window.innerWidth <= 768;
        const viewToggle = document.querySelector(".view-toggle");

        if (isMobile) {
            // On mobile, prefer table view
            const compactBtn = document.querySelector(".view-btn[data-view=\"table\"]");
            if (compactBtn && !compactBtn.classList.contains("active")) {
                compactBtn.click();
            }
        }
    }
    // Check on load and resize
    window.addEventListener("load", updateViewForScreen);
    window.addEventListener("resize", updateViewForScreen);
    // Keyboard shortcuts
    document.addEventListener("keydown", (e) => {
        if (e.ctrlKey || e.metaKey) {
            switch (e.key) {
                case "1":
                    e.preventDefault();
                    document.querySelector(".view-btn[data-view=\"cards\"]").click();
                    break;
                case "2":
                    e.preventDefault();
                    document.querySelector(".view-btn[data-view=\"table\"]").click();
                    break;
            }
        }
    });
    document.addEventListener("click", (e) => {
        const item = e.target.closest(".result-card, .results-table tbody tr, .compact-item");
        if (item) {
            e.preventDefault();
            console.log(item);
            window.location = `/report/frame/${item.dataset.id}`;
        }
    });

    // Auto-save search state
    function saveSearchState() {
        const state = {
            view: document.querySelector(".view-btn.active").dataset.view,
            // selectedItems: Array.from(selectedItems),
            timestamp: Date.now()
        };
        localStorage.setItem("search-state", JSON.stringify(state));
    }

    function loadSearchState() {
        const saved = localStorage.getItem("search-state");
        if (saved) {
            const state = JSON.parse(saved);
            // Restore view
            const viewBtn = document.querySelector(`.view-btn[data-view="${state.view}"]`);
            if (viewBtn) {
                viewBtn.click();
            }
        }
    }

    // Save state on changes
    document.addEventListener("click", saveSearchState);

    // Load state on page load
    loadSearchState();
</script>
