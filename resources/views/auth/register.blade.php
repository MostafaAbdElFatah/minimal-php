<x-layout title="Create your IdeaHub account" :show-nav="false">
    <div class="relative isolate flex min-h-screen items-center justify-center overflow-hidden px-5 py-12">
        <div class="pointer-events-none absolute -left-24 top-10 h-72 w-72 rounded-full bg-secondary/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-primary/15 blur-3xl"></div>

        <div class="relative w-full max-w-md">
            <div class="mb-8 text-center">
                <a href="/" class="mb-8 inline-flex items-center gap-3 text-lg font-bold tracking-tight text-base-content">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-primary text-xs font-black text-primary-content shadow-lg shadow-primary/20">IH</span>
                    IdeaHub
                </a>
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.22em] text-primary">Start creating</p>
                <h1 class="text-3xl font-semibold tracking-tight text-base-content">Make room for better ideas.</h1>
                <p class="mt-3 text-sm leading-6 text-base-content/60">Create your account and give every idea a place to grow.</p>
            </div>

            <form action="/register" method="POST" class="rounded-3xl border border-base-300/70 bg-base-200/80 p-6 shadow-2xl shadow-black/20 backdrop-blur sm:p-8">
                @csrf
                <div class="space-y-5">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-auth.name name="first_name" label="First name" autocomplete="given-name" />
                        <x-auth.name name="last_name" label="Last name" autocomplete="family-name" />
                    </div>
                    <x-auth.email />
                    <x-auth.password name="password" label="Password" autocomplete="new-password" :show-strength="true" />
                    <x-auth.password name="password_confirmation" label="Confirm password" autocomplete="new-password" />
                </div>
                <button type="submit" class="mt-6 h-12 w-full rounded-xl bg-primary text-sm font-semibold text-primary-content shadow-lg shadow-primary/20 transition hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-primary/25">Create account</button>
                <p class="mt-6 text-center text-sm text-base-content/60">
                    Already have an account?
                    <a href="/login" class="font-semibold text-primary hover:underline">Sign in</a>
                </p>
            </form>
        </div>
    </div>
</x-layout>
