{{--
    INC Card - incorporated FE information

    Parameters:
    - $lu: LU object with senseDescription
    - $incorporatedFE: Incorporated FE object (optional)
--}}

<div class="section-header">
    <h1 class="ui header section-title" id="incfe">
        <a href="#incfe">Incorporated FE</a>
    </h1>
    <button class="ui button basic icon section-toggle"
            onclick="toggleSection('incfe-content')"
            aria-expanded="true">
        <i class="chevron up icon"></i>
    </button>
</div>
<div class="section-content" id="definition-content">
    <div class="ui card fluid data-card definition-card">
        <div class="content">
            <div class="incorporated-fe-section">
                <div class="incorporated-fe-element">
                    <x-element::fe name="{{$incorporatedFE->name}}"
                                  type="{{$incorporatedFE->coreType}}"
                                  idColor="{{$incorporatedFE->idColor}}">
                    </x-element::fe>
                    {{$incorporatedFE->description}}
                </div>
            </div>
        </div>
    </div>
</div>
