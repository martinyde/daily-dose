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
task composer-install

# Generate an access key
task get-key -- 2024-01-01 ch --prefix="ch_" --filetype="jpg" --digits=4

# Visit the app at the URL shown in the key output
```

## Usage

Place numbered image files in `daily-files/<folder>/` and generate an access key
using the `app:get-key` console command. The key encodes configuration for which
folder, file prefix, type, start date, and weekend handling to use. Access the
display at `/display/daily/<key>`.

## Development

See `task --list` for all available commands.
