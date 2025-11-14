{{--
    Class Relations Card - Display entity relations for this class
    Parameters:
    - $relations: Relations array grouped by type
--}}
<div class="ui card fluid data-card relations-card">
    <div class="content">
        <div class="description">
            @if(count($relations) > 0)
                <div class="relations-container">
                    @foreach($relations as $nameEntry => $relations1)
                        @php([$entry, $name] = explode('|', $nameEntry))
                        @php($relId = str_replace(' ', '_', $name))
                        <div class="relation-type-section">
                            <div class="relation-type-header">
                                <h4 class="ui header small">{{ $name }}</h4>
                            </div>
                            <div class="relation-entities">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($relations1 as $idEntity => $relation)
                                        <div class="ui label basic">
                                            {{ $relation['name'] }}
                                            @if($relation['direction'] === 'outgoing')
                                                <i class="arrow right icon"></i>
                                            @else
                                                <i class="arrow left icon"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <div class="ui divider"></div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="ui message info">
                        <div class="header">No Relations</div>
                        <p>This class does not have any documented entity relations.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
