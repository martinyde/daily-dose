# Daily Dose

A Symfony web application that displays a rotating daily image from a numbered
image collection, with templated text overlays.

## Requirements

- Docker & Docker Compose
- [Task](https://taskfile.dev/) (optional, but recommended)

## Getting Started

```bash
# Start local development environment
task compose-up

# Install PHP dependencies
task composer -- install

# Copy and configure folder settings
cp config/daily_folders.yaml.dist config/daily_folders.yaml

# Generate an access key
task get-key -- 2024-01-01 my_folder

# Visit the app at the URL shown in the key output
```

## Folder Configuration

Image folders are configured in `config/daily_folders.yaml` (git-ignored).
A `config/daily_folders.yaml.dist` template is provided as a starting point.

### Adding a new folder

1. Place your numbered image files in `daily-files/<folder_name>/`.
2. Add an entry to `config/daily_folders.yaml`:

```yaml
my_folder:
  prefix: ""          # Text before the number (default: "")
  filetype: jpg       # File extension without dot (default: "jpg")
  digits: 4           # Zero-padded number width, e.g. 4 = 0001 (default: 4)
  start_zero: false   # First file numbered 0 instead of 1 (default: false)
```

3. Generate a key via CLI or the `/get-key` web form:

```bash
task get-key -- 2024-01-01 my_folder
task get-key -- 2024-01-01 my_folder --ignore_weekends
```

### Example

If your files are `daily-files/ch/ch_0001.jpg`, `daily-files/ch/ch_0002.jpg`, etc:

```yaml
ch:
  prefix: "ch_"
  filetype: jpg
  digits: 4
  start_zero: false
```

## Usage

Access the daily display at `/display/daily/<key>`.

Keys can be generated:
- **CLI:** `task get-key -- <start_date> <folder_name> [--ignore_weekends]`
- **Web:** Visit `/get-key` (protected by basic auth in production)

## Development

See `task --list` for all available commands.
