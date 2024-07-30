<x-layout.main>
    <x-slot:title>
        Webtool
    </x-slot:title>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:main>
        <div class="ui tree accordion">
            <div
                class="title"
                style="display: block !important;"
                hx-trigger="click"
                hx-get="/annotation/fe/grid/140/documents"
                hx-target="#documents_140"
                hx-swap="innerHTML"
            >
                <i class="dropdown icon"></i>
                Level 1
            </div>
            <div class="content">
                <div
                    id="documents_140"
                    class="accordion"
                >
                </div>
            </div>
            <div class="title">
                <x-icon.frame></x-icon.frame>
                Level 2
            </div>
            <div class="content">
                <div class="accordion transition hidden">
                    <div class="title">
                        <i class="dropdown icon"></i>
                        Level 2A
                    </div>
                    <div class="title">
                        <i class="dropdown icon"></i>
                        Level 2B
                    </div>
                    <div class="content">
                        Level 2B Contents
                    </div>
                    <div class="title">
                        <i class="dropdown icon"></i>
                        Level 2C
                    </div>
                    <div class="content">
                        Level 2C Contents
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(function() {
                $(".ui.accordion")
                    .accordion({
                        // onOpening: function() {
                        //     console.log(this);
                        //     //$(this).html("<span>teste</span>")
                        // }
                    });
            });
        </script>
    </x-slot:main>
</x-layout.main>

