---
title: References
order: 6
description: References
---

# References and Further Reading

## Primary Sources
- **DUL Namespace**: http://www.ontologydesignpatterns.org/ont/dul/DUL.owl
- **Ontology Design Patterns Portal**: http://ontologydesignpatterns.org/

## Foundational Papers
- Gangemi, A., & Mika, P. (2003). "Understanding the Semantic Web through Descriptions and Situations"
- Masolo, C., et al. (2003). "DOLCE: a Descriptive Ontology for Linguistic and Cognitive Engineering" (WonderWeb D18)
- Borgo, S., & Masolo, C. (2009). "Foundational Choices in DOLCE"

## Related Ontologies
- **DOLCE Lite-Plus**: Parent ontology
- **DOLCE Full**: Complete formalization
- **IOLite**: Information Objects ontology
- **COS/KCO**: Computational objects ontologies

## Philosophical Background
- Guarino, N. (1998). "Formal Ontology and Information Systems"
- Smith, B. (2003). "Ontology" (Blackwell Guide to the Philosophy of Computing and Information)
- Searle, J. (1995). "The Construction of Social Reality"

## Pattern Catalogs
- Gangemi, A. (2005). "Ontology Design Patterns for Semantic Web Content"
- Blomqvist, E., et al. (2016). "Ontology Design Patterns: Current Trends and Future Directions"


```dot
# http://www.graphviz.org/content/cluster

digraph G {
  rankdir=LR;
  graph [fontname = "Handlee"];
  node [fontname = "Handlee" shape="box" style="rounded" ];
  edge [fontname = "Handlee"];
    layout=dot;

  Description [color=blue fillcolor=blue] 
  Situation [color=orange fillcolor=orange]
  Collection [color=red fillcolor=red]
  "Information Object" [color=darkgreen fillcolor=darkgreen]
  Concept [color=blue fillcolor=blue]
  Role [color=blue fillcolor=blue]
  Task [color=blue fillcolor=blue]
  Entity
  Object
  Quality [color=purple fillcolor=purple]
  Region [color=purple fillcolor=purple]
  Agent [color=red fillcolor=red]
  "Information Realization" [color=darkgreen fillcolor=darkgreen]
  "Physical Agent" [color=red fillcolor=red]
  "Social Object" [color=red fillcolor=red]
  "Object Aggregate"
  "Social Agent" [color=red fillcolor=red]
  
  Agent -> "Social Agent" [label="acts for" color="red" fontcolor="red"]
  Concept -> "Collection" [label="characterizes" color="blue"  fontcolor="blue"]
  Concept -> "Entity" [label="classifies" color="blue" fontcolor="blue"]
  Agent -> "Social Object"   [label="conceptualizes" color="red"  fontcolor="red"]
  "Information Realization" -> Situation [label="concretely expresses" color="darkgreen" fontcolor="darkgreen"]
  Concept -> "Collection" [label="covers" color="blue" fontcolor="blue"]
  Description -> Concept [label="defines"  color="blue" fontcolor="blue"]
  Description -> Role [label="defines role" color="blue" fontcolor="blue"]
  Description -> Task [label="defines task" color="blue" fontcolor="blue"]
  Description -> Entity [label="describes" color="blue" fontcolor="blue"]
  "Information Object" -> "Social Object" [label="expresses" color="darkgreen" fontcolor="darkgreen"]
  "Information Object" -> Concept [label="expresses concept" color="darkgreen"  fontcolor="darkgreen"]
  Collection -> Entity [label="has member" color="red" fontcolor="red" fontcolor="red"]
  Entity -> Quality [label="has quality"]
  Entity -> Region [label="has region"]
  Object -> Role [label="has  role"]
  Entity -> Situation [label="has setting"]
  Role -> Task [label="has task" color="blue" fontcolor="blue"]
  Description -> "Social Agent" [label="introduces" color="blue" fontcolor="blue"]
  "Information Object" -> Entity [label="is about" color="darkgreen" fontcolor="darkgreen"]
  "Information Realization" -> "Information Object" [label="realizes" color="darkgreen" fontcolor="darkgreen"]
  Situation -> Description [label="satisfies" color="orange" fontcolor="orange"]
  Description -> Collection [label="unifies" color="blue" fontcolor="blue"]
  Description -> Concept [label="uses concept"  color="blue" fontcolor="blue"]
  "Physical Agent" -> "Social Agent" [label="acts through" color="red" fontcolor="red"]
  "Object Aggregate" -> Entity
  
  
}

```
