@if(isset($object))
    <div class="form" style="height:240px">
        <form
            hx-post="/annotation/dynamicMode/updateObjectComment"
        >
            <x-slot:title>
                <div class="flex gap-2">
                    <div class="title">Comment for Object: #{{$order}}</div>
                    <div class="flex h-2rem gap-2">
                        <div class="ui label">
                            Range
                            <div class="detail">{{$object->startFrame}}/{{$object->endFrame}}</div>
                        </div>
                        <div class="ui label wt-tag-id">
                            #{{$object->idDynamicObject}}
                        </div>
                    </div>
                    @if($object->email)
                        <div class="text-sm">Created by [{{$object->email}}] at [{{$object->createdAt}}]</div>
                    @endif
                </div>
            </x-slot:title>
            <x-slot:fields>
                <x-form::hidden-field id="idDocument" value="{{$idDocument}}"></x-form::hidden-field>
                <x-form::hidden-field id="idDynamicObject" value="{{$object?->idDynamicObject}}"></x-form::hidden-field>
                <x-form::hidden-field id="createdAt" value="{{$object?->createdAt}}"></x-form::hidden-field>
                <div class="field mr-1">
                    <x-form::multiline-field
                        label="Comment"
                        id="comment"
                        rows="4"
                        :value="$object->comment ?? ''"
                    ></x-form::multiline-field>
                </div>
            </x-slot:fields>
            <x-slot:buttons>
                <button type="submit" class="ui medium button">
                    Save
                </button>
                <button
                    class="ui medium button danger"
                    type="button"
                    onclick="annotation.objects.deleteObjectComment({{$object?->idDynamicObject}})"
                >Delete
                </button>
            </x-slot:buttons>
        </form>
    </div>
@endif
