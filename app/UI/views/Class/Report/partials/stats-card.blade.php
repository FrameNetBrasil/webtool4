{{--
    Class Stats Card - Quick overview of class usage statistics
    Parameters:
    - $stats: Statistics array with totalFEs and totalFrames
--}}
<div class="ui card fluid data-card stats-card">
    <div class="content">
        <div class="data-card-header">
            <div class="data-card-title">
                <div class="header">Usage Statistics</div>
            </div>
        </div>
        <div class="data-card-stats">
            <div class="stat-item">
                <div class="stat-value">{{ $stats['totalFEs'] ?? 0 }}</div>
                <div class="stat-label">Frame Elements</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['totalFrames'] ?? 0 }}</div>
                <div class="stat-label">Frames</div>
            </div>
        </div>
    </div>
</div>
