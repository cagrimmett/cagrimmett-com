---
name: cagrimmett-com
description: Chuck Grimmett's personal site. A quiet reading column in ET Book, paper and rust, sibling to the digital garden.
colors:
  paper: "#faf8f4"
  wash: "#efe9dd"
  ink: "#23201b"
  muted-ink: "#6e675b"
  rule: "#e3ddd1"
  rust: "#c0451d"
  sage: "#5d7052"
  paper-dark: "#171512"
  wash-dark: "#221f19"
  ink-dark: "#e9e3d6"
  muted-ink-dark: "#9c9384"
  rule-dark: "#322e26"
  rust-dark: "#f08a63"
  sage-dark: "#8fa383"
typography:
  display:
    fontFamily: "\"ET Book\", \"Iowan Old Style\", Palatino, Georgia, serif"
    fontSize: "2.8rem"
    fontWeight: 400
    lineHeight: 1.1
  headline:
    fontFamily: "\"ET Book\", \"Iowan Old Style\", Palatino, Georgia, serif"
    fontSize: "2.2rem"
    fontWeight: 400
    lineHeight: 1.15
  title:
    fontFamily: "\"ET Book\", \"Iowan Old Style\", Palatino, Georgia, serif"
    fontSize: "1.6rem"
    fontWeight: 400
    lineHeight: 1.2
  body:
    fontFamily: "\"ET Book\", \"Iowan Old Style\", Palatino, Georgia, serif"
    fontSize: "1.25rem"
    fontWeight: 400
    lineHeight: 1.5
  nav:
    fontFamily: "-apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif"
    fontSize: "0.9375rem"
    fontWeight: 400
    lineHeight: 1.4
  label:
    fontFamily: "-apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif"
    fontSize: "0.8125rem"
    fontWeight: 400
    lineHeight: 1.3
rounded:
  sm: "4px"
  md: "6px"
spacing:
  row: "0.32rem"
  mark: "0.85rem"
  block: "1.1rem"
  gutter: "1.5rem"
  column-gap: "4rem"
  section: "4.5rem"
components:
  post-row:
    textColor: "{colors.ink}"
    typography: "{typography.body}"
    padding: "0.32rem 0"
  post-row-hover:
    textColor: "{colors.rust}"
  mark:
    textColor: "{colors.ink}"
    padding: "0.85rem 0"
  month-label:
    textColor: "{colors.muted-ink}"
    typography: "{typography.label}"
  nav-link:
    textColor: "{colors.ink}"
    typography: "{typography.nav}"
    padding: "0.2rem 0"
  nav-link-current:
    textColor: "{colors.rust}"
  button:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.paper}"
    rounded: "{rounded.sm}"
    padding: "0.55rem 1rem"
  input:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink}"
    rounded: "{rounded.sm}"
    padding: "0.5rem 0.7rem"
  footer:
    backgroundColor: "{colors.wash}"
    textColor: "{colors.muted-ink}"
    typography: "{typography.label}"
---

# Design System: cagrimmett-com

## Overview

**Creative North Star: "The Field Notebook"**

This is one person's notebook, kept in good order: a single reading column of ET Book on warm paper, with a narrow margin on the left for the name and the way around. It shares its paper, ink and rust with the digital garden at notes.cagrimmett.com so the two read as volumes of the same set. Everything that is prose is set in the serif; everything that is bookkeeping (dates, categories, navigation, pagination) is set small in the system sans and kept out of the way.

The page is flat and quiet at rest. Personality comes from a handful of small hover responses that reward attention without asking for it: the leader between a title and its date ripples like a fishing line, the logo tips, the asterisk turns, a link's underline goes wavy. Nothing moves on its own.

Light and dark are the same notebook under different light. The scheme follows the reader's system setting; there is no toggle.

**Key Characteristics:**
- One 40rem reading column beside an 11rem sidebar; no cards, no grids of tiles.
- Serif for reading, system sans for metadata.
- Rust is the only accent for links and state. Sage appears only inside hover effects.
- Hairline rules and whitespace separate things. No shadows.
- Lists are one line per item, grouped under small uppercase time labels.

## Colors

Warm paper and ink with a single rust accent, mirrored in a warm near-black for dark mode.

### Primary
- **Rust** (`rust`, `rust-dark`): links, the current nav item, the current page number, hover on titles, the asterisk, text selection. It marks what is a link or what is "here", and nothing else.

### Secondary
- **Sage** (`sage`, `sage-dark`): the ripple in the post-row leader and the wavy underline on hovered links. It only ever appears in response to hover or focus.

### Neutral
- **Paper** (`paper`, `paper-dark`): the page.
- **Wash** (`wash`, `wash-dark`): the footer band, code blocks, empty image placeholders.
- **Ink** (`ink`, `ink-dark`): all reading text and titles.
- **Muted ink** (`muted-ink`, `muted-ink-dark`): dates, categories, labels, footer links, placeholders.
- **Rule** (`rule`, `rule-dark`): hairline dividers and input borders.

### Named Rules
**The One Accent Rule.** Rust is the only colour that means something. If a second colour seems needed for meaning, the answer is weight, size or position.

**The Preset Swap Rule.** Dark mode is implemented by redefining the `--wp--preset--color--*` variables under `prefers-color-scheme: dark`, so every block and every `has-*-color` class follows. Never hard-code a hex in a rule; use the tokens.

## Typography

**Display and body font:** ET Book (with Iowan Old Style, Palatino, Georgia), self-hosted in roman, italic and bold.
**Label font:** the system UI sans.

**Character:** A book face that is light on screen, so it runs large (20px body) and at regular weight even for headings. Hierarchy comes from size, never from bold.

### Hierarchy
- **Display** (400, 2.8rem, 1.1): the page title on the full-post blog page only, where it has to outrank post titles.
- **Headline** (400, 2.2rem, 1.15): page titles, single post titles, post titles in the blog stream.
- **Title** (400, 1.6rem, 1.2): section headings such as "Blog" and "Links I like" on the homepage, and year headings in the index.
- **Body** (400, 1.25rem, 1.5): all prose and list titles. The column caps the measure at 40rem.
- **Nav** (400, 0.9375rem): sidebar navigation.
- **Label** (400, 0.8125rem): dates, categories, footer links. Month labels add uppercase, 0.06em tracking and weight 500. Pagination is the one exception, at 1.0625rem so it is easy to hit.

### Named Rules
**The Two Voices Rule.** If a person wrote it, it is serif. If the system generated it (a date, a count, a category, a page number), it is sans and muted.

**The One Meta Size Rule.** All small metadata is 0.8125rem. Do not introduce 0.75rem or 0.875rem neighbours.

## Layout

A two-column shell, 62rem at most and centred: an 11rem sidebar and a reading column of up to 40rem, 4rem apart, with 1.5rem page gutters. The sidebar is sticky. Wide and full alignments are the same width as the column; nothing breaks out of it.

Below 720px the shell becomes a single column. The logo (40px) and name share a row and the navigation wraps beneath it. In post rows the leader and category are dropped so a wrapped title sits cleanly beside its date. Below 520px, date-column rows stack the date above the text.

Vertical rhythm: 1.1rem between blocks, 4.5rem between homepage sections, more space above a heading than below it. In the full-post stream, posts are separated by 8rem and a hairline, because they need to be distinguishable while scrolling fast.

The footer is a full-width wash band pinned to the bottom of short pages.

## Elevation & Depth

Flat. There are no shadows anywhere. Separation is done with hairline rules in the Rule colour, the Wash band of the footer, and whitespace.

**The No Shadow Rule.** If something seems to need lifting off the page, give it a rule or more space instead.

## Shapes

Square by default, with a small softening on things that are objects, not text: 6px on the logo and avatars, 4px on inputs, buttons, code blocks and images inside the likes and microblog streams. Borders are always 1px. The only drawn shapes are the eight-point asterisk before the homepage greeting and the sine-wave leader.

## Components

### Post row
The signature component. One line per post: title in body serif, a dotted leader that fills the gap, an optional category, and a right-aligned date in tabular figures. On hover or keyboard focus the title turns rust and the dotted leader is replaced by a sage wave that travels along the line. Rows are grouped under month labels; the all-posts index adds year headings and a jump list of years.

### Mark
The date-column row used for likes and microblog notes, taken from the garden's bookmarks: a 6rem date column in label type, then the content at 0.92em, with a hairline beneath each row. Links inside get a wavy sage underline on hover.

### Navigation
A vertical list in the sidebar in nav type, ink by default, rust on hover and for the current item. External items carry a small north-east arrow that nudges on hover. Wraps into rows on small screens; there is no hamburger menu.

### Buttons
- **Shape:** softly squared (4px).
- **Primary:** ink background, paper text, system sans at 0.875rem. There is only one kind.

### Inputs / Fields
- **Style:** paper background, 1px rule border, 4px radius, system sans.
- **Focus:** a 2px rust outline offset by 3px, shared with links and buttons. The caret is rust.

### Pagination
System sans at 1.0625rem with padded numbers. The current page is rust and semibold; the rest are muted and turn rust on hover.

### Footer
A wash band holding small muted link lists, social icons in muted ink that lift and turn rust on hover, and a borderless-looking search field.

### Hover touches
There are exactly four, and each is a response to hover or focus, eased with `cubic-bezier(0.16, 1, 0.3, 1)`: the leader ripple, the logo tilt (-8 degrees), the asterisk half-turn, and the wavy underline. All are disabled under `prefers-reduced-motion`.

## Do's and Don'ts

### Do:
- **Do** set anything a person wrote in ET Book and anything the system generated in the muted system sans.
- **Do** use the colour tokens so dark mode follows automatically.
- **Do** list things one per line under time labels before reaching for any other layout.
- **Do** keep motion as a response to hover or focus, and disable it for reduced motion.
- **Do** separate with hairlines and space.

### Don't:
- **Don't** use bold for headings; step the size instead.
- **Don't** add a second accent colour, or use sage outside hover effects.
- **Don't** add shadows, cards, gradients or background fills other than the footer wash.
- **Don't** add entrance animations or anything that moves without being touched.
- **Don't** add a fifth hover touch without removing one.
- **Don't** let content break out of the 40rem column.
