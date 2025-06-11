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
                {{--                <button class="view-btn" data-view="list">--}}
                {{--                    <i class="list icon"></i>--}}
                {{--                    List--}}
                {{--                </button>--}}
            </div>
            {{--            <button class="ui mini button">--}}
            {{--                <i class="download icon"></i>--}}
            {{--                Export--}}
            {{--            </button>--}}
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
                    {{--                        <div class="result-meta">--}}
                    {{--                            <div class="meta-item">--}}
                    {{--                                <i class="quote left icon"></i>--}}
                    {{--                                <span>12 words</span>--}}
                    {{--                            </div>--}}
                    {{--                            <div class="meta-item">--}}
                    {{--                                <i class="check icon"></i>--}}
                    {{--                                <span>Complete</span>--}}
                    {{--                            </div>--}}
                    {{--                            <div class="meta-item">--}}
                    {{--                                <i class="user icon"></i>--}}
                    {{--                                <span>Dr. Smith</span>--}}
                    {{--                            </div>--}}
                    {{--                            <div class="status-badge completed">Completed</div>--}}
                    {{--                        </div>--}}
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

    {{--    <!-- Compact List View -->--}}
    {{--    <div class="compact-view">--}}
    {{--        <div class="compact-results">--}}
    {{--            <div class="compact-item" data-id="sent1">--}}
    {{--                <div class="compact-type sentence">SENT</div>--}}
    {{--                <div class="compact-info">--}}
    {{--                    <div class="compact-title">Sentence 3: Neural Network Connections</div>--}}
    {{--                    <div class="compact-content">The learning process involves adjusting connection weights based on--}}
    {{--                        input patterns...--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="compact-meta">--}}
    {{--                    <span>14 words</span>--}}
    {{--                    <span class="status-badge pending">Pending</span>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
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

    // Selection functionality
    // document.addEventListener("click", (e) => {
    //     const card = e.target.closest(".result-card, .results-table tbody tr, .compact-item");
    //     if (card) {
    //         // Remove previous selections
    //         document.querySelectorAll(".result-card, .results-table tbody tr, .compact-item")
    //             .forEach(item => item.classList.remove("selected"));
    //
    //         // Select current item
    //         card.classList.add("selected");
    //
    //         console.log(`Selected: ${card.dataset.id}`);
    //     }
    // });

    // Initialize dropdowns
    // $(".ui.dropdown").dropdown();

    // Double-click to navigate
    // document.addEventListener("dblclick", (e) => {
    //     const item = e.target.closest(".result-card, .results-table tbody tr, .compact-item");
    //     if (item) {
    //         const id = item.dataset.id;
    //         console.log(`Navigating to: ${id}`);
    //         // Implement navigation logic
    //         alert(`Opening ${id} for annotation...`);
    //     }
    // });

    // Demo: Add some responsiveness
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
                // case "3":
                //     e.preventDefault();
                //     document.querySelector(".view-btn[data-view=\"compact\"]").click();
                //     break;
            }
        }
    });

    // Bulk selection demo
    // let selectedItems = new Set();
    //
    document.addEventListener("click", (e) => {
        // if (e.ctrlKey || e.metaKey) {
            const item = e.target.closest(".result-card, .results-table tbody tr, .compact-item");
            if (item) {
                e.preventDefault();
                console.log(item);
                window.location = `/report/frame/${item.dataset.id}`;

                // if (selectedItems.has(item.dataset.id)) {
                //     selectedItems.delete(item.dataset.id);
                //     item.classList.remove("selected");
                // } else {
                //     selectedItems.add(item.dataset.id);
                //     item.classList.add("selected");
                // }
                //
                // console.log(`Selected items: ${Array.from(selectedItems).join(", ")}`);
            }
        // }
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

            // Restore selections
            // state.selectedItems.forEach(id => {
            //     const item = document.querySelector(`[data-id="${id}"]`);
            //     if (item) {
            //         selectedItems.add(id);
            //         item.classList.add("selected");
            //     }
            // });
        }
    }

    // Save state on changes
    document.addEventListener("click", saveSearchState);

    // Load state on page load
    loadSearchState();
</script>
