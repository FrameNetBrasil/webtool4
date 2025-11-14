{{--
    Reusable FE Card Component for Class Report
    Parameters:
    - $feObj: Frame Element object
    - $frameName: Name of the frame this FE belongs to
    - $idFrame: ID of the frame
--}}
<div class="ui card fluid data-card fe-card"
     data-entity-id="{{ $feObj->idFrameElement }}">
    <div class="content">
        <div class="data-card-header">
            <div class="data-card-title">
                <div class="header">
                    <x-ui::element.fe
                        name="{{ $feObj->name }}"
                        type="{{ $feObj->coreType }}"
                        :idColor="$feObj->idColor" />
                    <span class="core-type-badge ui label tiny basic">
                        {{ str_replace('cty_', '', $feObj->coreType) }}
                    </span>
                </div>
            </div>
            @if($feObj->description)
                <button class="ui button basic icon expand-toggle"
                        data-target="#fe-{{ $feObj->idFrameElement }}-details"
                        onclick="toggleFeDetails(this)">
                    <i class="chevron down icon"></i>
                </button>
            @endif
        </div>
    </div>

    @if($feObj->description)
        <div class="extra content" id="fe-{{ $feObj->idFrameElement }}-details" style="display: none;">
            <div class="description">
                {!! nl2br($feObj->description) !!}
            </div>
        </div>
    @endif
</div>
