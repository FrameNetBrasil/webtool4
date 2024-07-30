<x-form id="formNewGroup" title="New Group" :center="false"  hx-post="/group/new">
    <x-slot:fields>
        <section class="hxRow">
            <section class="hxCol hxSpan-6">
                <x-text-field
                    label="Name"
                    id="name"
                    value=""
                ></x-text-field>
            </section>
            <section class="hxCol hxSpan-6">
                <x-text-field
                    label="Description"
                    id="description"
                    value=""
                ></x-text-field>
            </section>
        </section>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
