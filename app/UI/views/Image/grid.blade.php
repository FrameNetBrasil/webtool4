@php
    use App\Database\Criteria;
    $imageIcon = view('components.icon.image')->render();
    $images = Criteria::byFilter("image",["name","startswith", $search->name])
            ->limit(1000)->orderBy("name")->get()->keyBy("idImage")->all();
@endphp
<div
    id="imageGrid"
    class="h-full"
    hx-trigger="reload-gridImage from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/image/grid"
>
    <div class="relative h-full overflow-auto">
        <table class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
            <tbody>
            @foreach($images as $idImage => $image)
                <tr>
                    <td
                        hx-get="/image/{{$idImage}}/edit"
                        hx-target="#editArea"
                        hx-swap="innerHTML"
                    >
                        <a><x-icon.image></x-icon.image>{{$image->name}}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
