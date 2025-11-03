---
title: Frame Classification
order: 15
description: Frame classification
---

# Frame Classification

Frames are classified into 3 dimensions: Domain, Type, and Namespace

### Dimension: Domain

- The frames in the current network were developed over many years, in several different projects. Many frames are for general use, but many others are related to specific domains.

- Recording the domain to which a frame belongs can help in the disambiguation process, if it is possible to determine the domain from the context of the sentence.

- The list of domains is open and can/should be extended as new frames are created.

- The initial list includes: `@Agriculture, @Biology, @Body, @Business, @Cloth, @Communication, @Emotion, @Employment, @Finance, @Fire,
@Food, @Generic, @Health, @Legal, @Linguistics, @Math, @Military, @Music, @Physics, @Psychology, @Social, @Sports, @Time, @Tourism,
@Transport, @Visit, @Weapon`

### Dimension: type

- The frames in the network are of different types. "Type" here refers to some characteristics and functions that the frame possesses within the network.
Explicitly characterizing the frame type makes the network structure more understandable and can facilitate the disambiguation process.

- The types implemented in the current network are:

- `@Non-lexical`: frames that do not have LUs and are used to connect other frames semantically in the network. They are used to maintain the network structure.

- `@Lexical`: frames that are evoked by LUs.

- `@ImageSchemas`: frames that were created in the network to represent very basic/primitive *image schemas*. Generally, these frames are very generic and establish a structure (via Frame Elements) that can be inherited by lexical or non-lexical frames.

- `@Scenario`: frames that have the function of structuring a given scenario, bringing together other frames through various different relationships.
Scenario frames are important because, if activated, they can allow inferences about other parts of the scene/sentence that are not explicitly stated, enriching the NLU process.

- `@Non-perspectivalized`: According to the Book, these frames have a great diversity of LUs, each with a background scene. These frames do not have:

  - A consistent set of FEs for the targets

  - A consistent time for the events or participants

  - A consistent point of view among the targets

    These frames could be broken down into more consistent frames, but these would have few LUs. Today there are 41 frames marked as *non-perspectivalized frames*.

### Dimension: namespace

- An important idea that had been worked on was the possibility of establishing a *frame lattice* that would allow the definition of *top frames*. 
The network would be structured around these top frames. In the Lutma implementation, a lattice was also defined with the aim of facilitating the creation of new frames.

- The problem with defining top frames is the difficulty of establishing inheritance or perspective relationships in many generic frames. 
Thus, the idea implemented was the definition of *namespaces* for frames. These namespaces function as a set of frames that share some basic semantics.

- The frames sharing the same namespace do not need to be related to each other. 

- In the current version, the following namespaces are implemented:

| Namespace             | Description                                                                                                    |
|-----------------------|----------------------------------------------------------------------------------------------------------------| 
| @eventive | Eventive frames that do not have an explicitly defined agent, cause, or experiencer (e.g., natural phenomena). |
| @causative | Eventive frames that have a cause or an agent.                                                                 |
| @inchoative | Eventive frames that exhibit inchoative alternation.                                                           |
| @stative | Frames that represent states.                                                                                  |
| @experience | Eventive frames that profile the participant as an experiencer in an event.                                    |
| @transition | Eventive frames that represent changes in situation (states, attributes, categories, etc.).                    |
| @attribute | Frames that represent attributes or attribute values.                                                          |
| @entity | Frames that represent entities.                                                                                |
| @relation | Frames that represent relationships.                                                                           |
