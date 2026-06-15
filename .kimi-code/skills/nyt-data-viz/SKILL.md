# NYT Data Visualization

Produce New York Times / Upshot–grade charts and data-driven pages: the typography, color restraint, chart-type judgment, and annotation discipline of the NYT graphics desk.

## When to use

- Generating charts, dashboards, reports, or data-driven webpages.
- Any chart that should read as editorial, trustworthy, and crafted rather than a default library output.
- Single charts, multi-panel dashboards, and scrollable data stories.

## The five core rules

### 1. Color
- One hero accent for the series that matters; everything else grey.
- Monotonic-luminance sequential ramps only — never rainbow/jet/HSV-equidistant.
- Diverging palettes only when there is a meaningful zero.
- Categorical color caps at ~7 hues; beyond that, small-multiple by category.
- Default hero grey: `#888888`. Default accent: `#0055ff` or `#e4002b`.

### 2. Typography
- **Display serif** for headlines (Playfair Display, Source Serif 4).
- **Sans** for labels and annotations (Libre Franklin, Inter, Helvetica).
- **Source Serif 4** for body copy.
- `tabular-nums` on ALL numeric labels so digits don't jitter.
- Axis labels should be humanized ("Jan. 2024", not "2024-01").

### 3. Chart choice
- **Line first** for time series.
- **Bar second** for categories; bars start at zero.
- **Never pie.**
- **Never dual y-axes.**
- Past ~5 series → use small multiples.

### 4. Annotation
- **Declarative headline** — a sentence, not a label.
- **Subtitle** names the unit and timeframe.
- **Source line** at the bottom.
- Label series directly at endpoints; avoid legends.
- Annotate inflections and outliers in place.

### 5. Archie Tse rule
Crucial information must be visible without interaction. Mobile-test at 375px first.

## Palette guide

```python
# NYT-style palette starter
COLORS = {
    "hero": "#e4002b",      # the story
    "secondary": "#0055ff", # comparison series
    "grey": "#888888",      # context
    "bg": "#ffffff",
    "text": "#121212",
    "muted": "#666666",
    "line": "#dddddd",
    "sequential": ["#f0f0f0", "#c0c0c0", "#888888", "#444444"],
}
```

## Typography starter

```css
.nyt-chart {
  font-family: 'Libre Franklin', Inter, Helvetica, Arial, sans-serif;
}
.nyt-chart .headline {
  font-family: 'Playfair Display', 'Source Serif 4', Georgia, serif;
  font-size: 22px;
  line-height: 1.2;
  margin-bottom: 6px;
}
.nyt-chart .subtitle,
.nyt-chart .source {
  font-size: 12px;
  color: #666;
}
.nyt-chart .axis text,
.nyt-chart .label {
  font-variant-numeric: tabular-nums;
  font-size: 11px;
  fill: #666;
}
```

## Static chart scaffold (Observable Plot / D3 / SVG)

```html
<figure class="nyt-chart" role="img" aria-labelledby="chart-title">
  <figcaption>
    <div class="headline" id="chart-title">Headline sentence here</div>
    <div class="subtitle">Unit, timeframe, and one-line context</div>
  </figcaption>
  <div class="chart-body"><!-- SVG or Plot output --></div>
  <div class="source">Source: Name of source</div>
</figure>
```

## Interaction rules (for D3 dashboards)

1. **Hit-layer coordinate space.** Append the transparent hover `<rect>` to the SAME margin-translated `<g>` as the marks, not the SVG root.
2. **Proximity, not pixels.** Use a Delaunay triangulation to snap to the nearest mark instead of forcing users to hover tiny dots.
3. **Gliding, anchored tooltip.** Anchor the tooltip to the hovered mark's bounding box and CSS-transition `left/top` so it slides, not teleports.
4. **Mobile first.** Test the smallest width before adding desktop-only flourishes.

## Verification checklist

- [ ] Only one hero color for the most important series.
- [ ] Sequential/diverging color choice matches the data semantics.
- [ ] Numeric labels use `tabular-nums`.
- [ ] Headline is a declarative sentence.
- [ ] Source line is present.
- [ ] Bars start at zero; no dual y-axes; no pie charts.
- [ ] Key information is visible without hover on mobile.

## References

- The New York Times Graphics Desk / Upshot style examples
- Archie Tse, "Crucial info visible without interaction"
- Steve Haroz, connected scatterplot research
