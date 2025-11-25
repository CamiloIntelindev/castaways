# Changelog

## v1.1.0 - 2025-11-25
- Asset enqueues: moved to `wp_enqueue_scripts`, conditional loading.
- Performance: transient caching with invalidation hooks; optimized cron using WP timezone and numeric meta queries.
- Security: nonce checks and sanitized inputs for AJAX; escaped outputs.
- i18n: standardized text domain `castawaystravel`; localized user strings.
- Diagnostics: admin settings debug toggle (`CASTAWAYS_DEBUG`).
- Build: added npm scripts and PostCSS/Terser pipeline to generate `.min.css`/`.min.js` with source maps.

## v1.0.0
- Initial version.