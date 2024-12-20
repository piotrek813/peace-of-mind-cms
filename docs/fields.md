# Form Field Components

Documentation for available form field components and their constructor arguments.

## TextField

Basic single-line text input field.

### Arguments
| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `name` | string | Yes | - | Field identifier used in form submission |
| `label` | string | Yes | - | Label text displayed above the input |
| `required` | bool | No | `false` | Whether the field is required |
| `value` | string\|null | No | `null` | Initial value of the field |

## TextareaField

Multi-line text input field.

### Arguments
| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `name` | string | Yes | - | Field identifier used in form submission |
| `label` | string | Yes | - | Label text displayed above the textarea |
| `required` | bool | No | `false` | Whether the field is required |
| `value` | string\|null | No | `null` | Initial value of the field |

## SlugField

Text input that automatically generates URL-friendly slugs from another field.

### Arguments
| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `name` | string | Yes | - | Field identifier used in form submission |
| `label` | string | Yes | - | Label text displayed above the input |
| `sourceField` | string | Yes | - | Name of the field to generate slug from |
| `required` | bool | No | `false` | Whether the field is required |
| `value` | string\|null | No | `null` | Initial value of the field |

### Notes
- The SlugField extends TextField and adds automatic slug generation
- Slugs are generated from the sourceField's value on keyup events
- Special characters are replaced with hyphens
- The result is lowercase with no leading/trailing hyphens

## GroupField

Container field that groups related fields together with collapsible functionality.

### Arguments
| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `name` | string | Yes | - | Field identifier used in form submission |
| `label` | string | Yes | - | Label text displayed in the header |
| `fields` | array | Yes | - | Array of field objects to be rendered inside the group |
| `required` | bool | No | `false` | Whether the group is required |

### Notes
- Fields inside a group are nested in the form data structure
- Groups can be nested inside other groups
- Groups are collapsible by clicking the header
- Visual hierarchy is maintained through background colors

## ListField

Dynamic list of fields that can be added, removed, and reordered.

### Arguments
| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `name` | string | Yes | - | Field identifier used in form submission |
| `label` | string | Yes | - | Label text displayed in the header |
| `fields` | array | Yes | - | Array of field configurations available for adding to the list |
| `required` | bool | No | `false` | Whether the list is required |
| `value` | array | No | `[]` | Initial list items |

### Notes
- List items can be added via a modal interface
- Items can be reordered via drag and drop
- Each item can be collapsed/expanded independently
- Items can be deleted individually
- Supports nested groups and lists within items
- Field names are automatically indexed in the form data structure
- Search functionality in the add item modal for filtering field types