# Drupify / drupify — Changes Log

Scope: every change made since the first prompt (the audit-fix objective:
restructure libraries, self-host fonts, CSS tokens, gate the expensive
preprocess, modernize legacy routes, and verify). This documents code,
templates, configuration, and verification work in the `drupify` theme
(Drupal 11.4.5, local DDEV `company-website`).

---

## 1. Theme structure & libraries

**File: `themes/custom/drupify/drupify.info.yml`**
- Removed the global legacy CSS/JS libraries that previously loaded on every
  page: Bootstrap, legacy `styles.css`, Font Awesome asset CSS, and the JS
  bundles (owl-carousel, aos, fancybox, magnify, isotope, purecounter,
  simplyCountdown, clipboard).

**File: `themes/custom/drupify/drupify.libraries.yml`**
- New `drupify_homepage` library (attached by all modern templates):
  - CSS: `drupify-base.css`, `drupify-components.css`, `drupify-pages.css`,
    `drupify-responsive.css`
  - JS: `js/front-page.js`
- New `drupify_fonts` library (self-hosted DM Sans; no CDN dependency).
- Retained the legacy `bootstrap` library only for legacy/unmodernized pages.

**Verified:** modern pages load exactly 4 clean drupify CSS aggregates and no
legacy CSS anywhere (529 `.drupify` matches across all routes). JS on modern
pages is jquery + `front-page.js` + addtoany only.

## 2. CSS & fonts

- Self-hosted DM Sans font files in `themes/custom/drupify/fonts/`
  (`dmsans-normal-*`, `dmsans-italic-*`).
- New `css/drupify-fonts.css` for the `@font-face` rules.
- New token-based CSS split:
  - `css/drupify-base.css` – reset, layout primitives, design tokens (CSS
    variables for color/radii/spacing).
  - `css/drupify-components.css` – cards, grids, buttons, section heads.
  - `css/drupify-pages.css` – page-specific styles (about, services, pricing,
    blog, view list pages).
  - `css/drupify-responsive.css` – responsive behavior.
- Removed dead/legacy rules from `css/styles.css` so it no longer affects
  modern page styling.
- Appended view list-page grid styles to `css/drupify-pages.css` (team cards,
  client logo cells, blog article cards).

## 3. PHP / preprocess (`themes/custom/drupify/drupify.theme`)

- `drupify_preprocess_page()`: gated the expensive entity counts and
  content queries so they only run on the modern pages that use them
  (services, about, team, blog, portfolio), not site-wide.
- Added `drupify_preprocess_paragraph__drupify_*` helpers that build
  homepage section data (`drupify_clients`, `drupify_services`,
  `drupify_portfolio`, `drupify_insights`, ...) from the `drupify_home` view.
- **Favicon fix:** the configured favicon path pointed at
  `public://Favicon_0.png` (missing file → 404 on every page). Generated
  `sites/default/files/Favicon.png` (32×32) and set
  `drupify.settings favicon.path` to `public://Favicon.png`. Favicon now
  returns 200.
- **Portfolio gate fix (regression):** `/portfolio` is served by the legacy
  `original_files` view (`view.original_files.page_3`, display `page_3`), not
  `view.portfolio.page`. The initial gating used the non-existent route so
  `drupify_portfolio` was never built → "0 published portfolio entries" and an
  empty grid. Gate updated to `view.original_files.page_3`; the page now
  renders 8 entries with a featured project and matches the reference height
  (~4988px vs ~4972px).

## 4. Templates (`themes/custom/drupify/templates/`)

- **Removed legacy markup:** legacy banner markup removed from `html.twig`
  files; inline style blocks removed from head; `page.html.twig` keeps a
  legacy-only branch so old routes don't regress.
- **Modernized page templates** (drupify design language, shared
  `drupify-header.html.twig` / `drupify-footer.html.twig`):
  - `page--services`, `page--about-us`, `page--pricing`, `page--contact-us`
  - `page--portfolio.html.twig`
  - `page--service.html.twig`, `page--industry.html.twig` (detail pages)
  - `page--node--blog`, `page--node--client-`, `page--node--portfolio`,
    `page--node--team-member-`
- **Modernized paragraph templates** for homepage sections: hero, platforms,
  clients, services, work, process, why, testimonials, insights, faq,
  audit-cta, final-cta.
- **Legacy routes modernized:** search (`/search/node`), user auth
  (`/user/login`, `/user/register`, `/user/password`), taxonomy/category
  listing, error pages (404/403), and maintenance mode (drupify shell with
  countdown `data-deadline`).

## 5. View list pages (new grids to match references)

New theme overrides in `themes/custom/drupify/templates/views/`:

- `/team` (view `team_member_`, display `page`):
  - `views-view-unformatted--team-member---page.html.twig`
  - `views-view-fields--team-member---page.html.twig`
  - Compact 4-column card grid (image, name, role, profile link).
- `/clients` (view `client_`, display `page`):
  - `views-view-unformatted--client---page.html.twig`
  - `views-view-fields--client---page.html.twig`
  - Client logo grid.
- `/blog` (view `blog`, display `page`):
  - `views-view-unformatted--blog--page.html.twig`
  - `views-view-fields--blog--page.html.twig`
  - 3-column article cards (image, date, title link, read-article link).

> ⚠️ Naming note: `team_member_` and `client_` view ids have trailing
> underscores that merge with the display separator, producing a triple-dash
> template segment (`--team-member---page.html.twig`). Template suggestions
> confirmed via Twig theme debug.

Before: these view pages rendered raw bootstrap_barrio `views-row` output
(unstyled, huge heights). After: `/team` ~2940px (ref 2977), `/clients`
~2340px (ref 2711), `/blog` ~3686px (ref 4105).

## 6. Content / route fixes

- Blog detail alias corrected to `/blog/best-practices-efficient-drupal-development`.
- Cross-checked homepage and all pages against the reference QA screenshots in
  `visual-screenshots/`; confirmed homepage node content (12 drupify
  paragraphs) is intact.

## 7. QA & verification tooling

- `visual-screenshots/final-qa.js` — Playwright suite covering 17 routes:
  status code, horizontal overflow, broken images, legacy CSS detection, and
  console errors. **All 17 pass** (404 route's single console entry is the
  expected 404 response).
- `visual-screenshots/diff-board.html` — labeled reference-vs-live comparison
  board.
- `/tmp/ocr` (macOS Vision/Swift) — helper used to OCR reference screenshots
  (output: `normalizedY minX text`) when comparing section layout.

## 8. Documentation

- `docs/drupify-structure-cleanup-audit.md` — appended verification sections:
  asset-loading restructure, portfolio regression note, and the view list-page
  grid changes.

---

## Key files touched

| Area | Files |
| ---- | ----- |
| Theme definition | `drupify.info.yml`, `drupify.libraries.yml` |
| CSS | `css/drupify-base.css`, `drupify-components.css`, `drupify-pages.css`, `drupify-responsive.css`, `drupify-fonts.css`, `styles.css` |
| Fonts | `fonts/dmsans-*.woff2` |
| PHP | `drupify.theme` |
| Shared templates | `templates/includes/drupify-header.html.twig`, `drupify-footer.html.twig` |
| Page templates | `templates/page/page--{view--blog--page,view--team-member--page,view--client--page,portfolio,node--*, ...}` |
| View grids | `templates/views/views-view-{unformatted,fields}--{team-member,client}---page.html.twig`, `views-view-{unformatted,fields}--blog--page.html.twig` |
| QA | `visual-screenshots/final-qa.js`, `visual-screenshots/diff-board.html` |
| Config | `drupify.settings favicon.path` → `public://Favicon.png` |
| Docs | `docs/drupify-structure-cleanup-audit.md` |

## Known remaining gaps (not code regressions; by design)

- Homepage renders all 12 of its node-1 drupify paragraphs, but the reference
  homepage (`drupal-home-qa-desktop.png`) is an earlier design: different
  headings/order plus two sections that do not exist in content (a "real
  project work" proof showcase band and a "Simple ways to start" entry-points
  section).
- `/services` shows 5 cards vs the reference's 6 (the reference had a
  dedicated "Performance & security" card); content difference on node 80,
  pre-existing.