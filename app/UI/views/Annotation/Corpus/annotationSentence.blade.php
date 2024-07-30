<x-layout.annotation>
    <x-slot:title>
        Corpus Annotation
    </x-slot:title>
    <x-slot:actions>
        <div class="navigationPane">
            @if($idSentencePrevious)
                <div class="navigationPane-previous">
                    <span class="material-icons-outlined">arrow_back</span>
                    <a href="/annotation/corpus/sentence/{{$idSentencePrevious}}"><span>Previous</span></a>
                </div>
            @endif
            @if($idSentenceNext)
                <div class="navigationPane-next">
                    <a href="/annotation/corpus/sentence/{{$idSentenceNext}}">Next</a>
                    <span class="material-icons-outlined">arrow_forward</span>
                </div>
            @endif
        </div>
    </x-slot:actions>
    <x-slot:main>
        <div class="wt-annotation-corpus flex flex-column h-full">
            <div class="hxRow  hxGutterless">
                <div class="hxCol  hxGutterless hxSpan-6">
                    <x-tag label="Sentence: #{{$idSentence}}"></x-tag>
                    @foreach($data['metadata']['documents'] as $document)
                        <x-tag label="Document: {{$document}}"></x-tag>
                    @endforeach
                </div>
                <div class="hxCol  hxGutterless hxSpan-6 text-right">
                    <x-button label="Reload" href="/annotation/corpus/sentence/{{$idSentence}}"
                              color="secondary"></x-button>
                </div>
            </div>
            <div class="hxRow  hxGutterless">
                <div class="hxCol  hxGutterless">
                    @include('Annotation.Corpus.annotations')
                </div>
            </div>
            <div class="hxRow  hxGutterless flex-grow-1">
                <div class="hxCol  hxGutterless">
                    <div id="workArea">
                        @include('Annotation.Corpus.Layers.datagrid')
                    </div>
                </div>
            </div>
        </div>
        @include('Annotation.Corpus.Layers.popover')
        <script>
            let annotationFE = {
                selection: {
                    type: "",
                    id: "",
                    start: 0,
                    end: 0
                }
            };
            let idSentence = {{$idSentence}};
            let csrf = '{{csrf_token()}}';
            let annotationData = {{ Js::from($data) }};
            console.log(annotationData);
            @include('Annotation.Corpus.Layers.annotation')

            $(function() {
                annotation.initDatagrid();
                annotation.$dg = $("#dataGridLayers");


                // $("#corpusAnnotationPane").panel({
                //     fit: true,
                //     border: false
                // });

                /*
                $(".itemMenu").click((e => {
                        annotation.onLabelClick($(e.currentTarget).data().idlabeltype);
                    })
                );

                $(".itemMenuLU").click((e => {
                        annotation.onLUClick($(e.currentTarget).data().idlu);
                    })
                );

                $(".itemMenuNI").click((e => {
                        annotation.onNIClick($(e.currentTarget).data().idlabeltype);
                    })
                );

                 */

                // $(window).on('addx', () => console.log('a'));

            });

        </script>
    </x-slot:main>
</x-layout.annotation>

