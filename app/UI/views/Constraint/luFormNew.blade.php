<x-layout.content>
    <x-form
        id="luConstraintFormNew"
        center="true"
    >
        <x-slot:fields>
            <x-hidden-field
                id="idLU"
                :value="$idLU"
            ></x-hidden-field>
            <div class="grid">
                <div class="col-6">
                    <h3 class="ui violet dividing header">Metonym-LU</h3>
                    <x-combobox.lu
                        id="idLUMetonymConstraint"
                        label="Related LU"
                        class="w-25rem"
                    ></x-combobox.lu>
                </div>
                <div class="col-6">
                    <h3 class="ui violet dividing header">Equivalent-LU</h3>
                    <x-combobox.lu
                        id="idLUEquivalenceConstraint"
                        label="Equivalent LU"
                        class="w-25rem"
                    ></x-combobox.lu>
                </div>
            </div>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit
                label="Add Constraint"
                hx-post="/constraint/lu/{{$idLU}}"
            ></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
