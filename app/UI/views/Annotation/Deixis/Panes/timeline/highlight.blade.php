<div class="object-info">
    <h4>Objects active at frame {{ number_format($frameNumber) }}:</h4>
    @if (count($activeObjects) > 0)
        <ul>
            @foreach ($activeObjects as $item)
                @php
                    $object = $item['object'];
                    $label = $object['gl'] ?? $object['luName'] ?? "Object " . ($item['objectIndex'] + 1);
                @endphp
                <li>{{ $label }} ({{ $object['startFrame'] }}-{{ $object['endFrame'] }})</li>
            @endforeach
        </ul>
        <script>
            // Highlight objects in timeline
            document.querySelectorAll('.timeline-object').forEach(obj => {
                obj.classList.remove('highlighted');
                const startFrame = parseInt(obj.dataset.startFrame);
                const endFrame = parseInt(obj.dataset.endFrame);
                if ({{ $frameNumber }} >= startFrame && {{ $frameNumber }} <= endFrame) {
                    obj.classList.add('highlighted');
                }
            });

            // Remove highlights after 3 seconds
            setTimeout(() => {
                document.querySelectorAll('.timeline-object').forEach(obj => {
                    obj.classList.remove('highlighted');
                });
            }, 3000);
        </script>
    @else
        <p>No objects found at frame {{ number_format($frameNumber) }}.</p>
    @endif
</div>
