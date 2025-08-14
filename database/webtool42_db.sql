create table annotationmm
(
    idAnnotationMM     int auto_increment
        primary key,
    idObjectSentenceMM int              not null,
    idFrameElement     int(11) unsigned null,
    idFrame            int(11) unsigned null
)
    collate = utf8mb4_bin;

create index idx_annotationmm_frame
    on annotationmm (idFrame);

create index idx_annotationmm_frameelement
    on annotationmm (idFrameElement);

create index idx_annotationmm_objectsentencemm
    on annotationmm (idObjectSentenceMM);

create table boundingbox
(
    idBoundingBox int auto_increment
        primary key,
    frameNumber   int           null,
    frameTime     float         null,
    x             int           null,
    y             int           null,
    width         int           null,
    height        int           null,
    blocked       int           null,
    isGroundTruth int default 1 null
)
    collate = utf8mb4_bin;

create table color
(
    idColor int(11) unsigned auto_increment
        primary key,
    name    varchar(50) charset utf8mb4 null,
    rgbFg   char(6) charset utf8mb4     null,
    rgbBg   char(6) charset utf8mb4     null
)
    collate = utf8mb4_bin;

create table dashboard
(
    idDashboard    int(11) unsigned auto_increment
        primary key,
    timeLastUpdate datetime not null
);

create table dashboard_audition
(
    idDashboardAudition int(11) unsigned auto_increment
        primary key,
    text_sentence       int(11) unsigned not null,
    text_frame          int(11) unsigned not null,
    text_ef             int(11) unsigned not null,
    text_lu             int(11) unsigned not null,
    text_as             int(11) unsigned not null,
    video_bbox          int(11) unsigned not null,
    video_frame         int(11) unsigned not null,
    video_ef            int(11) unsigned not null,
    video_obj           int(11) unsigned not null,
    avg_sentence        float            not null,
    avg_obj             float            not null,
    origin              text             null
);

create table dashboard_frame2
(
    idDashboardFrame2 int(11) unsigned auto_increment
        primary key,
    text_sentence     int(11) unsigned not null,
    text_frame        int(11) unsigned not null,
    text_ef           int(11) unsigned not null,
    text_lu           int(11) unsigned not null,
    text_as           int(11) unsigned not null,
    video_bbox        int(11) unsigned not null,
    video_frame       int(11) unsigned not null,
    video_ef          int(11) unsigned not null,
    video_obj         int(11) unsigned not null,
    avg_sentence      float            not null,
    avg_obj           float            not null
);

create table dashboard_frame2gesture
(
    idDashboardFrame2Gesture int(11) unsigned auto_increment
        primary key,
    text_sentence            int(11) unsigned not null,
    text_frame               int(11) unsigned not null,
    text_ef                  int(11) unsigned not null,
    text_lu                  int(11) unsigned not null,
    text_as                  int(11) unsigned not null,
    video_bbox               int(11) unsigned not null,
    video_frame              int(11) unsigned not null,
    video_ef                 int(11) unsigned not null,
    video_obj                int(11) unsigned not null,
    avg_sentence             float            not null,
    avg_obj                  float            not null
);

create table dashboard_frame2nlg
(
    idDashboardFrame2NLG int(11) unsigned auto_increment
        primary key,
    text_sentence        int(11) unsigned not null,
    text_frame           int(11) unsigned not null,
    text_ef              int(11) unsigned not null,
    text_lu              int(11) unsigned not null,
    text_as              int(11) unsigned not null,
    video_bbox           int(11) unsigned not null,
    video_frame          int(11) unsigned not null,
    video_ef             int(11) unsigned not null,
    video_obj            int(11) unsigned not null,
    avg_sentence         float            not null,
    avg_obj              float            not null
);

create table dashboard_frame2ppm
(
    idDashboardFrame2PPM int(11) unsigned auto_increment
        primary key,
    text_sentence        int(11) unsigned not null,
    text_frame           int(11) unsigned not null,
    text_ef              int(11) unsigned not null,
    text_lu              int(11) unsigned not null,
    text_as              int(11) unsigned not null,
    video_bbox           int(11) unsigned not null,
    video_frame          int(11) unsigned not null,
    video_ef             int(11) unsigned not null,
    video_obj            int(11) unsigned not null,
    avg_sentence         float            not null,
    avg_obj              float            not null
);

create table dashboard_multi30k
(
    idDashboardMulti30k        int(11) unsigned auto_increment
        primary key,
    multi30k_image_image       int(11) unsigned not null,
    multi30k_image_bbox        int(11) unsigned not null,
    multi30k_image_frame       int(11) unsigned not null,
    multi30k_image_ef          int(11) unsigned not null,
    multi30k_ptt_sentence      int(11) unsigned not null,
    multi30k_ptt_lome          int(11) unsigned not null,
    multi30k_pto_sentence      int(11) unsigned not null,
    multi30k_pto_lome          int(11) unsigned not null,
    multi30k_eno_sentence      int(11) unsigned not null,
    multi30k_eno_lome          int(11) unsigned not null,
    multi30kevent_image_image  int(11) unsigned not null,
    multi30kevent_image_bbox   int(11) unsigned not null,
    multi30kevent_image_frame  int(11) unsigned not null,
    multi30kevent_image_ef     int(11) unsigned not null,
    multi30kentity_image_image int(11) unsigned not null,
    multi30kentity_image_bbox  int(11) unsigned not null,
    multi30kentity_image_frame int(11) unsigned not null,
    multi30kentity_image_ef    int(11) unsigned not null
);

create table dataset
(
    idDataset   int auto_increment
        primary key,
    name        varchar(255) null,
    description text         null
)
    collate = utf8mb4_bin;

create table documentmm
(
    idDocumentMM int auto_increment
        primary key,
    title        varchar(255)                 null,
    originalFile varchar(255)                 null,
    sha1Name     varchar(255)                 null,
    audioPath    varchar(255) charset utf8mb4 null,
    videoPath    varchar(255)                 null,
    alignPath    varchar(255) charset utf8mb4 null,
    videoWidth   int                          null,
    videoHeight  int                          null,
    flickr30k    char                         null,
    enabled      char                         null,
    url          varchar(255)                 null,
    idDocument   int(11) unsigned             not null,
    idLanguage   int(11) unsigned             not null
)
    collate = utf8mb4_bin;

create index idx_DocumentMM_Document
    on documentmm (idDocument);

create index idx_DocumentMM_Language
    on documentmm (idLanguage);

create table entity
(
    idEntity int(11) unsigned auto_increment
        primary key,
    type     char(3) null,
    idOld    int     null
)
    collate = utf8mb4_bin;

create table concept
(
    idConcept int auto_increment
        primary key,
    entry     varchar(255) charset utf8mb4 null,
    keyword   varchar(255)                 null,
    aka       text                         null,
    type      varchar(255)                 null,
    status    varchar(45)                  null,
    idEntity  int(11) unsigned             not null,
    idType    int                          not null,
    constraint fk_Concept_Entity
        foreign key (idEntity) references entity (idEntity)
)
    collate = utf8mb4_bin;

create index idx_Concept_Entity
    on concept (idEntity);

create index idx_Concept_Type
    on concept (idType);

create table corpus
(
    idCorpus int(11) unsigned auto_increment
        primary key,
    entry    varchar(255) charset utf8mb4 null,
    active   tinyint(1) default 1         null,
    idEntity int(11) unsigned             null,
    constraint fk_Corpus_Entity
        foreign key (idEntity) references entity (idEntity)
)
    collate = utf8mb4_bin;

create index idx_Corpus_Entity
    on corpus (idEntity);

create index idx_Corpus_Entry
    on corpus (entry);

create table dataset_corpus
(
    idCorpus  int(11) unsigned     not null,
    idDataset int                  not null,
    isSource  tinyint(1) default 0 null,
    primary key (idCorpus, idDataset),
    constraint fk_corpus_has_dataset_corpus1
        foreign key (idCorpus) references corpus (idCorpus),
    constraint fk_corpus_has_dataset_dataset1
        foreign key (idDataset) references dataset (idDataset)
)
    collate = utf8mb4_bin;

create index fk_corpus_has_dataset_corpus1_idx
    on dataset_corpus (idCorpus);

create index fk_corpus_has_dataset_dataset1_idx
    on dataset_corpus (idDataset);

create table domain
(
    idDomain int(11) unsigned auto_increment
        primary key,
    entry    varchar(255) charset utf8mb4 not null,
    idEntity int(11) unsigned             not null,
    constraint fk_Domain_Entity
        foreign key (idEntity) references entity (idEntity)
)
    collate = utf8mb4_bin;

create index idx_Domain_Entity1
    on domain (idEntity);

create index idx_Domain_entry
    on domain (entry);

create table frame
(
    idFrame           int(11) unsigned auto_increment
        primary key,
    entry             varchar(255) charset utf8mb4 not null,
    active            tinyint(1)                   null,
    defaultName       varchar(255)                 null,
    defaultDefinition text                         null,
    fnVersion         varchar(50)                  null,
    idEntity          int(11) unsigned             not null,
    constraint fk_Frame_Entity
        foreign key (idEntity) references entity (idEntity)
)
    collate = utf8mb4_bin;

create index idx_Frame_Entity
    on frame (idEntity);

create index idx_Frame_Entry
    on frame (entry);

create table frameelement
(
    idFrameElement    int(11) unsigned auto_increment
        primary key,
    entry             varchar(255) charset utf8mb4 null,
    coreType          varchar(50)                  null,
    active            tinyint(1)                   null,
    defaultName       varchar(255)                 null,
    defaultDefinition text                         null,
    idEntity          int(11) unsigned             not null,
    idFrame           int(11) unsigned             not null,
    idColor           int(11) unsigned             not null,
    constraint fk_FrameElement_Color
        foreign key (idColor) references color (idColor),
    constraint fk_FrameElement_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_FrameElement_Frame
        foreign key (idFrame) references frame (idFrame)
)
    collate = utf8mb4_bin;

create index idx_FrameElement_Color
    on frameelement (idColor);

create index idx_FrameElement_Entity
    on frameelement (idEntity);

create index idx_FrameElement_Entry
    on frameelement (entry);

create index idx_FrameElement_Frame
    on frameelement (idFrame);

create table genretype
(
    idGenreType int auto_increment
        primary key,
    entry       varchar(255) charset utf8mb4 null,
    idEntity    int(11) unsigned             null,
    constraint fk_GenreType_Entity
        foreign key (idEntity) references entity (idEntity)
)
    collate = utf8mb4_bin;

create table genre
(
    idGenre     int(11) unsigned auto_increment
        primary key,
    entry       varchar(255) charset utf8mb4 null,
    idGenreType int                          not null,
    idEntity    int(11) unsigned             null,
    constraint fk_Genre_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_Genre_GenreType
        foreign key (idGenreType) references genretype (idGenreType)
)
    collate = utf8mb4_bin;

create table document
(
    idDocument int(11) unsigned auto_increment
        primary key,
    entry      varchar(255) charset utf8mb4 not null,
    author     varchar(255) charset utf8mb4 null,
    active     tinyint(1) default 1         null,
    idGenre    int(11) unsigned             not null,
    idCorpus   int(11) unsigned             not null,
    idEntity   int(11) unsigned             null,
    constraint fk_Document_Corpus
        foreign key (idCorpus) references corpus (idCorpus),
    constraint fk_Document_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_Document_Genre
        foreign key (idGenre) references genre (idGenre)
)
    collate = utf8mb4_bin;

create index idx_Document_Corpus
    on document (idCorpus);

create index idx_Document_Entity
    on document (idEntity);

create index idx_Document_Entry
    on document (entry);

create index idx_Document_Genre
    on document (idGenre);

create index idx_Genre_Entity
    on genre (idEntity);

create index idx_Genre_Entry
    on genre (entry);

create index idx_Genre_GenreType
    on genre (idGenreType);

create index idx_GenreType_Entity
    on genretype (idEntity);

create table gfequivalence
(
    idGFEquivalence int auto_increment
        primary key,
    LangSource      varchar(45) charset utf8mb4 null,
    LangDest        varchar(45) charset utf8mb4 null,
    LabelSource     varchar(45) charset utf8mb4 null,
    LabelDest       varchar(45) charset utf8mb4 null
)
    collate = utf8mb4_bin;

create table `group`
(
    idGroup     int auto_increment
        primary key,
    name        varchar(50) charset utf8mb4  null,
    description varchar(255) charset utf8mb4 null
)
    collate = utf8mb4_bin;

create table imagemm
(
    idImageMM int default 0                    not null,
    name      varchar(255) collate utf8mb4_bin null,
    width     int                              null,
    height    int                              null,
    depth     int                              null,
    imagePath varchar(255) collate utf8mb4_bin null
)
    charset = utf8mb3;

create table language
(
    idLanguage  int(11) unsigned            not null
        primary key,
    language    varchar(50) charset utf8mb4 null comment 'Two-letter ISO 639-1 language codes + region, See: http://www.w3.org/International/articles/language-tags/',
    description varchar(50) charset utf8mb4 null
)
    collate = utf8mb4_bin;

create table construction
(
    idConstruction int auto_increment
        primary key,
    entry          varchar(255) charset utf8mb4 not null,
    abstract       tinyint(1)                   null,
    active         tinyint(1)                   null,
    idLanguage     int(11) unsigned             null,
    idEntity       int(11) unsigned             not null,
    constraint fk_Construction_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_Construction_Language
        foreign key (idLanguage) references language (idLanguage)
)
    collate = utf8mb4_bin;

create index idx_Construction_Entity
    on construction (idEntity);

create index idx_Construction_Entry
    on construction (entry);

create index idx_Construction_Language
    on construction (idLanguage);

create table constructionelement
(
    idConstructionElement int auto_increment
        primary key,
    entry                 varchar(255) charset utf8mb4 null,
    active                tinyint(1)                   null,
    idEntity              int(11) unsigned             not null,
    idColor               int(11) unsigned             not null,
    optional              tinyint(1) default 0         null,
    head                  tinyint(1) default 0         null,
    multiple              tinyint(1) default 1         null,
    idConstruction        int                          null,
    constraint fk_ConstructionElement_Color
        foreign key (idColor) references color (idColor),
    constraint fk_ConstructionElement_Construction
        foreign key (idConstruction) references construction (idConstruction),
    constraint fk_ConstructionElement_Entity
        foreign key (idEntity) references entity (idEntity)
)
    collate = utf8mb4_bin;

create index idx_ConstructionElement_Color
    on constructionelement (idColor);

create index idx_ConstructionElement_Construction
    on constructionelement (idConstruction);

create index idx_ConstructionElement_Entity
    on constructionelement (idEntity);

create index idx_ConstructionElement_Entry
    on constructionelement (entry);

create table entry
(
    idEntry     int auto_increment
        primary key,
    entry       varchar(255) charset utf8mb4 null,
    name        varchar(255) charset utf8mb4 null,
    description text charset utf8mb4         null,
    nick        varchar(255) charset utf8mb4 null,
    idLanguage  int(11) unsigned             not null,
    idEntity    int(11) unsigned             null,
    constraint fk_Entry_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_Entry_Language
        foreign key (idLanguage) references language (idLanguage)
)
    charset = utf8mb3;

create index idx_Entry_Entity
    on entry (idEntity);

create index idx_Entry_Entry
    on entry (entry);

create index idx_Entry_Language
    on entry (idLanguage);

create index idx_Entry_Name
    on entry (name);

create index idx_entry_entity_language
    on entry (idEntity, idLanguage);

create table image
(
    idImage    int auto_increment
        primary key,
    name       varchar(45)      null,
    width      int              null,
    height     int              null,
    depth      int              null,
    currentURL varchar(255)     null,
    idLanguage int(11) unsigned not null,
    constraint fk_image_language1
        foreign key (idLanguage) references language (idLanguage)
)
    collate = utf8mb4_bin;

create table dataset_image
(
    idDataset int not null,
    idImage   int not null,
    primary key (idDataset, idImage),
    constraint fk_dataset_has_image_dataset1
        foreign key (idDataset) references dataset (idDataset),
    constraint fk_dataset_has_image_image1
        foreign key (idImage) references image (idImage)
);

create index fk_dataset_has_image_dataset1_idx
    on dataset_image (idDataset);

create index fk_dataset_has_image_image1_idx
    on dataset_image (idImage);

create table document_image
(
    idDocument int(11) unsigned not null,
    idImage    int              not null,
    primary key (idDocument, idImage),
    constraint fk_document_has_image_document1
        foreign key (idDocument) references document (idDocument),
    constraint fk_document_has_image_image1
        foreign key (idImage) references image (idImage)
)
    collate = utf8mb4_bin;

create index fk_document_has_image_document1_idx
    on document_image (idDocument);

create index fk_document_has_image_image1_idx
    on document_image (idImage);

create index idx_document_image
    on document_image (idDocument, idImage);

create index idx_image_document
    on document_image (idImage, idDocument);

create index fk_image_language1_idx
    on image (idLanguage);

create index idx_image_name
    on image (name);

create table layergroup
(
    idLayerGroup int(11) unsigned auto_increment
        primary key,
    name         varchar(255) charset utf8mb4     null,
    type         varchar(255) collate utf8mb4_bin null
)
    charset = utf8mb3;

create table layertype
(
    idLayerType       int(11) unsigned auto_increment
        primary key,
    entry             varchar(255) charset utf8mb4 not null,
    allowsApositional tinyint(1)                   null,
    isAnnotation      tinyint(1)                   null,
    layerOrder        int unsigned default 0       null,
    idLayerGroup      int(11) unsigned             not null,
    idEntity          int(11) unsigned             not null,
    constraint fk_LayerType_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_LayerType_LayerGroup
        foreign key (idLayerGroup) references layergroup (idLayerGroup)
)
    collate = utf8mb4_bin;

create table dynamicobject
(
    idDynamicObject int auto_increment
        primary key,
    name            varchar(255)     null,
    startFrame      int              null,
    endFrame        int              null,
    startTime       float            null,
    endTime         float            null,
    status          int              null,
    origin          int              null,
    idLayerType     int(11) unsigned null,
    constraint fk_dynamicobject_layertype1
        foreign key (idLayerType) references layertype (idLayerType)
)
    collate = utf8mb4_bin;

create index fk_dynamicobject_layertype1_idx
    on dynamicobject (idLayerType);

create index idx_dynamicobject_origin
    on dynamicobject (origin, idLayerType);

create table dynamicobject_boundingbox
(
    idDynamicObject int not null,
    idBoundingBox   int not null,
    primary key (idDynamicObject, idBoundingBox),
    constraint fk_dynamicobject_has_boundingbox_boundingbox1
        foreign key (idBoundingBox) references boundingbox (idBoundingBox),
    constraint fk_dynamicobject_has_boundingbox_dynamicobject1
        foreign key (idDynamicObject) references dynamicobject (idDynamicObject)
)
    collate = utf8mb4_bin;

create index fk_dynamicobject_has_boundingbox_boundingbox1_idx
    on dynamicobject_boundingbox (idBoundingBox);

create index fk_dynamicobject_has_boundingbox_dynamicobject1_idx
    on dynamicobject_boundingbox (idDynamicObject);

create table genericlabel
(
    idGenericLabel int(11) unsigned auto_increment
        primary key,
    name           varchar(255) charset utf8mb4  not null,
    definition     varchar(4000) charset utf8mb4 null,
    example        text charset utf8mb4          null,
    idEntity       int(11) unsigned              not null,
    idColor        int(11) unsigned              not null,
    idLanguage     int(11) unsigned              not null,
    idLayerType    int(11) unsigned              null,
    constraint fk_GenericLabel_Color
        foreign key (idColor) references color (idColor),
    constraint fk_GenericLabel_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_GenericLabel_Language
        foreign key (idLanguage) references language (idLanguage),
    constraint fk_GenericLabel_Layertype1
        foreign key (idLayerType) references layertype (idLayerType)
)
    collate = utf8mb4_bin;

create index idx_GenericLabel_Color
    on genericlabel (idColor);

create index idx_GenericLabel_Entity
    on genericlabel (idEntity);

create index idx_GenericLabel_Language
    on genericlabel (idLanguage);

create index idx_GenericLabel_LayerYype
    on genericlabel (idLayerType);

create index idx_GenericLabel_Name
    on genericlabel (name);

create index idx_LayerType_Entity
    on layertype (idEntity);

create index idx_LayerType_Entry
    on layertype (entry);

create index idx_LayerType_LayerGroup
    on layertype (idLayerGroup);

create table lexicon_group
(
    idLexiconGroup int auto_increment
        primary key,
    name           varchar(45) charset utf8mb4 null,
    idEntity       int(11) unsigned            not null,
    constraint fk_lexicongroup_entity1
        foreign key (idEntity) references entity (idEntity)
)
    charset = utf8mb3;

create index fk_lexicongroup_entity1_idx
    on lexicon_group (idEntity);

create index idx_lexicongroup_name
    on lexicon_group (name);

create table lome_result
(
    idLOMEResult       int(11) unsigned auto_increment
        primary key,
    position           varchar(45) charset utf8mb4 null,
    value              float                       null,
    idFrame            int(11) unsigned            not null,
    idDocumentSentence int                         null,
    constraint fk_lome_result_frame1
        foreign key (idFrame) references frame (idFrame)
)
    charset = utf8mb3;

create index fk_lome_result_frame1_idx
    on lome_result (idFrame);

create table originmm
(
    idOriginMM int auto_increment
        primary key,
    origin     varchar(255) null
)
    collate = utf8mb4_bin;

create table paragraph
(
    idParagraph   int(11) unsigned auto_increment
        primary key,
    documentOrder int              null,
    idDocument    int(11) unsigned not null,
    constraint fk_Paragraph_Document
        foreign key (idDocument) references document (idDocument)
)
    collate = utf8mb4_bin;

create index idx_Paragraph_Document
    on paragraph (idDocument);

create table pos
(
    idPOS    int(11) unsigned auto_increment
        primary key,
    POS      varchar(50) charset utf8mb4  null,
    entry    varchar(255) charset utf8mb4 not null,
    idEntity int(11) unsigned             not null,
    constraint fk_POS_Entity
        foreign key (idEntity) references entity (idEntity)
)
    collate = utf8mb4_bin;

create index idx_POS_Entity
    on pos (idEntity);

create index idx_POS_Entry
    on pos (entry);

create table projectgroup
(
    idProjectGroup int auto_increment
        primary key,
    name           varchar(45) charset utf8mb4 null,
    description    text charset utf8mb4        null
)
    charset = utf8mb3;

create table project
(
    idProject      int auto_increment
        primary key,
    name           varchar(255) null,
    description    text         null,
    idProjectGroup int          null,
    constraint fk_project_projectgroup1
        foreign key (idProjectGroup) references projectgroup (idProjectGroup)
)
    collate = utf8mb4_bin;

create index fk_project_projectgroup1_idx
    on project (idProjectGroup);

create table project_dataset
(
    idProject int                  not null,
    idDataset int                  not null,
    isSource  tinyint(1) default 0 null,
    primary key (idProject, idDataset),
    constraint fk_project_has_dataset_dataset1
        foreign key (idDataset) references dataset (idDataset),
    constraint fk_project_has_dataset_project1
        foreign key (idProject) references project (idProject)
)
    collate = utf8mb4_bin;

create index fk_project_has_dataset_dataset1_idx
    on project_dataset (idDataset);

create index fk_project_has_dataset_project1_idx
    on project_dataset (idProject);

create table ptequivalence
(
    idPTEquivalence int auto_increment
        primary key,
    LangSource      varchar(45) charset utf8mb4 null,
    LangDest        varchar(45) charset utf8mb4 null,
    LabelSource     varchar(45) charset utf8mb4 null,
    LabelDest       varchar(45) charset utf8mb4 null
)
    collate = utf8mb4_bin;

create table qualia
(
    idQualia        int auto_increment
        primary key,
    info            varchar(50) charset utf8mb4  null,
    infoInverse     varchar(50)                  null,
    entry           varchar(255) charset utf8mb4 null,
    idType          int                          not null,
    idEntity        int(11) unsigned             not null,
    idFrame         int(11) unsigned             null,
    idFrameElement1 int(11) unsigned             null,
    idFrameElement2 int(11) unsigned             null,
    constraint fk_Qualia_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_Qualia_Frame
        foreign key (idFrame) references frame (idFrame),
    constraint fk_Qualia_FrameElement1
        foreign key (idFrameElement1) references frameelement (idFrameElement),
    constraint fk_Qualia_FrameElement2
        foreign key (idFrameElement2) references frameelement (idFrameElement)
)
    collate = utf8mb4_bin;

create index Idx_Qualia_Entry
    on qualia (entry);

create index idx_Qualia_Entity
    on qualia (idEntity);

create index idx_Qualia_Frame
    on qualia (idFrame);

create index idx_Qualia_FrameElement1
    on qualia (idFrameElement1);

create index idx_Qualia_FrameElement2
    on qualia (idFrameElement2);

create index idx_Qualia_Type
    on qualia (idType);

create table qualiarelation
(
    idQualiaRelation int auto_increment
        primary key,
    name             varchar(45) charset utf8mb4 null,
    direct           varchar(45) charset utf8mb4 null,
    inverse          varchar(45) charset utf8mb4 null
)
    charset = utf8mb3;

create table qualiastructure
(
    idQualiaStructure int auto_increment
        primary key,
    idFrame           int(11) unsigned not null,
    idQualiaRelation  int              not null,
    constraint fk_qualiastructure_frame1
        foreign key (idFrame) references frame (idFrame),
    constraint fk_qualiastructure_qualiarelation1
        foreign key (idQualiaRelation) references qualiarelation (idQualiaRelation)
)
    charset = utf8mb3;

create table qualiaargument
(
    idQualiaArgument  int auto_increment
        primary key,
    `order`           int default 0               null,
    type              varchar(45) charset utf8mb4 null,
    idQualiaStructure int                         not null,
    idFrameElement    int(11) unsigned            not null,
    constraint fk_qualiaargument_frameelement1
        foreign key (idFrameElement) references frameelement (idFrameElement),
    constraint fk_qualiaargument_qualiastructure1
        foreign key (idQualiaStructure) references qualiastructure (idQualiaStructure)
)
    charset = utf8mb3;

create index fk_qualiaargument_frameelement1_idx
    on qualiaargument (idFrameElement);

create index fk_qualiaargument_qualiastructure1_idx
    on qualiaargument (idQualiaStructure);

create index fk_qualiastructure_frame1_idx
    on qualiastructure (idFrame);

create index fk_qualiastructure_qualiarelation1_idx
    on qualiastructure (idQualiaRelation);

create table relationgroup
(
    idRelationGroup int(11) unsigned auto_increment
        primary key,
    entry           varchar(255) charset utf8mb4 null,
    idEntity        int(11) unsigned             null,
    constraint fk_RelationGroup_Entity
        foreign key (idEntity) references entity (idEntity)
)
    collate = utf8mb4_bin;

create index idx_RelationGroup_Entity
    on relationgroup (idEntity);

create table relationtype
(
    idRelationType  int(11) unsigned auto_increment
        primary key,
    entry           varchar(255) charset utf8mb4 not null,
    nameCanonical   varchar(255)                 null,
    nameDirect      varchar(45)                  null,
    nameInverse     varchar(45)                  null,
    color           char(7)                      null,
    prefix          char(3)                      null,
    idEntity        int(11) unsigned             null,
    idRelationGroup int(11) unsigned             not null,
    constraint fk_RelationType_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_RelationType_RelationGroup
        foreign key (idRelationGroup) references relationgroup (idRelationGroup)
)
    collate = utf8mb4_bin;

create table entityrelation
(
    idEntityRelation int(11) unsigned auto_increment
        primary key,
    idRelationType   int(11) unsigned not null,
    idEntity1        int(11) unsigned not null,
    idEntity2        int(11) unsigned not null,
    idEntity3        int(11) unsigned null,
    idRelation       int(11) unsigned null,
    constraint fk_EntityRelation_Entity1
        foreign key (idEntity1) references entity (idEntity),
    constraint fk_EntityRelation_Entity2
        foreign key (idEntity2) references entity (idEntity),
    constraint fk_EntityRelation_Entity3
        foreign key (idEntity3) references entity (idEntity),
    constraint fk_EntityRelation_RelationType
        foreign key (idRelationType) references relationtype (idRelationType),
    constraint fk_entityrelation_entityrelation
        foreign key (idRelation) references entityrelation (idEntityRelation)
)
    collate = utf8mb4_bin;

create index idx_EntityRelation_Entity1
    on entityrelation (idEntity1);

create index idx_EntityRelation_Entity2
    on entityrelation (idEntity2);

create index idx_EntityRelation_Entity3
    on entityrelation (idEntity3);

create index idx_EntityRelation_EntityRelation
    on entityrelation (idRelation);

create index idx_EntityRelation_RelationType
    on entityrelation (idRelationType);

create index idx_RelationType_Entity
    on relationtype (idEntity);

create index idx_RelationType_RelationGroup
    on relationtype (idRelationGroup);

create index idx_RelationType_entry
    on relationtype (entry);

create table rls_access
(
    idRLSAccess int auto_increment
        primary key,
    user        varchar(45)      null,
    value       tinyint unsigned null
);

create table rls_label
(
    idRLSLabel int auto_increment
        primary key,
    label      varchar(45) null,
    value      tinyint     null
);

create table semantictype
(
    idSemanticType int auto_increment
        primary key,
    entry          varchar(255) charset utf8mb4 not null,
    idEntity       int(11) unsigned             not null,
    idDomain       int(11) unsigned             not null,
    constraint fk_SemanticType_Domain
        foreign key (idDomain) references domain (idDomain),
    constraint fk_SemanticType_Entity
        foreign key (idEntity) references entity (idEntity)
)
    collate = utf8mb4_bin;

create index idx_SemanticType_Domain
    on semantictype (idDomain);

create index idx_SemanticType_Entity
    on semantictype (idEntity);

create index idx_SemanticType_Entry
    on semantictype (entry);

create table sentence
(
    idSentence     int(11) unsigned auto_increment
        primary key,
    text           varchar(4000) charset utf8mb4 null,
    paragraphOrder int                           null,
    idParagraph    int(11) unsigned              null,
    idLanguage     int(11) unsigned              not null,
    idOriginMM     int                           null,
    idRLSLabel     int                           not null,
    constraint fk_Sentence_Language
        foreign key (idLanguage) references language (idLanguage),
    constraint fk_Sentence_Paragraph
        foreign key (idParagraph) references paragraph (idParagraph),
    constraint fk_sentence_originmm1
        foreign key (idOriginMM) references originmm (idOriginMM),
    constraint fk_sentence_rls_label1
        foreign key (idRLSLabel) references rls_label (idRLSLabel)
)
    collate = utf8mb4_bin;

create table document_sentence
(
    idDocumentSentence int auto_increment
        primary key,
    idDocument         int(11) unsigned not null,
    idSentence         int(11) unsigned not null,
    constraint fk_document_sentence_document1
        foreign key (idDocument) references document (idDocument),
    constraint fk_document_sentence_sentence1
        foreign key (idSentence) references sentence (idSentence)
)
    collate = utf8mb4_bin;

create index fk_document_sentence_document1_idx
    on document_sentence (idDocument);

create index fk_document_sentence_sentence1_idx
    on document_sentence (idSentence);

create index idx_document_sentence_document
    on document_sentence (idDocument, idSentence);

create index idx_document_sentence_sentence
    on document_sentence (idSentence, idDocument);

create table image_sentence
(
    idImage    int              not null,
    idSentence int(11) unsigned not null,
    primary key (idImage, idSentence),
    constraint fk_image_has_sentence_image1
        foreign key (idImage) references image (idImage),
    constraint fk_image_has_sentence_sentence1
        foreign key (idSentence) references sentence (idSentence)
)
    collate = utf8mb4_bin;

create index fk_image_has_sentence_image1_idx
    on image_sentence (idImage);

create index fk_image_has_sentence_sentence1_idx
    on image_sentence (idSentence);

create index fk_sentence_originmm1_idx
    on sentence (idOriginMM);

create index fk_sentence_rls_label1_idx
    on sentence (idRLSLabel);

create index idx_Sentence_Language
    on sentence (idLanguage);

create index idx_Sentence_Paragraph
    on sentence (idParagraph);

create table sentence_translation
(
    idSentenceBase        int(11) unsigned not null,
    idSentenceTranslation int(11) unsigned not null,
    primary key (idSentenceBase, idSentenceTranslation),
    constraint fk_Sentence_Translation_Sentence1
        foreign key (idSentenceBase) references sentence (idSentence),
    constraint fk_Sentence_Translation_Sentence2
        foreign key (idSentenceTranslation) references sentence (idSentence)
)
    collate = utf8mb4_bin;

create index idx_Sentence_SentenceBase
    on sentence_translation (idSentenceBase);

create index idx_Sentence_SentenceTranslation
    on sentence_translation (idSentenceTranslation);

create table staticannotationmm
(
    idStaticAnnotationMM     int auto_increment
        primary key,
    idFrameElement           int(11) unsigned null,
    idLemma                  int(11) unsigned null,
    idLU                     int(11) unsigned null,
    idFrame                  int(11) unsigned null,
    idStaticObjectSentenceMM int              not null
)
    collate = utf8mb4_bin;

create index idx_annotationflickr30k_frame
    on staticannotationmm (idFrame);

create index idx_annotationflickr30k_frameelement
    on staticannotationmm (idFrameElement);

create index idx_annotationflickr30k_lemma
    on staticannotationmm (idLemma);

create index idx_annotationflickr30k_lu
    on staticannotationmm (idLU);

create index idx_staticannotationmm_staticobjectsentencemm
    on staticannotationmm (idStaticObjectSentenceMM);

create table staticbboxmm
(
    idStaticBBoxMM   int auto_increment
        primary key,
    x                int null,
    y                int null,
    width            int null,
    height           int null,
    idStaticObjectMM int not null
)
    collate = utf8mb4_bin;

create index idx_staticbboxmm_staticobjectmm
    on staticbboxmm (idStaticObjectMM);

create table staticobject
(
    idStaticObject           int auto_increment
        primary key,
    name                     varchar(255)  null,
    scene                    tinyint(1)    null,
    idFlickr30kEntitiesChain int           null,
    nobndbox                 tinyint(1)    null,
    externalId               int default 0 null
)
    collate = utf8mb4_bin;

create table image_staticobject
(
    idImage        int not null,
    idStaticObject int not null,
    primary key (idImage, idStaticObject),
    constraint fk_image_has_staticobject_image1
        foreign key (idImage) references image (idImage),
    constraint fk_image_has_staticobject_staticobject1
        foreign key (idStaticObject) references staticobject (idStaticObject)
)
    collate = utf8mb4_bin;

create index fk_image_has_staticobject_image1_idx
    on image_staticobject (idImage);

create index fk_image_has_staticobject_staticobject1_idx
    on image_staticobject (idStaticObject);

create index idx_staticobject_idFlickr30EntitiesChain
    on staticobject (idFlickr30kEntitiesChain);

create table staticobject_boundingbox
(
    idStaticObject int not null,
    idBoundingBox  int not null,
    primary key (idStaticObject, idBoundingBox),
    constraint fk_staticobject_has_boundingbox_boundingbox1
        foreign key (idBoundingBox) references boundingbox (idBoundingBox),
    constraint fk_staticobject_has_boundingbox_staticobject1
        foreign key (idStaticObject) references staticobject (idStaticObject)
)
    collate = utf8mb4_bin;

create index fk_staticobject_has_boundingbox_boundingbox1_idx
    on staticobject_boundingbox (idBoundingBox);

create index fk_staticobject_has_boundingbox_staticobject1_idx
    on staticobject_boundingbox (idStaticObject);

create table staticobjectmm
(
    idStaticObjectMM         int auto_increment
        primary key,
    scene                    int null,
    nobndbox                 int null,
    idFlickr30kEntitiesChain int null,
    idImageMM                int null
)
    collate = utf8mb4_bin;

create index idx_staticobjectmm_idFlickr30kEntitiesChain
    on staticobjectmm (idFlickr30kEntitiesChain);

create index idx_staticobjectmm_imagemm
    on staticobjectmm (idImageMM);

create table staticobjectsentencemm
(
    idStaticObjectSentenceMM int auto_increment
        primary key,
    name                     varchar(2555) charset utf8mb4 null,
    startWord                int                           null,
    endWord                  int                           null,
    idStaticObjectMM         int                           not null,
    idStaticSentenceMM       int                           not null
)
    charset = utf8mb3;

create index idx_staticobjectsentencemm_staticobjectmm
    on staticobjectsentencemm (idStaticObjectMM);

create index idx_staticobjectsentencemm_staticsentencemm
    on staticobjectsentencemm (idStaticSentenceMM);

create table staticsentencemm
(
    idStaticSentenceMM int auto_increment
        primary key,
    idFlickr30k        int              null,
    idSentence         int(11) unsigned not null,
    idImageMM          int              not null,
    idDocument         int(11) unsigned not null
)
    collate = utf8mb4_bin;

create index idx_sentenceflickr30k_document
    on staticsentencemm (idDocument);

create index idx_sentenceflickr30k_imagemm
    on staticsentencemm (idImageMM);

create index idx_sentenceflickr30k_sentence
    on staticsentencemm (idSentence);

create table taskgroup
(
    idTaskGroup int auto_increment
        primary key,
    name        varchar(45) null,
    description text        null
)
    charset = utf8mb3;

create table task
(
    idTask      int auto_increment
        primary key,
    size        int                          null,
    isActive    tinyint(1)                   null,
    type        varchar(45) charset utf8mb4  null,
    createdAt   timestamp                    null,
    name        varchar(255) charset utf8mb4 null,
    description text charset utf8mb4         null,
    idTaskGroup int                          not null,
    idProject   int                          not null,
    constraint fk_task_project1
        foreign key (idProject) references project (idProject),
    constraint fk_task_taskgroup1
        foreign key (idTaskGroup) references taskgroup (idTaskGroup)
)
    collate = utf8mb4_bin;

create index fk_task_project1_idx
    on task (idProject);

create index fk_task_taskgroup1_idx
    on task (idTaskGroup);

create table timespan
(
    idTimeSpan int auto_increment
        primary key,
    startTime  float null,
    endTime    float null
)
    collate = utf8mb4_bin;

create table cosine_node
(
    idCosineNode    int auto_increment
        primary key,
    name            varchar(255) charset utf8mb4 null,
    type            char(3) charset utf8mb4      null,
    idFrame         int(11) unsigned             null,
    idDynamicObject int                          null,
    idTimespan      int                          null,
    idSentence      int(11) unsigned             null,
    idDocument      int(11) unsigned             null,
    constraint fk_cosine_node_document1
        foreign key (idDocument) references document (idDocument),
    constraint fk_cosine_node_dynamicobject1
        foreign key (idDynamicObject) references dynamicobject (idDynamicObject),
    constraint fk_cosine_node_frame1
        foreign key (idFrame) references frame (idFrame),
    constraint fk_cosine_node_sentence1
        foreign key (idSentence) references sentence (idSentence),
    constraint fk_cosine_node_timespan1
        foreign key (idTimespan) references timespan (idTimeSpan)
)
    charset = utf8mb3;

create table cosine_link
(
    idCosineLink       int auto_increment
        primary key,
    value              float                   null,
    type               char(2) charset utf8mb4 null,
    idCosineNodeSource int                     not null,
    idCosineNodeTarget int                     not null,
    constraint fk_cosine_link_cosine_node1
        foreign key (idCosineNodeSource) references cosine_node (idCosineNode),
    constraint fk_cosine_link_cosine_node2
        foreign key (idCosineNodeTarget) references cosine_node (idCosineNode)
)
    charset = utf8mb3;

create index fk_cosine_link_cosine_node1_idx
    on cosine_link (idCosineNodeSource);

create index fk_cosine_link_cosine_node2_idx
    on cosine_link (idCosineNodeTarget);

create index fk_cosine_node_document1_idx
    on cosine_node (idDocument);

create index fk_cosine_node_dynamicobject1_idx
    on cosine_node (idDynamicObject);

create index fk_cosine_node_frame1_idx
    on cosine_node (idFrame);

create index fk_cosine_node_sentence1_idx
    on cosine_node (idSentence);

create index fk_cosine_node_timespan1_idx
    on cosine_node (idTimespan);

create table sentence_timespan
(
    idSentence int(11) unsigned not null,
    idTimeSpan int              not null,
    primary key (idSentence, idTimeSpan),
    constraint fk_sentence_has_timespan_sentence1
        foreign key (idSentence) references sentence (idSentence),
    constraint fk_sentence_has_timespan_timespan1
        foreign key (idTimeSpan) references timespan (idTimeSpan)
)
    collate = utf8mb4_bin;

create index fk_sentence_has_timespan_sentence1_idx
    on sentence_timespan (idSentence);

create index fk_sentence_has_timespan_timespan1_idx
    on sentence_timespan (idTimeSpan);

create table tokenizedsentence
(
    idTokenizedSentence int              not null
        primary key,
    createdAt           timestamp        null,
    tokenizer           varchar(45)      null,
    idSentence          int(11) unsigned not null,
    constraint fk_tokenizedsentence_sentence
        foreign key (idSentence) references sentence (idSentence)
)
    collate = utf8mb4_bin;

create table token
(
    idToken             int auto_increment
        primary key,
    token               varchar(45) null,
    idTokenizedSentence int         not null,
    constraint fk_token_tokenizedsentence1
        foreign key (idTokenizedSentence) references tokenizedsentence (idTokenizedSentence)
)
    collate = utf8mb4_bin;

create index fk_token_tokenizedsentence1_idx
    on token (idTokenizedSentence);

create index fk_tokenizedsentence_sentence1_idx
    on tokenizedsentence (idSentence);

create table topframe
(
    idTopFrame    int auto_increment
        primary key,
    frameBase     varchar(255) null,
    frameTop      varchar(255) null,
    frameCategory varchar(45)  null,
    score         float        null
)
    collate = utf8mb4_bin;

create index idx_TopFrame_frameBase
    on topframe (frameBase);

create index idx_TopFrame_frameTop
    on topframe (frameTop);

create table typegroup
(
    idTypeGroup int auto_increment
        primary key,
    entry       varchar(255)     null,
    idEntity    int(11) unsigned not null,
    constraint fk_typegroup_entity1
        foreign key (idEntity) references entity (idEntity)
)
    charset = utf8mb3;

create table type
(
    idType      int auto_increment
        primary key,
    entry       varchar(255) charset utf8mb4 not null,
    info        varchar(50) charset utf8mb4  null,
    flag        tinyint(1)                   null,
    idColor     int(11) unsigned             not null,
    idEntity    int(11) unsigned             not null,
    idTypeGroup int                          null,
    constraint fk_TypeInstance_Color
        foreign key (idColor) references color (idColor),
    constraint fk_TypeInstance_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_typeinstance_typegroup1
        foreign key (idTypeGroup) references typegroup (idTypeGroup)
)
    collate = utf8mb4_bin;

create index fk_typeinstance_typegroup1_idx
    on type (idTypeGroup);

create index idx_TypeInstance_Color
    on type (idColor);

create index idx_TypeInstance_Entity
    on type (idEntity);

create index idx_TypeInstance_Entry
    on type (entry);

create index fk_typegroup_entity1_idx
    on typegroup (idEntity);

create table udfeature
(
    idUDFeature    int(11) unsigned auto_increment
        primary key,
    name           varchar(255)                 null,
    info           varchar(255) charset utf8mb4 null,
    idEntity       int(11) unsigned             not null,
    idTypeInstance int                          not null,
    constraint fk_udfeature_Entity
        foreign key (idEntity) references entity (idEntity)
)
    collate = utf8mb4_bin;

create index idx_UDFeature_Entity1_idx
    on udfeature (idEntity);

create index idx_UDFeature_TypeInstance1_idx
    on udfeature (idTypeInstance);

create table udpos
(
    idUDPOS  int(11) unsigned auto_increment
        primary key,
    POS      varchar(50) charset utf8mb4  null,
    entry    varchar(255) charset utf8mb4 null,
    idEntity int(11) unsigned             not null,
    constraint fk_UDPOS_Entity
        foreign key (idEntity) references entity (idEntity)
)
    collate = utf8mb4_bin;

create table lemma
(
    idLemma    int(11) unsigned auto_increment
        primary key,
    name       varchar(255) charset utf8mb4 null,
    idOld      int                          null,
    version    int                          null,
    idPOS      int(11) unsigned             not null,
    idLanguage int(11) unsigned             not null,
    idEntity   int(11) unsigned             null,
    idUDPOS    int(11) unsigned             null,
    constraint fk_Lemma_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_Lemma_Language
        foreign key (idLanguage) references language (idLanguage),
    constraint fk_Lemma_POS
        foreign key (idPOS) references pos (idPOS),
    constraint fk_Lemma_UDPOS
        foreign key (idUDPOS) references udpos (idUDPOS)
)
    charset = utf8mb3;

create index idx_Lemma_Entity
    on lemma (idEntity);

create index idx_Lemma_Language
    on lemma (idLanguage);

create index idx_Lemma_POS
    on lemma (idPOS);

create index idx_Lemma_UDPOS
    on lemma (idUDPOS);

create index idx_Lemma_name
    on lemma (name);

create index idx_Lemma_version
    on lemma (version);

create table lexeme
(
    idLexeme   int(11) unsigned auto_increment
        primary key,
    name       varchar(255) charset utf8mb4 null,
    idPOS      int(11) unsigned             not null,
    idLanguage int(11) unsigned             not null,
    idEntity   int(11) unsigned             null,
    idUDPOS    int(11) unsigned             null,
    constraint fk_Lexeme_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_Lexeme_Language
        foreign key (idLanguage) references language (idLanguage),
    constraint fk_Lexeme_POS
        foreign key (idPOS) references pos (idPOS),
    constraint fk_Lexeme_UDPOS
        foreign key (idUDPOS) references udpos (idUDPOS)
)
    charset = utf8mb3;

create index fk_Lexeme_UDPOS_idx
    on lexeme (idUDPOS);

create index idx_Lexeme_Entity
    on lexeme (idEntity);

create index idx_Lexeme_Language
    on lexeme (idLanguage);

create index idx_Lexeme_POS
    on lexeme (idPOS);

create index idx_Lexeme_name
    on lexeme (name);

create index idx_Lexeme_name_lang_pos
    on lexeme (name, idLanguage, idPOS);

create table lexicon
(
    idLexicon      int(11) unsigned auto_increment
        primary key,
    form           varchar(255)         null,
    idLexiconGroup int                  not null,
    idEntity       int(11) unsigned     null,
    idPOS          int(11) unsigned     null,
    idUDPOS        int(11) unsigned     null,
    idLanguage     int(11) unsigned     not null,
    idLemma        int(11) unsigned     null,
    isMWE          tinyint(1) default 0 null,
    constraint fk_lexicon_entity1
        foreign key (idEntity) references entity (idEntity),
    constraint fk_lexicon_language1
        foreign key (idLanguage) references language (idLanguage),
    constraint fk_lexicon_lemma1
        foreign key (idLemma) references lemma (idLemma),
    constraint fk_lexicon_lexicongroup1
        foreign key (idLexiconGroup) references lexicon_group (idLexiconGroup),
    constraint fk_lexicon_pos1
        foreign key (idPOS) references pos (idPOS),
    constraint fk_lexicon_udpos1
        foreign key (idUDPOS) references udpos (idUDPOS)
);

create index fk_lexicon_entity1_idx
    on lexicon (idEntity);

create index fk_lexicon_language1_idx
    on lexicon (idLanguage);

create index fk_lexicon_lemma1_idx
    on lexicon (idLemma);

create index fk_lexicon_lexicongroup1_idx
    on lexicon (idLexiconGroup);

create index fk_lexicon_pos1_idx
    on lexicon (idPOS);

create index fk_lexicon_udpos1_idx
    on lexicon (idUDPOS);

create index idx_lexicon_form
    on lexicon (form);

create table lexicon_expression
(
    idLexiconExpression int auto_increment
        primary key,
    head                tinyint(1)       null,
    breakBefore         tinyint(1)       null,
    position            int unsigned     null,
    idLexicon           int(11) unsigned not null,
    idExpression        int(11) unsigned not null,
    constraint fk_lexiconmwe_lexicon1
        foreign key (idExpression) references lexicon (idLexicon),
    constraint fk_lexiconmwe_lexicon2
        foreign key (idLexicon) references lexicon (idLexicon)
)
    charset = utf8mb3;

create index fk_lexiconmwe_lexicon1_idx
    on lexicon_expression (idExpression);

create index fk_lexiconmwe_lexicon2_idx
    on lexicon_expression (idLexicon);

create table lexicon_feature
(
    idLexicon   int(11) unsigned not null,
    idUDFeature int(11) unsigned not null,
    primary key (idLexicon, idUDFeature),
    constraint fk_lexicon_has_udfeature_lexicon1
        foreign key (idLexicon) references lexicon (idLexicon),
    constraint fk_lexicon_has_udfeature_udfeature1
        foreign key (idUDFeature) references udfeature (idUDFeature)
)
    charset = utf8mb3;

create index fk_lexicon_has_udfeature_lexicon1_idx
    on lexicon_feature (idLexicon);

create index fk_lexicon_has_udfeature_udfeature1_idx
    on lexicon_feature (idUDFeature);

create table lu
(
    idLU             int(11) unsigned auto_increment
        primary key,
    name             varchar(255) charset utf8mb4  null,
    senseDescription varchar(4000) charset utf8mb4 null,
    active           tinyint(1)                    null,
    importNum        int unsigned                  null,
    incorporatedFE   int unsigned                  null,
    bff              int                           null,
    bffOther         varchar(4000) charset utf8mb4 null,
    idEntity         int(11) unsigned              not null,
    idLemma          int(11) unsigned              null,
    idFrame          int(11) unsigned              not null,
    idForm           int                           null,
    idLexicon        int unsigned                  not null,
    constraint fk_LU_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_LU_Frame
        foreign key (idFrame) references frame (idFrame),
    constraint fk_LU_Lemma
        foreign key (idLemma) references lemma (idLemma),
    constraint fk_lu_lexicon1
        foreign key (idLexicon) references lexicon (idLexicon)
)
    collate = utf8mb4_bin;

create table annotationset
(
    idAnnotationSet    int(11) unsigned auto_increment
        primary key,
    idSentence         int(11) unsigned null,
    idAnnotationStatus int              not null,
    idLU               int(11) unsigned null,
    idConstruction     int              null,
    lome               char             null,
    idFrame            int(11) unsigned null,
    idLexicon          int(11) unsigned null,
    constraint fk_annotationset_construction1
        foreign key (idConstruction) references construction (idConstruction),
    constraint fk_annotationset_frame1
        foreign key (idFrame) references frame (idFrame),
    constraint fk_annotationset_lexicon1
        foreign key (idLexicon) references lexicon (idLexicon),
    constraint fk_annotationset_lu1
        foreign key (idLU) references lu (idLU),
    constraint fk_annotationset_sentence
        foreign key (idSentence) references sentence (idSentence)
)
    collate = utf8mb4_bin;

create index fk_annotationset_construction1_idx
    on annotationset (idConstruction);

create index fk_annotationset_frame1_idx
    on annotationset (idFrame);

create index fk_annotationset_lexicon1_idx
    on annotationset (idLexicon);

create index fk_annotationset_lu1_idx
    on annotationset (idLU);

create index idx_AnnotationSet_Sentence
    on annotationset (idSentence);

create index idx_AnnotationSet_Type
    on annotationset (idAnnotationStatus);

create table ascomments
(
    idASComments         int auto_increment
        primary key,
    ExtraThematicFE      varchar(255) charset utf8mb4  null,
    ExtraThematicFEOther varchar(255) charset utf8mb4  null,
    Comment              varchar(4000) charset utf8mb4 null,
    Construction         varchar(255) charset utf8mb4  null,
    idAnnotationSet      int(11) unsigned              not null,
    constraint fk_ASComments_AnnotationSet1
        foreign key (idAnnotationSet) references annotationset (idAnnotationSet)
)
    collate = utf8mb4_bin;

create index idx_ASComment_idAnnotationSet
    on ascomments (idAnnotationSet);

create table layer
(
    idLayer         int(11) unsigned auto_increment
        primary key,
    rank            int              null,
    idAnnotationSet int(11) unsigned not null,
    idLayerType     int(11) unsigned not null,
    constraint fk_Layer_AnnotationSet
        foreign key (idAnnotationSet) references annotationset (idAnnotationSet),
    constraint fk_Layer_LayerType
        foreign key (idLayerType) references layertype (idLayerType)
)
    collate = utf8mb4_bin;

create index idx_Layer_AnnotationSet
    on layer (idAnnotationSet);

create index idx_Layer_LayerType
    on layer (idLayerType);

create table lome_resultfe
(
    idLOMEResultFE int auto_increment
        primary key,
    start          smallint                null,
    end            smallint                null,
    word           text charset utf8mb4    null,
    type           char(2) charset utf8mb4 null,
    idSpan         int unsigned            null,
    idLU           int(11) unsigned        null,
    idFrame        int(11) unsigned        null,
    idFrameElement int(11) unsigned        null,
    idSentence     int(11) unsigned        null,
    constraint fk_lome_resultfe_frame1
        foreign key (idFrame) references frame (idFrame),
    constraint fk_lome_resultfe_frameelement1
        foreign key (idFrameElement) references frameelement (idFrameElement),
    constraint fk_lome_resultfe_lu1
        foreign key (idLU) references lu (idLU),
    constraint fk_lome_resultfe_sentence1
        foreign key (idSentence) references sentence (idSentence)
)
    charset = utf8mb3;

create index fk_lome_resultfe_frame1_idx
    on lome_resultfe (idFrame);

create index fk_lome_resultfe_frameelement1_idx
    on lome_resultfe (idFrameElement);

create index fk_lome_resultfe_lu1_idx
    on lome_resultfe (idLU);

create index fk_lome_resultfe_sentence1_idx
    on lome_resultfe (idSentence);

create index fk_lu_lexicon1_idx
    on lu (idLexicon);

create index idx_LU_Entity
    on lu (idEntity);

create index idx_LU_Form
    on lu (idForm);

create index idx_LU_Frame
    on lu (idFrame);

create index idx_LU_Lemma
    on lu (idLemma);

create table luequivalence
(
    idLUEquivalence int auto_increment
        primary key,
    score           decimal(10, 9)   null,
    variance        decimal(10, 9)   null,
    idLUSource      int(11) unsigned not null,
    idLUTarget      int(11) unsigned not null,
    constraint fk_LUEquivalence_LU1
        foreign key (idLUSource) references lu (idLU),
    constraint fk_LUEquivalence_LU2
        foreign key (idLUTarget) references lu (idLU)
)
    comment '		' charset = dec8;

create index fk_LUEquivalence_LU1_idx
    on luequivalence (idLUSource);

create index fk_LUEquivalence_LU2_idx
    on luequivalence (idLUTarget);

create table pos_udpos
(
    idPOS   int(11) unsigned not null,
    idUDPOS int(11) unsigned not null,
    primary key (idPOS, idUDPOS),
    constraint fk_POS_has_UDPOS_POS1
        foreign key (idPOS) references pos (idPOS),
    constraint fk_POS_has_UDPOS_UDPOS1
        foreign key (idUDPOS) references udpos (idUDPOS)
)
    collate = utf8mb4_bin;

create index fk_POS_has_UDPOS_POS1_idx
    on pos_udpos (idPOS);

create index fk_POS_has_UDPOS_UDPOS1_idx
    on pos_udpos (idUDPOS);

create table qualialu
(
    idQualiaLU        int auto_increment
        primary key,
    idQualiaStructure int              not null,
    idLU1             int(11) unsigned not null,
    idLU2             int(11) unsigned not null,
    constraint fk_qualialu_lu1
        foreign key (idLU1) references lu (idLU),
    constraint fk_qualialu_lu2
        foreign key (idLU2) references lu (idLU),
    constraint fk_qualialu_qualiastructure1
        foreign key (idQualiaStructure) references qualiastructure (idQualiaStructure)
)
    charset = utf8mb3;

create index fk_qualialu_lu1_idx
    on qualialu (idLU1);

create index fk_qualialu_lu2_idx
    on qualialu (idLU2);

create index fk_qualialu_qualiastructure1_idx
    on qualialu (idQualiaStructure);

create table textspan
(
    idTextSpan          int auto_increment
        primary key,
    startChar           int              null,
    endChar             int              null,
    multi               tinyint(1)       null,
    idLayer             int(11) unsigned null,
    idInstantiationType int              null,
    startWord           int              null,
    endWord             int              null,
    idSentence          int(11) unsigned not null,
    idStaticObject      int              null,
    externalId          int default 0    null,
    constraint fk_textspan_layer1
        foreign key (idLayer) references layer (idLayer),
    constraint fk_textspan_sentence1
        foreign key (idSentence) references sentence (idSentence),
    constraint fk_textspan_staticobject1
        foreign key (idStaticObject) references staticobject (idStaticObject)
)
    collate = utf8mb4_bin;

create index fk_textspan_layer1_idx
    on textspan (idLayer);

create index fk_textspan_sentence1_idx
    on textspan (idSentence);

create index fk_textspan_staticobject1_idx
    on textspan (idStaticObject);

create index fk_textspan_typeinstance1_idx
    on textspan (idInstantiationType);

create index fk_UDPOS_Entity_idx
    on udpos (idEntity);

create index idx_udpos_pos
    on udpos (POS);

create table udrelation
(
    idUDRelation   int(11) unsigned auto_increment
        primary key,
    info           varchar(255) charset utf8mb4 null,
    idEntity       int(11) unsigned             not null,
    idTypeInstance int                          not null
)
    collate = utf8mb4_bin;

create index fk_UDRelation_Entity1_idx
    on udrelation (idEntity);

create index fk_UDRelation_TypeInstance1_idx
    on udrelation (idTypeInstance);

create table user
(
    idUser         int auto_increment
        primary key,
    login          varchar(50) charset utf8mb4  null,
    passMD5        varchar(255) charset utf8mb4 null,
    active         tinyint(1)                   null,
    status         char charset utf8mb4         null,
    name           varchar(255) charset utf8mb4 null,
    email          varchar(255) charset utf8mb4 null,
    auth0IdUser    varchar(255) charset utf8mb4 null,
    auth0CreatedAt varchar(50) charset utf8mb4  null,
    lastLogin      timestamp                    null,
    idLanguage     int(11) unsigned default 1   not null,
    config         text                         null,
    constraint fk_User_Language
        foreign key (idLanguage) references language (idLanguage)
)
    collate = utf8mb4_bin;

create table annotationcomment
(
    idAnnotationComment int auto_increment
        primary key,
    comment             text charset utf8mb4 null,
    createdAt           timestamp            null,
    updatedAt           timestamp            null,
    idDynamicObject     int                  null,
    idStaticObject      int                  null,
    idAnnotationSet     int(11) unsigned     null,
    idUser              int                  null,
    constraint fk_annotationcomment_annotationset1
        foreign key (idAnnotationSet) references annotationset (idAnnotationSet),
    constraint fk_annotationcomment_dynamicobject1
        foreign key (idDynamicObject) references dynamicobject (idDynamicObject),
    constraint fk_annotationcomment_staticobject1
        foreign key (idStaticObject) references staticobject (idStaticObject),
    constraint fk_annotationcomment_user1
        foreign key (idUser) references user (idUser)
)
    charset = utf8mb3;

create index fk_annotationcomment_annotationset1_idx
    on annotationcomment (idAnnotationSet);

create index fk_annotationcomment_dynamicobject1_idx
    on annotationcomment (idDynamicObject);

create index fk_annotationcomment_staticobject1_idx
    on annotationcomment (idStaticObject);

create index fk_annotationcomment_user1_idx
    on annotationcomment (idUser);

create table lucandidate
(
    idLUCandidate      int(11) unsigned auto_increment
        primary key,
    name               varchar(255) charset utf8mb4  null,
    senseDescription   varchar(4000) charset utf8mb4 null,
    frameCandidate     varchar(45)                   null,
    discussion         text                          null,
    idLemma            int(11) unsigned              null,
    idFrame            int(11) unsigned              null,
    idDocument         int(11) unsigned              null,
    idBoundingBox      int(11) unsigned              null,
    idDocumentSentence int(11) unsigned              null,
    incorporatedFE     int(11) unsigned              null,
    idUser             int                           not null,
    createdAt          timestamp                     null,
    idLexicon          int(11) unsigned              not null,
    constraint fk_LU_Frame0
        foreign key (idFrame) references frame (idFrame),
    constraint fk_LU_Lemma0
        foreign key (idLemma) references lemma (idLemma),
    constraint fk_lucandidate_lexicon1
        foreign key (idLexicon) references lexicon (idLexicon),
    constraint fk_lucandidate_user2
        foreign key (idUser) references user (idUser)
)
    collate = utf8mb4_bin;

create index fk_lucandidate_lexicon1_idx
    on lucandidate (idLexicon);

create index fk_lucandidate_user2_idx
    on lucandidate (idUser);

create index idx_LU_Frame
    on lucandidate (idFrame);

create index idx_LU_Lemma
    on lucandidate (idLemma);

create table message
(
    idMessage   int auto_increment
        primary key,
    text        text charset utf8mb4        null,
    active      tinyint(1)                  null,
    createdAt   datetime                    null,
    accessedAt  datetime                    null,
    dismissedAt datetime                    null,
    class       varchar(45) charset utf8mb4 null,
    idUserFrom  int                         not null,
    idUserTo    int                         not null,
    constraint fk_message_user1
        foreign key (idUserFrom) references user (idUser),
    constraint fk_message_user2
        foreign key (idUserTo) references user (idUser)
)
    charset = utf8mb3;

create index fk_message_user1_idx
    on message (idUserFrom);

create index fk_message_user2_idx
    on message (idUserTo);

create table project_manager
(
    idProject int not null,
    idUser    int not null,
    primary key (idProject, idUser),
    constraint fk_project_has_user_project1
        foreign key (idProject) references project (idProject),
    constraint fk_project_has_user_user1
        foreign key (idUser) references user (idUser)
)
    collate = utf8mb4_bin;

create index fk_project_has_user_project1_idx
    on project_manager (idProject);

create index fk_project_has_user_user1_idx
    on project_manager (idUser);

create table task_manager
(
    idTask int not null,
    idUser int not null,
    primary key (idTask, idUser),
    constraint fk_task_has_user_task1
        foreign key (idTask) references task (idTask),
    constraint fk_task_has_user_user1
        foreign key (idUser) references user (idUser)
)
    collate = utf8mb4_bin;

create index fk_task_has_user_task1_idx
    on task_manager (idTask);

create index fk_task_has_user_user1_idx
    on task_manager (idUser);

create table timeline
(
    idTimeline int auto_increment
        primary key,
    tlDateTime datetime                    null,
    author     varchar(50) charset utf8mb4 null,
    operation  char charset utf8mb4        null,
    tableName  varchar(45)                 null,
    id         int                         null,
    idUser     int                         null,
    constraint fk_Timeline_User
        foreign key (idUser) references user (idUser)
)
    collate = utf8mb4_bin;

create index idx_Timeline_Author
    on timeline (author);

create index idx_Timeline_DateTime
    on timeline (tlDateTime);

create index idx_Timeline_Operation
    on timeline (operation);

create index idx_Timeline_Table
    on timeline (tableName, id);

create index idx_Timeline_User
    on timeline (idUser);

create index idx_User_Language
    on user (idLanguage);

create table user_group
(
    idUser  int not null,
    idGroup int not null,
    primary key (idUser, idGroup),
    constraint fk_group_user
        foreign key (idGroup) references `group` (idGroup),
    constraint fk_user_group
        foreign key (idUser) references user (idUser)
)
    collate = utf8mb4_bin;

create index idx_user_group_group
    on user_group (idGroup);

create index idx_user_group_user
    on user_group (idUser);

create table userannotation
(
    idUserAnnotation int auto_increment
        primary key,
    idUser           int              not null,
    idSentenceStart  int(11) unsigned not null,
    idSentenceEnd    int(11) unsigned not null,
    idDocument       int(11) unsigned null,
    constraint fk_UserAnnotation_Document
        foreign key (idDocument) references document (idDocument),
    constraint fk_UserAnnotation_User
        foreign key (idUser) references user (idUser)
)
    collate = utf8mb4_bin;

create index idx_UserAnnotation_Document
    on userannotation (idDocument);

create index idx_UserAnnotation_Sentence1
    on userannotation (idSentenceStart);

create index idx_UserAnnotation_Sentence2
    on userannotation (idSentenceEnd);

create index idx_UserAnnotation_User
    on userannotation (idUser);

create table usertask
(
    idUserTask int auto_increment
        primary key,
    idUser     int        not null,
    idTask     int        not null,
    isIgnore   tinyint(1) null,
    isActive   tinyint(1) null,
    createdAt  timestamp  null,
    constraint fk_user_has_task_task
        foreign key (idTask) references task (idTask),
    constraint fk_user_has_task_user
        foreign key (idUser) references user (idUser)
)
    collate = utf8mb4_bin;

create table annotation
(
    idAnnotation    int auto_increment
        primary key,
    idEntity        int(11) unsigned not null,
    idUserTask      int              null,
    idTextSpan      int              null,
    idStaticObject  int              null,
    idDynamicObject int              null,
    constraint fk_annotation_dynamicobject1
        foreign key (idDynamicObject) references dynamicobject (idDynamicObject),
    constraint fk_annotation_staticobject1
        foreign key (idStaticObject) references staticobject (idStaticObject),
    constraint fk_annotation_textspan1
        foreign key (idTextSpan) references textspan (idTextSpan),
    constraint fk_annotation_usertask1
        foreign key (idUserTask) references usertask (idUserTask),
    constraint fk_annotationobjectentity_entity1
        foreign key (idEntity) references entity (idEntity)
)
    collate = utf8mb4_bin;

create index fk_annotation_dynamicobject1_idx
    on annotation (idDynamicObject);

create index fk_annotation_staticobject1_idx
    on annotation (idStaticObject);

create index fk_annotation_textspan1_idx
    on annotation (idTextSpan);

create index fk_annotation_usertask1_idx
    on annotation (idUserTask);

create index idx_annotation_entity_dynamicobject
    on annotation (idDynamicObject, idEntity);

create index idx_annotation_entity_staticobject
    on annotation (idEntity, idStaticObject);

create index idx_annotation_entity_textspan
    on annotation (idEntity, idTextSpan);

create index idx_annotationobjectentity_entity
    on annotation (idEntity);

create index idx_usertask_task
    on usertask (idTask);

create index idx_usertask_user
    on usertask (idUser);

create table usertask_document
(
    idUserTaskDocument int auto_increment
        primary key,
    idUserTask         int              not null,
    idDocument         int(11) unsigned null,
    idCorpus           int(11) unsigned null,
    constraint fk_document_has_usertask_document1
        foreign key (idDocument) references document (idDocument),
    constraint fk_document_has_usertask_usertask1
        foreign key (idUserTask) references usertask (idUserTask),
    constraint fk_usertask_document_corpus1
        foreign key (idCorpus) references corpus (idCorpus)
)
    collate = utf8mb4_bin;

create index fk_document_has_usertask_document1_idx
    on usertask_document (idDocument);

create index fk_document_has_usertask_usertask1_idx
    on usertask_document (idUserTask);

create index fk_usertask_document_corpus1_idx
    on usertask_document (idCorpus);

create table valencelu
(
    idValenceLU int auto_increment
        primary key,
    idLanguage  int(11) unsigned not null,
    idFrame     int(11) unsigned not null,
    idLU        int(11) unsigned not null,
    constraint fk_ValenceLU_Frame1
        foreign key (idFrame) references frame (idFrame),
    constraint fk_ValenceLU_LU1
        foreign key (idLU) references lu (idLU),
    constraint fk_ValenceLU_Language1
        foreign key (idLanguage) references language (idLanguage)
)
    charset = utf8mb3;

create index idx_ValenceLU_Frame
    on valencelu (idFrame);

create index idx_ValenceLU_LU
    on valencelu (idLU);

create index idx_ValenceLU_Language
    on valencelu (idLanguage);

create table valencepattern
(
    idValencePattern int auto_increment
        primary key,
    countPattern     int default 0 null,
    idValenceLU      int           not null,
    constraint fk_ValencePattern_ValenceLU1
        foreign key (idValenceLU) references valencelu (idValenceLU)
)
    charset = utf8mb3;

create index idx_ValencePattern_ValenceLU
    on valencepattern (idValenceLU);

create table valencevalent
(
    idValenceValent  int auto_increment
        primary key,
    idFrameElement   int(11) unsigned            not null,
    GF               varchar(45) charset utf8mb4 null,
    GFSource         varchar(45) charset utf8mb4 null,
    PT               varchar(45) charset utf8mb4 null,
    idValencePattern int                         not null,
    constraint fk_ValencePattern_FrameElement1
        foreign key (idFrameElement) references frameelement (idFrameElement),
    constraint fk_ValenceValent_ValencePattern1
        foreign key (idValencePattern) references valencepattern (idValencePattern)
)
    charset = utf8mb3;

create index idx_ValencePattern_FrameElement
    on valencevalent (idFrameElement);

create index idx_ValenceValent_ValencePattern
    on valencevalent (idValencePattern);

create table video
(
    idVideo      int auto_increment
        primary key,
    title        varchar(255)     null,
    originalFile varchar(255)     null,
    sha1Name     varchar(45)      null,
    currentURL   varchar(255)     null,
    width        int              null,
    height       int              null,
    idLanguage   int(11) unsigned not null,
    constraint fk_video_language1
        foreign key (idLanguage) references language (idLanguage)
)
    collate = utf8mb4_bin;

create table document_video
(
    idDocument int(11) unsigned not null,
    idVideo    int              not null,
    primary key (idDocument, idVideo),
    constraint fk_document_has_video_document1
        foreign key (idDocument) references document (idDocument),
    constraint fk_document_has_video_video1
        foreign key (idVideo) references video (idVideo)
)
    collate = utf8mb4_bin;

create index fk_document_has_video_document1_idx
    on document_video (idDocument);

create index fk_document_has_video_video1_idx
    on document_video (idVideo);

create index idx_document_video_document
    on document_video (idDocument, idVideo);

create index fk_video_language1_idx
    on video (idLanguage);

create table video_dynamicobject
(
    idVideo         int not null,
    idDynamicObject int not null,
    primary key (idVideo, idDynamicObject),
    constraint fk_video_has_dynamicobject_dynamicobject1
        foreign key (idDynamicObject) references dynamicobject (idDynamicObject),
    constraint fk_video_has_dynamicobject_video1
        foreign key (idVideo) references video (idVideo)
)
    collate = utf8mb4_bin;

create index fk_video_has_dynamicobject_dynamicobject1_idx
    on video_dynamicobject (idDynamicObject);

create index fk_video_has_dynamicobject_video1_idx
    on video_dynamicobject (idVideo);

create table wordform
(
    idWordForm int(11) unsigned auto_increment
        primary key,
    form       varchar(255) collate utf8mb4_bin null,
    md5        char(32) collate utf8mb4_bin     null,
    idLexeme   int(11) unsigned                 not null,
    idEntity   int(11) unsigned                 null,
    altSpell   tinyint(1)                       null,
    constraint fk_WordForm_Entity
        foreign key (idEntity) references entity (idEntity),
    constraint fk_WordForm_Lexeme
        foreign key (idLexeme) references lexeme (idLexeme)
);

create table lexemeentry
(
    idLexemeEntry int(11) unsigned auto_increment
        primary key,
    lexemeOrder   int              null,
    breakBefore   tinyint(1)       null,
    headWord      tinyint(1)       null,
    idLexeme      int(11) unsigned not null,
    idLemma       int(11) unsigned not null,
    idWordForm    int(11) unsigned null,
    constraint fk_LexemeEntry_Lemma
        foreign key (idLemma) references lemma (idLemma),
    constraint fk_LexemeEntry_Lexeme
        foreign key (idLexeme) references lexeme (idLexeme),
    constraint fk_LexemeEntry_WordForm
        foreign key (idWordForm) references wordform (idWordForm)
)
    collate = utf8mb4_bin;

create index idx_LexemeEntry_Lemma
    on lexemeentry (idLemma);

create index idx_LexemeEntry_Lexeme
    on lexemeentry (idLexeme);

create index idx_LexemeEntry_WordForm
    on lexemeentry (idWordForm);

create index idx_WordForm_Entity
    on wordform (idEntity);

create index idx_WordForm_Lexeme
    on wordform (idLexeme);

create index idx_WordForm_form
    on wordform (form);

create index idx_WordForm_md5
    on wordform (md5);

create table wordmm
(
    idWordMM            int auto_increment
        primary key,
    word                varchar(255) null,
    startTimestamp      varchar(255) null,
    endTimestamp        varchar(255) null,
    startTime           float        null,
    endTime             float        null,
    origin              int          null,
    idSentenceMM        int          null,
    idDocumentMM        int          not null,
    idDynamicSentenceMM int          null,
    idDocumentSentence  int          null,
    idVideo             int          not null,
    constraint fk_wordmm_video1
        foreign key (idVideo) references video (idVideo)
)
    collate = utf8mb4_bin;

create index fk_wordmm_video1_idx
    on wordmm (idVideo);

create index idx_WordMM_SentenceMM
    on wordmm (idSentenceMM);

create index idx_wordmm_dynamicsentencemm
    on wordmm (idDynamicSentenceMM);

create definer = fnbrasil@`%` view view_alloweddocs as
select distinct `allowed`.`idCorpus`     AS `idCorpus`,
                `allowed`.`corpusName`   AS `corpusName`,
                `allowed`.`idDocument`   AS `idDocument`,
                `allowed`.`documentName` AS `documentName`,
                `allowed`.`idUser`       AS `idUser`,
                `allowed`.`idLanguage`   AS `idLanguage`
from (select `c`.`idCorpus`     AS `idCorpus`,
             `c`.`name`         AS `corpusName`,
             `doc`.`idDocument` AS `idDocument`,
             `doc`.`name`       AS `documentName`,
             `ut`.`idUser`      AS `idUser`,
             `doc`.`idLanguage` AS `idLanguage`
      from (((`webtool42_db`.`usertask` `ut` join `webtool42_db`.`usertask_document` `utd`
              on (`ut`.`idUserTask` = `utd`.`idUserTask`)) join `webtool42_db`.`view_document` `doc`
             on (`utd`.`idDocument` = `doc`.`idDocument`)) join `webtool42_db`.`view_corpus` `c`
            on (`doc`.`idCorpus` = `c`.`idCorpus`))
      where `ut`.`isActive` = 1
        and `doc`.`idLanguage` = `c`.`idLanguage`
      union
      select `c`.`idCorpus`     AS `idCorpus`,
             `c`.`name`         AS `corpusName`,
             `doc`.`idDocument` AS `idDocument`,
             `doc`.`name`       AS `documentName`,
             `ut`.`idUser`      AS `idUser`,
             `doc`.`idLanguage` AS `idLanguage`
      from (((`webtool42_db`.`usertask` `ut` join `webtool42_db`.`usertask_document` `utd`
              on (`ut`.`idUserTask` = `utd`.`idUserTask`)) join `webtool42_db`.`view_corpus` `c`
             on (`utd`.`idCorpus` = `c`.`idCorpus`)) join `webtool42_db`.`view_document` `doc`
            on (`c`.`idCorpus` = `doc`.`idCorpus`))
      where `utd`.`idDocument` is null
        and `ut`.`isActive` = 1
        and `doc`.`idLanguage` = `c`.`idLanguage`) `allowed`;

create definer = fnbrasil@`%` view view_annotation as
select `a`.`idAnnotation`                                                 AS `idAnnotation`,
       `a`.`idEntity`                                                     AS `idEntity`,
       `e`.`type`                                                         AS `entityType`,
       `a`.`idTextSpan`                                                   AS `idTextspan`,
       `a`.`idStaticObject`                                               AS `idStaticObject`,
       `a`.`idDynamicObject`                                              AS `idDynamicObject`,
       coalesce(`dts`.`idDocument`, `di`.`idDocument`, `dv`.`idDocument`) AS `idDocument`,
       `a`.`idUserTask`                                                   AS `idUserTask`,
       `t`.`idTask`                                                       AS `idTask`,
       `t`.`name`                                                         AS `name`,
       `ut`.`idUser`                                                      AS `idUser`
from ((((((((((((`webtool42_db`.`annotation` `a` join `webtool42_db`.`entity` `e`
                 on (`a`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`usertask` `ut`
                on (`a`.`idUserTask` = `ut`.`idUserTask`)) join `webtool42_db`.`task` `t`
               on (`ut`.`idTask` = `t`.`idTask`)) left join `webtool42_db`.`textspan` `ts`
              on (`a`.`idTextSpan` = `ts`.`idTextSpan`)) left join `webtool42_db`.`sentence` `s`
             on (`ts`.`idSentence` = `s`.`idSentence`)) left join `webtool42_db`.`document_sentence` `dts`
            on (`s`.`idSentence` = `dts`.`idSentence`)) left join `webtool42_db`.`staticobject` `sob`
           on (`a`.`idStaticObject` = `sob`.`idStaticObject`)) left join `webtool42_db`.`image_staticobject` `isob`
          on (`sob`.`idStaticObject` = `isob`.`idStaticObject`)) left join `webtool42_db`.`document_image` `di`
         on (`isob`.`idImage` = `di`.`idImage`)) left join `webtool42_db`.`dynamicobject` `dob`
        on (`a`.`idDynamicObject` = `dob`.`idDynamicObject`)) left join `webtool42_db`.`video_dynamicobject` `vdob`
       on (`dob`.`idDynamicObject` = `vdob`.`idDynamicObject`)) left join `webtool42_db`.`document_video` `dv`
      on (`vdob`.`idVideo` = `dv`.`idVideo`));

create definer = fnbrasil@`%` view view_annotation_deixis as
select `dob`.`idDynamicObject`                                            AS `idDynamicObject`,
       `dob`.`name`                                                       AS `name`,
       `dob`.`startFrame`                                                 AS `startFrame`,
       `dob`.`endFrame`                                                   AS `endFrame`,
       `dob`.`startTime`                                                  AS `startTime`,
       `dob`.`endTime`                                                    AS `endTime`,
       `dob`.`status`                                                     AS `status`,
       `dob`.`origin`                                                     AS `origin`,
       `lt`.`idLayerType`                                                 AS `idLayerType`,
       `lt`.`name`                                                        AS `nameLayerType`,
       `lt`.`layerGroup`                                                  AS `layerGroup`,
       `lt`.`idLanguage`                                                  AS `idLanguageLT`,
       `alu`.`idAnnotation`                                               AS `idAnnotationLU`,
       `alu`.`idLU`                                                       AS `idLU`,
       if(`alu`.`idLU`, concat(`alu`.`frameName`, '.', `alu`.`name`), '') AS `lu`,
       `afe`.`idAnnotation`                                               AS `idAnnotationFE`,
       `afe`.`idFrameElement`                                             AS `idFrameElement`,
       `afe`.`idFrame`                                                    AS `idFrame`,
       ifnull(`afe`.`frameName`, '')                                      AS `frame`,
       ifnull(`afe`.`name`, '')                                           AS `fe`,
       `afe`.`rgbBg`                                                      AS `colorFE`,
       `afe`.`idLanguage`                                                 AS `idLanguageFE`,
       `agl`.`idAnnotation`                                               AS `idAnnotationGL`,
       `agl`.`idGenericLabel`                                             AS `idGenericLabel`,
       ifnull(`agl`.`name`, '')                                           AS `gl`,
       `agl`.`bgColor`                                                    AS `bgColorGL`,
       `agl`.`fgColor`                                                    AS `fgColorGL`,
       `agl`.`idLanguage`                                                 AS `idLanguageGL`,
       `dv`.`idDocument`                                                  AS `idDocument`
from ((((((`webtool42_db`.`dynamicobject` `dob` join `webtool42_db`.`view_layertype` `lt`
           on (`dob`.`idLayerType` = `lt`.`idLayerType`)) join `webtool42_db`.`video_dynamicobject` `vdo`
          on (`dob`.`idDynamicObject` = `vdo`.`idDynamicObject`)) join `webtool42_db`.`document_video` `dv`
         on (`vdo`.`idVideo` = `dv`.`idVideo`)) left join (select `lu`.`idLU`           AS `idLU`,
                                                                  `lu`.`idEntity`       AS `idEntity`,
                                                                  `lu`.`name`           AS `name`,
                                                                  `lu`.`frameName`      AS `frameName`,
                                                                  `a`.`idAnnotation`    AS `idAnnotation`,
                                                                  `a`.`idDynamicObject` AS `idDynamicObject`
                                                           from (`webtool42_db`.`view_lu` `lu` join `webtool42_db`.`annotation` `a`
                                                                 on (`lu`.`idEntity` = `a`.`idEntity`))
                                                           where `a`.`idDynamicObject` is not null) `alu`
        on (`dob`.`idDynamicObject` = `alu`.`idDynamicObject`)) left join (select `fe`.`idFrameElement`          AS `idFrameElement`,
                                                                                  `fe`.`idFrame`                 AS `idFrame`,
                                                                                  `fe`.`frameName`               AS `frameName`,
                                                                                  `fe`.`name`                    AS `name`,
                                                                                  `webtool42_db`.`color`.`rgbBg` AS `rgbBg`,
                                                                                  `fe`.`idLanguage`              AS `idLanguage`,
                                                                                  `a`.`idAnnotation`             AS `idAnnotation`,
                                                                                  `a`.`idDynamicObject`          AS `idDynamicObject`
                                                                           from ((`webtool42_db`.`view_frameelement` `fe` join `webtool42_db`.`color`
                                                                                  on (`fe`.`idColor` = `webtool42_db`.`color`.`idColor`)) join `webtool42_db`.`annotation` `a`
                                                                                 on (`fe`.`idEntity` = `a`.`idEntity`))
                                                                           where `a`.`idDynamicObject` is not null) `afe`
       on (`dob`.`idDynamicObject` = `afe`.`idDynamicObject`)) left join (select `gl`.`idGenericLabel`          AS `idGenericLabel`,
                                                                                 `gl`.`name`                    AS `name`,
                                                                                 `webtool42_db`.`color`.`rgbBg` AS `bgColor`,
                                                                                 `webtool42_db`.`color`.`rgbFg` AS `fgColor`,
                                                                                 `gl`.`idLanguage`              AS `idLanguage`,
                                                                                 `a`.`idAnnotation`             AS `idAnnotation`,
                                                                                 `a`.`idDynamicObject`          AS `idDynamicObject`
                                                                          from ((`webtool42_db`.`genericlabel` `gl` join `webtool42_db`.`color`
                                                                                 on (`gl`.`idColor` = `webtool42_db`.`color`.`idColor`)) join `webtool42_db`.`annotation` `a`
                                                                                on (`gl`.`idEntity` = `a`.`idEntity`))
                                                                          where `a`.`idDynamicObject` is not null) `agl`
      on (`dob`.`idDynamicObject` = `agl`.`idDynamicObject`))
where `dob`.`origin` = 5
order by `dob`.`startTime`, `dob`.`endTime`;

create definer = fnbrasil@`%` view view_annotation_dynamic as
select `dob`.`idDynamicObject`                                            AS `idDynamicObject`,
       `dob`.`name`                                                       AS `name`,
       `dob`.`startFrame`                                                 AS `startFrame`,
       `dob`.`endFrame`                                                   AS `endFrame`,
       `dob`.`startTime`                                                  AS `startTime`,
       `dob`.`endTime`                                                    AS `endTime`,
       `dob`.`status`                                                     AS `status`,
       `dob`.`origin`                                                     AS `origin`,
       `alu`.`idAnnotation`                                               AS `idAnnotationLU`,
       `alu`.`idLU`                                                       AS `idLU`,
       if(`alu`.`idLU`, concat(`alu`.`frameName`, '.', `alu`.`name`), '') AS `lu`,
       `afe`.`idAnnotation`                                               AS `idAnnotationFE`,
       `afe`.`idFrameElement`                                             AS `idFrameElement`,
       `afe`.`idFrame`                                                    AS `idFrame`,
       ifnull(`afe`.`frameName`, '')                                      AS `frame`,
       ifnull(`afe`.`name`, '')                                           AS `fe`,
       `afe`.`rgbBg`                                                      AS `color`,
       `afe`.`idLanguage`                                                 AS `idLanguage`,
       `dv`.`idDocument`                                                  AS `idDocument`
from ((((`webtool42_db`.`dynamicobject` `dob` join `webtool42_db`.`video_dynamicobject` `vdo`
         on (`dob`.`idDynamicObject` = `vdo`.`idDynamicObject`)) join `webtool42_db`.`document_video` `dv`
        on (`vdo`.`idVideo` = `dv`.`idVideo`)) left join (select `lu`.`idLU`           AS `idLU`,
                                                                 `lu`.`idEntity`       AS `idEntity`,
                                                                 `lu`.`name`           AS `name`,
                                                                 `lu`.`frameName`      AS `frameName`,
                                                                 `a`.`idAnnotation`    AS `idAnnotation`,
                                                                 `a`.`idDynamicObject` AS `idDynamicObject`
                                                          from (`webtool42_db`.`view_lu` `lu` join `webtool42_db`.`annotation` `a`
                                                                on (`lu`.`idEntity` = `a`.`idEntity`))
                                                          where `a`.`idDynamicObject` is not null) `alu`
       on (`dob`.`idDynamicObject` = `alu`.`idDynamicObject`)) left join (select `fe`.`idFrameElement`          AS `idFrameElement`,
                                                                                 `fe`.`idFrame`                 AS `idFrame`,
                                                                                 `fe`.`frameName`               AS `frameName`,
                                                                                 `fe`.`name`                    AS `name`,
                                                                                 `webtool42_db`.`color`.`rgbBg` AS `rgbBg`,
                                                                                 `fe`.`idLanguage`              AS `idLanguage`,
                                                                                 `a`.`idAnnotation`             AS `idAnnotation`,
                                                                                 `a`.`idDynamicObject`          AS `idDynamicObject`
                                                                          from ((`webtool42_db`.`view_frameelement` `fe` join `webtool42_db`.`color`
                                                                                 on (`fe`.`idColor` = `webtool42_db`.`color`.`idColor`)) join `webtool42_db`.`annotation` `a`
                                                                                on (`fe`.`idEntity` = `a`.`idEntity`))
                                                                          where `a`.`idDynamicObject` is not null) `afe`
      on (`dob`.`idDynamicObject` = `afe`.`idDynamicObject`))
where `dob`.`origin` in (1, 2)
order by `dob`.`startTime`, `dob`.`endTime`;

create definer = fnbrasil@`%` view view_annotation_static as
select `sob`.`idStaticObject`                                             AS `idStaticObject`,
       `sob`.`name`                                                       AS `name`,
       `alu`.`idAnnotation`                                               AS `idAnnotationLU`,
       `alu`.`idLU`                                                       AS `idLU`,
       if(`alu`.`idLU`, concat(`alu`.`frameName`, '.', `alu`.`name`), '') AS `lu`,
       `alu`.`idUserTask`                                                 AS `idUserTaskLU`,
       `afe`.`idAnnotation`                                               AS `idAnnotationFE`,
       `afe`.`idFrameElement`                                             AS `idFrameElement`,
       `afe`.`idFrame`                                                    AS `idFrame`,
       ifnull(`afe`.`frameName`, '')                                      AS `frame`,
       ifnull(`afe`.`name`, '')                                           AS `fe`,
       `afe`.`rgbBg`                                                      AS `color`,
       `afe`.`idLanguage`                                                 AS `idLanguage`,
       `afe`.`idUserTask`                                                 AS `idUserTaskFE`,
       `di`.`idDocument`                                                  AS `idDocument`,
       `di`.`idImage`                                                     AS `idImage`
from ((((`webtool42_db`.`staticobject` `sob` join `webtool42_db`.`image_staticobject` `iso`
         on (`sob`.`idStaticObject` = `iso`.`idStaticObject`)) join `webtool42_db`.`document_image` `di`
        on (`iso`.`idImage` = `di`.`idImage`)) left join (select `lu`.`idLU`          AS `idLU`,
                                                                 `lu`.`idEntity`      AS `idEntity`,
                                                                 `lu`.`name`          AS `name`,
                                                                 `lu`.`frameName`     AS `frameName`,
                                                                 `a`.`idAnnotation`   AS `idAnnotation`,
                                                                 `a`.`idStaticObject` AS `idStaticObject`,
                                                                 `a`.`idUserTask`     AS `idUserTask`
                                                          from (`webtool42_db`.`view_lu` `lu` join `webtool42_db`.`annotation` `a`
                                                                on (`lu`.`idEntity` = `a`.`idEntity`))
                                                          where `a`.`idStaticObject` is not null) `alu`
       on (`sob`.`idStaticObject` = `alu`.`idStaticObject`)) left join (select `fe`.`idFrameElement`          AS `idFrameElement`,
                                                                               `fe`.`idFrame`                 AS `idFrame`,
                                                                               `fe`.`frameName`               AS `frameName`,
                                                                               `fe`.`name`                    AS `name`,
                                                                               `webtool42_db`.`color`.`rgbBg` AS `rgbBg`,
                                                                               `fe`.`idLanguage`              AS `idLanguage`,
                                                                               `a`.`idAnnotation`             AS `idAnnotation`,
                                                                               `a`.`idStaticObject`           AS `idStaticObject`,
                                                                               `a`.`idUserTask`               AS `idUserTask`
                                                                        from ((`webtool42_db`.`view_frameelement` `fe` join `webtool42_db`.`color`
                                                                               on (`fe`.`idColor` = `webtool42_db`.`color`.`idColor`)) join `webtool42_db`.`annotation` `a`
                                                                              on (`fe`.`idEntity` = `a`.`idEntity`))
                                                                        where `a`.`idStaticObject` is not null) `afe`
      on (`sob`.`idStaticObject` = `afe`.`idStaticObject`))
order by `sob`.`idStaticObject`;

create definer = fnbrasil@`%` view view_annotation_text_ce as
select `a`.`idAnnotation`           AS `idAnnotation`,
       `ts`.`idTextSpan`            AS `idTextSpan`,
       `ts`.`startChar`             AS `startChar`,
       `ts`.`endChar`               AS `endChar`,
       `ts`.`multi`                 AS `multi`,
       `ts`.`idLayer`               AS `idLayer`,
       `ts`.`idInstantiationType`   AS `idInstantiationType`,
       `ce`.`idConstruction`        AS `idConstruction`,
       `ce`.`idConstructionElement` AS `idConstructionElement`,
       `ce`.`idEntity`              AS `idEntity`,
       `ce`.`idColor`               AS `idColor`,
       `ce`.`name`                  AS `name`,
       `ce`.`idLanguage`            AS `idLanguage`,
       `l`.`idAnnotationSet`        AS `idAnnotationSet`,
       `l`.`idLayerType`            AS `idLayerType`,
       `lt`.`layerOrder`            AS `layerOrder`,
       `lt`.`entry`                 AS `layerTypeEntry`,
       `lt`.`idEntity`              AS `layerTypeIdEntity`
from ((((`webtool42_db`.`textspan` `ts` join `webtool42_db`.`annotation` `a`
         on (`ts`.`idTextSpan` = `a`.`idTextSpan`)) join `webtool42_db`.`view_constructionelement` `ce`
        on (`a`.`idEntity` = `ce`.`idEntity`)) join `webtool42_db`.`layer` `l`
       on (`ts`.`idLayer` = `l`.`idLayer`)) join `webtool42_db`.`layertype` `lt`
      on (`l`.`idLayerType` = `lt`.`idLayerType`));

create definer = fnbrasil@`%` view view_annotation_text_fe as
select `a`.`idAnnotation`         AS `idAnnotation`,
       `ts`.`idTextSpan`          AS `idTextSpan`,
       `ts`.`startChar`           AS `startChar`,
       `ts`.`endChar`             AS `endChar`,
       `ts`.`multi`               AS `multi`,
       `ts`.`idLayer`             AS `idLayer`,
       `ts`.`idInstantiationType` AS `idInstantiationType`,
       `fe`.`idFrame`             AS `idFrame`,
       `fe`.`idFrameElement`      AS `idFrameElement`,
       `fe`.`idEntity`            AS `idEntity`,
       `fe`.`idColor`             AS `idColor`,
       `fe`.`coreType`            AS `coreType`,
       `fe`.`name`                AS `name`,
       `fe`.`idLanguage`          AS `idLanguage`,
       `l`.`idAnnotationSet`      AS `idAnnotationSet`,
       `l`.`idLayerType`          AS `idLayerType`,
       `lt`.`layerOrder`          AS `layerOrder`,
       `lt`.`entry`               AS `layerTypeEntry`,
       `lt`.`idEntity`            AS `layerTypeIdEntity`
from ((((`webtool42_db`.`textspan` `ts` join `webtool42_db`.`annotation` `a`
         on (`ts`.`idTextSpan` = `a`.`idTextSpan`)) join `webtool42_db`.`view_frameelement` `fe`
        on (`a`.`idEntity` = `fe`.`idEntity`)) join `webtool42_db`.`layer` `l`
       on (`ts`.`idLayer` = `l`.`idLayer`)) join `webtool42_db`.`layertype` `lt`
      on (`l`.`idLayerType` = `lt`.`idLayerType`));

create definer = fnbrasil@`%` view view_annotation_text_gl as
select `a`.`idAnnotation`         AS `idAnnotation`,
       `ts`.`idTextSpan`          AS `idTextSpan`,
       `ts`.`startChar`           AS `startChar`,
       `ts`.`endChar`             AS `endChar`,
       `ts`.`multi`               AS `multi`,
       `ts`.`idLayer`             AS `idLayer`,
       `ts`.`idInstantiationType` AS `idInstantiationType`,
       `gl`.`idGenericLabel`      AS `idGenericLabel`,
       `gl`.`idEntity`            AS `idEntity`,
       `gl`.`idColor`             AS `idColor`,
       `gl`.`name`                AS `name`,
       `gl`.`idLanguage`          AS `idLanguage`,
       `l`.`idAnnotationSet`      AS `idAnnotationSet`,
       `l`.`idLayerType`          AS `idLayerType`,
       `lt`.`layerOrder`          AS `layerOrder`,
       `lt`.`entry`               AS `layerTypeEntry`,
       `lt`.`idEntity`            AS `layerTypeIdEntity`
from ((((`webtool42_db`.`textspan` `ts` join `webtool42_db`.`annotation` `a`
         on (`ts`.`idTextSpan` = `a`.`idTextSpan`)) join `webtool42_db`.`genericlabel` `gl`
        on (`a`.`idEntity` = `gl`.`idEntity`)) join `webtool42_db`.`layer` `l`
       on (`ts`.`idLayer` = `l`.`idLayer`)) join `webtool42_db`.`layertype` `lt`
      on (`l`.`idLayerType` = `lt`.`idLayerType`));

create definer = fnbrasil@`%` view view_annotationset as
select `a`.`idAnnotationSet`                AS `idAnnotationSet`,
       `a`.`idAnnotationStatus`             AS `idAnnotationStatus`,
       `a`.`lome`                           AS `lome`,
       `webtool42_db`.`lu`.`idEntity`       AS `idEntityLU`,
       `webtool42_db`.`lu`.`idLU`           AS `idLU`,
       `cxn`.`idEntity`                     AS `idEntityCxn`,
       `cxn`.`idConstruction`               AS `idConstruction`,
       `webtool42_db`.`frame`.`idFrame`     AS `idFrame`,
       `webtool42_db`.`frame`.`idEntity`    AS `idEntityFrame`,
       `webtool42_db`.`lexicon`.`idLexicon` AS `idLexicon`,
       `webtool42_db`.`lexicon`.`idEntity`  AS `idEntityLexicon`,
       `t`.`idEntity`                       AS `idEntityType`,
       `ds`.`idSentence`                    AS `idSentence`,
       `ds`.`idDocument`                    AS `idDocument`,
       `ds`.`idDocumentSentence`            AS `idDocumentSentence`
from ((((((`webtool42_db`.`annotationset` `a` join `webtool42_db`.`type` `t`
           on (`a`.`idAnnotationStatus` = `t`.`idType`)) join `webtool42_db`.`document_sentence` `ds`
          on (`a`.`idSentence` = `ds`.`idSentence`)) left join `webtool42_db`.`lu`
         on (`a`.`idLU` = `webtool42_db`.`lu`.`idLU`)) left join `webtool42_db`.`construction` `cxn`
        on (`a`.`idConstruction` = `cxn`.`idConstruction`)) left join `webtool42_db`.`frame`
       on (`a`.`idFrame` = `webtool42_db`.`frame`.`idFrame`)) left join `webtool42_db`.`lexicon`
      on (`a`.`idLexicon` = `webtool42_db`.`lexicon`.`idLexicon`));

create definer = fnbrasil@`%` view view_concept as
select `cp`.`idConcept`                     AS `idConcept`,
       `cp`.`entry`                         AS `entry`,
       `cp`.`idEntity`                      AS `idEntity`,
       `cp`.`keyword`                       AS `keyword`,
       `cp`.`aka`                           AS `aka`,
       `cp`.`type`                          AS `type`,
       `cp`.`status`                        AS `status`,
       `webtool42_db`.`entry`.`name`        AS `name`,
       `webtool42_db`.`entry`.`description` AS `description`,
       `webtool42_db`.`entry`.`idLanguage`  AS `idLanguage`,
       `t`.`idType`                         AS `idType`,
       `t`.`name`                           AS `typeName`,
       `t`.`description`                    AS `typeDescription`
from ((`webtool42_db`.`concept` `cp` join `webtool42_db`.`entry`
       on (`cp`.`idEntity` = `webtool42_db`.`entry`.`idEntity`)) join `webtool42_db`.`view_type` `t`
      on (`cp`.`idType` = `t`.`idType`))
where `webtool42_db`.`entry`.`idLanguage` = `t`.`idLanguage`;

create definer = fnbrasil@`%` view view_concept_relation as
select `relation`.`idEntityRelation` AS `idEntityRelation`,
       `relation`.`idRelationType`   AS `idRelationType`,
       `relation`.`relationType`     AS `relationType`,
       `relation`.`nameCanonical`    AS `nameCanonical`,
       `relation`.`nameDirect`       AS `nameDirect`,
       `relation`.`nameInverse`      AS `nameInverse`,
       `relation`.`color`            AS `color`,
       `c1`.`name`                   AS `c1Name`,
       `c1`.`idConcept`              AS `c1IdConcept`,
       `c1`.`idEntity`               AS `c1IdEntity`,
       `c1`.`keyword`                AS `c1Keyword`,
       `c1`.`type`                   AS `c1Type`,
       `c1`.`status`                 AS `c1Status`,
       `c1`.`idLanguage`             AS `idLanguage`,
       `c2`.`name`                   AS `c2Name`,
       `c2`.`idConcept`              AS `c2IdConcept`,
       `c2`.`idEntity`               AS `c2IdEntity`,
       `c2`.`keyword`                AS `c2Keyword`,
       `c2`.`type`                   AS `c2Type`,
       `c2`.`status`                 AS `c2Status`
from ((`webtool42_db`.`view_concept` `c1` join `webtool42_db`.`view_relation` `relation`
       on (`c1`.`idEntity` = `relation`.`idEntity1`)) join `webtool42_db`.`view_concept` `c2`
      on (`relation`.`idEntity2` = `c2`.`idEntity`))
where `relation`.`idRelationGroup` in (select `webtool42_db`.`relationgroup`.`idRelationGroup`
                                       from `webtool42_db`.`relationgroup`
                                       where `webtool42_db`.`relationgroup`.`entry` = 'rgp_cc_relations')
  and `c1`.`idLanguage` = `c2`.`idLanguage`;

create definer = fnbrasil@`%` view view_constrainedby as
select `constraints`.`idLanguage`           AS `idLanguage`,
       `constraints`.`conName`              AS `conName`,
       `constraints`.`idConstraint`         AS `idConstraint`,
       `constraints`.`idConstrained`        AS `idConstrained`,
       `constraints`.`idConstrainedBy`      AS `idConstrainedBy`,
       `constraints`.`constrainedByType`    AS `constrainedByType`,
       `constraints`.`idConstraintInstance` AS `idConstraintInstance`,
       `constraints`.`name`                 AS `name`
from (select `view_constrainedby_frame`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_frame`.`conName`              AS `conName`,
             `view_constrainedby_frame`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_frame`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_frame`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_frame`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_frame`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_frame`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_frame`
      union
      select `view_constrainedby_construction`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_construction`.`conName`              AS `conName`,
             `view_constrainedby_construction`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_construction`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_construction`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_construction`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_construction`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_construction`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_construction`
      union
      select `view_constrainedby_ce`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_ce`.`conName`              AS `conName`,
             `view_constrainedby_ce`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_ce`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_ce`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_ce`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_ce`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_ce`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_ce`
      union
      select `view_constrainedby_semantictype`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_semantictype`.`conName`              AS `conName`,
             `view_constrainedby_semantictype`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_semantictype`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_semantictype`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_semantictype`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_semantictype`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_semantictype`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_semantictype`
      union
      select `view_constrainedby_constraint`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_constraint`.`conName`              AS `conName`,
             `view_constrainedby_constraint`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_constraint`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_constraint`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_constraint`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_constraint`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_constraint`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_constraint`
      union
      select `view_constrainedby_constraint_cx`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_constraint_cx`.`conName`              AS `conName`,
             `view_constrainedby_constraint_cx`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_constraint_cx`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_constraint_cx`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_constraint_cx`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_constraint_cx`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_constraint_cx`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_constraint_cx`
      union
      select `view_constrainedby_lexeme`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_lexeme`.`conName`              AS `conName`,
             `view_constrainedby_lexeme`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_lexeme`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_lexeme`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_lexeme`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_lexeme`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_lexeme`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_lexeme`
      union
      select `view_constrainedby_lemma`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_lemma`.`conName`              AS `conName`,
             `view_constrainedby_lemma`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_lemma`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_lemma`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_lemma`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_lemma`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_lemma`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_lemma`
      union
      select `view_constrainedby_morpheme`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_morpheme`.`conName`              AS `conName`,
             `view_constrainedby_morpheme`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_morpheme`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_morpheme`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_morpheme`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_morpheme`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_morpheme`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_morpheme`
      union
      select `view_constrainedby_lu`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_lu`.`conName`              AS `conName`,
             `view_constrainedby_lu`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_lu`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_lu`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_lu`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_lu`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_lu`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_lu`
      union
      select `view_constrainedby_udfeature`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_udfeature`.`conName`              AS `conName`,
             `view_constrainedby_udfeature`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_udfeature`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_udfeature`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_udfeature`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_udfeature`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_udfeature`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_udfeature`
      union
      select `view_constrainedby_udpos`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_udpos`.`conName`              AS `conName`,
             `view_constrainedby_udpos`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_udpos`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_udpos`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_udpos`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_udpos`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_udpos`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_udpos`
      union
      select `view_constrainedby_udrelation`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_udrelation`.`conName`              AS `conName`,
             `view_constrainedby_udrelation`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_udrelation`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_udrelation`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_udrelation`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_udrelation`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_udrelation`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_udrelation`
      union
      select `view_constrainedby_festandsforfe`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_festandsforfe`.`conName`              AS `conName`,
             `view_constrainedby_festandsforfe`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_festandsforfe`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_festandsforfe`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_festandsforfe`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_festandsforfe`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_festandsforfe`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_festandsforfe`
      union
      select `view_constrainedby_festandsforlu`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_festandsforlu`.`conName`              AS `conName`,
             `view_constrainedby_festandsforlu`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_festandsforlu`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_festandsforlu`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_festandsforlu`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_festandsforlu`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_festandsforlu`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_festandsforlu`
      union
      select `view_constrainedby_lustandsforlu`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_lustandsforlu`.`conName`              AS `conName`,
             `view_constrainedby_lustandsforlu`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_lustandsforlu`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_lustandsforlu`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_lustandsforlu`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_lustandsforlu`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_lustandsforlu`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_lustandsforlu`
      union
      select `view_constrainedby_luequivalence`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_luequivalence`.`conName`              AS `conName`,
             `view_constrainedby_luequivalence`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_luequivalence`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_luequivalence`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_luequivalence`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_luequivalence`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_luequivalence`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_luequivalence`
      union
      select `view_constrainedby_qualia`.`idLanguage`           AS `idLanguage`,
             `view_constrainedby_qualia`.`conName`              AS `conName`,
             `view_constrainedby_qualia`.`idConstraint`         AS `idConstraint`,
             `view_constrainedby_qualia`.`idConstrained`        AS `idConstrained`,
             `view_constrainedby_qualia`.`idConstrainedBy`      AS `idConstrainedBy`,
             `view_constrainedby_qualia`.`constrainedByType`    AS `constrainedByType`,
             `view_constrainedby_qualia`.`idConstraintInstance` AS `idConstraintInstance`,
             `view_constrainedby_qualia`.`name`                 AS `name`
      from `webtool42_db`.`view_constrainedby_qualia`
      union
      select `con`.`idLanguage`     AS `idLanguage`,
             'Evokes Frame'         AS `Evokes Frame`,
             0                      AS `0`,
             `r`.`idEntity1`        AS `idEntity1`,
             `r`.`idEntity2`        AS `idEntity2`,
             'EVK'                  AS `EVK`,
             `r`.`idEntityRelation` AS `idEntityRelation`,
             `con`.`name`           AS `name`
      from (`webtool42_db`.`view_relation` `r` join `webtool42_db`.`view_frame` `con`
            on (`r`.`idEntity2` = `con`.`idEntity`))
      union
      select `con`.`idLanguage`     AS `idLanguage`,
             'Evokes FE'            AS `Evokes FE`,
             0                      AS `0`,
             `r`.`idEntity1`        AS `idEntity1`,
             `r`.`idEntity2`        AS `idEntity2`,
             'EVK'                  AS `EVK`,
             `r`.`idEntityRelation` AS `idEntityRelation`,
             `con`.`name`           AS `name`
      from (`webtool42_db`.`view_relation` `r` join `webtool42_db`.`view_frameelement` `con`
            on (`r`.`idEntity2` = `con`.`idEntity`))
      union
      select `con`.`idLanguage`     AS `idLanguage`,
             'Evokes Concept'       AS `Evokes Concept`,
             `r`.`idEntity1`        AS `idEntity1`,
             `r`.`idEntity2`        AS `idEntity2`,
             `r`.`idEntity3`        AS `idEntity3`,
             'EVK'                  AS `EVK`,
             `r`.`idEntityRelation` AS `idEntityRelation`,
             `con`.`name`           AS `name`
      from (`webtool42_db`.`view_relation` `r` join `webtool42_db`.`view_concept` `con`
            on (`r`.`idEntity3` = `con`.`idEntity`))) `constraints`;

create definer = fnbrasil@`%` view view_constrainedby_ce as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `con`.`name`               AS `name`
from ((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
       on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`view_constructionelement` `con`
      on (`c`.`idConstrainedBy` = `con`.`idEntity` and `con`.`idLanguage` = `e`.`idLanguage`));

create definer = fnbrasil@`%` view view_constrainedby_constraint as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `conentry`.`name`          AS `name`
from (((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
        on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`view_constraint` `con`
       on (`c`.`idConstrainedBy` = `con`.`idConstraint`)) join `webtool42_db`.`entry` `conentry`
      on (`e`.`idLanguage` = `conentry`.`idLanguage`));

create definer = fnbrasil@`%` view view_constrainedby_constraint_cx as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `cx`.`name`                AS `name`
from ((((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
         on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`view_constraint` `con`
        on (`c`.`idConstrainedBy` = `con`.`idConstraint`)) join `webtool42_db`.`entry` `conentry`
       on (`e`.`idLanguage` = `conentry`.`idLanguage`)) join `webtool42_db`.`view_construction` `cx`
      on (`con`.`idConstrainedBy` = `cx`.`idEntity`))
where `cx`.`idLanguage` = `e`.`idLanguage`;

create definer = fnbrasil@`%` view view_constrainedby_construction as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `con`.`name`               AS `name`
from ((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
       on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`view_construction` `con`
      on (`c`.`idConstrainedBy` = `con`.`idEntity` and `con`.`idLanguage` = `e`.`idLanguage`));

create definer = fnbrasil@`%` view view_constrainedby_festandsforfe as
select `e`.`idLanguage`        AS `idLanguage`,
       `e`.`name`              AS `conName`,
       `rt`.`idEntityRelation` AS `idConstraint`,
       `rt`.`idEntity1`        AS `idConstrained`,
       `rt`.`idEntity2`        AS `idConstrainedBy`,
       `rt`.`entity2Type`      AS `constrainedByType`,
       `rt`.`idEntityRelation` AS `idConstraintInstance`,
       `fe`.`name`             AS `name`
from ((`webtool42_db`.`view_relation` `rt` join `webtool42_db`.`entry` `e`
       on (`rt`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`view_frameelement` `fe`
      on (`rt`.`idEntity2` = `fe`.`idEntity`))
where `fe`.`idLanguage` = `e`.`idLanguage`
  and `rt`.`relationType` = 'rel_festandsforfe';

create definer = fnbrasil@`%` view view_constrainedby_festandsforlu as
select `e`.`idLanguage`        AS `idLanguage`,
       `e`.`name`              AS `conName`,
       `rt`.`idEntityRelation` AS `idConstraint`,
       `rt`.`idEntity1`        AS `idConstrained`,
       `rt`.`idEntity2`        AS `idConstrainedBy`,
       `rt`.`entity2Type`      AS `constrainedByType`,
       `rt`.`idEntityRelation` AS `idConstraintInstance`,
       `lu`.`name`             AS `name`
from ((`webtool42_db`.`view_relation` `rt` join `webtool42_db`.`entry` `e`
       on (`rt`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`view_lu` `lu`
      on (`rt`.`idEntity2` = `lu`.`idEntity`))
where `rt`.`relationType` = 'rel_festandsforlu';

create definer = fnbrasil@`%` view view_constrainedby_frame as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `con`.`name`               AS `name`
from ((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
       on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`view_frame` `con`
      on (`c`.`idConstrainedBy` = `con`.`idEntity` and `con`.`idLanguage` = `e`.`idLanguage`));

create definer = fnbrasil@`%` view view_constrainedby_lemma as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `con`.`name`               AS `name`
from ((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
       on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`view_lexicon_lemma` `con`
      on (`c`.`idConstrainedBy` = `con`.`idEntity`));

create definer = fnbrasil@`%` view view_constrainedby_lexeme as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `con`.`name`               AS `name`
from ((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
       on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`lexeme` `con`
      on (`c`.`idConstrainedBy` = `con`.`idEntity`));

create definer = fnbrasil@`%` view view_constrainedby_lu as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `con`.`name`               AS `name`
from ((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
       on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`lu` `con`
      on (`c`.`idConstrainedBy` = `con`.`idEntity`));

create definer = fnbrasil@`%` view view_constrainedby_luequivalence as
select `e`.`idLanguage`        AS `idLanguage`,
       `e`.`name`              AS `conName`,
       `rt`.`idEntityRelation` AS `idConstraint`,
       `rt`.`idEntity1`        AS `idConstrained`,
       `rt`.`idEntity2`        AS `idConstrainedBy`,
       `rt`.`entity2Type`      AS `constrainedByType`,
       `rt`.`idEntityRelation` AS `idConstraintInstance`,
       `lu`.`name`             AS `name`
from ((`webtool42_db`.`view_relation` `rt` join `webtool42_db`.`entry` `e`
       on (`rt`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`view_lu` `lu`
      on (`rt`.`idEntity2` = `lu`.`idEntity`))
where `rt`.`relationType` = 'rel_luequivalence';

create definer = fnbrasil@`%` view view_constrainedby_lustandsforlu as
select `e`.`idLanguage`        AS `idLanguage`,
       `e`.`name`              AS `conName`,
       `rt`.`idEntityRelation` AS `idConstraint`,
       `rt`.`idEntity1`        AS `idConstrained`,
       `rt`.`idEntity2`        AS `idConstrainedBy`,
       `rt`.`entity2Type`      AS `constrainedByType`,
       `rt`.`idEntityRelation` AS `idConstraintInstance`,
       `lu`.`name`             AS `name`
from ((`webtool42_db`.`view_relation` `rt` join `webtool42_db`.`entry` `e`
       on (`rt`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`view_lu` `lu`
      on (`rt`.`idEntity2` = `lu`.`idEntity`))
where `rt`.`relationType` = 'rel_lustandsforlu';

create definer = fnbrasil@`%` view view_constrainedby_morpheme as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `con`.`form`               AS `name`
from ((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
       on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`lexicon` `con`
      on (`c`.`idConstrainedBy` = `con`.`idEntity`))
where `con`.`idLexiconGroup` not in (1, 2, 7);

create definer = fnbrasil@`%` view view_constrainedby_qualia as
select `e`.`idLanguage`        AS `idLanguage`,
       `qualia`.`name`         AS `conName`,
       `rt`.`idEntityRelation` AS `idConstraint`,
       `rt`.`idEntity1`        AS `idConstrained`,
       `rt`.`idEntity2`        AS `idConstrainedBy`,
       `rt`.`entity2Type`      AS `constrainedByType`,
       `rt`.`idEntityRelation` AS `idConstraintInstance`,
       `fe`.`name`             AS `name`
from (((`webtool42_db`.`view_relation` `rt` join `webtool42_db`.`entry` `e`
        on (`rt`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`view_qualia` `qualia`
       on (`rt`.`idEntity3` = `qualia`.`idEntity`)) join `webtool42_db`.`view_frameelement` `fe`
      on (`rt`.`idEntity2` = `fe`.`idEntity`))
where `rt`.`relationType` = 'rel_qualia'
  and `e`.`idLanguage` = `qualia`.`idLanguage`
  and `e`.`idLanguage` = `fe`.`idLanguage`;

create definer = fnbrasil@`%` view view_constrainedby_semantictype as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `con`.`name`               AS `name`
from ((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
       on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`view_semantictype` `con`
      on (`c`.`idConstrainedBy` = `con`.`idEntity` and `con`.`idLanguage` = `e`.`idLanguage`));

create definer = fnbrasil@`%` view view_constrainedby_udfeature as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `con`.`name`               AS `name`
from ((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
       on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`udfeature` `con`
      on (`c`.`idConstrainedBy` = `con`.`idEntity`));

create definer = fnbrasil@`%` view view_constrainedby_udpos as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `con`.`POS`                AS `name`
from ((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
       on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`udpos` `con`
      on (`c`.`idConstrainedBy` = `con`.`idEntity`));

create definer = fnbrasil@`%` view view_constrainedby_udrelation as
select `e`.`idLanguage`           AS `idLanguage`,
       `e`.`name`                 AS `conName`,
       `c`.`idConstraint`         AS `idConstraint`,
       `c`.`idConstrained`        AS `idConstrained`,
       `c`.`idConstrainedBy`      AS `idConstrainedBy`,
       `c`.`constrainedByType`    AS `constrainedByType`,
       `c`.`idConstraintInstance` AS `idConstraintInstance`,
       `con`.`info`               AS `name`
from ((`webtool42_db`.`view_constraint` `c` join `webtool42_db`.`entry` `e`
       on (`c`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`udrelation` `con`
      on (`c`.`idConstrainedBy` = `con`.`idEntity`));

create definer = fnbrasil@`%` view view_constraint as
select `ci`.`idEntityRelation` AS `idConstraintInstance`,
       `ct`.`entry`            AS `entry`,
       `ci`.`idEntity1`        AS `idConstraint`,
       `e1`.`type`             AS `constraintType`,
       `ci`.`idEntity2`        AS `idConstrained`,
       `e2`.`type`             AS `constrainedType`,
       `ci`.`idEntity3`        AS `idConstrainedBy`,
       `e3`.`type`             AS `constrainedByType`,
       `ct`.`idRelationType`   AS `idConstraintType`,
       `ct`.`idEntity`         AS `idEntity`,
       `ct`.`prefix`           AS `prefix`
from ((((`webtool42_db`.`entityrelation` `ci` join `webtool42_db`.`relationtype` `ct`
         on (`ci`.`idRelationType` = `ct`.`idRelationType`)) join `webtool42_db`.`entity` `e1`
        on (`ci`.`idEntity1` = `e1`.`idEntity`)) join `webtool42_db`.`entity` `e2`
       on (`ci`.`idEntity2` = `e2`.`idEntity`)) left join `webtool42_db`.`entity` `e3`
      on (`ci`.`idEntity3` = `e3`.`idEntity`))
where `ct`.`idRelationGroup` = 6;

create definer = fnbrasil@`%` view view_construction as
select `webtool42_db`.`construction`.`idConstruction`                                         AS `idConstruction`,
       `webtool42_db`.`construction`.`entry`                                                  AS `entry`,
       `webtool42_db`.`construction`.`abstract`                                               AS `abstract`,
       `webtool42_db`.`construction`.`active`                                                 AS `active`,
       `webtool42_db`.`construction`.`idEntity`                                               AS `idEntity`,
       `webtool42_db`.`construction`.`idLanguage`                                             AS `cxIdLanguage`,
       `webtool42_db`.`entry`.`name`                                                          AS `name`,
       `webtool42_db`.`entry`.`description`                                                   AS `description`,
       `webtool42_db`.`entry`.`idLanguage`                                                    AS `idLanguage`,
       concat(`webtool42_db`.`entry`.`name`, ' [', `webtool42_db`.`language`.`language`, ']') AS `fullName`
from ((`webtool42_db`.`construction` join `webtool42_db`.`entry`
       on (`webtool42_db`.`construction`.`idEntity` = `webtool42_db`.`entry`.`idEntity`)) join `webtool42_db`.`language`
      on (`webtool42_db`.`construction`.`idLanguage` = `webtool42_db`.`language`.`idLanguage`));

create definer = fnbrasil@`%` view view_construction_relation as
select `relation`.`idEntityRelation` AS `idEntityRelation`,
       `relation`.`idRelationType`   AS `idRelationType`,
       `relation`.`relationType`     AS `relationType`,
       `relation`.`nameCanonical`    AS `nameCanonical`,
       `relation`.`nameDirect`       AS `nameDirect`,
       `relation`.`nameInverse`      AS `nameInverse`,
       `relation`.`color`            AS `color`,
       `c1`.`name`                   AS `c1Name`,
       `c1`.`idConstruction`         AS `c1IdConstruction`,
       `c1`.`idEntity`               AS `c1IdEntity`,
       `c1`.`cxIdLanguage`           AS `cxIdLanguage`,
       `c1`.`idLanguage`             AS `idLanguage`,
       `c2`.`name`                   AS `c2Name`,
       `c2`.`idConstruction`         AS `c2IdConstruction`,
       `c2`.`idEntity`               AS `c2IdEntity`
from ((`webtool42_db`.`view_construction` `c1` join `webtool42_db`.`view_relation` `relation`
       on (`c1`.`idEntity` = `relation`.`idEntity1`)) join `webtool42_db`.`view_construction` `c2`
      on (`relation`.`idEntity2` = `c2`.`idEntity`))
where `relation`.`relationType` in ('rel_daughter_of', 'rel_inheritance_cxn')
  and `c1`.`idLanguage` = `c2`.`idLanguage`;

create definer = fnbrasil@`%` view view_constructionelement as
select `webtool42_db`.`construction`.`idConstruction` AS `idConstruction`,
       `webtool42_db`.`construction`.`entry`          AS `constructionEntry`,
       `webtool42_db`.`construction`.`idEntity`       AS `constructionIdEntity`,
       `ce`.`idConstructionElement`                   AS `idConstructionElement`,
       `ce`.`entry`                                   AS `entry`,
       `ce`.`active`                                  AS `active`,
       `ce`.`idEntity`                                AS `idEntity`,
       `ce`.`idColor`                                 AS `idColor`,
       `ce`.`optional`                                AS `optional`,
       `ce`.`head`                                    AS `head`,
       `ce`.`multiple`                                AS `multiple`,
       `webtool42_db`.`entry`.`name`                  AS `name`,
       `webtool42_db`.`entry`.`description`           AS `description`,
       `webtool42_db`.`entry`.`idLanguage`            AS `idLanguage`
from ((`webtool42_db`.`constructionelement` `ce` join `webtool42_db`.`construction`
       on (`ce`.`idConstruction` = `webtool42_db`.`construction`.`idConstruction`)) join `webtool42_db`.`entry`
      on (`ce`.`idEntity` = `webtool42_db`.`entry`.`idEntity`));

create definer = fnbrasil@`%` view view_constructionelement_relation as
select `relation`.`idEntityRelation` AS `idEntityRelation`,
       `relation`.`idRelationType`   AS `idRelationType`,
       `relation`.`relationType`     AS `relationType`,
       `relation`.`idRelation`       AS `idRelation`,
       `relation`.`nameCanonical`    AS `nameCanonical`,
       `relation`.`nameDirect`       AS `nameDirect`,
       `relation`.`nameInverse`      AS `nameInverse`,
       `relation`.`color`            AS `color`,
       `ce1`.`name`                  AS `ce1Name`,
       `ce1`.`idConstructionElement` AS `ce1IdConstructionElement`,
       `ce1`.`idEntity`              AS `ce1IdEntity`,
       `ce1`.`idLanguage`            AS `idLanguage`,
       `ce1`.`idColor`               AS `ce1IdColor`,
       `ce2`.`name`                  AS `ce2Name`,
       `ce2`.`idConstructionElement` AS `ce2IdConstructionElement`,
       `ce2`.`idEntity`              AS `ce2IdEntity`,
       `ce2`.`idColor`               AS `ce2IdColor`
from ((`webtool42_db`.`view_constructionelement` `ce1` join `webtool42_db`.`view_relation` `relation`
       on (`ce1`.`idEntity` = `relation`.`idEntity1`)) join `webtool42_db`.`view_constructionelement` `ce2`
      on (`relation`.`idEntity2` = `ce2`.`idEntity`))
where `relation`.`relationType` in ('rel_daughter_of', 'rel_inheritance_cxn')
  and `ce1`.`idLanguage` = `ce2`.`idLanguage`;

create definer = fnbrasil@`%` view view_corpus as
select `webtool42_db`.`corpus`.`idCorpus`   AS `idCorpus`,
       `webtool42_db`.`corpus`.`active`     AS `active`,
       `webtool42_db`.`corpus`.`idEntity`   AS `idEntity`,
       `webtool42_db`.`entry`.`name`        AS `name`,
       `webtool42_db`.`entry`.`description` AS `description`,
       `webtool42_db`.`entry`.`idLanguage`  AS `idLanguage`
from (`webtool42_db`.`corpus` join `webtool42_db`.`entry`
      on (`webtool42_db`.`corpus`.`idEntity` = `webtool42_db`.`entry`.`idEntity`));

create definer = fnbrasil@`%` view view_document as
select `webtool42_db`.`document`.`idDocument` AS `idDocument`,
       `webtool42_db`.`document`.`author`     AS `author`,
       `webtool42_db`.`document`.`active`     AS `active`,
       `webtool42_db`.`document`.`idGenre`    AS `idGenre`,
       `webtool42_db`.`document`.`idCorpus`   AS `idCorpus`,
       `webtool42_db`.`document`.`idEntity`   AS `idEntity`,
       `view_corpus`.`name`                   AS `corpusName`,
       `view_corpus`.`description`            AS `corpusDescription`,
       `webtool42_db`.`entry`.`name`          AS `name`,
       `webtool42_db`.`entry`.`description`   AS `description`,
       `webtool42_db`.`entry`.`idLanguage`    AS `idLanguage`
from ((`webtool42_db`.`document` join `webtool42_db`.`view_corpus`
       on (`webtool42_db`.`document`.`idCorpus` = `view_corpus`.`idCorpus`)) join `webtool42_db`.`entry`
      on (`webtool42_db`.`document`.`idEntity` = `webtool42_db`.`entry`.`idEntity`))
where `view_corpus`.`idLanguage` = `webtool42_db`.`entry`.`idLanguage`;

create definer = fnbrasil@`%` view view_document_image as
select `d`.`idDocument` AS `idDocument`, `i`.`idImage` AS `idImage`
from ((`webtool42_db`.`document_image` `di` join `webtool42_db`.`document` `d`
       on (`di`.`idDocument` = `d`.`idDocument`)) join `webtool42_db`.`image` `i` on (`di`.`idImage` = `i`.`idImage`));

create definer = fnbrasil@`%` view view_document_sentence as
select `ds`.`idDocumentSentence` AS `idDocumentSentence`,
       `ds`.`idDocument`         AS `idDocument`,
       `ds`.`idSentence`         AS `idSentence`
from `webtool42_db`.`document_sentence` `ds`;

create definer = fnbrasil@`%` view view_document_video as
select `d`.`idDocument` AS `idDocument`, `v`.`idVideo` AS `idVideo`
from ((`webtool42_db`.`document_video` `dv` join `webtool42_db`.`document` `d`
       on (`dv`.`idDocument` = `d`.`idDocument`)) join `webtool42_db`.`video` `v` on (`dv`.`idVideo` = `v`.`idVideo`));

create definer = fnbrasil@`%` view view_document_wordmm as
select `w`.`idWordMM`           AS `idWordMM`,
       `w`.`word`               AS `word`,
       `w`.`startTime`          AS `startTime`,
       `w`.`endTime`            AS `endTime`,
       `w`.`origin`             AS `origin`,
       `w`.`idDocumentSentence` AS `idDocumentSentence`,
       `ds`.`idDocument`        AS `idDocument`,
       `ds`.`idSentence`        AS `idSentence`
from (`webtool42_db`.`wordmm` `w` join `webtool42_db`.`document_sentence` `ds`
      on (`w`.`idDocumentSentence` = `ds`.`idDocumentSentence`));

create definer = fnbrasil@`%` view view_domain as
select `webtool42_db`.`domain`.`idDomain`   AS `idDomain`,
       `webtool42_db`.`domain`.`entry`      AS `entry`,
       `webtool42_db`.`domain`.`idEntity`   AS `idEntity`,
       `webtool42_db`.`entry`.`name`        AS `name`,
       `webtool42_db`.`entry`.`description` AS `description`,
       `webtool42_db`.`entry`.`idLanguage`  AS `idLanguage`
from (`webtool42_db`.`domain` join `webtool42_db`.`entry`
      on (`webtool42_db`.`domain`.`idEntity` = `webtool42_db`.`entry`.`idEntity`));

create definer = fnbrasil@`%` view view_domain_frame as
select `d`.`idDomain`   AS `idDomain`,
       `d`.`entry`      AS `domainEntry`,
       `d`.`idEntity`   AS `domainIdEntity`,
       `d`.`name`       AS `domainName`,
       `f`.`idEntity`   AS `frameIdEntity`,
       `f`.`entry`      AS `frameEntry`,
       `f`.`name`       AS `frameName`,
       `f`.`idLanguage` AS `idLanguage`
from ((`webtool42_db`.`view_domain` `d` join `webtool42_db`.`entityrelation` `er`
       on (`d`.`idEntity` = `er`.`idEntity2`)) join `webtool42_db`.`view_frame` `f`
      on (`er`.`idEntity1` = `f`.`idEntity`))
where `d`.`idLanguage` = `f`.`idLanguage`;

create definer = fnbrasil@`%` view view_domain_semantictype as
select `d`.`idDomain`    AS `idDomain`,
       `d`.`entry`       AS `domainEntry`,
       `d`.`idEntity`    AS `domainIdEntity`,
       `d`.`name`        AS `domainName`,
       `st`.`idEntity`   AS `stIdEntity`,
       `st`.`entry`      AS `stEntry`,
       `st`.`name`       AS `stName`,
       `st`.`idLanguage` AS `idLanguage`
from (`webtool42_db`.`view_domain` `d` join `webtool42_db`.`view_semantictype` `st`
      on (`d`.`idDomain` = `st`.`idDomain`))
where `d`.`idLanguage` = `st`.`idLanguage`;

create definer = fnbrasil@`%` view view_dynamicobject_boundingbox as
select `bb`.`idBoundingBox`    AS `idBoundingBox`,
       `bb`.`frameNumber`      AS `frameNumber`,
       `bb`.`frameTime`        AS `frameTime`,
       `bb`.`x`                AS `x`,
       `bb`.`y`                AS `y`,
       `bb`.`width`            AS `width`,
       `bb`.`height`           AS `height`,
       `bb`.`blocked`          AS `blocked`,
       `dob`.`idDynamicObject` AS `idDynamicObject`
from ((`webtool42_db`.`boundingbox` `bb` join `webtool42_db`.`dynamicobject_boundingbox` `db`
       on (`bb`.`idBoundingBox` = `db`.`idBoundingBox`)) join `webtool42_db`.`dynamicobject` `dob`
      on (`db`.`idDynamicObject` = `dob`.`idDynamicObject`));

create definer = fnbrasil@`%` view view_entrylanguage as
select `webtool42_db`.`entry`.`idEntry`     AS `idEntry`,
       `webtool42_db`.`entry`.`entry`       AS `entry`,
       `webtool42_db`.`entry`.`name`        AS `name`,
       `webtool42_db`.`entry`.`description` AS `description`,
       `webtool42_db`.`entry`.`nick`        AS `nick`,
       `webtool42_db`.`entry`.`idLanguage`  AS `idLanguage`,
       `webtool42_db`.`language`.`language` AS `language`
from (`webtool42_db`.`entry` join `webtool42_db`.`language`
      on (`webtool42_db`.`entry`.`idLanguage` = `webtool42_db`.`language`.`idLanguage`));

-- comment on column view_entrylanguage.language not supported: Two-letter ISO 639-1 language codes + region, See: http://www.w3.org/International/articles/language-tags/

create definer = fnbrasil@`%` view view_fe_internal_relation as
select `relation`.`idEntityRelation` AS `idEntityRelation`,
       `relation`.`idRelationType`   AS `idRelationType`,
       `relation`.`relationType`     AS `relationType`,
       `relation`.`nameCanonical`    AS `nameCanonical`,
       `relation`.`nameDirect`       AS `nameDirect`,
       `relation`.`nameInverse`      AS `nameInverse`,
       `relation`.`color`            AS `color`,
       `fe1`.`name`                  AS `fe1Name`,
       `fe1`.`idFrameElement`        AS `fe1IdFrameElement`,
       `fe1`.`idEntity`              AS `fe1IdEntity`,
       `fe1`.`idFrame`               AS `fe1IdFrame`,
       `fe1`.`coreType`              AS `fe1CoreType`,
       `fe1`.`idColor`               AS `fe1IdColor`,
       `fe1`.`idLanguage`            AS `idLanguage`,
       `fe2`.`name`                  AS `fe2Name`,
       `fe2`.`idFrameElement`        AS `fe2IdFrameElement`,
       `fe2`.`idEntity`              AS `fe2IdEntity`,
       `fe2`.`idFrame`               AS `fe2IdFrame`,
       `fe2`.`coreType`              AS `fe2CoreType`,
       `fe2`.`idColor`               AS `fe2IdColor`
from ((`webtool42_db`.`view_frameelement` `fe1` join `webtool42_db`.`view_relation` `relation`
       on (`fe1`.`idEntity` = `relation`.`idEntity1`)) join `webtool42_db`.`view_frameelement` `fe2`
      on (`relation`.`idEntity2` = `fe2`.`idEntity`))
where `relation`.`relationType` in ('rel_coreset', 'rel_excludes', 'rel_requires')
  and `fe1`.`idLanguage` = `fe2`.`idLanguage`;

create definer = fnbrasil@`%` view view_fe_relation as
select `relation`.`idEntityRelation` AS `idEntityRelation`,
       `relation`.`idRelationType`   AS `idRelationType`,
       `relation`.`relationType`     AS `relationType`,
       `relation`.`idRelation`       AS `idRelation`,
       `relation`.`nameCanonical`    AS `nameCanonical`,
       `relation`.`nameDirect`       AS `nameDirect`,
       `relation`.`nameInverse`      AS `nameInverse`,
       `relation`.`color`            AS `color`,
       `fe1`.`name`                  AS `fe1Name`,
       `fe1`.`idFrameElement`        AS `fe1IdFrameElement`,
       `fe1`.`idEntity`              AS `fe1IdEntity`,
       `fe1`.`idFrame`               AS `fe1IdFrame`,
       `fe1`.`coreType`              AS `fe1CoreType`,
       `fe1`.`idColor`               AS `fe1IdColor`,
       `fe1`.`idLanguage`            AS `idLanguage`,
       `fe2`.`name`                  AS `fe2Name`,
       `fe2`.`idFrameElement`        AS `fe2IdFrameElement`,
       `fe2`.`idEntity`              AS `fe2IdEntity`,
       `fe2`.`idFrame`               AS `fe2IdFrame`,
       `fe2`.`coreType`              AS `fe2CoreType`,
       `fe2`.`idColor`               AS `fe2IdColor`
from ((`webtool42_db`.`view_frameelement` `fe1` join `webtool42_db`.`view_relation` `relation`
       on (`fe1`.`idEntity` = `relation`.`idEntity1`)) join `webtool42_db`.`view_frameelement` `fe2`
      on (`relation`.`idEntity2` = `fe2`.`idEntity`))
where `relation`.`relationType` in
      ('rel_causative_of', 'rel_inchoative_of', 'rel_inheritance', 'rel_perspective_on', 'rel_precedes', 'rel_see_also',
       'rel_subframe', 'rel_structure', 'rel_using', 'rel_metaphorical_projection')
  and `fe1`.`idLanguage` = `fe2`.`idLanguage`;

create definer = fnbrasil@`%` view view_frame as
select `webtool42_db`.`frame`.`idFrame`     AS `idFrame`,
       `webtool42_db`.`frame`.`entry`       AS `entry`,
       `webtool42_db`.`frame`.`active`      AS `active`,
       `webtool42_db`.`frame`.`idEntity`    AS `idEntity`,
       `webtool42_db`.`entry`.`name`        AS `name`,
       `webtool42_db`.`entry`.`description` AS `description`,
       `webtool42_db`.`entry`.`idLanguage`  AS `idLanguage`
from (`webtool42_db`.`frame` join `webtool42_db`.`entry`
      on (`webtool42_db`.`frame`.`idEntity` = `webtool42_db`.`entry`.`idEntity`));

create definer = fnbrasil@`%` view view_frame_classification as
select `st`.`idSemanticType`            AS `idSemanticType`,
       `relation`.`relationType`        AS `relationType`,
       `e`.`name`                       AS `name`,
       `e`.`idLanguage`                 AS `idLanguage`,
       `webtool42_db`.`frame`.`idFrame` AS `idFrame`
from (((`webtool42_db`.`frame` join `webtool42_db`.`view_relation` `relation`
        on (`webtool42_db`.`frame`.`idEntity` = `relation`.`idEntity1`)) join `webtool42_db`.`semantictype` `st`
       on (`relation`.`idEntity2` = `st`.`idEntity`)) join `webtool42_db`.`entry` `e`
      on (`e`.`idEntity` = `st`.`idEntity`))
where `relation`.`relationType` in ('rel_framal_type', 'rel_framal_domain');

create definer = fnbrasil@`%` view view_frame_relation as
select `relation`.`idEntityRelation` AS `idEntityRelation`,
       `relation`.`idRelationType`   AS `idRelationType`,
       `relation`.`relationType`     AS `relationType`,
       `relation`.`nameCanonical`    AS `nameCanonical`,
       `relation`.`nameDirect`       AS `nameDirect`,
       `relation`.`nameInverse`      AS `nameInverse`,
       `relation`.`color`            AS `color`,
       `f1`.`name`                   AS `f1Name`,
       `f1`.`idFrame`                AS `f1IdFrame`,
       `f1`.`idEntity`               AS `f1IdEntity`,
       `f1`.`idLanguage`             AS `idLanguage`,
       `f2`.`name`                   AS `f2Name`,
       `f2`.`idFrame`                AS `f2IdFrame`,
       `f2`.`idEntity`               AS `f2IdEntity`
from ((`webtool42_db`.`view_frame` `f1` join `webtool42_db`.`view_relation` `relation`
       on (`f1`.`idEntity` = `relation`.`idEntity1`)) join `webtool42_db`.`view_frame` `f2`
      on (`relation`.`idEntity2` = `f2`.`idEntity`))
where `relation`.`relationType` in
      ('rel_causative_of', 'rel_inchoative_of', 'rel_inheritance', 'rel_perspective_on', 'rel_precedes', 'rel_see_also',
       'rel_subframe', 'rel_structure', 'rel_using', 'rel_metaphorical_projection')
  and `f1`.`idLanguage` = `f2`.`idLanguage`;

create definer = fnbrasil@`%` view view_frameelement as
select `webtool42_db`.`frame`.`idFrame`     AS `idFrame`,
       `webtool42_db`.`frame`.`entry`       AS `frameEntry`,
       `webtool42_db`.`frame`.`idEntity`    AS `frameIdEntity`,
       `entryframe`.`name`                  AS `frameName`,
       `fe`.`idFrameElement`                AS `idFrameElement`,
       `fe`.`entry`                         AS `entry`,
       `fe`.`active`                        AS `active`,
       `fe`.`idEntity`                      AS `idEntity`,
       `fe`.`idColor`                       AS `idColor`,
       `fe`.`coreType`                      AS `coreType`,
       `webtool42_db`.`entry`.`name`        AS `name`,
       `webtool42_db`.`entry`.`description` AS `description`,
       `webtool42_db`.`entry`.`idLanguage`  AS `idLanguage`,
       `entryen`.`name`                     AS `nameEn`
from ((((`webtool42_db`.`frameelement` `fe` join `webtool42_db`.`frame`
         on (`fe`.`idFrame` = `webtool42_db`.`frame`.`idFrame`)) join `webtool42_db`.`entry`
        on (`fe`.`idEntity` = `webtool42_db`.`entry`.`idEntity`)) join `webtool42_db`.`entry` `entryframe`
       on (`webtool42_db`.`frame`.`idEntity` = `entryframe`.`idEntity`)) join `webtool42_db`.`entry` `entryen`
      on (`fe`.`idEntity` = `entryen`.`idEntity` and `entryen`.`idLanguage` = 2))
where `webtool42_db`.`entry`.`idLanguage` = `entryframe`.`idLanguage`
order by `webtool42_db`.`entry`.`name`;

create definer = fnbrasil@`%` view view_group_user as
select `webtool42_db`.`group`.`idGroup`     AS `idGroup`,
       `webtool42_db`.`group`.`name`        AS `name`,
       `webtool42_db`.`user_group`.`idUser` AS `idUser`
from (`webtool42_db`.`group` join `webtool42_db`.`user_group`
      on (`webtool42_db`.`group`.`idGroup` = `webtool42_db`.`user_group`.`idGroup`));

create definer = fnbrasil@`%` view view_image_sentence as
select `i`.`idImage` AS `idImage`, `s`.`idSentence` AS `idSentence`
from ((`webtool42_db`.`image_sentence` `ise` join `webtool42_db`.`image` `i`
       on (`ise`.`idImage` = `i`.`idImage`)) join `webtool42_db`.`view_sentence` `s`
      on (`ise`.`idSentence` = `s`.`idSentence`));

create definer = fnbrasil@`%` view view_instantiationtype as
select `t`.`idType`                         AS `idInstantiationType`,
       `t`.`entry`                          AS `entry`,
       `t`.`info`                           AS `info`,
       `t`.`flag`                           AS `flag`,
       `t`.`idType`                         AS `idType`,
       `t`.`idTypeGroup`                    AS `idTypeGroup`,
       `t`.`idColor`                        AS `idColor`,
       `t`.`idEntity`                       AS `idEntity`,
       `webtool42_db`.`entry`.`name`        AS `name`,
       `webtool42_db`.`entry`.`description` AS `description`,
       `webtool42_db`.`entry`.`idLanguage`  AS `idLanguage`
from ((`webtool42_db`.`type` `t` join `webtool42_db`.`typegroup`
       on (`t`.`idTypeGroup` = `webtool42_db`.`typegroup`.`idTypeGroup`)) join `webtool42_db`.`entry`
      on (`t`.`idEntity` = `webtool42_db`.`entry`.`idEntity`))
where `webtool42_db`.`typegroup`.`entry` = 'typ_instantiationtype';

create definer = fnbrasil@`%` view view_layer as
select `l`.`idLayer`                        AS `idLayer`,
       `l`.`rank`                           AS `rank`,
       `l`.`idAnnotationSet`                AS `idAnnotationSet`,
       `l`.`idLayerType`                    AS `idLayerType`,
       `lt`.`entry`                         AS `entry`,
       `lt`.`idEntity`                      AS `idEntity`,
       `lt`.`layerOrder`                    AS `layerOrder`,
       `webtool42_db`.`entry`.`name`        AS `name`,
       `webtool42_db`.`entry`.`description` AS `description`,
       `webtool42_db`.`entry`.`idLanguage`  AS `idLanguage`
from ((`webtool42_db`.`layer` `l` join `webtool42_db`.`layertype` `lt`
       on (`l`.`idLayerType` = `lt`.`idLayerType`)) join `webtool42_db`.`entry`
      on (`lt`.`idEntity` = `webtool42_db`.`entry`.`idEntity`));

create definer = fnbrasil@`%` view view_layertype as
select `lt`.`idLayerType`       AS `idLayerType`,
       `lt`.`entry`             AS `entry`,
       `lt`.`allowsApositional` AS `allowsApositional`,
       `lt`.`isAnnotation`      AS `isAnnotation`,
       `lt`.`layerOrder`        AS `layerOrder`,
       `lt`.`idLayerGroup`      AS `idLayerGroup`,
       `lt`.`idEntity`          AS `idEntity`,
       `e`.`name`               AS `name`,
       `e`.`description`        AS `description`,
       `e`.`idLanguage`         AS `idLanguage`,
       `lg`.`name`              AS `layerGroup`,
       `lg`.`type`              AS `layerGroupType`
from ((`webtool42_db`.`layertype` `lt` join `webtool42_db`.`layergroup` `lg`
       on (`lt`.`idLayerGroup` = `lg`.`idLayerGroup`)) join `webtool42_db`.`entry` `e`
      on (`lt`.`idEntity` = `e`.`idEntity`));

create definer = fnbrasil@`%` view view_layertype_gl as
select `lt`.`idLayerType`       AS `idLayerType`,
       `lt`.`entry`             AS `entry`,
       `lt`.`allowsApositional` AS `allowsApositional`,
       `lt`.`isAnnotation`      AS `isAnnotation`,
       `lt`.`layerOrder`        AS `layerOrder`,
       `lt`.`idLayerGroup`      AS `idLayerGroup`,
       `lt`.`idEntity`          AS `idEntityLayerType`,
       `gl`.`idGenericLabel`    AS `idGenericLabel`,
       `gl`.`name`              AS `name`,
       `gl`.`definition`        AS `definition`,
       `gl`.`example`           AS `example`,
       `gl`.`idEntity`          AS `idEntityGenericLabel`,
       `gl`.`idColor`           AS `idColor`,
       `gl`.`idLanguage`        AS `idLanguage`
from ((`webtool42_db`.`layertype` `lt` join `webtool42_db`.`view_relation` `r`
       on (`lt`.`idEntity` = `r`.`idEntity1`)) join `webtool42_db`.`genericlabel` `gl`
      on (`r`.`idEntity2` = `gl`.`idEntity`));

create definer = fnbrasil@`%` view view_lemma as
select `webtool42_db`.`lemma`.`idLanguage`                                                    AS `idLanguage`,
       `webtool42_db`.`lemma`.`idLemma`                                                       AS `idLemma`,
       `webtool42_db`.`lemma`.`name`                                                          AS `name`,
       `webtool42_db`.`lemma`.`idPOS`                                                         AS `idPOS`,
       `webtool42_db`.`lemma`.`idEntity`                                                      AS `idEntity`,
       `webtool42_db`.`pos`.`POS`                                                             AS `POS`,
       concat(`webtool42_db`.`lemma`.`name`, ' [', `webtool42_db`.`language`.`language`, ']') AS `fullName`
from ((`webtool42_db`.`lemma` join `webtool42_db`.`pos`
       on (`webtool42_db`.`lemma`.`idPOS` = `webtool42_db`.`pos`.`idPOS`)) join `webtool42_db`.`language`
      on (`webtool42_db`.`lemma`.`idLanguage` = `webtool42_db`.`language`.`idLanguage`));

create definer = fnbrasil@`%` view view_lexicon as
select `wf`.`idLexicon`                       AS `idWordForm`,
       `wf`.`form`                            AS `form`,
       `wf`.`idEntity`                        AS `idEntityWF`,
       `le`.`idLexiconExpression`             AS `idLexiconExpression`,
       `le`.`position`                        AS `position`,
       `le`.`breakBefore`                     AS `breakBefore`,
       `le`.`head`                            AS `head`,
       `lm`.`idLexicon`                       AS `idLemma`,
       `lm`.`shortName`                       AS `lemma`,
       `lm`.`idEntity`                        AS `idEntityLM`,
       `lm`.`idLanguage`                      AS `idLanguageLM`,
       `lm`.`idPOS`                           AS `idPOSLM`,
       `lm`.`POS`                             AS `posLM`,
       `lm`.`udPOS`                           AS `udPOSLM`,
       `webtool42_db`.`lu`.`idLU`             AS `idLU`,
       `webtool42_db`.`lu`.`name`             AS `lu`,
       `webtool42_db`.`lu`.`senseDescription` AS `senseDescription`,
       `webtool42_db`.`lu`.`incorporatedFE`   AS `incorporatedFE`,
       `webtool42_db`.`lu`.`idFrame`          AS `idFrame`,
       `webtool42_db`.`lu`.`idEntity`         AS `idEntityLU`
from (((`webtool42_db`.`lexicon` `wf` left join `webtool42_db`.`lexicon_expression` `le`
        on (`wf`.`idLexicon` = `le`.`idExpression`)) left join `webtool42_db`.`view_lexicon_lemma` `lm`
       on (`le`.`idLexicon` = `lm`.`idLexicon`)) left join `webtool42_db`.`lu`
      on (`lm`.`idLexicon` = `webtool42_db`.`lu`.`idLexicon`));

create definer = fnbrasil@`%` view view_lexicon_402 as
select `wf`.`idWordForm`                      AS `idWordForm`,
       `wf`.`form`                            AS `form`,
       `wf`.`md5`                             AS `md5`,
       `wf`.`altSpell`                        AS `altSpell`,
       `wf`.`idEntity`                        AS `idEntityWF`,
       `lx`.`idLexeme`                        AS `idLexeme`,
       `lx`.`name`                            AS `lexeme`,
       `lx`.`idLanguage`                      AS `idLanguageLX`,
       `lx`.`idEntity`                        AS `idEntityLX`,
       `poslx`.`POS`                          AS `posLX`,
       `le`.`idLexemeEntry`                   AS `idLexemeEntry`,
       `le`.`lexemeOrder`                     AS `lexemeOrder`,
       `le`.`breakBefore`                     AS `breakBefore`,
       `le`.`headWord`                        AS `headWord`,
       `lm`.`idLemma`                         AS `idLemma`,
       `lm`.`name`                            AS `lemma`,
       `lm`.`idEntity`                        AS `idEntityLM`,
       `lm`.`idLanguage`                      AS `idLanguageLM`,
       `lm`.`idPOS`                           AS `idPOSLM`,
       `poslm`.`POS`                          AS `posLM`,
       `webtool42_db`.`lu`.`idLU`             AS `idLU`,
       `webtool42_db`.`lu`.`name`             AS `lu`,
       `webtool42_db`.`lu`.`senseDescription` AS `senseDescription`,
       `webtool42_db`.`lu`.`incorporatedFE`   AS `incorporatedFE`,
       `webtool42_db`.`lu`.`idFrame`          AS `idFrame`,
       `webtool42_db`.`lu`.`idEntity`         AS `idEntityLU`
from ((((((`webtool42_db`.`wordform` `wf` join `webtool42_db`.`lexeme` `lx`
           on (`wf`.`idLexeme` = `lx`.`idLexeme`)) join `webtool42_db`.`pos` `poslx`
          on (`lx`.`idPOS` = `poslx`.`idPOS`)) left join `webtool42_db`.`lexemeentry` `le`
         on (`lx`.`idLexeme` = `le`.`idLexeme`)) left join `webtool42_db`.`lemma` `lm`
        on (`le`.`idLemma` = `lm`.`idLemma`)) left join `webtool42_db`.`pos` `poslm`
       on (`lm`.`idPOS` = `poslm`.`idPOS`)) left join `webtool42_db`.`lu`
      on (`lm`.`idLemma` = `webtool42_db`.`lu`.`idLemma`));

create definer = fnbrasil@`%` view view_lexicon_expression as
select `le`.`idLexiconExpression`   AS `idLexiconExpression`,
       `lemma`.`idLanguage`         AS `idLanguage`,
       `lemma`.`idLexicon`          AS `idLemma`,
       `lemma`.`form`               AS `lemma`,
       `lemma`.`idEntity`           AS `idEntity`,
       `lemma`.`idPOS`              AS `idPOS`,
       `webtool42_db`.`pos`.`POS`   AS `POS`,
       lcase(concat(`lemma`.`form`, '.', `webtool42_db`.`pos`.`POS`, ' [', `webtool42_db`.`language`.`language`,
                    ']'))           AS `fullName`,
       `lemma`.`idUDPOS`            AS `idUDPOS`,
       `webtool42_db`.`udpos`.`POS` AS `udPOS`,
       lcase(concat(`lemma`.`form`, '.', `webtool42_db`.`udpos`.`POS`, ' [', `webtool42_db`.`language`.`language`,
                    ']'))           AS `fullNameUD`,
       `expression`.`idLexicon`     AS `idForm`,
       `expression`.`form`          AS `form`,
       `le`.`head`                  AS `head`,
       `le`.`breakBefore`           AS `breakBefore`,
       `le`.`position`              AS `position`
from ((((((`webtool42_db`.`lexicon` `lemma` join `webtool42_db`.`lexicon_expression` `le`
           on (`lemma`.`idLexicon` = `le`.`idLexicon`)) join `webtool42_db`.`lexicon` `expression`
          on (`le`.`idExpression` = `expression`.`idLexicon`)) join `webtool42_db`.`lexicon_group` `lg`
         on (`lemma`.`idLexiconGroup` = `lg`.`idLexiconGroup`)) join `webtool42_db`.`pos`
        on (`lemma`.`idPOS` = `webtool42_db`.`pos`.`idPOS`)) join `webtool42_db`.`udpos`
       on (`lemma`.`idUDPOS` = `webtool42_db`.`udpos`.`idUDPOS`)) join `webtool42_db`.`language`
      on (`lemma`.`idLanguage` = `webtool42_db`.`language`.`idLanguage`))
where `lemma`.`idLexiconGroup` = 2;

create definer = fnbrasil@`%` view view_lexicon_form as
select `l`.`idLanguage`                                                                            AS `idLanguage`,
       `l`.`idLexicon`                                                                             AS `idLexicon`,
       `l`.`form`                                                                                  AS `form`,
       `l`.`idLexiconGroup`                                                                        AS `idLexiconGroup`,
       `l`.`idEntity`                                                                              AS `idEntity`,
       `l`.`idPOS`                                                                                 AS `idPOS`,
       `webtool42_db`.`pos`.`POS`                                                                  AS `POS`,
       if(`l`.`idUDPOS`, lcase(concat(`l`.`form`, '.', `webtool42_db`.`udpos`.`POS`)), `l`.`form`) AS `shortName`,
       if(`l`.`idPOS`,
          lcase(concat(`l`.`form`, '.', `webtool42_db`.`pos`.`POS`, ' [', `webtool42_db`.`language`.`language`, ']')),
          `l`.`form`)                                                                              AS `fullName`,
       `l`.`idUDPOS`                                                                               AS `idUDPOS`,
       `webtool42_db`.`udpos`.`POS`                                                                AS `udPOS`,
       if(`l`.`idUDPOS`,
          lcase(concat(`l`.`form`, '.', `webtool42_db`.`udpos`.`POS`, ' [', `webtool42_db`.`language`.`language`, ']')),
          `l`.`form`)                                                                              AS `fullNameUD`
from ((((`webtool42_db`.`lexicon` `l` join `webtool42_db`.`lexicon_group` `lg`
         on (`l`.`idLexiconGroup` = `lg`.`idLexiconGroup`)) join `webtool42_db`.`language`
        on (`l`.`idLanguage` = `webtool42_db`.`language`.`idLanguage`)) left join `webtool42_db`.`pos`
       on (`l`.`idPOS` = `webtool42_db`.`pos`.`idPOS`)) left join `webtool42_db`.`udpos`
      on (`l`.`idUDPOS` = `webtool42_db`.`udpos`.`idUDPOS`))
where `l`.`idLexiconGroup` <> 2;

create definer = fnbrasil@`%` view view_lexicon_lemma as
select `l`.`idLanguage`                                                                                              AS `idLanguage`,
       `l`.`idLexicon`                                                                                               AS `idLexicon`,
       `l`.`form`                                                                                                    AS `name`,
       `l`.`idEntity`                                                                                                AS `idEntity`,
       `l`.`idPOS`                                                                                                   AS `idPOS`,
       `webtool42_db`.`pos`.`POS`                                                                                    AS `POS`,
       lcase(concat(`l`.`form`, '.', `webtool42_db`.`udpos`.`POS`))                                                  AS `shortName`,
       lcase(concat(`l`.`form`, '.', `webtool42_db`.`pos`.`POS`, ' [', `webtool42_db`.`language`.`language`,
                    ']'))                                                                                            AS `fullName`,
       `l`.`idUDPOS`                                                                                                 AS `idUDPOS`,
       `webtool42_db`.`udpos`.`POS`                                                                                  AS `udPOS`,
       lcase(concat(`l`.`form`, '.', `webtool42_db`.`udpos`.`POS`, ' [', `webtool42_db`.`language`.`language`,
                    ']'))                                                                                            AS `fullNameUD`
from ((((`webtool42_db`.`lexicon` `l` join `webtool42_db`.`lexicon_group` `lg`
         on (`l`.`idLexiconGroup` = `lg`.`idLexiconGroup`)) join `webtool42_db`.`pos`
        on (`l`.`idPOS` = `webtool42_db`.`pos`.`idPOS`)) join `webtool42_db`.`udpos`
       on (`l`.`idUDPOS` = `webtool42_db`.`udpos`.`idUDPOS`)) join `webtool42_db`.`language`
      on (`l`.`idLanguage` = `webtool42_db`.`language`.`idLanguage`))
where `l`.`idLexiconGroup` = 2;

create definer = fnbrasil@`%` view view_lu as
select `frame`.`idLanguage`                   AS `idLanguageFrame`,
       `frame`.`idFrame`                      AS `idFrame`,
       `frame`.`idEntity`                     AS `frameIdEntity`,
       `frame`.`name`                         AS `frameName`,
       `webtool42_db`.`lu`.`idLU`             AS `idLU`,
       `webtool42_db`.`lu`.`name`             AS `name`,
       `webtool42_db`.`lu`.`senseDescription` AS `senseDescription`,
       `webtool42_db`.`lu`.`active`           AS `active`,
       `webtool42_db`.`lu`.`importNum`        AS `importNum`,
       `webtool42_db`.`lu`.`incorporatedFE`   AS `incorporatedFE`,
       `webtool42_db`.`lu`.`idEntity`         AS `idEntity`,
       `webtool42_db`.`lu`.`idLemma`          AS `idLemma`,
       `webtool42_db`.`lu`.`idLexicon`        AS `idLexicon`,
       `lemma`.`name`                         AS `lemmaName`,
       `lemma`.`idPOS`                        AS `idPOS`,
       `lemma`.`idUDPOS`                      AS `idUDPOS`,
       `lemma`.`idLanguage`                   AS `idLanguage`
from ((`webtool42_db`.`lu` join `webtool42_db`.`view_frame` `frame`
       on (`webtool42_db`.`lu`.`idFrame` = `frame`.`idFrame`)) join `webtool42_db`.`view_lexicon_lemma` `lemma`
      on (`webtool42_db`.`lu`.`idLexicon` = `lemma`.`idLexicon`))
where `lemma`.`idLanguage` = `frame`.`idLanguage`;

create definer = fnbrasil@`%` view view_lucandidate as
select `frame`.`name`                AS `frameName`,
       `lu`.`frameCandidate`         AS `frameCandidate`,
       `frame`.`idFrame`             AS `idFrame`,
       `lu`.`idLUCandidate`          AS `idLUCandidate`,
       `lu`.`name`                   AS `name`,
       `lu`.`senseDescription`       AS `senseDescription`,
       `lu`.`idLemma`                AS `idLemma`,
       `lu`.`discussion`             AS `discussion`,
       `lu`.`idDocument`             AS `idDocument`,
       `lu`.`idDocumentSentence`     AS `idDocumentSentence`,
       `lu`.`idBoundingBox`          AS `idBoundingBox`,
       `lu`.`idUser`                 AS `idUser`,
       `lu`.`incorporatedFE`         AS `incorporatedFE`,
       `lu`.`createdAt`              AS `createdAt`,
       `lu`.`idLexicon`              AS `idLexicon`,
       `lemma`.`shortName`           AS `lemmaName`,
       `lemma`.`idPOS`               AS `idPOS`,
       `lemma`.`idUDPOS`             AS `idUDPOS`,
       `lemma`.`idLanguage`          AS `idLanguage`,
       `webtool42_db`.`user`.`name`  AS `userName`,
       `webtool42_db`.`user`.`email` AS `email`
from (((`webtool42_db`.`lucandidate` `lu` join `webtool42_db`.`view_lexicon_lemma` `lemma`
        on (`lu`.`idLexicon` = `lemma`.`idLexicon`)) join `webtool42_db`.`user`
       on (`lu`.`idUser` = `webtool42_db`.`user`.`idUser`)) left join `webtool42_db`.`view_frame` `frame`
      on (`lu`.`idFrame` = `frame`.`idFrame`))
where `lemma`.`idLanguage` = `frame`.`idLanguage`
   or `frame`.`idLanguage` is null;

create definer = fnbrasil@`%` view view_project_docs as
select distinct `p`.`idProject`    AS `idProject`,
                `p`.`name`         AS `projectName`,
                `c`.`idCorpus`     AS `idCorpus`,
                `c`.`name`         AS `corpusName`,
                `doc`.`idDocument` AS `idDocument`,
                `doc`.`name`       AS `documentName`,
                `doc`.`idLanguage` AS `idLanguage`
from ((((`webtool42_db`.`project` `p` join `webtool42_db`.`project_dataset` `d`
         on (`p`.`idProject` = `d`.`idProject`)) join `webtool42_db`.`dataset_corpus` `dc`
        on (`d`.`idDataset` = `dc`.`idDataset`)) join `webtool42_db`.`view_corpus` `c`
       on (`dc`.`idCorpus` = `c`.`idCorpus`)) join `webtool42_db`.`view_document` `doc`
      on (`c`.`idCorpus` = `doc`.`idCorpus`))
where `doc`.`idLanguage` = `c`.`idLanguage`;

create definer = fnbrasil@`%` view view_project_tasks as
select distinct `p`.`idProject`    AS `idProject`,
                `p`.`name`         AS `projectName`,
                `t`.`idTask`       AS `idTask`,
                `t`.`name`         AS `taskName`,
                `t`.`description`  AS `taskDescription`,
                `tg`.`idTaskGroup` AS `idTaskGroup`,
                `tg`.`name`        AS `taskGroupName`
from ((`webtool42_db`.`project` `p` join `webtool42_db`.`task` `t`
       on (`p`.`idProject` = `t`.`idProject`)) join `webtool42_db`.`taskgroup` `tg`
      on (`t`.`idTaskGroup` = `tg`.`idTaskGroup`));

create definer = fnbrasil@`%` view view_qualia as
select `eq`.`idLanguage`          AS `idLanguage`,
       `q`.`idQualia`             AS `idQualia`,
       substr(`t`.`entry`, 5, 15) AS `type`,
       `q`.`info`                 AS `info`,
       `q`.`infoInverse`          AS `infoinverse`,
       `q`.`idEntity`             AS `idEntity`,
       `eq`.`name`                AS `name`,
       `eq`.`description`         AS `description`,
       `q`.`idType`               AS `idType`,
       `f`.`idFrame`              AS `idFrame`,
       `f`.`name`                 AS `frameName`,
       `fe1`.`idFrameElement`     AS `idFrameElement1`,
       `fe1`.`name`               AS `fe1Name`,
       `fe2`.`idFrameElement`     AS `idFrameElement2`,
       `fe2`.`name`               AS `fe2Name`
from (((((`webtool42_db`.`qualia` `q` join `webtool42_db`.`entry` `eq`
          on (`q`.`idEntity` = `eq`.`idEntity`)) join `webtool42_db`.`type` `t`
         on (`q`.`idType` = `t`.`idType`)) left join `webtool42_db`.`view_frame` `f`
        on (`q`.`idFrame` = `f`.`idFrame`)) left join `webtool42_db`.`view_frameelement` `fe1`
       on (`q`.`idFrameElement1` = `fe1`.`idFrameElement`)) left join `webtool42_db`.`view_frameelement` `fe2`
      on (`q`.`idFrameElement2` = `fe2`.`idFrameElement`))
where (`f`.`idLanguage` = `eq`.`idLanguage` or `f`.`idLanguage` is null)
  and (`fe1`.`idLanguage` = `eq`.`idLanguage` or `fe1`.`idLanguage` is null)
  and (`fe2`.`idLanguage` = `eq`.`idLanguage` or `fe2`.`idLanguage` is null);

create definer = fnbrasil@`%` view view_qualiaargument as
select `qa`.`idQualiaArgument`  AS `idQualiaArgument`,
       `qa`.`order`             AS `order`,
       `qa`.`type`              AS `type`,
       `qa`.`idQualiaStructure` AS `idQualiaStructure`,
       `fe`.`name`              AS `feName`,
       `fe`.`coreType`          AS `feCoreType`,
       `fe`.`idColor`           AS `feIdColor`,
       `fe`.`idLanguage`        AS `idLanguage`
from (`webtool42_db`.`qualiaargument` `qa` join `webtool42_db`.`view_frameelement` `fe`
      on (`qa`.`idFrameElement` = `fe`.`idFrameElement`));

create definer = fnbrasil@`%` view view_qualialu as
select `ql`.`idQualiaLU`      AS `idQualiaLU`,
       `lu1`.`idLU`           AS `idLU1`,
       `lu1`.`name`           AS `lu1`,
       `lu2`.`idLU`           AS `idLU2`,
       `lu2`.`name`           AS `lu2`,
       `qa1`.`idFrameElement` AS `idFrameElement1`,
       `qa1`.`order`          AS `order1`,
       `qa1`.`type`           AS `type1`,
       `qa2`.`idFrameElement` AS `idFrameElement2`,
       `qa2`.`order`          AS `order2`,
       `qa2`.`type`           AS `type2`
from (((((`webtool42_db`.`qualialu` `ql` join `webtool42_db`.`view_lu` `lu1`
          on (`ql`.`idLU1` = `lu1`.`idLU`)) join `webtool42_db`.`view_lu` `lu2`
         on (`ql`.`idLU2` = `lu2`.`idLU`)) join `webtool42_db`.`view_qualiastructure` `qs`
        on (`ql`.`idQualiaStructure` = `qs`.`idQualiaStructure`)) join `webtool42_db`.`qualiaargument` `qa1`
       on (`qa1`.`idQualiaStructure` = `qs`.`idQualiaStructure` and
           `qa1`.`order` = 1)) join `webtool42_db`.`qualiaargument` `qa2`
      on (`qa2`.`idQualiaStructure` = `qs`.`idQualiaStructure` and `qa2`.`order` = 2));

create definer = fnbrasil@`%` view view_qualiastructure as
select `qs`.`idQualiaStructure` AS `idQualiaStructure`,
       `f`.`idFrame`            AS `idFrame`,
       `f`.`idEntity`           AS `idEntity`,
       `f`.`name`               AS `name`,
       `f`.`idLanguage`         AS `idLanguage`,
       `qr`.`name`              AS `relation`,
       `qr`.`idQualiaRelation`  AS `idQualiaRelation`
from ((`webtool42_db`.`qualiastructure` `qs` join `webtool42_db`.`view_frame` `f`
       on (`qs`.`idFrame` = `f`.`idFrame`)) join `webtool42_db`.`qualiarelation` `qr`
      on (`qs`.`idQualiaRelation` = `qr`.`idQualiaRelation`));

create definer = fnbrasil@`%` view view_relation as
select `er`.`idEntityRelation` AS `idEntityRelation`,
       `rg`.`idRelationGroup`  AS `idRelationGroup`,
       `rg`.`entry`            AS `relationGroup`,
       `rt`.`idRelationType`   AS `idRelationType`,
       `rt`.`entry`            AS `relationType`,
       `rt`.`idEntity`         AS `idEntity`,
       `rt`.`prefix`           AS `prefix`,
       `rt`.`nameCanonical`    AS `nameCanonical`,
       `rt`.`nameDirect`       AS `nameDirect`,
       `rt`.`nameInverse`      AS `nameInverse`,
       `rt`.`color`            AS `color`,
       `er`.`idEntity1`        AS `idEntity1`,
       `e1`.`type`             AS `entity1Type`,
       `er`.`idEntity2`        AS `idEntity2`,
       `e2`.`type`             AS `entity2Type`,
       `er`.`idEntity3`        AS `idEntity3`,
       `e3`.`type`             AS `entity3Type`,
       `er`.`idRelation`       AS `idRelation`
from (((((`webtool42_db`.`entityrelation` `er` join `webtool42_db`.`relationtype` `rt`
          on (`er`.`idRelationType` = `rt`.`idRelationType`)) join `webtool42_db`.`relationgroup` `rg`
         on (`rt`.`idRelationGroup` = `rg`.`idRelationGroup`)) join `webtool42_db`.`entity` `e1`
        on (`er`.`idEntity1` = `e1`.`idEntity`)) join `webtool42_db`.`entity` `e2`
       on (`er`.`idEntity2` = `e2`.`idEntity`)) left join `webtool42_db`.`entity` `e3`
      on (`er`.`idEntity3` = `e3`.`idEntity`));

create definer = fnbrasil@`%` view view_relationgroup as
select `rg`.`idRelationGroup` AS `idRelationGroup`,
       `rg`.`entry`           AS `entry`,
       `rg`.`idEntity`        AS `idEntity`,
       `e`.`name`             AS `name`,
       `e`.`description`      AS `description`,
       `e`.`idLanguage`       AS `idLanguage`
from (`webtool42_db`.`relationgroup` `rg` join `webtool42_db`.`entry` `e` on (`rg`.`idEntity` = `e`.`idEntity`));

create definer = fnbrasil@`%` view view_relationtype as
select `rt`.`idRelationType`  AS `idRelationType`,
       `rt`.`entry`           AS `entry`,
       `rt`.`prefix`          AS `prefix`,
       `rt`.`nameCanonical`   AS `nameCanonical`,
       `rt`.`nameDirect`      AS `nameDirect`,
       `rt`.`nameInverse`     AS `nameInverse`,
       `rt`.`color`           AS `color`,
       `rt`.`idRelationGroup` AS `idRelationGroup`,
       `rt`.`idEntity`        AS `idEntity`,
       `e`.`name`             AS `name`,
       `e`.`description`      AS `description`,
       `e`.`idLanguage`       AS `idLanguage`,
       `rg`.`entry`           AS `rgEntry`,
       `eg`.`name`            AS `rgName`
from (((`webtool42_db`.`relationtype` `rt` join `webtool42_db`.`entry` `e`
        on (`rt`.`idEntity` = `e`.`idEntity`)) join `webtool42_db`.`relationgroup` `rg`
       on (`rt`.`idRelationGroup` = `rg`.`idRelationGroup`)) join `webtool42_db`.`entry` `eg`
      on (`rg`.`idEntity` = `eg`.`idEntity`))
where `e`.`idLanguage` = `eg`.`idLanguage`;

create definer = fnbrasil@`%` view view_semantictype as
select `st`.`idSemanticType` AS `idSemanticType`,
       `st`.`entry`          AS `entry`,
       `st`.`idEntity`       AS `idEntity`,
       `st`.`idDomain`       AS `idDomain`,
       `d`.`entry`           AS `domainEntry`,
       `e`.`name`            AS `name`,
       `e`.`description`     AS `description`,
       `e`.`idLanguage`      AS `idLanguage`
from ((`webtool42_db`.`semantictype` `st` join `webtool42_db`.`entry` `e`
       on (`e`.`idEntity` = `st`.`idEntity`)) join `webtool42_db`.`domain` `d` on (`st`.`idDomain` = `d`.`idDomain`));

create definer = fnbrasil@`%` view view_semantictype_relation as
select `relation`.`idEntityRelation` AS `idEntityRelation`,
       `relation`.`idRelationType`   AS `idRelationType`,
       `relation`.`relationType`     AS `relationType`,
       `relation`.`nameCanonical`    AS `nameCanonical`,
       `relation`.`nameDirect`       AS `nameDirect`,
       `relation`.`nameInverse`      AS `nameInverse`,
       `relation`.`color`            AS `color`,
       `st1`.`name`                  AS `st1Name`,
       `st1`.`idSemanticType`        AS `st1IdSemanticType`,
       `st1`.`idEntity`              AS `st1IdEntity`,
       `st1`.`idLanguage`            AS `idLanguage`,
       `st2`.`name`                  AS `st2Name`,
       `st2`.`idSemanticType`        AS `st2IdSemanticType`,
       `st2`.`idEntity`              AS `stIdEntity`
from ((`webtool42_db`.`view_semantictype` `st1` join `webtool42_db`.`view_relation` `relation`
       on (`st1`.`idEntity` = `relation`.`idEntity1`)) join `webtool42_db`.`view_semantictype` `st2`
      on (`relation`.`idEntity2` = `st2`.`idEntity`))
where `relation`.`relationType` = 'rel_subtypeof'
  and `st1`.`idLanguage` = `st2`.`idLanguage`;

create definer = fnbrasil@`%` view view_sentence as
select `s`.`idSentence`     AS `idSentence`,
       `s`.`text`           AS `text`,
       `s`.`paragraphOrder` AS `paragraphOrder`,
       `s`.`idParagraph`    AS `idParagraph`,
       `s`.`idLanguage`     AS `idLanguage`,
       `s`.`idOriginMM`     AS `idOriginMM`,
       `s`.`idRLSLabel`     AS `idRLSLabel`
from ((`webtool42_db`.`sentence` `s` join `webtool42_db`.`rls_label` `l`
       on (`s`.`idRLSLabel` = `l`.`idRLSLabel`)) join `webtool42_db`.`rls_access` `u`
      on (`u`.`user` = substring_index(user(), '@', 1)))
where `l`.`value` & `u`.`value`;

create definer = fnbrasil@`%` view view_sentence_old as
select `s`.`idSentence`     AS `idSentence`,
       `s`.`text`           AS `text`,
       `s`.`paragraphOrder` AS `paragraphOrder`,
       `s`.`idParagraph`    AS `idParagraph`,
       `s`.`idLanguage`     AS `idLanguage`,
       `p`.`documentOrder`  AS `documentOrder`,
       `p`.`idDocument`     AS `idDocument`,
       `d`.`entry`          AS `documentEntry`,
       `d`.`author`         AS `author`,
       `d`.`idGenre`        AS `idGenre`,
       `d`.`idCorpus`       AS `idCorpus`,
       `c`.`entry`          AS `corpusEntry`
from (((`webtool42_db`.`sentence` `s` join `webtool42_db`.`paragraph` `p`
        on (`s`.`idParagraph` = `p`.`idParagraph`)) join `webtool42_db`.`document` `d`
       on (`p`.`idDocument` = `d`.`idDocument`)) join `webtool42_db`.`corpus` `c` on (`d`.`idCorpus` = `c`.`idCorpus`));

create definer = fnbrasil@`%` view view_sentence_timespan as
select `s`.`idSentence` AS `idSentence`,
       `s`.`text`       AS `text`,
       `s`.`idLanguage` AS `idLanguageSentence`,
       `s`.`idOriginMM` AS `idOriginMM`,
       `t`.`idTimeSpan` AS `idTimeSpan`,
       `t`.`startTime`  AS `startTime`,
       `t`.`endTime`    AS `endTime`
from ((`webtool42_db`.`view_sentence` `s` join `webtool42_db`.`sentence_timespan` `sts`
       on (`s`.`idSentence` = `sts`.`idSentence`)) join `webtool42_db`.`timespan` `t`
      on (`sts`.`idTimeSpan` = `t`.`idTimeSpan`));

create definer = fnbrasil@`%` view view_staticobject_boundingbox as
select `bb`.`idBoundingBox`   AS `idBoundingBox`,
       `bb`.`x`               AS `x`,
       `bb`.`y`               AS `y`,
       `bb`.`width`           AS `width`,
       `bb`.`height`          AS `height`,
       `bb`.`blocked`         AS `blocked`,
       `sob`.`idStaticObject` AS `idStaticObject`
from ((`webtool42_db`.`boundingbox` `bb` join `webtool42_db`.`staticobject_boundingbox` `sbb`
       on (`bb`.`idBoundingBox` = `sbb`.`idBoundingBox`)) join `webtool42_db`.`staticobject` `sob`
      on (`sbb`.`idStaticObject` = `sob`.`idStaticObject`));

create definer = fnbrasil@`%` view view_staticobject_textspan as
select `sob`.`idStaticObject` AS `idStaticObject`,
       `sob`.`name`           AS `name`,
       `sob`.`scene`          AS `scene`,
       `sob`.`nobndbox`       AS `nobndbox`,
       `ts`.`startWord`       AS `startWord`,
       `ts`.`endWord`         AS `endWord`,
       `ds`.`idDocument`      AS `idDocument`,
       `ts`.`idSentence`      AS `idSentence`
from ((`webtool42_db`.`staticobject` `sob` join `webtool42_db`.`textspan` `ts`
       on (`sob`.`idStaticObject` = `ts`.`idStaticObject`)) join `webtool42_db`.`document_sentence` `ds`
      on (`ts`.`idSentence` = `ds`.`idSentence`));

create definer = fnbrasil@`%` view view_task_manager as
select `t`.`idTask`       AS `idTask`,
       `t`.`name`         AS `taskName`,
       `u`.`idUser`       AS `idUser`,
       `u`.`login`        AS `login`,
       `u`.`name`         AS `userName`,
       `u`.`email`        AS `email`,
       `tg`.`idTaskGroup` AS `idTaskGroup`,
       `tg`.`name`        AS `taskGroupName`,
       `p`.`idProject`    AS `idProject`,
       `p`.`name`         AS `projectName`
from ((((`webtool42_db`.`task_manager` `tm` join `webtool42_db`.`user` `u`
         on (`tm`.`idUser` = `u`.`idUser`)) join `webtool42_db`.`task` `t`
        on (`tm`.`idTask` = `t`.`idTask`)) join `webtool42_db`.`taskgroup` `tg`
       on (`t`.`idTaskGroup` = `tg`.`idTaskGroup`)) join `webtool42_db`.`project` `p`
      on (`t`.`idProject` = `p`.`idProject`));

create definer = fnbrasil@`%` view view_type as
select `t`.`idType`                         AS `idType`,
       `t`.`entry`                          AS `entry`,
       `t`.`idEntity`                       AS `idEntity`,
       `webtool42_db`.`entry`.`name`        AS `name`,
       `webtool42_db`.`entry`.`description` AS `description`,
       `webtool42_db`.`entry`.`nick`        AS `nick`,
       `webtool42_db`.`entry`.`idLanguage`  AS `idLanguage`
from (`webtool42_db`.`type` `t` join `webtool42_db`.`entry` on (`t`.`idEntity` = `webtool42_db`.`entry`.`idEntity`));

create definer = fnbrasil@`%` view view_usertask as
select `ut`.`idUserTask`  AS `idUserTask`,
       `ut`.`isActive`    AS `isActive`,
       `ut`.`isIgnore`    AS `isIgnore`,
       `u`.`idUser`       AS `idUser`,
       `u`.`login`        AS `login`,
       `u`.`name`         AS `userName`,
       `u`.`email`        AS `email`,
       `t`.`idTask`       AS `idTask`,
       `t`.`name`         AS `taskName`,
       `tg`.`idTaskGroup` AS `idTaskGroup`,
       `tg`.`name`        AS `taskGroupName`,
       `p`.`idProject`    AS `idProject`,
       `p`.`name`         AS `projectName`
from ((((`webtool42_db`.`usertask` `ut` join `webtool42_db`.`user` `u`
         on (`ut`.`idUser` = `u`.`idUser`)) join `webtool42_db`.`task` `t`
        on (`ut`.`idTask` = `t`.`idTask`)) join `webtool42_db`.`taskgroup` `tg`
       on (`t`.`idTaskGroup` = `tg`.`idTaskGroup`)) join `webtool42_db`.`project` `p`
      on (`t`.`idProject` = `p`.`idProject`));

create definer = fnbrasil@`%` view view_usertask_docs as
select distinct `utd`.`idUserTaskDocument` AS `idUserTaskDocument`,
                `ut`.`idUserTask`          AS `idUserTask`,
                `ut`.`isActive`            AS `isActive`,
                `ut`.`isIgnore`            AS `isIgnore`,
                `u`.`idUser`               AS `idUser`,
                `u`.`login`                AS `login`,
                `u`.`name`                 AS `userName`,
                `u`.`email`                AS `email`,
                `t`.`idTask`               AS `idTask`,
                `t`.`name`                 AS `taskName`,
                `d`.`idDocument`           AS `idDocument`,
                `d`.`name`                 AS `documentName`,
                `c`.`idCorpus`             AS `idCorpus`,
                `c`.`name`                 AS `corpusName`,
                `c`.`idLanguage`           AS `idLanguage`
from (((((`webtool42_db`.`usertask` `ut` join `webtool42_db`.`user` `u`
          on (`ut`.`idUser` = `u`.`idUser`)) join `webtool42_db`.`task` `t`
         on (`ut`.`idTask` = `t`.`idTask`)) join `webtool42_db`.`usertask_document` `utd`
        on (`ut`.`idUserTask` = `utd`.`idUserTask`)) join `webtool42_db`.`view_corpus` `c`
       on (`utd`.`idCorpus` = `c`.`idCorpus`)) left join `webtool42_db`.`view_document` `d`
      on (`utd`.`idDocument` = `d`.`idDocument`))
where `d`.`idLanguage` is null
   or `c`.`idLanguage` = `d`.`idLanguage` and `d`.`idLanguage` is not null;

create definer = fnbrasil@`%` view view_valencepattern as
select `vl`.`idValenceLU`         AS `idValenceLU`,
       `vl`.`idFrame`             AS `idFrame`,
       `vl`.`idLU`                AS `idLU`,
       `vl`.`idLanguage`          AS `idLanguage`,
       `vp`.`idValencePattern`    AS `idValencePattern`,
       `vp`.`countPattern`        AS `countPattern`,
       `vv`.`idFrameElement`      AS `idFrameElement`,
       `vv`.`GF`                  AS `GF`,
       `vv`.`GFSource`            AS `GFSource`,
       `vv`.`PT`                  AS `PT`,
       `webtool42_db`.`lu`.`name` AS `luName`,
       `fentry`.`name`            AS `frameName`,
       `feentry`.`name`           AS `feName`,
       `l`.`language`             AS `language`
from ((((((((`webtool42_db`.`valencelu` `vl` join `webtool42_db`.`valencepattern` `vp`
             on (`vl`.`idValenceLU` = `vp`.`idValenceLU`)) join `webtool42_db`.`valencevalent` `vv`
            on (`vp`.`idValencePattern` = `vv`.`idValencePattern`)) join `webtool42_db`.`frame` `f`
           on (`vl`.`idFrame` = `f`.`idFrame`)) join `webtool42_db`.`frameelement` `fe`
          on (`vv`.`idFrameElement` = `fe`.`idFrameElement`)) join `webtool42_db`.`lu`
         on (`vl`.`idLU` = `webtool42_db`.`lu`.`idLU`)) join `webtool42_db`.`language` `l`
        on (`vl`.`idLanguage` = `l`.`idLanguage`)) join `webtool42_db`.`entry` `fentry`
       on (`f`.`entry` = `fentry`.`entry`)) join `webtool42_db`.`entry` `feentry` on (`fe`.`entry` = `feentry`.`entry`))
where `fentry`.`idLanguage` = `vl`.`idLanguage`
  and `feentry`.`idLanguage` = `vl`.`idLanguage`;

-- comment on column view_valencepattern.language not supported: Two-letter ISO 639-1 language codes + region, See: http://www.w3.org/International/articles/language-tags/

create definer = fnbrasil@`%` view view_video_dynamicobject as
select `v`.`idVideo` AS `idVideo`, `dob`.`idDynamicObject` AS `idDynamicObject`
from ((`webtool42_db`.`video_dynamicobject` `vdo` join `webtool42_db`.`video` `v`
       on (`vdo`.`idVideo` = `vdo`.`idVideo`)) join `webtool42_db`.`dynamicobject` `dob`
      on (`vdo`.`idDynamicObject` = `dob`.`idDynamicObject`));

create definer = fnbrasil@`%` view view_video_wordmm as
select `w`.`idWordMM`           AS `idWordMM`,
       `w`.`word`               AS `word`,
       `w`.`startTime`          AS `startTime`,
       `w`.`endTime`            AS `endTime`,
       `w`.`origin`             AS `origin`,
       `v`.`idVideo`            AS `idVideo`,
       `w`.`idDocumentSentence` AS `idDocumentSentence`,
       `ds`.`idDocument`        AS `idDocument`,
       `ds`.`idSentence`        AS `idSentence`
from ((`webtool42_db`.`wordmm` `w` join `webtool42_db`.`video` `v`
       on (`w`.`idVideo` = `v`.`idVideo`)) left join `webtool42_db`.`document_sentence` `ds`
      on (`w`.`idDocumentSentence` = `ds`.`idDocumentSentence`));

create definer = fnbrasil@`%` view view_wflexemelemma as
select `wf`.`idWordForm`    AS `idWordForm`,
       `wf`.`form`          AS `form`,
       `wf`.`md5`           AS `md5`,
       `lx`.`idLexeme`      AS `idLexeme`,
       `lx`.`name`          AS `lexeme`,
       `pos1`.`idPOS`       AS `idPOSLexeme`,
       `pos1`.`POS`         AS `POSLexeme`,
       `lx`.`idLanguage`    AS `idLanguage`,
       `le`.`idLexemeEntry` AS `idLexemeEntry`,
       `le`.`lexemeOrder`   AS `lexemeOrder`,
       `le`.`breakBefore`   AS `breakBefore`,
       `le`.`headWord`      AS `headWord`,
       `lm`.`idLemma`       AS `idLemma`,
       `lm`.`name`          AS `lemma`,
       `pos2`.`idPOS`       AS `idPOSLemma`,
       `pos2`.`POS`         AS `POSLemma`,
       `lang`.`language`    AS `language`
from ((((((`webtool42_db`.`wordform` `wf` join `webtool42_db`.`lexeme` `lx`
           on (`wf`.`idLexeme` = `lx`.`idLexeme`)) join `webtool42_db`.`pos` `pos1`
          on (`lx`.`idPOS` = `pos1`.`idPOS`)) join `webtool42_db`.`language` `lang`
         on (`lx`.`idLanguage` = `lang`.`idLanguage`)) left join `webtool42_db`.`lexemeentry` `le`
        on (`lx`.`idLexeme` = `le`.`idLexeme`)) left join `webtool42_db`.`lemma` `lm`
       on (`le`.`idLemma` = `lm`.`idLemma`)) left join `webtool42_db`.`pos` `pos2` on (`lm`.`idPOS` = `pos2`.`idPOS`))
where `lm`.`idLanguage` = `lx`.`idLanguage`
   or `lm`.`idLanguage` is null;

-- comment on column view_wflexemelemma.language not supported: Two-letter ISO 639-1 language codes + region, See: http://www.w3.org/International/articles/language-tags/

create
    definer = fnbrasil@`%` function annotation_create(par_json longtext) returns int
BEGIN
   DECLARE v_idEntity INT;
   DECLARE v_idTextspan INT;
   DECLARE v_idStaticObject INT;
   DECLARE v_idDynamicObject INT;
   DECLARE v_idUserTask INT;
   SET v_idEntity = JSON_EXTRACT(par_json, '$.idEntity');
   SET v_idTextspan = JSON_EXTRACT(par_json, '$.idTextSpan');
   SET v_idStaticObject = JSON_EXTRACT(par_json, '$.idStaticObject');
   SET v_idDynamicObject = JSON_EXTRACT(par_json, '$.idDynamicObject');
   SET v_idUserTask = JSON_EXTRACT(par_json, '$.idUserTask');
   INSERT INTO annotation (idEntity, idTextspan, idStaticObject, idDynamicObject, idUserTask)
	values (v_idEntity, v_idTextspan, v_idStaticObject, v_idDynamicObject, v_idUserTask);
   SET @idAnnotation = (SELECT last_insert_id());
   RETURN @idAnnotation;
END;

create
    definer = fnbrasil@`%` function annotationobject_create(par_externalId int, par_type char(3)) returns int
BEGIN
   INSERT INTO annotationobject (externalId, type, createdAt) values (par_externalId, par_type, now());
   SET @idAnnotationObject = (SELECT last_insert_id());
   RETURN @idAnnotationObject;
END;

create
    definer = fnbrasil@`%` function annotationset_delete(par_idAnnotationSet int, par_idUser int) returns int
BEGIN
		DELETE FROM ascomments WHERE idAnnotationSet = par_idAnnotationSet;
        DELETE FROM annotation WHERE idTextSpan IN (
			SELECT idTextSpan FROM textspan WHERE idLayer IN (
				SELECT idLayer FROM layer WHERE idAnnotationSet = par_idAnnotationSet
            )
        );
		DELETE FROM textspan WHERE idLayer IN (SELECT idLayer FROM layer WHERE idAnnotationSet = par_idAnnotationSet);
        DELETE FROM layer WHERE idAnnotationSet = par_idAnnotationSet;
		DELETE FROM annotationset WHERE idAnnotationSet = par_idAnnotationSet;
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','annotationset', par_idAnnotationSet,par_idUser);
        RETURN par_idAnnotationSet;
    END;

create
    definer = fnbrasil@`%` function boundingbox_dynamic_create(par_json longtext) returns int
BEGIN
   DECLARE v_frameNumber INT;
   DECLARE v_frameTime FLOAT;
   DECLARE v_x INT;
   DECLARE v_y INT;
   DECLARE v_width INT;
   DECLARE v_height INT;
   DECLARE v_blocked INT;
   DECLARE v_idDynamicObject INT;
   SET v_frameNumber = JSON_EXTRACT(par_json, '$.frameNumber');
   SET v_frameTime = JSON_EXTRACT(par_json, '$.frameTime');
   SET v_x = JSON_EXTRACT(par_json, '$.x');
   SET v_y = JSON_EXTRACT(par_json, '$.y');
   SET v_width = JSON_EXTRACT(par_json, '$.width');
   SET v_height = JSON_EXTRACT(par_json, '$.height');
   SET v_blocked = JSON_EXTRACT(par_json, '$.blocked');
   INSERT INTO boundingbox (frameNumber, frameTime, x, y, width, height, blocked) values (v_frameNumber, v_frameTime, v_x, v_y, v_width, v_height, v_blocked);
   SET @idBoundingBox = (SELECT last_insert_id());
   SET v_idDynamicObject = JSON_EXTRACT(par_json, '$.idDynamicObject');
   INSERT INTO dynamicobject_boundingbox(idDynamicObject,idBoundingBox) values (v_idDynamicObject,@idBoundingBox);
   RETURN @idBoundingBox;
END;

create
    definer = fnbrasil@`%` function boundingbox_dynamic_delete(par_idBoundingBox int, par_idUser int) returns int
BEGIN
		DELETE FROM dynamicobject_boundingbox where (idBoundingBox = par_idBoundingBox);
		DELETE FROM boundingbox WHERE idBoundingBox = par_idBoundingBox;
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','boundingbox', par_idBoundingBox,par_idUser);
        RETURN par_idBoundingBox;
    END;

create
    definer = fnbrasil@`%` function boundingbox_static_create(par_json longtext) returns int
BEGIN
   DECLARE v_x INT;
   DECLARE v_y INT;
   DECLARE v_width INT;
   DECLARE v_height INT;
   DECLARE v_idStaticObject INT;
   DECLARE v_idAnnotationObject1 INT;
   DECLARE v_idAnnotationObject2 INT;
   SET v_x = JSON_EXTRACT(par_json, '$.x');
   SET v_y = JSON_EXTRACT(par_json, '$.y');
   SET v_width = JSON_EXTRACT(par_json, '$.width');
   SET v_height = JSON_EXTRACT(par_json, '$.height');
   INSERT INTO boundingbox (x,y,width,height) values (v_x, v_y, v_width, v_height);
   SET @idBoundingBox = (SELECT last_insert_id());
   SET v_idStaticObject = JSON_EXTRACT(par_json, '$.idStaticObject');
   INSERT INTO staticobject_boundingbox(idStaticObject,idBoundingBox) values (v_idStaticObject, @idBoundingBox);
   RETURN @idBoundingBox;
END;

create
    definer = fnbrasil@`%` function ce_create(par_ce longtext) returns int
BEGIN
	DECLARE v_nameEn VARCHAR(255);
	DECLARE v_entry VARCHAR(255);
    DECLARE v_idConstruction INT;
    DECLARE v_idColor INT;
    DECLARE v_optional INT;
    DECLARE v_head INT;
    DECLARE v_multiple INT;
    DECLARE v_idUser INT;
    INSERT INTO entity (type) values ('CE');
	SET @idEntity = (SELECT last_insert_id());
    SET v_nameEn = JSON_UNQUOTE(JSON_EXTRACT(par_ce, '$.nameEn'));
    SET v_entry = CONCAT('ce_',lower(v_nameEn));
    CALL entry_create(v_entry, v_nameEn, @idEntity);
    SET v_idConstruction = JSON_EXTRACT(par_ce, '$.idConstruction');
    SET v_idColor = JSON_EXTRACT(par_ce, '$.idColor');
    SET v_optional = JSON_EXTRACT(par_ce, '$.optional');
    SET v_head = JSON_EXTRACT(par_ce, '$.head');
    SET v_multiple = JSON_EXTRACT(par_ce, '$.multiple');
    INSERT INTO constructionelement(idConstruction, entry, idColor, optional, head, multiple, active, idEntity)
			values (v_idConstruction, v_entry, v_idColor, v_optional, v_head, v_multiple, 1, @idEntity);
    SET @idConstructionElement = (SELECT last_insert_id());
    INSERT INTO timeline (tlDateTime,author,operation,tableName,id) VALUES (NOW(),'user','C','constructionelement', @idConstructionElement);
    RETURN @idConstructionElement;
END;

create
    definer = fnbrasil@`%` function ce_delete(par_idConstructionElement int, par_idUser int) returns int
BEGIN
		DECLARE v_idEntity INT;
		SELECT idEntity INTO v_idEntity FROM constructionelement WHERE (idConstructionElement = par_idConstructionElement);
        DELETE FROM entityrelation where (idEntity1 = v_idEntity) OR (idEntity2 = v_idEntity) OR (idEntity3 = v_idEntity);
        DELETE FROM entry where (idEntity = v_idEntity);
        DELETE FROM constructionelement where (idEntity = v_idEntity);
        DELETE FROM entity where (idEntity = v_idEntity);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','constructionelement', par_idConstructionElement,par_idUser);
        RETURN par_idConstructionElement;
    END;

create
    definer = fnbrasil@`%` function concept_create(par_concept longtext) returns int
BEGIN
	DECLARE v_nameEn VARCHAR(255);
	DECLARE v_entry VARCHAR(255);
    DECLARE v_type VARCHAR(255);
    DECLARE v_keyword VARCHAR(255);
    DECLARE v_aka TEXT;
    DECLARE v_idTypeInstance INT;
    DECLARE v_idUser INT;
    INSERT INTO entity (type) values ('CPT');
	SET @idEntity = (SELECT last_insert_id());
    SET v_nameEn = JSON_UNQUOTE(JSON_EXTRACT(par_concept, '$.nameEn'));
    SET v_type = JSON_UNQUOTE(JSON_EXTRACT(par_concept, '$.type'));
    SET v_keyword = JSON_UNQUOTE(JSON_EXTRACT(par_concept, '$.keyword'));
    SET v_aka = JSON_UNQUOTE(JSON_EXTRACT(par_concept, '$.aka'));
    SET v_idTypeInstance = JSON_EXTRACT(par_concept, '$.idTypeInstance');
    SET v_entry = CONCAT('cpt_',lower(v_nameEn),'_',lower(v_type));
    CALL entry_create(v_entry, v_nameEn, @idEntity);
    INSERT INTO concept(entry, keyword, aka, type, idEntity, idTypeInstance) values (v_entry, v_keyword, v_aka, v_type, @idEntity, v_idTypeInstance);
    SET @idConcept = (SELECT last_insert_id());
    SET v_idUser = JSON_EXTRACT(par_concept, '$.idUser');
    INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','concept', @idConcept, v_idUser);
    RETURN @idConcept;
 END;

create
    definer = fnbrasil@`%` function concept_delete(par_idConcept int, par_idUser int) returns int
BEGIN
		DECLARE v_idEntity INT;
		SELECT idEntity INTO v_idEntity FROM concept WHERE (idConcept = par_idConcept);
        DELETE FROM entityrelation where (idEntity1 = v_idEntity) OR (idEntity2 = v_idEntity) OR (idEntity3 = v_idEntity);
        DELETE FROM entry where (idEntity = v_idEntity);
        DELETE FROM concept where (idEntity = v_idEntity);
        DELETE FROM entity where (idEntity = v_idEntity);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','concept', par_idConcept,par_idUser);
        RETURN par_idConcept;
    END;

create
    definer = fnbrasil@`%` function corpus_create(par_json longtext) returns int
BEGIN
   DECLARE v_entry VARCHAR(255);
   DECLARE v_name VARCHAR(255);
   DECLARE v_idUser INT;
   INSERT INTO entity (type) values ('CRP');
   SET @idEntity = (SELECT last_insert_id());
   SET v_name = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.name'));
   SET v_idUser = JSON_EXTRACT(par_json, '$.idUser');
   SET v_entry = LOWER(CONCAT('crp_', v_name));
   CALL entry_create(v_entry, v_name, @idEntity);
   INSERT INTO corpus(entry,active,idEntity) values (v_entry, 1, @idEntity);
   SET @idCorpus = (SELECT last_insert_id());
   INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','corpus', @idCorpus, v_idUser);
   RETURN @idCorpus;
END;

create
    definer = fnbrasil@`%` function cxn_create(par_cxn longtext) returns int
BEGIN
	DECLARE v_nameEn VARCHAR(255);
	DECLARE v_entry VARCHAR(255);
    DECLARE v_abstract INT;
    DECLARE v_active INT;
    DECLARE v_idLanguage INT;
    DECLARE v_idUser INT;
    INSERT INTO entity (type) values ('CXN');
	SET @idEntity = (SELECT last_insert_id());
    SET v_nameEn = JSON_UNQUOTE(JSON_EXTRACT(par_cxn, '$.nameEn'));
    SET v_entry = CONCAT('cxn_',lower(v_nameEn));
    CALL entry_create(v_entry, v_nameEn, @idEntity);
    SET v_abstract = JSON_EXTRACT(par_cxn, '$.abstract');
    SET v_active = JSON_EXTRACT(par_cxn, '$.active');
    SET v_idLanguage = JSON_EXTRACT(par_cxn, '$.idLanguage');
    INSERT INTO construction(entry, active, abstract,idLanguage, idEntity) values (v_entry, v_active, v_abstract,v_idLanguage,@idEntity);
    SET @idConstruction = (SELECT last_insert_id());
    SET v_idUser = JSON_EXTRACT(par_cxn, '$.idUser');
    INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','construction', @idConstruction, v_idUser);
    RETURN @idConstruction;
 END;

create
    definer = fnbrasil@`%` function cxn_delete(par_idConstruction int, par_idUser int) returns int
BEGIN
		DECLARE v_idEntity INT;
		SELECT idEntity INTO v_idEntity FROM construction WHERE (idConstruction = par_idConstruction);
        DELETE FROM entityrelation where (idEntity1 = v_idEntity) OR (idEntity2 = v_idEntity) OR (idEntity3 = v_idEntity);
        DELETE FROM entry where (idEntity = v_idEntity);
        DELETE FROM constructionelement where (idConstruction = par_idConstruction);
        DELETE FROM construction where (idEntity = v_idEntity);
        DELETE FROM entity where (idEntity = v_idEntity);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','construction', par_idConstruction,par_idUser);
        RETURN par_idConstruction;
    END;

create
    definer = fnbrasil@`%` function dataset_create(par_dataset longtext) returns int
BEGIN
		DECLARE v_name VARCHAR(255);
        DECLARE v_description VARCHAR(4000);
        DECLARE v_idProject INT;
        DECLARE v_idUser INT;
        SET v_name = JSON_UNQUOTE(JSON_EXTRACT(par_dataset, '$.name'));
        SET v_description = JSON_UNQUOTE(JSON_EXTRACT(par_dataset, '$.description'));
        SET v_idProject = JSON_EXTRACT(par_dataset, '$.idProject');
        SET v_idUser = JSON_EXTRACT(par_dataset, '$.idUser');
        INSERT INTO dataset(name,description,idProject) values (v_name, v_description,v_idProject);
        SET @idDataset = (SELECT last_insert_id());
        INSERT INTO project_dataset(idProject,idDataset) values (v_idProject, @idDataset);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','dataset', @idDataset, v_idUser);
        RETURN @idDataset;
    END;

create
    definer = fnbrasil@`%` function dataset_delete(par_idDataset int, par_idUser int) returns int
BEGIN
        DELETE FROM project_dataset where (idDataset = par_idDataset);
        DELETE FROM dataset where (idDataset = par_idDataset);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','dataset', par_idDataset, par_idUser);
        RETURN par_idDataset;
    END;

create
    definer = fnbrasil@`%` function dataset_update(par_dataset longtext) returns int
BEGIN
		DECLARE v_name VARCHAR(255);
        DECLARE v_description VARCHAR(4000);
        DECLARE v_idUser INT;
        DECLARE v_idDataset INT;
        SET v_name = JSON_UNQUOTE(JSON_EXTRACT(par_dataset, '$.name'));
        SET v_description = JSON_UNQUOTE(JSON_EXTRACT(par_dataset, '$.description'));
        SET v_idUser = JSON_EXTRACT(par_dataset, '$.idUser');
        SET v_idDataset = JSON_EXTRACT(par_dataset, '$.idDataset');
        UPDATE dataset set name = v_name, description = v_description WHERE (idDataset = v_idDataset);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','U','dataset', v_idDataset, v_idUser);
        RETURN v_idDataset;
    END;

create
    definer = fnbrasil@`%` function document_create(par_json longtext) returns int
BEGIN
   DECLARE v_entry VARCHAR(255);
   DECLARE v_name VARCHAR(255);
   DECLARE v_idCorpus INT;
   DECLARE v_idUser INT;
   INSERT INTO entity (type) values ('DOC');
   SET @idEntity = (SELECT last_insert_id());
   SET v_name = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.name'));
   SET v_idCorpus = JSON_EXTRACT(par_json, '$.idCorpus');
   SET v_idUser = JSON_EXTRACT(par_json, '$.idUser');
   SET v_entry = LOWER(CONCAT('doc_', v_name));
   CALL entry_create(v_entry, v_name, @idEntity);
   INSERT INTO document(entry,active,idGenre,idCorpus,idEntity) values (v_entry, 1, 1, v_idCorpus, @idEntity);
   SET @idDocument = (SELECT last_insert_id());
   INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','document', @idDocument, v_idUser);
   RETURN @idDocument;
END;

create
    definer = fnbrasil@`%` function domain_create(par_domain longtext) returns int
BEGIN
	DECLARE v_nameEn VARCHAR(255);
	DECLARE v_entry VARCHAR(255);
    DECLARE v_idUser INT;
    INSERT INTO entity (type) values ('DOM');
	SET @idEntity = (SELECT last_insert_id());
    SET v_nameEn = JSON_UNQUOTE(JSON_EXTRACT(par_domain, '$.nameEn'));
    SET v_entry = CONCAT('dom_',lower(v_nameEn));
    CALL entry_create(v_entry, v_nameEn, @idEntity);
    INSERT INTO domain(entry, idEntity) values (v_entry, @idEntity);
    SET @idDomain = (SELECT last_insert_id());
    SET v_idUser = JSON_EXTRACT(par_domain, '$.idUser');
    INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','domain', @idDomain, v_idUser);
    RETURN @idDomain;
 END;

create
    definer = fnbrasil@`%` function dynamicobject_create(par_json longtext) returns int
BEGIN
   DECLARE v_name VARCHAR(255);
   DECLARE v_startFrame INT;
   DECLARE v_endFrame INT;
   DECLARE v_startTime FLOAT;
   DECLARE v_endTime FLOAT;
   DECLARE v_status INT;
   DECLARE v_origin INT;
   DECLARE v_idUser INT;
   SET v_name  = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.name'));
   SET v_startFrame  = JSON_EXTRACT(par_json, '$.startFrame');
   SET v_endFrame  = JSON_EXTRACT(par_json, '$.endFrame');
   SET v_startTime  = JSON_EXTRACT(par_json, '$.startTime');
   SET v_endTime  = JSON_EXTRACT(par_json, '$.endTime');
   SET v_status = JSON_EXTRACT(par_json, '$.status');
   SET v_origin = JSON_EXTRACT(par_json, '$.origin');
   SET v_idUser = JSON_EXTRACT(par_json, '$.idUser');
   INSERT INTO dynamicobject (name,startFrame,endFrame,startTime,endTime,status,origin)
	values (v_name,v_startFrame,v_endFrame,v_startTime,v_endTime,v_status,v_origin);
   SET @idDynamicObject = (SELECT last_insert_id());
   INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','dynamicobject', @idDynamicObject,v_idUser);
   RETURN @idDynamicObject;
END;

create
    definer = fnbrasil@`%` function dynamicobject_delete(par_idDynamicObject int, par_idUser int) returns int
BEGIN
        DELETE FROM annotation WHERE idDynamicObject = par_idDynamicObject;
        DELETE FROM dynamicobject_boundingbox WHERE (idDynamicObject = par_idDynamicObject);
        DELETE FROM video_dynamicobject WHERE (idDynamicObject = par_idDynamicObject);
		DELETE FROM dynamicobject WHERE idDynamicObject = par_idDynamicObject;
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','dynamicobject', par_idDynamicObject,par_idUser);
        RETURN par_idDynamicObject;
    END;

create
    definer = fnbrasil@`%` procedure entry_create(IN par_entry varchar(255), IN par_name varchar(255),
                                                  IN par_idEntity int)
BEGIN
        DECLARE idLanguageE INT;
		DECLARE done INT DEFAULT FALSE;
		DECLARE cursorLanguage CURSOR FOR SELECT idLanguage FROM language;
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

		OPEN cursorLanguage;
		read_loop: LOOP
			FETCH cursorLanguage INTO idLanguageE;
			IF done THEN
				LEAVE read_loop;
			END IF;
			INSERT INTO entry (idLanguage, entry, name, description, idEntity) values (idLanguageE, par_entry, par_name, par_name, par_idEntity);
		END LOOP;
		CLOSE cursorLanguage;
    END;

create
    definer = fnbrasil@`%` function fe_create(par_idFrame int, par_name varchar(255), par_coreType char(32),
                                              par_idColor int, par_idUser int) returns int
BEGIN
		DECLARE entry VARCHAR(255);
        set entry = CONCAT('fe_', LOWER(par_name));
		INSERT INTO entity (type) values ('FE');
        SET @idEntity = (SELECT last_insert_id());
        CALL entry_create(entry, par_name, @idEntity);
        INSERT INTO frameelement(idFrame, entry, coreType, idColor, idEntity) values (par_idFrame, entry, par_coreType, par_idColor, @idEntity);
        SET @idFrameElement = (SELECT last_insert_id());
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id) VALUES (NOW(),'user','C','frameelement', @idFrameElement);
        RETURN @idFrameElement;
    END;

create
    definer = fnbrasil@`%` function fe_delete(par_idFrameElement int, par_idUser int) returns int
BEGIN
		DECLARE v_idEntity INT;
		SELECT idEntity INTO v_idEntity FROM frameelement WHERE (idFrameElement = par_idFrameElement);
        DELETE FROM entityrelation where (idEntity1 = v_idEntity) OR (idEntity2 = v_idEntity) OR (idEntity3 = v_idEntity);
        DELETE FROM entry where (idEntity = v_idEntity);
        DELETE FROM frameelement where (idEntity = v_idEntity);
        DELETE FROM entity where (idEntity = v_idEntity);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','frameelement', par_idFrameElement,par_idUser);
        RETURN par_idFrameElement;
    END;

create
    definer = fnbrasil@`%` function frame_create(par_frame longtext) returns int
BEGIN
	DECLARE v_nameEn VARCHAR(255);
	DECLARE v_entry VARCHAR(255);
    DECLARE v_idUser INT;
    INSERT INTO entity (type) values ('FRM');
	SET @idEntity = (SELECT last_insert_id());
    SET v_nameEn = JSON_UNQUOTE(JSON_EXTRACT(par_frame, '$.nameEn'));
    SET v_entry = CONCAT('frm_',lower(v_nameEn));
    CALL entry_create(v_entry, v_nameEn, @idEntity);
    INSERT INTO frame(entry, active, defaultName, defaultDefinition, idEntity) values (v_entry, 1, v_nameEn, v_nameEn, @idEntity);
    SET @idFrame = (SELECT last_insert_id());
    SET v_idUser = JSON_EXTRACT(par_frame, '$.idUser');
    INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','frame', @idFrame, v_idUser);
    RETURN @idFrame;
 END;

create
    definer = fnbrasil@`%` function frame_delete(par_idFrame int, par_idUser int) returns int
BEGIN
		DECLARE v_idEntity INT;
		SELECT idEntity INTO v_idEntity FROM frame WHERE (idFrame = par_idFrame);
        DELETE FROM entityrelation where (idEntity1 = v_idEntity) OR (idEntity2 = v_idEntity) OR (idEntity3 = v_idEntity);
        DELETE FROM entry where (idEntity = v_idEntity);
        DELETE FROM frameelement where (idFrame = par_idFrame);
        DELETE FROM lu where (idFrame = par_idFrame);
        DELETE FROM frame where (idEntity = v_idEntity);
        DELETE FROM entity where (idEntity = v_idEntity);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','frame', par_idFrame,par_idUser);
        RETURN par_idFrame;
    END;

create
    definer = fnbrasil@`%` function genericlabel_create(par_genericlabel longtext) returns int
BEGIN
	DECLARE v_name VARCHAR(255);
	DECLARE v_definition TEXT;
    DECLARE v_example TEXT;
    DECLARE v_idColor INT;
    DECLARE v_idLanguage INT;
    DECLARE v_idLayerType INT;
    DECLARE v_idUser INT;
    INSERT INTO entity (type) values ('GLB');
	SET @idEntity = (SELECT last_insert_id());
    SET v_idColor = JSON_EXTRACT(par_genericlabel, '$.idColor');
    SET v_idLanguage = JSON_EXTRACT(par_genericlabel, '$.idLanguage');
    SET v_idLayerType = JSON_EXTRACT(par_genericlabel, '$.idLayerType');
    SET v_name = JSON_UNQUOTE(JSON_EXTRACT(par_genericlabel, '$.name'));
    SET v_definition = JSON_UNQUOTE(JSON_EXTRACT(par_genericlabel, '$.definition'));
    SET v_example = JSON_UNQUOTE(JSON_EXTRACT(par_genericlabel, '$.example'));
    INSERT INTO genericlabel(name,definition, example, idEntity, idColor, idLanguage, idLayerType) values (v_name, v_definition, v_example, @idEntity, v_idColor, v_idLanguage, v_idLayerType);
    SET @idGenericLabel = (SELECT last_insert_id());
    SET v_idUser = JSON_EXTRACT(par_genericlabel, '$.idUser');
    INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','genericlabel', @idGenericLabel, v_idUser);
    RETURN @idGenericLabel;
 END;

create
    definer = fnbrasil@`%` function genericlabel_delete(par_idGenericLabel int, par_idUser int) returns int
BEGIN
		DECLARE v_idEntity INT;
		SELECT idEntity INTO v_idEntity FROM genericlabel WHERE (idGenericLabel = par_idGenericLabel);
        DELETE FROM genericlabel where (idEntity = v_idEntity);
        DELETE FROM entity where (idEntity = v_idEntity);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','genericlabel', par_idGenericLabel,par_idUser);
        RETURN par_idGenericLabel;
    END;

create
    definer = fnbrasil@`%` function image_create(par_json longtext) returns int
BEGIN
   DECLARE v_name VARCHAR(255);
   DECLARE v_currentURL VARCHAR(255);
   DECLARE v_width INT;
   DECLARE v_height INT;
   DECLARE v_depth INT;
   DECLARE v_idLanguage INT;
   SET v_name  = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.name'));
   SET v_currentURL  = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.currentURL'));
   SET v_width  = JSON_EXTRACT(par_json, '$.width');
   SET v_height  = JSON_EXTRACT(par_json, '$.height');
   SET v_depth  = JSON_EXTRACT(par_json, '$.depth');
   SET v_idLanguage  = JSON_EXTRACT(par_json, '$.idLanguage');
   INSERT INTO image (name,currentURL,width,height,depth,idLanguage)
	values (v_name,v_currentURL,v_width,v_height,v_depth,v_idLanguage);
   SET @idImage = (SELECT last_insert_id());
   RETURN @idImage;
END;

create
    definer = fnbrasil@`%` function layergroup_create(par_layergroup longtext) returns int
BEGIN
	DECLARE v_name VARCHAR(255);
    DECLARE v_type VARCHAR(255);
    INSERT INTO layergroup(name, type) values (v_name, v_type);
    SET @idLayerGroup = (SELECT last_insert_id());
    RETURN @idLayerGroup;
 END;

create
    definer = fnbrasil@`%` function layertype_create(par_layertype longtext) returns int
BEGIN
	DECLARE v_nameEn VARCHAR(255);
	DECLARE v_entry VARCHAR(255);
    DECLARE v_allowsApositional INT;
    DECLARE v_isAnnotation INT;
    DECLARE v_layerOrder INT;
    DECLARE v_idLayerGroup INT;
    DECLARE v_idUser INT;
    INSERT INTO entity (type) values ('LTY');
	SET @idEntity = (SELECT last_insert_id());
    SET v_allowsApositional = JSON_EXTRACT(par_layertype, '$.allowsApositional');
    SET v_isAnnotation = JSON_EXTRACT(par_layertype, '$.isAnnotation');
    SET v_layerOrder = JSON_EXTRACT(par_layertype, '$.layerOrder');
    SET v_idLayerGroup = JSON_EXTRACT(par_layertype, '$.idLayerGroup');
    SET v_nameEn = JSON_UNQUOTE(JSON_EXTRACT(par_layertype, '$.nameEn'));
    SET v_entry = CONCAT('lty_',lower(v_nameEn));
    CALL entry_create(v_entry, v_nameEn, @idEntity);
    INSERT INTO layertype(entry, allowsApositional, isAnnotation, layerOrder, idLayerGroup, idEntity) values (v_entry,v_allowsApositional, v_isAnnotation, v_layerOrder, v_idLayerGroup, @idEntity);
    SET @idLayerType = (SELECT last_insert_id());
    SET v_idUser = JSON_EXTRACT(par_layertype, '$.idUser');
    INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','layertype', @idLayerType, v_idUser);
    RETURN @idLayerType;
 END;

create
    definer = fnbrasil@`%` function layertype_delete(par_idLayerType int, par_idUser int) returns int
BEGIN
		DECLARE v_idEntity INT;
		SELECT idEntity INTO v_idEntity FROM layertype WHERE (idLayerType = par_idLayerType);
        DELETE FROM entry where (idEntity = v_idEntity);
        DELETE FROM layertype where (idEntity = v_idEntity);
        DELETE FROM entity where (idEntity = v_idEntity);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','layertype', par_idLayerType,par_idUser);
        RETURN par_idLayerType;
    END;

create
    definer = fnbrasil@`%` function lemma_create(par_lemma longtext) returns int
BEGIN
		DECLARE v_name VARCHAR(255);
        DECLARE v_idLanguage INT;
        DECLARE v_idPOS INT;
        DECLARE v_idUser INT;
        INSERT INTO entity (type) values ('LEM');
        SET @idEntity = (SELECT last_insert_id());
        SET v_name = JSON_UNQUOTE(JSON_EXTRACT(par_lemma, '$.name'));
        SET v_idLanguage = JSON_EXTRACT(par_lemma, '$.idLanguage');
        SET v_idPOS = JSON_EXTRACT(par_lemma, '$.idPOS');
        SET v_idUser = JSON_EXTRACT(par_lemma, '$.idUser');
        INSERT INTO lemma(name, idLanguage, idPOS, idEntity) values (v_name, v_idLanguage, v_idPOS, @idEntity);
        SET @idLemma = (SELECT last_insert_id());
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','lemma', @idLemma, v_idUser);
        RETURN @idLemma;
    END;

create
    definer = fnbrasil@`%` function lemma_delete(par_idLemma int, par_idUser int) returns int
BEGIN
		DECLARE v_idEntity INT;
		SELECT idEntity INTO v_idEntity FROM lemma WHERE (idLemma = par_idLemma);
        DELETE FROM lexemeentry where (idLemma = par_idLemma);
        DELETE FROM lemma where (idEntity = v_idEntity);
        DELETE FROM entity where (idEntity = v_idEntity);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','lemma', par_idLemma,par_idUser);
        RETURN par_idLemma;
    END;

create
    definer = fnbrasil@`%` function lexeme_create(par_lexeme longtext) returns int
BEGIN
		DECLARE v_name VARCHAR(255);
        DECLARE v_idLanguage INT;
        DECLARE v_idPOS INT;
        DECLARE v_idUser INT;
        INSERT INTO entity (type) values ('LEX');
        SET @idEntity = (SELECT last_insert_id());
        SET v_name = JSON_UNQUOTE(JSON_EXTRACT(par_lexeme, '$.name'));
        SET v_idLanguage = JSON_EXTRACT(par_lexeme, '$.idLanguage');
        SET v_idPOS = JSON_EXTRACT(par_lexeme, '$.idPOS');
        INSERT INTO lexeme(name, idLanguage, idPOS, idEntity) values (v_name, v_idLanguage, v_idPOS, @idEntity);
        SET @idLexeme = (SELECT last_insert_id());
        INSERT INTO wordform (form, md5, idLexeme) values (v_name, md5(v_name), @idLexeme);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','lexeme', @idLexeme, v_idUser);
        RETURN @idLexeme;
    END;

create
    definer = fnbrasil@`%` function lexeme_delete(par_idLexeme int, par_idUser int) returns int
BEGIN
		DECLARE v_idEntity INT;
		SELECT idEntity INTO v_idEntity FROM lexeme WHERE (idLexeme = par_idLexeme);
        DELETE FROM lexemeentry where (idLexeme = par_idLexeme);
        DELETE FROM wordform where (idLexeme = par_idLexeme);
        DELETE FROM lexeme where (idEntity = v_idEntity);
        DELETE FROM entity where (idEntity = v_idEntity);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','lexeme', par_idLexeme,par_idUser);
        RETURN par_idLexeme;
    END;

create
    definer = fnbrasil@`%` function lexicon_create(par_lexicon longtext) returns int
BEGIN
		DECLARE v_form VARCHAR(255);
        DECLARE v_idLanguage INT;
        DECLARE v_idPOS INT;
        DECLARE v_idUDPOS INT;
        DECLARE v_idLexiconGroup INT;
        INSERT INTO entity (type) values ('LXC');
        SET @idEntity = (SELECT last_insert_id());
        SET v_form = JSON_UNQUOTE(JSON_EXTRACT(par_lexicon, '$.form'));
        SET v_idLanguage = JSON_EXTRACT(par_lexicon, '$.idLanguage');
        SET v_idPOS = JSON_EXTRACT(par_lexicon, '$.idPOS');
        SET v_idUDPOS = JSON_EXTRACT(par_lexicon, '$.idUDPOS');
        SET v_idLexiconGroup = JSON_EXTRACT(par_lexicon, '$.idLexiconGroup');
        INSERT INTO lexicon(form, idLexiconGroup,idEntity,idPOS, idUDPOS,idLanguage) values (v_form, v_idLexiconGroup, @idEntity, v_idPOS, v_idUDPOS,v_idLanguage);
        SET @idLexicon = (SELECT last_insert_id());
        RETURN @idLexicon;
    END;

create
    definer = fnbrasil@`%` function lexicon_delete(par_idLexicon int, par_idUser int) returns int
BEGIN
		DECLARE v_idEntity INT;
		SELECT idEntity INTO v_idEntity FROM lexicon WHERE (idLexicon = par_idLexicon);
        DELETE FROM lexicon_expression where (idLexicon = par_idLexicon);
        DELETE FROM lexicon where (idEntity = v_idEntity);
        DELETE FROM entity where (idEntity = v_idEntity);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','lexicon', par_idLexicon,par_idUser);
        RETURN par_idLexicon;
    END;

create
    definer = fnbrasil@`%` function lexicon_group_create(par_lexicon_group longtext) returns int
BEGIN
		DECLARE v_name VARCHAR(255);
        INSERT INTO entity (type) values ('LXG');
        SET @idEntity = (SELECT last_insert_id());
        SET v_name = JSON_UNQUOTE(JSON_EXTRACT(par_lexicon_group, '$.name'));
        INSERT INTO lexicon_group(name,idEntity) values (v_name, @idEntity);
        SET @idLexiconGroup = (SELECT last_insert_id());
        RETURN @idLexiconGroup;
    END;

create
    definer = fnbrasil@`%` function lu_create(par_lu longtext) returns int
BEGIN
		DECLARE v_name VARCHAR(255);
        DECLARE v_idFrame INT;
        DECLARE v_incorporatedFE INT;
        DECLARE v_senseDescription VARCHAR(4000);
        DECLARE v_idLemma INT;
        DECLARE v_idLexicon INT;
        DECLARE v_idUser INT;
        INSERT INTO entity (type) values ('LU');
        SET @idEntity = (SELECT last_insert_id());
        SET v_name = JSON_UNQUOTE(JSON_EXTRACT(par_lu, '$.name'));
        SET v_idFrame = JSON_EXTRACT(par_lu, '$.idFrame');
        IF (JSON_EXTRACT(par_lu, '$.incorporatedFE')) THEN
			SET v_incorporatedFE = JSON_EXTRACT(par_lu, '$.incorporatedFE');
        END IF;
        SET v_senseDescription = JSON_UNQUOTE(JSON_EXTRACT(par_lu, '$.senseDescription'));
        #SET v_idLemma = JSON_EXTRACT(par_lu, '$.idLemma');
        SET v_idLexicon = JSON_EXTRACT(par_lu, '$.idLexicon');
        SET v_idUser = JSON_EXTRACT(par_lu, '$.idUser');
        INSERT INTO lu(idFrame, name, senseDescription, importNum, active, incorporatedFE, idLexicon, idEntity) values (v_idFrame, v_name, v_senseDescription, 0, 1, v_incorporatedFE, v_idLexicon, @idEntity);
        SET @idLU = (SELECT last_insert_id());
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','lu', @idLU, v_idUser);
        RETURN @idLU;
    END;

create
    definer = fnbrasil@`%` function lu_delete(par_idLU int, par_idUser int) returns int
BEGIN
		DECLARE v_idEntity INT;
		SELECT idEntity INTO v_idEntity FROM lu WHERE (idLU = par_idLU);
        DELETE FROM entityrelation where (idEntity1 = v_idEntity) OR (idEntity2 = v_idEntity) OR (idEntity3 = v_idEntity);
        DELETE FROM entry where (idEntity = v_idEntity);
        DELETE FROM lu where (idEntity = v_idEntity);
        DELETE FROM entity where (idEntity = v_idEntity);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','lu', par_idLU,par_idUser);
        RETURN par_idLU;
    END;

create
    definer = fnbrasil@`%` function objectrelation_create(par_json longtext) returns int
BEGIN
   DECLARE v_idAnnotationObject1 INT;
   DECLARE v_idAnnotationObject2 INT;
   DECLARE v_relationType VARCHAR(255);
   DECLARE v_idVideo INT;
   DECLARE v_idDynamicObject INT;
   DECLARE v_idImage INT;
   DECLARE v_idStaticObject INT;
   DECLARE v_idSentence INT;
   DECLARE v_idTimespan INT;
   
   SET v_idAnnotationObject1 = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.idAnnotationObject1'));
   SET v_idAnnotationObject2 = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.idAnnotationObject2'));
   SET v_relationType = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.relationType'));
   
   -- Handle specific relation types with new link tables
   IF v_relationType = 'rel_video_dynobj' THEN
       -- Get video ID from annotation object
       SELECT idVideo INTO v_idVideo FROM video WHERE idAnnotationObject = v_idAnnotationObject1;
       -- Get dynamic object ID from annotation object  
       SELECT idDynamicObject INTO v_idDynamicObject FROM dynamicobject WHERE idAnnotationObject = v_idAnnotationObject2;
       -- Insert into video_dynamicobject link table
       INSERT INTO video_dynamicobject (idVideo, idDynamicObject) VALUES (v_idVideo, v_idDynamicObject);
       SET @idAnnotationObjectRelation = (SELECT last_insert_id());
   ELSEIF v_relationType = 'rel_image_staobj' THEN
       -- Get image ID from annotation object
       SELECT idImage INTO v_idImage FROM image WHERE idAnnotationObject = v_idAnnotationObject1;
       -- Get static object ID from annotation object
       SELECT idStaticObject INTO v_idStaticObject FROM staticobject WHERE idAnnotationObject = v_idAnnotationObject2;
       -- Insert into image_staticobject link table
       INSERT INTO image_staticobject (idImage, idStaticObject) VALUES (v_idImage, v_idStaticObject);
       SET @idAnnotationObjectRelation = (SELECT last_insert_id());
   ELSEIF v_relationType = 'rel_sentence_time' THEN
       -- Get sentence ID from annotation object
       SELECT idSentence INTO v_idSentence FROM sentence WHERE idAnnotationObject = v_idAnnotationObject1;
       -- Get timespan ID from annotation object
       SELECT idTimespan INTO v_idTimespan FROM timespan WHERE idAnnotationObject = v_idAnnotationObject2;
       -- Insert into sentence_timespan link table
       INSERT INTO sentence_timespan (idSentence, idTimespan) VALUES (v_idSentence, v_idTimespan);
       SET @idAnnotationObjectRelation = (SELECT last_insert_id());
   ELSE
       -- For other relation types, use generic annotation object relations (if still needed)
       -- This is a fallback for any remaining relations that haven't been migrated
       DECLARE v_idRelationType INT;
       SELECT idRelationType INTO v_idRelationType FROM relationtype WHERE entry = v_relationType;
       -- Note: Since annotationobjectrelation table is removed, this will need alternative handling
       -- For now, return 0 to indicate unsupported relation type
       SET @idAnnotationObjectRelation = 0;
   END IF;
   
   RETURN @idAnnotationObjectRelation;
END;

create
    definer = fnbrasil@`%` function relation_create(par_json longtext) returns int
BEGIN
		DECLARE v_relationType VARCHAR(255);
		DECLARE v_idEntity1 INT;
		DECLARE v_idEntity2 INT;
		DECLARE v_idEntity3 INT DEFAULT NULL;
		DECLARE v_idRelation INT DEFAULT NULL;
        DECLARE v_idUser INT;
		DECLARE v_idRelationType INT;
        SET v_relationType = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.relationType'));
        SET v_idEntity1 = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.idEntity1'));
        SET v_idEntity2 = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.idEntity2'));
        IF JSON_EXTRACT(par_json, '$.idEntity3') THEN
           SET v_idEntity3 = JSON_EXTRACT(par_json, '$.idEntity3');
        END IF;
        IF (JSON_EXTRACT(par_json, '$.idRelation')) THEN
			SET v_idRelation = JSON_EXTRACT(par_json, '$.idRelation');
        END IF;
        SET v_idUser = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.idUser'));
SELECT
    idRelationType
INTO v_idRelationType FROM
    relationtype
WHERE
    entry = v_relationType;
		INSERT INTO entityrelation (idRelationType, idEntity1, idEntity2, idEntity3, idRelation) values (v_idRelationType, v_idEntity1, v_idEntity2, v_idEntity3, v_idRelation);
        SET @idEntityRelation = (SELECT last_insert_id());
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id, idUser) VALUES (NOW(),'user','C','entityrelation', @idEntityRelation, v_idUser);
        RETURN @idEntityRelation;
    END;

create
    definer = fnbrasil@`%` function relationgroup_create(par_json longtext) returns int
BEGIN
   DECLARE v_nameEn VARCHAR(255);
   DECLARE v_entry VARCHAR(255);
   DECLARE v_idUser INT;
   INSERT INTO entity (type) values ('RGP');
   SET @idEntity = (SELECT last_insert_id());
   SET v_nameEn = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.nameEn'));
   SET v_entry = CONCAT('rgp_',lower(v_nameEn));
   CALL entry_create(v_entry, v_nameEn, @idEntity);
   INSERT INTO relationgroup(entry,idEntity) values (v_entry, @idEntity);
   SET @idRelationGroup = (SELECT last_insert_id());
   INSERT INTO timeline (tlDateTime,author,operation,tableName,id, idUser) VALUES (NOW(),'user','C','relationgroup', @idRelationGroup, v_idUser);
   RETURN @idRelationGroup;
END;

create
    definer = fnbrasil@`%` function relationtype_create(par_json longtext) returns int
BEGIN
   DECLARE v_nameCanonical VARCHAR(255);
   DECLARE v_entry VARCHAR(255);
   DECLARE v_nameDirect VARCHAR(255);
   DECLARE v_nameInverse VARCHAR(255);
   DECLARE v_color VARCHAR(255);
   DECLARE v_prefix VARCHAR(255);
   DECLARE v_idRelationGroup INT;
   DECLARE v_idUser INT;
   INSERT INTO entity (type) values ('RTY');
   SET @idEntity = (SELECT last_insert_id());
   SET v_nameCanonical = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.nameCanonical'));
   SET v_nameDirect = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.nameDirect'));
   SET v_nameInverse = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.nameInverse'));
   SET v_color = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.color'));
   SET v_prefix = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.prefix'));
   SET v_idRelationGroup = JSON_EXTRACT(par_json, '$.idRelationGroup');
   SET v_idUser = JSON_EXTRACT(par_json, '$.idUser');
   SET v_entry = CONCAT('rel_',lower(v_nameCanonical));
   CALL entry_create(v_entry, v_nameCanonical, @idEntity);
   INSERT INTO relationtype(entry,nameCanonical,nameDirect,nameInverse,color,prefix,idRelationGroup,idEntity) values (v_entry, v_nameCanonical, v_nameDirect, v_nameInverse, v_color, v_prefix, v_idRelationGroup,@idEntity);
   SET @idRelationType = (SELECT last_insert_id());
   INSERT INTO timeline (tlDateTime,author,operation,tableName,id, idUser) VALUES (NOW(),'user','C','relationtype', @idRelationType, v_idUser);
   RETURN @idRelationType;
END;

create
    definer = fnbrasil@`%` function semantictype_create(par_semantictype longtext) returns int
BEGIN
	DECLARE v_nameEn VARCHAR(255);
    DECLARE v_name VARCHAR(255);
	DECLARE v_entry VARCHAR(255);
    DECLARE v_idDomain INT;
    DECLARE v_idUser INT;
    INSERT INTO entity (type) values ('STY');
	SET @idEntity = (SELECT last_insert_id());
    SET v_idDomain = JSON_EXTRACT(par_semantictype, '$.idDomain');
    SET v_nameEn = JSON_UNQUOTE(JSON_EXTRACT(par_semantictype, '$.nameEn'));
    SET v_entry = CONCAT('sty_',lower(v_nameEn));
    SET v_name = CONCAT('@',v_nameEn);
    CALL entry_create(v_entry, v_name, @idEntity);
    INSERT INTO semantictype(entry, idEntity, idDomain) values (v_entry, @idEntity, v_idDomain);
    SET @idSemanticType = (SELECT last_insert_id());
    SET v_idUser = JSON_EXTRACT(par_semantictype, '$.idUser');
    INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','semantictype', @idSemanticType, v_idUser);
    RETURN @idSemanticType;
 END;

create
    definer = fnbrasil@`%` function sentence_create(par_json longtext) returns int
BEGIN
   DECLARE v_text TEXT;
   DECLARE v_idDocument INT;
   DECLARE v_idLanguage INT;
   DECLARE v_idUser INT;
   SET v_text = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.text'));
   SET v_idDocument = JSON_EXTRACT(par_json, '$.idDocument');
   SET v_idLanguage = JSON_EXTRACT(par_json, '$.idLanguage');
   SET v_idUser = JSON_EXTRACT(par_json, '$.idUser');
   SELECT idRLSLabel INTO @idRLSLabel FROM rls_label where label = 'public';
   INSERT INTO sentence (text, idLanguage, idRLSLabel) values (v_text, v_idLanguage, @idRLSLabel);
   SET @idSentence = (SELECT last_insert_id());
   INSERT INTO document_sentence(idDocument, idSentence) values (v_idDocument, @idSentence);
   INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','sentence', @idSentence, v_idUser);
   RETURN @idSentence;
END;

create
    definer = fnbrasil@`%` function sentence_delete(par_idSentence int, par_idUser int) returns int
BEGIN
   UPDATE wordmm SET idDocumentSentence = null
		WHERE (idDocumentSentence IN (SELECT idDocumentSentence from document_sentence WHERE (idSentence = par_idSentence)));
   DELETE FROM document_sentence WHERE (idSentence = par_idSentence);
   DELETE FROM textspan WHERE (idSentence = par_idSentence);
   DELETE FROM image_sentence WHERE (idSentence = par_idSentence);
   DELETE FROM sentence_timespan WHERE (idSentence = par_idSentence);
   DELETE FROM sentence WHERE (idSentence = par_idSentence);
   INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','sentence', par_idSentence,par_idUser);
   RETURN par_idSentence;
END;

create
    definer = fnbrasil@`%` function staticobject_create(par_json longtext) returns int
BEGIN
   DECLARE v_name VARCHAR(255);
   DECLARE v_scene INT;
   DECLARE v_nobndbox INT;
   DECLARE v_idFlickr30kEntitiesChain INT;
   DECLARE v_idUser INT;
   SET v_name  = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.name'));
   SET v_scene = JSON_EXTRACT(par_json, '$.scene');
   SET v_nobndbox = JSON_EXTRACT(par_json, '$.nobndbox');
   SET v_idFlickr30kEntitiesChain = JSON_EXTRACT(par_json, '$.idFlickr30kEntitiesChain');
   SET v_idUser = JSON_EXTRACT(par_json, '$.idUser');
   INSERT INTO staticobject (name,scene,nobndbox,idFlickr30kEntitiesChain)
	values (v_name,v_scene,v_nobndbox,v_idFlickr30kEntitiesChain);
   SET @idStaticObject = (SELECT last_insert_id());
   INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','staticobject', @idStaticObject,v_idUser);
   RETURN @idStaticObject;
END;

create
    definer = fnbrasil@`%` function staticobject_delete(par_idStaticObject int, par_idUser int) returns int
BEGIN
        DELETE FROM annotation WHERE idStaticObject = par_idStaticObject;
        DELETE FROM staticobject_boundingbox WHERE (idStaticObject = par_idStaticObject);
        DELETE FROM image_staticobject WHERE (idStaticObject = par_idStaticObject);
		DELETE FROM staticobject WHERE idStaticObject = par_idStaticObject;
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','staticobject', par_idStaticObject,par_idUser);
        RETURN par_idStaticObject;
    END;

create
    definer = fnbrasil@`%` function textspan_char_create(par_json longtext) returns int
BEGIN
   DECLARE v_startChar INT;
   DECLARE v_endChar INT;
   DECLARE v_multi INT;
   DECLARE v_idLayer INT;
   DECLARE v_idInstantiationType INT;
   DECLARE v_idSentence INT;
   SET v_startChar = JSON_EXTRACT(par_json, '$.startChar');
   SET v_endChar = JSON_EXTRACT(par_json, '$.endChar');
   SET v_multi = JSON_EXTRACT(par_json, '$.multi');
   SET v_idLayer = JSON_EXTRACT(par_json, '$.idLayer');
   SET v_idInstantiationType = JSON_EXTRACT(par_json, '$.idInstantiationType');
   SET v_idSentence =  JSON_EXTRACT(par_json, '$.idSentence');
   INSERT INTO textspan (startChar, endChar, multi, idLayer, idInstantiationType, idSentence) values (v_startChar, v_endChar, v_multi, v_idLayer, v_idInstantiationType, v_idSentence);
   SET @idTextSpan = (SELECT last_insert_id());
   RETURN @idTextSpan;
END;

create
    definer = fnbrasil@`%` function textspan_create(par_json longtext) returns int
BEGIN
   DECLARE v_x INT;
   DECLARE v_y INT;
   DECLARE v_width INT;
   DECLARE v_height INT;
   DECLARE v_idStaticObject INT;
   DECLARE v_idAnnotationObject1 INT;
   DECLARE v_idAnnotationObject2 INT;
   SET v_x = JSON_EXTRACT(par_json, '$.x');
   SET v_y = JSON_EXTRACT(par_json, '$.y');
   SET v_width = JSON_EXTRACT(par_json, '$.width');
   SET v_height = JSON_EXTRACT(par_json, '$.height');
   SET @idAnnotationObject2 = annotationobject_create(0,'BBX');
   INSERT INTO boundingbox (x,y,width,height,idAnnotationObject) values (v_x, v_y, v_width, v_height, @idAnnotationObject2);
   SET @idBoundingBox = (SELECT last_insert_id());
   SET v_idStaticObject = JSON_EXTRACT(par_json, '$.idStaticObject');
   SELECT idAnnotationObject INTO v_idAnnotationObject1 FROM staticobject where (idStaticObject = v_idStaticObject);
   SET @idAnnotationObjectRelation = objectrelation_create(JSON_OBJECT("idAnnotationObject1", v_idAnnotationObject1, "idAnnotationObject2", @idAnnotationObject2, "relationType", "rel_staobj_bbox"));
   RETURN @idBoundingBox;
END;

create
    definer = fnbrasil@`%` function textspan_word_create(par_json longtext) returns int
BEGIN
   DECLARE v_startWord INT;
   DECLARE v_endWord INT;
   SET v_startWord = JSON_EXTRACT(par_json, '$.startWord');
   SET v_endWord = JSON_EXTRACT(par_json, '$.endWord');
   INSERT INTO textspan (startWord, endWord) values (v_startWord, v_endWord);
   SET @idTextSpan = (SELECT last_insert_id());
   RETURN @idTextSpan;
END;

create
    definer = fnbrasil@`%` function timespan_create(par_json longtext) returns int
BEGIN
   DECLARE v_startTime FLOAT;
   DECLARE v_endTime FLOAT;
   SET v_startTime = JSON_EXTRACT(par_json, '$.startTime');
   SET v_endTime = JSON_EXTRACT(par_json, '$.endTime');
   INSERT INTO timespan (startTime, endTime) values (v_startTime, v_endTime);
   SET @idTimeSpan = (SELECT last_insert_id());
   RETURN @idTimeSpan;
END;

create
    definer = fnbrasil@`%` function type_create(par_type longtext) returns int
BEGIN
	DECLARE v_nameEn VARCHAR(255);
    DECLARE v_name VARCHAR(255);
	DECLARE v_entry VARCHAR(255);
    DECLARE v_info VARCHAR(255);
    DECLARE v_flag VARCHAR(255);
    DECLARE v_typeGroupEntry VARCHAR(255);
    DECLARE v_idColor INT;
    DECLARE v_idUser INT;
    INSERT INTO entity (type) values ('TYP');
	SET @idEntity = (SELECT last_insert_id());
    SET v_nameEn = JSON_UNQUOTE(JSON_EXTRACT(par_type, '$.nameEn'));
    SET v_info = JSON_UNQUOTE(JSON_EXTRACT(par_type, '$.info'));
    SET v_flag = JSON_UNQUOTE(JSON_EXTRACT(par_type, '$.flag'));
    SET v_typeGroupEntry = JSON_UNQUOTE(JSON_EXTRACT(par_type, '$.typeGroupEntry'));
    SET v_idColor = JSON_EXTRACT(par_type, '$.idColor');
    SET v_entry = CONCAT('typ_',lower(v_nameEn));
    SET v_name = v_nameEn;
    CALL entry_create(v_entry, v_name, @idEntity);
    SELECT idTypeGroup INTO @idTypeGroup FROM typegroup WHERE entry = v_typeGroupEntry;
    INSERT INTO type(entry,info,flag,idColor,idEntity,idTypeGroup) values (v_entry, v_info,v_flag,v_idColor, @idEntity, @idTypeGroup);
    SET @idType = (SELECT last_insert_id());
    SET v_idUser = JSON_EXTRACT(par_type, '$.idUser');
    INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','type', @idType, v_idUser);
    RETURN @idType;
 END;

create
    definer = fnbrasil@`%` function typegroup_create(par_typegroup longtext) returns int
BEGIN
	DECLARE v_nameEn VARCHAR(255);
	DECLARE v_entry VARCHAR(255);
    DECLARE v_idUser INT;
    INSERT INTO entity (type) values ('TYG');
	SET @idEntity = (SELECT last_insert_id());
    SET v_nameEn = JSON_UNQUOTE(JSON_EXTRACT(par_typegroup, '$.nameEn'));
    SET v_entry = CONCAT('tyg_',lower(v_nameEn));
    CALL entry_create(v_entry, v_nameEn, @idEntity);
    INSERT INTO typegroup(entry,idEntity) values (v_entry, @idEntity);
    SET @idTypeGroup = (SELECT last_insert_id());
    SET v_idUser = JSON_EXTRACT(par_typegroup, '$.idUser');
    INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','C','typegroup', @idTypeGroup, v_idUser);
    RETURN @idTypeGroup;
 END;

create
    definer = fnbrasil@`%` function udfeature_create(par_udfeature longtext) returns int
BEGIN
	DECLARE v_type VARCHAR(255);
	DECLARE v_info VARCHAR(255);
    DECLARE v_idTypeInstance INT;
    INSERT INTO entity (type) values ('UDF');
	SET @idEntity = (SELECT last_insert_id());
    SET v_type = JSON_UNQUOTE(JSON_EXTRACT(par_udfeature, '$.type'));
    SET v_info = JSON_UNQUOTE(JSON_EXTRACT(par_udfeature, '$.info'));
    SELECT idTypeInstance INTO v_idTypeInstance FROM typeinstance WHERE info = v_type AND idType = 11;
    INSERT INTO udfeature(name,info,idEntity,idTypeInstance) values (concat(v_type,'=',v_info), v_info, @idEntity, v_idTypeInstance);
    SET @idUDFeature = (SELECT last_insert_id());
    RETURN @idUDFeature;
 END;

create
    definer = fnbrasil@`%` function user_create(par_login varchar(255), par_passMD5 char(32)) returns int
BEGIN
		SET @idGroup = (SELECT idGroup from `group` where name = 'BEGINNER');
		INSERT INTO user (login, passMD5, active, status, idLanguage) VALUES (par_login, par_passMD5, 1, 0, 1);
        SET @idUser = (SELECT last_insert_id());
		INSERT INTO user_group (idUser, idGroup) values (@idUser, @idGroup);
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id) VALUES (NOW(),'system','C','user', @idUser);
        RETURN @idUser;
    END;

create
    definer = fnbrasil@`%` function user_delete(par_idUser int) returns int
BEGIN
        DELETE FROM user_group WHERE (idUser = par_idUser);
        DELETE FROM timeline WHERE (idUser = par_idUser);
        DELETE FROM user WHERE (idUser = par_idUser);
        RETURN par_idUser;
    END;

create
    definer = fnbrasil@`%` function user_update(par_user longtext) returns int
BEGIN
		DECLARE v_name VARCHAR(255);
        DECLARE v_email VARCHAR(255);
        DECLARE v_idUser INT;
        DECLARE v_idGroup INT;
        SET v_name = JSON_UNQUOTE(JSON_EXTRACT(par_user, '$.name'));
        SET v_email = JSON_UNQUOTE(JSON_EXTRACT(par_user, '$.email'));
        SET v_idGroup = JSON_EXTRACT(par_user, '$.idGroup');
        SET v_idUser = JSON_EXTRACT(par_user, '$.idUser');
        DELETE FROM user_group WHERE (idUser = v_idUser);
        INSERT INTO user_group(idGroup, idUser) values (v_idGroup, v_idUser);
        UPDATE user set name = v_name, email = v_email WHERE idUser = v_idUser;
        RETURN v_idUser;
    END;

create
    definer = fnbrasil@`%` function video_create(par_json longtext) returns int
BEGIN
   DECLARE v_title VARCHAR(255);
   DECLARE v_originalFile VARCHAR(255);
   DECLARE v_sha1Name VARCHAR(255);
   DECLARE v_currentURL VARCHAR(255);
   DECLARE v_width INT;
   DECLARE v_height INT;
   DECLARE v_idLanguage INT;
   SET v_title  = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.title'));
   SET v_originalFile  = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.originalFile'));
   SET v_sha1Name  = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.sha1Name'));
   SET v_currentURL  = JSON_UNQUOTE(JSON_EXTRACT(par_json, '$.currentURL'));
   SET v_width  = JSON_EXTRACT(par_json, '$.width');
   SET v_height  = JSON_EXTRACT(par_json, '$.height');
   SET v_idLanguage  = JSON_EXTRACT(par_json, '$.idLanguage');
   INSERT INTO video (title,originalFile,sha1Name,currentURL,width,height,idLanguage)
	values (v_title,v_originalFile,v_sha1Name,v_currentURL,v_width,v_height,v_idLanguage);
   SET @idVideo = (SELECT last_insert_id());
   RETURN @idVideo;
END;

create
    definer = fnbrasil@`%` function video_delete(par_idVideo int, par_idUser int) returns int
BEGIN
        DELETE FROM document_video WHERE (idVideo = par_idVideo);
        DELETE FROM video_dynamicobject WHERE (idVideo = par_idVideo);
		DELETE FROM video WHERE idVideo = par_idVideo;
        INSERT INTO timeline (tlDateTime,author,operation,tableName,id,idUser) VALUES (NOW(),'user','D','video', par_idVideo,par_idUser);
        RETURN par_idVideo;
END;

