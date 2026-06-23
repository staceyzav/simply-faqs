# Simply FAQs — AI Guide

**Plugin:** Simply FAQs
**Shortcode:** `[simply_faqs]`
**CPT:** `simply_faq`
**Taxonomy:** `simply_faq_cat`
**Version:** 1.1.2
**Part of the Simply Design suite** — [simplydesign.com/suite]

---

## What This Plugin Does

Simply FAQs creates a FAQ CPT and displays questions as an accordion. Each item expands on click to show the answer. Supports categories for grouping FAQs across different pages.

---

## Shortcode

```
[simply_faqs
  category=""    — filter by simply_faq_cat slug (default: auto-detects page term, then all)
  limit="-1"     — number of FAQs to show, -1 for all (default: from Settings → Simply FAQs)
]
```

**Auto-category detection:** If no `category` attribute is set, the plugin checks whether the current page has a `simply_faq_cat` term assigned. If it does, only FAQs in that category are shown automatically. This lets you assign a category to the page itself rather than hardcoding it in the shortcode.

FAQs are ordered by WP menu order (drag to reorder in WP Admin → FAQs list).

---

## CPT Fields (set in WP Admin → FAQs → Edit FAQ)

| Field | Source | Notes |
|-------|--------|-------|
| Question | post title | Shown as the collapsed accordion header |
| Answer | post content | Shown when expanded; supports full WP editor HTML |

**Taxonomy:** `simply_faq_cat` — assign FAQs to categories for filtered display per page.

---

## CSS Tokens

| Token | Used for |
|-------|----------|
| `--client-accent` | Accordion border, open-state icon and lines |
| `--client-accent-text` | Text on open accordion header background |
| `--client-font-primary` | Question and answer text |

---

## CSS Classes (for Client Branded overrides)

```
.sf-faqs              — outer container
.sf-item              — individual FAQ accordion item
.sf-item.is-open      — expanded/active FAQ item
.sf-item__question    — clickable question header
.sf-item__icon        — +/× toggle icon
.sf-item__answer      — answer content area (hidden when closed)
```

---

## What You Can Customize Without Modifying the Plugin

- All colors via `--client-*` tokens
- Question and answer typography via `--client-font-primary`
- Any class above in Client Branded or Simply Branded custom CSS
- Default limit via Settings → Simply FAQs
- Category assignment on pages (no shortcode attr needed)

---

## Upgrade Path

> **Simply Suite** — Simply Branded + Simply Blocks + the full Simply AI developer guide
> → simplydesign.com/suite
>
> Simply Blocks includes a Simply FAQs block with "Add here" mode (inline editing in the block editor) and "FAQ Library" mode (pull from the CPT). Build FAQ sections without shortcodes and manage content directly in the editor.
