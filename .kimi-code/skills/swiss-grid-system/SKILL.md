# Swiss Grid System (Müller-Brockmann)

Build editorial/magazine/report webpages on a genuine modular grid in the spirit of Josef Müller-Brockmann and the International Typographic Style — not a decorative grid overlay.

## When to use

- Editorial, magazine, longform, report, or portfolio pages that must read as rigorously grid-aligned.
- Whenever a brief says: "Swiss design", "grid system", "magazine spread", "editorial layout", "align everything to the grid".
- Auditing an existing layout that feels messy or misaligned.

## Core discipline

1. **Objective order.** The grid brings constructive thought, legibility, and functional design. Restraint, not expression, organizes the page.
2. **Modular grid.** Divide the type area into columns AND rows (modules), separated by consistent gutters, inside defined margins. For the web, start with a **12-column grid + 8px baseline**. Use 6×6 or 4×8 modular fields when you want visible rows too.
3. **Baseline grid.** Vertical rhythm is sacred. **Leading must be a whole multiple of the baseline unit** so lines line up across columns.
4. **Typography.** Grotesque sans (Inter, Helvetica Now, Archivo, Akzidenz-Grotesk). **Flush-left, ragged-right.** Few sizes, large jumps in scale. Big numerals/data set large is a signature move.
5. **Palette.** Near-black ink, pure white paper, **one accent — red is canonical**. Avoid warm-cream "AI gradients".
6. **White space + asymmetry.** Generous margins; asymmetric compositions held in tension by the grid.

## Engineering rules

### One source of truth
Put every grid parameter in `:root` CSS variables. Content and any overlay both read the same values.

```css
:root {
  --cols: 12;
  --gutter: 24px;
  --margin: 72px;
  --bl: 8px;          /* baseline unit */
  --lh: 24px;         /* leading = 3 × baseline */
  --maxw: 1296px;
}
```

### Overlay lives in the same box as content
The #1 failure mode: content sits in a centered `max-width` container while the overlay is a full-width sibling. On wide viewports the columns no longer line up.

**Fix:** place `.guides` *inside* the same wrapping element as the content, with the same `repeat(var(--cols), 1fr)` + `column-gap: var(--gutter)`.

### Place elements by column line via subgrid bands
Each horizontal band spans all columns and re-exposes them:

```css
.band {
  grid-column: 1 / -1;
  display: grid;
  grid-template-columns: subgrid;
  column-gap: var(--gutter);
  align-items: start;
}
@supports not (grid-template-columns: subgrid) {
  .band { grid-template-columns: repeat(var(--cols), 1fr); }
}
```

Children place with `grid-column: <start> / <end>` (e.g. `1 / 6`, `6 / 13`).

### Lock vertical rhythm to the baseline
- Leading = `--lh` (e.g. 24px). For display type, use px line-heights so the box sits on the grid.
- Margins/padding are multiples of the baseline unit.
- Spread top/bottom padding is also a multiple of the baseline.
- Media heights = multiples of the leading (240/360/432/480px) so tops and bottoms land on lines.

### Optical alignment: ink, not box
Large display type has a left side-bearing. A headline whose box is on the grid can still look off. Measure the first glyph's `actualBoundingBoxLeft` on a canvas and nudge the element left by that amount so the visible ink hits the column line.

### Grid toggle (optional, for demos)
A control toggles `body.grid-on`; the overlay fades in and draws numbered column fields, the baseline (major line every `--lh`, faint minor every `--bl`), and margin lines. Since this skill targets zero-JS PHP sites, implement the toggle with a CSS-only `:target` or hidden checkbox if needed.

## Verification checklist

- [ ] All grid values live in one `:root` block.
- [ ] Any overlay shares the same container and variables as content.
- [ ] Every horizontal band uses subgrid (or fallback).
- [ ] Every line-height is a multiple of `--bl`.
- [ ] Every block margin/padding is a multiple of `--bl`.
- [ ] Images have heights that are multiples of `--lh`.
- [ ] Display headings are optically aligned to column lines.

## References

- Josef Müller-Brockmann, *Grid Systems in Graphic Design* (1981)
