# Phrasal CE Labels

| Label | Name | Description | Examples |
|-------|------|-------------|----------|
| `Head` | Head | Core element of a phrase | nouns, verbs, pronouns |
| `Mod` | Modifier | Specifies/describes the head | articles, adjectives, numerals |
| `Adm` | Admodifier | Modifies modifiers | "very", "quite", "extremely" |
| `Adp` | Adposition | Marks relations | prepositions, postpositions |
| `Lnk` | Linker | Connects elements | subordinators, linking particles |
| `Conj` | Conjunction | Coordinates elements | "and", "or", "but" |

## Head

- POS: NOUN, PRON, PROPN, VERB, ADV, AUX
- Typical features: Gender, Number, VerbForm, Mood, Tense, Aspect, Voice, Evident, Polarity, Person,PronType=Prs,PronType=Rcp,PronType=Rel,

## Mod

- POS: DET, NUM, ADJ (if not $features['VerbForm'])
- Typical features: Definite, PronType=Art,PronType=Dem,PronType=Ind

## Adm

- POS: Some ADV
- 

## Adp

- POS: ADP

## Lnk

- POS: SCONJ, PART

## Conj

- POS: CCONJ


