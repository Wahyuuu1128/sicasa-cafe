# SICASA Cafe --- Design System & UI Guidelines

> **Project:** Sicasa Cafe QR-Order & POS System\
> **Client:** Sicasa Cafe Padang\
> **Document:** `design.md`\
> **Methodology:** Waterfall\
> **Product type:** Responsive web application / PWA-ready experience\
> **Primary modules:** Customer QR Ordering, Cashier POS, Owner
> Analytics Dashboard\
> **Visual direction:** Modern Editorial Utility + Playful Minimalism +
> Swiss Modernism

------------------------------------------------------------------------

## 1. Product Vision

SICASA is an integrated cafe operations system designed to streamline
customer ordering, cashier verification, kitchen preparation, and
owner-level sales monitoring. The experience should feel like one
coherent SICASA product while adapting its visual language to the needs
of each role.

The design must balance: - **Distinctive brand identity:** use SICASA's
vivid lime and deep blue identity, with organic forms inspired by the
logo. - **Operational clarity:** keep important actions, order states,
prices, and totals easy to scan. - **Fast interaction:** minimize
unnecessary steps for customers and staff, especially during peak hours
(19.00--23.00 WIB). - **Responsive access:** customer ordering is
mobile-first; POS and owner analytics support desktop and tablet
layouts. - **Accessible contrast:** vivid brand colors are accents and
surfaces, not a substitute for readable text.

### Design principles

1.  **Editorial for discovery:** use expressive typography, generous
    spacing, asymmetric image compositions, and strong visual hierarchy
    on the landing page.
2.  **Playful but purposeful:** use rounded menu cards, friendly
    microcopy, and restrained organic shapes on QR ordering.
3.  **Utility first:** POS and operational views prioritize legibility,
    speed, and predictable placement over decoration.
4.  **One brand, different density:** keep color, typography, icons, and
    component language consistent while allowing each module its own
    layout density.
5.  **Visible system status:** orders, payments, table availability, and
    stock states must be unmistakable.
6.  **No decorative friction:** avoid animations or visual effects that
    delay ordering, verification, or printing.

------------------------------------------------------------------------

## 2. Brand Identity

### 2.1 Logo character

The SICASA logo uses a vivid lime background, deep blue organic shapes,
and a compact wordmark. The rounded, abstract forms communicate a
youthful, informal, creative cafe personality.

Use the logo as a brand signature. Do not stretch, rotate, redraw, or
add effects to the logo. Maintain clear space around it and ensure it
remains legible on both light and dark backgrounds.

### 2.2 Color palette

The following values are a proposed working palette sampled visually
from the supplied logo direction. Confirm exact brand values from the
original vector/source artwork before production.

  -----------------------------------------------------------------------
  Token                   Hex                     Purpose
  ----------------------- ----------------------- -----------------------
  `brand-lime`            `#A8FF00`               Primary brand accent,
                                                  selected states, key
                                                  highlights

  `brand-blue`            `#182B87`               Brand signature,
                                                  primary dark accent,
                                                  links on light surfaces

  `ink`                   `#17191C`               Main text, headings,
                                                  high-contrast interface
                                                  elements

  `paper`                 `#F7F7F2`               Editorial page
                                                  background

  `surface`               `#FFFFFF`               Cards, menus, forms,
                                                  dashboard panels

  `surface-muted`         `#F0F1EA`               Secondary surfaces,
                                                  input backgrounds

  `border`                `#E2E4DC`               Dividers, card borders,
                                                  input outlines

  `text-secondary`        `#62675F`               Supporting labels and
                                                  descriptions

  `success`               `#237A45`               Completed/available
                                                  states where
                                                  appropriate

  `warning`               `#A65B00`               Attention-needed states

  `danger`                `#B42318`               Failed, cancelled, or
                                                  unavailable states

  `info`                  `#2457B8`               Informational state and
                                                  neutral process updates

  `navy-surface`          `#101B54`               Dark brand surface for
                                                  selected promotional
                                                  moments
  -----------------------------------------------------------------------

#### Color usage rules

-   Use `paper` or white as the default background for most application
    screens.
-   Use `brand-lime` in deliberate areas: primary CTA fills, active
    filters, small highlights, selected navigation states, and brand-led
    hero elements.
-   Use `brand-blue` for logo placement, dark brand blocks, links, and
    selected iconography. For text on lime, verify contrast at the final
    font size and weight; prefer `ink` or a sufficiently dark blue.
-   Do not set long paragraphs in lime or low-contrast blue.
-   Use semantic colors consistently. Do not use green to mean "paid" in
    one screen and "available" in another without a clear label.
-   Never communicate status through color alone. Pair status colors
    with text and/or an icon.
-   Keep lime as an accent rather than flooding dense POS and analytics
    screens with it.
-   Dark surfaces are optional brand moments, not the default for every
    page.

### 2.3 Typography

**Recommended type pairing** - **Display / headings:** Space Grotesk -
**Body / UI / tables:** Inter - **Fallback:** system sans-serif

  Role              Typeface          Suggested weight   Desktop size   Mobile size
  ----------------- --------------- ------------------ -------------- -------------
  Hero display      Space Grotesk             600--700      64--88 px     40--52 px
  Page title        Space Grotesk             600--700      36--48 px     28--36 px
  Section title     Space Grotesk                  600      24--32 px     22--26 px
  Card title        Inter                          600      16--20 px     16--18 px
  Body              Inter                          400      15--17 px     14--16 px
  Supporting text   Inter                          400      13--14 px     12--14 px
  Label / eyebrow   Inter                          600      11--12 px     11--12 px
  Button            Inter                          600      14--16 px     14--16 px
  KPI value         Space Grotesk             600--700      30--40 px     26--32 px

Typography rules: - Use sentence case for most interface labels. - Use
uppercase sparingly for small editorial eyebrow labels, not for
paragraphs. - Use tabular numerals for prices, quantities, times, and
analytics values where supported. - Avoid using more than two type
families. - Maintain comfortable line height: 1.45--1.65 for body copy
and 1.05--1.2 for display headings. - Do not use oversized editorial
typography inside dense POS tables or order queues.

### 2.4 Shape language

-   Landing page: mix rectangular editorial grids with selected rounded
    image frames and organic brand shapes.
-   QR ordering: rounded cards and controls, friendly but not
    excessively bubbly.
-   POS: mostly 8--12 px corner radii, clear rectangular panels, and
    compact controls.
-   Owner dashboard: 12--16 px card radii with restrained shadows or
    borders.
-   Avoid applying extreme pill shapes to every component. Reserve pills
    for tags, filters, and compact status labels.

### 2.5 Iconography

Use a consistent outline icon set such as Lucide. Keep icons simple,
recognizable, and consistent in stroke weight.

-   Pair icons with text for critical actions (payment, print, cancel,
    complete).
-   Use icon-only buttons only for familiar actions and always provide
    an accessible label/tooltip.
-   Do not use emoji as interface icons.
-   Use the logo's organic forms as occasional decorative motifs, not as
    substitutes for functional icons.

------------------------------------------------------------------------

## 3. Global Experience & Navigation

### 3.1 User roles

  -----------------------------------------------------------------------
  Role              Main device       Primary goal      Access
  ----------------- ----------------- ----------------- -----------------
  Customer          Smartphone        Browse menu,      QR table link; no
                                      customize, order, app installation
                                      track status      

  Cashier / Staff   Desktop or tablet Receive orders,   Authenticated POS
                                      verify payment,   
                                      manage tables and 
                                      menu              
                                      availability,     
                                      print receipts    

  Owner             Desktop / tablet  Monitor sales,    Authenticated
                                      menu performance, dashboard
                                      and operating     
                                      patterns          
  -----------------------------------------------------------------------

The PRD describes three multi-role staff and two kitchen staff. The
interface should support role-appropriate access and clear handoff
between cashier and kitchen, while exact permissions are defined during
implementation.

### 3.2 Navigation patterns

**Customer** - Mobile top bar: SICASA logo, table indicator, cart
shortcut. - Category navigation: horizontally scrollable or sticky
chips. - Cart access: persistent bottom action when cart has items. -
Checkout: focused, single-column flow with clear back navigation. -
Order tracking: simple status timeline and order reference.

**POS** - Desktop: left navigation rail/sidebar, central operational
workspace, optional right-side order detail panel. - Tablet: collapsible
sidebar or compact navigation. - Core sections: Overview/Orders, Tables,
Menu & Stock, Transaction History, Settings (only if included in
approved scope). - Keep incoming orders and action buttons accessible
without excessive scrolling.

**Owner** - Desktop: persistent sidebar with Dashboard,
Sales/Transactions, Menu Analytics, and Settings (if included). -
Tablet: collapsible navigation. - Use date-range filters near the top of
relevant analytics pages. - Clearly distinguish live/current data from
selected historical date ranges.

### 3.3 Responsive breakpoints

Use project-standard breakpoints, adjustable to the implementation
framework: - **Small mobile:** below 360 px --- single column, compact
spacing, no horizontal overflow. - **Mobile:** 360--767 px ---
customer-first layouts; stacked content. - **Tablet:** 768--1023 px ---
two-column layouts where useful; compact POS navigation. - **Desktop:**
1024--1439 px --- full operational layout. - **Wide desktop:** 1440 px
and above --- use a readable max-width; do not stretch text and tables
edge-to-edge.

Responsive rules: - Customer ordering is designed mobile-first and must
remain usable on narrow devices. - POS touch targets should be
comfortable for touch devices (target approximately 44--48 px
minimum). - Tables may switch to stacked cards on narrow widths;
preserve key values and actions. - Dashboard charts should resize and
retain labels/tooltips without clipping. - Avoid fixed-width page
containers and horizontal scrolling except for intentional chip rows or
data tables with clear affordance.

------------------------------------------------------------------------

## 4. Design Direction by Module

### 4.1 Landing Page --- Modern Editorial Utility

**Purpose:** introduce SICASA, showcase the cafe/menu, and direct
visitors toward ordering or visiting.

Visual approach: - Editorial composition with bold headline and
supporting text. - Large, high-quality food/drink photography. -
Asymmetric grid or split hero layout; avoid generic centered SaaS hero
patterns. - Lime highlights and deep-blue brand elements used in
controlled blocks. - Spacious sections with clear rhythm and minimal
ornament. - Organic logo-inspired shapes may appear as a subtle
background motif.

Suggested page sections: 1. **Header:** logo, Menu, About/Our Space,
Location, and primary "Explore Menu" or "Order Now" CTA. Keep navigation
short. 2. **Hero:** strong editorial headline, concise description,
prominent menu CTA, and hero photography. 3. **Featured menu:** selected
drinks/food with image, name, short description, and price where
approved. 4. **The SICASA experience:** cafe atmosphere, space, or
service highlights using photography and short copy. 5. **Visit us:**
location details, operating hours (content to be supplied by client),
map/link if available. 6. **Footer:** logo, navigation, contact/social
links, and essential cafe information.

Interaction: - Header CTA scrolls to featured menu or opens the
ordering/menu destination. - Menu cards may open details if a public
menu page is supported. - Use subtle hover/focus feedback; do not hide
essential information behind hover.

### 4.2 Customer QR Ordering --- Playful Minimalism

**Purpose:** allow a customer at a table to order without installing a
native application.

Visual approach: - Mobile-first, bright, approachable, and easy to
scan. - White/paper surfaces with lime highlights and deep-blue
details. - Rounded product cards, clear imagery, compact category chips,
and large action controls. - Friendly visual language without clutter or
unnecessary decoration.

#### Customer screens

**A. QR Entry / Table Confirmation** - SICASA logo and short welcome. -
Detected table number displayed prominently. - If QR data is invalid or
table cannot be resolved, show a clear recovery message and staff
assistance instruction. - Continue action only after table context is
valid. - Do not expose internal table identifiers in customer-facing
copy.

**B. Customer Name** - Name field with clear label and example
placeholder. - Explain briefly that the name helps staff identify the
order. - Continue button disabled only when required data is invalid. -
Preserve entered data when navigating back.

**C. Menu Catalog** - Top bar with logo, table number, and cart. -
Search field if the menu volume warrants it. - Category chips (e.g.,
Coffee, Non-Coffee, Food; actual categories supplied by cafe). - Product
card includes image, name, short description if available, price, and
availability. - Unavailable items are visibly marked and cannot be
added. - Keep product imagery consistent in crop ratio and quality.

**D. Product Detail / Customization** - Product image, name, price,
description. - Customization controls such as Hot/Cold where
applicable. - Required options clearly marked; optional add-ons
separated. - Quantity selector and add-to-cart CTA. - Display price
changes caused by options before adding to cart. - Do not assume every
product has the same customization options.

**E. Cart** - List selected products, options, quantity, and line
totals. - Allow edit and remove actions. - Show subtotal and any other
approved charges only; do not invent tax/service fees. - Empty cart
state should guide the user back to the menu. - Persistent checkout CTA
with order total.

**F. Checkout** - Confirm table number, customer name, items,
quantities, options, and total. - Payment method selection: Cash or
QRIS, reflecting the Pay-First verification process. - Explain the
actual payment instruction for each method, supplied and approved by the
cafe. - Explicit final action such as "Place order" / "Send order". -
Prevent duplicate submission and show loading/confirmation state. -
State clearly that order processing follows cashier payment
verification.

**G. Order Status** - Show order reference, table, order summary, and
current status. - Status stages from PRD: Waiting for verification,
Processing, Completed. - If needed, show payment state separately from
preparation state to avoid ambiguity. - Provide a refresh/live-update
state and a last-updated indicator if real-time updates are not
available. - Avoid promising exact preparation time unless supported by
operational data.

Customer status language: - **Waiting for verification:** "Pesanan
diterima. Kasir sedang memverifikasi pembayaran." - **Processing:**
"Pembayaran terverifikasi. Pesanan sedang disiapkan." - **Completed:**
"Pesanan selesai. Silakan ikuti arahan staf." - Cancellation, rejection,
or payment mismatch states must be defined with the cafe before release.

#### Customer ordering interaction rules

-   Keep the main path linear: QR → name → menu → product options → cart
    → checkout → status.
-   Show table context throughout the ordering journey.
-   Keep cart contents when the customer returns to the menu.
-   Use clear feedback after add-to-cart.
-   Confirm before destructive actions such as clearing the cart.
-   Make order submission idempotent or otherwise protected against
    accidental repeated taps.
-   Display currency consistently in Indonesian Rupiah (Rp), using a
    consistent formatting convention.
-   Ensure status changes are communicated through text and visual cues.

### 4.3 Cashier POS --- Swiss Modernism / Utility-First

**Purpose:** centralize customer orders, payment verification, table
state, menu availability, and receipt printing.

Visual approach: - Neutral, high-contrast workspace. - Compact but
breathable information density. - Clear hierarchy for incoming orders
and orders requiring action. - Lime is used for primary action emphasis,
not large decorative backgrounds. - Deep blue supports navigation and
brand recognition.

#### POS screens

**A. Login** - SICASA logo and system name. - Email/username and
password fields (final authentication identifier to be confirmed). -
Show/hide password control. - Clear validation and error messages. - No
unnecessary marketing content. - Secure session behavior is an
implementation requirement, not a visual assumption.

**B. POS Overview / Incoming Orders** - Page title and service status. -
Summary counters: waiting verification, processing, completed
(period/context must be explicit). - Incoming order queue sorted by a
defined operational rule (e.g., oldest first); confirm final rule with
client. - Each order card/row shows order reference, time, table,
customer name, item summary, total, and payment method. - Visually
distinguish orders requiring verification. - New-order alert should be
noticeable but not disruptive; do not rely on sound alone. - Provide
empty, loading, connection-lost, and error states.

**C. Order Detail & Payment Verification** - Full item list,
customization, quantities, total, table, customer name, order time, and
payment method. - Cash: cashier confirms physical payment received. -
QRIS: cashier manually confirms payment based on the approved
verification procedure. - Explicit "Verify payment" action with a
confirmation step if the action is irreversible. - Payment state and
kitchen/preparation state must be separate fields where applicable. -
Record cashier identity and verification timestamp in transaction data
if supported by backend requirements. - If payment cannot be verified,
keep the order in a clear pending/exception state and show the next
staff action. - Do not auto-mark payment as successful merely because
the customer selected QRIS.

**D. Receipt Printing** - Trigger printing after the payment
verification and transaction-recording steps succeed, as defined by the
backend workflow. - Support two receipt formats: - **Customer receipt:**
cafe identity, order reference, date/time, table, item/options,
quantities, totals, payment method/status, and concise footer. -
**Kitchen receipt:** order reference, table, time, item/options,
quantities, and preparation notes if available. Avoid unnecessary
payment details. - Show printer readiness, printing, success, and
failure states. - If printing fails, preserve the transaction and
provide a controlled retry action. - Avoid duplicate receipt creation or
duplicate transaction recording when retrying a print. - Hardware
behavior (USB/LAN/Bluetooth, ESC/POS, browser/OS constraints) must be
validated in a technical spike; UI must not imply unsupported direct
browser printing.

**E. Tables** - Visual table overview for 26 tables, with a
representation of table number and state. - The cafe has 79 seats total;
show seat capacity only where it is operationally useful and confirmed
per table. - Table states should be clearly labeled, e.g., Available,
Ordering, Occupied/Preparing, Ready/Completed, or Needs attention. Final
state model must be approved because the PRD only specifies table status
management. - Use both labels and colors/icons. - Selecting a table
opens its active order/context. - Define when a table becomes available
again (e.g., staff closes the visit/order); do not infer this solely
from order completion.

**F. Menu & Stock Availability** - Menu list with product
image/thumbnail, name, category, price, and availability. - Quick
availability toggle: Available / Sold out. - Confirmation or immediate
undo for accidental stock toggles. - Make stock state changes visible to
customer catalog promptly. - Distinguish menu availability toggle from
inventory quantity tracking; the PRD specifies availability, not full
ingredient inventory. - Include search/filter if the menu list is large.

**G. Transaction History** - Search by order reference and filter by
date, payment method, or status where approved. - Show totals and
transaction details. - Restrict access according to agreed staff
permissions. - Avoid adding accounting, payroll, or advanced finance
features outside scope.

#### POS operational states

-   New order
-   Waiting for payment verification
-   Payment verified / transaction recorded
-   Sent to kitchen / processing
-   Completed
-   Verification exception / payment mismatch
-   Print pending / print failed
-   Cancelled or rejected (policy and permissions to be confirmed)

The exact state transitions and permitted actions must be agreed with
the client and encoded consistently across UI, backend, and analytics.

### 4.4 Owner Dashboard --- Editorial + Swiss Utility

**Purpose:** provide an understandable view of sales performance,
transaction volume, busy periods, and menu performance.

Visual approach: - Clean analytical layout with neutral background and
clear chart labeling. - Editorial page title and short context line. -
KPI cards with large tabular values. - Lime and blue reserved for chart
series and selected states, with accessible contrast. - Avoid excessive
gradients, 3D effects, decorative charts, and visual noise.

#### Owner screens

**A. Dashboard Overview** - Header: "Dashboard" with selected reporting
period. - Date filter: today, this week, this month, custom range if
supported. - KPI cards: - Gross sales/omzet (definition must be agreed;
e.g., based on verified transactions). - Number of transactions. -
Average transaction value. - Best-selling menu item. - Sales trend chart
by hour/day based on selected period. - Peak hours visualization with
explicit definition (e.g., order count or sales volume by hour). -
Best-performing and low-performing menu summaries, with the measurement
basis stated. - Recent transactions list with order reference, time,
table, amount, and status. - Last-updated timestamp and clear empty-data
state.

**B. Sales Analytics** - Sales by day/week/month based on available
data. - Transaction count and average transaction value. - Filters must
update all relevant metrics consistently. - Clarify whether
cancelled/refunded/exception transactions are excluded; define before
implementation.

**C. Peak Hours** - Hourly order count and/or sales amount. - State the
selected date range and measure. - Use local cafe time (WIB)
consistently. - Avoid calling a period "peak" unless the data shows it
has the highest value within the displayed comparison window.

**D. Menu Performance** - Best-performing menu by an explicitly selected
measure (quantity sold or sales amount). - Low-performing menu by the
same stated measure and a defined period. - Show item name, quantity,
sales contribution, and trend only where data exists. - Avoid treating a
low-selling item as objectively poor; present it as a data observation
for the selected period. - Provide an empty or insufficient-data state
when the period has too few transactions.

**E. Transaction Detail** - Read-only transaction detail for owner,
subject to role permissions. - Display payment verification and order
timestamps where captured. - Link detail to the source transaction, not
to an editable accounting ledger unless separately scoped.

#### Analytics visualization rules

-   Every chart needs a title, unit, time range, and readable
    axes/legend.
-   Use bar charts for comparisons and line charts for trends when
    suitable.
-   Avoid truncated axes that exaggerate small differences; disclose any
    non-zero baseline where used.
-   Provide a table or textual equivalent for essential chart values.
-   Use consistent currency formatting and WIB timestamps.
-   Define every metric in a tooltip, info label, or supporting text.
-   Do not label dashboard figures "real-time" unless the system
    actually updates in real time. If updates are periodic, state the
    refresh interval or last-updated time.

------------------------------------------------------------------------

## 5. Core User Flows

### 5.1 Customer QR ordering

1.  Customer scans the table QR code.
2.  System resolves the table context and opens the mobile web ordering
    page.
3.  Customer enters a name.
4.  Customer browses categories and products.
5.  Customer selects a product and required options (e.g., Hot/Cold
    where applicable).
6.  Customer adds items to the cart and can edit quantity/options.
7.  Customer reviews cart and proceeds to checkout.
8.  Customer selects Cash or QRIS and submits the order.
9.  System creates an order with a pending verification state and shows
    an order reference.
10. Cashier receives the order in POS and verifies the actual payment.
11. After successful verification, the transaction is recorded and
    receipt printing is triggered according to the agreed sequence.
12. Kitchen prepares the order; customer sees status updates.
13. Staff marks the order completed and manages table availability
    according to the agreed operational rules.

**Key UX requirement:** never communicate that payment is verified
before the cashier confirms it.

### 5.2 Cashier verification and fulfillment

1.  Cashier logs in.
2.  Incoming orders appear in the queue.
3.  Cashier opens an order and checks its contents and payment method.
4.  Cashier verifies cash received or checks QRIS payment using the
    cafe's approved procedure.
5.  Cashier confirms verification.
6.  System records the transaction and updates order/payment state.
7.  System attempts to print customer and kitchen receipts.
8.  If printing succeeds, show confirmation; if it fails, show retry
    guidance without duplicating the transaction.
9.  Kitchen prepares the order.
10. Staff updates preparation/completion status and table state as
    permitted.

### 5.3 Owner analytics

1.  Owner logs in.
2.  Dashboard loads metrics for a clearly defined default period.
3.  Owner changes the date range or analytics filter.
4.  KPI cards, charts, menu performance, and transaction list update to
    the same selected period.
5.  Owner opens a transaction or menu performance detail if available.
6.  Owner uses the displayed information for operational review.

------------------------------------------------------------------------

## 6. Information Architecture

``` text
SICASA
├── Public Landing Page
│   ├── Home
│   ├── Featured Menu
│   ├── About / Experience
│   └── Location / Contact
│
├── Customer QR Ordering
│   ├── QR Entry / Table Context
│   ├── Customer Name
│   ├── Menu Catalog
│   ├── Product Detail / Customization
│   ├── Cart
│   ├── Checkout
│   └── Order Status
│
├── POS (Authenticated)
│   ├── Login
│   ├── Incoming Orders / Overview
│   ├── Order Detail / Payment Verification
│   ├── Receipt Printing State
│   ├── Table Management
│   ├── Menu & Availability
│   └── Transaction History
│
└── Owner Dashboard (Authenticated)
    ├── Login
    ├── Dashboard Overview
    ├── Sales Analytics
    ├── Peak Hours
    ├── Menu Performance
    └── Transaction Detail
```

The landing page, customer ordering, POS, and owner dashboard should
share the same design tokens but may use separate route groups and
layouts.

------------------------------------------------------------------------

## 7. Component Guidelines

### 7.1 Buttons

-   **Primary:** lime fill with dark text, used for the main action on a
    screen.
-   **Secondary:** white/paper fill with border and dark text.
-   **Tertiary / text:** low-emphasis action for navigation or
    non-critical alternatives.
-   **Danger:** semantic red for destructive or cancellation actions
    only.
-   **Disabled:** visibly disabled while retaining readable text.

Button requirements: - Use clear verb-led labels: "Add to cart",
"Continue", "Verify payment", "Print receipt". - Avoid ambiguous labels
such as "OK" for important actions. - Provide hover, focus-visible,
pressed, loading, and disabled states. - Prevent repeat submissions
while an action is processing.

### 7.2 Cards

-   Customer menu cards: image-led, product name, price, availability,
    add/select action.
-   POS order cards: operational details first; status and primary
    action visible without opening where possible.
-   Owner KPI cards: metric name, value, period, and optional comparison
    only if a valid baseline exists.
-   Use borders and spacing before strong shadows. Shadows should be
    subtle and consistent.

### 7.3 Forms and inputs

-   Always show persistent labels; placeholders are examples, not
    labels.
-   Validate close to the field and provide actionable error text.
-   Preserve user-entered data across non-destructive navigation.
-   Mark required fields clearly.
-   Ensure inputs and dropdowns are touch-friendly on mobile.
-   For payment and order confirmation, show a review summary before
    final submission.

### 7.4 Status badges

Use text labels and semantic icon/color combinations. Example mapping
(final colors can be tuned for contrast):

  Status                 Meaning                   Visual treatment
  ---------------------- ------------------------- ----------------------------
  Waiting verification   Cashier action required   Amber + clock icon
  Verified               Payment confirmed         Green + check icon
  Processing             Kitchen preparing         Blue + cooking/loader icon
  Completed              Fulfillment complete      Green + check-circle icon
  Sold out               Menu item unavailable     Neutral/red-muted + label
  Print failed           Receipt not printed       Red + printer warning icon
  Cancelled              Order stopped             Neutral + ban icon

Do not use the same status label for payment and kitchen progress. Where
both matter, display two distinct status fields.

### 7.5 Tables and data lists

-   Keep column headers visible and clear.
-   Right-align numeric totals.
-   Use consistent date/time and currency formatting.
-   Provide search, sorting, and filters only where they help the task.
-   On mobile, convert rows to cards or allow deliberate horizontal
    scrolling with a visible cue.
-   Provide loading skeletons, empty states, and error recovery.

### 7.6 Charts

-   Use a restrained palette and clear labels.
-   Maintain consistent meaning for colors across analytics screens.
-   Include tooltips or accessible summaries.
-   Do not rely only on color to distinguish series.
-   Show no-data and insufficient-data states without fabricated values.

### 7.7 Navigation

-   Highlight the current section clearly.
-   Use concise labels and consistent icon placement.
-   Avoid nested navigation deeper than necessary.
-   Keep customer ordering navigation lighter than authenticated
    operational navigation.

### 7.8 Notifications and feedback

-   Use toast/snackbar for brief success or recoverable feedback.
-   Use inline alerts for persistent errors or important instructions.
-   Use confirmation dialogs for consequential actions such as verifying
    payment, cancelling an order, or clearing a cart.
-   Incoming order notifications must not block the cashier's current
    task.
-   Provide non-visual feedback for accessibility; do not rely on sound
    alone.

------------------------------------------------------------------------

## 8. Layout, Spacing & Elevation

Use a consistent spacing scale based on 4 px: - `space-1`: 4 px -
`space-2`: 8 px - `space-3`: 12 px - `space-4`: 16 px - `space-5`: 20
px - `space-6`: 24 px - `space-8`: 32 px - `space-10`: 40 px -
`space-12`: 48 px - `space-16`: 64 px - `space-20`: 80 px - `space-24`:
96 px

Layout guidance: - Landing page: generous vertical rhythm (64--112 px
between major desktop sections; 40--72 px on mobile). - Customer
ordering: compact, task-oriented spacing (16--24 px between major
blocks). - POS: compact operational spacing (12--24 px between
panels). - Dashboard: 20--28 px card gaps depending on viewport. -
Content max-width: approximately 1200--1360 px for editorial/analytics
pages; narrower reading widths for forms and checkout. - Use consistent
alignment across page title, filters, cards, and charts.

Elevation: - Prefer border-based separation for most panels. - Use low,
soft shadows only for floating elements, menus, dialogs, and selected
cards. - Avoid strong shadows, glassmorphism, excessive gradients, and
layered effects that reduce legibility.

------------------------------------------------------------------------

## 9. Motion & Micro-interactions

Motion should be subtle, purposeful, and reduced when the user prefers
reduced motion.

-   Button feedback: brief color/scale or elevation change.
-   Add to cart: concise confirmation and cart count update.
-   Page transitions: simple fade/slide only if it does not delay task
    completion.
-   POS incoming order: non-blocking visual highlight and optional
    approved sound setting.
-   Status update: clearly animate or announce the change without moving
    the whole layout unexpectedly.
-   Loading: use skeletons for content and spinners for short actions.
-   Respect `prefers-reduced-motion`.
-   Avoid parallax, looping decorative animation, and long transitions
    in operational modules.

------------------------------------------------------------------------

## 10. Accessibility & Usability

Target WCAG 2.2 AA as a design objective, subject to testing.

-   Ensure sufficient text/background contrast, especially for lime and
    blue combinations.
-   Use visible keyboard focus states.
-   Support keyboard navigation for menus, dialogs, forms, and POS
    actions.
-   Use semantic headings, labels, buttons, and form controls.
-   Give meaningful alt text to informative product and cafe imagery;
    mark decorative imagery appropriately.
-   Do not convey order/payment/stock status by color alone.
-   Maintain adequate touch target sizes, especially for customer
    ordering and tablet POS.
-   Announce important order status changes to assistive technology
    where supported.
-   Ensure errors are described in text and associated with relevant
    inputs.
-   Test at 200% zoom and narrow viewport widths.
-   Respect reduced-motion preferences.

------------------------------------------------------------------------

## 11. Content & Microcopy

Tone: warm, direct, contemporary, and helpful. Avoid overusing slang or
jokes in payment and operational flows.

Examples: - Landing CTA: "Explore Menu" - QR welcome: "Welcome to
SICASA" - Table context: "Your table: 08" - Add action: "Add to cart" -
Cart CTA: "Review order" - Checkout action: "Send order" - Pending
payment: "Waiting for payment verification" - Processing: "Your order is
being prepared" - Completed: "Your order is ready / completed" (choose
wording aligned with the cafe's actual handoff process) - Stock
unavailable: "Sold out" - Print error: "Receipt could not be printed.
Retry printing."

Use Indonesian or bilingual content consistently based on the cafe's
preferred customer language. Confirm final copy with the client.

------------------------------------------------------------------------

## 12. Empty, Loading, Error & Edge States

Every major screen must be designed for:

-   **Loading:** skeleton or progress indicator; preserve layout
    stability.
-   **Empty:** explain what is absent and provide the next useful
    action.
-   **Network interruption:** show connection issue and safe retry;
    avoid duplicate orders.
-   **Invalid QR/table:** explain that the table could not be identified
    and direct the customer to staff.
-   **Unavailable item:** disable ordering and show sold-out label.
-   **Cart changed:** inform customer if an item becomes unavailable
    before checkout and require review.
-   **Duplicate submit:** prevent or safely resolve repeated order
    submission.
-   **Payment mismatch:** preserve pending/exception state and show
    cashier next steps.
-   **Printer offline/failure:** keep transaction data, expose retry,
    and avoid re-verifying payment.
-   **No analytics data:** show selected range and explain that no
    records are available.
-   **Insufficient sample:** avoid over-interpreting best/low menu
    performance.
-   **Session expiry:** provide a safe re-authentication path for staff.
-   **Unauthorized access:** show an access-denied state without
    exposing sensitive data.

------------------------------------------------------------------------

## 13. Security & Privacy UX

-   Customer should not need to create an account or install an app for
    QR ordering, as specified in the PRD.
-   Collect only the customer data required for the order (currently
    name and any approved operational details).
-   Do not expose other customers' orders or personal details through a
    table QR link.
-   Order status access should use a secure, non-guessable order
    reference/token or another approved mechanism.
-   Staff and owner pages require authentication.
-   Hide sensitive operational details from unauthorized roles.
-   Avoid displaying credentials, payment secrets, or internal
    identifiers.
-   Explain data handling only using a client-approved privacy notice.
-   Security implementation, authorization, validation, and audit
    logging must be defined in technical requirements; UI alone does not
    provide security.

------------------------------------------------------------------------

## 14. Technical & Hardware-Aware UI Notes

The PRD proposes Laravel backend, Blade/HTML5/Tailwind/JavaScript for
customer web, Flutter or Web POS, MySQL/PostgreSQL, and ESC/POS thermal
printing over USB/LAN/Bluetooth.

Design requirements: - Maintain shared design tokens across Laravel
Blade and a separate Flutter/Web POS if that architecture is retained. -
If Flutter POS is used, recreate the same component behavior and visual
tokens using Flutter-native components; do not expect Blade components
to transfer directly. - Validate thermal printer connection method and
browser/OS support with a technical proof of concept before finalizing
print controls. - Provide explicit printer states and manual
retry/fallback instructions approved by the cafe. - Use loading and
connection indicators for real-time order delivery. - If real-time
updates rely on polling rather than WebSockets, reflect actual refresh
behavior in UI. - Support a PWA experience for customer ordering if
included in implementation; native mobile applications remain out of
scope. - Ensure responsive behavior works in modern mobile browsers and
the cashier's target devices.

------------------------------------------------------------------------

## 15. Data & Metric Definitions to Confirm

The interface depends on clear definitions. Confirm these with the cafe
before implementation:

  -----------------------------------------------------------------------
  Item                                Decision required
  ----------------------------------- -----------------------------------
  Omzet                               Gross sales, net sales, or verified
                                      order total; define discounts/fees
                                      treatment

  Transaction count                   Whether cancelled, unpaid, or
                                      exceptional orders are excluded

  Average transaction                 Formula and eligible transactions

  Best seller                         Rank by quantity sold or sales
                                      amount

  Low performing menu                 Metric, period, minimum sample, and
                                      whether new items are excluded

  Peak hours                          Order count, revenue, or both; hour
                                      grouping and selected time zone

  Pay-First                           Exact payment instructions and
                                      verification procedure for Cash and
                                      QRIS

  Order lifecycle                     Exact state transitions and who may
                                      change each state

  Table lifecycle                     When table becomes occupied,
                                      available, or manually released

  Stock toggle                        Availability-only toggle versus
                                      actual inventory quantity

  Receipt behavior                    Whether printing occurs after
                                      payment verification, transaction
                                      save, or both

  Real-time                           Update mechanism and expected
                                      refresh latency

  Permissions                         Actions available to cashier,
                                      staff, kitchen, and owner

  Refund/cancel                       Whether supported; approval rules
                                      and analytics treatment

  Fees                                Whether tax/service charge exists
                                      and how it is displayed
  -----------------------------------------------------------------------

Do not fabricate these values or policies in the interface. Use
configurable content or placeholders until confirmed.

------------------------------------------------------------------------

## 16. Out of Scope

The design must not imply the product includes: - GoFood, GrabFood, or
other third-party delivery integrations. - Native Android or iOS
applications. - Accounting or bookkeeping system. - Payroll
management. - Loyalty, membership, or rewards. - Ingredient-level
inventory unless added through an approved scope change. - Automated
payment gateway verification unless separately specified and
implemented. - Customer accounts or mandatory app installation.

Any additional functionality requires explicit scope approval and
corresponding PRD updates.

------------------------------------------------------------------------

## 17. Quality Checklist

### Brand & visual consistency

-   [ ] Logo is not distorted and has sufficient clear space.
-   [ ] Lime and blue are used consistently and accessibly.
-   [ ] Typography follows the two-family system.
-   [ ] Shape language is appropriate to the module.
-   [ ] Layouts avoid unnecessary decoration and visual clutter.

### Customer QR ordering

-   [ ] Table context is visible and correct.
-   [ ] Customer can browse, customize, edit cart, and checkout on
    mobile.
-   [ ] Price and option changes are clear.
-   [ ] Sold-out products cannot be ordered.
-   [ ] Payment verification is not falsely implied.
-   [ ] Submission is protected against accidental duplicates.
-   [ ] Status is understandable and accessible.

### POS

-   [ ] Incoming orders are easy to identify and process.
-   [ ] Payment verification is a deliberate action.
-   [ ] Payment and preparation statuses are distinct.
-   [ ] Receipt printing has loading, success, failure, and retry
    states.
-   [ ] Print retry does not duplicate the transaction.
-   [ ] Table and availability states are clear.
-   [ ] Core actions work with keyboard and touch.

### Owner analytics

-   [ ] Reporting period is always visible.
-   [ ] KPI definitions are documented.
-   [ ] Charts have labels, units, and accessible equivalents.
-   [ ] Filters update related metrics consistently.
-   [ ] Empty and insufficient-data states are designed.
-   [ ] "Real-time" claims match actual update behavior.

### Responsive, accessibility & resilience

-   [ ] Screens work at mobile, tablet, desktop, and wide desktop
    widths.
-   [ ] No unintended horizontal overflow.
-   [ ] Focus states, labels, and error messages are present.
-   [ ] Color is not the only carrier of meaning.
-   [ ] Loading, empty, offline, and failure states are covered.
-   [ ] Reduced motion is respected.

------------------------------------------------------------------------

## 18. Suggested Design Deliverables

1.  Brand token sheet (colors, typography, spacing, radii, shadows, icon
    rules).
2.  Landing page desktop and mobile designs.
3.  Customer QR ordering mobile flow: QR entry, name, catalog, product
    detail, cart, checkout, status.
4.  POS desktop/tablet designs: login, incoming orders, order detail,
    payment verification, printing, tables, menu availability,
    transaction history.
5.  Owner dashboard desktop/tablet designs: overview, sales analytics,
    peak hours, menu performance, transaction detail.
6.  Shared component library with variants and states.
7.  Responsive behavior specifications.
8.  Clickable prototype covering customer-to-cashier-to-owner core
    journey.
9.  Usability review with cafe staff and representative customers.
10. Developer handoff including interaction rules, empty/error states,
    and metric definitions.

------------------------------------------------------------------------

## 19. Final Design Direction

SICASA should feel **editorial and expressive when introducing the cafe,
playful and intuitive when customers order, and structured and
dependable when staff operate the system**.

The recommended visual balance is: - **Landing page:** Modern Editorial
Utility. - **Customer QR ordering:** Playful Minimalism. - **Cashier
POS:** Swiss Modernism / Utility-First. - **Owner analytics:**
Editorial + Swiss Utility.

Keep the same lime, deep-blue, typography, iconography, and spacing
foundations throughout. Vary layout density and visual expression by
user task, not by creating unrelated designs for each module.

**Core principle:** distinctive enough to feel like SICASA; clear enough
to work during a busy cafe shift.
