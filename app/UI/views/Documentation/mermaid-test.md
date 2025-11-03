# Mermaid Diagram Test

This page demonstrates Mermaid diagram rendering in Webtool 4.2 documentation.

## Frame Causation Example

The following diagram shows the relationships between frame types:

```mermaid
classDiagram
    class `Transitive_action`
    class `State`
    class `Event`
    class `Frame_A:Causative`
    class `Frame_A:Stative`
    class `Frame_A:Inchoative`
    `Transitive_action`  <|-- `Frame_A:Causative`
    `State`  <|-- `Frame_A:Stative`
    `Event`  <|-- `Frame_A:Inchoative`
    `Frame_A:Causative` --> `Frame_A:Inchoative`: causative_of
    `Frame_A:Inchoative` --> `Frame_A:Stative`: inchoative_of
```

## Additional Examples

### Simple Flowchart

```mermaid
flowchart LR
    A[Start] --> B{Is it?}
    B -->|Yes| C[OK]
    B -->|No| D[End]
    C --> D
```

### Sequence Diagram

```mermaid
sequenceDiagram
    User->>+System: Request annotation
    System->>+Database: Fetch frame data
    Database-->>-System: Return data
    System-->>-User: Display annotation UI
```

This demonstrates that Mermaid diagrams from Obsidian can be directly copied into Webtool documentation files and will render correctly.
