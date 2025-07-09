// Define the tree web component
class WtTree extends HTMLElement {
    // Define which attributes to observe
    static get observedAttributes() {
        return ['url', 'title', 'height', 'initial-data'];
    }

    constructor() {
        super();

        // Set initial HTML template
        this.innerHTML = `
                    <div class="tree-container" style="width: 100%; min-height: 200px; height: 400px;">
                        <div class="tree-header"></div>
                        <div class="tree-body">
                            <div class="loading-indicator">Loading...</div>
                        </div>
                    </div>
                `;

        // Store references
        this._container = this.querySelector('.tree-container');
        this._header = this.querySelector('.tree-header');
        this._body = this.querySelector('.tree-body');
        this._initialData = [];
        this._loadedNodes = new Set();
    }

    // Handle attribute changes
    attributeChangedCallback(name, oldValue, newValue) {
        // Update height directly on the container
        if (name === 'height' && this._container) {
            const height = newValue || '400px';
            this._container.style.height = `${height}`;
        }

        // Update title
        if (name === 'title' && this._header) {
            this._header.textContent = newValue || '';
            this._header.style.display = newValue ? 'block' : 'none';
        }

        // Parse initial data
        if (name === 'initial-data') {
            try {
                this._initialData = JSON.parse(newValue || '[]');
                if (this._body) {
                    this._renderTree();
                }
            } catch (e) {
                console.error('Invalid initial-data format', e);
            }
        }
    }

    // Lifecycle: when element is connected to DOM
    connectedCallback() {
        // Update height from attribute
        const height = this.getAttribute('height') || '400px';
        this._container.style.height = `${height}`;

        // Update title
        const title = this.getAttribute('title') || '';
        this._header.textContent = title;
        this._header.style.display = title ? 'block' : 'none';

        // Parse initial data
        if (this.hasAttribute('initial-data')) {
            try {
                this._initialData = JSON.parse(this.getAttribute('initial-data'));
            } catch (e) {
                console.error('Invalid initial-data format', e);
            }
        }

        // Initialize the tree
        this._initTree();
    }

    // Initialize the tree
    _initTree() {
        if (!window.htmx) {
            console.error('HTMX not available');
            setTimeout(() => this._initTree(), 100);
            return;
        }

        this._renderTree();
    }

    // Render the tree structure
    _renderTree() {
        if (this._initialData.length === 0) {
            this._body.innerHTML = '<div class="no-data">No data available</div>';
            return;
        }

        const table = document.createElement('table');
        table.className = 'tree-table';

        this._initialData.forEach(item => {
            const row = this._createTreeRow(item);
            table.appendChild(row);
            const rowChild = this._createTreeRowChild(item);
            table.appendChild(rowChild);
        });

        this._body.innerHTML = '';
        this._body.appendChild(table);
    }


    // Create a tree row
    _createTreeRow(item, level = 0) {
        const row = document.createElement('tr');

        // Toggle cell
        const toggleCell = document.createElement('td');
        toggleCell.className = 'toggle';
        toggleCell.style.paddingLeft = `${level * 20}px`;
        toggleCell.innerHTML = '<span class="toggle-icon collapsed"></span>';
        toggleCell.addEventListener('click', () => this._toggleNode(item, toggleCell));

        // Content cell
        const contentCell = document.createElement('td');
        contentCell.className = 'content-cell';
        contentCell.id = item.id;
        contentCell.innerHTML = `
                    <span class="tree-item-text">${item.text}</span>
<!--                    <div id="tree_${item.id}" class="tree-content hidden"></div>-->
                `;

        // Add click event to the text content
        const textSpan = contentCell.querySelector('.tree-item-text');
        textSpan.style.cursor = 'pointer';
        textSpan.addEventListener('click', () => this._handleItemClick(item.id));

        row.appendChild(toggleCell);
        row.appendChild(contentCell);

        return row;
    }

    // Create a tree row
    _createTreeRowChild(item, level = 0) {
        const row = document.createElement('tr');
        row.className = 'hidden';
        row.id = 'row_' + item.type + '_' + item.id;

        // Toggle cell
        const toggleCell = document.createElement('td');

        // Content cell
        const contentCell = document.createElement('td');
        contentCell.innerHTML = `
                    <div id="tree_${item.id}" class="tree-content hidden"></div>
                `;
        row.appendChild(toggleCell);
        row.appendChild(contentCell);
        return row;
    }

    // Handle item click and dispatch custom event
    _handleItemClick(itemId) {
        // Dispatch custom event
        this.dispatchEvent(new CustomEvent('onClickData', {
            detail: { id: itemId },
            bubbles: true
        }));

        // Also call the onClickData function if it exists as an attribute
        const onClickDataAttr = this.getAttribute('onClickData');
        if (onClickDataAttr) {
            try {
                // Create a function from the attribute value
                const func = new Function('id', onClickDataAttr);
                func(itemId);
            } catch (e) {
                console.error('Error executing onClickData function:', e);
            }
        }
    }

    // Toggle node expansion
    _toggleNode(item, toggleElement) {
        const itemId = item.id;
        const rowId = 'row_' + item.type + '_' + item.id;
        const row = document.getElementById(rowId);
        const treeDiv = document.getElementById(`tree_${itemId}`);
        const icon = toggleElement.querySelector('.toggle-icon');

        if (treeDiv.classList.contains('hidden')) {
            // Opening - check if content needs to be loaded
            if (!this._loadedNodes.has(itemId)) {
                treeDiv.innerHTML = '<div class="loading">Loading...</div>';
                this._loadNodeContent(item);
                this._loadedNodes.add(itemId);
            }

            row.classList.remove('hidden');
            treeDiv.classList.remove('hidden');
            icon.classList.remove('collapsed');
            icon.classList.add('expanded');
            //icon.textContent = '▼';
        } else {
            // Closing
            row.classList.add('hidden');
            treeDiv.classList.add('hidden');
            icon.classList.add('collapsed');
            icon.classList.remove('expanded');
            //icon.textContent = '▶';
        }
    }

    // Load node content using HTMX
    _loadNodeContent(item) {
        console.log(item);
        const itemId = item.id;
        const type = item.type;
        const url = this.getAttribute('url') || '/api/tree';
        const targetDiv = document.getElementById(`tree_${itemId}`);

        if (window.htmx) {
            htmx.ajax('GET', `${url}/${type}/${itemId}`, {
                target: `#tree_${itemId}`,
                swap: 'innerHTML'
            });
        }
    }

    // Public API methods
    reload() {
        this._loadedNodes.clear();
        this._renderTree();
    }

    expand(itemId) {
        const toggleElement = this.querySelector(`#${itemId}`).closest('tr').querySelector('.toggle');
        if (toggleElement) {
            this._toggleNode(itemId, toggleElement);
        }
    }

    collapse(itemId) {
        const treeDiv = document.getElementById(`tree_${itemId}`);
        const toggleElement = this.querySelector(`#${itemId}`).closest('tr').querySelector('.toggle');

        if (treeDiv && !treeDiv.classList.contains('hidden')) {
            this._toggleNode(itemId, toggleElement);
        }
    }

    getExpandedNodes() {
        return Array.from(this._loadedNodes);
    }
}

// Register the web component
customElements.define('wt-tree', WtTree);
