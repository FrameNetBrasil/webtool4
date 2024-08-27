<div class="grid words w-full">
@foreach($words as $word)
        <div class="col-3">
            <div
                class="ui card cursor-pointer w-full"
            >
                <div class="content">
                    <div
                        class="header"
                    >
                        <div
                            class="word"
                        >
                            {{$word->word}}
                        </div>
                    </div>
                    <div class="description">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
