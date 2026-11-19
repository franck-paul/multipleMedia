# Changelog

## 8.1 — 2026-11-07

- Use Html/Form/Note rather than Para when it is applicable
- Enforces some verification before registering module

## 8.0.1 — 2026-08-10

- Fix insertion in HTML wysiwyg mode

## 8.0 — 2026-08-03

- Do not replace single quote in alternate texts and descriptions (see issue opened here : https://codeberg.org/Dotclear/dotclear/issues/168)
- Avoid using magic methods for user preferences and blog settings (2.39+)
- Use dotclear.ToolBar rather than old jsToolBar, DC 2.39+

## 7.7 — 2026-05-06

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 7.6 — 2026-04-04

- DA -&gt; DC

## 7.5.1 — 2026-01-21

- Misc
- Wait some time before closing current popup (Vivaldi seems to have some difficulties with the transition without that), should fix #9

## 7.5 — 2025-10-11

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 7.4 — 2025-09-22

- Use backend services rather than static calls

## 7.3 — 2025-09-15

- Use legacyEditor groups management

## 7.2 — 2025-09-15

- Add spacer around toolbar button (waiting for group management)

## 7.1 — 2025-09-11

- Don't provide editor button if not enough permissions to use media manager

## 7.0.1 — 2025-09-09

- Fix context checking for popup

## 7.0 — 2025-09-07

- DC 2.36 compliance

## 6.3 — 2025-06-05

- Add legacyEditor dark icon

## 6.2 — 2025-03-05

- Plugins adding buttons on legacyEditor/CKEditor should have at least 1010 priority 1005 is reserved for additional syntaxes to legacyEditor (as legacyMarkdown)

## 6.1 — 2025-01-28

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 6.0.1 — 2025-01-27

- Forgot a newline if link is required

## 6.0 — 2025-01-27

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.6 — 2025-01-27

- Use real alternate text rather than title

## 5.5.2 — 2024-11-11

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.5.1 — 2024-10-16

- Fix media path determination, fixes #6

## 5.5 — 2024-08-26

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.4.2 — 2024-08-26

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.4.1 — 2024-03-11

- Fix insertion in wiki mode

## 5.4 — 2023-12-17

- Use dialog rather than popup as js filename prefix, as some adsblock detector may use .php?*/popup as filter

## 5.3.2 — 2023-12-10

- Forgot this one

## 5.3.1 — 2023-12-09

- Fix labels (en)

## 5.3 — 2023-12-09

- Insertion d'image : - Ne pas utiliser l’attribut `title` dans `img` - Ne pas autoriser de lien avec un `alt` vide dans `img` - Le `title` du lien doit contenir "ouvrir le média" - Ne pas autoriser une légende avec une `alt` vide dans `img` - Ne pas dupliquer le `alt` dans la légende

## 5.2 — 2023-10-27

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.1 — 2023-10-25

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.0 — 2023-10-24

- 2.28 compliance

## 4.0 — 2023-08-25

- post commit
- Dotclear 2.27 compliance, PHP 8.1+

## 3.3.3 — 2023-06-28

- Fix media center alignment

## 3.3.2 — 2023-06-27

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 3.3.1 — 2023-06-27

- Add local static $init variable

## 3.3 — 2023-06-25

- Use dotclear.jsonServicesPost() rather than $.get(…)

## 3.2.1 — 2023-06-24

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 3.2 — 2023-06-23

- Cope with 2.27 media alignment classes (still compatible with 2.26)

## 3.1.1 — 2023-06-20

- Fix var collision

## 3.1 — 2023-06-20

- Use admin URLs

## 3.0.1 — 2023-05-31

- Adjust permission to open popup

## 3.0 — 2023-05-27

- Forgot this one
- Switch to namespace
- Prepare 2.26

## 2.2 — 2022-12-06

- Use minified version of js

## 2.1.1 — 2022-12-05

- Add settings info

## 2.1 — 2022-12-05

- Add container option, with optional class(es)

## 2.0 — 2022-11-30

- Dotclear 2.24 compliance

## 1.2.1 — 2022-08-14

- Usual oups :-)

## 1.2 — 2022-08-13

- Add CHANGELOG
- Use Template Literal

## 1.1 — 2022-01-15

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 1.0 — 2022-01-13

- Fix wysiwyg insertion
- Update details URL, fix default main branch
- First version, bug to fix in wysiwyg mode
- Initial commit
