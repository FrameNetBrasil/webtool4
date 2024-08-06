<div class="ui right flyout" id="graph-drawer">
    <i class="close icon"></i>
    <div class="ui header">
        <div class="content">
            Grapher Options
        </div>
    </div>
    <div class="content">
        <div class="flex flex-column">
            <label for="ranker">Ranker:</label>
            <div id="ranker_dropdown" class="ui tiny selection dropdown" style="overflow:initial">
                <input type="hidden" id="ranker" name="ranker" value="network-simplex">
                <i class="dropdown icon"></i>
                <div class="default text">network-simplex</div>
                <div class="menu">
                    <div class="item" data-value="network-simplex">network-simplex</div>
                    <div class="item" data-value="tight-tree">tight-tree</div>
                    <div class="item" data-value="longest-path">longest-path</div>
                </div>
            </div>
            <label for="rankdir">RankDir:</label>
            <div id="rankdir_dropdown" class="ui tiny selection dropdown" style="overflow:initial">
                <input type="hidden" id="rankdir" name="rankdir" value="BT">
                <i class="dropdown icon"></i>
                <div class="default text">Bottom-Top</div>
                <div class="menu">
                    <div class="item" data-value="TB">Top-Bottom</div>
                    <div class="item" data-value="BT">Bottom-Top</div>
                    <div class="item" data-value="RL">Right-Left</div>
                    <div class="item" data-value="LR">Left-Right</div>
                </div>
            </div>
            <label for="align">Align:</label>
            <div id="align_dropdown" class="ui tiny selection dropdown" style="overflow:initial">
                <input type="hidden" id="align" name="align" value="DL">
                <i class="dropdown icon"></i>
                <div class="default text">Down-Left</div>
                <div class="menu">
                    <div class="item" data-value="DL">Down-Left</div>
                    <div class="item" data-value="DR">Down-Right</div>
                    <div class="item" data-value="UL">Up-Left</div>
                    <div class="item" data-value="UR">Up-Right</div>
                </div>
            </div>
            <label for="connector">Connector:</label>
            <div id="connector_dropdown" class="ui tiny selection dropdown" style="overflow:initial">
                <input type="hidden" id="connector" name="connector" value="smooth">
                <i class="dropdown icon"></i>
                <div class="default text">smooth</div>
                <div class="menu">
                    <div class="item" data-value="smooth">smooth</div>
                    <div class="item" data-value="curve">curve</div>
                    <div class="item" data-value="normal">normal</div>
                    <div class="item" data-value="jumpover">jumpover</div>
                </div>
            </div>

            <div class="p-1">
                <label for="ranksep">RankSep:</label>
                <input id="ranksep" type="range" min="1" max="100" value="50" />
            </div>
            <div class="p-1">
                <label for="edgesep">EdgeSep:</label>
                <input id="edgesep" type="range" min="1" max="100" value="50" />
            </div>
            <div class="p-1">
                <label for="nodesep">NodeSep:</label>
                <input id="nodesep" type="range" min="1" max="100" value="50" />
            </div>
            <div class="p-1">
                <label for="vertices">Vertices:</label>
                <input type=checkbox checked id="vertices">
            </div>

        </div>
    </div>
    <div class="actions">
    </div>
</div>

