# Vignelli Canon Design System

Apply Massimo Vignelli's design discipline — distilled from *The Vignelli Canon* and his subway/wayfinding work — to identity systems, design systems, editorial layouts, and transit-style navigation.

## When to use

- Designing or critiquing a visual artifact that should read as disciplined, timeless, and Swiss-modernist.
- Brand/identity systems, design systems, style guides, editorial/book/poster layouts.
- Transit/wayfinding signage, route diagrams, and clear hierarchical navigation.
- Any brief asking for grid-driven, grotesque-type, intellectually elegant design.

## Intangibles (decide before drawing)

1. **Semantics** — search for the meaning first. Design without semantics is shallow.
2. **Syntactics** — control the relationships: grid, typefaces, headline/text/image, page to page.
3. **Pragmatics** — it must be understood without explanation. Clarity of intent → clarity of result.
4. **Discipline** — no sloppiness. Quality is there or it is not.
5. **Appropriateness** — listen to what a thing wants to be.
6. **Ambiguity** — a plurality of meanings, used as spice.
7. **Design Is One** — master one discipline and you can design anything.
8. **Visual Power** — strength through scale and weight, never loudness.
9. **Intellectual Elegance** — elegance of the mind, not manners.
10. **Timelessness** — prefer primary shapes, primary colors, and typography beyond trends.
11. **Responsibility** — to self, client, and public.
12. **Equity** — refine long-lived marks; don't replace them for the sake of change.

## Tangibles (concrete rules)

### Grid
- The grid is the organization of information.
- Infinite grids exist; pick the one most appropriate for the problem.
- Too fine = empty page; too coarse = restrictive.
- **Tight outside margins** create tension; **wider margins** bring serenity.
- **Tight gutters** (~one line of type) so type and images snap to the same grid.
- Common specimen grids: 2×4, 5×4, 3×6, 6×6, 4×8 (columns × modules).
- Prefer DIN A-series proportions when possible.

### Typography
- **Six basic typefaces for a whole career:** Garamond, Bodoni, Century Expanded, Futura, Times, Helvetica. Also acceptable: Optima, Univers, Caslon, Baskerville.
- Type is objective organization, not self-expression. Differentiate with **space, weight, alignment** — not novelty faces.
- **Flush left by default.** Centered only for lapidary/invitation text. Justified is "fundamentally contrived" — avoid.
- **Two type sizes per page, maximum.** Heading ≈ 2× body (e.g., 10/20).
- Size/leading by column width: 8/9, 9/10, 10/11 ≤70mm; 12/13, 14/16 ≤140mm; 16/18, 18/20 larger.
- **Rulers:** 2pt major, 0.5–1pt minor; type hangs from the ruler.

### Scale, Texture, Color
- **Scale:** the most appropriate size in context — push it deliberately for power.
- **Texture:** light is the master of form and texture.
- **Color as signifier/identifier (chromotype), not pictorial.** Default to the primary palette: Red, Blue, Yellow.

### White space
- White space is the protagonist.
- "In a world where everybody screams, silence is noticeable."
- Don't fill the page.

### Layout, Sequence, Identity, Economy
- A publication is cinematic — design the sequence.
- If you see the layout, it is probably a bad layout.
- Balance identity vs. diversity: strong system, room to play.
- Standardization is an ethic; good design doesn't cost more than bad design.

## Transit & wayfinding

- Route/line diagrams use **45° and 90° angles only**.
- Each line has one color; each station is one dot/tick.
- Signage stack: line badge → direction → station name. One typeface, few weights, generous clear space.
- Maintain consistent vertical rhythm across signs; the grid makes the system scalable.

## Token generator

Use this starting point to lock in a Vignelli-consistent system:

```css
:root {
  --font-display: Helvetica, Arial, sans-serif;
  --font-body: Helvetica, Arial, sans-serif;
  --font-mono: 'SF Mono', Consolas, monospace;

  --size-body: 14px;
  --lh-body: 21px;          /* 1.5 × body, baseline-friendly */
  --size-display: 28px;     /* 2× body */
  --size-small: 12px;

  --color-black: #0d1117;
  --color-white: #f0f6fc;
  --color-red: #e4002b;
  --color-blue: #0055ff;
  --color-yellow: #f5c518;
  --color-muted: #8b949e;

  --grid-cols: 12;
  --gutter: 24px;
  --margin: 72px;
  --max-width: 1296px;
}
```

## Verification checklist

- [ ] Meaning/semantics are defined before any visual decisions.
- [ ] No more than two type sizes on a page.
- [ ] Text is flush-left, ragged-right.
- [ ] Colors are used as signifiers, not decoration.
- [ ] White space is treated as an active element.
- [ ] Navigation reads like wayfinding: one typeface, clear hierarchy, generous space.

## References

- Massimo Vignelli, *The Vignelli Canon*
- Vignelli Associates, NYC Subway diagram and signage standards (1972)
- National Park Service Unigrid system
