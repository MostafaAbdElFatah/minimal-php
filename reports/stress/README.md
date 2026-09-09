# Stress baselines

One JSON file per endpoint, written after a run against the dedicated stress
environment, e.g. `login-page.json`:

```json
{ "recorded_at": "2026-09-09", "concurrency": 50, "seconds": 10, "med": 42.1, "p95": 118.7 }
```

`composer test:stress` fails when the measured p95 exceeds the recorded one by
more than 20 %. Re-record deliberately after a performance change.
