<div class="ui modal" id="grapherOptionsModal">
    <i class="close icon"></i>
    <div class="header">
        Grapher Options
    </div>
    <div class="content">
        <div class="ui form">
            <div class="field">
                <label for="ranker">Ranker</label>
                <select id="ranker" x-model="ranker">
                    <option value="network-simplex">network-simplex</option>
                    <option value="tight-tree">tight-tree</option>
                    <option value="longest-path">longest-path</option>
                </select>
            </div>
            <div class="field">
                <label for="rankdir">RankDir</label>
                <select id="rankdir" x-model="rankdir">
                    <option value="TB">Top-Bottom</option>
                    <option value="BT">Bottom-Top</option>
                    <option value="RL">Right-Left</option>
                    <option value="LR">Left-Right</option>
                </select>
            </div>
            <div class="field">
                <label for="align">Align</label>
                <select id="align" x-model="align">
                    <option value="DL">Down-Left</option>
                    <option value="DR">Down-Right</option>
                    <option value="UL">Up-Left</option>
                    <option value="UR">Up-Right</option>
                </select>
            </div>
            <div class="field">
                <label for="connector">Connector</label>
                <select id="connector" x-model="connector">
                    <option value="normal">Normal</option>
                    <option value="smooth">Smooth</option>
                    <option value="jumpover">Jumpover</option>
                    <option value="curve">Curve</option>
                </select>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" id="vertices" x-model="vertices">
                    <label for="vertices">Vertices</label>
                </div>
            </div>
            <div class="field">
                <label for="ranksep">RankSep: <span x-text="ranksep"></span></label>
                <input id="ranksep" type="range" min="1" max="100" x-model="ranksep" />
            </div>
            <div class="field">
                <label for="edgesep">EdgeSep: <span x-text="edgesep"></span></label>
                <input id="edgesep" type="range" min="1" max="100" x-model="edgesep" />
            </div>
            <div class="field">
                <label for="nodesep">NodeSep: <span x-text="nodesep"></span></label>
                <input id="nodesep" type="range" min="1" max="100" x-model="nodesep" />
            </div>
        </div>
    </div>
    <div class="actions">
        <div class="ui cancel button">Cancel</div>
        <div class="ui primary button" @click="relayout(); $('#grapherOptionsModal').modal('hide');">Apply</div>
    </div>
</div>
