<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['','New Microframe']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="ui container">
                <div class="page-content">
                    <form class="ui form">
                        <div class="ui card form-card w-full p-1">
                            <div class="content">
                                <div class="header">
                                    Create new Microframe
                                </div>
                                <div class="description">

                                </div>
                            </div>
                            <div class="content">
                                <div class="field">
                                    <x-text-field
                                        id="nameEn"
                                        label="English Name"
                                        value="">

                                    </x-text-field>
                                </div>
                                <div class="field">
                                    <x-ui::tree
                                        :title="$title ?? ''"
                                        url="/semanticType/browse/search"
                                        :data="$data"
                                    ></x-ui::tree>
                                </div>
                            </div>
                            <div class="extra content">
                                <button
                                    type="submit"
                                    class="ui primary button"
                                    hx-post="/microframe"
                                >
                                    Add Microframe
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</x-layout::index>
