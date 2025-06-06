@php
    use Orkester\Security\MAuth;
    use App\Data\MenuData;

    $actions = config('webtool.actions');
    $isLogged = MAuth::isLogged();
    if ($isLogged) {
        $user = MAuth::getLogin();
        $userLevel = session('userLevel');
    }
    $currentLanguage = session('currentLanguage');
    $languages = config('webtool.user')[3]['language'][3];
    $profile = config('webtool.user')[3]['profile'][3];
    $hrefLogin = (env('AUTH0_CLIENT_ID') == 'auth0') ? '/auth0Login' : '/';

@endphp

<div class="app-sidebar">
    <div class="ui secondary vertical menu">
        <a class="item">
            Reports
        </a>
        <a class="item">
            Grapher
        </a>
        <a class="item">
            Settings
        </a>
    </div>
    <!-- Optional Search -->
{{--    <div class="sidebar-search">--}}
{{--        <div class="ui input">--}}
{{--            <input type="text" placeholder="Search navigation..." id="nav-search">--}}
{{--        </div>--}}
{{--    </div>--}}


    <!-- Main Navigation Accordion -->
{{--    <div class="ui fluid accordion" id="sidebar-navigation">--}}

{{--    @foreach($actions as $id => $action)--}}
{{--            @php--}}
{{--                $menuData = MenuData::from([--}}
{{--                    'id' => $id . '_small',--}}
{{--                    'label' => $action[0],--}}
{{--                    'href' => $action[1],--}}
{{--                    'group' => $action[2],--}}
{{--                    'items' => $action[3]--}}
{{--                ]);--}}
{{--            @endphp--}}
{{--            @if (MAuth::checkAccess($menuData->group))--}}
{{--                <div class="title">--}}
{{--                    <i class="dropdown icon"></i>--}}
{{--                    {!! $menuData->label !!}--}}
{{--                    <i class="chevron down icon collapse-icon"></i>--}}
{{--                </div>--}}
{{--                <div class="content">--}}
{{--                    @foreach($menuData->items as $idItem => $item)--}}
{{--                        @php--}}
{{--                            $itemData = MenuData::from([--}}
{{--                                'id' => $idItem . '_small',--}}
{{--                                'label' => $item[0],--}}
{{--                                'href' => $item[1],--}}
{{--                                'group' => $item[2],--}}
{{--                                'items' => $item[3]--}}
{{--                            ]);--}}
{{--                        @endphp--}}
{{--                        <a href="{{$itemData->href}}" class="nav-item">--}}
{{--                        <a href="#" class="nav-item">--}}
{{--                            {{$itemData->label}}--}}
{{--                        </a>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        @endforeach--}}


{{--        <!-- Projects Section -->--}}
{{--        <div class="title">--}}
{{--            <i class="dropdown icon"></i>--}}
{{--            <i class="fas fa-project-diagram icon"></i>--}}
{{--            Projects--}}
{{--            <span class="badge">3</span>--}}
{{--        </div>--}}
{{--        <div class="content">--}}
{{--            <a href="#" class="nav-item active">--}}
{{--                <i class="fas fa-circle icon"></i>--}}
{{--                Website Redesign--}}
{{--                <span class="badge">12</span>--}}
{{--            </a>--}}
{{--            <a href="#" class="nav-item">--}}
{{--                <i class="fas fa-circle icon"></i>--}}
{{--                Mobile App--}}
{{--                <span class="badge">8</span>--}}
{{--            </a>--}}
{{--            <a href="#" class="nav-item">--}}
{{--                <i class="fas fa-circle icon"></i>--}}
{{--                API Development--}}
{{--                <span class="badge">5</span>--}}
{{--            </a>--}}
{{--        </div>--}}

{{--        <!-- Documents Section with Nested Categories -->--}}
{{--        <div class="title">--}}
{{--            <i class="dropdown icon"></i>--}}
{{--            <i class="fas fa-folder icon"></i>--}}
{{--            Documents--}}
{{--            <span class="badge">24</span>--}}
{{--        </div>--}}
{{--        <div class="content">--}}
{{--            <!-- Nested accordion for document types -->--}}
{{--            <div class="ui accordion">--}}
{{--                <div class="title">--}}
{{--                    <i class="dropdown icon"></i>--}}
{{--                    <i class="fas fa-file-text icon"></i>--}}
{{--                    Specifications--}}
{{--                    <span class="badge">8</span>--}}
{{--                </div>--}}
{{--                <div class="content">--}}
{{--                    <a href="#" class="nav-item">--}}
{{--                        <i class="fas fa-file icon"></i>--}}
{{--                        Technical Requirements--}}
{{--                    </a>--}}
{{--                    <a href="#" class="nav-item">--}}
{{--                        <i class="fas fa-file icon"></i>--}}
{{--                        User Stories--}}
{{--                    </a>--}}
{{--                    <a href="#" class="nav-item">--}}
{{--                        <i class="fas fa-file icon"></i>--}}
{{--                        API Documentation--}}
{{--                    </a>--}}
{{--                </div>--}}

{{--                <div class="title">--}}
{{--                    <i class="dropdown icon"></i>--}}
{{--                    <i class="fas fa-image icon"></i>--}}
{{--                    Design Assets--}}
{{--                    <span class="badge">16</span>--}}
{{--                </div>--}}
{{--                <div class="content">--}}
{{--                    <a href="#" class="nav-item">--}}
{{--                        <i class="fas fa-palette icon"></i>--}}
{{--                        Wireframes--}}
{{--                    </a>--}}
{{--                    <a href="#" class="nav-item">--}}
{{--                        <i class="fas fa-paint-brush icon"></i>--}}
{{--                        Mockups--}}
{{--                    </a>--}}
{{--                    <a href="#" class="nav-item">--}}
{{--                        <i class="fas fa-images icon"></i>--}}
{{--                        Assets Library--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <!-- Annotations Section -->--}}
{{--        <div class="title">--}}
{{--            <i class="dropdown icon"></i>--}}
{{--            <i class="fas fa-sticky-note icon"></i>--}}
{{--            Annotations--}}
{{--            <span class="badge">47</span>--}}
{{--        </div>--}}
{{--        <div class="content">--}}
{{--            <a href="#" class="nav-item">--}}
{{--                <i class="fas fa-star icon"></i>--}}
{{--                Favorites--}}
{{--                <span class="badge">5</span>--}}
{{--            </a>--}}
{{--            <a href="#" class="nav-item">--}}
{{--                <i class="fas fa-clock icon"></i>--}}
{{--                Recent--}}
{{--                <span class="badge">12</span>--}}
{{--            </a>--}}
{{--            <a href="#" class="nav-item">--}}
{{--                <i class="fas fa-tag icon"></i>--}}
{{--                Tagged Items--}}
{{--                <span class="badge">30</span>--}}
{{--            </a>--}}
{{--        </div>--}}

{{--        <!-- Settings Section -->--}}
{{--        <div class="title">--}}
{{--            <i class="dropdown icon"></i>--}}
{{--            <i class="fas fa-cog icon"></i>--}}
{{--            Settings--}}
{{--        </div>--}}
{{--        <div class="content">--}}
{{--            <a href="#" class="nav-item">--}}
{{--                <i class="fas fa-user icon"></i>--}}
{{--                Profile--}}
{{--            </a>--}}
{{--            <a href="#" class="nav-item">--}}
{{--                <i class="fas fa-bell icon"></i>--}}
{{--                Notifications--}}
{{--            </a>--}}
{{--            <a href="#" class="nav-item">--}}
{{--                <i class="fas fa-shield-alt icon"></i>--}}
{{--                Security--}}
{{--            </a>--}}
{{--        </div>--}}

{{--        <!-- Help Section -->--}}
{{--        <div class="title">--}}
{{--            <i class="dropdown icon"></i>--}}
{{--            <i class="fas fa-question-circle icon"></i>--}}
{{--            Help & Support--}}
{{--        </div>--}}
{{--        <div class="content">--}}
{{--            <a href="#" class="nav-item">--}}
{{--                <i class="fas fa-book icon"></i>--}}
{{--                Documentation--}}
{{--            </a>--}}
{{--            <a href="#" class="nav-item">--}}
{{--                <i class="fas fa-life-ring icon"></i>--}}
{{--                Support Center--}}
{{--            </a>--}}
{{--            <a href="#" class="nav-item">--}}
{{--                <i class="fas fa-comments icon"></i>--}}
{{--                Contact Us--}}
{{--            </a>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>


{{--<nav class="app-sidebar">--}}
{{--    @if($isLogged)--}}
{{--        <div class="nav-section collapsible" data-section="user">--}}
{{--            <div class="section-header">--}}
{{--                <div class="section-title-content">--}}
{{--                    <img src="avatar.jpg" alt="User" class="user-avatar"--}}
{{--                         style="width: 24px; height: 24px; border-radius: 50%; margin-right: 8px;">--}}
{{--                    <span>John Doe</span>--}}
{{--                    <span class="nav-badge danger">3</span>--}}
{{--                </div>--}}
{{--                <i class="dropdown icon collapse-icon"></i>--}}
{{--            </div>--}}
{{--            <div class="section-items">--}}
{{--                <a href="#" class="nav-item">--}}
{{--                    <i class="user icon nav-icon"></i>--}}
{{--                    <span class="nav-text">Profile Settings</span>--}}
{{--                </a>--}}
{{--                <a href="#" class="nav-item">--}}
{{--                    <i class="cog icon nav-icon"></i>--}}
{{--                    <span class="nav-text">Preferences</span>--}}
{{--                </a>--}}
{{--                <a href="#" class="nav-item">--}}
{{--                    <i class="bell icon nav-icon"></i>--}}
{{--                    <span class="nav-text">Notifications</span>--}}
{{--                    <span class="nav-badge danger">3</span>--}}
{{--                </a>--}}
{{--                <div class="nav-divider"></div>--}}
{{--                <a href="#" class="nav-item">--}}
{{--                    <i class="sign out icon nav-icon"></i>--}}
{{--                    <span class="nav-text">Logout</span>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--    @foreach($actions as $id => $action)--}}
{{--        @php--}}
{{--            $menuData = MenuData::from([--}}
{{--                'id' => $id . '_small',--}}
{{--                'label' => $action[0],--}}
{{--                'href' => $action[1],--}}
{{--                'group' => $action[2],--}}
{{--                'items' => $action[3]--}}
{{--            ]);--}}
{{--        @endphp--}}
{{--        @if (MAuth::checkAccess($menuData->group))--}}
{{--            <div class="nav-section collapsible">--}}
{{--                <div class="section-header">--}}
{{--                    <div class="section-title-content">--}}
{{--                        {!! $menuData->label !!}--}}
{{--                    </div>--}}
{{--                    <i class="chevron down icon collapse-icon"></i>--}}
{{--                </div>--}}
{{--                <div class="section-items">--}}
{{--                    @foreach($menuData->items as $idItem => $item)--}}
{{--                        @php--}}
{{--                            $itemData = MenuData::from([--}}
{{--                                'id' => $idItem . '_small',--}}
{{--                                'label' => $item[0],--}}
{{--                                'href' => $item[1],--}}
{{--                                'group' => $item[2],--}}
{{--                                'items' => $item[3]--}}
{{--                            ]);--}}
{{--                        @endphp--}}
{{--                        <a href="{{$itemData->href}}" class="nav-item">--}}
{{--                            <span class="nav-text">{{$itemData->label}}</span>--}}
{{--                        </a>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}
{{--    @endforeach--}}
{{--</nav>--}}
<script>
    $(document).ready(function() {
        // $('#sidebar-navigation')
        //     .accordion({
        //         exclusive: false
        //     })
        // ;

        // console.log('Initializing Accordion Sidebar...');
        //
        // // Initialize the sidebar with proper options
        // const sidebar = new AccordionSidebar('#sidebar-navigation', {
        //     exclusive: false,     // Allow multiple sections open
        //     collapsible: true,    // Sections can be closed
        //     duration: 300,        // Standard animation duration
        //     animateChildren: false // Keep disabled to prevent layout shifts
        // });
        //
        // // Wait a moment to ensure everything is ready
        // setTimeout(() => {
        //     if (!sidebar.isReady()) {
        //         console.error('Sidebar failed to initialize properly');
        //         return;
        //     }
        //
        //     console.log('Sidebar initialized successfully');
        //     console.log('Section count:', sidebar.getSectionCount());
        //
        //     // Start with all sections closed for a clean initial state
        //     sidebar.closeAll();
        //
        //     // Optionally open the first section (Projects)
        //     setTimeout(() => {
        //         sidebar.openSection(0);
        //     }, 100);
        //
        // }, 200);
        //
        // // Listen for navigation events
        // $('#sidebar-navigation').on('navigation:select', function(event, data) {
        //     console.log('Navigation selected:', {
        //         text: data.text,
        //         section: data.section,
        //         href: data.href
        //     });
        //
        //     // Here you would typically:
        //     // - Update the main content area
        //     // - Change the URL
        //     // - Load new data
        //     // - Update breadcrumbs, etc.
        //
        //     // Example: Update page title or breadcrumb
        //     if (data.text && data.section) {
        //         document.title = `${data.text} - ${data.section}`;
        //     }
        // });
        //
        // // Create a global API for easy access
        // window.sidebarAPI = {
        //     openSection: (index) => sidebar.openSection(index),
        //     closeSection: (index) => sidebar.closeSection(index),
        //     toggleSection: (index) => sidebar.toggleSection(index),
        //     closeAll: () => sidebar.closeAll(),
        //     openAll: () => sidebar.openAll(),
        //     refresh: () => sidebar.refresh(),
        //     getOpenSections: () => sidebar.getOpenSections(),
        //     getSectionCount: () => sidebar.getSectionCount()
        // };
        //
        // // Debug helpers (remove in production)
        // window.debugSidebar = {
        //     sidebar: sidebar,
        //     testOpen: () => {
        //         console.log('Testing section opening...');
        //         sidebar.openSection(0);
        //     },
        //     testClose: () => {
        //         console.log('Testing section closing...');
        //         sidebar.closeSection(0);
        //     },
        //     testToggle: () => {
        //         console.log('Testing section toggle...');
        //         sidebar.toggleSection(0);
        //     },
        //     testAllSections: () => {
        //         console.log('Testing all sections...');
        //         const count = sidebar.getSectionCount();
        //         for (let i = 0; i < count; i++) {
        //             setTimeout(() => {
        //                 console.log(`Toggling section ${i}`);
        //                 sidebar.toggleSection(i);
        //             }, i * 1000);
        //         }
        //     },
        //     clearState: () => {
        //         localStorage.removeItem('sidebar-accordion-state');
        //         console.log('Cleared saved state');
        //     }
        // };
        //
        // console.log('Sidebar API available as window.sidebarAPI');
        // console.log('Debug helpers available as window.debugSidebar');
    });
</script>
