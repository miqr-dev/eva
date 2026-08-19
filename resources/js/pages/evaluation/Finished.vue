<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import AppIcon from '@/components/AppIcon.vue';
import { create as enterTan } from '@/routes/evaluation/tan';
</script>

<template>
    <Head title="Evaluation abgeschlossen" />

    <main
        class="flex min-h-screen items-center justify-center bg-gray-100 px-5 py-10"
    >
        <div class="w-full max-w-md">
            <div class="mb-8 flex items-center justify-center gap-2.5">
                <span
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-teal-600 text-white"
                >
                    <AppIcon name="ring" class="h-4.5 w-4.5" />
                </span>
                <span
                    class="text-xl font-semibold tracking-tight text-slate-900"
                >
                    eva
                </span>
            </div>

            <section
                class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm"
            >
                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-teal-50 text-teal-600"
                >
                    <AppIcon name="calendar-check" class="h-7 w-7" />
                </div>
                <h1
                    class="mt-5 text-2xl font-semibold tracking-tight text-slate-900"
                >
                    Vielen Dank
                </h1>
                <p class="mt-3 text-sm leading-6 text-slate-500">
                    Ihre Antworten wurden anonym gespeichert. Die TAN kann nicht
                    erneut verwendet werden.
                </p>
                <Link
                    :href="enterTan()"
                    class="mt-7 inline-flex h-11 items-center rounded-lg border border-slate-300 px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Weitere TAN eingeben
                </Link>
            </section>
        </div>
    </main>
</template>
