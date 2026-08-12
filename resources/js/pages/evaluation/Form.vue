<script setup lang="ts">
import { Head, useHttp } from '@inertiajs/vue3';

import { store as submitEvaluation } from '@/routes/evaluation/form';

type EvaluationOption = {
    id: number;
    label: string;
    value: string;
};

type EvaluationQuestion = {
    id: number;
    answer_key: string;
    question_text: string;
    question_type: 'scale' | 'free_text' | 'yes_no' | 'single_choice';
    scale_min: number | null;
    scale_max: number | null;
    scale_min_label: string | null;
    scale_max_label: string | null;
    is_required: boolean;
    target_id: number | null;
    options: EvaluationOption[];
};

type EvaluationSection = {
    id: number;
    title: string;
    description: string | null;
    questions: EvaluationQuestion[];
};

type EvaluationModule = {
    id: number;
    module_version_id: number;
    title: string;
    version_title: string;
    repeat_mode: string;
    target_type: string;
    target: { id: number; type: string; label: string } | null;
    sections: EvaluationSection[];
};

type EvaluationForm = {
    campaign: {
        id: number;
        title: string;
        description: string | null;
        starts_at: string | null;
        ends_at: string | null;
    };
    questionnaire: {
        id: number;
        title: string;
        version_number: number;
        default_language: string;
    };
    modules: EvaluationModule[];
};

type AnswerValue = string | number | null;

const props = defineProps<{
    session: string;
    form: EvaluationForm;
}>();

const responseForm = useHttp<
    { language: string; answers: Record<string, AnswerValue> },
    unknown
>({
    language: props.form.questionnaire.default_language || 'de',
    answers: initialAnswers(),
});

function initialAnswers(): Record<string, AnswerValue> {
    const answers: Record<string, AnswerValue> = {};

    for (const module of props.form.modules) {
        for (const section of module.sections) {
            for (const question of section.questions) {
                answers[question.answer_key] =
                    question.question_type === 'free_text' ? '' : null;
            }
        }
    }

    return answers;
}

function scaleValues(question: EvaluationQuestion): number[] {
    const minimum = question.scale_min ?? 1;
    const maximum = question.scale_max ?? 5;

    return Array.from(
        { length: Math.max(0, maximum - minimum + 1) },
        (_, index) => minimum + index,
    );
}

function errorFor(answerKey: string): string | null {
    const errors = responseForm.errors as Record<string, unknown>;
    const error = errors[`answers.${answerKey}`];

    if (!error) {
        return null;
    }

    return Array.isArray(error) ? String(error[0]) : String(error);
}

async function submit(): Promise<void> {
    await responseForm.submit(submitEvaluation(props.session));
}
</script>

<template>
    <Head :title="props.form.campaign.title" />

    <main class="min-h-screen bg-slate-100 px-5 py-8 text-slate-900">
        <div class="mx-auto max-w-4xl">
            <header class="rounded-3xl bg-slate-950 p-7 text-white shadow-xl">
                <p
                    class="text-xs font-semibold tracking-[0.22em] text-sky-300 uppercase"
                >
                    Anonyme Evaluation
                </p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight">
                    {{ props.form.campaign.title }}
                </h1>
                <p
                    v-if="props.form.campaign.description"
                    class="mt-3 max-w-3xl text-sm leading-6 text-slate-300"
                >
                    {{ props.form.campaign.description }}
                </p>
            </header>

            <form class="mt-6 space-y-6" @submit.prevent="submit">
                <section
                    v-for="module in props.form.modules"
                    :key="`${module.id}-${module.target?.id ?? 'once'}`"
                    class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
                >
                    <div class="border-b border-slate-200 px-6 py-5">
                        <p
                            class="text-xs font-semibold tracking-[0.18em] text-sky-600 uppercase"
                        >
                            Modul
                        </p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950">
                            {{ module.title }}
                            <span v-if="module.target">
                                - {{ module.target.label }}
                            </span>
                        </h2>
                    </div>

                    <div class="divide-y divide-slate-100">
                        <section
                            v-for="section in module.sections"
                            :key="section.id"
                            class="px-6 py-5"
                        >
                            <h3 class="font-semibold text-slate-950">
                                {{ section.title }}
                            </h3>
                            <p
                                v-if="section.description"
                                class="mt-1 text-sm leading-6 text-slate-500"
                            >
                                {{ section.description }}
                            </p>

                            <div class="mt-5 space-y-6">
                                <article
                                    v-for="question in section.questions"
                                    :key="question.answer_key"
                                    class="rounded-2xl border border-slate-200 bg-slate-50 p-4"
                                >
                                    <div
                                        class="flex items-start justify-between gap-4"
                                    >
                                        <p
                                            class="leading-6 font-medium text-slate-900"
                                        >
                                            {{ question.question_text }}
                                        </p>
                                        <span
                                            v-if="question.is_required"
                                            class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600"
                                        >
                                            Pflicht
                                        </span>
                                    </div>

                                    <div
                                        v-if="
                                            question.question_type === 'scale'
                                        "
                                        class="mt-4"
                                    >
                                        <div class="flex flex-wrap gap-2">
                                            <label
                                                v-for="value in scaleValues(
                                                    question,
                                                )"
                                                :key="value"
                                                class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold"
                                            >
                                                <input
                                                    v-model="
                                                        responseForm.answers[
                                                            question.answer_key
                                                        ]
                                                    "
                                                    type="radio"
                                                    :name="question.answer_key"
                                                    :value="value"
                                                    class="text-sky-600 focus:ring-sky-500"
                                                />
                                                {{ value }}
                                            </label>
                                        </div>
                                        <div
                                            class="mt-2 flex justify-between gap-4 text-xs text-slate-500"
                                        >
                                            <span>{{
                                                question.scale_min_label
                                            }}</span>
                                            <span>{{
                                                question.scale_max_label
                                            }}</span>
                                        </div>
                                    </div>

                                    <textarea
                                        v-else-if="
                                            question.question_type ===
                                            'free_text'
                                        "
                                        v-model="
                                            responseForm.answers[
                                                question.answer_key
                                            ]
                                        "
                                        rows="4"
                                        class="mt-4 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                                    />

                                    <div
                                        v-else-if="
                                            question.question_type === 'yes_no'
                                        "
                                        class="mt-4 flex flex-wrap gap-3"
                                    >
                                        <label
                                            class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold"
                                        >
                                            <input
                                                v-model="
                                                    responseForm.answers[
                                                        question.answer_key
                                                    ]
                                                "
                                                type="radio"
                                                :name="question.answer_key"
                                                value="yes"
                                                class="text-sky-600 focus:ring-sky-500"
                                            />
                                            Ja
                                        </label>
                                        <label
                                            class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold"
                                        >
                                            <input
                                                v-model="
                                                    responseForm.answers[
                                                        question.answer_key
                                                    ]
                                                "
                                                type="radio"
                                                :name="question.answer_key"
                                                value="no"
                                                class="text-sky-600 focus:ring-sky-500"
                                            />
                                            Nein
                                        </label>
                                    </div>

                                    <div v-else class="mt-4 grid gap-2">
                                        <label
                                            v-for="option in question.options"
                                            :key="option.id"
                                            class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold"
                                        >
                                            <input
                                                v-model="
                                                    responseForm.answers[
                                                        question.answer_key
                                                    ]
                                                "
                                                type="radio"
                                                :name="question.answer_key"
                                                :value="option.id"
                                                class="text-sky-600 focus:ring-sky-500"
                                            />
                                            {{ option.label }}
                                        </label>
                                    </div>

                                    <p
                                        v-if="errorFor(question.answer_key)"
                                        class="mt-3 text-sm font-medium text-red-600"
                                    >
                                        {{ errorFor(question.answer_key) }}
                                    </p>
                                </article>
                            </div>
                        </section>
                    </div>
                </section>

                <div
                    class="sticky bottom-4 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-xl backdrop-blur"
                >
                    <button
                        type="submit"
                        :disabled="responseForm.processing"
                        class="h-12 w-full rounded-xl bg-slate-950 px-5 text-sm font-semibold text-white transition hover:bg-sky-600 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{
                            responseForm.processing
                                ? 'Antworten werden gespeichert...'
                                : 'Evaluation absenden'
                        }}
                    </button>
                </div>
            </form>
        </div>
    </main>
</template>
