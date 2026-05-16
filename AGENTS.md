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

## Häufige Use Cases

### CSV-Beispiel: EAV Attribute Import
```csv
code,label,frontend_label,attribute_type,input_type
custom_color,Custom Color,Color,varchar,select
custom_size,Custom Size,Size,varchar,select
custom_brand,Brand Name,Brand,text,text
```

### Szenarien
1. **Multi-Language Attributes**: Attribute mit Übersetzungen aus Excel
2. **Custom Product Attributes**: Spezialisierte Attributes für Produktkategorien
3. **Attribute-Options**: Selectable-Options wie Farben, Größen
4. **Dropdown-Population**: Tausende von Attribute-Options

## Performance-Überlegungen

- **Attribute-Lookup**: Code-basierte Suche kostet ~2-5ms pro Attribute
- **Options-Insert**: Batch-Inserts für Options sind 10x schneller als Individual
- **EAV-Structure**: EAV speichert jedes Attribut separat - mehr Writes als Columns
- **Optimal für**: 100-1.000 Attributes mit bis zu 10.000 Options
- **Index-Overhead**: Attribute-Indexes benötigen ~20-30% extra Storage

## Verwandte Module

- **import-attribute-set**: Nutzt Attributes für Attribute-Sets
- **import-converter-customer-attribute**: Konvertiert Customer Attributes
- **import-converter-product-attribute**: Konvertiert Product Attributes  
- **import-attribute** ← **diese Datei**

## Troubleshooting & FAQ

**Q: "Attribute code already exists" Fehler**
- A: Attributes sind eindeutig per Code. Nutze Update-Mode statt Create, oder andere Codes.

**Q: Attribute-Options werden nicht importiert**
- A: Options müssen nach Attribute-Erstellung importiert werden. Attribute muss existieren in DB.

**Q: "Unsupported attribute type" Fehler**
- A: Nur standard Magento Types unterstützt: `varchar`, `text`, `int`, `decimal`, `static`. Custom Types nicht möglich.

## Bekannte Einschränkungen

- **EAV-Only**: Nur EAV Attribute unterstützt
- **Keine Attribute-Validierung**: Validierung erfolgt in Importern
- **Keine Attribute-Sets**: Attribute-Sets sind in `import-attribute-set`

## Zusammenfassung

`import-attribute` ist ein **Tier 4 Modul**, das Attribute Import-Funktionalität für das Pacemaker-System bietet. Es ist die Basis für Attribute-bezogene Importer und unterstützt EAV Attribute und Attribute Options.

**Für Agenten:** Verstehe dieses Modul als **Attribute Importer** mit Observer Pattern und Repository Pattern.
