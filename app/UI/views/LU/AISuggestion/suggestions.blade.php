<table>
@foreach($results['items'] as $item)
    @php
        $confidence = $item['confidence'] ?? 0;
    $confidenceColor = $confidence > 0.8 ? 'green' : ($confidence > 0.6 ? 'yellow' : 'red');

    $tableData = [
        $item['lemma'] ?? '',
        $item['pos'] ?? '',
        substr($item['gloss_pt'] ?? '', 0, 50) . (strlen($item['gloss_pt'] ?? '') > 50 ? '...' : ''),
        number_format($confidence, 2),
        substr($item['rationale_short'] ?? '', 0, 60) . (strlen($item['rationale_short'] ?? '') > 60 ? '...' : ''),
    ];


    @endphp
        <tr>
            <td>
               {{$tableData[0]}}
            </td>
            <td>
                {{$tableData[1]}}
            </td>
            <td>
                {{$tableData[2]}}
            </td>
        </tr>

@endforeach
</table>
