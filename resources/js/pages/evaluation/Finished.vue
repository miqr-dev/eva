<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import { create as enterTan } from '@/routes/evaluation/tan';
</script>

<template>
    <Head title="Evaluation abgeschlossen" />

    <main
        class="flex min-h-screen items-center justify-center bg-slate-950 px-5 py-10 text-slate-100"
    >
        <section
            class="w-full max-w-xl rounded-3xl bg-white p-8 text-center text-slate-900 shadow-2xl"
        >
            <div
                class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-2xl font-bold text-emerald-700"
            >
                ✓
            </div>
            <h1 class="mt-5 text-3xl font-semibold tracking-tight">
                Vielen Dank
            </h1>
            <p class="mt-3 text-sm leading-6 text-slate-500">
                Ihre Antworten wurden anonym gespeichert. Die TAN kann nicht
                erneut verwendet werden.
            </p>
            <Link
                :href="enterTan()"
                class="mt-7 inline-flex h-11 items-center rounded-xl border border-slate-200 px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Weitere TAN eingeben
            </Link>
        </section>
    </main>
</template>
