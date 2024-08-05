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
            <div id="ranker" class="ui selection dropdown">
                <i class="dropdown icon"></i>
                <div class="default text">network-simplex</div>
                <div class="menu">
                    <div class="item" data-value="network-simplex">network-simplex</div>
                    <div class="item" data-value="tight-tree">tight-tree</div>
                    <div class="item" data-value="longest-path">longest-path</div>
                </div>
            </div>
            <div class="hxBox p-1">
                <label for="rankdir">RankDir:</label>
                <x-select id="rankdir" style="width:120px">
                    <option value="TB">Top-Bottom</option>
                    <option value="BT" selected>Bottom-Top</option>
                    <option value="RL">Right-Left</option>
                    <option value="LR">Left-Right</option>
                </x-select>
            </div>
            <div class="hxBox p-1">
                <label for="align">Align:</label>
                <x-select id="align" style="width:120px">
                    <option value="DL" selected>Down-Left</option>
                    <option value="DR">Down-Right</option>
                    <option value="UL">Up-Left</option>
                    <option value="UR">Up-Right</option>
                </x-select>
            </div>
            <div class="hxBox p-1">
                <label for="ranksep">RankSep:</label>
                <input id="ranksep" type="range" min="1" max="100" value="50" />
            </div>
            <div class="hxBox p-1">
                <label for="edgesep">EdgeSep:</label>
                <input id="edgesep" type="range" min="1" max="100" value="50" />
            </div>
            <div class="hxBox p-1">
                <label for="nodesep">NodeSep:</label>
                <input id="nodesep" type="range" min="1" max="100" value="50" />
            </div>
            <div class="hxBox p-1">
                <label for="vertices">Vertices:</label>
                <input type=checkbox checked id="vertices">
            </div>
            <div class="hxBox p-1">
                <label for="connector">Connector:</label>
                <x-select id="connector" style="width:120px">
                    <option value="smooth" selected>smooth</option>
                    <option value="curve">curve</option>
                    <option value="normal">normal</option>
                    <option value="jumpover">jumpover</option>
                </x-select>
            </div>
        </div>
    </div>
    <div class="actions">
    </div>
</div>
<script>
    $(function() {
        $('#ranker').dropdown();
    });
</script>
