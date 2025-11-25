# Castaways Custom Code

This plugin adds custom CSS, JS and PHP code for Castaways.

## Build Assets
- Prerequisites: Node.js 18+ and npm.
- Install dev dependencies:

```zsh
cd /Applications/MAMP/htdocs/wordpress/wp-content/plugins/castaways
npm install
```

- Build minified assets with source maps:

```zsh
npm run build
```

- Outputs:
	- `assets/css/castawaystravel.min.css` (+ `*.map`)
	- `assets/js/castawaystravel.min.js` (+ `*.map`)

The enqueue logic auto-detects `.min` files; non-minified files are used if `.min` is missing.
