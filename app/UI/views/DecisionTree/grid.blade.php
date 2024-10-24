@php
    $tree = [
        ['Eventive','','',[
            ['Process','Processo formado por várias etapas.', 'frm_process',[
                ['Activity','Processo formado por várias etapas que tem uma agente.','',[]],
            ]],
            ['Event','Eventos que ocorrem no tempo/espaço.','',[
                ['Phenomenon','Eventos que ocorrem naturalmente.','',[
                    ['Natural_phenomena','Fenômenos da natureza.','',[]],
                ]],
                ['Change','Eventos que representam uma mudança de estado.','',[
                    ['Eventive_affecting','Uma entidade sofre uma mudança através de um evento pontual.','',[]],
                    ['Transition_to_a_state','Uma entidade termina em um estado (categoria, situação ou qualidade) em que não estava antes.','',[
                        ['Transition_to_a_situation','Uma entidade termina em uma situação em que não estava antes.','',[]],
                        ['Becoming','Uma entidade termina com uma qualidade que representa um novo fato sobre ela.','',[]],
                        ['Transition_to_a_quality','Uma entidade termina com uma qualidade nova.','',[]],
                        ['Undergo_change','Uma entidade sofre uma mudança na sua classificação ou no valor de um de seus atributos.','',[]],
                        ['Undergo_transformation','Uma entidade muda de um tipo para outro tipo.','',[]],
                    ]],
                ]],
                ['Transitive_action','Um agente (entidade) ou uma causa (evento) afeta um paciente. Raiz da família "causar" e "fazer_ficar".','',[
                    ['Intentionally_affect','Ações intencionais realizadas por um agente para afetar um paciente.','',[]],
                ]],
                ['Intentionally_act','Ações intencionais realizadas por seres conscientes.','',[]],
                ['Subjective_experience','Uma entidade passa por uma experiência interna e subjetiva.','',[
                    ['Subjective_influence','Um agente, uma entidade ou um evento exercem uma influência qualquer sobre um ser consciente.','',[]],
                    ['Mental_activity','Um ser consciente realiza alguma operação mental.','',[]],
                    ['Emotions','Um experienciador está num estado emocional específico.','',[]],
                    ['Perception','Um experienciador percebe um fenômeno, independente da modalidade sensória.','',[]],
                ]],
            ]],
            ['State','Estado de uma entidade.','',[
                ['Stative_location','Estado relacionado à localização de uma entidade.','',[]],
                ['Stative_configuration','Estado relacionados à configuração de uma entidade ou de seus elementos.','',[]],
                ['Subjective_state','Estado relacionado à condições subjetivas (cognitivas, mentais, psicológicas).','',[]],
                ['State_of_process','Estado associado à execução de uma processo.','',[]],
                ['Possession','Estado de posse.','',[]],
                ['State_of_entity','Estado (físico) de uma entidade.','',[]],
            ]],
        ]],
        ['Quality','','',[
            ['Attributes','Atributos (propriedades, qualidades) de uma entidade','',[
                ['Gradable_attributes','Atributos que possuem gradação em sua medida.','',[]],
                ['Measurable_attributes','Atributos que podem ter suas dimensões medidas.','',[]],
                ['Physical_attributes','Atributos associados a características físicas.','',[]],
                ['Social_attributes','Atributos associados a descrições usadas socialmente.','',[]],
                ['Eventive_attributes','Atributos associados a eventos.','',[]],
            ]],
            ['Dimension','Palavras usadas para descrever as dimensões de qualidade.','',[
                ['Quantity','Palavras usadas para descrever quantidades relativas às dimensões de qualidade.','',[]],
            ]],
        ]],
        ['Relation','','',[
            ['Entity_relation','Relação entre entidades.','',[]],
            ['Time_relation','Relações que envolvem medidas de tempo, ou organização temporal.','',[]],
            ['Configuration_relation','Relações que envolvem a configuração dos elementos de uma entidade.','',[]],
        ]],
        ['Entity','','',[
            ['Artifact','Entidades (físicas ou não) criadas com um propósito inerente.','',[]],
            ['Biological_entity.','Entidades biológicas.','',[]],
            ['People','Frames relacionados à caracterização de indivíduos.','',[]],
            ['Physical_entity','Entidades com propriedades físicas.','',[]],
            ['Social_entity','Entidades (abstratas) com papel social.','',[]],
            ['Representing','Entidades que representam algum fenômeno.','',[]],
            ['Information','Entidades que (re/a)presentam alguma informação.','',[]],
        ]],
    ];

    function getData($tree) {
        $nodes = [];
        foreach($tree as $i => $t) {
            $children = getData($t[3]);
            if (empty($children)) {
                $children = null;
            }
            $nodes[] = [
                'id' =>  $t[0] . $i,
                'type' => 'frame',
                'text' => $t[0],
                'description' => $t[1],
                'entry' => $t[2],
                'state' => 'closed',
                'children' => $children
            ];
        }
        return $nodes;
    }

    $data = getData($tree);


@endphp
<div
        class="h-full"
>
    <div class="relative h-full overflow-auto">
        <div id="decisionTreeWrapper" class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
            @fragment('search')
                <ul id="decisionTreeTree">
                </ul>
                <script>
                    $(function() {
                        $("#decisionTreeTree").treegrid({
                            data: {{Js::from($data)}},
                            fit: true,
                            showHeader: false,
                            rownumbers: false,
                            idField: "id",
                            treeField: "text",
                            showFooter: false,
                            border: false,
                            columns: [[
                                {
                                    field: "text",
                                },
                                {
                                    field: "description",
                                },
                                {
                                    field: "entry",
                                    formatter: function(value,row,index){
                                        if (row.entry !== ''){
                                            return "<a>frames</a>";
                                        }
                                    }
                                }
                            ]],
                            onClickCell: (index,field,value) => {
                                console.log(index,field,value);
                                if (field === 'entry') {
                                    htmx.ajax("GET", `/decisiontree/frame/${value}`, "#gridArea");
                                }
                            },
                            onClickRow: (row) => {
                                $("#decisionTreeTree").treegrid("toggle", row.id);
                            }
                        });
                    });
                </script>
            @endfragment
        </div>
    </div>
</div>
