# AGENTS.md - import-attribute

## Zweck & Verantwortung

Das `import-attribute` Modul bietet **Attribute Import-Funktionalität** für das Pacemaker Import-System. Es ist ein **Tier 4 Modul** und dient als Basis für Attribute-bezogene Importer.

**Hauptverantwortung:**
- EAV Attribute Import
- Attribute Options Import
- Repository Pattern für Attribute-Persistierung
- Service Layer für Attribute-Verarbeitung
- Observer Pattern für Attribute-Hooks
- 4 Dependents (attribute-set, converter-customer-attr, converter-product-attr)

## Architektur & Design Patterns

### Kern-Klassen
- **AttributeRepository**: Persistierung von Attributen
- **AttributeOptionRepository**: Persistierung von Attribute Options
- **AttributeProcessor**: Service Layer für Attribute-Verarbeitung
- **AttributeObserver**: Observer für Attribute-Hooks
- **AttributeOptionObserver**: Observer für Attribute Options

### Verwendete Patterns
- **Observer Pattern**: Für Attribute-Hooks
- **Repository Pattern**: Für Daten-Persistierung
- **Service Layer**: Für Business Logic
- **Factory Pattern**: Für Object-Erstellung

## Abhängigkeiten

### Externe Pakete
- **Keine** - Nur Importer-Implementierungen

### TechDivision Dependencies
- **import** ^18.1 - Core Framework

### Abhängig von diesem Modul (4 Reverse Dependencies)
1. **import-attribute-set** - Attribute Set Importer
2. **import-converter-customer-attribute** - Customer Attribute Converter
3. **import-converter-product-attribute** - Product Attribute Converter
4. **import-cli-simple** - Master CLI

## Wichtige Entry Points

### Repository Klassen
```php
// Attribute Repository
AttributeRepository::create($row): void
AttributeRepository::update($row): void
AttributeRepository::findByCode($code): array

// Attribute Option Repository
AttributeOptionRepository::create($row): void
AttributeOptionRepository::findByCode($code): array
```

### Observer Klassen
```php
// Attribute Observer
AttributeObserver::handle($row): void

// Attribute Option Observer
AttributeOptionObserver::handle($row): void
```

## Events & Extension Points

**Keine Events** - Tier 4 Importer-Modul

## Hints für KI-Agenten

### Wichtig zu verstehen
1. **Tier 4 Modul**: Basis für Attribute-bezogene Importer
2. **EAV-fokussiert**: Spezialisiert auf EAV Attribute
3. **Observer Pattern**: Für Attribute-Hooks
4. **Repository Pattern**: Für Daten-Persistierung
5. **4 Dependents**: Basis für spezialisierte Importer

### Bei Änderungen
- **EAV-Kompatibilität**: Beachte EAV-Struktur
- **Observer-Kompatibilität**: Neue Observers sollten optional sein
- **Backward Compatibility**: Alte Imports sollten noch funktionieren

### Implementierungs-Hinweise
- Nutze Observer Pattern für Custom Attribute-Processing
- Beachte Attribute-Optionen bei Imports
- Erwäge Attribute-Validierung

## Bekannte Einschränkungen

- **EAV-Only**: Nur EAV Attribute unterstützt
- **Keine Attribute-Validierung**: Validierung erfolgt in Importern
- **Keine Attribute-Sets**: Attribute-Sets sind in `import-attribute-set`

## Zusammenfassung

`import-attribute` ist ein **Tier 4 Modul**, das Attribute Import-Funktionalität für das Pacemaker-System bietet. Es ist die Basis für Attribute-bezogene Importer und unterstützt EAV Attribute und Attribute Options.

**Für Agenten:** Verstehe dieses Modul als **Attribute Importer** mit Observer Pattern und Repository Pattern.
