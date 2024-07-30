<div
    id="luConstraintGrid"
    class="grid"
    hx-trigger="reload-gridConstraintLU from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/lu/{{$idLU}}/constraints/grid"
>
    @foreach($constraints as $constraint)
        <div class="col-3">
            <div class="ui card w-full">
                <div class="content">
                    <span class="right floated">
                        <x-delete
                            title="delete LU Constraint"
                            onclick="manager.confirmDelete(`Removing Constraint '{{$constraint->constraintName}}'.`, '/constraint/lu/{{$constraint->idConstraintInstance}}')"
                        ></x-delete>
                    </span>
                    <div
                        class="header"
                    >
                        <x-element.constraint
                            name="{{$constraint->constraintName}}"
                        ></x-element.constraint>
                    </div>
                    <div class="description">
                        {{$constraint->idConstrainedByName}}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>