# Krypto- & Sicherheits-Audit – gem-template-woo

Stand: 06.11.2026 · Basis `origin/main` `39dfb42` · Modus: `fix` (freigegeben von Len Messerschmidt)
Grundlage: OWASP ASVS 4/5, OWASP Cryptographic/Password Storage Cheat Sheets, BSI TR-02102, DSGVO Art. 32.

## Kurzfazit

WordPress-/WooCommerce-Theme (6 PHP-Templates, ein Menü-Skript, Gulp-Build). Das Theme speichert selbst nichts,
verarbeitet keine Formulare, hat keine eigenen Endpunkte, keine Secrets und keine Kryptografie. Passwörter,
Sessions, Bestellungen und Zahlungsdaten liegen vollständig bei WordPress/WooCommerce bzw. beim Zahlungsanbieter.
Ein zentrales Krypto-Modul (Phase 7) ist deshalb **nicht** angelegt.

Gefunden wurden Hygiene-Themen: eingechecktes `node_modules` (7330 Dateien) ohne `.gitignore`, verwundbare
Dev-Abhängigkeiten, unescapte Warenkorb-Ausgabe und direkt aufrufbare Templates. Alles ist im Code behoben.
Kein Secret in der Git-Historie (gitleaks, alle Branches).

| Phase | Ampel | Kurz |
|---|---|---|
| 1 Transport | 🟢 | Nur relative/`esc_url()`-URLs, kein `http://` in Templates (nur Dev-URL in `gulpfile.js`). HTTPS/HSTS ist Sache des Hostings |
| 2 Data at Rest | 🟢 | Theme speichert keine Daten |
| 3 Passwörter | – | nicht zuständig (WordPress-Core) |
| 4 Tokens/Sessions | – | keine eigenen Tokens, Formulare oder AJAX-Endpunkte |
| 5 Schlüssel/Secrets | 🟢 | gitleaks: keine Funde; jetzt `.gitignore` mit `.env*` (F-01) |
| 6 Verbotene Muster | 🟢 | keine Fundstelle (`md5`, `rand`, `eval`, `base64`, `Math.random` …) |
| 8 Basis-Checks | 🟡 | 19 hohe npm-Funde (nur Dev) → 5 hohe/1 mittel verbleibend; Escaping nachgezogen |
| 9 DSGVO | – | Textbaustein unten |

## Phase 0 – Inventar

- **Stack:** WordPress-Theme (PHP), WooCommerce-Support, SCSS/JS-Build mit Gulp 5, Sass, browser-sync.
- **Datenspeicher:** keine eigenen (keine Optionen, Meta, Tabellen, Uploads).
- **Externe Verbindungen:** keine. Keine eingebundenen Fremd-Assets (Fonts/CDN).
- **Secrets:** keine. Salts/DB-Zugang liegen in `wp-config.php` der jeweiligen Installation, nicht im Theme.

### Datenklassifizierung

| Klasse | Objekt | Schutz |
|---|---|---|
| K3 | – (Passwörter, Zahlungsdaten bei WP-Core/WooCommerce/PSP) | außerhalb des Themes |
| K2 | Warenkorb-Inhalt/Summe der Sitzung (nur angezeigt) | WooCommerce-Session |
| K1 | Build-Konfiguration (`gulpfile.js`, `package.json`) | – |
| K0 | Templates, CSS, JS | öffentlich |

## Findings

| ID | Schwere | Phase | Datei:Zeile | Problem | Fix | Aufwand |
|---|---|---|---|---|---|---|
| F-01 | Mittel | 5/8 | Repo-Root (kein `.gitignore`), `node_modules/` | `node_modules` (7330 Dateien, inkl. verwundbarer Pakete) und `.DS_Store` eingecheckt; kein Schutz davor, dass `.env`/Zips versehentlich committet werden | `.gitignore` anlegen, `node_modules`/`.DS_Store` aus dem Index | S |
| F-02 | Mittel | 8 | `package-lock.json` | `npm audit`: 26 Funde (19 hoch) in Dev-Werkzeugen (ws, socket.io-parser, axios, send, source-map-js über browser-sync 2) | `npm audit fix` + browser-sync 3 | S |
| F-03 | Niedrig | 8 | `functions.php:57-58` | `get_cart_contents_count()`/`get_cart_total()` ungefiltert ausgegeben; Fatal Error, wenn kein Warenkorb geladen ist | `absint()` / `wp_kses_post()`, Guard auf `WC()->cart` | S |
| F-04 | Niedrig | 8 | `header.php`, `footer.php`, `index.php`, `page.php`, `woocommerce.php` | Direktaufruf der Template-Dateien erzeugt Fatal Error (bei `display_errors` mit Serverpfad) | `defined( 'ABSPATH' ) \|\| exit;` | S |
| F-05 | Niedrig | 8 | `gulp-sourcemaps@3` → `postcss@7`, `decode-uri-component@0.2` | 5 hohe/1 mittlere Funde bleiben; Fix nur per Downgrade von gulp-sourcemaps. Betrifft nur den lokalen Build mit eigenen Dateien | später gulp-sourcemaps ersetzen (Sass-eigene Sourcemaps) | S |
| F-06 | Niedrig | 8 | `assets/css/style.min.css.map`, `gulpfile.js:77` | Sourcemap landet im Theme-Zip (verrät nur eigenes SCSS) | im `bundle()` `!**/*.map` ausschließen, falls gewünscht | S |
| F-07 | Prüfen | 1/8 | Hosting | HTTPS-Redirect, HSTS, CSP, `nosniff`, `Referrer-Policy` sind Sache des Hostings/Plugins, nicht des Themes | in der jeweiligen Installation prüfen | – |

## Priorisierte Fix-Reihenfolge
1. F-01, F-02 (Quick Wins, Repo-Hygiene und Lieferkette)
2. F-03, F-04 (Quick Wins, Härtung der Templates)
3. F-05, F-06 bei der nächsten Build-Überarbeitung
4. F-07 in jeder Shop-Installation

## Technische Maßnahmen Verschlüsselung (TOM-Baustein, Art. 32 DSGVO)
Das Theme verarbeitet und speichert selbst keine personenbezogenen Daten. Transportverschlüsselung (TLS/HSTS),
Speicherung, Passwort-Hashing und Schlüsselverwaltung richten sich nach der WordPress-/WooCommerce-Installation
und dem Hosting, in dem das Theme eingesetzt wird. Das Theme bindet keine externen Ressourcen ein und überträgt
keine Daten an Dritte.

## Umsetzung

| Finding | Status |
|---|---|
| F-01 | gefixt `4662305` |
| F-02 | gefixt `3a8f09e` (Rest siehe F-05) |
| F-03 | gefixt `e858689` |
| F-04 | gefixt `cee15cf` |
| F-05 | offen – kein Fix ohne Downgrade, nur lokaler Build |
| F-06 | offen – Entscheidung Len (Funktionsänderung am Zip) |
| F-07 | offen – Hosting/Installation |

Tests/Lint: Das Repo hat keine Tests und keinen Linter. `gulp styles` und `gulp scripts` laufen fehlerfrei durch;
PHP-Syntaxprüfung lokal nicht möglich (kein PHP installiert).
