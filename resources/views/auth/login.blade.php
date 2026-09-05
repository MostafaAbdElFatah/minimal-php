<x-layout title="Sign in to IdeaHub" :show-nav="false">
    <div class="relative isolate flex min-h-screen items-center justify-center overflow-hidden px-5 py-12">
        <div class="pointer-events-none absolute -left-24 top-10 h-72 w-72 rounded-full bg-primary/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-secondary/10 blur-3xl"></div>

        <div class="relative w-full max-w-md">
            <div class="mb-8 text-center">
                <a href="/" class="mb-8 inline-flex items-center gap-3 text-lg font-bold tracking-tight text-base-content">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-primary text-xs font-black text-primary-content shadow-lg shadow-primary/20">IH</span>
                    IdeaHub
                </a>
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.22em] text-primary">Welcome back</p>
                <h1 class="text-3xl font-semibold tracking-tight text-base-content">Pick up where you left off.</h1>
                <p class="mt-3 text-sm leading-6 text-base-content/60">Sign in to keep shaping your best ideas.</p>
            </div>

            <form action="/login" method="POST" class="rounded-3xl border border-base-300/70 bg-base-200/80 p-6 shadow-2xl shadow-black/20 backdrop-blur sm:p-8">
                @csrf
                <div class="space-y-5">
                    <x-auth.email />
                    <x-auth.password />
                </div>
                <div class="mt-4 flex justify-end">
                    <a href="#" class="text-xs font-semibold text-primary transition hover:text-primary/70">Forgot password?</a>
                </div>
                <button type="submit" class="mt-6 h-12 w-full rounded-xl bg-primary text-sm font-semibold text-primary-content shadow-lg shadow-primary/20 transition hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-primary/25">Sign in</button>
                <p class="mt-6 text-center text-sm text-base-content/60">
                    New to IdeaHub?
                    <a href="/register" class="font-semibold text-primary hover:underline">Create an account</a>
                </p>
            </form>
        </div>
    </div>
</x-layout>
