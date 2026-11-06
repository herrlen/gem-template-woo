# Sicherheit – gem-template-woo

## Sicherheitslücke melden
Bitte nicht öffentlich als Issue. Mail an **hello@lensuh.de** (Kontakt laut `IMPRESSUM.md`) mit Beschreibung,
betroffener Datei/Version und Schritten zum Nachstellen. Antwort innerhalb von 3 Werktagen.

## Geltungsbereich
WordPress-/WooCommerce-Theme ohne eigene Datenspeicherung, Formulare, Endpunkte oder Kryptografie. Passwörter,
Sessions, Bestellungen und Zahlungen verantwortet WordPress/WooCommerce bzw. der Zahlungsanbieter.

## Regeln für dieses Theme
- Jede Ausgabe escapen (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`), jede Template-Datei mit
  `defined( 'ABSPATH' ) || exit;`.
- Eigene Formulare/AJAX nur mit Nonce (`wp_nonce_field`/`check_ajax_referer`) und Capability-Prüfung.
- Keine Fremd-Assets von CDNs ohne Not (DSGVO); keine `http://`-URLs.
- Falls später doch Geheimnisse nötig werden: nie im Theme, sondern in `wp-config.php`/Umgebung; Krypto nur
  über `sodium_*` bzw. `random_bytes`/`hash_hmac`/`hash_equals` (siehe Krypto-Standard).
- Keine Secrets im Repo; `.env*`, `node_modules`, Zips sind gitignored.
- Abhängigkeiten: `npm audit` vor jedem Release; nur Dev-Werkzeuge, nichts davon im Theme-Zip.
