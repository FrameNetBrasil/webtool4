{{--
    Frame Elements Using This Class - Organized by Frame
    Parameters:
    - $frameElements: Array of frame elements grouped by frame name
    - $class: The class object being reported
--}}
<div class="frame-elements-section">
    <h2 class="ui header section-title">Frame Elements Using This Class</h2>

    @if(empty($frameElements))
        <div class="ui message info">
            <div class="header">No Frame Elements</div>
            <p>This Class is not currently used as a semantic type by any Frame Elements.</p>
        </div>
    @else
        <div class="fe-sections">
            @foreach($frameElements as $frameName => $frameData)
                <div class="fe-section frame-section">
                    <div class="ui card fluid data-card section-card">
                        <div class="content">
                            <div class="data-card-header">
                                <div class="data-card-title">
                                    <h3 class="ui header" id="frame-{{ $frameData['idFrame'] }}">
                                        <a href="/report/frame/{{ $frameData['idFrame'] }}" target="_blank">
                                            <x-ui::element.frame name="{{ $frameName }}"></x-ui::element.frame>
                                        </a>
                                        <span class="count">({{ count($frameData['elements']) }} {{ count($frameData['elements']) == 1 ? 'element' : 'elements' }})</span>
                                    </h3>
                                </div>
                                <button class="ui icon basic button section-toggle"
                                        onclick="toggleSection('frame-{{ $frameData['idFrame'] }}-fes')"
                                        aria-expanded="true">
                                    <i class="chevron up icon"></i>
                                </button>
                            </div>
                            <div class="section-content" id="frame-{{ $frameData['idFrame'] }}-fes">
                                <div class="fe-cards-grid">
                                    @foreach($frameData['elements'] as $feObj)
                                        @include('Class.Report.partials.fe-card', [
                                            'feObj' => $feObj,
                                            'frameName' => $frameName,
                                            'idFrame' => $frameData['idFrame']
                                        ])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
