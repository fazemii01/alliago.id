# Airlabs API Migration + Airline Logo Rendering

## Problem

Flight search on alliago.id returns incomplete results due to data gaps in the current API provider. Dev team observed missing airline/route data. Users also have no visual identifier for airlines — logos are absent — making results harder to scan and compare.

## Evidence

- Dev team observed data gaps in current provider's flight search results (missing airlines/routes)
- No airline logo endpoint available in current provider

## Users

- **Primary**: End users searching for flights on alliago.id — people comparing tickets and needing accurate, complete route data with clear airline identification
- **Secondary**: Dev team — maintaining a reliable, maintainable API integration
- **Not for**: Hotel/car rental consumers, admin/back-office users

## Hypothesis

We believe **migrating flight search to Airlabs and rendering airline logos on each ticket result** will **eliminate data gaps and improve airline identification** for **flight-searching end users**.  
We'll know we're right when **all major airlines show logos in search results and dev team no longer observes missing route/airline data**.

## Success Metrics

| Metric | Target | How measured |
|---|---|---|
| Airline logo coverage | ≥ 95% of results show a logo | QA pass on top 50 routes |
| Data gap incidents | 0 missing airline reports post-launch | Dev monitoring / user reports |
| Search result accuracy | Matches Airlabs docs sample data | Manual spot-check |

## Scope

**MVP** — Replace current flight API provider with Airlabs; render airline logo image alongside each ticket in search results using Airlabs logo data.

**Out of scope**
- Changing search UI layout — logo added inline, no structural redesign
- Booking/payment flow — search results only
- Hotel/car rental APIs — flight search only
- Performance optimization — not a perf task, purely API swap + logo render

## Delivery Milestones

| # | Milestone | Outcome | Status | Plan |
|---|---|---|---|---|
| 1 | Airlabs API integration | Flight search data sourced from Airlabs; current provider removed | in-progress | `.claude/plans/airlabs-migration-airline-logos.plan.md` |
| 2 | Airline logo rendering | Each ticket result displays correct airline logo from Airlabs | in-progress | `.claude/plans/airlabs-migration-airline-logos.plan.md` |
| 3 | Fallback handling | Graceful fallback when logo URL is unavailable (placeholder/text) | in-progress | `.claude/plans/airlabs-migration-airline-logos.plan.md` |

## Open Questions

- [ ] Does Airlabs return logo URLs directly in the flight search response, or does it require a separate logos endpoint?
- [ ] What is the Airlabs API key / auth setup — already obtained?
- [ ] Current provider: is it safe to remove completely or should it be feature-flagged during transition?
- [ ] Logo image hosting: CDN-served by Airlabs or must we proxy/cache them?

## Risks

| Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|
| Airlabs response schema differs from current provider | High | High | Map fields before switching; run both in parallel during dev |
| Logo URLs unavailable for some airlines | Medium | Low | Render fallback (IATA code or generic icon) |
| Airlabs rate limits / quota exceeded | Low | High | Check plan limits before launch; add error monitoring |
| Breaking change in existing search UI props | Medium | Medium | Audit component contracts before migration |

---
*Status: DRAFT — requirements only. Implementation planning pending via /plan.*
