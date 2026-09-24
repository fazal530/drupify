# Drupify Structure Cleanup Audit

## Safe Cleanup Completed

- Removed the Classic Pro theme dependency on `dt_user_statistics`.
- Uninstalled Drupal 11 incompatible modules:
  - `dt_user_statistics`
  - `paragraphs_ee`
- Removed `drupal/paragraphs_ee` from Composer dependencies and deleted its contrib code through Composer.
- Archived the uninstalled custom statistics module at `_archive/modules/dt_user_statistics`.
- Cleaned editor-facing content type labels without changing machine names:
  - `client_` displays as `Client`
  - `team_member_` displays as `Team Member`
  - `testimonial_` displays as `Testimonial`
- Archived superseded page-specific templates:
  - `_archive/theme-templates/page/page--node--87.html.twig`
  - `_archive/theme-templates/page/page--node--9.html.twig`
- Unpublished node `87` (`Blog`) because `/blog` is now owned by the Blog View page.
- Converted `/team` and `/clients` from legacy Basic Page holders into proper View pages:
  - `team_member_.page` at `/team`
  - `client_.page` at `/clients`
- Moved old holder aliases:
  - `/team` to `/team-old-page`
  - `/clients` to `/clients-old-page`
- Unpublished old holder nodes:
  - node `65` (`Team`)
  - node `66` (`Clients`)
- Split the modern Drupify CSS library into readable files:
  - `css/drupify-base.css`
  - `css/drupify-components.css`
  - `css/drupify-pages.css`
  - `css/drupify-responsive.css`
- Archived the original combined CSS at `_archive/theme-css/front-page.css`.
- Removed empty/legacy patch CSS from the active global library:
  - `css/portfolio-page.css`
  - `css/custom.css`
- Added real footer destinations:
  - `/service/drupal-support`
  - `/industry/healthcare`
  - `/industry/education`
  - `/industry/agencies`
- Added reusable bundle templates:
  - `page--node--service.html.twig`
  - `page--node--industry.html.twig`
  - `page--node--blog.html.twig`
  - `page--view--blog--page.html.twig`

## Current Content Model

Keep as active production structure:

- `home_page`
- `service`
- `industry`
- `portfolio`
- `blog`
- `client_`
- `faq`
- `team_member_`
- `testimonial_`
- `webform`

Review later:

- `short_codes`: legacy/demo builder content, many nodes.
- `career`: only keep if Careers will be public.
- `article`: no content currently.
- `page`: still used for About, Pricing, Services, Contact, and old demo pages.

## Paragraph Structure

Keep as current Drupify production homepage paragraphs:

- `drupify_hero`
- `drupify_platforms`
- `drupify_clients`
- `drupify_services`
- `drupify_work`
- `drupify_process`
- `drupify_process_item`
- `drupify_why`
- `drupify_why_item`
- `drupify_testimonials`
- `drupify_testimonial_item`
- `drupify_insights`
- `drupify_faq`
- `drupify_faq_item`
- `drupify_audit_cta`
- `drupify_final_cta`

Do not delete yet:

- Old home-1/home-2/home-3 paragraph types are still referenced by legacy/demo nodes.
- Short code paragraphs are heavily referenced by legacy short-code content.

## Theme Template Cleanup Candidates

Keep:

- `templates/includes/drupify-header.html.twig`
- `templates/includes/drupify-footer.html.twig`
- `templates/page/page--front.html.twig`
- `templates/page/page--view--blog--page.html.twig`
- `templates/page/page--node--blog.html.twig`
- `templates/page/page--node--service.html.twig`
- `templates/page/page--node--industry.html.twig`
- `templates/page/page--node--portfolio.html.twig`
- `templates/page/page--node--team-member-.html.twig`
- `templates/page/page--node--client-.html.twig`
- Modern Drupify paragraph templates.
- Modern Drupify CSS files:
  - `drupify-base.css`
  - `drupify-components.css`
  - `drupify-pages.css`
  - `drupify-responsive.css`

Review before archiving:

- `page--node--62.html.twig`: About page.
- `page--node--69.html.twig`: Pricing page.
- `page--node--80.html.twig`: Services listing page.
- `page--node--97.html.twig`: Contact page.

Archived:

- `page--node--87.html.twig`: old Blog node page, replaced by `/blog` View page.
- `page--node--9.html.twig`: old Migration node-specific template, now replaced by service bundle template.
- 218 unused legacy paragraph/View/page templates archived to
  `_archive/theme-templates/unused-20260911-162317`.
- 3 legacy service paragraph templates archived to
  `_archive/theme-templates/legacy-service-20260911-163322` after service detail
  pages were updated to render service list paragraph data directly in the
  modern page template.
- 3 legacy contact/error paragraph templates archived to
  `_archive/theme-templates/legacy-contact-error-20260911-163915` because
  contact and error pages are rendered by modern page templates.

Current active theme-template state after cleanup:

- 27 paragraph templates remain active.
- 5 View templates remain active.
- Remaining legacy paragraph templates are still referenced by published content
  or enabled custom blocks and should be handled only after replacing that
  content with modern Drupify paragraphs/templates.

Verification:

- Drupal cache rebuilt successfully after archiving templates.
- Detail QA passed for portfolio, team, and client detail pages.
- Services QA passed for mobile, tablet, and desktop layouts.
- Contact QA passed for mobile, tablet, and desktop layouts.
- Error page QA passed for `/page-not-found`, `/access-forbidden`, and a real
  404 route.
- Link QA checked 34 internal links with 0 broken links.

## Views

Keep active production Views:

- `blog`
- `services`
- `portfolio`
- `drupify_home`
- `client_`
- `faq`
- `team_member_`
- `testimonial`

Review later:

- `entity_pager_example`
- `frontpage`
- `who_s_new`
- `who_s_online`
- `comments_recent`
- `content_recent`
- `riaz_s_portfolio`
- multiple legacy blog and portfolio block displays.

## Active Config Cleanup Completed

Disabled legacy/demo block placements that are no longer part of the modern
public Drupify design:

- Old blog sidebars and ad blocks.
- Old service and portfolio sidebar blocks.
- Old personal portfolio/user-info blocks for `/portfolio/ishfaq` and
  `/portfolio/riaz`.
- Old banner and blog breadcrumb Views blocks.

Normalized messy public aliases:

- Team members now use `/team/name` aliases.
- Older portfolio entries now use `/portfolio/project-name` aliases instead of
  `/portfolio/project-N`.
- Duplicate old alias rows for the cleaned records were removed.

Verification:

- Drupal cache rebuilt successfully after cleanup.
- Expanded browser link QA checked 36 internal links with 0 broken links.

## Assets

Generated public asset cleanup completed. The detailed report is stored in:

- `docs/drupify-public-files-asset-audit.md`
- `docs/drupify-public-files-asset-audit.json`

Archived stale generated aggregate/cache files and junk files to:

- `_archive/public-files-generated-20260911-155651`

Archived unreferenced Drupal image-style derivatives to:

- `_archive/public-files-image-styles-20260911-160506`

Archived unreferenced duplicate branding/demo images to:

- `_archive/public-files-duplicate-branding-20260911-161335`

Current post-cleanup state:

- `sites/default/files` is about 72 MB after cleanup.
- 10 unreferenced candidate files remain, all intentionally retained Drupal
  support files such as media icons, sitemap XML, `.htaccess`, and config
  README files.
- 50 current Drupal aggregate files remain after cache/browser QA regenerated
  the active CSS/JS files.
- 0 junk files remain.

Do not delete the remaining candidate files yet. Many assets can be referenced
from:

- node fields
- paragraph fields
- body HTML
- theme CSS/Twig
- theme settings
- generated aggregate files

Next safe asset step:

1. Leave the 10 remaining support candidates in place.
2. Re-run the asset audit monthly or after major design/content changes.
3. Archive only stale generated aggregates or new duplicate demo uploads.
4. Delete only after visual/link QA confirms there are no missing images.

## Asset Loading Restructure (completed and verified)

Goal: modern Drupify pages load only the drupify CSS/fonts/JS; the legacy
Bootstrap + Font Awesome + 10-legacy-JS stack is confined to the legacy
template fallback.

Changes applied:

- `drupify.info.yml`: removed the global `libraries:` block
  (`drupify/bootstrap`, `drupify/global-styling`). Base theme
  `bootstrap_barrio` contributes nothing (all its settings are disabled).
- `drupify.libraries.yml`: added `drupify_fonts` library, added it as a
  dependency of `drupify_homepage`, removed the unused `bootstrap_cdn`
  definition.
- `html.html.twig`: removed the inline `<style>{{ styles }}</style>` block so
  the legacy custom CSS blob (theme setting `styles`) is no longer injected on
  every page.
- `page.html.twig` legacy branch now attaches
  `drupify/global-styling`, `fontawesome/fontawesome.webfonts`,
  `fontawesome/fontawesome.webfonts.shim`, and renders `<style>{{ styles }}</style>`.
- `fontawesome.settings.load_assets = false` (persisted via
  `\Drupal::service('config.factory')->getEditable(...)`, not `drush config:set`,
  which has an interactive-prompt bug). `use_cdn` stays `true` so the webfonts
  libraries still resolve when the legacy branch attaches them.
- DM Sans is self-hosted: `fonts/dmsans-{normal,italic}-{latin,latinext}.woff2`
  (v17 variable, weight 100-1000) + `css/drupify-fonts.css` `@font-face` rules.
- CSS tokens fixed: `.drupify-home` now defines `--dp-ink: #1b1c21` and
  `--dp-radius: 8px` (values from design-prototype).
- Dead CSS removed from `css/drupify-pages.css` and
  `css/drupify-responsive.css`: `.drupify-blog-featured`,
  `.drupify-service-fit-grid`, `.drupify-team-card` (kept shared
  `.drupify-client-card` groups).
- `drupify.theme` `drupify_preprocess_page()`: expensive view queries
  gated by route/bundle/layout instead of running on every page.
- `drupify_theme_suggestions_page_alter()`: added
  `page--user--login/register/pass` and `page--taxonomy` suggestions.
- Modernized remaining legacy surfaces: search, user login/register/password,
  taxonomy (blog category), and maintenance pages now use the `drupify-home`
  shell (new styles appended to `drupify-pages.css`). Legacy page banners
  removed from the user form templates.
- Favicon: generated `sites/default/files/Favicon.png`, updated
  `drupify.settings.favicon.path` (the old `Favicon_0.png` did not exist
  and 404'd on every page).

### Payload after restructure

Modern and former-legacy routes now share ONE clean aggregate group:

- CSS: 4 drupify aggregates (`drupify-base/components/pages/responsive` +
  `drupify-fonts`), zero bootstrap, zero `styles.css`, zero Font Awesome.
- JS: jquery + the drupify `front-page.js` aggregate + addtoany (content
  share) only. No bootstrap/popper/owl/fancybox/magnific/global.js.

### Route QA (Playwright, `visual-screenshots/final-qa.js`)

All checked routes return 200 with no horizontal overflow, no broken images,
no console errors, and no legacy CSS loaded:

`/`, `/about-us`, `/services`, `/pricing`, `/contact-us`, `/blog`,
`/blog/best-practices-efficient-drupal-development`, `/portfolio`, `/team`,
`/clients`, `/service/drupal-support`, `/search/node?keys=drupal`,
`/user/login`, `/user/register`, `/user/password`,
`/category/best-practices-drupal-development`.

- `/does-not-exist-xyz` → 404 page, modern shell, no legacy CSS.
- Maintenance mode: `drush state:set system.maintenance_mode 1` renders the
  drupify maintenance shell with countdown (`data-deadline` from theme setting
  `launch_date`); cleared after test.
- Services page renders 5 real service cards; About section proof/team/counts
  populate; contact webform present.

### Regression found and fixed during comparison QA

The `/portfolio` route is served by the legacy `original_files` view
(`view.original_files.page_3`), not `view.portfolio.page`. A route gate in
`drupify_preprocess_page()` used the non-existent `view.portfolio.page`
route, so `drupify_portfolio` was never built and the portfolio page rendered
"0 published portfolio entries" with an empty grid (~2617px vs the ~4972px
reference). Fixed the gate to `view.original_files.page_3`; the page now
renders 8 entries, a featured project with image, and matches the reference
height (4988px).

## Next Cleanup Batch

Recommended next batch:

1. Audit legacy short-code pages and decide whether to keep them unpublished or remove/archive them.
2. Review old home-1/home-2/home-3 paragraph displays after deciding whether demo pages should remain.
3. Build an asset usage scanner before moving or deleting files from `sites/default/files`.

### View list pages styled to match references

`/team`, `/clients`, and `/blog` are view-driven routes whose view content
still rendered raw bootstrap_barrio `views-row` markup (huge unstyled
blobs). Added theme overrides in
`themes/custom/drupify/templates/views/`:

- `views-view-unformatted/fields--team-member---page.html.twig` — compact
  4-column member card grid (image, name, role, profile link).
- `views-view-unformatted/fields--client---page.html.twig` — client logo grid.
- `views-view-unformatted/fields--blog--page.html.twig` — 3-column article
  card grid (image, date, title link, read-article link).

Note the trailing underscore in the `team_member_`/`client_` view ids produces
a triple-dash template segment (e.g. `--team-member---page.html.twig`).

Heights after change: /team ~2940px (ref 2977, grid of 7), /clients ~2340px
(ref 2711, 8 logos), /blog ~3686px (ref 4105, 4 article cards). New CSS
appended to `css/drupify-pages.css`. All 17 QA routes pass (no overflow, no
broken images, no legacy CSS).
