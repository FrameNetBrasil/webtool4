<div class="object-info">
    <h4>Object Clicked:</h4>
    <ul>
        <li><strong>Layer:</strong> {{ $clickData['layer'] }}</li>
        <li><strong>Object:</strong> {{ $object['gl'] ?? $object['luName'] ?? 'Unnamed' }}</li>
        <li><strong>Frame Range:</strong> {{ $clickData['frameRange'] }}</li>
        <li><strong>Duration:</strong> {{ $clickData['duration'] }} frames</li>
        <li><strong>Line:</strong> {{ $clickData['lineIndex'] + 1 }}</li>
    </ul>
</div>
