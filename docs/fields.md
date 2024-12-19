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