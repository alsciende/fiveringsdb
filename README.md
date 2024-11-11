![develop status](https://github.com/alsciende/fiveringsdb/actions/workflows/symfony.yml/badge.svg?branch=main)

# FiveringsDB

## How to help

### Prerequisites

- Docker (and Docker Compose)
- GNU make (or equivalent)

### Installation

- clone this repo and cd to it
- `make up`
- `make install`
- go to http://localhost:8080

### Contributing

- fork this repo
- create a branch in your repo
- submit a PR to upstream (this repo)

## Image Processing

### Prerequisites

- Gimp with CLI (brew install gimp)
- Scripts in ./script-fu installed in the Gimp script folder

### Steps

Apply the script to the JPEG images and turn them into a 302x422 webp.
```
gimp -i -b '(glob-rounded-rectangle-cut "*.jpeg" 755 1055 50 302 422)' -b '(gimp-quit 0)'
```

Export to the assets folder.
