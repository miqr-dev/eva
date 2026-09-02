<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import { store as submitEvaluation } from '@/routes/evaluation/form';
import { create as enterTan } from '@/routes/evaluation/tan';

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
    scale_labels: (string | null)[] | null;
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
    description: string | null;
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

type IntroStep = {
    kind: 'intro';
    key: string;
    module: EvaluationModule;
};

type QuestionStep = {
    kind: 'question';
    key: string;
    module: EvaluationModule;
    section: EvaluationSection;
    question: EvaluationQuestion;
};

type Step = IntroStep | QuestionStep;

const props = defineProps<{
    session: string | null;
    preview?: boolean;
    form: EvaluationForm;
}>();

const responseForm = useForm<{
    language: string;
    answers: Record<string, AnswerValue>;
}>({
    language: props.form.questionnaire.default_language || 'de',
    answers: initialAnswers(),
});

const steps = computed<Step[]>(() => {
    const result: Step[] = [];

    for (const module of props.form.modules) {
        const moduleKey = `${module.id}-${module.target?.id ?? 'once'}`;

        if (module.description) {
            result.push({ kind: 'intro', key: `${moduleKey}-intro`, module });
        }

        for (const section of module.sections) {
            for (const question of section.questions) {
                result.push({
                    kind: 'question',
                    key: question.answer_key,
                    module,
                    section,
                    question,
                });
            }
        }
    }

    return result;
});

const currentStepIndex = ref(0);
const currentStepError = ref<string | null>(null);

const currentStep = computed<Step | null>(
    () => steps.value[currentStepIndex.value] ?? null,
);

const isLastStep = computed(
    () => currentStepIndex.value === steps.value.length - 1,
);

const questionSteps = computed(() =>
    steps.value.filter((step): step is QuestionStep => step.kind === 'question'),
);

const currentQuestionPosition = computed(() => {
    if (currentStep.value?.kind !== 'question') {
        return null;
    }

    return (
        questionSteps.value.findIndex(
            (step) => step.key === currentStep.value?.key,
        ) + 1
    );
});

const progressPercent = computed(() => {
    if (steps.value.length === 0) {
        return 0;
    }

    return Math.round(
        ((currentStepIndex.value + 1) / steps.value.length) * 100,
    );
});

function isAnswerEmpty(value: AnswerValue): boolean {
    return value === null || value === '';
}

function goBack(): void {
    currentStepError.value = null;

    if (currentStepIndex.value > 0) {
        currentStepIndex.value -= 1;
    }
}

function goNext(): void {
    currentStepError.value = null;

    if (currentStep.value?.kind === 'question') {
        const question = currentStep.value.question;
        const answer = responseForm.answers[question.answer_key];

        if (question.is_required && isAnswerEmpty(answer)) {
            currentStepError.value = 'Diese Frage ist ein Pflichtfeld.';

            return;
        }
    }

    if (isLastStep.value) {
        submit();

        return;
    }

    currentStepIndex.value += 1;
}

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

function scaleLabelFor(
    question: EvaluationQuestion,
    value: number,
): string | null {
    const minimum = question.scale_min ?? 1;
    const index = value - minimum;

    return question.scale_labels?.[index] ?? null;
}

function errorFor(answerKey: string): string | null {
    const errors = responseForm.errors as Record<string, unknown>;
    const error = errors[`answers.${answerKey}`];

    if (!error) {
        return null;
    }

    return Array.isArray(error) ? String(error[0]) : String(error);
}

const sessionError = computed<string | null>(() => {
    const errors = responseForm.errors as Record<string, unknown>;
    const error = errors.tan_code;

    if (!error) {
        return null;
    }

    return Array.isArray(error) ? String(error[0]) : String(error);
});

const hasUnansweredRequiredQuestions = computed(
    () => !sessionError.value && responseForm.hasErrors,
);

function submit(): void {
    if (props.preview || !props.session) {
        return;
    }

    responseForm.submit(submitEvaluation(props.session));
}

const nextButtonLabel = computed(() => {
    if (!isLastStep.value) {
        return 'Weiter';
    }

    if (props.preview) {
        return 'Vorschau (kein Absenden möglich)';
    }

    return responseForm.processing
        ? 'Antworten werden gespeichert...'
        : 'Evaluation absenden';
});
</script>

<template>
    <Head :title="props.form.campaign.title" />

    <main class="min-h-screen bg-gray-100 px-5 py-8 text-slate-900">
        <div class="mx-auto max-w-4xl">
            <div
                v-if="props.preview"
                class="mb-4 flex items-center justify-between gap-4 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-3 text-sm font-medium text-amber-800"
            >
                <span>
                    Vorschau-Modus &ndash; so sieht die Evaluation für
                    Teilnehmende aus. Antworten werden nicht gespeichert.
                </span>
            </div>

            <header class="rounded-2xl bg-teal-800 p-7 text-white shadow-sm">
                <p
                    class="text-xs font-semibold tracking-[0.22em] text-teal-300 uppercase"
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

            <div
                v-if="sessionError"
                class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-800"
            >
                <p class="font-semibold">
                    Ihre Antworten konnten nicht gespeichert werden.
                </p>
                <p class="mt-1 leading-6">{{ sessionError }}</p>
                <Link
                    :href="enterTan()"
                    class="mt-3 inline-flex h-10 items-center rounded-lg bg-red-600 px-4 text-sm font-semibold text-white transition hover:bg-red-700"
                >
                    Zur TAN-Eingabe zurückkehren
                </Link>
            </div>

            <div
                v-else-if="hasUnansweredRequiredQuestions"
                class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-800"
            >
                <p class="font-semibold">
                    Ihre Antworten konnten nicht gespeichert werden.
                </p>
                <p class="mt-1 leading-6">
                    Bitte prüfen Sie die rot markierten Pflichtfragen weiter
                    unten und antworten Sie erneut.
                </p>
            </div>

            <div
                v-if="steps.length === 0"
                class="mt-6 rounded-3xl border border-slate-200 bg-white p-10 text-center text-slate-500"
            >
                Dieser Fragebogen enthält aktuell keine Fragen.
            </div>

            <form v-else class="mt-6" @submit.prevent="goNext">
                <div
                    class="flex items-center justify-between text-xs font-medium text-slate-500"
                >
                    <span>
                        {{
                            currentQuestionPosition
                                ? `Frage ${currentQuestionPosition} von ${questionSteps.length}`
                                : 'Einleitung'
                        }}
                    </span>
                    <span>Schritt {{ currentStepIndex + 1 }} von {{ steps.length }}</span>
                </div>
                <div
                    class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-200"
                >
                    <div
                        class="h-full rounded-full bg-teal-600 transition-all duration-300"
                        :style="{ width: `${progressPercent}%` }"
                    />
                </div>

                <section
                    v-if="currentStep"
                    :key="currentStep.key"
                    class="mt-5 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
                >
                    <div v-if="currentStep.kind === 'intro'" class="px-6 py-8">
                        <p
                            class="text-xs font-semibold tracking-[0.18em] text-teal-600 uppercase"
                        >
                            Modul
                        </p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-900">
                            {{ currentStep.module.title }}
                            <span v-if="currentStep.module.target">
                                - {{ currentStep.module.target.label }}
                            </span>
                        </h2>
                        <p
                            class="mt-4 rounded-xl bg-teal-50 px-4 py-3 text-sm leading-6 text-teal-900"
                        >
                            {{ currentStep.module.description }}
                        </p>
                    </div>

                    <div v-else class="px-6 py-6">
                        <p
                            class="text-xs font-semibold tracking-[0.18em] text-teal-600 uppercase"
                        >
                            Modul
                        </p>
                        <h2 class="mt-1 text-lg font-semibold text-slate-900">
                            {{ currentStep.module.title }}
                            <span v-if="currentStep.module.target">
                                - {{ currentStep.module.target.label }}
                            </span>
                        </h2>
                        <p
                            class="mt-3 text-xs font-semibold tracking-[0.18em] text-slate-400 uppercase"
                        >
                            Abschnitt
                        </p>
                        <h3 class="mt-1 font-semibold text-slate-900">
                            {{ currentStep.section.title }}
                        </h3>
                        <p
                            v-if="currentStep.section.description"
                            class="mt-1 text-sm leading-6 text-slate-500"
                        >
                            {{ currentStep.section.description }}
                        </p>

                        <article
                            class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4"
                        >
                            <div
                                class="flex items-start justify-between gap-4"
                            >
                                <p
                                    class="leading-6 font-medium text-slate-900"
                                >
                                    {{ currentStep.question.question_text }}
                                </p>
                                <span
                                    v-if="currentStep.question.is_required"
                                    class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600"
                                >
                                    Pflicht
                                </span>
                            </div>

                            <div
                                v-if="
                                    currentStep.question.question_type ===
                                    'scale'
                                "
                                class="mt-4"
                            >
                                <div class="flex items-stretch gap-2">
                                    <label
                                        v-for="value in scaleValues(
                                            currentStep.question,
                                        )"
                                        :key="value"
                                        class="flex flex-1 cursor-pointer flex-col items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-center text-sm font-semibold"
                                    >
                                        <span
                                            v-if="
                                                scaleLabelFor(
                                                    currentStep.question,
                                                    value,
                                                )
                                            "
                                            class="text-xs font-medium text-slate-500"
                                        >
                                            {{
                                                scaleLabelFor(
                                                    currentStep.question,
                                                    value,
                                                )
                                            }}
                                        </span>
                                        <input
                                            v-model="
                                                responseForm.answers[
                                                    currentStep.question
                                                        .answer_key
                                                ]
                                            "
                                            type="radio"
                                            :name="
                                                currentStep.question.answer_key
                                            "
                                            :value="value"
                                            class="h-4 w-4 text-teal-600 focus:ring-teal-500"
                                        />
                                    </label>
                                </div>
                            </div>

                            <textarea
                                v-else-if="
                                    currentStep.question.question_type ===
                                    'free_text'
                                "
                                v-model="
                                    responseForm.answers[
                                        currentStep.question.answer_key
                                    ]
                                "
                                rows="4"
                                class="mt-4 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-100"
                            />

                            <div
                                v-else-if="
                                    currentStep.question.question_type ===
                                    'yes_no'
                                "
                                class="mt-4 flex flex-wrap gap-3"
                            >
                                <label
                                    class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold"
                                >
                                    <input
                                        v-model="
                                            responseForm.answers[
                                                currentStep.question.answer_key
                                            ]
                                        "
                                        type="radio"
                                        :name="
                                            currentStep.question.answer_key
                                        "
                                        value="yes"
                                        class="text-teal-600 focus:ring-teal-500"
                                    />
                                    Ja
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold"
                                >
                                    <input
                                        v-model="
                                            responseForm.answers[
                                                currentStep.question.answer_key
                                            ]
                                        "
                                        type="radio"
                                        :name="
                                            currentStep.question.answer_key
                                        "
                                        value="no"
                                        class="text-teal-600 focus:ring-teal-500"
                                    />
                                    Nein
                                </label>
                            </div>

                            <div v-else class="mt-4 grid gap-2">
                                <label
                                    v-for="option in currentStep.question
                                        .options"
                                    :key="option.id"
                                    class="flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold"
                                >
                                    <input
                                        v-model="
                                            responseForm.answers[
                                                currentStep.question.answer_key
                                            ]
                                        "
                                        type="radio"
                                        :name="
                                            currentStep.question.answer_key
                                        "
                                        :value="option.id"
                                        class="text-teal-600 focus:ring-teal-500"
                                    />
                                    {{ option.label }}
                                </label>
                            </div>

                            <p
                                v-if="currentStepError"
                                class="mt-3 text-sm font-medium text-red-600"
                            >
                                {{ currentStepError }}
                            </p>
                            <p
                                v-else-if="
                                    errorFor(currentStep.question.answer_key)
                                "
                                class="mt-3 text-sm font-medium text-red-600"
                            >
                                {{ errorFor(currentStep.question.answer_key) }}
                            </p>
                        </article>
                    </div>
                </section>

                <div
                    class="sticky bottom-4 mt-5 flex items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-xl backdrop-blur"
                >
                    <button
                        v-if="currentStepIndex > 0"
                        type="button"
                        class="h-12 rounded-lg border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                        @click="goBack"
                    >
                        Zurück
                    </button>
                    <span v-else />
                    <button
                        type="submit"
                        :disabled="
                            responseForm.processing ||
                            (isLastStep && props.preview)
                        "
                        class="h-12 flex-1 rounded-lg bg-teal-700 px-5 text-sm font-semibold text-white transition hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{ nextButtonLabel }}
                    </button>
                </div>
            </form>
        </div>
    </main>
</template>
